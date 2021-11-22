<?php

namespace DBhandler;

use DBhandler\DeletePostWithId;
use DBhandler\ExecSQLstatement;
use DBhandler\GetPostWithId;
use DBhandler\StorePost;
require('STR.php');

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
  private $incData; // Incoming data array. 

  private $incomingIdColumn;
  private $incomingIdValue;
  private $incomingUpdateDataAsArray;

  private $database;
  private $table;

  private $stringOfColumns;
  private $stringOfValues;
  
  // *** PUBLIC METHODS ***
  function storePost(StorePost $dbParametersAndPostData) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndPostData);
    $sql = $this->SQL_storePost(); 
    $success = $this->performDBcall($dbConn, $sql);
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

  private function unpackDataAndOpenDBconnection($incomingData) {
    $this->incData = $incomingData;
    $this->unpackIncomingDataArray(); 
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

  private function unpackIncomingDataArray() {
    $this->extractDBparameters();

    if($this->itIsAPost()) {

      $this->extractColumns();
      $this->extractValues();

    }
    else if($this->itIsAnUpdate()) {
      $idArray = $this->incData->{self::ARRAYWITHID};
      foreach($idArray as $key => $val) {
        $this->incomingIdColumn = $key;
        $this->incomingIdValue = $val;
      }
      $this->incomingUpdateDataAsArray = $this->incData->{self::POSTDATA};
    }
    else if($this->itIsAnId()) {

      $idArray = $this->incData->{self::ARRAYWITHID};
      if($this->arrayHasMaxOneItem($idArray) == false)
        throw new \Exception("DBhandler received to long array. Only id is needed.");
      else
        foreach($idArray as $key => $val) {
          $this->incomingIdColumn = $key;
          $this->incomingIdValue = $val;
        }
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

  // Extraction functions
  private function extractDBparameters() {
    $this->database = $this->incData->{self::DATABASE};
    $this->table = $this->incData->{self::TABLE};
  }

  private function extractColumns() {
    foreach($this->incData->{self::POSTDATA} as $col => $val) {
      $this->stringOfColumns .= "{$col}, ";
    }

    $this->takeAwayTrailingComa($this->stringOfColumns);
  }

  private function extractValues() {
    foreach($this->incData->{self::POSTDATA} as $col => $val) {
      $this->stringOfValues .= "'{$val}', ";
    }

    $this->takeAwayTrailingComa($this->stringOfValues);
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");

  }

  private function itIsAPost() {
    $namespc = STR::INCNAMESPACE;
    $qury = (get_class($this->incData) == "{$namespc}StorePost");
    return $qury;
  }

  private function itIsAnUpdate() {
    $namespc = STR::INCNAMESPACE;
    $qury = (get_class($this->incData) == "{$namespc}UpdatePost");
    return $qury;
  }

  private function itIsAnId() {
    return isset($this->incData->{self::ARRAYWITHID});
    // $namespc = \STR::INCNAMESPACE;
    // $qury = (get_class($this->incData) == "{$namespc}GetPostWithId");
    // return $qury;
  }

  private function arrayHasMaxOneItem(array $arr) {
    return sizeof($arr) == 1;
  }
}

