<?php
App::uses('AppShell', 'Console/Command');

class InstantPaymentNotificationTask extends AppShell {

	public $timeout = 120;

	public $retries = 0;

	public function add() {
	$this->err('Queue Email Task cannot be added via Console.');
		$this->out('Please use createJob() on the QueuedTask Model to create a Proper Email Task.');
		$this->out('The Data Array should look something like this:');
		$this->out(var_export(array('settings' => array('to' => 'email@example.com', 'subject' => 'Email Subject', 'from' => 'system@example.com',
			'template' => 'sometemplate'), 'vars' => array('text' => 'hello world')), true));
	}

	public function execute() {
		$this->DenariusAddress = ClassRegistry::init('Payment.DenariusAddress');

		if ($this->DenariusAddress->update()) {
			$this->log('Shell ' . $this->name . ' successfully completed', 'denarius');
			return true;
		}
		$this->log('Shell ' . $this->name . ' aborted', 'denarius');
		return false;
	}

}
