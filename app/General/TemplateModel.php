<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class TemplateModel extends Eloquent
{
    protected $connection = 'mongodb';
	
	protected $table = 'templates'; 
 
    protected $fillable = [
        'template_name', 
		'template_subject', 
		'template_body'
    ];
}
