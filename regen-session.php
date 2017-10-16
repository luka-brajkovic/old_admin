<?php

session_start();
$old_sessionid = session_id();
session_regenerate_id();
$new_sessionid = session_id();
echo "<meta http-equiv='refresh' content='0;URL=/' />";
die();
