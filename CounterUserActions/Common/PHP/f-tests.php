<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
require_once 'krumo.php';
require_once 'f.php';

#setValues
f::setValues($db, 'UPDATE test_list SET record = ? WHERE record = ?',
  array(array('new record text', 's'), array('old record text', 's')));

#putRecord
echo 'inserted record id='.
  f::putRecord($db, 'INSERT test_list (record) VALUE (?)',
                   array(array('new record text', 's')));

#getValue


#getValues


#getRecord


#getRecords

//krumo(f::getValue($db, "SELECT id FROM test_list WHERE id > ?", array(array(63, 'i'))));
//krumo(f::getValue($db, "SELECT id FROM test_list WHERE id > ?", array(array(63, 'i'))));
//krumo(f::getValues($db, "SELECT record FROM test_list"));
//krumo(f::getRecord($db, "SELECT id, record FROM test_list WHERE id > ?", array(array(63, 'i'))));
//krumo(f::getRecords($db, "SELECT id, record FROM test_list WHERE id > ? AND id < ?", array(array(62, 'i'), array(66, 'i'))));
//krumo(f::getRecords($db, "SELECT id, record, dt_create, dt_modify FROM test_list WHERE id > ? AND id < ?", array(array(62, 'i'), array(66, 'i'))));
//krumo(f::getRecord($db, "SELECT id, record, dt_create, dt_modify FROM test_list WHERE id > ? AND id < ?", array(array(65, 'i'), array(66, 'i'))));
//krumo(f::getRecord($db, "SELECT id, record FROM test_list WHERE id > ? AND id < ? AND record = ?", array(array('60', 'i'), array('66', 'i'), array('Terminator', 's') )));

?>
