<?php
//App::uses('AppController', 'Controller');
App::uses('PaymentAppController', 'Payment.Controller');

class DenariusTransactionsController extends PaymentAppController {

	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();
	}

/****************************************************************************************
 * USER functions
 ****************************************************************************************/

/****************************************************************************************
 * ADMIN functions
 ****************************************************************************************/

	public function admin_index() {
		$this->DenariusTransaction->recursive = 0;
		$denariusTransactions = $this->paginate();
		$this->set(compact('denariusTransactions'));
	}

	public function admin_view($id = null) {
		if (empty($id) || !($denariusTransaction = $this->DenariusTransaction->find('first', array('conditions' => array('DenariusTransaction.id' => $id))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		$this->set(compact('denariusTransaction'));
	}

	public function admin_add() {
		if ($this->Common->isPosted()) {
			$this->DenariusTransaction->create();
			if ($this->DenariusTransaction->save($this->request->data)) {
				$var = $this->request->data['DenariusTransaction']['amount'];
				$this->Common->flashMessage(__('record add %s saved', h($var)), 'success');
				$this->Common->postRedirect(array('action' => 'index'));
			} else {
				$this->Common->flashMessage(__('formContainsErrors'), 'error');
			}
		}
		$addresses = $this->DenariusTransaction->Address->find('list');
		$this->set(compact('addresses'));
	}

	public function admin_edit($id = null) {
		if (empty($id) || !($denariusTransaction = $this->DenariusTransaction->find('first', array('conditions' => array('DenariusTransaction.id' => $id))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Common->isPosted()) {
			if ($this->DenariusTransaction->save($this->request->data)) {
				$var = $this->request->data['DenariusTransaction']['amount'];
				$this->Common->flashMessage(__('record edit %s saved', h($var)), 'success');
				$this->Common->postRedirect(array('action' => 'index'));
			} else {
				$this->Common->flashMessage(__('formContainsErrors'), 'error');
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $denariusTransaction;
		}
		$addresses = $this->DenariusTransaction->Address->find('list');
		$this->set(compact('addresses'));
	}

	public function admin_delete($id = null) {
		if (!$this->Common->isPosted()) {
			throw new MethodNotAllowedException();
		}
		if (empty($id) || !($denariusTransaction = $this->DenariusTransaction->find('first', array('conditions' => array('DenariusTransaction.id' => $id), 'fields' => array('id', 'amount'))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		$var = $denariusTransaction['DenariusTransaction']['amount'];

		if ($this->DenariusTransaction->delete($id)) {
			$this->Common->flashMessage(__('record del %s done', h($var)), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Common->flashMessage(__('record del %s not done exception', h($var)), 'error');
		$this->Common->autoRedirect(array('action' => 'index'));
	}

/****************************************************************************************
 * protected/interal functions
 ****************************************************************************************/

/****************************************************************************************
 * deprecated/test functions
 ****************************************************************************************/

}
