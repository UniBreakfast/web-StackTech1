<?php

function userCheck($db, $sessionTable) {
  $userId = trim($_REQUEST['userid']);
  $token  = trim($_REQUEST['token' ]);
  if ($userId and $token)  {
    require_once '../_Commons/PHP/f.php';
    $agent    = $_SERVER['HTTP_USER_AGENT'];
    $addr     = $_SERVER['REMOTE_ADDR'    ];
    $addrpart = substr($addr, 0, strrpos($addr, '.'));
    $bfp      = "$addrpart $agent";

    $query = "SELECT id, bfp_hash, dt_modify FROM $sessionTable
              WHERE user_id = ? AND token = ?";
    $params = array(array($userId, 'i'), array($token, 's'));

    if (list($id, $hash, $dtModify) = f::getRecord($db, $query, $params)) {
      require_once '../_Commons/PHP/seq.php';
      //exit (hashGen($bfp));
      //if (
      if (hashCheck($bfp, $hash) and
          strtotime($dtModify)+216000 /*2.5days*/ - time() > 0) {
        $newToken = tokenGen();
        //$newToken = $token;
        $query = "UPDATE $sessionTable SET token='$newToken' WHERE id=$id";
        mysqli_query($db, $query)
          or exit ("UPDATE $sessionTable SET token Query failed!");
        return $newToken;
      }
      else {
        $query = "DELETE FROM $sessionTable WHERE id=$id";
        mysqli_query($db, $query)
          or exit ("DELETE FROM $sessionTable Query failed!");
        echo "no session found with this userid/token pair";
      }
    }
    else echo "no session found with this userid/token pair";
  }
  elseif (!$userId and !$token) return 'no userid or token provided';
  elseif (!$userId)             return 'no user id provided';
  else                          return 'no token provided';
}

# testing
# ?userid=3&token=Wr7f40rlIc9YmcCL8lgPQrhEDMsWlLIp
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
  require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';

  $result = userCheck($db, 'test_sessions');
  if (substr($result, 0, 3) != 'no ')
    echo "<a href=http://p.acoras.in.ua/FacelessDataModel/PHP/usercheck.php".
                    "?userid=3&token=$result>
                      $result
                    </a>";
  else echo $result;
}

?>