<h2>Transfer Denariuss</h2>

<div style="float: right;">
<?php echo $this->Form->create('Denarius');?>
<?php echo $this->Form->input('own_account_id', array('empty'=>'- pleaseSelect - '));?>
<?php echo $this->Form->end(__('Change'));?>
</div>

<h3></h3>
<?php
if (!empty($this->request->data['DenariusTransaction']['address'])) {
	echo 'Aktuelle Addresse: '.h($this->request->data['DenariusTransaction']['address']).BR;
	$this->QrCode->setSize(100);
	$this->QrCode->setLevel('H');
	$string = $this->QrCode->format('denarius', $this->request->data['DenariusTransaction']['address']);
	echo $this->QrCode->image($string);
}
?>

<div class="">
<?php echo $this->Form->create('DenariusTransaction');?>
	<fieldset>
		<legend><?php echo __('Request payment');?></legend>
		<?php
			echo $this->Form->hidden('request', array('value'=>1));
			echo $this->Form->input('address', array('options'=>$ownAddresses));
			//echo $this->Form->input('amount', array());# not supported yet
			//echo $this->Form->input('currency', array('options'=>array('DNR - Denariuss')));
			//echo $this->Form->input('label', array()); # not supported yet
			//echo $this->Form->input('commment', array()); # not supported yet
		?>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>


<table><tr><td>

<h3>Move inside Wallet</h3>
<div class="">
<?php echo $this->Form->create('DenariusTransaction');?>
	<fieldset>
		<legend><?php echo __('Transfer money from one account to another');?></legend>
		<?php
if (!empty($this->request->data['Denarius']['own_account_id'])) {
	echo 'Aktueller Account: '.h($this->request->data['Denarius']['own_account_id']).BR;
}
			echo $this->Form->hidden('move', array('value'=>1));
			echo $this->Form->input('amount', array());
			echo $this->Form->error('from_account');
			//echo $this->Form->input('from_account', array());
			echo $this->Form->input('to_account', array('options'=>$ownAccounts, 'empty'=>'- pleaseSelect - '));
			echo $this->Form->input('comment', array());
		?>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>

</td><td>

<h3>Send to Address</h3>
<div class="">
<?php echo $this->Form->create('DenariusTransaction');?>
	<fieldset>
		<legend><?php echo __('Send Money');?></legend>
		<?php
if (isset($infos['balance'])) {
	echo __('Available').': ' . h($infos['balance']) . ' DNR'.BR;
}
if (!empty($this->request->data['Denarius']['own_account_id'])) {
	echo 'Aktueller Account: '.h($this->request->data['Denarius']['own_account_id']).BR;
}
if (false && !empty($this->request->data['DenariusTransaction']['address'])) {
	echo 'Aktuelle Adresse: '.h($this->request->data['DenariusTransaction']['address']).BR;
}
			echo $this->Form->hidden('send', array('value'=>1));
			echo $this->Form->input('amount', array());
			echo $this->Form->error('from_account');
			echo $this->Form->input('currency', array('options'=>array('DNR - Denariuss')));
			echo $this->Form->input('to_address', array());
			echo $this->Form->input('comment', array());
			echo $this->Form->input('comment_to', array());
			if (Configure::read('Denarius.key')) {
				echo $this->Form->input('pwd', array('label'=>'Your Denarius Confirmation Key'));
			}

		?>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>


</td></tr></table>