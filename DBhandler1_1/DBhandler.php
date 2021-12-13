<?php

declare(strict_types = 1);

namespace DBhandler;


use DBhandler\DeletePostWithId;
use DBhandler\ExecSQLstatement;
use DBhandler\GetPostWithId;
use DBhandler\StorePost;
use mysqli;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once 'STR.php';
require_once './DBhandler1_1/incomingDataClasses/Mother_targetPostWithOneCriteria.php';
require_once './ENV.php';
require_once './DBhandler1_1/lib/Unpacker.php';
require_once './DBhandler1_1/lib/SQLgenerator.php';
require_once './DBhandler1_1/lib/StmtHandler.php';

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

  private string $incomingCritColumn;
  private string $incomingCritValue;
  private array $incomingUpdateDataAsArray; // This is used by updatePost(). It might be possible to switch it out for $stringOfColumns and $stringOfValues.

  private string $database;
  private string $table;

  private string $stringOfColumns;
  private string $stringOfValues;

  private array $postData;

  // SUPPORTING LIB

  private $sqlGen;
  private $stmtGen;

  function __construct()
  {
    $this->sqlGen = new SQLgenerator($this);
    $this->stmtHandler = new StmtHandler($this);
  }

  // *** GETTERS AND SETTERS ***
  function setDatabase(string $dbName) {$this->database = $dbName;}
  function getDatabase() {return $this->database;}

  function setTable(string $tableName) {$this->table = $tableName;}
  function getTable() {return $this->table;}


  function setIncomingCritColumn(string $col) {$this->incomingCritColumn = $col;}
  function getIncomingCritColumn() {return $this->incomingCritColumn;}


  function setIncomingCritValue(string $val) {$this->incomingCritValue = $val;}
  function getIncomingCritValue() {return $this->incomingCritValue;}


  function setIncomingUpdateDataAsArray(array $arr) {$this->incomingUpdateDataAsArray = $arr;}
  function getIncomingUpdateDataAsArray() {return $this->incomingUpdateDataAsArray;}


  function setStringOfColumns(string $soc) {$this->stringOfColumns = $soc;}
  function getStringOfColumns() {return $this->stringOfColumns;}

  function setStringOfValues( string $sov) {$this->stringOfValues = $sov;}
  function getStringOfValues() {return $this->stringOfValues; }

  function setPostData(array $pd) {$this->postData = $pd;}
  function getPostData() {return $this->postData;}


  // *** PUBLIC METHODS ***
  function storePost(StorePost $dbParametersAndPostData) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndPostData);
    $success = $this->stmtHandler->storePost($dbConn, $this->postData);

    return $success;
  }

  function getPostWithId(GetPostWithId $dbParametersAndId) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $postAsArray = $this->stmtHandler->getPostWithId($dbConn);

    return $postAsArray;
  }

  function getAllPosts(GetAllPosts $dbParameters) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParameters);
    $sql = "SELECT * FROM {$this->getTable()};";
    $rawData = $this->performDBcall($dbConn, $sql);
    $postAsArray = $this->getAllPostsAsArray($rawData);
    $dbConn->close();
    return $postAsArray;
  }

  function getPostsByCriteria(GetPostsByCriteria $dbParametersAndId) {
    // This should not replace getPostWithId because getting with id
    // is the only reading method that guarantees only ONE post. 
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $postsAsArray = $this->stmtHandler->getPostsByCriteria($dbConn);

    // $sql = $this->sqlGen->SQL_getPostWithId(); 
    // $rawData = $this->performDBcall($dbConn, $sql);
    // $postAsArray = $this->getAllPostsAsArray($rawData);
    // $dbConn->close();
    return $postsAsArray;
  }

  function updatePost(UpdatePost $dbParametersAndUpdatedPost) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndUpdatedPost);
    $success = $this->stmtHandler->updatePost($dbConn, $this->postData);

    return $success;
  }

  function deletePostWithId(DeletePostWithId  $dbParametersAndId) {
    $dbConn = $this->unpackDataAndOpenDBconnection($dbParametersAndId);
    $sql = $this->sqlGen->SQL_deletePostWithId(); 
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
      
      $result = mysqli_query($dbConn, $sql); // For security reasons, mysqli_query will not execute multiple queries to prevent SQL injections.
      return $result;
    }
    catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  private function getPostAsArray($rawData) {
    // used by READING functions, after they have fetched the "row" from the database.
    if(mysqli_num_rows($rawData) > 0) {
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

}

