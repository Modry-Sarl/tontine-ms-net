<?php

namespace App\MS;

use BlitzPHP\Facades\Storage;
use BlitzPHP\Traits\SingletonTrait;
use Exception;

/**
 * Paiement
 */
class Payment
{
	use SingletonTrait;

	/**
	 * Langue du widget
	 */
	private string $lang = 'fr';


	/**
	 * la date d'initialisation de la requete
	 */
	private string $date_init = '';

	/**
	 * Endpoint de l'api d'initialisation de paiement monetbil
	 */
	private string $endpoint_init = 'https://api.monetbil.com/widget/v2.1/%s';

	/**
	 * Endpoint de l'api de retrait d'argent monetbil
	 */
	private string $endpoint_retrait = 'https://api.monetbil.com/v1/payouts/withdrawal';
	
	/**
	 * Endpoint de l'api de verification de paiement monetbil
	 */
	private string $endpoint_check = 'https://api.monetbil.com/payment/v1/checkPayment';


	private array $transaction_details = [];



	/**
	 * Modifie la langue du widget monetbil
	 */
	public function setLang(string $lang): self
	{
		$this->lang = (in_array(strtolower($lang), ['fr','en'])) ? $lang : 'fr';

		return $this;
	}

	
	/**
	 * @return array les details de la transaction
	 */
	public static function getTransactionDetails(array $transaction): array
	{
		$td = (object) $transaction;

		$transaction_details = [
			'phone'                   => isset($td->msisdn) ? $td->msisdn : '',
			'indicatif'               => isset($td->country_code) ? $td->country_code : '',
			'country'                 => isset($td->country_name) ? $td->country_name : '',
		
			'amount'                  => isset($td->amount) ? $td->amount : 0,
			'fee'                     => isset($td->fee) ? $td->fee : 0,
			'status'                  => isset($td->status) ? $td->status : 0,
			'message'                 => isset($td->message) ? $td->message : '',	
			'date'                    => isset($td->created_date) ? $td->created_date : null,
		
			'transaction_id'          => isset($td->transaction_UUID) ? $td->transaction_UUID : '',
			'payment_method'          => '', 
			'operator'                => isset($td->mobile_operator_code) ? $td->mobile_operator_code : '',
			'operator_transaction_id' => isset($td->operator_transaction_id) ? $td->operator_transaction_id : ''
		];

		if (isset($td->mobile_operator_code)) {
			$transaction_details['payment_method'] = match($td->mobile_operator_code) {
				'CM_ORANGEMONEY'    => 'OM',
				'CM_MTNMOBILEMONEY' => 'MOMO',
				'CM_EUMM'           => 'EUM'
			};
		}
		
		unset($td);

		return $transaction_details;
	}
	
	/**
	 * Verifie le statut d'une tansaction 
	 * 
	 * @param  string $id_transaction l'id de la transaction
	 * 
	 * @return array [int status, array details]
	 */
	public static function check(string $id_transaction): array
	{
		return  static::instance()->checkMonetbil($id_transaction);
	}

	/**
	 * [Verification du paiement chez monetbil]
	 * 
	 * @param  string $id_transaction l'id de la transaction
	 * 
	 * @return array [int status, array details]
	 */
	private function checkMonetbil(string $id_transaction): array
	{
		$json        = $this->curlCheck(['paymentId' => $id_transaction]);
		$result      = json_decode($json, true);
		$status      = 0;
		$transaction = [];

		if (is_array($result) && array_key_exists('transaction', $result)) {
			$transaction               = $result['transaction'];
			$status                    = (int) $transaction['status'];
		}

		return compact('transaction', 'status');
	}

	/**
	 * Initie le formulaire de paiement
	 * 
	 * @param  array  $data les donnees
	 * 
	 * @return string l'url de paiement
	 */
	public static function init(array $data): string
	{
		if (empty($data['amount']) || !is_int($data['amount'])) {
			throw new Exception('Montant de la transaction non défini');
		}

		return self::instance()->initMonetbil($data);
	}

