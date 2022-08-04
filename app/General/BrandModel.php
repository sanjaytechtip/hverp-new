<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class BrandModel extends Eloquent
{
    protected $connection = 'mongodb';
	
	protected $table = 'brands'; 
 
    protected $fillable = [
        'merchant_name', 
		'agent_sales_person_code', 
		'payment_term'
    ];
}
