<?php

session_start();

$type = $_POST['view_type'];


if ($type == "row") {
    $_SESSION["view_type"] = true;
} else {
    if (isset($_SESSION['view_type'])) {
        unset($_SESSION['view_type']);
    }
}