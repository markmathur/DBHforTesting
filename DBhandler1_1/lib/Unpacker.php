<?php

namespace DBhandler;

class Unpacker {
  
  private DBhandler $dbh;
  private string $stringOfColumns = '';
  private string $stringOfValues = '';

  function __construct(DBhandler $dBhandler)
  {
    $this->dbh = $dBhandler;
  }

  function unpackIncomingDataArray($incData) {
     var_dump($incData);

    $this->extractDBparameters($incData);

    if($this->itIsAPost($incData)) {

      $this->extractColumns($incData);
      $this->extractValues($incData);

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

  private function extractDBparameters($incData) {
    $this->dbh->setDatabase($incData->{$this->dbh::DATABASE});
    $this->dbh->setTable($incData->{$this->dbh::TABLE});
  }

  private function extractColumns($incData) {
    foreach($incData->{$this->dbh::POSTDATA} as $col => $val) {
      $this->stringOfColumns .= "{$col}, ";
    }

    $this->takeAwayTrailingComa($this->stringOfColumns);
    
    $this->dbh->setStringOfColumns($this->stringOfColumns);
  }

  private function extractValues($incData) {
    foreach($incData->{$this->dbh::POSTDATA} as $col => $val) {
      $this->stringOfValues .= "'{$val}', ";
    }

    $this->takeAwayTrailingComa($this->stringOfValues);

    $this->dbh->setStringOfValues($this->stringOfValues);
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