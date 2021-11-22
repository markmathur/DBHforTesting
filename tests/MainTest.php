<?php

require_once './vendor/autoload.php';
use PHPUnit\Framework\TestCase;

require_once './DBhandler1_0/Main.php';

final class MainTest extends TestCase {

  public function testTrimTrailingComa() {
    $str = 'inget comma,';
    \DBhandler\DBhandler::takeAwayTrailingComa($str);
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