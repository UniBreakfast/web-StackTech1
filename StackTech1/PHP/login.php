<?php
//$_REQUEST['login'] = 'Limi'; $_REQUEST['password'] = 'Ted';
#############################################################
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once $_SERVER['DOCUMENT_ROOT'].'StackTech1/PHP/seq.php';
if (isset($_REQUEST['login'])     and isset($_REQUEST['password']) and
     trim($_REQUEST['login'])!=='' and trim($_REQUEST['password'])!=='') {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);

  $query = "SELECT id, passhash FROM test_users
            WHERE login='$login'";
  $result = mysqli_query($db, $query) or exit ('SELECT id Query failed!');

  if (list($userid, $hash)=mysqli_fetch_row($result)) {
    $query = "DELETE FROM test_sessions
              WHERE dt_modify < NOW() - INTERVAL 60 HOUR";
    mysqli_query($db, $query)
      or exit ('DELETE FROM test_sessions WHERE dt_modify... Query failed!');

    if (hashCheck($password, $hash)) {
      $query = "SELECT id FROM test_sessions
                WHERE user_id = $userid ORDER BY dt_create";
      $result = mysqli_query($db, $query)
        or exit ('SELECT id ORDER BY dt_create... Query failed!');
      if (mysqli_num_rows($result)>2) {
        list($id) = mysqli_fetch_row($result);
        $query = "DELETE FROM test_sessions WHERE id = $id";
        mysqli_query($db, $query)
          or exit ('DELETE FROM test_sessions WHERE id... Query failed!');
      }

      $token = tokenGen();
      $query = "INSERT test_sessions (user_id, token) VALUE ($userid, '$token')";
      mysqli_query($db, $query)
        or exit ('INSERT user_id, token... Query failed!');
      setcookie('user', "$userid|$token", time()+216000, '/');
      header('Location: ../inside.htm');
    }
    else {
      setcookie('login',    $login,    time()+5, '/');
      setcookie('check', 'wrong_pass', time()+5, '/');
      header('Location: ../login.htm');
    }
  }
  else {
    setcookie('login',    $login,     time()+5, '/');
    setcookie('check', 'wrong_login', time()+5, '/');
    header('Location: ../login.htm');
  }
}
else echo 'No login or password provided'

?>
