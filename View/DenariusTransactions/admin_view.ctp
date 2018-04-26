<div class="page view">
<h2><?php echo __('Denarius Transaction');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Denarius Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($denariusTransaction['DenariusAddress']['address'], array('controller' => 'denarius_addresses', 'action' => 'view', $denariusTransaction['DenariusAddress']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Model'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['model']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Foreign Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['foreign_id']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['amount']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Amount Expected'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['amount_expected']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Confirmations'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['confirmations']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Details'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['details']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Payment Fee'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['payment_fee']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['status']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Refund Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusTransaction['DenariusTransaction']['refund_address']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Datetime->niceDate($denariusTransaction['DenariusTransaction']['created']); ?>
			&nbsp;
		</dd>
<?php if ($denariusTransaction['DenariusTransaction']['created'] != $denariusTransaction['DenariusTransaction']['modified']) { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Datetime->niceDate($denariusTransaction['DenariusTransaction']['modified']); ?>
			&nbsp;
		</dd>
<?php } ?>
	</dl>
</div>

<br /><br />

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit %s', __('Denarius Transaction')), array('action' => 'edit', $denariusTransaction['DenariusTransaction']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete %s', __('Denarius Transaction')), array('action' => 'delete', $denariusTransaction['DenariusTransaction']['id']), null, __('Are you sure you want to delete # %s?', $denariusTransaction['DenariusTransaction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Transactions')), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Addresses')), array('controller' => 'denarius_addresses', 'action' => 'index')); ?> </li>
	</ul>
</div>