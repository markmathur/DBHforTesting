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
    $this->dbh->setIncomingIdColumn('id');
    $this->dbh->setIncomingIdValue('10');
    $this->postData = array(
      "col1" => "one",
      "col2" => "two"
    );
  }

  public function testMakePreparedStatementForStorePost() {

    $this->assertEquals(
      "INSERT INTO Tbl_example (col1, col2) VALUES (?, ?);",
      $this->stmtHandler->makePreparedStatementForStorePost($this->postData)
    );
  }

  public function testMakePreparedStatementForUpdatePost() {
    $this->assertEquals(
      "UPDATE Tbl_example SET col1 = ?, col2 = ? WHERE id = ?;",
      $this->stmtHandler->makePreparedStatementForUpdatePost($this->postData)
    );
  }

  public function testMakeArrayOfParameters() {
    $this->assertEquals(
      array('col1' => 'one', 'col2' => 'two', 'id' => '10'),
      $this->stmtHandler->makeArrayOfParametersForUpdatePost($this->postData)
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