<?php

namespace App\MS;

use App\Services\PaymentProvider\BaseProvider;
use App\Services\PaymentProvider\Flutterwave;
use App\Services\PaymentProvider\Monetbil;
use App\Services\PaymentProvider\Tranzak;
use BlitzPHP\Facades\Storage;
use BlitzPHP\Traits\SingletonTrait;
use LogicException;

/**
 * Paiement
 */
class Payment
{
	use SingletonTrait;

	const FLUTTERWAVE = 'flutterwave';
	const MONETBIL = 'monetbil';
	const TRANZAK = 'tranzak';

	private ?BaseProvider $provider = null;

	public function __construct(private string $service = self::MONETBIL)
	{
	}

	public static function service(string $service): self
	{
		return new self($service);
	}

	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array([self::instance(), $name], $arguments);
	}

	public function setLang(string $lang): self
	{
		if ($this->provider() instanceof Monetbil) {
			$this->provider()->setLang($lang);
		}

		return $this;
	}

	public function getTransactionDetails(array $transaction): array
	{
		return $this->provider()->transactionDetails($transaction);
	}

	public function check(string $id_transaction): array
	{
		return $this->provider()->check($id_transaction);
	}

	public function init(array $data): string
	{
		return $this->provider()->init($data);
	}

	public function send(array $data, bool $eum = false): array
	{
		return $this->provider()->send($data, $eum);
	}

	public function tranzakTopUpPayoutAccount($amount): array
	{
		return $this->tranzakProvider()->topUpPayoutAccount($amount);
	}

	public function tranzakAccountBalance(string $account): int
	{
		return $this->tranzakProvider()->accountBalance($account);
	}

	/**
	 * Supprime le fichier temporaire d'un paiement et renvoie le contenu qui etait present.
	 */
	public static function removeRef(string $ref): array
	{
		$data = self::getPaymentRef($ref);

		Storage::delete('payments/' . $ref);

		return $data;
	}

	/**
	 * Recupere les informations temporaires d'un paiement.
	 */
	public static function getPaymentRef(string $ref): array
	{
		$data = Storage::get('payments/' . $ref);

		if (! empty($data)) {
			$data = json_decode($data, true);
		}

		return $data ?? [];
	}

	private function provider(): BaseProvider
	{
		if ($this->provider !== null) {
			return $this->provider;
		}

		$this->provider = match ($this->service) {
			self::FLUTTERWAVE => new Flutterwave(),
			self::TRANZAK     => new Tranzak(),
			default           => new Monetbil(),
		};

		return $this->provider;
	}

	private function tranzakProvider(): Tranzak
	{
		$provider = $this->service === self::TRANZAK ? $this->provider() : new Tranzak();

		if (! $provider instanceof Tranzak) {
			throw new LogicException('Le fournisseur Tranzak n\'est pas disponible.');
		}

		return $provider;
	}
}
