<div class="index">

<h2>Denarius Transactions</h2>

<div style="float: right;">
<?php echo $this->Form->create('Denarius');?>
<?php echo $this->Form->input('account', array('empty'=>'- pleaseSelect - '));?>
<?php echo $this->Form->input('category', array('empty'=>'- all - ', 'options' => array('receive'=>'incoming', 'send'=>'outgoing', 'move' => 'moved')));?>
<?php echo $this->Form->button(__('Change'));?>
<?php echo $this->Form->end();?>
</div>



<h3>All</h3>
<?php
	echo pre($transactions);
?>

</div>