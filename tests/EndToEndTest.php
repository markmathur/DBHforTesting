<?php

namespace DBhandler;

use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostsByCritera.php';
require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
require_once './DBhandler1_1/incomingDataClasses/StorePost.php';

final class EndToEndTest extends TestCase {

  public function testGetCardWithId() {

    $dbh = new DBhandler();

    $dataObj = new GetPostWithId(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '1'
      )
    );

    $this->assertEquals(
      'Second',
      $dbh->getPostWithId($dataObj)['text1']
    );

  }

  public function testGetCardByCriteria() {

    $dbh = new DBhandler();

    $dataObj = new GetPostsByCriteria(
      'flashcardapp', 
      'tbl_flashcard',
      array(
        'card_id' => '1'
      )
    );

    $this->assertEquals(
      'Second',
      $dbh->getPostsByCriteria($dataObj)[0]['text1']
    );

  }

  

}