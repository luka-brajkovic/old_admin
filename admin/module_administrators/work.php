<?php

require("../library/config.php");

$values = $f->getRequestValues();
$action = $f->getValue("action");
$admin_id = $values["admin_id"];

switch ($action) {

	case "add_admin":

		$username = $values["username"];
		$adminse = mysqli_query($conn, "SELECT id FROM administrators WHERE username = '$username' LIMIT 1");
		$adminse = mysqli_fetch_object($adminse);
		if ($adminse->id!="") {
				$f->redirect("add_admin.php?admin_id=$admin->id&same=yes");
		}

		$admin = new View("administrators");
		$admin->extend($_POST);

		if ($values["password"] != $values["rep_pass"]) {
			$f->redirect("add_admin.php?admin_id=$admin->id&wrong_pass=yes");
		}

		if ($values["fullname"] == "" || $values["username"] == '' || $values["email"] == '') {
			$f->redirect("add_admin.php?admin_id=$admin->id&wrong_data=yes");
		}

		$admin->password = md5($values["rep_pass"]);

		$admin->Save();
		$f->redirect("index.php");

		break;

	case "edit_admin":
		$admin = new View("administrators", $admin_id);
		$admin->extend($_POST);

		if ($values["password"] != $values["rep_pass"]) {
			$f->redirect("edit_admin.php?admin_id=$admin->id&wrong_pass=yes");
		}

		if ($values["fullname"] == "" || $values["username"] == '' || $values["email"] == '') {
			$f->redirect("edit_admin.php?admin_id=$admin->id&wrong_data=yes");
		}

		$admin->password = md5($values["rep_pass"]);
		$admin->Save();

		$f->redirect("index.php");

		break;

	/* $username = $f->getValue("username");
	  $fullname = $f->getValue("fullname");
	  $email = $f->getValue("email");
	  $password = $f->getValue("password");
	  $repeat_password = $f->getValue("repeat_password");
	  $admin_id = $f->getValue("admin_id");


	  $db->execQuery("UPDATE administrators SET `username`='$username', `fullname`='$fullname', `email`='$email'WHERE id='$admin_id'");

	  if($password == $repeat_password) {
	  $password = md5($password);
	  $db->execQuery("UPDATE administrators SET `password`='$password' WHERE id='$admin_id'");
	  }

	  $f->redirect("index.php");

	  break; */
	case "delete_admin":
		$admin = new View("administrators", $admin_id);
		$admin->Remove();
		$f->redirect("index.php");
		break;
}
?>