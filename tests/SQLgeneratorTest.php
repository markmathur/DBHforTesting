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

  }

}