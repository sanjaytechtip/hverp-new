<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;use Auth;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use App\General\ArticleModel;
use App\RegisterForm;
use DB;
use Session;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class BalanceStockController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		//checkPermission('view_pi');
		if(!$_POST){
		$openingstocklist = DB::table('tbl_balance_stock')->where('type',1)->orderBy('_id','DESC')->paginate(30);
		}else{
		    $query = DB::table('tbl_balance_stock')->where('type',1);
            if($request->room_no!=''){
            $da = DB::table('room')->select('_id')->where('room_no', 'LIKE','%'.$request->room_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
            array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('room_no',$arr);
            }
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('_id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
            array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            $openingstocklist =$query->orderBy('_id', 'DESC')->paginate(30);
		}
		 //pr($openingstocklist);die; 
		return view('general.openingstock.openingstocklist',['openingstocklist'=>$openingstocklist,'request'=>$request]);
	 }

	 public function delete_opening_stock(Request $request, $id)
	 {
	   if($id!='')
	   {
	   DB::table('tbl_balance_stock')->where('_id',$id)->delete();
	   }
	   Session::flash('success', 'Opening Stock deleted successfully!');
	   return redirect()->route('opening_stock_list')->with('danger', 'Opening Stock deleted successfully.');
	 }
	 
	 public function delete_balance_stock(Request $request, $id)
	 {
	   if($id!='')
	   {
	   DB::table('tbl_balance_stock')->where('item_id',$id)->delete();
	   }
	   Session::flash('success', 'Balance Stock deleted successfully!');
	   return redirect()->route('balance_stock_list')->with('danger', 'Balance Stock deleted successfully.');
	 }
	 
	 public function balance_stock_list(Request $request)
	 {
	  if(!$_POST){
                $balancestocklist = DB::table('tbl_balance_stock');
                $balancestocklist = $balancestocklist->select('item_id','room_no','rack_no','batch_no','mfg_date','expiry_date','quantity','mrp','loc_cen','rate','amount');
                //$balancestocklist = $balancestocklist->sum('tbl_balance_stock.quantity');
                //////////	$balancestocklist = $balancestocklist->groupBy('item_id');
                //$balancestocklist = $balancestocklist->select('sum(quantity) as quantity');
                $balancestocklist = $balancestocklist->orderBy('id', 'DESC');
                $balancestocklist = $balancestocklist->paginate(30);
		}else{
		    //pr($request->all()); die;
		    $query = DB::table('tbl_balance_stock')->select('item_id','room_no','rack_no','batch_no','mfg_date','expiry_date','quantity','mrp','loc_cen','rate','amount');
            
            if($request->brand_name!=''){
            $da = DB::table('items')->select('id')->where('brand', 'LIKE','%'.$request->brand_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
				array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->product_code!=''){
            $da = DB::table('items')->select('id')->where('vendor_sku', 'LIKE','%'.$request->product_code.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            //$balancestocklist =$query->orderBy('id', 'DESC')->groupBy('item_id')->paginate(30);
            $balancestocklist =$query->orderBy('id', 'DESC')->paginate(30);
          
		}
		//pr($balancestocklist); die; tbl_balance_stock
		return view('general.balancestock.balancestocklist',['balancestocklist'=>$balancestocklist,'request'=>$request]);   
	 }
	
	
	
	public function ajax_rack_details(Request $request)
	{
	   if($request->id!='')
	   {
	    $query = DB::table('tbl_balance_stock')->select('item_id','room_no','rack_no','batch_no','mfg_date','expiry_date','quantity','mrp','loc_cen','rate','amount');
            
            if($request->brand!=''){
            $da = DB::table('items')->select('id')->where('brand', 'LIKE','%'.$request->brand.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->rack_no!=''){
            $da = DB::table('rack')->select('id')->where('rack_name', 'LIKE','%'.$request->rack_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('rack_no',$arr);
            }
            
            if($request->type!=''){
            $query->where('loc_cen', 'LIKE','%'.$request->type.'%');
            }
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            if($request->mfg_date!=''){
            $query->where('mfg_date', date('Y-m-d',strtotime($request->mfg_date)));
            }
            
	    $rackstocklist = $query->where('item_id',$request->id)->orderBy('id','DESC')->paginate(100);  
	    $rackstocklist_count = $query->where('item_id',$request->id)->orderBy('id','DESC')->count();  
	    $html = "";
	    if(!empty($rackstocklist))
	    {
	        $brand_name = getAllbrandDataList();
	        $item_data = getAllitemsDataList();
	        $rack_data = getAllrackDataList();
	        foreach($rackstocklist as $stocklist)
	        {
				$stocklist = (array)$stocklist;
	            $html .='<tr><td>'.$brand_name[$request->id].'</td><td>'.$item_data[$request->id].'</td><td>'.$rack_data[$stocklist['rack_no']].'</td><td>'.$stocklist['loc_cen'].'</td><td>'.$stocklist['batch_no'].'</td><td>'.date('d-M-Y',strtotime($stocklist['mfg_date'])).'</td><td>'.date('d-M-Y',strtotime($stocklist['expiry_date'])).'</td><td>'.$stocklist['rate'].'</td><td>'.$stocklist['quantity'].'</td></tr>';
	        }
	    }
	    $arr = array('html'=>$html,'record_count'=>$rackstocklist_count);
	    return json_encode($arr);
	   }
	}
	
}