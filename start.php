<?php

use DBhandler\DBhandler;
use DBhandler\GetAllPosts;
use DBhandler\STR;

require_once './DBhandler1_1/DBhandler.php';
require_once './DBhandler1_1/incomingDataClasses/GetAllPosts.php';
require_once './ENV.php';

getUsers();


function getUsers() {
  echo 'Inside Get Users';

  $dbh = new DBhandler();


  $dataObj = new GetAllPosts(STR::USERDB, STR::USERTBL);
  $allPosts = $dbh->getAllPosts($dataObj);
  var_dump($allPosts);
}