<?php

namespace App\BuyerManagement;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
class SupplierModel extends Model
{
  
	protected $connection = 'mongodb';
    protected $collection = 'supplier_form';
	/* protected $guarded 	  = []; */	
}