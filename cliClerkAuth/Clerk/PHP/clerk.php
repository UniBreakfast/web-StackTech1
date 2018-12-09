<?php

require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once 'clerkvars.php';

require_once '../_Commons/PHP/krumo.php';
require_once '../_Commons/PHP/f.php';

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
  case 'reg': {} break;
  case 'login': {} break;
  case 'check': {} break;
  case 'logout': {} break;
  case 'newpass': {} break;
  case 'rename': {} break;
  case 'unreg': {} break;
  default: {}
}

?>
