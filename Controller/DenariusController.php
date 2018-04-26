<?php
App::uses('PaymentAppController', 'Payment.Controller');

class DenariusController extends PaymentAppController {

	public $helpers = array('Tools.Numeric');

	public $uses = array('Payment.DenariusTransaction');

	public function beforeFilter() {
		parent::beforeFilter();

		# temporary
		if (isset($this->Auth)) {
			//$this->Auth->allow();
		}
	}

/****************************************************************************************
 * ADMIN functions
 ****************************************************************************************/

	/**
	 * denarius admincenter (main overview)
	 */
	public function admin_index() {
		$details = $infos = array();
		try {
			if (Configure::read('Denarius.username') && Configure::read('Denarius.password')) {
				$details = array(
					'accounts' => $this->DenariusTransaction->Denarius->listAccounts(),
					//'account' => $this->DenariusTransaction->Denarius->getAccountAddress(),
					'active' => Configure::read('Denarius.account'),
					'addresses' => $this->DenariusTransaction->Denarius->getAddressesByAccount(),
				);
				$infos = $this->DenariusTransaction->Denarius->getInfo();
			} else {
				$this->Common->flashMessage('Zugangsdaten fehlen. Kann keine Verbindung aufbauen.', 'warning');
			}
		} catch (DenariusClientException $e) {
			$this->Common->flashMessage($e->getMessage(), 'error');
		}

		if ($this->Common->isPosted()) {
			try {
				$this->DenariusTransaction->set($this->request->data);
				if ($this->DenariusTransaction->validates()) {
					$addressDetails = array();
					if (Configure::read('Denarius.username') && Configure::read('Denarius.password')) {
						$addressDetails['firstSeen'] = $this->DenariusTransaction->Denarius->addressFirstSeen($this->request->data['DenariusAddress']['address']);

					}
					$this->Common->flashMessage('Valid', 'success');
				} else {
					$this->Common->flashMessage('Invalid', 'error');
				}
			} catch (DenariusClientException $e) {
				$this->Common->flashMessage($e->getMessage(), 'error');
			}
		}

		$this->set(compact('infos', 'details'));
	}

	public function admin_address_details($address = null) {
		if (empty($address) || !($this->DenariusTransaction->set(array('address' => $address)) && $this->DenariusTransaction->validates())) {
			$this->Common->autoRedirect(array('action' => 'index'));
		}

		//TODO
	}

	public function admin_transfer() {
		$accounts = $this->DenariusTransaction->Denarius->listAccounts();
		$addresses = $this->DenariusTransaction->Denarius->getAddressesByAccount($this->DenariusTransaction->ownAccount());
		$ownAddresses = $this->DenariusTransaction->addressList($addresses);
		$ownAccounts = $this->DenariusTransaction->accountList($accounts);

		if (!empty($this->request->data) && isset($this->request->data['Denarius']['own_account_id'])) {
			$this->request->data['DenariusTransaction']['from_account'] = $this->request->data['Denarius']['own_account_id'];
			$this->DenariusTransaction->set($this->request->data);
			if ($this->DenariusTransaction->validates()) {
				$this->Session->write('Denarius.account', $this->request->data['Denarius']['own_account_id']);
				$this->Common->flashMessage('Changed', 'success');
				$this->redirect(array('action' => 'transfer'));
			} else {
				$this->DenariusTransaction->validationErrors = array();
				$this->Common->flashMessage('formContainsErrors', 'error');
			}

		} elseif (!empty($this->request->data) && isset($this->request->data['DenariusTransaction']['request'])) {
			# request
			$this->DenariusTransaction->set($this->request->data);
			if ($this->DenariusTransaction->validates()) {
				$this->Common->flashMessage('Displayed', 'success');
			} else {
				$this->Common->flashMessage('formContainsErrors', 'error');
			}

		} elseif (!empty($this->request->data) && isset($this->request->data['DenariusTransaction']['move'])) {
			# move
			if ($this->DenariusTransaction->move($this->request->data)) {
				$this->Common->flashMessage('Transfer complete', 'success');
				$this->redirect(array('action' => 'transfer'));
			} else {
				$this->Common->flashMessage('formContainsErrors', 'error');
			}

		} elseif (!empty($this->request->data) && isset($this->request->data['DenariusTransaction']['send'])) {
			# send
			try {
				if ($this->DenariusTransaction->send($this->request->data)) {
					$this->Common->flashMessage('Transfer complete', 'success');
					$this->redirect(array('action' => 'transfer'));
				} else {
					$this->Common->flashMessage('formContainsErrors', 'error');
				}
			} catch (DenariusClientException $e) {
			$this->Common->flashMessage($e->getMessage(), 'error');
		}
		}

		if (empty($this->request->data)) {
			$this->request->data['Denarius']['own_account_id'] = $this->DenariusTransaction->ownAccount();
			if ($address = $this->DenariusTransaction->ownAddress($addresses)) {
				$this->request->data['DenariusTransaction']['address'] = $address;
			} elseif ($address = $this->DenariusTransaction->Denarius->getNewAddress()) {
				$this->Common->flashMessage('New Denarius Address generated', 'info');
				$this->request->data['DenariusTransaction']['address'] = $address;
			}
		}

		$infos = $this->DenariusTransaction->Denarius->getInfo();
		$this->Common->loadHelper(array('Tools.QrCode'));
		$this->set(compact('ownAccounts', 'ownAddresses', 'infos'));
	}

