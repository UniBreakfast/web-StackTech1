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
  $response = array('msg' => array('code'=>$code,'type'=>$type,'text'=>$text));
  if ($data) $response['data'] = $data;
  return $response;
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
    else echo json_encode(Response(102, 'E',
                                   "Not enough credentials to register!"));
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

          echo json_encode(Response(103,'S',"You are signed in now as $login!",
            array('userid'=>$userid, 'token'=>$token, 'expire'=>$sessExpire)));
        }
        else echo json_encode(Response(104, 'F',
                                       "Can't sign in. Incorrect password!"));
      }
      else echo json_encode(Response(105, 'F',
                                     "Can't sign in. User $login not found!"));
    }
    else echo json_encode(Response(106, 'E',
                                   "Not enough credentials to sign in!"));
  } break;
  case 'check': {
    list ($userid, $token) = f::request('userid', 'token');
    if ($userid and $token) {
      $q = "SELECT id FROM $tblSessions WHERE user_id = ? AND token = ?
            AND dt_modify > CURDATE() - INTERVAL $sessExpire DAY";
      $p = array(array($userid, 'i'), array($token, 's'));
      if ($id = f::getValue($db, $q, $p)) {
        $token = randStr();
        $q = "UPDATE $tblSessions SET token = ? WHERE id = ?";
        $p = array(array($token, 's'), array($id, 'i'));
        f::execute($db, $q, $p);
        echo json_encode(Response(107, 'I', "Session confirmed, "
          ."you are signed in", array('token'=>$token, 'expire'=>$sessExpire)));
      }
      else echo json_encode(Response(108, 'I', "No such session in act"));
    }
    else echo json_encode(Response(110, 'E',
                                   "No complete session cookie provided"));
  } break;
  case 'logout': {
    list ($userid, $token) = f::request('userid', 'token');
    if ($userid and $token) {
      $q = "DELETE FROM $tblSessions WHERE user_id = ? AND token = ?";
      $p = array(array($userid, 'i'), array($token, 's'));
      f::execute($db, $q, $p);
    }
    else echo json_encode(Response(112, 'E',
                                   "No complete session cookie provided"));
  } break;
  case 'newpass': {
    list (       $login,  $oldpass,  $newpass ) =
      f::request('login', 'oldpass', 'newpass');
    if ($login and $oldpass and $newpass) {
      $q = "SELECT id, passhash FROM $tblUsers WHERE login = ?";
      $p = array(array($login, 's'));
      if (list ($userid, $hash) = f::getRecord($db, $q, $p)) {

        if (hashCheck($oldpass, $hash)) {
          $hash = hashStr($newpass);
          $q = "UPDATE $tblUsers SET passhash = ? WHERE id = ?";
          $p = array(array($hash,'s'), array($userid,'i'));
          f::execute($db, $q, $p);
          echo json_encode(Response(114, 'S',
                                    "Password changed for user $login"));
        }
        else echo json_encode(Response(115, 'F',
                                 "Can't change password. Incorrect password!"));
      }
      else echo json_encode(Response(116, 'F',
                               "Can't change password. No user $login found!"));
    }
    else echo json_encode(Response(117, 'E',
                                 "Not enough credentials to change password!"));
  } break;
  case 'rename': {
    list (       $oldlogin,  $pass,  $newlogin ) =
      f::request('oldlogin', 'pass', 'newlogin');
    if ($oldlogin and $pass and $newlogin) {
      $q = "SELECT id, passhash FROM $tblUsers WHERE login = ?";
      $p = array(array($oldlogin, 's'));
      if (list ($userid, $hash) = f::getRecord($db, $q, $p)) {

        if (hashCheck($pass, $hash)) {
          $q = "UPDATE $tblUsers SET login = ? WHERE id = ?";
          $p = array(array($newlogin,'s'), array($userid,'i'));
          f::execute($db, $q, $p);
          echo json_encode(Response(118, 'S',
                                  "Login changed from $oldlogin to $newlogin"));
        }
        else echo json_encode(Response(119, 'F',
                                    "Can't change login. Incorrect password!"));
      }
      else echo json_encode(Response(120, 'F',
                               "Can't change login. No user $oldlogin found!"));
    }
    else echo json_encode(Response(121, 'E',
                                   "Not enough credentials to change login!"));
  } break;
  case 'unreg': {
    list ($login, $pass) = f::request('login', 'pass');
    if ($login and $pass) {
      $q = "SELECT id, passhash FROM $tblUsers WHERE login = ?";
      $p = array(array($login, 's'));
      if (list ($userid, $hash) = f::getRecord($db, $q, $p)) {

        if (hashCheck($pass, $hash)) {
          $q = "DELETE FROM $tblUsers WHERE id = ?";
          $p = array(array($userid,'i'));
          f::execute($db, $q, $p);
          echo json_encode(Response(122,'S',"User $login removed!"));
        }
        else echo json_encode(Response(123, 'F',
                                      "Can't unregister. Incorrect password!"));
      }
      else echo json_encode(Response(124, 'F',
                         "Can't unregister. No user with login $login found!"));
    }
    else echo json_encode(Response(125, 'E',
                                   "Not enough credentials to unregister!"));
  } break;
  default: {}
}

?>
