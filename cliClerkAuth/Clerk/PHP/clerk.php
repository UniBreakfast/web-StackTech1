<?php

require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once 'clerkvars.php';

require_once $commonsPath.'krumo.php';
require_once $commonsPath.'f.php';

require_once 'Auth/randhash.php';

function Response($code, $type, $text, $data=null) {
  switch ($type) {
    case 'S': $type = 'SUCCESS';  break;
    case 'F': $type = 'FAIL';     break;
    case 'E': $type = 'ERROR';    break;
    case 'I': $type = 'INFO';     break;
  }
  $msg = array('code'=>$code, 'type'=>$type, 'text'=>$text);
  if ($data) return array('msg' => $msg, 'data' => $data);
  else return array('msg' => $msg);
}

switch ($_REQUEST['task']) {
  case 'usercheck': {
    echo userCheck($db, $sessionTable);
  } break;
  case 'signin': {
    require_once 'signin.php';
    echo signIn($db, $userTable, $sessionTable);
  } break;
  case 'signout': {
    require_once 'signout.php';
    echo signOut($db, $sessionTable);
  } break;
  case 'reg': {
    list ($login, $pass) = f::request('login', 'pass');
    if ($login and $pass) {
      $q = "SELECT login FROM $tblUsers WHERE login = ?";
      $p = array(array($login, 's'));
      if (f::getValue($db, $q, $p))
        echo json_encode(Response(101, 'F', "Login $login already occupied"));
      else {
        $hash = hashStr($pass);
        $q = "INSERT $tblUsers (login, passhash) VALUES (?, ?)";
        $p = array(array($login, 's'), array($hash, 's'));
        f::execute($db, $q, $p);
        echo json_encode(Response(100, 'S', "User $login is registered!"));
      }
    }
    else echo json_encode(Response(102, 'E', "Not enough data to register!"));
  } break;
  case 'login': {
    list ($login, $pass) = f::request('login', 'pass');
    if ($login and $pass) {
      $q = "SELECT id, passhash FROM $tblUsers WHERE login = ?";
      $p = array(array($login, 's'));
      if (list ($userid, $hash) = f::getRecord($db, $q, $p)) {

        if (hashCheck($pass, $hash)) {
          $token = randStr();
          $q = "INSERT $tblSessions (user_id, token) VALUES (?, ?)";
          $p = array(array($userid,'i'), array($token,'s'));
          f::execute($db, $q, $p);

          $q = "DELETE FROM $tblSessions WHERE user_id = ? AND dt_modify <
                  (SELECT min(dt_modify) FROM
                    (SELECT dt_modify FROM $tblSessions WHERE user_id = ?
                      ORDER BY dt_modify DESC LIMIT $sessNum) AS tmp)";
          $p = array(array($userid,'i'), array($userid,'i'));
          f::execute($db, $q, $p);

          $data = array('userid'=>$userid,'token'=>$token,'expire'=>$sessTime);
          echo json_encode(Response(103,'S',"You are signed in now as $login!",                           $data));
        }
        else echo json_encode(Response(104, 'F',
                                       "Can't sign in. Incorrect password!"));
      }
      else echo json_encode(Response(105, 'F',
                                     "Can't sign in. User $login not found!"));
    }
    else echo json_encode(Response(106, 'E', "Not enough data to sign in!"));
  } break;
  case 'check': {} break;
  case 'logout': {} break;
  case 'newpass': {} break;
  case 'rename': {} break;
  case 'unreg': {} break;
  default: {}
}

?>
