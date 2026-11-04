<?php

namespace App\Controllers;

use App\Entities\Retrait;
use App\Entities\Transaction;
use App\Entities\Utilisateur;
use App\MS\Constants;
use App\MS\Payment;
use BlitzPHP\Contracts\Http\StatusCode;
use Throwable;

class WebhookController extends AppController
{
    /**
     * Endpoint de réception des Webhooks SimplePay (IPN)
     * Gère : collect, order, withdrawal, whatsapp, webhook.ping
     */
    public function simplepay()
    {
        $rawPayload = (string) $this->request->getBody();
        if ($rawPayload === '') {
            $rawPayload = (string) file_get_contents('php://input');
        }

        $signature = trim($this->request->headerLine('X-SimplePay-Signature') ?: ($_SERVER['HTTP_X_SIMPLEPAY_SIGNATURE'] ?? ''));
        $event     = trim($this->request->headerLine('X-SimplePay-Event') ?: ($_SERVER['HTTP_X_SIMPLEPAY_EVENT'] ?? ''));
        $operation = trim($this->request->headerLine('X-SimplePay-Operation') ?: ($_SERVER['HTTP_X_SIMPLEPAY_OPERATION'] ?? ''));
        $timestamp = (int) ($this->request->headerLine('X-SimplePay-Timestamp') ?: ($_SERVER['HTTP_X_SIMPLEPAY_TIMESTAMP'] ?? 0));

        // 1. Vérification contre les attaques par rejeu (écart max 5 minutes = 300s)
        if ($timestamp <= 0 || abs(time() - $timestamp) > 300) {
            return $this->response->withStatus(StatusCode::UNAUTHORIZED)->json([
                'status'  => 'error',
                'message' => 'Requête expirée',
            ]);
        }

        // 2. Vérification de la signature cryptographique HMAC-SHA256
        if (! $this->verifySignature($rawPayload, $signature)) {
            return $this->response->withStatus(StatusCode::UNAUTHORIZED)->json([
                'status'  => 'error',
                'message' => 'Signature invalide',
            ]);
        }

        // 3. Décodage du payload JSON
        $payload = json_decode($rawPayload, true);
        if (! is_array($payload)) {
            return $this->response->withStatus(StatusCode::BAD_REQUEST)->json([
                'status'  => 'error',
                'message' => 'Payload JSON invalide',
            ]);
        }

        $event     = strtolower((string) ($payload['event'] ?? $event));
        $operation = strtolower((string) ($payload['operation'] ?? $operation));
        $data      = is_array($payload['data'] ?? null) ? $payload['data'] : [];

        if ($operation === '' && str_contains($event, '.')) {
            $operation = explode('.', $event, 2)[0];
        }

        try {
            match ($operation) {
                'collect', 'order' => $this->handleCollect($event, $data),
                'withdrawal'       => $this->handleWithdrawal($event, $data),
                'whatsapp',
                'webhook'          => null,
                default            => null,
            };
        } catch (Throwable $e) {
            logger()->error('[SIMPLEPAY WEBHOOK ERROR]: ' . $e->getMessage(), [
                'event'     => $event,
                'operation' => $operation,
                'trace'     => $e->getTraceAsString(),
            ]);

            return $this->response->withStatus(StatusCode::INTERNAL_ERROR)->json([
                'status'  => 'error',
                'message' => 'Erreur interne lors du traitement du webhook',
            ]);
        }

        return $this->response->withStatus(StatusCode::OK)->json([
            'status' => 'received',
        ]);
    }