	/**
	 * initialisation du formulaire de paiement chez monetbil
	 */
	private function initMonetbil(array $data): string
	{
		helper('assets');

		$ref = self::generateRef($data);

		$json = $this->curlInit([
			'amount'      => $data['amount'],
			// 'phone'       => $data['phone'] ?? '',
			'locale'      => $this->lang,
			'country'     => 'CM',
			'currency'    => 'XAF',
			'payment_ref' => $ref,
			'return_url'  => link_to('recharge'),
			'notify_url'  => link_to('payment.notify', $ref),
			'cancel_url'  => link_to('recharge'),
			'logo'        => img_url('logo/logo-mini.jpg'),
		]);

		$result = json_decode($json, true);

		$payment_url = '';
		if (is_array($result) && array_key_exists('payment_url', $result)) {
			$payment_url = $result['payment_url'];
		}

		return $payment_url;
	}

	/**
	 * Envoie l'argent vers un compte mobile
	 * 
	 * @param  array  $data les donnees
	 */
	public static function send(array $data, bool $eum = false): array
	{
		if (empty($data['amount']) || !is_numeric($data['amount'])) {
			throw new Exception('Montant de la transaction non défini');
		}
		if (empty($data['phone'])) {
			throw new Exception('Numéro de téléphone défini');
		}

		if (true === $eum) {
			$data['operator'] = 'CM_EUMM';
		}

		return self::instance()->sendMonetbil($data);
	}

	/**
	 * Envoie d'argent vers un compte mobile chez monetbil
	 */
	private function sendMonetbil(array $data): array
	{
		helper('scl');

		$post = [
			'service_key'       => env('MONETBIL.PUBLIC_KEY'),
			'service_secret'    => env('MONETBIL.SECRET_KEY'),
			'processing_number' => scl_generateKeys(15, 3),
			'phonenumber'       => '237' . $data['phone'],
			'amount'            => $data['amount']
		];
		if (isset($data['operator']) && in_array($data['operator'], ['eum', 'CM_EUMM'])) {
			$post['operator'] = 'CM_EUMM';
		}
		
		$json = $this->curlSend($post);
		
		return json_decode($json, true);		
	}

	/**
	 * Requete de verification de paiement
	 */
	private function curlCheck(array $data): string
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->endpoint_check);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$json = curl_exec($ch); 
		$err = curl_error($ch); 
		curl_close($ch);

		return $this->curlResponse($json, $err);
	}

	/**
	 * Requete d'initialisation de paiement
	 */
	private function curlInit(array $data): string
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, sprintf($this->endpoint_init, env('MONETBIL.PUBLIC_KEY')));
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));

		$json = curl_exec($ch); 
		$err = curl_error($ch); 
		curl_close($ch);

		return $this->curlResponse($json, $err);	
	}

	/**
	 * Requete d'envoie d'argent
	 */
	private function curlSend($data)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->endpoint_retrait);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3000);

		$json = curl_exec($ch);
		$err  = curl_error($ch);
		curl_close($ch);

		return $this->curlResponse($json, $err);	
	}


	private function curlResponse(bool|string $json, string $err): string
	{
		if ($json === false) {
			throw new Exception('Un probleme est survenu lors de l\'appel du service de paiement.');
    	}
		
    	if (!empty($err)) {
			throw new Exception('Un probleme est survenu lors de l\'appel du service de paiement. :: [' . $err . ']');
    	}

		return $json;	
	}


	private static function generateRef(array $data): string
	{
		$ref = str_replace('.', '-', uniqid('ms-', true));

		Storage::put('payments/' . $ref, json_encode([
			'ref'  => $ref,
			'date' => date('Y-m-d H:i:s')
		] + $data));

		return $ref;
	}

	/**
	 * Supprime le fichier temporaire d'un paiement et renvoie le contenu qui etait present
	 */
	public static function removeRef(string $ref): array 
	{
		$data = self::getPaymentRef($ref);

		Storage::delete('payments/' . $ref);

		return $data;
	}

	/**
	 * Recupere les info temporaire d'un paiement
	 */
	public static function getPaymentRef(string $ref): array
	{
		$data = Storage::get('payments/' . $ref);

		if (!empty($data)) {
			$data = json_decode($data, true);
		}

		return $data ?? [];		
	}
}