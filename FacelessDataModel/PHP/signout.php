<?php

function signOut($db, $sessionTable) {
  $userId = trim($_REQUEST['userid']);
  $token  = trim($_REQUEST['token' ]);
  if ($userId and $token)  {
    require_once '../_Commons/PHP/f.php';

    $query = "DELETE FROM $sessionTable WHERE user_id = ? AND token = ?";
    $params = array(array($userId, 'i'), array($token, 's'));
    f::execute($db, $query, $params);

    return 'if there was this userid/token pair it has been deleted';
  }
  elseif (!$userId and !$token) return 'no userid or token provided';
  elseif (!$userId)             return 'no user id provided';
  else                          return 'no token provided';
}

?>
