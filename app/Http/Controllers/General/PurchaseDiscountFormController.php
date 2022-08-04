<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use DB;
use Session;
use PDF;
use Route;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class PurchaseDiscountFormController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

	
    
	 
    public function create() {
        userPermission(Route::current()->getName());
		return view('general.purchasediscountform.purchasediscountformcreate');
    }
    
    public function index(Request $req) {
        //userPermission(Route::current()->getName());
        if(!$_POST){
		$data = DB::table('tbl_purchase_discount')->orderBy('id', 'DESC')->paginate(50);
		$customer_name =''; 
		$brand_name = '';
        }else{
        $query = DB::table('tbl_purchase_discount');
        if($req->vendor_name!=''){
        $query->where('vendor_id',$req->vendor_name);  
        $vendor_name =$req->vendor_name; 
        }
        if($req->brand_name!=''){
        $object = DB::table('brand')->select('id')->where('brand', 'LIKE','%'.$req->brand_name.'%')->get()->toArray();
        $da = json_decode(json_encode($object), true);
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,$da_d['id']);
        }
        $query->whereIn('brand_id',$arr);
        $brand_name = $req->brand_name;
        }
        $data =$query->orderBy('id', 'DESC')->paginate(50);
        }
		//pr($data); die;
		return view('general.purchasediscountform.purchasediscountlist',['datas'=>$data,'vendor_name'=>$vendor_name,'brand_name'=>$brand_name]);
    }
    
    public function purchase_delete_discount(Request $req, $id)
    {
    //userPermission(Route::current()->getName());
     DB::table('tbl_purchase_discount')->where('id',$id)->delete();
     return redirect('admin/purchase-discount-net-rate-list')->with('success', 'Discount deleted Successfully.');   
    }
    
   
    
    public function purchase_add_discount_form(Request $request)
    {
        
                    $insertData = array(
                    "vendor_id"         =>      $request->vendor_name,
                    "brand_id"          =>      $request->brand,
                    "valid_till"        =>      date('Y-m-d',strtotime($request->get('valid_till'))),
                    "discount"          =>      $request->discount,
                    "discount_by"       =>      $request->discount_by,
                    );
                    //pr($insertData); die;
                    $insert = DB::table('tbl_purchase_discount')->insert(array($insertData));
                    return redirect('admin/purchase-discount-net-rate-list')->with('success', 'Discount added Successfully.');
    }
    
    
    
    public function purchase_discount_data_update(Request $request) {
            //($request->all()); die;
            $updated_on = date('Y-m-d H:i:s');
			$updateData = array(
				"valid_till"            =>      date('Y-m-d',strtotime($request->get('date_rate'))),
				"discount"              =>      $request->net_rate,
                "discount_by"           =>      $request->discount_by
			);
			DB::table('tbl_purchase_discount')->where('id',$request->id)->update($updateData);
			$arr = array('till_date' => globalDateformatNet(date('Y-m-d',strtotime($request->get('date_rate')))),'net_rate' => $request->net_rate,'updated_on' => globalDateformatNet($updated_on));
			return json_encode($arr);
    }
    
    public function purchase_net_rate_list(Request $req)
    {
        if(!$_POST){
		$data = DB::table('tbl_purchase_netrate')->orderBy('id', 'DESC')->paginate(50);
		$vendor_name ='';
		$product_name = '';
        }else{
            //pr($req->all()); die;
            $query = DB::table('tbl_purchase_netrate');
            if($req->vendor_name!=''){
            $query->where('vendor_id',$req->vendor_name);  
            $vendor_name =$req->vendor_name; 
            }
                if($req->product_name!=''){
                $object = DB::table('items')->select('id')->where('name', 'LIKE','%'.$req->product_name.'%')->get()->toArray();
                $da = json_decode(json_encode($object), true);
                $arr = array();
                foreach($da as $da_d){
                array_push($arr,$da_d['id']);
                }
                $query->whereIn('product_id',$arr);
                $product_name = $req->product_name;

                }
            $data =$query->orderBy('id', 'DESC')->paginate(50);
        }
		//pr($data); die;
		return view('general.purchasediscountform.purchasenetratelist',['datas'=>$data,'product_name'=>$product_name,'vendor_name'=>$vendor_name]);
    }

	public function purchase_netrate_form_create(Request $request)
	{
	  $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
      $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        //pr($itemlist); die;
		return view('general.purchasediscountform.purchasenetrateformcreate',['datalist'=>$itemlist,'itemlist_count'=>$itemlist_count]);  
	}
	
	public function add_purchase_netrate_form(Request $request)
    {
        //pr($request->all()); die;
                    if($request->vendor_name==''){
                        $vendor_name = 0;
                    }else{
                        $vendor_name = $request->vendor_name;
                    }
                    $insertData = array(
                    "option"            =>      $request->option,
                    "customer_id"       =>      $request->customer_name,
                    "vendor_id"         =>      $vendor_name,
                    "product_id"        =>      $request->product_id,
                    "date_rate"         =>      date('Y-m-d',strtotime($request->get('date_rate'))),
                    "net_rate"          =>      $request->net_rate,
                    "min_order_qty"     =>      $request->min_order_qty,
                    "contact_name"      =>      $request->contact_name,
                    "email"             =>      $request->email,
                    "phone"             =>      $request->phone
                    );
                    //pr($insertData); die;
                    $insert = DB::table('tbl_purchase_netrate')->insert(array($insertData));
                    return redirect('admin/purchase-net-rate-list')->with('success', 'Purchase Net Rate added Successfully.');
    }
    
    
     public function delete_purchase_netrate(Request $req, $id)
    {
     //userPermission(Route::current()->getName());
     DB::table('tbl_purchase_netrate')->where('id',$id)->delete();
     return redirect('admin/purchase-net-rate-list')->with('success', 'Net Rate deleted Successfully.');   
    }
    
    public function purchase_netrate_data_update(Request $request) {
            $updated_on = date('Y-m-d H:i:s');
			$updateData = array(
				"date_rate"         =>      date('Y-m-d',strtotime($request->get('date_rate'))),
				"net_rate"          =>      $request->net_rate,
			);
			DB::table('tbl_purchase_netrate')->where('id',$request->id)->update($updateData);
			$arr = array('till_date' => globalDateformatNet(date('Y-m-d',strtotime($request->get('date_rate')))),'net_rate' => $request->net_rate,'updated_on' => globalDateformatNet($updated_on));
			return json_encode($arr);
    }
}