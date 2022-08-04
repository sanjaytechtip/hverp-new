<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class AgentModel extends Eloquent
{
    protected $connection = 'mongodb';
	protected $table = 'agents'; 
	protected $fillable = [
        'name', 'email', 'phone'
    ];
}
