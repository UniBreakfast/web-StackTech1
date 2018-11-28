<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once '../_Commons/PHP/krumo.php';
require_once '../_Commons/PHP/f.php';

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
