<?php

namespace App\Services\PaymentProvider;

use BlitzPHP\HttpClient\Config\Services;
use BlitzPHP\HttpClient\Request;

class Tranzak extends BaseProvider
{
    /**
	 * Base Url des api tranzak
	 */
	private string $baseUrl = 'https://dsapi.tranzak.me';

    protected function initCollection(array $data, string $ref): string
    {
        $response = $this->client()->post('xp021/v1/request/create', [
			'amount'              => $data['amount'],
			'currencyCode'        => 'XAF',
			'description'         => 'Recharge de compte Virmo Cash',
			'mchTransactionRef'   => $ref,
			'returnUrl'           => link_to('recharge', ['service' => 'tranzak', 'mchTransactionRef' => $ref]),
			'cancelUrl'           => link_to('recharge', ['service' => 'tranzak', 'mchTransactionRef' => $ref]),
			'callbackUrl'         => link_to('payment.notify', $ref, ['service' => 'tranzak']),
			'receivingEntityName' => config('app.name'),
			'customization'       => [
				'logoUrl' => img_url('logo/logo-mini.jpg'),
				'title'   => config('app.name'),
			],
		])->json();

		$payment_url = '';
		if (is_array($response) && array_key_exists('data', $response)) {
			$links = $response['data']['links'] ?? [];
			$payment_url = $links['paymentAuthUrl'] ?? '';
		}

		return $payment_url;
    }
    
    protected function checkTransaction(string $id_transaction): array
    {
        $response = $this->client()
			->get('xp021/v1/request/details', ['requestId' => $id_transaction])
			->json();

		$status      = 0;
		$transaction = [];

		if (is_array($response) && array_key_exists('data', $response)) {
			$transaction = $response['data'];
			$status      = (int) ($transaction['status'] === 'SUCCESSFUL');
		}

		if ($status == 1) {
			// la collecte s'est bien passé alors on transfert les fonds
			$this->topUpPayoutAccount($transaction['amount']);
		}

		return compact('transaction', 'status');
    }

    protected function getTransactionDetails(object $td): array
    {
		$payer = (object) ($td->payer ?? []);

        $transaction_details = [
			'phone'                   => $payer->accountId ?? '',
			'indicatif'               => '',
			'country'                 => $payer->countryCode ?? '',
		
			'amount'                  => isset($td->amount) ? $td->amount : 0,
			'fee'                     => $payer->fee ?? 0,
			'status'                  => isset($td->status) ? (int) ($td->status === 'SUCCESSFUL') : 0,
			'message'                 => isset($td->transactionStatus) ? $td->transactionStatus : '',	
			'date'                    => isset($td->createdAt) ? $td->createdAt : null,
		
			'transaction_id'          => isset($td->transactionId) ? $td->transactionId : '',
			'payment_method'          => '', 
			'operator'                => $payer->paymentMethod ?? '',
			'operator_transaction_id' => isset($td->partnerTransactionId) ? $td->partnerTransactionId : ''
		];

		$transaction_details['payment_method'] = match($payer->paymentMethod ?? '') {
			'Orange Money'      => 'OM',
			'CM_MTNMOBILEMONEY' => 'MOMO',
			'CM_EUMM'           => 'EUM',
			default             => '',
		};

		return $transaction_details;
    }

    protected function processSend(array $data): array
    {
        $data = $this->client()->post('xp021/v1/transfer/to-mobile-wallet', [
			'amount'               => $data['amount'],
			'currencyCode'         => 'XAF',
			'description'          => 'Paiement Virmo Cash',
			'payeeAccountId'       => '+237' . $data['phone'],
			'customTransactionRef' => scl_generateKeys(15, 3),
			'verifyPayeeName'      => false,
		])->json('data');

		$returner = fn(array $d) => [
			'success'                 => true,
			'message'                 => $d['status'],
			'phonenumber'             => str_replace('+', '', $d['payeeAccountId']),
			'amount'                  => $d['amount'],
			'operator'                => $d['destinationServiceName'],
			'operator_transaction_id' => $d['partnerTransactionId'] ?? '',
		];

		if ($data['status'] === 'SUCCESSFUL') {
			cache()->deleteMany(['tranzak_account_collection', 'tranzak_account_payout']);

			return $returner($data);
		}

		if ($data['status'] === 'PROCESSING') {
			$maxAttemps = 10;
			$i = 0;
			sleep(5); // perf, autant mieux patienter d'abord avant de lancer la verification

			while($i < $maxAttemps) {
				$result = $this->client()->get('xp021/v1/transfer/details', ['transferId' => $data['transferId']])->json('data');

				if ($result['status'] === 'SUCCESSFUL') {
					cache()->deleteMany(['tranzak_account_collection', 'tranzak_account_payout']);

					return $returner($result);
				}

				sleep(3);
				$i++;
			}
		}

		return ['success' => false, 'message' => 'Error for transfert'];
    }

    /**
	 * Transfert un montant du compte "collecte" vers le compte "paiement"
	 * 
	 * ceci est necessaire car Tranzak gere les collecte et les retraits dans 2 comptes differents
	 * si le compte retrait n'est pas approvisionné, les retraits ne pourront pas être fait
	 * ainsi donc, apres chaque collecte reussi, nous devons transferer le montant collecté dans le compte de paiement
	 */
	public function topUpPayoutAccount($amount): array
	{
		$response = $this->client()->post('xp021/v1/transfer/payout-account-topup', [
			'amount'               => $amount,
			'currencyCode'         => 'XAF',
			'customTransactionRef' => uniqid(more_entropy: true),
		])->json();

		cache()->deleteMany(['tranzak_account_collection', 'tranzak_account_payout']);

		return $response;
	}

	/**
	 * @param 'collection' | 'payout' $account
	 */
	public function accountBalance(string $account): int
	{
		return cache()->remember('tranzak_account_' . $account, HOUR, function () use($account) {
			$data = $this->client()->post(sprintf('xp021/v1/account/%s-account-details', $account))->json('data');

			return $data['availableBalance'];
		});
	}

	private function client(): Request
	{
		return Services::httpclient()->withToken($this->getTranzakToken())
			->baseUrl($this->baseUrl)
			->asJson();
	}

	private function getTranzakToken(): string 
	{
		if (empty($token = cache()->get('tranzak_access_token'))) {
			$request = Services::httpclient()
				->baseUrl($this->baseUrl)
				->asJson()
				->post('auth/token', [
					'appId' => env('TRANZAK_APP_ID'),
  					'appKey' => env('TRANZAK_APP_KEY'),
				]);

			$response = $request->json('data');

			$token = $response['token'];

			cache()->set('tranzak_access_token', $token, $response['expiresIn'] - 400);
		}

        return $token;
	}
}
