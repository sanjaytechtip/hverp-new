<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class StaffModel extends Eloquent
{
    protected $connection = 'mongodb';
	
	protected $table = 'staff'; 
 
    protected $fillable = [
        'article_no', 
		'description', 
		'hsn_code', 
		'gsm', 
		'width', 
		'unit', 
		'other', 
		'color'
    ];
}
