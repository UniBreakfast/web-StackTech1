<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
  $query = "SELECT record, dt_create FROM test_list";
  $result = mysqli_query($db, $query) or exit ('SELECT records Query failed');
  $headerows = array('headers'=>array('record', 'dt_create'), 'rows'=>array());
  while ($row = mysqli_fetch_row($result)) $headerows['rows'][] = $row;
  echo json_encode($headerows);
?>
