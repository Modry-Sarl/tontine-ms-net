<?php

namespace App\Services\PaymentProvider;

use BlitzPHP\HttpClient\Config\Services;
use BlitzPHP\HttpClient\Request;
use RuntimeException;

class SimplePay extends BaseProvider
{
	private string $baseUrl = 'https://api.simple-pay.net';

	protected function initCollection(array $data, string $ref): string
	{
		$response = $this->request('POST', 'v1/payment/collect', [
			'amount'      => $data['amount'],
			'currency'    => 'XAF',
			'merchant_ref' => $ref,
			'return_url'  => link_to('recharge', ['service' => 'simplepay', 'merchant_ref' => $ref]),
			'cancel_url'  => link_to('recharge', ['service' => 'simplepay', 'merchant_ref' => $ref]),
			'notify_url'  => link_to('webhook.simplepay'),
			'method'      => 'mobile_money',
			'customization' => [
				'title' => config('app.name'),
				'description' => 'Recharge de compte Virmo Cash',
				'logo' => img_url('logo/logo-mini.jpg'),
				'locale' => 'fr',
				'theme' => 'light',
				'primary_color' => '#d49e1e',
			],
		]);

		if (empty($response['payment_link'])) {
			throw new RuntimeException('SimplePay n\'a pas retourné de lien de paiement.');
		}

		return $response['payment_link'];
	}

	protected function checkTransaction(string $id_transaction): array
	{
		$ref = rawurlencode($id_transaction);
		$statusResponse = $this->request('GET', 'v1/payment/transaction/' . $ref . '/status');
		$details = $this->request('GET', 'v1/payment/transaction/' . $ref . '/details');

		$transaction = array_merge($details, $statusResponse);
		$status = (int) (($statusResponse['status'] ?? '') === 'success');

		return compact('transaction', 'status');
	}

	protected function getTransactionDetails(object $td): array
	{
		$rawOperator = $td->operator ?? '';
		$operator = is_array($rawOperator)
			? ($rawOperator['code'] ?? $rawOperator['name'] ?? '')
			: (is_object($rawOperator) ? ($rawOperator->code ?? $rawOperator->name ?? '') : (string) $rawOperator);
		$status = $td->status ?? '';
		$payer = (array) ($td->payer ?? []);

		return [
			'phone'                   => $td->phone ?? $td->recipient ?? ($payer['phone'] ?? $payer['msisdn'] ?? ''),
			'indicatif'               => '',
			'country'                 => '',
			'amount'                  => $td->amount ?? 0,
			'fee'                     => $td->fees ?? $td->fee ?? 0,
			'status'                  => (int) ($status === 'success'),
			'message'                 => $td->reason ?? $td->message ?? '',
			'date'                    => $td->completed_at ?? $td->updated_at ?? $td->created_at ?? null,
			'transaction_id'          => $td->ref ?? $td->transaction_ref ?? '',
			'payment_method'          => match ($operator) {
				'orange_money' => 'OM',
				'mtn_momo'     => 'MOMO',
				default        => '',
			},
			'operator'                => $operator,
			'operator_transaction_id' => $td->operator_transaction_id ?? $td->transfer_id ?? $td->order_id ?? $td->internal_ref ?? '',
		];
	}

