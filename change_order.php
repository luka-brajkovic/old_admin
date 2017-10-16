<?php

session_start();
$type = $_POST['order_by'];
$_SESSION["order_by"] = $type;
