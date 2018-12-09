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
  case 'check': {} break;
  case 'logout': {} break;
  case 'newpass': {} break;
  case 'rename': {} break;
  case 'unreg': {} break;
  default: {}
}

?>
