<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Purchase;
use App\General\ArticleModel;
use App\General\BrandModel;
use App\General\SupplierModel;
use App\RegisterForm;
use App\User;
use DB;
use Session;
use PDF;

class TestController extends Controller
{
	function __construct(){
		
	}
	
    public function test_join() {
        $brands = DB::table('books')->get();
        //echo '<pre>';
        //print_r($brands);
            //$db = static::db()->books;
            $search = '';
            $start = 0;
            $limit = 10;
           $result = DB::raw(function($collection) use($search, $start, $limit) {
     return $collection->aggregate(array(
       array( '$lookup' => array(
         'from' => 'books_selling_data',
         'localField' => 'mid',
         'foreignField' => 'mid',
         'as' => 'user'
       )),
       array( '$unwind' => array( 
         'path' => '$user', 'preserveNullAndEmptyArrays' => True
       )),
       array( '$match' => array(
         '$or' => array(
           array( 'invoice_number' => array( '$regex' => $search ) ),
           array( 'payment_type' => array( '$regex' => $search ) ),
           array( 'txid' => array( '$regex' => $search ) ),
           array( 'user.usrEmail' => array( '$regex' => $search ) )
         )
       )),
       array( '$skip' => $start ),
       array( '$limit' => $limit )
     ));
  });
  
  //pr($result);
  
  
  
  $user_list = User::with('clientMaster')
                    ->get()
                    ->toArray();
                    //pr($user_list);
                    $var="books_selling_data";
             $res = DB::collection("books")->raw(function($collection) use ($var) {
        return $collection->aggregate([
                ['$lookup' => [
                    'from' => $var,
                    'localField' => 'm_id',
                    'foreignField' => 'mid',
                    'as' => 'specifications'
                ]]
            ]);
        });       
                    
    echo "<pre>";
print_r(json_decode(json_encode($res->toArray(),true))); die;
    }
	public function create() {
		
    }

}