<?php session_start();
if($_POST){
	extract($_POST);
	include_once("../config/PDO.php");

	$db = new db();
	$table = "admin";
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$login = isset($_POST['login']) ? $_POST['login'] : 'No';

	if($login == 'Yes'){
		if(file_exists('../installer.txt')){
			unlink('../installer.txt');
		}
		if(file_exists('../login.php')){
			rename('../login.php','../login-sample.php');
			//unlink('../login.php');
		}
	}
	$res = $db->getRow("SELECT * FROM ".$table." where `email`=? and `password`=? and `status`=?",array($email,$db->passwordEncrypt($password),1));
	if($res)
	{
		// select permisstion setting
		$_SESSION['adminPermission'] = $db->getRow("SELECT * FROM `admin_setting` where `admin_id`=?",array($res['admin_id']));
		$_SESSION['unique_id'] = $res['unique_id'];
		$_SESSION['admin_id'] = $res['admin_id'];
		$_SESSION['admin_username'] = $res['username'];
		$_SESSION['admin_email'] = $res['email'];
		$_SESSION['admin_role'] = $res['role'];
		echo 1;
	}else{
		echo 0;
	}
}else{
	echo 2;
}
?>