	protected function processSend(array $data): array
	{
		$merchantRef = $data['ref'] ?? scl_generateKeys(15, 3);
		$payload = [
			'phone'        => $this->formatPhone($data['phone']),
			'amount'       => $data['amount'],
			'currency'     => 'XAF',
			'merchant_ref' => $merchantRef,
			// 'name'         => $data['username'] ?? '',
			'description'  => 'Paiement Virmo Cash',
			'notify_url'   => link_to('webhook.simplepay'),
		];

		if (! empty($data['operator'])) {
			$payload['operator'] = $this->formatOperator($data['operator']);
		}

		$response = $this->request('POST', 'v1/payment/withdraw', $payload, true);
		$ref = $response['ref'] ?? '';

		if ($ref === '') {
			throw new RuntimeException('SimplePay n\'a pas retourné la référence du transfert.');
		}

		$status = strtolower((string) ($response['status'] ?? ''));
		$success = in_array($status, ['success', 'processing'], true);

		return [
			'success'                 => $success,
			'status'                  => $status === 'success' ? 'SUCCESSFUL' : strtoupper($status ?: 'FAILED'),
			'message'                 => $response['reason'] ?? $response['message'] ?? $status,
			'phonenumber'             => $response['recipient'] ?? $payload['phone'],
			'amount'                  => $response['amount'] ?? $payload['amount'],
			'operator'                => $response['operator'] ?? $payload['operator'] ?? '',
			'reference'               => $ref,
			'operator_transaction_id' => $response['transfer_id'] ?? $ref,
		];
	}

	private function request(string $method, string $uri, array $data = [], bool $withSecret = false): array
	{
		$client = $this->client();
		if ($withSecret) {
			$secret = env('SIMPLEPAY.SECRET_KEY');
			if (empty($secret)) {
				throw new RuntimeException('La clé secrète SimplePay n\'est pas configurée.');
			}
			$client->withHeaders(['X-Secret-Key' => $secret]);
		}

		$response = match ($method) {
			'GET'  => $client->bodyFormat('none')->get($uri, $data),
			'POST' => $client->asJson()->post($uri, $data),
			default => throw new RuntimeException('Méthode HTTP SimplePay non prise en charge.'),
		};

		$result = $response->json();
		if ($response->failed() || ! is_array($result)) {
			$message = is_array($result)
				? ($result['message'] ?? $result['error'] ?? 'Réponse invalide')
				: 'Réponse JSON invalide';
			throw new RuntimeException(sprintf(
				'Erreur SimplePay (HTTP %d) : %s',
				$response->status(),
				$message
			));
		}

		return $result;
	}

	private function client(): Request
	{
		return Services::httpclient()
			->withToken($this->getAccessToken())
			->baseUrl($this->baseUrl)
			->acceptJson();
	}

	private function getAccessToken(): string
	{
		if (! empty($token = cache()->get('simplepay_access_token'))) {
			return $token;
		}

		$appCode = env('SIMPLEPAY.APPCODE');
		$apiKey = env('SIMPLEPAY.API_KEY');

		if (empty($appCode) || empty($apiKey)) {
			throw new RuntimeException('Les identifiants SimplePay ne sont pas configurés.');
		}

		$response = Services::httpclient()
			->baseUrl($this->baseUrl)
			->asJson()
			->post('v1/auth', ['appcode' => $appCode, 'key' => $apiKey]);

		$result = $response->json();

		if ($response->failed() || ! is_array($result) || empty($result['token'])) {
			$message = is_array($result)
				? ($result['message'] ?? $result['error'] ?? 'Réponse invalide')
				: 'Réponse JSON invalide';
			throw new RuntimeException(sprintf(
				'Échec de l\'authentification SimplePay (HTTP %d) : %s',
				$response->status(),
				$message
			));
		}

		$token = $result['token'];
		cache()->set('simplepay_access_token', $token, max(1, (int) ($result['expires_in'] ?? 3600) - 60));

		return $token;
	}

	private function formatPhone(string $phone): string
	{
		$phone = preg_replace('/\D+/', '', $phone);

		return str_starts_with($phone, '237') ? $phone : '237' . $phone;
	}

	private function formatOperator(string $operator): string
	{
		return match (strtoupper($operator)) {
			'CM_ORANGEMONEY', 'ORANGEMONEY', 'ORANGE_MONEY', 'OM' => 'orange_money',
			'CM_MTNMOBILEMONEY', 'MTNMOBILEMONEY', 'MTN_MOMO', 'MTN', 'MOMO' => 'mtn_momo',
			'MOBILE_MONEY' => 'mobile_money',
			default => throw new RuntimeException('Cet opérateur n\'est pas pris en charge par SimplePay.'),
		};
	}
}
