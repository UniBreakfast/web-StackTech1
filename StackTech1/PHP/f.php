<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
include 'krumo.php';

error_reporting(E_PARSE | E_ERROR);

# just a convenient object with utilitary functions
class f
{

  private static function _acronym($string) {
    $words = explode(' ', $string);
    $acronym = '';
    foreach ($words as $word) {
      $word = trim($word);
      $acronym .= $word[0].' ';
    }
    return $acronym;
  }

  # retrieves a single field value from a database
  static function getValue($query, $db) {
    $q_short = f::_acronym($query);
    $result = mysqli_query($db, $query) or exit("$q_short Query failed!");
    $rows   = mysqli_num_rows  ($result);
    $fields = mysqli_num_fields($result);
    if     ($rows>1 and $fields>1)
      exit("$q_short Query retrieved $rows rows
                                with $fields fiedls instead of one value!");
    elseif ($rows>1)
      exit("$q_short Query retrieved $rows rows instead of one value!");
    elseif ($fields>1)
      exit("$q_short Query retrieved $fields fiedls instead of one value!");
    elseif (list($value) = mysqli_fetch_row($result)) return $value;
  }

  # retrieves multiple rows from a database with one value each
  static function getValues($query, $db) {
    $q_short = f::_acronym($query);
    $result = mysqli_query($db, $query) or exit("$q_short Query failed!");
    $rows   = mysqli_num_rows  ($result);
    $fields = mysqli_num_fields($result);
    if ($fields>1) exit("$q_short Query retrieved $rows row(s)
                    with $fields fiedls each instead of one value per row!");
    elseif ($rows>0) {
      $values = array();
      while (list($value) = mysqli_fetch_row($result)) $values[] = $value;
      return $values;
    }
  }

  # retrieves a row of fields values from a database
  static function getRecord($query, $db) {
    $q_short = f::_acronym($query);
    $result = mysqli_query($db, $query) or exit("$q_short Query failed!");
    $rows   = mysqli_num_rows  ($result);
    $fields = mysqli_num_fields($result);
    if ($rows>1) exit("$q_short Query retrieved $rows rows instead of one!");
    elseif ($values = mysqli_fetch_row($result)) return $values;
  }

  # retrieves multiple records with fieds from a database
  static function getRecords($query, $db) {
    $q_short = f::_acronym($query);
    $result = mysqli_query($db, $query) or exit("$q_short Query failed!");
    $rows   = mysqli_num_rows  ($result);
    $fields = mysqli_num_fields($result);
    if ($rows>0) {
      $records = array();
      while ($values = mysqli_fetch_row($result)) $records[] = $values;
      return $records;
    }
  }
}

//krumo(f::getValue("SELECT id FROM test_list WHERE record = 'Terminator'", $db));
//krumo(f::getValue("SELECT id, record FROM test_list", $db));
//krumo(f::getValue("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getValue("SELECT id FROM test_list", $db));
krumo(f::getValue("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getValues("SELECT id, record FROM test_list", $db));
//krumo(f::getValues("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getValues("SELECT id FROM test_list", $db));
krumo(f::getValues("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getValues("SELECT id FROM test_list WHERE record = 'Termin'", $db));
//krumo(f::getRecord("SELECT id, record FROM test_list", $db));
krumo(f::getRecord("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getRecord("SELECT id FROM test_list", $db));
krumo(f::getRecord("SELECT id FROM test_list LIMIT 1", $db));
krumo(f::getRecord("SELECT id FROM test_list WHERE record = 'Termin'", $db));
krumo(f::getRecords("SELECT id, record FROM test_list", $db));
krumo(f::getRecords("SELECT id, record FROM test_list LIMIT 1", $db));
krumo(f::getRecords("SELECT id FROM test_list", $db));
krumo(f::getRecords("SELECT id FROM test_list LIMIT 1", $db));
krumo(f::getRecords("SELECT id FROM test_list WHERE record = 'Termin'", $db));
krumo::functions();
?>
