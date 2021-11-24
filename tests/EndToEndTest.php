<?php

namespace DBhandler;

use PHPUnit\Framework\TestCase;

require_once './DBhandler1_1/incomingDataClasses/GetPostWithId.php';

// final class EndToEndTest extends TestCase {

//   public function testGetPostWithId() {

//     $dbh = new DBhandler();
//     $dataObj = new GetPostWithId(ENV::USERDB, ENV::USERTBL, array('user_id' => 2));
//     $user = $dbh->getPostWithId($dataObj);

//     $this->assertEquals(
//       'bellavit',
//       $user["username"]
//     );

//   }

// }