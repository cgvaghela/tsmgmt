<?php session_start();
if(!isset($_SESSION['unique_id']) && empty($_SESSION['unique_id'])){
	header('Location:../index.php');
	exit();
}
?>