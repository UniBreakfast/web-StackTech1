<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once $_SERVER['DOCUMENT_ROOT'].'StackTech1/PHP/seq.php';
/*
foreach (getallheaders() as $name => $value) {
  echo "<pre>$name: $value\n</pre>";
}
*/
//echo $_SERVER['REMOTE_ADDR']
$addr     = $_SERVER['REMOTE_ADDR'];
$addrpart = substr($addr, 0, strrpos($addr, '.'));
$agent    = $_SERVER['HTTP_USER_AGENT'];
$bfp      = $addrpart.' '.$agent;
$hash = hashGen($bfp);
echo hashCheck($bfp, $hash);
?>
