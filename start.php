<?php

use DBhandler\DBhandler;
use DBhandler\GetAllPosts;
use DBhandler\StorePost;
use DBhandler\STR;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/incomingDataClasses/GetAllPosts.php';
require_once './DBhandler1_1/incomingDataClasses/StorePost.php';
require_once './ENV.php';

getUsers();


function getUsers() {

  $dbh = new DBhandler();
  // $dataObj = new GetAllPosts(
  //   STR::USERDB, 
  //   STR::USERTBL
  // );
  // $allPosts = $dbh->getAllPosts($dataObj);
  // var_dump($allPosts);

  $dataObj = new StorePost(
    STR::CARDDB, 
    STR::CARDTBL,
    array(
      "text1" => "Saturday",
      "text2" => "Lördag",
      "user" => "1"
    )
  );

  $success = $dbh->storePost($dataObj);
  var_dump($success);

}