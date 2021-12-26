<?php

namespace DBhandler;

use mysqli;

class SetupRunBreakdownTest {

  private $server;
  private $username;
  private $password;
  private $dbName;

  private $id;
  private $field1;
  private $field2;

  private $dbh;

  function __construct()
  {
    require_once './ENV.php';
    require_once './DBhandler1_1/STR.php';
    require_once './DBhandler1_1/DBhandler.php';

    require_once './DBhandler1_1/incomingDataClasses/GetAllPosts.php';
    require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
    require_once './DBhandler1_1/incomingDataClasses/StorePost.php';
    require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
    require_once './DBhandler1_1/incomingDataClasses/GetPostsByCritera.php';
    require_once './DBhandler1_1/incomingDataClasses/DeletePostWithId.php';

    $this->server = \ENV::dbServer;
    $this->username = 'tester';
    $this->password = 'o9)v423SD!!y/';
    $this->dbName = STR::CARDDB;

    $this->tableName = 'tableForTesting';
  }

  public function run() {

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    try {

      // *** SETUP ***
      $this->dbh = new \DBhandler\DBhandler();

      $dbConn = $this->makeDBconnection();
      
      $this->dropTableIfThereRemainsOneFromAfaultyTest($dbConn);
      $this->createTable($dbConn);


      // *** TESTING ***
      $this->storePost();
      $this->readPost();
      $this->updatePost();
      $this->readPost();
      $this->manyPosts();
      $this->deletePost();
      $this->makeSurePostIsDeleted();
      
      // ** BREAKDOWN
      $this->dropTable($dbConn);
      $dbConn->close();

    }
    
    catch (\Exception $e) {
      echo "<div class=\"exceptionMessage\"> " . $e->getMessage() . " </div> ";
      // die();
    }

  }

  private function dropTableIfThereRemainsOneFromAfaultyTest($dbConn) {
    $dataObj = new GetAllPosts($this->dbName, $this->tableName);
    $postArr = $this->dbh->getAllPosts($dataObj);
    
    if($postArr !== false) {
      $this->dropTable($dbConn);
    }

  }

  private function storePost() {
    $dataObj = new StorePost($this->dbName, $this->tableName, array(
      $this->field1 => 'Sugar', 
      $this->field2 => 'Socker'
    ));

    if($this->dbh->storePost($dataObj) == true)
      echo '<div>Post is stored.</div>';
    else
      throw new \Exception('Failed to store post.');
  }

  private function readPost() {
    echo "<div> Reading post with id = 1.</div>";
    $dataObj = new GetPostWithId($this->dbName, $this->tableName, array('id' => '1'));
    $post = $this->dbh->getPostWithId($dataObj);

    if($post == true)
      $this->displayPostInTable($post);
    else
      throw new \Exception('Failed to read post with id = 1');
  }

  private function makeSurePostIsDeleted() {

    try {
      $this->readPost();
      echo "<div class=\"exceptionMessage\">Failed to delete post with id = 1 </div> ";
    }
    catch (\Exception $e) {
      echo 'Read failed. Post is successfully deleted.';
    }

  }

  private function updatePost() {
    $dataObj = new UpdatePost($this->dbName, $this->tableName, array('id' => '1'), array($this->field2 => 'Brunsocker'));

    if($this->dbh->updatePost($dataObj) == true)
      echo '<div>Post is updated.</div>';
    else
      throw new \Exception('Failed to update post with id = 1.');
    
  }

  private function manyPosts() {
    $dataObj = new GetPostsByCriteria($this->dbName, $this->tableName, array($this->field1 => 'Sugar'));
    $postArr = $this->dbh->getPostsByCriteria($dataObj);
    if($postArr == true) {
      echo "<div> Reading posts by criteria fieldOne = Sugar.</div>";
      $arrayLength = sizeof($postArr);
      echo("<div> { $arrayLength } post matches the criteria. </div>");
    }
    else {
      throw new \Exception('Failed to read posts with field1 = sugar');
    }
    
  }

  private function deletePost() {
    $dataObj = new DeletePostWithId($this->dbName, $this->tableName, array('id' => '1'));
    
    if($this->dbh->deletePostWithId($dataObj) == true)
      echo '<div>Post is deleted.</div>';
    else
      throw new \Exception('Failed to delete post.');
  }

  private function createTable($dbConn) {

    $this->id = 'id';
    $this->field1 = 'fieldOne';
    $this->field2 = 'fieldTwo';
    
    $sql = "CREATE TABLE {$this->tableName} (
      {$this->id} INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      {$this->field1} VARCHAR(30) NOT NULL,
      {$this->field2} VARCHAR(30) NOT NULL
      ); ";

    if($dbConn->query($sql) === FALSE){
      echo $dbConn->errno;
      throw new \Exception("Failed to to create table. <br>" . $dbConn->error);
    }
    else{
      echo "<div class=\"tableAction\"> Table created </div>";
    } 
    
    return;
  }

  private function dropTable($dbConn) {
    $sql = "DROP TABLE {$this->tableName};";

    if($dbConn->query($sql) === FALSE)
      throw new \Exception('Failed to connect to drop table.');
    else 
      echo "<div class=\"tableAction\"> Table dropped </div>";
  }


  private function makeDBconnection() {
    $dbConn = new mysqli($this->server, $this->username, $this->password, $this->dbName);
    if(!$dbConn) {
      throw new \Exception('Can not establish connection to database.', 500);
    }
    return $dbConn;
  }


  // SUPPORTING METHODS

  private function displayPostInTable($post) {
    if(sizeof($post) === 0)
      $post = array('id' => '- ', 'fieldOne' => '- ', 'fieldTwo' => '- ');

    $row = "<table class=\"th\"> <tr  class=\"td\"> ";

    foreach($post as $col => $val) {
      $row .= "<td> $col </td>";
    }

    $row .= "</tr> <tr>";

    foreach($post as $col => $val) {
      $row .= "<td> $val </td>";
    }

    $row .= "</tr> </table>";

    echo $row;
  }

}