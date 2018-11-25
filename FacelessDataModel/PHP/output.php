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

$test_endeavors =
  f::getRecords($db, 'SELECT name, category, deadline FROM test_endeavors');

$for_output = array (
  'endeavors' => array (
    'class'   => 'Endeavor',
    'headers' => array ('name', 'category', 'deadline'),
    'rows'    => $test_endeavors
  )
);
//krumo($test_endeavors);
//krumo($for_output);

$output = json_encode($for_output);

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

echo $output;

?>
