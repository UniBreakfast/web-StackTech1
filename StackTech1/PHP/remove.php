<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
  if (isset($_REQUEST['recid']) && trim($_REQUEST['recid'])!=='') {
    $record_id = trim($_REQUEST['recid']);
    $query = "DELETE FROM test_list WHERE id = $record_id";
    mysqli_query($db, $query) or exit ('DELETE record by id Query failed');
  }
?>
