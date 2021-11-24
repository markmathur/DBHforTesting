<?php

namespace DBhandler;

use DBhandler\DBhandler;
use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/lib/Unpacker.php';
require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
require_once './DBhandler1_1/STR.php';
require_once './DBhandler1_1/DBhandler.php';

final class UnpackerTest extends TestCase {

  public function testExtracDBname() {
    $mockDbh = $this->mockDBHandRunUnpacker();    

    $this->assertEquals(
      'flashcardapp',
      $mockDbh->getDatabase()
    );
  }

  public function testExtractTableName() {
    $mockDbh = $this->mockDBHandRunUnpacker();    

    $this->assertEquals(
      'tbl_flashcard',
      $mockDbh->getTable()
    );
  } 

  private function mockDBHandRunUnpacker() {
    $mockDbh = new MockDBH();
    $mockIncData = $this->makeUpdatePostDataObject();

    $unpacker = new Unpacker($mockDbh);
    $unpacker->unpackIncomingDataArray($mockIncData);

    return $mockDbh;
  }

  private function makeUpdatePostDataObject() {
    return new UpdatePost(
      'flashcardapp', 
      'tbl_flashcard', 
      array(
        'card_id' => 1
      ), 
      array(
        'text1' => 'Tested by PHPunit',
        'text2' => 'Tesat av PHPunit'      
      )); 
  }

}

class MockDBH extends DBhandler {}
