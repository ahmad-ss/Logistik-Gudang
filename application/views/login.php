<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="<?php echo base_url();?>assets/icon.png">
	<title>Logistik</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>styles/css/bootstrap.min.css">

  	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery/jquery.min.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery-confirm-v3.3.2/jquery-confirm.min.js"></script> 	
  	<script type="text/javascript" src="<?php echo base_url();?>styles/js/bootstrap.min.js"></script>
</head>
<body class="container">
	<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4" style="padding:10px;">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					<h3 class="text-uppercase">Login Logistik</h3>
				</div>
				<div class="panel-body">
					<div id="alert-login"></div>
					<form id="form-login">
						<div class="col-md-12">
							<div class="form-group">
								<label class="text-uppercase">Username</label>
								<input type="text" class="form-control" name="username">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="text-uppercase">Password</label>
								<input type="password" class="form-control" name="password">
							</div>
						</div>
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary btn-block">Sign In</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4"></div>
	</div>
<script type="text/javascript">
function getLogin(){
	var form = $("#form-login");

	$.ajax({
		url:'../logistik/login/getlogin',
		type:'POST',
		data:form.serialize(),
		beforeSend:function(){
			$('#alert-login').html('<div class="alert alert-warning alert-dismissible"><i class="glyphicon glyphicon-search"></i> Loading...</div>');
		},success:function(data){
			$('#alert-login').html(data);
			location.href='../logistik/welcome';
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}

// Start up
$(document).ready(function(){
	$('#alert-login').html('<div class="alert alert-info alert-dismissible">Please fill this form correctly ! (Using <b>"TAB"</b> for next form)</div>');
});

// Form submit login
$("#form-login").submit(function(e){
	e.preventDefault();
	getLogin();
});
</script>
</body>
</html>