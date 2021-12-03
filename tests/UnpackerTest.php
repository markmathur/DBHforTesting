<?php

namespace DBhandler;

use DBhandler\DBhandler;
use PHPUnit\Framework\TestCase;


// require './DBhandler1_1/DBhandler.php';
// require './DBhandler1_1/incomingDataClasses/Mother_targetPostWithId.php';
// require './DBhandler1_1/incomingDataClasses/UpdatePost.php';
// require './DBhandler1_1/incomingDataClasses/StorePost.php';
// require './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
// require './DBhandler1_1/incomingDataClasses/GetPostsByCritera.php';


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

  public function testExtractColumnsAndValues() {
    $mockDBH = $this->mockUnpack_StorePost_Request();

    $this->assertEquals(
      'text1, text2, user',
      $mockDBH->getStringOfColumns()
    );

    $this->assertEquals(
      "'dog', 'hund', '1'",
      $mockDBH->getStringOfValues()
    );
  }

  public function testExtractOnlyIdKeyAndValue() {
    $mockDBH = $this->mockUnpack_GetPostById_Request();

    $this->assertEquals(
      'card_id',
      $mockDBH->getIncomingIdColumn()
    );

    $this->assertEquals(
      '10',
      $mockDBH->getIncomingIdValue()
    );

  }


  // ** The updatePost execution **

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

  private function unpackAndUpdateMockedDBH ($mockIncData) {
    $mockDbh = new MockDBH();
    $unpacker = new Unpacker($mockDbh);
    $unpacker->unpackIncomingDataArray($mockIncData);
    
    return $mockDbh; // The results are stored in this object, thanks to dependency injection.
  }

  // ** StorePost execution **

  private function mockUnpack_StorePost_Request() {

    $mockIncData = new StorePost(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'text1' => 'dog',
        'text2' => 'hund',
        'user' => 1
      )
    );

    $mockDbh = $this->unpackAndUpdateMockedDBH($mockIncData);

    return $mockDbh; // The results are stored in this object, thanks to dependency injection.
  }

  // ** GetPostById execution **
  private function mockUnpack_GetPostById_Request() {
      
    $mockIncData = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '10'
      )
    );

    $mockDbh = $this->unpackAndUpdateMockedDBH($mockIncData);

    return $mockDbh;    
  }
  
}



class MockDBH extends DBhandler {}