	/**
	 * transaction details
	 */
	public function admin_tx($txid = null) {
		if (empty($txid) || !$this->DenariusTransaction->Denarius->validateTransaction($txid)) {
			$this->Common->flashMessage('Invalid Transaction', 'error');
			$this->redirect(array('action' => 'transfer'));
		}
		$transaction = $this->DenariusTransaction->Denarius->getTransaction($txid);
		//e5b0f6297fa6743e0c2126fe5bda7b894a95bae7aae37d2695756b68468e4732
		$this->set(compact('txid', 'transaction'));
	}

	/**
	 * address details
	 */
	public function admin_address($address = null) {
		if (empty($address) || !$this->DenariusTransaction->Denarius->validateAddress($address)) {
			$this->Common->flashMessage('Invalid Address', 'error');
			$this->redirect(array('action' => 'transfer'));
		}
	}

	public function admin_transactions($account = null) {
		if (!empty($this->request->params['named']['account'])) {
			$account = $this->request->params['named']['account'];
		}

		$transactions =	$this->DenariusTransaction->Denarius->listTransactions($account);
		$accounts = $this->DenariusTransaction->accountList();
		$this->set(compact('accounts', 'transactions'));
	}

	public function admin_fee() {
		if ($this->Common->isPosted()) {
			$this->DenariusTransaction->set($this->request->data);
			if ($this->DenariusTransaction->validates() && ($amount = $this->DenariusTransaction->data['DenariusAddress']['amount']) >= 0 && $this->DenariusTransaction->Denarius->setFee($amount)) {
				$this->Common->flashMessage('Changed', 'success');
			} else {
				$this->Common->flashMessage('formContainsErrors', 'error');
			}
		}

		$infos = $this->DenariusTransaction->Denarius->getInfo();
		$this->set(compact('infos'));
	}

	/**
	 * manually trigger the cronjobbed tasks
	 */
	public function admin_run() {
		if ($this->DenariusTransaction->update()) {
			$this->log('Tasks manually triggered and successfully completed', 'denarius');
			$this->Common->flashMessage('Tasks manually triggered and successfully completed', 'success');
		} else {
			$this->log('Tasks manually triggered but aborted', 'denarius');
		}
		$this->Common->autoRedirect(array('action' => 'index'));
	}

/****************************************************************************************
 * protected/internal functions
 ****************************************************************************************/

/****************************************************************************************
 * deprecated/test functions
 ****************************************************************************************/

}
