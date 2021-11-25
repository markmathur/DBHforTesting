<?php

declare(strict_types = 1);

namespace DBhandler;


use DBhandler\DeletePostWithId;
use DBhandler\ExecSQLstatement;
use DBhandler\GetPostWithId;
use DBhandler\StorePost;
use mysqli;

require('STR.php');
require_once './DBhandler1_1/incomingDataClasses/Mother_targetPostWithId.php';
require_once './ENV.php';
require_once './DBhandler1_1/lib/Unpacker.php';

class DBhandler {

  // ABOUT
  // This is supposed to be an allround database handler which
  // can be used in all projects, using a MySQL server and PHP. 
  
  // The methods of this class requires incoming requests to pass objects of 
  // custom made classes (found in the same folder) which 
  // specify what data is needed. 

  // CONSTANTS
  const DATABASE = 'databaseName'; 
  const TABLE = 'tableName';
  const POSTDATA = 'dataAccToColumns';
  const ARRAYWITHID = "arrayWithId";
  const ARRAYWITHCRIT = 'arrayWithCriteria';
  const INCSQL = 'incomingSQLstatement';


  // PROPERTIES

  private string $incomingIdColumn;
  private string $incomingIdValue;
  private array $incomingUpdateDataAsArray; // This is used by updatePost(). It might be possible to switch it out for $stringOfColumns and $stringOfValues.

  private string $database;
  private string $table;

  private string $stringOfColumns;
  private string $stringOfValues;

  // *** GETTERS AND SETTERS ***
  function setDatabase(string $dbName) {$this->database = $dbName;}
  function getDatabase() {return $this->database;}

  function setTable(string $tableName) {$this->table = $tableName;}
  function getTable() {return $this->table;}


  function setIncomingIdColumn(string $col) {$this->incomingIdColumn = $col;}
  function getIncomingIdColumn() {return $this->incomingIdColumn;}


  function setIncomingIdValue(string $val) {$this->incomingIdValue = $val;}
  function getIncomingIdValue() {return $this->incomingIdValue;}


  function setIncomingUpdateDataAsArray(array $arr) {$this->incomingUpdateDataAsArray = $arr;}
  function getIncomingUpdateDataAsArray() {return $this->incomingUpdateDataAsArray;}


  function setStringOfColumns(string $soc) {$this->stringOfColumns = $soc;}
  function getStringOfColumns() {return $this->stringOfColumns;}

  function setStringOfValues( string $sov) {$this->stringOfValues = $sov;}
  function getStringOfValues() {return $this->stringOfValues; }


  // *** PUBLIC METHODS ***
  function storePost(StorePost $dbParametersAndPostData) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndPostData);
    $sql = $this->SQL_storePost(); 
    var_dump($sql);
    $success = $this->performDBcall($dbConn, $sql);
    var_dump(mysqli_error_list($dbConn));
    
    $dbConn->close();
    return $success;
  }

  function getPostWithId(GetPostWithId $dbParametersAndId) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $sql = $this->SQL_getPostWithId(); 
    $rawData = $this->performDBcall($dbConn, $sql);
    $postAsArray = $this->getPostAsArray($rawData);
    $dbConn->close();
    return $postAsArray;
  }

  function getAllPosts(GetAllPosts $dbParameters) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParameters);
    $sql = $this->SQL_getAllPosts(); 
    $rawData = $this->performDBcall($dbConn, $sql);
    $postAsArray = $this->getAllPostsAsArray($rawData);
    $dbConn->close();
    return $postAsArray;
  }

  function getPostsByCriteria(GetPostWithId $dbParametersAndId) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $sql = $this->SQL_getPostWithId(); 
    $rawData = $this->performDBcall($dbConn, $sql);
    $postAsArray = $this->getAllPostsAsArray($rawData);
    $dbConn->close();
    return $postAsArray;
  }

  function updatePost(UpdatePost $dbParametersAndUpdatedPost) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndUpdatedPost);
    $sql = $this->SQL_updatePost();
    $success = $this->performDBcall($dbConn, $sql);
    $dbConn->close();
    return $success;
  }

  function deletePostWithId(DeletePostWithId  $dbParametersAndId) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $sql = $this->SQL_deletePostWithId(); 
    $success = $this->performDBcall($dbConn, $sql);
    if(mysqli_affected_rows($dbConn) < 1) return false;
    $dbConn->close();
    return $success;
  }


  // *** PRIVATE SUPPORTING METHODS ***

  private function unpackDataAndOpenDBconnection($incData) {
    
    $unpacker = new Unpacker($this);
    $unpacker->unpackIncomingDataArray($incData); 
    
    $dbConn = $this->connectToServer();

    return $dbConn;
  }

  private function connectToServer() {
    $dbConn = mysqli_connect(\ENV::dbServer, \ENV::dbUsername, \ENV::dbPassword, $this->database);
  
    if(!$dbConn) {
      throw new \Exception('Can not establish connection to database.', 500);
    }

    return $dbConn;
  }

  private function performDBcall($dbConn, $sql) {
    try {
      return mysqli_query($dbConn, $sql);
    }
    catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  private function getPostAsArray($rawData) {
    // used by READING functions, after they have fetched the "row" from the database.
    if(mysqli_num_rows($rawData) > 0) {
      // while ($row = mysqli_fetch_assoc($result)) {
      //   var_dump($row);
      // }
      $postAsArray = mysqli_fetch_assoc($rawData);
      return $postAsArray;
    }
  }

  private function getAllPostsAsArray($rawData) {
    $allRowsArray = array();
    while ($row = mysqli_fetch_assoc($rawData)) {
      array_push($allRowsArray, $row);
    }

    return $allRowsArray;
  }

  // SQL generators
  private function SQL_storePost() {
    return "INSERT INTO $this->table 
    ($this->stringOfColumns) VALUES ($this->stringOfValues);";
  }

  private function SQL_getPostWithId() {
    return "SELECT * FROM $this->table WHERE $this->incomingIdColumn = '$this->incomingIdValue';";
  }

  private function SQL_getAllPosts() {
    return "SELECT * FROM $this->table;";
  }

  private function SQL_deletePostWithId() {
    return "DELETE FROM $this->table WHERE $this->incomingIdColumn = $this->incomingIdValue;";
  }

  private function SQL_updatePost() {
    
    $sql = "UPDATE $this->table SET ";
    foreach($this->incomingUpdateDataAsArray as $col => $val) {
      $sql .= " $col = '$val', ";
    }
    $this->takeAwayTrailingComa($sql);
    $sql .= " WHERE $this->incomingIdColumn = $this->incomingIdValue;";
    return $sql;
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");

  }
  // Extraction functions





}

