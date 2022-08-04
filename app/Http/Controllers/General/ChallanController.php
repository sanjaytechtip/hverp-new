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

class ChallanController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		if(empty($_POST))
		{
		$challanlist = DB::table('tbl_challan')->orderBy('id','DESC')->paginate(50);
        //pr($challanlist); die;
		}else{
		    //pr($request->all());
        $query = DB::table('tbl_challan');
        
        if($request->challan_no!=''){
        $query->where('challan_no','LIKE','%'.$request->challan_no.'%'); 
        }
        if($request->customer_id!=''){
        $query->where('customer_id',$request->customer_id);  
        }
        
        if($request->city!=''){
        $object = DB::table('customers')->select('id')->where('h_city', 'LIKE','%'.$request->city.'%')->get()->toArray();
        $da = json_decode(json_encode($object), true);
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,$da_d['id']);
        }
        $query->whereIn('customer_id',$arr);
        }
        
        if($request->dispatch_date!=''){
        $query->where('challan_date','LIKE','%'.date('Y-m-d',strtotime($request->dispatch_date)).'%'); 
        }
        
        
        // if($request->saleorder_ref_no!=''){
        // $da = DB::table('tbl_sale_order')->select('saleorder_no')->where('saleorder_ref_no', 'LIKE','%'.$request->saleorder_ref_no.'%')->get()->toArray();
        // $arr = array();
        // foreach($da as $da_d){
        // array_push($arr,(string)$da_d['saleorder_no']);
        // }
        // //pr($arr); die;
        // $query->whereIn('sale_order_no',$arr);
        // }
        
        
        if($request->created_date!=''){
        $object = DB::table('tbl_sale_order')->select('saleorder_no')->where('created_date', 'LIKE','%'.date('Y-m-d',strtotime($request->created_date)).'%')->get()->toArray();
        $da = json_decode(json_encode($object), true);
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,$da_d['saleorder_no']);
        }
        //pr($arr); die;
        $query->whereIn('sale_order_no',$arr);
        }
        
        
        if($request->item_name!=''){
        $object = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
        $da = json_decode(json_encode($object), true);
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,$da_d['id']);
        }
        //pr($arr); die;
        $query->whereIn('item_id',$arr);
        }
        
        if($request->user_id!=''){
        $query->where('created_by',$request->user_id); 
        }
        
        $challanlist =$query->orderBy('id', 'DESC')->paginate(50);
		}
		//pr($challanlist); die;
		return view('general.challan.challanlist',['challanlist'=>$challanlist,'request'=>$request]);
	 }
	 
	 public function challanlist(Request $request) {
		if(empty($_POST))
		{
		$challanlist = DB::table('tbl_challan_item')->orderBy('_id','DESC')->paginate(50);
		}else{
		    //pr($request->all());
        $query = DB::table('tbl_challan_item');
        
        if($request->challan_no!=''){
        $da = DB::table('tbl_challan')->select('_id')->where('challan_no', 'LIKE','%'.$request->challan_no.'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['_id']);
        }
        $query->whereIn('challan_id',$arr);
        }
        if($request->customer_id!=''){
        $query->where('customer_id',$request->customer_id);  
        }
        
        if($request->city!=''){
        $da = DB::table('customers')->select('_id')->where('h_city', 'LIKE','%'.$request->city.'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['_id']);
        }
        $query->whereIn('customer_id',$arr);
        }
        
        if($request->dispatch_date!=''){
        $da = DB::table('tbl_challan')->select('_id')->where('dispatch_date', 'LIKE', '%'.date('Y-m-d',strtotime($request->dispatch_date)).'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['_id']);
        }
        $query->whereIn('challan_id',$arr);
        }
        
        
        if($request->saleorder_ref_no!=''){
        $da = DB::table('tbl_sale_order')->select('saleorder_no')->where('saleorder_ref_no', 'LIKE','%'.$request->saleorder_ref_no.'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['saleorder_no']);
        }
        //pr($arr); die;
        $query->whereIn('sale_order_no',$arr);
        }
        
        
        if($request->created_date!=''){
        $da = DB::table('tbl_sale_order')->select('saleorder_no')->where('created_date', 'LIKE','%'.date('Y-m-d',strtotime($request->created_date)).'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['saleorder_no']);
        }
        //pr($arr); die;
        $query->whereIn('sale_order_no',$arr);
        }
        
        
        if($request->item_name!=''){
        $da = DB::table('items')->select('_id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
        $arr = array();
        foreach($da as $da_d){
        array_push($arr,(string)$da_d['_id']);
        }
        //pr($arr); die;
        $query->whereIn('item_id',$arr);
        }
        
        if($request->user_id!=''){
        $query->where('user_id',$request->user_id); 
        }
        
        $challanlist =$query->orderBy('_id', 'DESC')->paginate(50);
		}
		//pr($challanlist); die;
		return view('general.challan.challanlisting',['challanlist'=>$challanlist,'request'=>$request]);
	 }
	 public function view_challan(Request $request, $id){
	     $challan_data = DB::table('tbl_challan')->where('_id',$id)->first();
	     $challan_data_item = DB::table('tbl_challan_item')->where('challan_id',$id)->get()->toArray();
	     return view('general.challan.challanviewlist',['challan_data'=>$challan_data,'challan_data_item'=>$challan_data_item,'request'=>$request]);
	 }
	 
	 public function ajax_item_details_challan(Request $request)
	 {
	     $cus_data_city = getAllBuyerDataCity();
	     $challanlist = DB::table('tbl_challan_item')->where('challan_id',$request->id)->orderBy('_id','DESC')->get();
	     $html = '';
	     foreach($challanlist as $key=>$list)
	     {
	         $items = getChallanDetails($list['challan_id']);
	         if($cus_data_city[$list['customer_id']]['city']!='NULL'){ $city = $cus_data_city[$list['customer_id']]['city'];}else{$city='';}
	         if(getItemDetails($list['item_id'])['expiry_date']!='')
	         {
	           $time = '('.date('d/M/Y',strtotime(getItemDetails($list['item_id'])['expiry_date'])).')';  
	         }else{
	             $time = '';
	         }
	         if(getRoomName(getItemDetails($list['item_id'])['room_no'])!='' && getRackName(getItemDetails($list['item_id'])['rack_no'])!='' && getItemDetails($list['item_id'])['loc_cen']!='')
	         {
	           $roo_rac_loc =   getRoomName(getItemDetails($list['item_id'])['room_no']).'/'.getRackName(getItemDetails($list['item_id'])['rack_no']).'/'.getItemDetails($list['item_id'])['loc_cen'];
	         }else{
	            $roo_rac_loc =   ''; 
	         }
	         $html .='<tr><td class="white-space-normal">'.getAllitemsById($list['item_id']).'</td><td>'.$city.'</td>
	         <td>'.$roo_rac_loc.'</td><td>'.getItemDetails($list['item_id'])['batch_no'].$time.'</td><td>'.getItemDetails($list['item_id'])['rate'].'</td><td>'.getItemDetails($list['item_id'])['quantity'].'</td></tr>';
	     }
	     return $html;
	 }
	 
	 public function ajax_challan_search_data(Request $request)
	 {
	     if(!empty($request->challan_no))
	     {
	         $query = DB::table('tbl_challan')->where('challan_no',$request->challan_no)->first(); 
	         $challan_id = $query['_id'];
	         $challanlist = DB::table('tbl_challan_item')->where('challan_id', 'LIKE', "%{$challan_id}%")->paginate(50);
             $data_list_count =DB::table('tbl_challan_item')->where('challan_id', 'LIKE', "%{$challan_id}%")->count();
	         $html = "";
	         if(!empty($challanlist)){
	         foreach($challanlist as $list)
	         {
	          $html .='<tr>
                     <td><input type="checkbox" name="chk_search[]" class="chk_search" value="'.$list['_id'].'"></td>
                     <td class="white-space-normal">'.getAllitemsById($list['item_id']).'</td>
                     <td align="center">'.$list['dispatch_qty'].'</td>
                     <td>'.date('d-m-Y h:i:s',strtotime($list['created_date'])).'</td>
                 </tr>';   
	         }
	         }
	         $arr = array('html'=>$html,'record_count'=>$data_list_count);
	         return json_encode($arr);
	     }
	 }
	 
	 public function ajaxChallanItems(Request $request)
	 {
	     //pr($request->challan_id); die;
	      $html = "";
	      $i=1;
	     foreach($request->challan_id as $challan_no){
	         $challan_data = DB::table('tbl_challan_item')->where('_id',$challan_no)->first();
	         $items = DB::table('items')->where('_id',$challan_data['item_id'])->first();
	         if($items['rate']==''){$rate = 0;}
	         if($items['discount_per']==''){$disc = 0;}
	             $html .='<tr class="pur-san" id="data-row-'.$i.'"><input type="hidden" name="row['.$i.'][challan_id]" value="'.$challan_data['challan_id'].'"><td data-text="Sl"><span class="count-row"></span></td><td data-text="Sl"><input type="text" value="" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td><td data-text="Item"><input type="text" readonly value="'.$items['name'].'" name="row['.$i.'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required /><input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$items['_id'].'" /></td><td data-text="Vendor SKU"> <input type="text" readonly value="'.$items['vendor_sku'].'" name="row['.$i.'][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required /> </td><td data-text="SAP Code"> <input type="text" readonly name="row['.$i.'][sap_code]" value="'.$items['sap_code'].'" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" /></td><td data-text="HSN Code"> <input type="text" readonly value="'.$items['hsn_code'].'" name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'.$i.'" required /> </td><td data-text="Tax"> <input type="text" value="'.$items['tax_rate'].'" name="row['.$i.'][tax_rate]" cus="'.$i.'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required /> </td><td data-text="Packing Name"> <input type="text" readonly value="'.$items['packing_name'].'" name="row['.$i.'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> </td><td data-text="List Price"> <input type="text" readonly value="'.$items['list_price'].'" name="row['.$i.'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> </td><td data-text="Rate"> <input type="text" value="'.$rate.'" name="row['.$i.'][rate]" cus="'.$i.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" /> </td><td data-text="Stock"> <input type="text" value="'.$items['stock'].'" readonly name="row['.$i.'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> </td><td data-text="MRP"> <input type="text" readonly value="'.$items['mrp'].'" name="row['.$i.'][mrp]" cus="'.$i.'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> </td><td data-text="Dis %"> <input type="number" value="'.$disc.'" name="row['.$i.'][discount_per]" cus="'.$i.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" /> </td><td data-text="Discount"> <input type="number" readonly value="'.$items['discount'].'" name="row['.$i.'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" /> </td><td data-text="Quantity"> <input type="number" value="'.$challan_data['dispatch_qty'].'" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" /> </td><td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right">'.$items['net_rate'].'</td><td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right">'.$items['tax_amount'].'</td><td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right"></td><input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" value="'.$items['net_rate'].'" name="row['.$i.'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" value="'.$items['tax_amount'].'" name="row['.$i.'][tax_amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" value="'.$items['amount'].'" name="row['.$i.'][amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
	             $i++;
	        
	         
	     }
	     
	         $arr = array('html'=>$html);
	         return json_encode($arr);
	     
	 }
	
	
	public function challan_item_list(Request $request,$id)
	{
        $object_challan = DB::table('tbl_oc_item')->select('tbl_oc_item.*','tbl_challan.challan_date','tbl_challan.challan_no')->join('tbl_challan', 'tbl_challan.id', '=', 'tbl_oc_item.challan_id')->where('tbl_oc_item.challan_id',$id)->get()->toArray();
        $challan_c = json_decode(json_encode($object_challan), true);
        //pr($challan_c); die;
	    return view('general.challan.challanitemlist',['challanlist'=>$challan_c,'request'=>$request]);
	}
	
	public function challan_delete(Request $request,$id)
	{
	   DB::table('tbl_challan_item')->where('challan_id',$id)->delete(); 
	   DB::table('tbl_challan')->where('_id',$id)->delete(); 
	   return redirect()->route('challan_list')->with('success', 'Challan deleted successfully.');
	}
	
    public function make_challan(Request $request)
    {
        $customer_id            = $request['customer_id'];
        $challan_date           = date('Y-m-d H:i:s');
        $object_challan         = DB::table('tbl_config_challan')->first();
        $result_challan         = json_decode(json_encode($object_challan), true);
	    $challan_sr_no          = $result_challan['row']+1;
        $challan_no             = "CN".str_pad($challan_sr_no,4,"0",STR_PAD_LEFT);
        $challan_data = array('challan_no'=>$challan_no,'challan_date'=>$challan_date,'customer_id'=>$customer_id,'created_by'=>Auth::user()->id);
	    $last_id = DB::table('tbl_challan')->insertGetId($challan_data);
        DB::table('tbl_config_challan')->update(array('row'=>$challan_sr_no));
        if(!empty($request->id))
        {
        foreach($request->id as $challan_item_id)
        {
            $arr = array('status'=>1,'challan_id'=>$last_id);
            DB::table('tbl_oc_item')->where('id',$challan_item_id)->update($arr);
            $item_details_object = DB::table('tbl_oc_item')->select('item_id')->where('id',$challan_item_id)->first();
            $item_details         = json_decode(json_encode($item_details_object), true);
            balanceStockUpdate($item_details['item_id']);
        }
        }
        echo 1;
    }

    public function dispatch_planning(Request $request)
    {
            if(!$_POST){
            $object = DB::table('tbl_oc')
            ->select('tbl_oc_item.id','oc_no','dispatch_date','tbl_oc.customer_id','tbl_oc.user_id','tbl_oc_item.oc_id','item_stock_id','dispatch_qty',
            'tbl_oc_item.item_id','company_name','h_city','items.vendor_sku','batch_no','room.room_no',
            'rack_name','mfg_date','expiry_date','tbl_oc_item.created_at','loc_cen','scheduled_date')
            ->join('tbl_oc_item','tbl_oc.id','=','tbl_oc_item.oc_id')
            ->join('customers', 'customers.id', '=', 'tbl_oc.customer_id')
            ->join('items', 'items.id', '=', 'tbl_oc_item.item_id')
            ->join('tbl_balance_stock', 'tbl_balance_stock.id', '=', 'tbl_oc_item.item_stock_id')
            ->join('room','room.id','=','tbl_balance_stock.room_no')
            ->join('rack','rack.id','=','tbl_balance_stock.rack_no')
            ->join('tbl_sale_order_item','tbl_sale_order_item.id','=','tbl_oc_item.o_item_id')
            ->where(['tbl_oc_item.dispatch_planning_status' => 0,'status'=> 0])
            ->get();
            $dispatch_planning         = json_decode(json_encode($object), true);
            //pr($dispatch_planning); die;
            }else{
                $query = DB::table('tbl_oc');
                $query->select('tbl_oc_item.id','oc_no','dispatch_date','tbl_oc.customer_id','tbl_oc.user_id','tbl_oc_item.oc_id','item_stock_id','dispatch_qty',
            'tbl_oc_item.item_id','company_name','h_city','items.vendor_sku','batch_no','room.room_no',
            'rack_name','mfg_date','expiry_date','tbl_oc_item.created_at','loc_cen','scheduled_date')
            ->join('tbl_oc_item','tbl_oc.id','=','tbl_oc_item.oc_id')
            ->join('customers', 'customers.id', '=', 'tbl_oc.customer_id')
            ->join('items', 'items.id', '=', 'tbl_oc_item.item_id')
            ->join('tbl_balance_stock', 'tbl_balance_stock.id', '=', 'tbl_oc_item.item_stock_id')
            ->join('room','room.id','=','tbl_balance_stock.room_no')
            ->join('rack','rack.id','=','tbl_balance_stock.rack_no')
            ->join('tbl_sale_order_item','tbl_sale_order_item.id','=','tbl_oc_item.o_item_id');
                if($request->oc_no!=''){
                $query->where('tbl_oc.oc_no','LIKE','%'.$request->oc_no.'%'); 
                }
                if($request->city!=''){
                $query->where('h_city','LIKE','%'.$request->city.'%'); 
                }
                if($request->oc_date!=''){
                $query->where('tbl_oc_item.created_at','LIKE','%'.date('Y-m-d',strtotime($request->oc_date)).'%'); 
                }
                if($request->customer_id!=''){
                $query->where('tbl_oc.customer_id','LIKE','%'.$request->customer_id.'%'); 
                }
            $query->where(['tbl_oc_item.dispatch_planning_status' => 0,'status'=> 0]);
            $object = $query->get();
            $dispatch_planning         = json_decode(json_encode($object), true);
            }
            //pr($dispatch_planning); die;
            return view('general.challan.dispatch-planning-list',['dispatch_planning'=>$dispatch_planning,'request'=>$request]);
    }

    public function update_dispatch_planning(Request $request)
    {
        DB::table('tbl_oc_item')->whereIn('id', $request->id)->update(['dispatch_planning_status' =>1]);
        echo 1;
    }
}