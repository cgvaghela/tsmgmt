<?php session_start();
include_once('config/PDO.php');
$db = new db();
if(isset($_SESSION['unique_id']) && !empty($_SESSION['unique_id']))
{
	$adminLogin = $db->getRow("SELECT (`unique_id`) FROM `admin` where `unique_id`=?",array($_SESSION['unique_id']));
	if(!empty($adminLogin)){
		$_SESSION['unique_id'] = $adminLogin['unique_id'];
	}else{
		$adminLogin['unique_id'] = "";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<title>Two Wheeler Showroom Management</title>
	<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/fonts/style.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/main-responsive.css">
	<link rel="stylesheet" href="assets/css/theme_light.css" type="text/css" id="skin_color">
	<link rel="stylesheet" href="assets/css/print.css" type="text/css" media="print"/>
	<link rel="icon" href="favicon.ico" type="image/x-icon" sizes="16x16">
</head>
	
<body class="login example2">
<div class="main-login col-sm-4 col-sm-offset-4">
	<div class="logo"><img src="images/main-logo.png"></div>
	<!-- start: LOGIN BOX -->
	<?php if((isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) && ($_SESSION['unique_id']==$adminLogin['unique_id'])){?>
		<div class="box-login">
			<h3>Log Out</h3>
			<p>go to <a href="dashboard.php" class="log-out">DASHBOARD</a></p>
			<p>click to <a href="logout.php" class="log-out">Logout</a></p>
		</div>
	<?php }else{ ?>
	<div class="box-login">
		<h3>Login</h3>
		<?php if((isset($_SESSION['unique_id']) && !empty($_SESSION['unique_id'])) && ($_SESSION['unique_id']==$adminLogin['unique_id'])){?>
			<p>Login to Continue...</p>
		<?php }?>
		<p>Please enter your email and password to log in.</p>
		<form class="form-login" method="post" id="form-login" name="form-login">
			<input type="hidden" name="login" value="Yes">
			<div class="no-display loading-div">
				<img src="loading.gif" height="100" width="100">
			</div>
			<div class="errorHandler alert alert-danger no-display">
				<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
			</div>
			<fieldset>
				<div class="form-group">
					<span class="input-icon">
						<input type="email" class="form-control email" name="email" placeholder="Email">
						<i class="fa fa-user"></i>
						<span for="email" class="help-block no-display email-text">This field is required.</span>
					</span>
				</div>
				<div class="form-group form-actions">
					<span class="input-icon">
						<input type="password" class="form-control password" name="password" placeholder="Password">
						<span for="password" class="help-block no-display password-text">This field is required.</span>
						<i class="fa fa-lock"></i>
						<!--<a class="forgot" href="#" id="forgot">
							I forgot my password
						</a>--> 
					</span>
				</div>
				<div class="form-actions">
					<label for="remember" class="checkbox-inline">
						<input type="checkbox" class="grey remember" id="remember" name="remember">
						Keep me signed in
					</label>
					<button type="button" class="btn btn-success btn-squared pull-right btn-login">
						Login <i class="fa fa-arrow-circle-right"></i>
					</button>
				</div>
			</fieldset>
		</form>
	</div>
	
	<?php } ?>
	<div class="copyright">
		<?php echo date('Y');?> &copy; by <a href="" target="">TS-MGMT</a>
		Develop By <a href="https://www.syntegowebsolution.com" target="_blank">SWS</a>
	</div>
	<!-- end: COPYRIGHT -->
</div>
<script src="assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script src="assets/js/form-validation.js"></script>
<script>
	jQuery(document).ready(function() {
		$(".box-forgot").hide();
		$(".box-login").show();
	});
</script>
<script>
$(document).ready(function(){
	$("#forgot").click(function(){
		$(".box-login").hide();
		document.getElementById("form-login").reset();
		document.getElementById("form-forgot").reset();
		$(".box-forgot").show();
	});
	$(".go-back").click(function(){
		$(".box-forgot").hide();
		$(".box-login").show();
		document.getElementById("form-login").reset();
		document.getElementById("form-forgot").reset();
	});
	
	// Enter To fire Login code
	$('#form-login').keydown(function(e) {
		var key = e.which;
		if (key == 13){
			if(!$("#form-login").valid()){
				return false;	
			}else{
				var form = document.getElementById("form-login");
				formdata2 = new FormData(form);
				$.ajax({
					url : "model/login.php",
					cache : false,
					contentType : false,
					processData : false,
					data : formdata2,
					beforeSend: function() {
						$(".loading-div").removeClass('no-display');
						$(".loading-div").addClass('display');
						$(".main-form-div").addClass('no-display');
						$(".main-form-div").removeClass('display');
					},
					type : "post",
					success : function(data) {
						$(".loading-div").removeClass('display');
						$(".loading-div").addClass('no-display');
						$(".main-form-div").addClass('display');
						$(".main-form-div").removeClass('no-display');
						if(data == 1){
							document.getElementById("form-login").reset();
							window.location.href = 'dashboard.php';
						}else if (data == 0){
							$(".email").css("border","1px solid red");
							$(".password").css("border","1px solid red");
							alert("Email and Password Is Wrong...");
						}else if(data == 2){
							$(".email").css("border","1px solid red");
							$(".password").css("border","1px solid red");
							alert("Email and Password Is Wrong...");
						}
					}
				});
			}
		}
	});
	
	$(".btn-login").click(function(){
		//form.validate();
		if(!$("#form-login").valid()){
			return false;	
		}else{
			var form = document.getElementById("form-login");
			formdata2 = new FormData(form);
			$.ajax({
				url : "model/login.php",
				cache : false,
				contentType : false,
				processData : false,
				data : formdata2,
				beforeSend: function() {
					$(".loading-div").removeClass('no-display');
					$(".loading-div").addClass('display');
					$(".main-form-div").addClass('no-display');
					$(".main-form-div").removeClass('display');
				},
				type : "post",
				success : function(data) {
					$(".loading-div").removeClass('display');
					$(".loading-div").addClass('no-display');
					$(".main-form-div").addClass('display');
					$(".main-form-div").removeClass('no-display');
					if(data == 1){
						document.getElementById("form-login").reset();
						window.location.href = 'dashboard.php';
					}else if (data == 0){
						$(".email").css("border","1px solid red");
						$(".password").css("border","1px solid red");
						alert("Email and Password Is Wrong...");
					}else if(data == 2){
						$(".email").css("border","1px solid red");
						$(".password").css("border","1px solid red");
						alert("Email and Password Is Wrong...");
					}
				}
			});
		}
	});
});
</script>
	
</body>
</html>