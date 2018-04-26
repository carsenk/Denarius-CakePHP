<?php

App::import('Vendor', array('Payment.denarius/denarius'));
if (!defined('DENARIUS_CERTIFICATE')) {
	define('DENARIUS_CERTIFICATE', APP . 'Config' . DS . 'server.cert');
}

/**
 * CakePHP1.3 Wrapper for PHP Denarius Library (Vendor)
 * - adds more methods
 * - offline mode available for localhost development (does not through exceptions if not connected to denariusd daemon)
 * - automagic via configure::write()
 * - wraps the http://blockexplorer.com/q API in offline mode and where the daemon is not able to handle it
 *
 *****
 * If you find this library useful, your donation of Bitcoins to address
 * 161AcnPykE42e4ErQNR9B73Bb78Jy81AN6 would be greatly appreciated. Thanks! Mark Scherer
 *****
 *
 * v1.0
 * @author Mark Scherer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class DenariusLib extends Denarius {

	public $C = null;

	public $info = null;

	public $defaults = array(
		'scheme' => 'http', # should be https (but http works)
		'username' => '',
		'password' => '',
		'certificate' => '', # path (absolute)
		'port' => 32339,
		'debug' => 0, # logs everything in debug mode (not only successfull transactions) TODO
		'address' => 'localhost',
		'account' => '', # default account to work on (do not use the empty account!)
		'minconf' => 10, # official minconf, but can be lowered down to 3 just fine
		'daemon' => true, # set to false if you can't run daemon (will automatically happen if you dont provide username+password)
	);

	public $settings = array();

	public function __construct($settings = array()) {
		$this->defaults = array_merge($this->defaults, (array)Configure::read('Denarius'));
		$this->settings = array_merge($this->defaults, $settings);
		extract($this->settings, EXTR_OVERWRITE);

		if ($this->settings['certificate'] === true) {
			$this->settings['certificate'] = DENARIUS_CERTIFICATE;
		}

		if (!Configure::read('Denarius.username') || !Configure::read('Denarius.password')) {
			# offline mode - will result in incomplete checks (as some methods cannot completely be replaced by webservice)
			$this->settings['daemon'] = false;
			return;
		}

		if (!$this->C) {
			$this->C = new DenariusClient($scheme, $username, $password, $address, $port, $certificate, $debug);
		}
	}

	/**
	 * return info about your wallet
	 */
	public function getInfo() {
		if ($this->info !== null) {
			return $this->info;
		}
		if (!$this->settings['daemon']) {
			$this->info = array();
		} else {
			$this->info = $this->C->getinfo();
		}
		return $this->info;
	}

	public function getAddressesByAccount($account = null) {
		if (!$this->settings['daemon']) {
			return array();
		}
		if ($account === null) {
			$account = $this->settings['account'];
		}
		return $this->C->getaddressesbyaccount($account);
	}

	public function getAccountAddress($account = null) {
		if (!$this->settings['daemon']) {
			return false;
		}
		if ($account === null) {
			$account = $this->settings['account'];
		}
		return $this->C->getaccountaddress($account);
	}

	public function getAccount($address) {
		return $this->C->getaccount($address);
	}

	/**
	 * moves the address to a differenc account
	 */
	public function setAccount($address, $account = null) {
		return $this->C->setaccount($address, $account = null);
	}

	public function backupWallet($dest) {
		return $this->C->backupwallet($dest);
	}

	/**
	 * total amount received by address
	 */
	public function getReceivedByAddress($address, $minconf = 1) {
		if ($minconf === null) {
			$minconf = $this->settings['minconf'];
		}
		if (true || !$this->settings['daemon']) { # bug in daemon v32400!!! returns always 0 for no good reason
			return $this->_getReceivedByAddress($address, $minconf);
		}
		return $this->C->getreceivedbyaddress($address, $minconf);
	}

	public function listReceivedByAddress($minconf = 1, $includeempty = false) {
		if ($minconf === null) {
			$minconf = $this->settings['minconf'];
		}
		return $this->C->listreceivedbyaddress($minconf, $includeempty);
	}

	public function listReceivedByAccount($minconf = 1, $includeempty = false) {
		if ($minconf === null) {
			$minconf = $this->settings['minconf'];
		}
		return $this->C->listreceivedbyaccount($minconf, $includeempty);
	}

	public function listReceivedByLabel($minconf = 1, $includeempty = false) {
		if ($minconf === null) {
			$minconf = $this->settings['minconf'];
		}
		return $this->C->listreceivedbylabel($minconf, $includeempty);
	}

	public function listAccounts() {
		if (!$this->settings['daemon']) {
			return array();
		}
		return $this->C->query('listaccounts');
	}

	/**
	 * @param string $account (defaults to own)
	 * @param string $type (defaults to all: receive, send, move)
	 */
	public function listTransactions($account = null, $type = null) {
		if (!$this->settings['daemon']) {
			return array();
		}
		if ($account === null) {
			$account = $this->settings['account'];
		}
		if (!$account) {
			return $this->C->query('listtransactions');
		}
		return $this->C->query('listtransactions', $account);
	}

	/**
	 * Gets the balance of a specific account in your wallet
	 *
	 * @param string $account (defaults to own)
	 * @return integer amount or bool FALSE if offline or account not found
	 */
	public function getBalance($account = null) {
		if (!$this->settings['daemon']) {
			return false;
		}
		if ($account === null) {
			$account = $this->settings['account'];
		}
		$accounts = $this->listAccounts();
		foreach ($accounts as $ownAccount => $amount) {
			if ($ownAccount == $account) {
				return $amount;
			}
		}
		//throw new DenariusClientException('invalid account given');
		return false;
	}

	/**
	 * @param string $transaction
	 * @param boolean $isMine (defaults to null): false => has to be foreign, true => has to be own
	 * @return boolean success
	 */
	public function validateTransaction($txid, $isMine = null) {
		if (empty($txid) || strlen($txid) != 64 || !preg_match('/^[0-9a-fA-F]+$/', $txid)) {
			return false;
		}
		return true;
	}

	/**
	 * Make sure an address is correct (length, network availability)
	 * note: if in offline mode it will only check the length and chars
	 *
	 * @param string $address
	 * @param boolean $isMine (defaults to null): false => has to be foreign, true => has to be own
	 * @return boolean Success
	 */
	public function validateAddress($address, $isMine = null) {
		/*
		if (!preg_match('/^[a-z0-9]{33,34}$/i', $address)) {
			return false;
		}
		*/
		if (!Denarius::checkAddress($address)) {
			return false;
		}
		if (!$this->settings['daemon']) {
			return true;
		}
		$res = $this->C->validateaddress($address);
		if (empty($res['isvalid'])) {
			return false;
		}
		if ($isMine !== null) {
			return $res['ismine'] == $isMine;
		}
		return true;
	}

	public function getHashesPerSec() {
		return $this->C->gethashespersec();
	}

	public function getTransaction($tx) {
		if (!$this->settings['daemon']) {
			return array();
		}
		return $this->C->gettransaction($tx);
	}

	public function getNewAddress($account = null) {
		if (!$this->settings['daemon']) {
			return false;
		}
		if ($account === null) {
			$account = $this->settings['account'];
		}
		return $this->C->getnewaddress($account);
	}

	public function sendFrom($fromAccount = null, $toAddress, $amount, $minconf = 1, $comment = null, $commentTo = null) {
		if ($fromAccount === null) {
			$fromAccount = $this->settings['account'];
		}
		return $this->C->sendfrom($fromAccount, $toAddress, $amount, $minconf = 1, $comment = null, $commentTo = null);
	}

	public function sendToAddress($address, $amount, $comment = null, $commentTo = null) {
		if (!$this->settings['daemon']) {
			return false;
		}
		return $this->C->sendtoaddress($address, $amount, $comment = null, $commentTo);
	}

	/**
	 * transfer money from one account to another
	 */
	public function move($fromAccount = null, $toAccount, $amount, $minconf = 1, $comment = null) {
		if (!$this->settings['daemon']) {
			return false;
		}
		if ($fromAccount === null) {
			$fromAccount = $this->settings['account'];
		}
		return $this->C->move($fromAccount, $toAccount, $amount, $minconf, $comment);
	}

	public function setFee($amount) {
		if (!$this->settings['daemon']) {
			return false;
		}
		return $this->C->query('settxfee', $amount);
	}

	/**
	 * get number of total denariuss in circulation
	 */
	public function getTotalDenariuss() {
		return (int)$this->_query('totalbc');
	}

	/**
	 * shows the time at which an address was first seen on the network
	 */
	public function addressFirstSeen($address) {
		return $this->_query('addressfirstseen/' . $address);
	}

	/**
	 * Returns total DNR sent by an address. Using this data is almost always a very
	 * bad idea, as the amount of DNR sent by an address is usually very different
	 * from the amount of DNR sent by the person owning the address
	 */
	public function getTotalSentByAddress($address) {
		return $this->_query('getsentbyaddress/' . $address);
	}

	/**
	 * Returns all transactions sent or received by the period-separated Denarius
	 * addresses in parameter 1. The optional parameter 2 contains a hexadecimal block
	 * hash: transactions in blocks up to and including this block will not be returned.
	 *
	 * @return array
	 */
	public function myTransactions($address, $block = null) {
		if ($block) {
			$address .= '/' . $block;
		}
		$res = $this->_query('mytransactions/' . $address);
		if (empty($res)) {
			return array();
		}
		return (array)json_decode($res);
	}

	/**
	 * Shows the number of blocks in the longest block chain (not including the genesis block). Equivalent to Denarius's getblockcount
	 *
	 * @return integer
	 */
	public function getBlockCount() {
		if (!$this->settings['daemon']) {
			return (int)$this->_query('getblockcount');
		}
		$this->getInfo();
		if (empty($this->info['blocks'])) {
			return 0;
		}
		return $this->info['blocks'];
	}

	/**
	 * Shows the difficulty
	 *
	 * @return integer
	 */
	public function getDifficulty() {
		if (!$this->settings['daemon']) {
			return (int)$this->_query('getdifficulty');
		}
		$this->getInfo();
		if (empty($this->info['difficulty'])) {
			return 0;
		}
		return (int)$this->info['difficulty'];
	}

/** backup methods if localhost is not running denariusd service **/

	/**
	 * Returns total DNR received by an address. Sends are not taken into account.
	 * The optional second parameter specifies the required number of confirmations for
	 * transactions comprising the balance
   *
	 * @return float amount
	 */
	public function _getReceivedByAddress($address, $minconf = null) {
		if ($minconf) {
			$address .= '/' . $minconf;
		}
		return (float)$this->_query('getreceivedbyaddress/' . $address);
	}

	/**
	 * @deprecated
	 * note: probably not neccessary as validation is already implemented in denarius.php
	 * Returns 00 if the address is valid, something else otherwise.
	 */
	public function _checkAddress($address) {
		$res = $this->_query('checkaddress/' . $address);
		if (empty($res) || $res !== '00') {
			return false;
		}
		return true;
	}

	/**
	 * does the actual query
	 */
	public function _query($q) {
		$url = 'http://denariusexplorer.org/q/';
		$res = file_get_contents($url . $q);

		if ($res === '') {
			trigger_error('Lookup Failed (' . $q . ')');
			return '';
		} elseif (strpos($res, 'ERROR: ') === 0) {
			trigger_error(substr($res, 7));
			return '';
		}

		return $res;
	}

}
