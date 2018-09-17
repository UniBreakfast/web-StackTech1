<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
//  if (isset($_REQUEST['record']) && trim($_REQUEST['record'])!=='') {
    $record = trim($_REQUEST['record']);
echo 'a'.$record.'b';
//    $query = "INSERT test_list (record) VALUES ('$record')";
//    mysqli_query($db, $query) or exit ('INSERT record Query failed');
//    echo "Added record: '$record'";
//  }
//  else echo "No record provided - none added";
