<?php
  
namespace App\General;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
use MyApp\Agent;


  
class AgentImport implements ToModel,WithHeadingRow
{
    /**
    * @return \Illuminate\Support\Collection
    */
	  protected $connection = 'mongodb';
	
	  protected $table = 'agents'; 
	
   public function model(array $row)

    {
		//echo '<pre>';
		//print_r($row); die;
		//if($row['features']==1){$features = 1;}else{$features=0;}
	  return new Product([
            'name' => $row['name'], 
            'email'    => $row['email'], 
			'phone' => $row['phone'], 	 
		 ]);

    }
}