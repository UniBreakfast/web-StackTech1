<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
  $query = 'DELETE FROM test_list';
  mysqli_query($db, $query) or exit ('DELETE all FROM test_list Query failed');
?>
