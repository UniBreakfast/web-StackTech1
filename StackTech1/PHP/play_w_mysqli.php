<?php
/*
http://localhost/web-StackTech1/
http://p.acoras.in.ua/
*/
//require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';


include 'krumo.php';
krumo(array(1,2,3));

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

//throw new Exception('Division by zero.');
//phpinfo();

//date_default_timezone_set('Europe/Kiev');
//ini_set('display_errors', 1);
//error_reporting(E_PARSE | E_ERROR);
//ini_set('error_prepend_string', '<p style="font-family:Trebuchet MS, arial; font-size: 3vmin; color: red">');
//ini_set('error_append_string', '</p>');
?>
