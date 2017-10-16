<?php

session_start();
$type = $_POST['ppp'];
$_SESSION["ppp"] = $type;
