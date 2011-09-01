<div class="parts index">
    <h2><?php __('Parts');?></h2>

	<?php echo $this->DataTables->display($dataTables); ?>

	<?php debug ($dataTables); ?>

	<script type="text/javascript" language="javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="/js/jquery.dataTables.min.js"></script>
	
	<?php echo $this->DataTables->javaScript($dataTables, array(
		'sScrollY' => '500px',
	)); ?>

</div>
