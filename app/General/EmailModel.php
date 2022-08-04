<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class EmailModel extends Eloquent
{
    protected $connection = 'mongodb';
	protected $table = 'emails'; 
	protected $fillable = [
        'name', 'key_name', 'from', 'subject', 'email_text'
    ];
}
