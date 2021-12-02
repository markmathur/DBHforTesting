<?php

namespace DBhandler;

use PHPUnit\Framework\TestCase;
// require './DBhandler1_1/lib/SQLgenerator.php';
// require './tests/MockDBhandler.php';

class SQLgeneratorTest extends TestCase {


  public function testSQL_getPostWithId() {
    $this->dbh = new MockDBH();

    $this->dbh->setTable('tbl_flashcard');
    $this->dbh->setIncomingIdColumn ('card_id');
    $this->dbh->setIncomingIdValue('10');

    $gen = new SQLgenerator($this->dbh);

    $this->assertEquals(
      "SELECT * FROM tbl_flashcard WHERE card_id = '10';",
      $gen->SQL_getPostWithId()
    );

    // $this->dbh->setIncomingIdValue('10; select * FROM tbl_database --');

    // $this->assertEquals(
    //   "SELECT * FROM tbl_flashcard WHERE card_id = '10';",
    //   $gen->SQL_getPostWithId()
    // );

  }

  public function testSQL_avoidInjectionAttacks() {
    $this->dbh = new MockDBH();

    $dataObj = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '10; select * from tbl_flashcard --'
      )
    );

    $postAsArray = $this->dbh->getPostWithId($dataObj);

    $this->assertIsArray($postAsArray);
    $this->assertEquals(
      'blow',
      $postAsArray['text1']
    );

    $gen = new SQLgenerator($this->dbh);
    
    $this->assertNotEquals(
      "SELECT * FROM tbl_flashcard WHERE card_id = '10; select * from tbl_flashcard --';",
      $gen->SQL_getPostWithId()
    );
  }

}