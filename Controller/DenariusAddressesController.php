<?php
App::uses('PaymentAppController', 'Payment.Controller');

class DenariusAddressesController extends PaymentAppController {

	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index() {
		$this->DenariusAddress->recursive = 0;
		$denariusAddresses = $this->paginate();
		$this->set(compact('denariusAddresses'));
	}

	public function admin_view($id = null) {
		if (empty($id) || !($denariusAddress = $this->DenariusAddress->find('first', array('conditions' => array('DenariusAddress.id' => $id))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		$this->set(compact('denariusAddress'));
	}

	public function admin_add() {
		if ($this->Common->isPosted()) {
			$this->DenariusAddress->create();
			if ($this->DenariusAddress->save($this->request->data)) {
				$var = $this->request->data['DenariusAddress']['address'];
				$this->Common->flashMessage(__('record add %s saved', h($var)), 'success');
				$this->Common->postRedirect(array('action' => 'index'));
			} else {
				$this->Common->flashMessage(__('formContainsErrors'), 'error');
			}
		}
	}

	public function admin_edit($id = null) {
		if (empty($id) || !($denariusAddress = $this->DenariusAddress->find('first', array('conditions' => array('DenariusAddress.id' => $id))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Common->isPosted()) {
			if ($this->DenariusAddress->save($this->request->data)) {
				$var = $this->request->data['DenariusAddress']['address'];
				$this->Common->flashMessage(__('record edit %s saved', h($var)), 'success');
				$this->Common->postRedirect(array('action' => 'index'));
			} else {
				$this->Common->flashMessage(__('formContainsErrors'), 'error');
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $denariusAddress;
		}
	}

	public function admin_delete($id = null) {
		if (!$this->Common->isPosted()) {
			throw new MethodNotAllowedException();
		}
		if (empty($id) || !($denariusAddress = $this->DenariusAddress->find('first', array('conditions' => array('DenariusAddress.id' => $id), 'fields' => array('id', 'address'))))) {
			$this->Common->flashMessage(__('invalid record'), 'error');
			$this->Common->autoRedirect(array('action' => 'index'));
		}
		$var = $denariusAddress['DenariusAddress']['address'];

		if ($this->DenariusAddress->delete($id)) {
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
