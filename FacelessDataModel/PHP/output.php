<?php


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
