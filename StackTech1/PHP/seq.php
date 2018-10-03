<?php

function tokenGen() {
  $chrs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; $token = '';
  for ($i = 0; $i < 32; ++$i) {
    $chrs = str_shuffle($chrs);
    $token .= $chrs[rand(0, 60)];
  }
  return $token;
}

?>
