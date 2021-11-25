<?php

namespace DBhandler;

use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';


final class DBhandlerTest extends TestCase {

  public function testTrimTrailingComa() {
    $str = 'inget comma,';
    DBhandler::takeAwayTrailingComa($str);
    $this->assertEquals(
      'inget comma',
      $str
    );
  }

  public function testGetPostWithId() {

    $dataObj = new GetPostWithId('flashcardapp', 'tbl_fcusers', array('user_id' => '1'));
    $dbh = new DBhandler();

    $this->assertIsArray(
      $dbh->getPostWithId($dataObj, 'getPostWithId should return an array')
    );
  }

  // public function testReturnDoubleOf24() {
  //   $this->assertEquals(
  //     '48',
  //     Doubler::do(24)
  //   );
  // }

  // public function testReturnDoubleOf3() {
  //   $this->assertEquals(
  //     '6',
  //     Doubler::do(3)
  //   );
  // }
}