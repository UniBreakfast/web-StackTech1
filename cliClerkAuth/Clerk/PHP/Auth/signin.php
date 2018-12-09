<?php

function signIn($db, $userTable, $sessionTable) {
  $login    = trim($_REQUEST['login']);
  $password = trim($_REQUEST['password']);
  if ($login and $password) {
    $query = "SELECT id, passhash FROM $userTable WHERE login = ?";
    $param = array(array($login, 's'));
    if (list($userid, $hash) = f::getRecord($db, $query, $param)) {
      $query = "DELETE FROM $sessionTable
                  WHERE dt_modify < NOW() - INTERVAL 60 HOUR";
      f::execute($db, $query);
      require_once '../_Commons/PHP/seq.php';
      if (hashCheck($password, $hash)) {
        $query = "SELECT id FROM $sessionTable
                    WHERE user_id = $userid ORDER BY dt_create";
        $ids = f::getValues($db, $query);
        if (sizeof($ids) > 2)
          f::execute($db, "DELETE FROM $sessionTable WHERE id = ".$ids[0]);

        $addr     = $_SERVER['REMOTE_ADDR'];
        $addrpart = substr($addr, 0, strrpos($addr, '.'));
        $agent    = $_SERVER['HTTP_USER_AGENT'];
        $hash     = hashGen("$addrpart $agent");
        $token    = tokenGen();
        $query = "INSERT $sessionTable (user_id, token, bfp_hash)
                    VALUE ($userid, '$token', '$hash')";
        f::execute($db, $query);

        return json_encode(array($userid, $token));
      }
      else return 'wrong password';
    }
    else return 'no user with this login';
  }
  else return 'no login and/or password provided';
}

# testing
# ?userid=3&token=Wr7f40rlIc9YmcCL8lgPQrhEDMsWlLIp
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';

}

?>
