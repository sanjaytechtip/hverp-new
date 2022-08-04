<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use App\General\ArticleModel;
use App\RegisterForm;
use DB;
use Session;
use PDF;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class AjaxPaginationController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxPagination(Request $request) {
		
		$products = DB::table('items')->orderBy('_id','DESC')->paginate(10);
		
		 //pr($products);die; 
		 if ($request->ajax()) {
        return view('general.purchaseorder.presult', compact('products'));
         }
  
        return view('general.purchaseorder.ajaxPagination',compact('products'));
	 }
	 
	 public function ajaxPagination2(Request $request) {
		
		$products = DB::table('items')->orderBy('_id','DESC')->paginate(10);
		
		 //pr($products);die; 
		 $html = '';
		 if ($request->ajax()) {
        //return view('general.purchaseorder.presult', compact('products'));
        foreach($products as $pro)
        {
          $html .="<tr><td>".$pro['vendor_sku']."</td><td>".$pro['name']."</td></tr>";
        }
        echo $html;
         }
  
        //return view('general.purchaseorder.ajaxPagination',compact('products'));
	 }

	 
   
	
	
	
	
}