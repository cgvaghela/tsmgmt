<?php include_once('config/PDO.php');
$db = new db();
$email = $password = $error = $installerTxt = '';

try{
	$checkForInstall = $db->numRow("SELECT * FROM `admin`",array());
	if($checkForInstall)
	{
		if (file_exists('installer.txt')) {   
			$fh = fopen('installer.txt','r');
			while ($line = fgets($fh)) {
				$installerTxt .= $line;  
			}
			fclose($fh);
		}else{
			header('Location:index.php'); exit();
		}
	}else{
		$unique = $db->generateRandomString();
		$email = 'admin@admin.com';
		$username = 'admin';
		$password = $db->generateRandomString(8);
		$role = 'main';

		$file = fopen('installer.txt',"w");
		$content = '<label>Username: <span>'.$email.'</span></label><br><label>Password: <span>'.$password.'</span></label>';
		fwrite($file,$content);
		fclose($file);

		$admin_id = $db->insertRow("INSERT INTO `admin` (`unique_id`,`email`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,NOW(),NOW())",array($unique,$email,$username,$db->passwordEncrypt($password),$role));
		if($admin_id){
			$db->insertRow("INSERT INTO `admin_setting` (`admin_id`,`admin`,`model`,`dealer`,`sales_man`,`branch`,`showroom`,`cashier`,`expence`,`exchange`,`finance`,`atm`,`bank`,`gatepass`,`billing`,`rto`,`report`,`re_passing`,`re_total`,`re_stock`,`re_incentive`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($admin_id,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1));
		}
	}
}catch (Exception $e) {
	try{
		$sql = @file_get_contents('tsmgmt.sql');
		if ($sql === false) {
			$error = "In Root Folder <span>tsmgmt.sql</span> not exist!";
		}

		$sqlQ = $db->queryRow($sql);
		header('Location:install.php'); exit();
	}catch (Exception $e) {
		$error = "In Root Folder <span>tsmgmt.sql</span> not exist!";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<title>TS-MGMT Installer</title>
	<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/main-responsive.css">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" sizes="16x16">
</head>
<style type="text/css">
.box-login h4 span, .box-login .form-group label span{
    color: red;
    background-color: #000;
    padding: 3px 8px;
}
</style>
<body class="login example2">
	<div class="main-login col-sm-4 col-sm-offset-4">
		<div class="logo">
			<img src="images/main-logo.png">
		</div>

		<div class="box-login">
		<?php if(empty($error)){ ?>
			<h4>Below you must save your login details.</h4>
			<div class="form-group">
				<?php if(!empty($installerTxt)){
						echo $installerTxt;
					}else{
				?>
				<label>Username: <span><?php echo $email; ?></span></label>
				<br>
				<label>Password: <span><?php echo $password; ?></span></label>
				<?php } ?>
			</div>
			<div class="form-actions">
				<a href="login.php" class="btn btn-success btn-squared btn-login">Login to continue...</a>
			</div>
		<?php }else{ ?>
			<h4><?php echo $error;?></h4>
		<?php } ?>
		</div>

		<div class="copyright">
			<?php echo date('Y');?> &copy; by <a href="" target="">TS-MGMT</a>
			Develop By <a href="https://www.syntegowebsolution.com" target="_blank">SWS</a>
		</div>
	</div>
	<script src="assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>
	<script>
	jQuery(document).ready(function() {
		$(".box-login").show();
	});
	</script>
</body>
</html>