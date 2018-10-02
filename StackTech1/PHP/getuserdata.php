<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
/*
foreach (getallheaders() as $name => $value) {
  echo "<pre>$name: $value\n</pre>";
}
*/
//echo $_SERVER['REMOTE_ADDR']
echo substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], '.'))
  .' '.$_SERVER['HTTP_USER_AGENT'];
?>
