<?php

namespace App\Services\PaymentProvider;

use BlitzPHP\Facades\Storage;
use Exception;

abstract class BaseProvider
{
    abstract protected function initCollection(array $data, string $ref): string;
    
    /**
     * 
	 * @return array{status: int, transaction: array}
     */
    abstract protected function checkTransaction(string $id_transaction): array;

    abstract protected function getTransactionDetails(object $td): array;

    abstract protected function processSend(array $data): array;

    public function transactionDetails(array $transaction): array
	{
		return $this->getTransactionDetails((object) $transaction);
	}

    /**
	 * Verifie le statut d'une tansaction 
	 * 
	 * @param  string $id_transaction l'id de la transaction
	 * 
	 * @return array{status: int, transaction: array}
	 */
	public function check(string $id_transaction): array
	{
        return $this->checkTransaction($id_transaction);
	}

    /**
	 * Initie le formulaire de paiement
	 */
	public function init(array $data): string
	{
		if (empty($data['amount']) || !is_int($data['amount'])) {
			throw new Exception('Montant de la transaction non défini');
		}
		
		helper('assets');

		return $this->initCollection($data, $this->generateRef($data));
	}
    
    /**
	 * Envoie l'argent vers un compte mobile
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
        set_time_limit(0);

        return $this->processSend($data);
	}

	protected function generateRef(array $data): string
	{
		$ref = str_replace('.', '-', uniqid('vc-', true));

		Storage::put('payments/' . $ref, json_encode([
			'ref'  => $ref,
			'date' => date('Y-m-d H:i:s'),
		] + $data));

		return $ref;
	}
}
