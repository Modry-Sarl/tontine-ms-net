<?php

namespace App\Services\PaymentProvider;

use BlitzPHP\HttpClient\Config\Services;
use BlitzPHP\HttpClient\Request;

class Flutterwave extends BaseProvider
{
	private string $baseUrl = 'https://api.flutterwave.com/v3';

	protected function initCollection(array $data, string $ref): string
	{
		$response = $this->client()->post('payments', [
			'tx_ref'          => $ref,
			'amount'          => $data['amount'],
			'currency'        => 'XAF',
			'country'         => 'CM',
			'payment_options' => 'mobilemoneyfranco',
			'redirect_url'    => link_to('recharge', ['service' => 'flutterwave']),
			'meta'            => ['user_id' => $data['user'], 'fee' => $data['frais']],
			'customer'        => [
				'email'       => $data['useremail'] ?? config('mail.from.address'),
				'phonenumber' => $data['phone'],
				'name'        => $data['username'] ?? config('mail.from.name'),
			],
			'customizations' => [
				'title'       => config('app.name'),
				'description' => 'Paiement Virmo Cash',
				'logo'        => img_url('logo/logo-mini.jpg'),
			],
		])->json();

		return is_array($response) ? ($response['data']['link'] ?? '') : '';
	}

	protected function checkTransaction(string $id_transaction): array
	{
		$response = $this->client()
			->get('transactions/' . rawurlencode($id_transaction) . '/verify')
			->json();

		$transaction = is_array($response) && is_array($response['data'] ?? null)
			? $response['data']
			: [];
		$status = (int) (($transaction['status'] ?? '') === 'successful');

		return compact('transaction', 'status');
	}

	protected function getTransactionDetails(object $td): array
	{
		$customer = (array) ($td->customer ?? []);
		$meta = (array) ($td->meta ?? []);
		$network = $meta['MOMO_NETWORK'] ?? '';

		return [
			'phone'                   => $customer['phone_number'] ?? '',
			'indicatif'               => '',
			'country'                 => '',
			'amount'                  => $td->amount ?? 0,
			'fee'                     => $td->app_fee ?? 0,
			'status'                  => (int) (($td->status ?? '') === 'successful'),
			'message'                 => $td->processor_response ?? '',
			'date'                    => $td->created_at ?? null,
			'transaction_id'          => $td->flw_ref ?? '',
			'payment_method'          => match ($network) {
				'ORANGEMONEY'       => 'OM',
				'MTNMOBILEMONEY', 'MTN' => 'MOMO',
				default             => '',
			},
			'operator'                => $network,
			'operator_transaction_id' => '',
		];
	}

	protected function processSend(array $data): array
	{
		$response = $this->client()->post('transfers', [
			'account_bank'     => 'ORANGEMONEY',
			'account_number'   => '237' . $data['phone'],
			'amount'           => (int) $data['amount'],
			'currency'         => 'XAF',
			'beneficiary_name' => $data['username'] ?? '',
			'meta'             => [
				'sender'         => config('app.name'),
				'sender_country' => 'CM',
				'mobile_number'  => '237691889587',
			],
		])->json();

		return is_array($response) ? $response : [];
	}

	private function client(): Request
	{
		return Services::httpclient()
			->withToken(env('FLUTTERWAVE.SECRET_KEY'))
			->baseUrl($this->baseUrl)
			->asJson();
	}
}
