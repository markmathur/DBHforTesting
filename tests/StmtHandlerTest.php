<?php

namespace DBhandler;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use PHPUnit\Framework\TestCase;
use RangeException;

class StmtHandlerTest extends TestCase {

  protected function setUp(): void
  {
    $this->dbh = new MockDBH();
    $this->stmtHandler = new StmtHandler($this->dbh);

    $this->dbh->setTable('Tbl_example');
    $this->dbh->setStringOfColumns('col1, col2');
  }
  
  public function testGetStringOfQmarks() {

    $this->assertEquals(
      "?, ?, ?",
      $this->stmtHandler->getStringOfQmarks(3)
    );

    $this->assertEquals(
      '?',
      $this->stmtHandler->getStringOfQmarks(1)
    );

    $this->expectException(\Exception::class);
    $this->stmtHandler->getStringOfQmarks(0);
  }

  public function testMakePreparedStatementForStorePost() {
    $postData = array(
      "col1" => "one",
      "col2" => "two"
    );

    $this->assertEquals(
      "INSERT INTO Tbl_example (col1, col2) VALUES (?, ?);",
      $this->stmtHandler->makePreparedStatementForStorePost($postData)
    );
  }

  // public function testGetTypesOfValues() {
    
  //   $postData = array(
  //     "col1" => "one",
  //     "col2" => 256,
  //     "col3" => false
  //   );

  //   $this->assertEquals(
  //     "s",
  //     $this->stmtHandler->getTypesOfValues($postData)["col1"]->getType()
  //   );

  // }

  public function testGetStrOfTypeInitials() {
    $postData = array(
      "col1" => "one",
      "col2" => 256,
      "col3" => false
    );
    $this->assertEquals(
      'sib',
      $this->stmtHandler->getStrOfTypeInitials($postData)
    );
  }

  protected function tearDown(): void
  {
    $this->dbh = null;
  }
}

class StupidClassForTestPurposes {
  public $prop = "A prop.";
}