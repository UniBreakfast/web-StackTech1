<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
  // FUTURE real MySQL check
  if (isset($_REQUEST['cookie']) && trim($_REQUEST['cookie'])!=='') {
    $cookieString = trim($_REQUEST['cookie']);
    //$query = "INSERT test_list (record) VALUES ('$record')";
    //mysqli_query($db, $query) or exit ('INSERT record Query failed');
    echo 'logged out';
  }
  else echo 'no cookie to log out';
?>
