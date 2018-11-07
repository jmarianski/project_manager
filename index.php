<?php
set_time_limit(10);
require_once "CommandBasis.php";
require_once "Git.php";
require_once "Database.php";
require_once "Login.php";
$p = $_GET["project"];
$git = new Git();
$db = new Database($p);
$login = new Login();
	if($_GET["logout"]!=null) {
		$login->logout();
	}
	if(!$login->isLoggedIn() && strlen($_POST["login"])==0 || strlen($_POST["login"])!=0 && !$login->login($_POST["login"], $_POST["password"], $_POST["register"]=="tak")) { ?>
<form method="POST">
	<input name="register" type=hidden value="<?=$_GET["register"]=="tak"?"tak":"false"?>">
	<input name="login">
	<input name="password" type="password">
	<button type="submit">Loguj</button>
</form>
	<?php 
	return;
} else {
	echo "<a href=?logout=true>Wyloguj</a><HR>";
}
if(!isset($_GET["content"])) {
	include "main_content.php";
} else {
	include "frame.php";
}