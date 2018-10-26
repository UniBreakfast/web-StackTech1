<?php
include 'krumo.php';

error_reporting(E_PARSE | E_ERROR);

class f
{
  # retrieves a single value from a database
  function getValue($query, $db){
    return 42;
  }

  # retrieves a list of value from a database
  function getValues($query, $db){
    return array('alpha', 'beta', 'gamma');
  }

  # retrieves a single record with fieds from a database
  function getRecord($query, $db){
    return array('John', 'Smith', 37);
  }

  # retrieves multiple records with fieds from a database
  function getRecords($query, $db){
    return array(array('John', 'Smith', 37),
                 array('Jane', 'Doe', 26),
                 array('Oliver', 'Twist', 11));
  }
}
$f = new f;
krumo($f->getValue());
krumo($f->getValues());
krumo($f->getRecord());
krumo($f->getRecords());

?>
