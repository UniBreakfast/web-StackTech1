<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once $_SERVER['DOCUMENT_ROOT'].'StackTech1/Auth/PHP/seq.php';
if (isset($_REQUEST['login'])     and isset($_REQUEST['password']) and
     trim($_REQUEST['login'])!=='' and trim($_REQUEST['password'])!=='') {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);

  $query = "SELECT id FROM test_users
            WHERE login='$login'";
  $result = mysqli_query($db, $query) or exit ('SELECT id Query failed!');

  if (list($userid)=mysqli_fetch_row($result)) {
    setcookie('login',   $login,   time()+5, '/');
    setcookie('check', 'occupied', time()+5, '/');
    header('Location: ../../register.htm');
  }
  else {
    $hash = hashGen($password);
    $query = "INSERT test_users (login, passhash) VALUE ('$login', '$hash')";
    mysqli_query($db, $query)
      or exit ('INSERT test_users... Query failed!');

    setcookie('login', $login, time()+5, '/');
    header('Location: ../../login.htm');
  }
}
else echo 'No login or password provided'

?>
