<div class="page index">
<h2><?php echo __('Denarius Transactions');?></h2>

<table class="list">
<tr>
	<th><?php echo $this->Paginator->sort('address_id');?></th>
	<th><?php echo $this->Paginator->sort('model');?></th>
	<th><?php echo $this->Paginator->sort('foreign_id');?></th>
	<th><?php echo $this->Paginator->sort('amount');?></th>
	<th><?php echo $this->Paginator->sort('amount_expected');?></th>
	<th><?php echo $this->Paginator->sort('confirmations');?></th>
	<th><?php echo $this->Paginator->sort('details');?></th>
	<th><?php echo $this->Paginator->sort('payment_fee');?></th>
	<th><?php echo $this->Paginator->sort('status');?></th>
	<th><?php echo $this->Paginator->sort('refund_address');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($denariusTransactions as $denariusTransaction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($denariusTransaction['DenariusAddress']['address'], array('controller' => 'denarius_addresses', 'action' => 'view', $denariusTransaction['DenariusAddress']['id'])); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['model']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['foreign_id']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['amount']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['amount_expected']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['confirmations']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['details']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['payment_fee']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['status']); ?>
		</td>
		<td>
			<?php echo h($denariusTransaction['DenariusTransaction']['refund_address']); ?>
		</td>
		<td>
			<?php echo $this->Datetime->niceDate($denariusTransaction['DenariusTransaction']['created']); ?>
		</td>
		<td>
			<?php echo $this->Datetime->niceDate($denariusTransaction['DenariusTransaction']['modified']); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Format->icon('view'), array('action'=>'view', $denariusTransaction['DenariusTransaction']['id']), array('escape'=>false)); ?>
			<?php echo $this->Html->link($this->Format->icon('edit'), array('action'=>'edit', $denariusTransaction['DenariusTransaction']['id']), array('escape'=>false)); ?>
			<?php echo $this->Form->postLink($this->Format->icon('delete'), array('action'=>'delete', $denariusTransaction['DenariusTransaction']['id']), array('escape'=>false), __('Are you sure you want to delete # %s?', $denariusTransaction['DenariusTransaction']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<div class="pagination-container">
<?php echo $this->element('pagination', array(), array('plugin'=>'tools')); ?></div>

</div>

<br /><br />

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Add %s', __('Denarius Transaction')), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Addresses')), array('controller' => 'denarius_addresses', 'action' => 'index')); ?> </li>
	</ul>
</div>