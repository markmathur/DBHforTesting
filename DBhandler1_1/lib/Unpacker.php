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
    
    $xtractor = new Extractor($this->dbh, $incData);

    $xtractor->extractDBparameters();

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

class Extractor {
  private DBhandler $dbh;
  private $incData;
  private string $stringOfColumns;
  private string $stringOfValues;

  function __construct($dBhandler, $incomingDataFromRequest) {
    
    $this->dbh = $dBhandler;
    $this->incData = $incomingDataFromRequest;

  }

  // *** PUBLIC METHODS ***

  public function extractDBparameters() {

    $this->setDBnameInDBHandler();
    $this->setTableNameInDBHandler();  
  
  }

  public function extractColumns($incData) {
    foreach($this->incData->{$this->dbh::POSTDATA} as $col => $val) {
      $this->stringOfColumns .= "{$col}, ";
    }

    $this->takeAwayTrailingComa($this->stringOfColumns);
    
    $this->dbh->setStringOfColumns($this->stringOfColumns);
  }


  // *** PRIVATE METHODS ***

  private function setDBnameInDBHandler() {
    $this->dbh->setDatabase($this->incData->{$this->dbh::DATABASE});
  }

  private function setTableNameInDBHandler() {
    $this->dbh->setTable($this->incData->{$this->dbh::TABLE});
  }


  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");
  }
}