<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class Purchase extends Model
{
   protected $connection = 'mongodb';
   protected $collection = 'tbl_pp_purchase_invoice';
   
   protected $fillable = [
        
    ];
}
