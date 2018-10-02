<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
/*
foreach (getallheaders() as $name => $value) {
  echo "<pre>$name: $value\n</pre>";
}
*/
echo $_SERVER['REMOTE_ADDR'];
?>