    /**
     * Permet de vérifier et traiter une collecte SimplePay lors du retour sur return_url
     * si le webhook asynchrone n'est pas encore arrivé.
     */
    public function verifyAndProcessCollect(string $merchantRef, ?string $simplePayRef = null): void
    {
        if ($merchantRef === '') {
            return;
        }

        if (Transaction::where('ref', $merchantRef)->count() > 0) {
            Payment::removeRef($merchantRef);

            return;
        }

        if (empty($simplePayRef)) {
            return;
        }

        try {
            ['status' => $status, 'transaction' => $transaction] = Payment::service(Payment::SIMPLEPAY)->check($simplePayRef);
            $transaction['merchant_ref'] = $transaction['merchant_ref'] ?? $merchantRef;
            $transaction['ref']          = $transaction['ref'] ?? $simplePayRef;

            $event = $status === 1
                ? 'collect.completed'
                : (($transaction['status'] ?? '') === 'cancelled' ? 'collect.cancelled' : 'collect.failed');

            $this->handleCollect($event, $transaction);
        } catch (Throwable $e) {
            logger()->error('[SIMPLEPAY VERIFY COLLECT ERROR]: ' . $e->getMessage(), [
                'merchant_ref' => $merchantRef,
                'ref'          => $simplePayRef,
            ]);
        }
    }

