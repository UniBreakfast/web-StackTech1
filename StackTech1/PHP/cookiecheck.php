<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
//////////////////////////////////////////////////
//$_REQUEST['cookie'] = '17|Jeronimo';
//$_REQUEST['cookie'] = '17|Barnaby';
$newToken = 'Barnaby';
//$newToken = 'Jeronimo';
//////////////////////////////////////////////////
if (isset($_REQUEST['cookie']) && trim($_REQUEST['cookie'])!=='') {
  $cookie = trim($_REQUEST['cookie']);
  list($userid, $token) = explode('|', $cookie);

  $query = "SELECT id, dt_modify FROM test_sessions
            WHERE user_id = $userid AND token = '$token'";
  $result = mysqli_query($db, $query)
    or exit ('SELECT token FROM test_sessions Query failed');
  if (list($id, $dtModify) = mysqli_fetch_row($result))
    if (strtotime($dtModify)+216000 /*2.5days*/ - time() > 0) {
      $query = "UPDATE test_sessions SET token='$newToken' WHERE id=$id";
      mysqli_query($db, $query)
        or exit ('UPDATE test_sessions SET token Query failed');
      echo "valid|$newToken";
    } else {
      $query = "DELETE FROM test_sessions WHERE id=$id";
      mysqli_query($db, $query)
        or exit ('DELETE FROM test_sessions Query failed');
      echo "invalid";
    }
  else echo 'invalid';
}
else echo 'no cookie provided for checking';
?>
