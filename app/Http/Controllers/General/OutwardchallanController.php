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

class OutwardchallanController extends Controller
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
		$oclist = DB::table('tbl_oc')->select('tbl_oc.*','customers.h_city','customers.company_name')->join('customers', 'customers.id', '=', 'tbl_oc.customer_id')->orderBy('tbl_oc.id','DESC')->paginate(50);
		}else{
		    //pr($request->all());
            $query = DB::table('tbl_oc')->select('tbl_oc.*','customers.h_city','customers.company_name')->join('customers', 'customers.id', '=', 'tbl_oc.customer_id');
            
            if($request->oc_no!=''){
            $query->where('tbl_oc.oc_no','LIKE','%'.$request->oc_no.'%'); 
            }
            
            if($request->customer_id!=''){
            $query->where('tbl_oc.customer_id',$request->customer_id);  
            }
            
            if($request->city!=''){
            $query->where('customers.h_city','LIKE','%'.$request->city.'%'); 
            }
            
            if($request->dispatch_date!=''){
            $query->where('tbl_oc.dispatch_date','LIKE','%'.date('Y-m-d',strtotime($request->dispatch_date)).'%'); 
            }
        
            if($request->user_id!=''){
             $query->where('user_id',$request->user_id); 
            }
        
        $oclist =$query->orderBy('id', 'DESC')->paginate(50);
		}
		//pr($oclist); die;
		return view('general.oc.oclist',['oclist'=>$oclist,'request'=>$request]);
	 }
	 
	 
	 public function details(Request $request, $id){
	    // $oc_data = DB::table('tbl_oc')->where('id',$id)->first();
 	     $oc_data = DB::table('tbl_oc')->select('tbl_oc.*','customers.h_city','customers.company_name')->join('customers', 'customers.id', '=', 'tbl_oc.customer_id')->where('tbl_oc.id',$id)->first();

	     //$oc_data_item = DB::table('tbl_oc_item')->where('oc_id',$id)->get()->toArray();
        $oc_data_item = DB::table('tbl_oc_item')->select('tbl_oc_item.*','items.vendor_sku','items.mrp','tbl_balance_stock.batch_no','tbl_balance_stock.loc_cen','room.room_no','rack.rack_name','tbl_balance_stock.mfg_date','tbl_balance_stock.expiry_date')->join('items', 'items.id', '=', 'tbl_oc_item.item_id')->join('tbl_balance_stock', 'tbl_balance_stock.id', '=', 'tbl_oc_item.item_stock_id')->join('room','room.id','=','tbl_balance_stock.room_no')->join('rack','rack.id','=','tbl_balance_stock.rack_no')->where('tbl_oc_item.dispatch_planning_status',1)->where('tbl_oc_item.oc_id',$id)->get()->toArray();
	     //pr($oc_data_item); die;
	     return view('general.oc.ocdetails',['oc_data'=>$oc_data,'oc_data_item'=>$oc_data_item,'request'=>$request]);
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
	    $challan_c = DB::table('tbl_challan_item')->where('challan_id',$id)->first();
	    $customer_id = $challan_c['customer_id'];
	    $customer_adv_payment = getCustomerAdvPayment($customer_id);
	    
	    $challanlist = DB::table('tbl_challan_item')->where('challan_id',$id)->orderBy('_id','DESC')->get();
	    
	    return view('general.challan.challanitemlist',['challanlist'=>$challanlist,'challan'=>$challan,'customer_adv_payment'=>$customer_adv_payment,'request'=>$request]);
	}
	
	public function challan_delete(Request $request,$id)
	{
	   DB::table('tbl_challan_item')->where('challan_id',$id)->delete(); 
	   DB::table('tbl_challan')->where('_id',$id)->delete(); 
	   return redirect()->route('challan_list')->with('success', 'Challan deleted successfully.');
	}
	
}