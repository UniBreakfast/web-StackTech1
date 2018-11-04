<?php
/*
http://localhost/web-StackTech1/
http://p.acoras.in.ua/
*/
//require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';

include 'krumo.php';

##################################################


$login    = trim($_REQUEST['login']);
$password = trim($_REQUEST['password']);

krumo($login, $password);

krumo(array($_REQUEST['login'], $_REQUEST['password']));

if (isset($_REQUEST['login'])      and isset($_REQUEST['password'])) {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);
  krumo(array($login, $password));
}

if (isset($_REQUEST['login'])      and isset($_REQUEST['password']) and
     trim($_REQUEST['login'])!=='' and  trim($_REQUEST['password'])!=='') {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);
  krumo(array($login, $password));
}

?>
