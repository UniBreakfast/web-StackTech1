<?php
require_once $_SERVER['DOCUMENT_ROOT'].'sandbox.php';
include 'krumo.php';

error_reporting(E_PARSE | E_ERROR);

# just a convenient object with utilitary functions
class f
{

  private static function _acronym($string) {
    $words = explode(' ', $string);
    $acronym = '"';
    foreach ($words as $word) {
      $word = trim($word);
      $acronym .= $word[0].' ';
    }
    return trim($acronym).'"';
  }

  private static function _query($query, $db, $params=array()) {
    $stmt = mysqli_stmt_init($db);
    $query = "SELECT id, record FROM test_list WHERE id = ?";
    if (mysqli_stmt_prepare($stmt, $query)) {
      $results[] = f::_acronym($query);
      foreach($params as $param)
        mysqli_stmt_bind_param($stmt, $param[1], $param[0]);
      mysqli_stmt_execute($stmt) or exit("$results[0] Query failed!");
      $results[] = mysqli_stmt_num_rows($stmt);
      $results[] = mysqli_stmt_field_count($stmt);
      mysqli_stmt_bind_result($stmt, $results[], $results[]);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      krumo($results);
      return $results;
    }
  }
/*

  private static function _query($query, $db) {
    $results[] = f::_acronym($query);
    $results[] = mysqli_query($db, $query) or exit("$results[0] Query failed!");
    $results[] = mysqli_num_rows  ($results[1]);
    $results[] = mysqli_num_fields($results[1]);
    return $results;
  }
*/

  # retrieves a single field value from a database
  static function getValue($db, $query, $params=array()) {
    list($q_short, $result, $rows, $fields) = f::_query($query, $db, $params);
    if     ($rows>1 and $fields>1)
      exit("$q_short Query retrieved $rows rows
                                with $fields fiedls instead of one value!");
    elseif ($rows>1)
      exit("$q_short Query retrieved $rows rows instead of one value!");
    elseif ($fields>1)
      exit("$q_short Query retrieved $fields fiedls instead of one value!");
    else return $value;
  }
/*
  static function getValue($query, $db) {
    list($q_short, $result, $rows, $fields) = f::_query($query, $db);
    if     ($rows>1 and $fields>1)
      exit("$q_short Query retrieved $rows rows
                                with $fields fiedls instead of one value!");
    elseif ($rows>1)
      exit("$q_short Query retrieved $rows rows instead of one value!");
    elseif ($fields>1)
      exit("$q_short Query retrieved $fields fiedls instead of one value!");
    elseif (list($value) = mysqli_fetch_row($result)) return $value;
  }
*/

  # retrieves multiple rows from a database with one value each
  static function getValues($query, $db) {
    list($q_short, $result, $rows, $fields) = f::_query($query, $db);
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
    list($q_short, $result, $rows, $fields) = f::_query($query, $db);
    if ($rows>1) exit("$q_short Query retrieved $rows rows instead of one!");
    elseif ($values = mysqli_fetch_row($result)) return $values;
  }

  # retrieves multiple records with fieds from a database
  static function getRecords($query, $db) {
    list($q_short, $result, $rows, $fields) = f::_query($query, $db);
    if ($rows>0) {
      $records = array();
      while ($values = mysqli_fetch_row($result)) $records[] = $values;
      return $records;
    }
  }
}

//krumo(f::getValue("SELECT id, record FROM test_list", $db));
//krumo(f::getValue("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getValue("SELECT id FROM test_list", $db));
//krumo(f::getValue("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getValue($db, "SELECT record FROM test_list WHERE id > ?", array(array(64, 'i'))));
//krumo(f::getValue("SELECT id FROM test_list WHERE record = 'Termin'", $db));
//krumo(f::getValues("SELECT id, record FROM test_list", $db));
//krumo(f::getValues("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getValues("SELECT id FROM test_list", $db));
//krumo(f::getValues("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getValues("SELECT id FROM test_list WHERE record = 'Termin'", $db));
//krumo(f::getRecord("SELECT id, record FROM test_list", $db));
//krumo(f::getRecord("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getRecord("SELECT id FROM test_list", $db));
//krumo(f::getRecord("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getRecord("SELECT id FROM test_list WHERE record = 'Termin'", $db));
//krumo(f::getRecords("SELECT id, record FROM test_list", $db));
//krumo(f::getRecords("SELECT id, record FROM test_list LIMIT 1", $db));
//krumo(f::getRecords("SELECT id FROM test_list", $db));
//krumo(f::getRecords("SELECT id FROM test_list LIMIT 1", $db));
//krumo(f::getRecords("SELECT id FROM test_list WHERE record = 'Termin'", $db));
krumo::functions();
?>
