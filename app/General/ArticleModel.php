<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class ArticleModel extends Eloquent
{
    protected $connection = 'mongodb';
	
	protected $table = 'articles'; 
 
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
