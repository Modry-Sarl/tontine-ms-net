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

	const FLUTTERWAVE = 'flutterwave';
	const MONETBIL = 'monetbil';

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
	
	/**
	 * Endpoint de l'api de verification de paiement flutterwave
	 */
	private string $flutterwave_endpoint_check = 'https://api.flutterwave.com/v3/transactions/%s/verify';


	private array $transaction_details = [];


	public function __construct(private string $service = 'monetbil')
	{
	}

	/**
	 * Modifie la langue du widget monetbil
	 */
	public function setLang(string $lang): self
	{
		$this->lang = (in_array(strtolower($lang), ['fr','en'])) ? $lang : 'fr';

		return $this;
	}

	public static function service(string $service): self 
	{
		return self::instance($service);
	}

	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array([self::instance(), $name], $arguments);	
	}

	
	/**
	 * @return array les details de la transaction
	 */
	public function getTransactionDetails(array $transaction): array
	{
		$td = (object) $transaction;

		return match($this->service) {
			self::FLUTTERWAVE => $this->flutterwaveTransactionDetails($td),
			default           => $this->monetbilTransactionDetails($td),
		};
	}

	/**
	 * Details de la transaction chez monetbil
	 */
	public function monetbilTransactionDetails(object $td): array
	{
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

		return $transaction_details;
	}

	/**
	 * Details de la transaction chez flutterwave
	 */
	public function flutterwaveTransactionDetails(object $td): array
	{
		$transaction_details = [
			'phone'                   => isset($td->customer) ? $td->customer['phone_number'] ?? '' : '',
			'indicatif'               => '',
			'country'                 => '',
		
			'amount'                  => $td->amount ?? 0, // $td->amount_settled ?? 0,
			'fee'                     => $td->app_fee ?? 0,
			'status'                  => isset($td->status) ? (int) ($td->status === 'status') : 0,
			'message'                 => $td->processor_response ?? '',	
			'date'                    => $td->created_at ?? null,
		
			'transaction_id'          => $td->flw_ref ?? '',
			'payment_method'          => '', 
			'operator'                => isset($td->meta) ? $td->meta['MOMO_NETWORK'] ?? '' : '',
			'operator_transaction_id' => ''
		];

		if (isset($td->meta)) {
			$transaction_details['payment_method'] = match($td->meta['MOMO_NETWORK'] ?? '') {
				                 'ORANGEMONEY' => 'OM',
				'MTNMOBILEMONEY', 'MTN'        => 'MOMO',
				                 default       => ''
			};
		}

		return $transaction_details;
	}

	
	/**
	 * Verifie le statut d'une tansaction 
	 * 
	 * @param  string $id_transaction l'id de la transaction
	 * 
	 * @return array [int status, array details]
	 */
	public function check(string $id_transaction): array
	{
		return match($this->service) {
			self::FLUTTERWAVE => $this->checkFlutterwave($id_transaction),
			default           => $this->checkMonetbil($id_transaction),
		};
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
	 * [Verification du paiement chez flutterwave]
	 * 
	 * @param  string $id_transaction l'id de la transaction
	 * 
	 * @return array [int status, array details]
	 */
	private function checkFlutterwave(string $id_transaction): array 
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL            => sprintf($this->flutterwave_endpoint_check, $id_transaction),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => ["authorization: Bearer " . env('FLUTTERWAVE.SECRET_KEY')],
		]);

		$json = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		$json        = $this->curlResponse($json, $err);
		$result      = json_decode($json, true);
		$status      = 0;
		$transaction = [];

		if (is_array($result) && array_key_exists('data', $result)) {
			$transaction = $result['data'];
			$status      = (int) ($transaction['status'] === 'successful');
		}

		return compact('transaction', 'status');
	}

	/**
	 * Initie le formulaire de paiement
	 * 
	 * @param  array  $data les donnees
	 * 
	 * @return array|string
	 */
	public function init(array $data)
	{
		if (empty($data['amount']) || !is_int($data['amount'])) {
			throw new Exception('Montant de la transaction non défini');
		}
		
		helper('assets');

		$ref = self::generateRef($data);

		return match($this->service) {
			self::FLUTTERWAVE => $this->initFlutterwave($data, $ref),
			default       => $this->initMonetbil($data, $ref),
		};
	}

	/**
	 * initialisation du formulaire de paiement chez monetbil
	 */
	private function initMonetbil(array $data, string $ref): string
	{
		$json = $this->curlInit([
			'amount'      => $data['amount'],
			// 'phone'       => $data['phone'] ?? '',
			'locale'      => $this->lang,
			'country'     => 'CM',
			'currency'    => 'XAF',
			'payment_ref' => $ref,
			'return_url'  => link_to('recharge', ['service' => self::MONETBIL]),
			'notify_url'  => link_to('payment.notify', $ref, ['service' => self::MONETBIL]),
			'cancel_url'  => link_to('recharge', ['service' => self::MONETBIL]),
			'logo'        => img_url('logo/logo-mini.jpg'),
		]);

		$result = json_decode($json, true);

		$payment_url = '';
		if (is_array($result) && array_key_exists('payment_url', $result)) {
			$payment_url = $result['payment_url'];
		}

		return $payment_url;
	}

	private function initFlutterwave(array $data, string $ref)
	{
		return [
			'public_key'      => env('FLUTTERWAVE.PUBLIC_KEY'),
			'tx_ref'          => $ref,
			'amount'          => $data['amount'],
			'currency'        => 'XAF',
			'country'         => 'CM',
			'payment_options' => 'mobilemoneyfranco',
			'redirect_url'    => url_to('recharge', ['service' => self::FLUTTERWAVE]),
			'meta'            => ['user_id' => $data['user'], 'fee' => $data['frais']],
			'customer'        => [
				'email'        => $data['useremail'] ?? config('mail.from.address'),
				'phone_number' => $data['phone'],
				'name'         => $data['username'] ?? config('mail.from.name'),
			],
			'customizations' => [
				'title' => config('app.name'),
				'description' => 'Payment for an awesome cruise',
				'logo' => img_url('logo/logo-mini.jpg'),
			],
		];
	}

	/**
	 * Envoie l'argent vers un compte mobile
	 * 
	 * @param  array  $data les donnees
	 */
	public function send(array $data, bool $eum = false): array
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

		helper('scl');

		return match($this->service) {
			self::FLUTTERWAVE => $this->sendFlutterwave($data),
			default           => $this->sendMonetbil($data),
		};
	}

	/**
	 * Envoie d'argent vers un compte mobile chez monetbil
	 */
	private function sendMonetbil(array $data): array
	{
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
	 * Envoie d'argent vers un compte mobile chez flutterwave
	 */
	private function sendFlutterwave(array $data): array
	{
		$post = [
			'account_bank'      => 'ORANGEMONEY',
			'account_number'    => '237' . $data['phone'],
			'amount'            => (int) $data['amount'],
			'currency'          => 'XAF',
			'beneficiary_name'  => $data['username'] ?? '',
			'meta'              => [
				'sender'         => config('app.name'),
				'sender_country' => 'CM',
				'mobile_number'  => '237691889587',
			],
		];

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.flutterwave.com/v3/transfers",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($post),
			CURLOPT_HTTPHEADER => [
				"authorization: Bearer " . env('FLUTTERWAVE.SECRET_KEY'),
				"content-type: application/json"
			],
		]);

		$json = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		$json = $this->curlResponse($json, $err);
		
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
		$ref = str_replace('.', '-', uniqid('vc-', true));

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