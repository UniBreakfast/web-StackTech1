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

  private static function _query($query, $db, $params) {
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
      foreach($params as &$param) {
        $param_types .= $param[1];
        $c_u_f_a_params[] = &$param[0];
      }
      array_unshift($c_u_f_a_params, $stmt, $param_types);
      call_user_func_array('mysqli_stmt_bind_param', $c_u_f_a_params);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $a, $b);
      while (mysqli_stmt_fetch($stmt)) {
        $results[] = $a;
        $results[] = $b;
      }
      krumo($results);
      mysqli_stmt_close($stmt);
    }
  }


  private static function old_query($query, $db, $params) {
    $stmt = mysqli_stmt_init($db);
    $query = "SELECT id, record FROM test_list";
    //$query = "SELECT id, record FROM test_list WHERE id = ?";
    if (mysqli_stmt_prepare($stmt, $query)) {
      $results[] = f::_acronym($query);
      //foreach($params as $param)
      //  mysqli_stmt_bind_param($stmt, $param[1], $param[0]);
      mysqli_stmt_execute($stmt) or exit("$results[0] Query failed!");
      //$results[] = mysqli_stmt_num_rows($stmt);
      $results[] = mysqli_stmt_field_count($stmt);
      $result = array_fill(0, $results[1], null);
      array_unshift($result, $stmt);
      krumo($result);
      mysqli_stmt_bind_result($stmt, $results[], $results[]);
      krumo(call_user_func_array('mysqli_stmt_bind_result', $result));
      while (mysqli_stmt_fetch($stmt)) {
        $results[] = $result[1];
        $results[] = $result[2];
      }
      //mysqli_stmt_fetch($stmt);
      mysqli_stmt_store_result($stmt);
      mysqli_stmt_close($stmt);
      //krumo($stmt);
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
    $result  = f::_query($query, $db, $params);
    //$q_short = array_shift($result);
    //$rows    = array_shift($result);
    //$fields  = array_shift($result);
    //krumo($q_short, $rows, $fields, $result);
    /*    if     ($rows>1 and $fields>1)
      exit("$q_short Query retrieved $rows rows
                                with $fields fiedls instead of one value!");
    elseif ($rows>1)
      exit("$q_short Query retrieved $rows rows instead of one value!");
    elseif ($fields>1)
      exit("$q_short Query retrieved $fields fiedls instead of one value!");
    else return $value;*/
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
//krumo(f::getValue($db, "SELECT id, record FROM test_list WHERE id > ? AND id < ?", array(array(62, 'i'), array(64, 'i'))));
krumo(f::getValue($db, "SELECT id, record FROM test_list WHERE id > ? AND id < ? AND record = ?", array(array('60', 'i'), array('66', 'i'), array('Terminator', 's') )));
//krumo(f::getValue($db, "SELECT id, record FROM test_list WHERE id > ?", array(array(63, 'i'))));
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
//krumo::functions();
?>
