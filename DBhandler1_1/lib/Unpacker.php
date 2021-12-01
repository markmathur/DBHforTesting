<?php

namespace DBhandler;

require_once './DBhandler1_1/lib/Extractor.php';

class Unpacker {
  
  private DBhandler $dbh;
  private string $stringOfColumns = '';
  private string $stringOfValues = '';

  function __construct(DBhandler $dBhandler)
  {
    $this->dbh = $dBhandler;
  }

  function unpackIncomingDataArray($incData) {
    
    $xtractor = new Extractor($this->dbh, $incData);

    $xtractor->extractDBparameters();

    if($this->incDataIsOf_StorePostClass($incData)) {
      // Creating a new post
      $xtractor->extractColumns($incData);
      $xtractor->extractValues($incData);

    }
    else if($this->incDataIsOf_UpdatePostClass($incData)) {
      // Updating a current post
      $xtractor->setIdColumnNameAndValue($incData);
      $this->dbh->setIncomingUpdateDataAsArray($incData->{$this->dbh::POSTDATA});
    }
    else if($this->itIsAnId($incData)) {
      // Reading or deleting a post
      $xtractor->setIdColumnNameAndValue($incData);
    }
      
  }



  private function incDataIsOf_StorePostClass($incData) {
    return get_class($incData) == STR::INCNAMESPACE."StorePost";
  }

  private function incDataIsOf_UpdatePostClass($incData) {
    return get_class($incData) == STR::INCNAMESPACE . "UpdatePost";
  }

  private function itIsAnId($incData) {
    return isset($incData->{$this->dbh::ARRAYWITHID});
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");
  }

}




