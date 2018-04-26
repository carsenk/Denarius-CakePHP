<div class="page view">
<h2><?php echo __('Denarius Address');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($denariusAddress['User']['username'], array('controller' => 'users', 'action' => 'view', $denariusAddress['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Model'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['model']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Foreign Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['foreign_id']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['address']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['amount']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Confirmations'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['confirmations']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Details'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['details']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['status']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Refund Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo h($denariusAddress['DenariusAddress']['refund_address']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Datetime->niceDate($denariusAddress['DenariusAddress']['created']); ?>
			&nbsp;
		</dd>
<?php if ($denariusAddress['DenariusAddress']['created'] != $denariusAddress['DenariusAddress']['modified']) { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Datetime->niceDate($denariusAddress['DenariusAddress']['modified']); ?>
			&nbsp;
		</dd>
<?php } ?>
	</dl>
</div>

<br /><br />

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit %s', __('Denarius Address')), array('action' => 'edit', $denariusAddress['DenariusAddress']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete %s', __('Denarius Address')), array('action' => 'delete', $denariusAddress['DenariusAddress']['id']), null, __('Are you sure you want to delete # %s?', $denariusAddress['DenariusAddress']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List %s', __('Denarius Addresses')), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
	</ul>
</div>