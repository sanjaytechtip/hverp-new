<?php

namespace App\General;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
class CompanyTypeModel extends Model
{
  
	protected $connection = 'mongodb';
    protected $collection = 'companyType_items';
	protected $guarded 	  = [];
	
}
