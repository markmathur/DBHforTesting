<?php

namespace DBHtests;

use PHPUnit\Framework\TestCase;
use DBhandler\DBhandler;

require_once './DBhandler1_1/DBhandler.php';


final class DBhandlerTest extends TestCase {

  public function testTrimTrailingComa() {
    $str = 'inget comma,';
    DBhandler::takeAwayTrailingComa($str);
    $this->assertEquals(
      'inget comma',
      $str
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