<?php

namespace DBhandler;

use DBhandler\DBhandler;
use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/lib/Unpacker.php';
require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
require_once './DBhandler1_1/STR.php';
require_once './DBhandler1_1/DBhandler.php';

final class UnpackerTest extends TestCase {

  // *** Based on DBhandler/updatPost() ***

  public function testExtracDBname() {
    $mockDbh = $this->mockUnpackUpdatePostRequest();    

    $this->assertEquals(
      'flashcardapp',
      $mockDbh->getDatabase()
    );
  }

  public function testExtractTableName() {
    $mockDbh = $this->mockUnpackUpdatePostRequest();    

    $this->assertEquals(
      'tbl_flashcard',
      $mockDbh->getTable()
    );
  } 

  public function testExtractIncomingIdColumn() {
    $mockDbh = $this->mockUnpackUpdatePostRequest();    

    $this->assertEquals(
      'card_id',
      $mockDbh->getIncomingIdColumn()
    );
  }

  public function testExtractIncomingIdValue() {
    $mockDbh = $this->mockUnpackUpdatePostRequest();    

    $this->assertEquals(
      '1',
      $mockDbh->getIncomingIdValue()
    );
  }

  public function testExtractIncomingUpdateDataAsArray() {
    $mockDbh = $this->mockUnpackUpdatePostRequest();    

    $this->assertEquals(
      array(        
        'text1' => 'Tested by PHPunit',
        'text2' => 'Tesat av PHPunit'  ),
      $mockDbh->getIncomingUpdateDataAsArray()
    );
  }

  // public function testStringOfColumns() {
  //   $mockDbh = $this->mockUnpackUpdatePostRequest();    

  //   $this->assertEquals(
  //     '1',
  //     $mockDbh->getIncomingIdValue()
  //   );
  // }

  // ** Based on getPostById and getPostByCriteria
  // public function testGetPostById() {
  //   $mockDbh = $this->mockUnpackGetPostById();

  //   $this->assertEquals(
  //     array(        
  //       'bellav',
  //     $mockDbh->getIncomingUpdateDataAsArray()
  //   );
  // }


  // ** The execution **

  private function mockUnpackUpdatePostRequest() {
    $mockIncData = new UpdatePost(
      'flashcardapp', 
      'tbl_flashcard', 
      array(
        'card_id' => 1
      ), 
      array(
        'text1' => 'Tested by PHPunit',
        'text2' => 'Tesat av PHPunit'
      )
    ); 

    $mockDbh = $this->unpackAndUpdateMockedDBH($mockIncData);
    
    return $mockDbh;
  }

  private function mockUnpackGetPostById() {
    $mockIncData = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard', 
      array(
        'card_id' => 1
      )
    );

    $mockDbh = $this->unpackAndUpdateMockedDBH($mockIncData);

    return $mockDbh;
  }

  private function unpackAndUpdateMockedDBH ($mockIncData) {
    $mockDbh = new MockDBH();
    $unpacker = new Unpacker($mockDbh);
    $unpacker->unpackIncomingDataArray($mockIncData);
    
    return $mockDbh;
  }

  
}

class MockDBH extends DBhandler {}


