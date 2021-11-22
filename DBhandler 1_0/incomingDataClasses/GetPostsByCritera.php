<?php

namespace DBhandler;

use DBhandler\DBhandler;

class GetPostsByCriteria {
  public function __construct(string $db, string $tbl, array $arrCrit)
  {
    $this->{DBhandler::DATABASE} = $db;
    $this->{DBhandler::TABLE} = $tbl;

    $this->{DBhandler::ARRAYWITHID} = $arrCrit;
    // We use ARRAYWITHID as long as it is just one criteria coming in.
  }
}