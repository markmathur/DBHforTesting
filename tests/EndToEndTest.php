<?php

namespace DBhandler;

use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostsByCritera.php';
require_once './DBhandler1_1/incomingDataClasses/GetAllPosts.php';
require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
require_once './DBhandler1_1/incomingDataClasses/StorePost.php';

final class EndToEndTest extends TestCase {

  protected function setUp(): void 
  {
    $this->dbh = new DBhandler();
  }

  public function testGetPostWithId_returnsPost1() {

    $dataObj = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '1'
      )
    );

    $this->assertEquals(
      'Second',
      $this->dbh->getPostWithId($dataObj)['text1']
    );

  }

  public function testGetPostWithId_failsToReturnNonExistantPost2() {

    $dataObj = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '2'
      )
    );

    // $this->assertIsArray($dbh->getPostWithId($dataObj));

    $this->assertSameSize(
      array(),
      $this->dbh->getPostWithId($dataObj)
    );
  } 

  public function testGetPostByCriteria_getsPost1() {

    $dataObj = new GetPostsByCriteria(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '1'
      )
    );

    $this->assertEquals(
      'Second',
      $this->dbh->getPostsByCriteria($dataObj)[0]['text1']
    );

  }

  public function testGetAllPosts_givesArray() {
    $this->assertIsArray(
      $this->dbh->getAllPosts($this->getAllPostsDataObj())
    );
  }

  public function testArrayFrom_GetAllPosts_isNotEmpty() {
    $this->assertNotSameSize(
      array(),
      $this->dbh->getAllPosts($this->getAllPostsDataObj())
    );
  }

  private function getAllPostsDataObj() {
    return new GetAllPosts(
      'flashcardapp', 
      'tbl_flashcard'
    );
  }

  

}