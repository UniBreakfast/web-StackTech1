<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
if (isset($_REQUEST['cookie']) && trim($_REQUEST['cookie'])!=='') {
  $cookie = trim($_REQUEST['cookie']);
  list($userid, $token) = explode('|', $cookie);

  $query = "DELETE FROM test_sessions
            WHERE user_id = $userid AND token = '$token'";
  mysqli_query($db, $query)
    or exit ('DELETE FROM test_sessions Query failed');

}
else echo 'no cookie provided for deletion';
?>

