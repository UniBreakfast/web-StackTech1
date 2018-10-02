<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
  // FUTURE real MySQL check
  if (isset($_REQUEST['cookie']) && trim($_REQUEST['cookie'])!=='') {
    $cookieString = trim($_REQUEST['cookie']);
    $query = "SELECT token FROM test_token";
    $result = mysqli_query($db, $query)
      or exit ('SELECT token FROM test_token Query failed');
    list($token) = mysqli_fetch_row($result);
    if ($cookieString == $token) echo 'valid';
    else echo 'invalid';
  }
  else echo 'no cookie provided for checking';
?>
