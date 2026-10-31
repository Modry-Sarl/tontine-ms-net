<?php

namespace App\Services\PaymentProvider;

use BlitzPHP\HttpClient\Config\Services;
use BlitzPHP\HttpClient\Request;

class Monetbil extends BaseProvider
{
	private string $baseUrl = 'https://api.monetbil.com';
	private string $lang = 'fr';

	public function setLang(string $lang): self
	{
		$this->lang = in_array(strtolower($lang), ['fr', 'en'], true) ? strtolower($lang) : 'fr';

		return $this;
	}

	protected function initCollection(array $data, string $ref): string
	{
		$response = $this->client()
			->asForm()
			->post('widget/v2.1/' . env('MONETBIL.PUBLIC_KEY'), [
				'amount'      => $data['amount'],
				'locale'      => $this->lang,
				'country'     => 'CM',
				'currency'    => 'XAF',
				'payment_ref' => $ref,
				'return_url'  => link_to('recharge', ['service' => 'monetbil']),
				'notify_url'  => link_to('payment.notify', $ref, ['service' => 'monetbil']),
				'cancel_url'  => link_to('recharge', ['service' => 'monetbil']),
				'logo'        => img_url('logo/logo-mini.jpg'),
			])
			->json();

		return is_array($response) ? ($response['payment_url'] ?? '') : '';
	}

	protected function checkTransaction(string $id_transaction): array
	{
		$response = $this->client()
			->asForm()
			->post('payment/v1/checkPayment', ['paymentId' => $id_transaction])
			->json();

		$transaction = is_array($response) && is_array($response['transaction'] ?? null)
			? $response['transaction']
			: [];
		$status = (int) ($transaction['status'] ?? 0);

		return compact('transaction', 'status');
	}

	protected function getTransactionDetails(object $td): array
	{
		$operator = $td->mobile_operator_code ?? '';

		return [
			'phone'                   => $td->msisdn ?? '',
			'indicatif'               => $td->country_code ?? '',
			'country'                 => $td->country_name ?? '',
			'amount'                  => $td->amount ?? 0,
			'fee'                     => $td->fee ?? 0,
			'status'                  => $td->status ?? 0,
			'message'                 => $td->message ?? '',
			'date'                    => $td->created_date ?? null,
			'transaction_id'          => $td->transaction_UUID ?? '',
			'payment_method'          => match ($operator) {
				'CM_ORANGEMONEY'    => 'OM',
				'CM_MTNMOBILEMONEY' => 'MOMO',
				'CM_EUMM'           => 'EUM',
				default             => '',
			},
			'operator'                => $operator,
			'operator_transaction_id' => $td->operator_transaction_id ?? '',
		];
	}

	protected function processSend(array $data): array
	{
		$post = [
			'service_key'       => env('MONETBIL.PUBLIC_KEY'),
			'service_secret'    => env('MONETBIL.SECRET_KEY'),
			'processing_number' => scl_generateKeys(15, 3),
			'phonenumber'       => '237' . $data['phone'],
			'amount'            => $data['amount'],
		];
		if (isset($data['operator']) && in_array($data['operator'], ['eum', 'CM_EUMM'], true)) {
			$post['operator'] = 'CM_EUMM';
		}

		$response = $this->client()
			->asForm()
			->post('v1/payouts/withdrawal', $post)
			->json();

		return is_array($response) ? $response : [];
	}

	private function client(): Request
	{
		return Services::httpclient()->baseUrl($this->baseUrl);
	}
}
