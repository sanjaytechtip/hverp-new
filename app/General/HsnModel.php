<?php
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
class HsnModel extends Eloquent
{
    protected $connection = 'mongodb';	
	protected $table = 'hsncode'; 
	
	public function getData(){
		$hsncode = DB::table('hsncode')->get();
		return $hsncode;
	}
	
	public function addHsnData($data){
		$insert = DB::table('hsncode')->insert(array($data));
		return $insert;
	}
	
	public function editHsn($id){
		$editHsn = DB::table('hsncode')->where('_id',$id)->first();
		return $editHsn;
	}
}
