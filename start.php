<?php

namespace mockClient;

use DBhandler\DBhandler;
use DBhandler\GetAllPosts;
use DBhandler\STR;
use DBhandler\UpdatePost;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/lib/Unpacker.php';
require_once './DBhandler1_1/incomingDataClasses/GetAllPosts.php';
require_once './DBhandler1_1/incomingDataClasses/UpdatePost.php';
require_once './DBhandler1_1/incomingDataClasses/StorePost.php';
require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';
require_once './ENV.php';



getUsers();


function getUsers() {

  $dbh = new DBhandler();

  $dataObj = new \DBhandler\StorePost('flashcardapp', 'tbl_flashcard', array('text1' => 'blind', 'text2' => 'blind', 'user' => 2));
  $success = $dbh->storePost($dataObj);
  var_dump($success);  

  // $dataObj = new \DBhandler\UpdatePost('flashcardapp', 'tbl_flashcard', array('card_id' => '23'), array('text2' => 'oanvänd'));
  // $success = $dbh->updatePost($dataObj);
  // var_dump($dataObj, $success);



  // $unpacker = new \DBhandler\Unpacker($dbh);
  // $unpacker->unpackIncomingDataArray($dataObj);
  // var_dump($dbh);

  // $dataObj = new GetAllPosts(STR::USERDB, STR::USERTBL);
  // $allPosts = $dbh->getAllPosts($dataObj);
  // var_dump($allPosts);

  // $dataObj = new \DBhandler\GetPostWithId('flashcardapp', 'tbl_flashcard', array('card_id' => '10; DROP TABLE tbl_test --'));
  // $post = $dbh->getPostWithId($dataObj);
  // var_dump($post);

  
}