<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once 'authvars.php';

if (isset($_REQUEST['cookie']) && trim($_REQUEST['cookie'])!=='') {
  $cookie = trim($_REQUEST['cookie']);
  list($userid, $token) = explode('|', $cookie);

  $query = "DELETE FROM $sessionTable
            WHERE user_id = $userid AND token = '$token'";
  mysqli_query($db, $query)
    or exit ("DELETE FROM $sessionTable Query failed!");
}
else echo 'who do you want to log out?';
?>

