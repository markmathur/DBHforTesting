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

    if($this->itIsAPost($incData)) {

      $xtractor->extractColumns($incData);
      $xtractor->extractValues($incData);

    }
    else if($this->itIsAnUpdate($incData)) {
      $idArray = $incData->{$this->dbh::ARRAYWITHID};
      foreach($idArray as $key => $val) {
        $this->dbh->setIncomingIdColumn($key);
        $this->dbh->setIncomingIdValue($val);
      }
      $this->dbh->setIncomingUpdateDataAsArray($incData->{$this->dbh::POSTDATA});
    }
    else if($this->itIsAnId($incData)) {

      $idArray = $incData->{$this->dbh::ARRAYWITHID};
      if($this->arrayHasMaxOneItem($idArray) == false)
        throw new \Exception("DBhandler received to long array. Only id is needed.");
      else
        foreach($idArray as $key => $val) {
          $this->dbh->setIncomingIdColumn($key);
          $this->dbh->setIncomingIdValue($val);
        }
    }
      
  }

  private function itIsAPost($incData) {
    $namespc = STR::INCNAMESPACE;
    $qury = (get_class($incData) == "{$namespc}StorePost");
    return $qury;
  }

  private function itIsAnUpdate($incData) {
    $namespc = STR::INCNAMESPACE;
    $qury = (get_class($incData) == "{$namespc}UpdatePost");
    return $qury;
  }

  private function itIsAnId($incData) {
    return isset($incData->{$this->dbh::ARRAYWITHID});
    // $namespc = \STR::INCNAMESPACE;
    // $qury = (get_class($this->incData) == "{$namespc}GetPostWithId");
    // return $qury;
  }


  private function arrayHasMaxOneItem(array $arr) {
    return sizeof($arr) == 1;
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");
  }

}




