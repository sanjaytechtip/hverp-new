<?php

namespace App\General;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
class DropdownModel extends Model
{
  
	protected $connection = 'mongodb';
    protected $collection = 'dropdown_items';
	protected $guarded 	  = [];
	
}
