<?php

function userCheck($db, $table) {
  $userId = trim($_REQUEST['userid']);
  $token  = trim($_REQUEST['token' ]);
  if ($userId and $token) {





    return 'nEwToKeN';
  }
  else if (!$userId and !$token) return 'no userid or token provided';
  else if (!$userId) return 'no user id provided';
  else               return 'no token provided';
}



echo userCheck();



?>
