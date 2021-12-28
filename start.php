<?php

namespace mockClient;

use DBhandler\DBhandler;
use DBhandler\GetAllPosts;
use DBhandler\STR;
use DBhandler\UpdatePost;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once './DBhandler1_1/lib/miscRequireFunctions.php';

getUsers();


function getUsers() {

  $dbh = new DBhandler();

  // $dataObj = new \DBhandler\StorePost('flashcardapp', 'tbl_flashcard', array('text1' => 'blind', 'text2' => 'blind', 'user' => 2));
  // $success = $dbh->storePost($dataObj);
  // var_dump($success);  

  // $dataObj = new \DBhandler\UpdatePost('flashcardapp', 'tbl_flashcard', array('card_id' => '23'), array('text2' => 'oanvänd'));
  // $success = $dbh->updatePost($dataObj);
  // var_dump($dataObj, $success);



  // $unpacker = new \DBhandler\Unpacker($dbh);
  // $unpacker->unpackIncomingDataArray($dataObj);
  // var_dump($dbh);

  $dataObj = new GetAllPosts(STR::USERDB, STR::USERTBL);
  $allPosts = $dbh->getAllPosts($dataObj);
  var_dump($allPosts);


  // $id = '30';
  // $dataObj = new \DBhandler\DeletePostWithId('flashcardapp', 'tbl_flashcard', array('card_i' => $id));
  // $success = $dbh->deletePostWithId($dataObj);

  // if($success === false)
  //   echo 'Database order failed.';
  // else
  //   echo "Number of posts deleted: {$success}";



  // $dataObj = new \DBhandler\GetPostsByCriteria('flashcardapp', 'tbl_flashcard', array('user' => '1'));
  // $post = $dbh->getPostsByCriteria($dataObj);
  // var_dump($post);
  
}