<h2><?php echo __('Edit %s', __('Denarius Transaction')); ?></h2>

<div class="page form">
<?php echo $this->Form->create('DenariusTransaction');?>
	<fieldset>
 		<legend><?php echo __('Edit %s', __('Denarius Transaction')); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('address_id', array('empty'=>' - [ '.__('pleaseSelect').' ] - '));
		echo $this->Form->input('model');
		echo $this->Form->input('foreign_id');
		echo $this->Form->input('amount');
		echo $this->Form->input('amount_expected');
		echo $this->Form->input('confirmations');
		echo $this->Form->input('details');
		echo $this->Form->input('payment_fee');
		echo $this->Form->input('status');
		echo $this->Form->input('refund_address');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<br /><br />

<div class="actions">
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('DenariusTransaction.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('DenariusTransaction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Transactions')), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Addresses')), array('controller' => 'denarius_addresses', 'action' => 'index')); ?> </li>
	</ul>
</div>