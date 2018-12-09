<?php

require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once 'clerkvars.php';

require_once $commonsPath.'krumo.php';
require_once $commonsPath.'f.php';

require_once 'Auth/randhash.php';



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
      if (f::getValue($db, $q, $p)) {
        $response = array('msg'=>
                          array('type'=>'FAILED', 'code'=>101,
                                'text'=>"Login $login already occupied"));
        echo json_encode($response);
      }
      else {
        $hash = hashStr($pass);
        $q = "INSERT $tblUsers (login, passhash) VALUES (?, ?)";
        $p = array(array($login, 's'), array($hash, 's'));
        f::execute($db, $q, $p);
        $response = array('msg'=>
                          array('type'=>'SUCCESS', 'code'=>100,
                                'text'=>"User $login is registered!"));
        echo json_encode($response);
      }
    }
    else {
      $response = array('msg'=>
                        array('type'=>'ERROR', 'code'=>102,
                              'text'=>"Not enough data to register!"));
      echo json_encode($response);
    }
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

          $response = array('msg'=>
                            array('type'=>'SUCCSESS', 'code'=>103,
                                  'text'=>"You are signed in now as $login!"),
                            'data'=>
                            array('userid'=>$userid, 'token'=>$token,
                                  'expire'=>$sessTime));
          echo json_encode($response);
        }
        else {
          $response = array('msg'=>
                          array('type'=>'FAILED', 'code'=>104,
                                'text'=>"Can't sign in. Incorrect password!"));
          echo json_encode($response);
        }



      }
      else {
        $response = array('msg'=>
                        array('type'=>'FAILED', 'code'=>105,
                              'text'=>"Can't sign in. User $login not found!"));
        echo json_encode($response);
      }
    }
    else {
      $response = array('msg'=>
                        array('type'=>'ERROR', 'code'=>106,
                              'text'=>"Not enough data to sign in!"));
      echo json_encode($response);
    }
  } break;
  case 'check': {} break;
  case 'logout': {} break;
  case 'newpass': {} break;
  case 'rename': {} break;
  case 'unreg': {} break;
  default: {}
}

?>
