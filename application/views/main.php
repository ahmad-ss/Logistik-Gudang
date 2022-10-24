<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="<?php echo base_url();?>assets/icon.png">
<title>Logistik</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>styles/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/DataTables/datatables.min.css">
<link href="<?php echo base_url();?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquery-confirm-v3.3.2/jquery-confirm.min.css">

<!--Datatable-->
<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery-confirm-v3.3.2/jquery-confirm.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>styles/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>plugins/DataTables/datatables.min.js"></script>
<script src="<?php echo base_url();?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>plugins/jquery-confirm-v3.3.2/jquery-confirm.min.js"></script>
<!-- bootstrap-select (nanti ganti dilocal) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"> -->
<!-- LOCAL -->
<script type="text/javascript" src="<?php echo base_url();?>plugins/bootstrap-select/bootstrap-select.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/bootstrap-select/bootstrap-select.min.css">
</head>
<body>
<?php echo $nav;?>
<div class="container-fluid">
<?php echo $content;?>
</div>
<?php echo $footer;?>
<script type="text/javascript">
$(document).ready(function(){
	$('.datepicker').datepicker({
		todayBtn: "linked",
		format: "dd-mm-yyyy",
		autoclose: true
	}); 
});
</script>
</body>
</html>