    /**
     * Traitement des webhooks de collecte (collect.completed, collect.failed, collect.cancelled, order.completed, order.failed)
     */
    protected function handleCollect(string $event, array $data): void
    {
        $simplePayRef = (string) ($data['ref'] ?? $data['transaction_ref'] ?? '');
        $merchantRef  = (string) ($data['merchant_ref'] ?? '');
        $statusStr    = strtolower((string) ($data['status'] ?? ''));

        if ($event === 'collect.cancelled' || $statusStr === 'cancelled') {
            if ($merchantRef !== '') {
                Payment::removeRef($merchantRef);
            }

            return;
        }

        if ($merchantRef === '' && $simplePayRef === '') {
            return;
        }

        // Idempotence stricte : vérifier si la transaction a déjà été enregistrée
        $alreadyProcessed = Transaction::where(function ($query) use ($merchantRef, $simplePayRef) {
            if ($merchantRef !== '') {
                $query->where('ref', $merchantRef);
            }
            if ($simplePayRef !== '') {
                $method = $merchantRef !== '' ? 'orWhere' : 'where';
                $query->{$method}('reference', $simplePayRef);
            }
        })->count() > 0;

        if ($alreadyProcessed) {
            if ($merchantRef !== '') {
                Payment::removeRef($merchantRef);
            }

            return;
        }

        $payement = $merchantRef !== '' ? Payment::getPaymentRef($merchantRef) : [];
        if (empty($payement['user'])) {
            return;
        }

        $user = Utilisateur::find($payement['user']);
        if (empty($user)) {
            return;
        }

        $paymentInstance = Payment::service(Payment::SIMPLEPAY);
        $details         = $paymentInstance->getTransactionDetails($data);

        $isSuccess = in_array($event, ['collect.completed', 'order.completed'], true) || $statusStr === 'success' || $details['status'] === 1;
        $status    = $isSuccess ? 1 : 0;
        $montant   = ($payement['amount'] ?? 0) ?: ($details['amount'] ?? 0);
        $frais     = $payement['frais'] ?? $details['fee'] ?? 0;
        $message   = (string) ($details['message'] ?: ($isSuccess ? 'Paiement effectué' : 'Échec du paiement'));
        $dt        = $this->formatDateTime($details['date'] ?? $payement['date'] ?? null);

        $db = service('database');
        $db->beginTransaction();

        try {
            Transaction::create([
                'user_id'                 => $user->id,
                'numero'                  => (string) ($details['phone'] ?: ($payement['phone'] ?? '')),
                'ref'                     => $merchantRef ?: null,
                'montant'                 => to_dollar($montant, 'entree'),
                'frais'                   => to_dollar($frais, 'entree'),
                'type'                    => 'entree',
                'statut'                  => $status,
                'message'                 => mb_substr($message, 0, 25),
                'operateur'               => $details['operator'] ?? '',
                'reference'               => mb_substr($details['transaction_id'] ?: $simplePayRef, 0, 25),
                'operator_transaction_id' => mb_substr((string) ($details['operator_transaction_id'] ?: $simplePayRef), 0, 35),
                'ip'                      => $payement['ip'] ?? $this->request->clientIp(),
                'ua'                      => $payement['ua'] ?? $this->request->userAgent(),
                'dt'                      => $dt,
            ]);

            if ($status === 1) {
                $amount = to_dollar($montant - ($payement['frais'] ?? 0), 'entree');

                if (intval($amount) == Constants::MASSIVE_WITHDRAWAL_REFUND_AMOUNT && $user->ref === Constants::MASSIVE_WITHDRAWAL_REFUND_ACCOUNT) {
                    $amount -= Constants::MASSIVE_WITHDRAWAL_SUBSTRACTED_AMOUNT;

                    Utilisateur::where('ref', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->increment('solde_recharge', Constants::MASSIVE_WITHDRAWAL_ADDED_AMOUNT);
                }

                $user->increment('solde_recharge', $amount);
            }

            $db->commit();

            if ($merchantRef !== '') {
                Payment::removeRef($merchantRef);
            }
        } catch (Throwable $e) {
            $db->rollback();

            throw $e;
        }
    }

    /**
     * Traitement des webhooks de retrait / transfert (withdrawal.completed, withdrawal.failed)
     */
    protected function handleWithdrawal(string $event, array $data): void
    {
        $simplePayRef = (string) ($data['ref'] ?? '');
        $merchantRef  = (string) ($data['merchant_ref'] ?? '');
        $statusStr    = strtolower((string) ($data['status'] ?? ''));

        if ($merchantRef === '') {
            return;
        }

        $retrait = Retrait::with('user')->where('ref', $merchantRef)->first();
        if (empty($retrait) || empty($retrait->user)) {
            return;
        }

        $paymentInstance = Payment::service(Payment::SIMPLEPAY);
        $details         = $paymentInstance->getTransactionDetails($data);
        $meta            = (array) ($retrait->meta ?? []);
        $dt              = $this->formatDateTime($details['date'] ?? $meta['dt'] ?? null);
        $numero          = simple_tel((string) ($data['recipient'] ?? $details['phone'] ?? $retrait->tel));
        $montantCfa      = $data['amount'] ?? ($meta['amount_cfa'] ?? to_cfa($retrait->montant));
        $operator        = (string) ($details['operator'] ?? $data['operator'] ?? '');
        $operatorTxId    = (string) ($data['transfer_id'] ?? $details['operator_transaction_id'] ?? $meta['operator_transaction_id'] ?? $simplePayRef);

        $isSuccess = $event === 'withdrawal.completed' || $statusStr === 'success';
        $isFailed  = $event === 'withdrawal.failed' || $statusStr === 'failed';

        if (! $isSuccess && ! $isFailed) {
            return;
        }

        $db = service('database');
        $db->beginTransaction();

        try {
            if ($isSuccess) {
                // Idempotence : vérifier si la transaction de sortie a déjà été enregistrée
                $alreadyRecorded = Transaction::where('type', 'sortie')
                    ->where(function ($query) use ($retrait, $simplePayRef) {
                        $query->where('ref', $retrait->ref);
                        if ($simplePayRef !== '') {
                            $query->orWhere('reference', $simplePayRef);
                        }
                    })
                    ->count() > 0;

                if (! $alreadyRecorded) {
                    // Si le compte n'avait pas encore été débité (cas exceptionnel), on le débite
                    if (empty($meta['debited'])) {
                        $retrait->user->decrement('solde_' . $retrait->compte, $retrait->montant);
                        $meta['debited'] = true;
                    }

                    $message = (string) ($details['message'] ?: 'Transfert effectué');

                    Transaction::create([
                        'user_id'                 => $retrait->user_id,
                        'numero'                  => mb_substr($numero, 0, 20),
                        'ref'                     => $retrait->ref,
                        'montant'                 => to_dollar($montantCfa),
                        'frais'                   => 0,
                        'type'                    => 'sortie',
                        'statut'                  => 1,
                        'message'                 => mb_substr($message, 0, 25),
                        'operateur'               => mb_substr($operator, 0, 25),
                        'reference'               => mb_substr($simplePayRef ?: ($meta['payment_ref'] ?? ''), 0, 25),
                        'operator_transaction_id' => mb_substr($operatorTxId, 0, 35),
                        'ip'                      => $meta['ip'] ?? $this->request->clientIp(),
                        'ua'                      => $meta['ua'] ?? $this->request->userAgent(),
                        'dt'                      => $dt,
                    ]);
                }

                $meta['payment_ref']             = $simplePayRef ?: ($meta['payment_ref'] ?? '');
                $meta['operator_transaction_id'] = $operatorTxId;
                $meta['completed_at']            = $dt;

                $retrait->update([
                    'statut'          => 'validated',
                    'process_at'      => $retrait->process_at ?: date('Y-m-d H:i:s'),
                    'rejected_reason' => null,
                    'meta'            => $meta,
                ]);
            } elseif ($isFailed) {
                // Idempotence stricte : ne recréditer le solde qu'une seule fois si le retrait avait été débité
                $wasDebited      = ! empty($meta['debited']) || $retrait->statut === 'validated';
                $alreadyRefunded = ! empty($meta['refunded']);

                if ($wasDebited && ! $alreadyRefunded) {
                    $retrait->user->increment('solde_' . $retrait->compte, $retrait->montant);
                    $meta['debited']     = false;
                    $meta['refunded']    = true;
                    $meta['refunded_at'] = date('Y-m-d H:i:s');
                }

                $reason = (string) ($data['reason'] ?? $data['message'] ?? $details['message'] ?? 'Échec du transfert SimplePay');

                $alreadyRecorded = Transaction::where('type', 'sortie')
                    ->where(function ($query) use ($retrait, $simplePayRef) {
                        $query->where('ref', $retrait->ref);
                        if ($simplePayRef !== '') {
                            $query->orWhere('reference', $simplePayRef);
                        }
                    })
                    ->count() > 0;

                if (! $alreadyRecorded) {
                    Transaction::create([
                        'user_id'                 => $retrait->user_id,
                        'numero'                  => mb_substr($numero, 0, 20),
                        'ref'                     => $retrait->ref,
                        'montant'                 => to_dollar($montantCfa),
                        'frais'                   => 0,
                        'type'                    => 'sortie',
                        'statut'                  => 0,
                        'message'                 => mb_substr($reason, 0, 25),
                        'operateur'               => mb_substr($operator, 0, 25),
                        'reference'               => mb_substr($simplePayRef ?: ($meta['payment_ref'] ?? ''), 0, 25),
                        'operator_transaction_id' => mb_substr($operatorTxId, 0, 35),
                        'ip'                      => $meta['ip'] ?? $this->request->clientIp(),
                        'ua'                      => $meta['ua'] ?? $this->request->userAgent(),
                        'dt'                      => $dt,
                    ]);
                }

                $retrait->update([
                    'statut'          => 'rejected',
                    'process_at'      => $retrait->process_at ?: date('Y-m-d H:i:s'),
                    'rejected_reason' => mb_substr($reason, 0, 255),
                    'meta'            => $meta,
                ]);
            }

            $db->commit();
        } catch (Throwable $e) {
            $db->rollback();

            throw $e;
        }
    }

    /**
     * Vérifie la signature HMAC-SHA256 du corps brut avec la clé secrète de webhook (ou clé secrète par défaut)
     */
    private function verifySignature(string $rawPayload, string $signature): bool
    {
        if ($signature === '' || $rawPayload === '') {
            return false;
        }

        $secrets = array_values(array_filter(array_unique([
            env('SIMPLEPAY.WEBHOOK_SECRET'),
            env('SIMPLEPAY.SECRET_KEY'),
        ])));

        if (empty($secrets)) {
            return false;
        }

        foreach ($secrets as $secret) {
            $computedSignature = hash_hmac('sha256', $rawPayload, (string) $secret);
            if (hash_equals($computedSignature, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Formate une date (ex: ISO 8601 issue du webhook) au format MySQL DATETIME (Y-m-d H:i:s)
     */
    private function formatDateTime(?string $date): string
    {
        if (! empty($date)) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        }

        return date('Y-m-d H:i:s');
    }
}
