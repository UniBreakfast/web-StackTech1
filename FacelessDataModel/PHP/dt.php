<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once '../_Commons/PHP/krumo.php';
require_once '../_Commons/PHP/f.php';
require_once 'usercheck.php';

/*
$for_output = array (
  'endeavors' => array (
    'class' => 'Endeavor',
    'headers' => array ('name', 'category', 'deadline'),
    'rows' => array (
      array ('Разбогатеть', 'сильное желание', null),
      array ('Стать красивее', 'мечта', null)
    )
  ),
  'activities' => array (
    'class' => 'Activity',
    'headers' => array ('name', 'amount', 'difficulty'),
    'rows' => array (
      array ('Отжиматься', '20 раз', 7),
      array ('Работать', '8 часов', 10)
    )
  )
);
*/

$userTable    = 'test_users';
$sessionTable = 'test_sessions';

switch ($_REQUEST['task']) {
  case 'usercheck': {
    echo userCheck($db, $sessionTable);
  } break;
  case 'signin': {
    $login    = trim($_REQUEST['login']);
    $password = trim($_REQUEST['password']);
    if ($login and $password) {
      $query = "SELECT id, passhash FROM $userTable WHERE login = ?";
      $param = array(array($login, 's'));
      if (list($userid, $hash) = f::getRecord($db, $query, $param)) {
        $query = "DELETE FROM $sessionTable
                  WHERE dt_modify < NOW() - INTERVAL 60 HOUR";
        mysqli_query($db, $query) or exit ("D F $sessionTable W d Q f");
        require_once '../_Commons/PHP/seq.php';
        if (hashCheck($password, $hash)) {
          $query = "SELECT id FROM $sessionTable
                WHERE user_id = $userid ORDER BY dt_create";
          $result = mysqli_query($db, $query)
            or exit ('SELECT id ORDER BY dt_create... Query failed!');
          if (mysqli_num_rows($result)>2) {
            list($id) = mysqli_fetch_row($result);
            $query = "DELETE FROM $sessionTable WHERE id = $id";
            mysqli_query($db, $query)
              or exit ("DELETE FROM $sessionTable WHERE id... Query failed!");
          }

          $addr     = $_SERVER['REMOTE_ADDR'];
          $addrpart = substr($addr, 0, strrpos($addr, '.'));
          $agent    = $_SERVER['HTTP_USER_AGENT'];
          $hash     = hashGen("$addrpart $agent");
          $token    = tokenGen();
          $query = "INSERT $sessionTable (user_id, token, bfp_hash)
                    VALUE ($userid, '$token', '$hash')";
          mysqli_query($db, $query)
            or exit ('INSERT user_id, token... Query failed!');

          echo json_encode(array($userid, $token));
        }
        else echo 'wrong password';
      }
      else echo 'no user with this login';
    }
    else echo 'no login and/or password provided';
  } break;
  default: {
    $user = trim($_REQUEST['user']);
    if (!$user) exit ('no user name provided');
    else {
      $userId = f::getValue($db, 'SELECT id FROM test_users WHERE login = ?',
                            array(array($user,'s')));
      if(!$userId) exit ('user not found');
    }


    $table = trim($_REQUEST['table']);
    if (!$table) $table = 'test_endeavors';

    $fields = trim($_REQUEST['fields']);
    if ($fields) $fields = json_decode($fields);
    else         $fields = array ('name', 'category', 'deadline');
    $test_endeavors =
      f::getRecords($db, 'SELECT '.implode($fields, ', ') // $table not safe?
                    ." FROM $table WHERE user_id = ?", array(array($userId,'i')));

    $for_output = array (
      'endeavors' => array (
        'class'   => 'Endeavor',
        'headers' => $fields,
        'rows'    => $test_endeavors
      )
    );

    $output = json_encode($for_output);
    echo $output;
  }
}




/*
$raw_output = '
{
  "endeavors" : {
    "class"  : "Endeavor",
    "headers": ["name", "category", "deadline"],
    "rows"   : [
      ["Разбогатеть",     "желание",  "NULL"],
      ["Стать красивее",  "мечта",    "NULL"]
    ]
  },
  "activities" : {
    "class"  : "Activity",
    "headers": ["name", "amount", "difficulty"],
    "rows"   : [
      ["Отжиматься", "20 раз",  7],
      ["Работать",  "8 часов",  10]
    ]
  }
}';
*/

?>
