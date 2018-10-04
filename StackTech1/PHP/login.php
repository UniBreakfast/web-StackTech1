<?php
////////////////////////////////////////////
//$_REQUEST['login'] = 'Uni'; $_REQUEST['password'] = 'Breakfast';
////////////////////////////////////////////
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once $_SERVER['DOCUMENT_ROOT'].'StackTech1/PHP/seq.php';
if (isset($_REQUEST['login'])     and isset($_REQUEST['password']) and
     trim($_REQUEST['login'])!=='' and trim($_REQUEST['password'])!=='') {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);

  $query = "SELECT id FROM test_users
            WHERE login='$login' AND passhash='$password'";
  $result = mysqli_query($db, $query) or exit ('SELECT id Query failed!');

  if (list($userid)=mysqli_fetch_row($result)) {
    $query = "DELETE FROM test_sessions
              WHERE dt_modify < NOW() - INTERVAL 60 HOUR";
    mysqli_query($db, $query)
      or exit ('DELETE FROM test_sessions WHERE dt_modify... Query failed!');

    $token = tokenGen();
    $query = "INSERT test_sessions (user_id, token) VALUE ($userid, '$token')";
    mysqli_query($db, $query)
      or exit ('INSERT user_id, token... Query failed!');
    setcookie('user', "$userid|$token", time()+216000, '/');
    header('Location: ../inside.htm');
    //echo "valid|$userid|".tokenGen();
  }
  else echo 'invalid';
}
else echo 'No login or password provided'

?>
