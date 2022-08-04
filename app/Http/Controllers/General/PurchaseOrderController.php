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

class PurchaseOrderController extends Controller
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
		$purchaseorderlist = DB::table('tbl_purchase_order')->orderBy('id','DESC')->paginate(30);
		}else{
		    
		  $query = DB::table('tbl_purchase_order');
            if($request->purchaseorder_no!=''){
            $query->where('purchaseorder_no', 'LIKE','%'.$request->purchaseorder_no.'%');
            }
            if($request->purchaseorder_priority!=''){
            $query->where('purchaseorder_priority', 'LIKE','%'.$request->purchaseorder_priority.'%');
            }
            if($request->purchaseorder_date!=''){
            $query->where('purchaseorder_date', 'LIKE','%'.date('Y-m-d',strtotime($request->purchaseorder_date)).'%');
            }
            if($request->created_by!=''){
            $query->where('created_by',$request->created_by);
            }
            if($request->approved_by!=''){
            $query->where('approved_by',$request->approved_by);
            }
            if($request->customer_name!=''){
            $query->where('customer_name',$request->customer_name);  
            }
            if($request->purchaseorder_status!=''){
            $query->where('purchaseorder_status',$request->purchaseorder_status);  
            }
            $purchaseorderlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($purchaseorderlist);die; 
		return view('general.purchaseorder.purchaseorderlist',['purchaseorderlist'=>$purchaseorderlist,'request'=>$request]);		
	 }

	 
    public function create(Request $request) {
		
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->orderBy('id', 'DESC')->count();
        //$itemlist_pur = DB::table('items')->orderBy('id', 'DESC')->paginate(100);
       
        $query = \DB::table('items AS it')
        ->select(\DB::raw('it.*, 
        (SELECT SUM(poi.quantity) FROM tbl_purchase_order_item poi
        LEFT JOIN tbl_purchase_order po ON po.id = poi.purchase_order_id WHERE poi.item_id = it.id AND po.purchaseorder_status = "Approved") as po_items, (SELECT SUM(soi.quantity) FROM tbl_sale_order_item soi
        LEFT JOIN tbl_sale_order so ON so.id = soi.sale_order_id
        WHERE soi.item_id = it.id AND so.approved_by = "") as so_items'));
        $itemlist_pur =$query->orderBy('id', 'DESC')->paginate(100);

        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		
        $SrNo = DB::table('tbl_config_purchase_order')->first();
		$SrNo = (array)$SrNo;

        $query = DB::table('tbl_sale_order_item');
        $so_details =$query->orderBy('created_at', 'DESC')->paginate(100);
        $so_details_count =$query->orderBy('created_at', 'DESC')->count();
        if(empty($_POST)){
		return view('general.purchaseorder.purchaseordercreate',['so_details_count'=>$so_details_count,'so_details'=>$so_details,'datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo['row'],'itemlist_pur'=>$itemlist_pur,'itemlist_count'=>$itemlist_count]);
        }else{
            //pr($request->all()); die;
            if($request->purchaseorder_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            $arr = array(
                            'purchaseorder_type'                =>  $request->purchaseorder_type,
                            'purchaseorder_srno'                =>  $request->purchaseorder_srno,
                            'purchaseorder_no'                  =>  $request->purchaseorder_no,
                            'purchaseorder_priority'            =>  $request->purchaseorder_priority,
                            'vendor_adv_amount'                 =>  isset($request->vendor_adv_amount)?$request->vendor_adv_amount:"",
                            'customer_name'                     =>  $request->customer_name,
                            'vendor_name'                       =>  $request->vendor_name,
                            'purchaseorder_date'                =>  date('Y-m-d',strtotime($request->purchaseorder_date)),
                            'purchaseorder_due_date'            =>  date('Y-m-d',strtotime($request->purchaseorder_due_date)),
                            'purchaseorder_ref_date'            =>  date('Y-m-d',strtotime($request->purchaseorder_ref_date)),
                            'purchaseorder_remarks'             =>  $request->purchaseorder_remarks,
                            'purchaseorder_status'              =>  $request->purchaseorder_status,
                            'purchaseorder_approved'            =>  $request->purchaseorder_approved,
                            'purchaseorder_contact_name'        =>  $request->purchaseorder_contact_name,
                            'purchaseorder_contact_department'  =>  $request->purchaseorder_contact_department,
                            'purchaseorder_contact_phone'       =>  $request->purchaseorder_contact_phone,
                            'purchaseorder_contact_email'       =>  $request->purchaseorder_contact_email,
                            'purchaseorder_subtotal'            =>  $request->purchaseorder_subtotal,
                            'purchaseorder_saletax'             =>  $request->purchaseorder_saletax,
                            'purchaseorder_tax_amount'          =>  $request->purchaseorder_tax_amount,
                            'purchaseorder_grand_total'         =>  $request->purchaseorder_grand_total,
                            /* 'other_charges_val'                 =>  $request->other_charges_val,
                            'other_charges_name'                =>  $request->other_charges_name, */
                            'created_by'                        =>  Auth::user()->id,
                            'approved_by'                       =>  $approved_by,
                            'created_at'                      =>  date('Y-m-d H:i:s')
                            
                        );
						//pr($arr); die;
            $last_id = DB::table('tbl_purchase_order')->insertGetId($arr);
            
			if(!empty($request->other_charges_val)){
                foreach($request->other_charges_val as $key=>$rows_charges)
                {
                    $data_charges = array(
                        'purchase_id'          => $last_id,
                        'other_charges_name'    => $request->other_charges_name[$key],
                        'other_charges_val'     => $rows_charges
                    );
                    DB::table('tbl_purchase_shipping_charge')->insertGetId($data_charges);
                }
            }
			
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'   =>$rows['cust_ref_no'],
                    'item_name'     =>$rows['item_name'],
                    'item_id'       =>$rows['item_id'],
                    'vendor_sku'    =>$rows['vendor_sku'],
                    'sap_code'      =>$rows['sap_code'],
                    'hsn_code'      =>$rows['hsn_code'],
                    'tax_rate'      =>$rows['tax_rate'],
                    'grade'         =>$rows['grade'],
                    'brand'         =>$rows['brand'],
                    'packing_name'  =>$rows['packing_name'],
                    'list_price'    =>$rows['list_price'],
                    'rate'          =>$rows['rate'],
                    'stock'         =>$rows['stock'],
                    'mrp'           =>$rows['mrp'],
                    'discount_per'  =>$rows['discount_per'],
                    'discount'      =>$rows['discount'],
                    'quantity'      =>$rows['quantity'],
                    'adv_amount'    =>  isset($rows['adv_amount'])?$rows['adv_amount']:"",
                    'net_rate'      =>$rows['net_rate'],
                    'tax_amount'    =>$rows['tax_amount'],
                    'amount'        =>$rows['amount'],
                    'inward_qty'    => "0",
                    'customer_id'   =>$request->customer_name,
                    'vendor_id'   =>$request->vendor_name,
                    'purchase_order_id' =>$last_id,
                    'created_at'  => date('Y-m-d H:i:s'),
                    );
            DB::table('tbl_purchase_order_item')->insertGetId($data);
            }			
            }
            DB::table('tbl_config_purchase_order')->update(array('row'=>$request->purchaseorder_srno+1));
			
            return redirect()->route('purchaseorder_list')->with('success', 'Purchaseorder saved successfully.');
            
        }
    }
    
    
	
	public function view($id)
	{
	    $purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
		$purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$other_charges_data = DB::table('tbl_purchase_shipping_charge')->where('purchase_id', $id)->orderBy('id', 'ASC')->get()->toArray();
		return view('general.purchaseorder.purchaseorderview',['purchaseorder_details'=>$purchaseorder_details,'purchaseorder_item_details'=>$purchaseorder_item_details,'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data]);
		
	}
	

	
	public function purchaseorder_print($id)
	{
	    $purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
		$purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$other_charges_data = DB::table('tbl_purchase_shipping_charge')->where('purchase_id', $id)->orderBy('id', 'ASC')->get()->toArray();
		$company_details = DB::table('company_details')->first();
		//pr($saleorder_item_details); die;
		return view('general.purchaseorder.purchaseorderprint',['purchaseorder_details'=>$purchaseorder_details,'purchaseorder_item_details'=>$purchaseorder_item_details,'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data,'company_details'=>$company_details]);
	}
	
	public function purchaseRevise($id){
		$purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
		$purchaseorder_details = (array) $purchaseorder_details;
		//pr($purchaseorder_details);
		$purchaseorder_details['revised_id'] = $id;
        $purchaseorder_details['revised_date'] = date('Y-m-d H:i:s');
		
		unset($purchaseorder_details['id']); 
		//pr($purchaseorder_details); 
		$last_id = DB::table('tbl_purchase_order_revised')->insertGetId($purchaseorder_details);

		$purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
		//pr($purchaseorder_item_details); die('jjj');
        foreach($purchaseorder_item_details as $details_item){
				$details_item = (array) $details_item;
				unset($details_item['id']);
				unset($details_item['purchase_order_id']);
				$details_item['revised_id'] = $id;
				$details_item['purchase_order_id'] = $last_id;
				$details_item['revised_date'] = date('Y-m-d H:i:s');
				DB::table('tbl_purchase_order_item_revised')->insert(array($details_item));
        }
		
		
		$other_charges_data = DB::table('tbl_purchase_shipping_charge')->where('purchase_id', $id)->orderBy('id', 'ASC')->get()->toArray();
			//pr($other_charges_data);
			if(!empty($other_charges_data)){
                foreach($other_charges_data as $key=>$rows_charges)
                {
                    $data_charges = array(
											'purchase_id'=> $last_id,
											'revised_id'=> $id,
											'revised_date'=> date('Y-m-d H:i:s'),
											'other_charges_name'=> $rows_charges->other_charges_name,
											'other_charges_val'=> $rows_charges->other_charges_val,
											'created_at'=> $rows_charges->created_at,
											'update_at'=> $rows_charges->update_at,
										);
                    DB::table('tbl_purchase_shipping_charge_revised')->insert($data_charges);
                }
            }
			return true;
	}
	
    public function edit(Request $request, $id)
    {
		//pr($_POST); die;
		$purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
		$purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		
		$other_charges_data = DB::table('tbl_purchase_shipping_charge')->where('purchase_id', $id)->orderBy('id', 'ASC')->get()->toArray();
		
		$itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $itemlist_pur = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(100);
        $query = DB::table('tbl_sale_order_item');
        $so_details =$query->orderBy('created_at', 'DESC')->paginate(100);
        $so_details_count =$query->orderBy('created_at', 'DESC')->count();
		//pr($saleorder_item_details); die;
		if(empty($_POST)){
			
		return view('general.purchaseorder.purchaseorderedit',['so_details_count'=>$so_details_count,'so_details'=>$so_details,'itemlist_pur'=>$itemlist_pur,'datalist'=>$itemlist,'purchaseorder_details'=>$purchaseorder_details,'purchaseorder_item_details'=>$purchaseorder_item_details,'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data,'itemlist_count'=>$itemlist_count]);
		}else{
		    
            //pr($request->all()); die;
        $purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
        $purchaseorder_details = (array) $purchaseorder_details;
		//pr($purchaseorder_details); die;
        $purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        
		
		/* purchase order revised */
		$purchase_rev = $this->purchaseRevise($id);
		
        //pr($quotation_details); die;
        if($request->purchaseorder_status=='Approved' || $request->purchaseorder_status=='Completed'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            $arr = array(
                            'purchaseorder_type'                =>  $request->purchaseorder_type,
                            'purchaseorder_srno'                =>  $request->purchaseorder_srno,
                            'purchaseorder_no'                  =>  $request->purchaseorder_no,
                            'purchaseorder_priority'            =>  $request->purchaseorder_priority,
                            'vendor_adv_amount'                 =>  isset($request->vendor_adv_amount)?$request->vendor_adv_amount:NULL,
                            'customer_name'                     =>  $request->customer_name,
                            'vendor_name'                       =>  $request->vendor_name,
                            'purchaseorder_date'                =>  date('Y-m-d',strtotime($request->purchaseorder_date)),
                            'purchaseorder_due_date'            =>  date('Y-m-d',strtotime($request->purchaseorder_due_date)),
                            'purchaseorder_ref_date'            =>  date('Y-m-d',strtotime($request->purchaseorder_ref_date)),
                            'purchaseorder_remarks'             =>  $request->purchaseorder_remarks,
                            'purchaseorder_status'              =>  $request->purchaseorder_status,
                            'purchaseorder_approved'            =>  $request->purchaseorder_approved,
                            'purchaseorder_contact_name'        =>  $request->purchaseorder_contact_name,
                            'purchaseorder_contact_department'  =>  $request->purchaseorder_contact_department,
                            'purchaseorder_contact_phone'       =>  $request->purchaseorder_contact_phone,
                            'purchaseorder_contact_email'       =>  $request->purchaseorder_contact_email,
                            'purchaseorder_subtotal'            =>  $request->purchaseorder_subtotal,
                            'purchaseorder_saletax'             =>  $request->purchaseorder_saletax,
                            'purchaseorder_tax_amount'          =>  $request->purchaseorder_tax_amount,
                            'purchaseorder_grand_total'         =>  $request->purchaseorder_grand_total,
                            /* 'other_charges_val'            		=> '',
                            'other_charges_name'            	=>  '', */
                            'created_by'                    =>  Auth::user()->id,
                            'approved_by'                   =>  $approved_by,
                            'updated_at'                   =>  date('Y-m-d H:i:s')
                            
                        );
            $last_id = DB::table('tbl_purchase_order')->where('id',$id)->update($arr);
            DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->delete();
			
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'   =>$rows['cust_ref_no'],
                    'item_name'     =>$rows['item_name'],
                    'item_id'       =>$rows['item_id'],
                    'vendor_sku'    =>$rows['vendor_sku'],
                    'sap_code'      =>$rows['sap_code'],
                    'hsn_code'      =>$rows['hsn_code'],
                    'tax_rate'      =>$rows['tax_rate'],
                    'grade'         =>$rows['grade'],
                    'brand'         =>$rows['brand'],
                    'packing_name'  =>$rows['packing_name'],
                    'list_price'    =>$rows['list_price'],
                    'rate'          =>$rows['rate'],
                    'stock'         =>$rows['stock'],
                    'mrp'           =>$rows['mrp'],
                    'discount_per'  =>$rows['discount_per'],
                    'discount'      =>$rows['discount'],
                    'quantity'      =>$rows['quantity'],
                    'adv_amount'    =>  isset($rows['adv_amount'])?$rows['adv_amount']:NULL,
                    'net_rate'      =>$rows['net_rate'],
                    'tax_amount'    =>$rows['tax_amount'],
                    'amount'        =>$rows['amount'],
                    'customer_id'   =>$request->customer_name,
                    'vendor_id'     =>  $request->vendor_name,
                    'purchase_order_id'  =>$id,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                    );
                    //pr($data); die;
            DB::table('tbl_purchase_order_item')->insertGetId($data);
            }			
            }
			
			
			DB::table('tbl_purchase_shipping_charge')->where('purchase_id',$id)->delete();
			if(!empty($request->other_charges_val)){
                foreach($request->other_charges_val as $key=>$rows_charges)
                {
                    $data_charges = array(
                        'purchase_id'          => $id,
                        'other_charges_name'    => $request->other_charges_name[$key],
                        'other_charges_val'     => $rows_charges
                    );
                    DB::table('tbl_purchase_shipping_charge')->insert($data_charges);
                }
            }
			
            return redirect()->route('purchaseorder_list')->with('success', 'Purchase Order Updated successfully.');
            
        
		}
    }

    
   
    public function destroy($id)
    {
		//checkPermission('delete_pi');
         if($id!='') {
			DB::table('tbl_purchase_order')->where('id', '=', $id)->delete();
			DB::table('tbl_purchase_order_item')->where('purchase_order_id', '=', $id)->delete();
		}
		Session::flash('success', 'Purchase Order deleted successfully!');
		return redirect()->route('purchaseorder_list')->with('danger', 'Purchase Order deleted successfully.');
    } 
	
	
	
	public function purchaseorder_pdf($id)
	{
	    $purchaseorder_details = DB::table('tbl_purchase_order')->where('id',$id)->get()->first();
		$purchaseorder_item_details = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$other_charges_data = DB::table('tbl_purchase_shipping_charge')->where('purchase_id', $id)->orderBy('id', 'ASC')->get()->toArray();
		$company_details = DB::table('company_details')->first();
		$view = \View::make('general.purchaseorder.purchaseorderpdf',['purchaseorder_details' => $purchaseorder_details, 'purchaseorder_item_details' => $purchaseorder_item_details, 'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data, 'company_details' => $company_details]);
		$html_content = $view->render(); 
 		PDF::SetTitle('Purchase Order');
		PDF::SetFont('helvetica', '', 8);
 		PDF::AddPage('L');
 		PDF::writeHTML($html_content, true, false, true, false, '');
 		$filename = $purchaseorder_details->purchaseorder_no.'.pdf';
 		PDF::Output($filename,'D');
 		exit;
	}
	
	public function net_rate_search_po_details(Request $request)
	{
	$query = DB::table('tbl_sale_order_item');
    if($request->item_id!=''){
    $query->where('item_id',$request->item_id);
    }
    $so_details =$query->orderBy('created_at', 'DESC')->paginate(100); 
    $so_details_count =$query->orderBy('created_at', 'DESC')->count();
    //pr($so_details->items()); die;
    $html = '';
            if(!empty($so_details->items()))
            {
            $cus_data = getAllBuyerData();
            $cus_data_city = getAllBuyerDataCity();
            foreach($so_details as $quo)
            {
            $get_quotation_item = getSaleOrderItemDetails($quo['sale_order_id']);
            if($cus_data_city[$quo['customer_id']]['city']!='NULL'){
                $city = $cus_data_city[$quo['customer_id']]['city'];
            }else{
                $city = '';
            }
            if($get_quotation_item['saleorder_date']!='1970-01-01'){
                $sale_date = globalDateformatNet($get_quotation_item['saleorder_date']);
            }else{
                $sale_date = '';
            }
            $html .='<tr>
                  <td>'.$cus_data[$quo['customer_id']].'</td>
                  <td>'.$city.'</td>
                  <td>'.$quo['item_name'].'</td>
                  <td>'.$get_quotation_item['saleorder_no'].'</td>
                  <td>'.$sale_date.'</td>
                  <td style="text-align:center;">'.$quo['quantity'].'</td>
                  <td style="text-align:center;">0</td>
                  </tr>';
                
             }}else{
            $html .='<tr>
                <td colspan="7" style="text-align:center;">No record found.</td>
              </tr>';
            }
            $arr = array('html' => $html,'record_count'=>$so_details_count);
            
            return json_encode($arr);
	}
	
	public function net_rate_search_purchase(Request $request)
    {
            $item_data = getAllitemsDataList();
            $item_vendor_sku = getAllitemsVendorSKUList();
            $item_brand = getAllitemsBrandList();
            $item_packing_name = getAllitemsPackingList();
            $item_grade_name = getAllitemsGradeList();
            //$query = DB::table('items');
			
			$query = \DB::table('items AS it')
			->select(\DB::raw('it.*, 
			(SELECT SUM(poi.quantity) FROM tbl_purchase_order_item poi
 LEFT JOIN tbl_purchase_order po ON po.id = poi.purchase_order_id WHERE poi.item_id = it.id AND po.purchaseorder_status = "Approved") as po_items, (SELECT SUM(soi.quantity) FROM tbl_sale_order_item soi
LEFT JOIN tbl_sale_order so ON so.id = soi.sale_order_id
WHERE soi.item_id = it.id AND so.approved_by = "") as so_items'));
 
/*  , (SELECT SUM(soi.quantity) FROM tbl_sale_order_item soi
LEFT JOIN tbl_sale_order so ON so.id = soi.sale_order_id
WHERE soi.item_id = it.id AND soi.approved_by = '') as so_items' */
			            
            if($request->vendor_sku!=''){
            $query->where('vendor_sku', 'LIKE', checkChar($request->vendor_sku));  
            $vendor_sku =$request->vendor_sku; 
            }
            if($request->name!=''){
            $query->where('name', 'LIKE', checkChar($request->name));  
            $name =$request->name; 
            }
            
            if($request->grade!=''){
            $query->where('grade', 'LIKE', checkChar($request->grade));  
            $grade =$request->grade; 
            }
            if($request->brand!=''){
            $query->where('brand', 'LIKE', checkChar($request->brand));  
            $brand =$request->brand; 
            }
            if($request->packing_name!=''){
            $query->where('packing_name', 'LIKE', checkChar($request->packing_name));  
            $packing_name =$request->packing_name; 
            }
            
            $data_list =$query->orderBy('id', 'DESC')->paginate(100);
			
            /* pr($data_list); die; */
			
            $data_list_count =$query->orderBy('id', 'DESC')->count();
            $html = '';
            if(!empty($data_list))
            {
            $i=1;
            foreach($data_list as $user)
            {
				$user = (array) $user;
				$po_items = !empty($user['po_items'])?$user['po_items']:'0';
				$so_items = !empty($user['so_items'])?$user['so_items']:'0';
				
                $html .='<tr id="san" cus="'.$i.'" item_id="'.$user['id'].'">
                <td style="text-align:center;" class="white-space-normal">'.getAllitemsById($user['id']).'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['vendor_sku'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['brand'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['packing_name'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['grade'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.getOpeningStocksQty($user['id']).'</td>
                <td style="text-align:center;" class="white-space-normal">'.getInwardStocksQty($user['id']).'</td>
                <td style="text-align:center;" class="white-space-normal">0</td>
                <td style="text-align:center;" class="white-space-normal">'.getItemStocks($user['id']).'</td>
                <td style="text-align:center;" class="white-space-normal">'.$po_items.'</td>
                <td style="text-align:center;" class="white-space-normal">'.$so_items.'</td>
                <td style="text-align:center;" class="white-space-normal">0</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['mrp'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['quantity'].'</td>
                <td style="text-align:center;" class="white-space-normal">
                <input class="custom-purchase-qty" id="'.$user['id'].'" type="number" min="1" name="purchase_qty">
                </td>
               </tr>';
               
             }$i++;}else{
            $html .='<tr>
                <td colspan="8">No record found.</td>
              </tr>';
            }
            $arr = array('html' => $html,'record_count' => $data_list_count);
            
            return json_encode($arr);
            
    }
	
	
	public function net_rate_search_purchase_post(Request $request)
	{
		//pr($_POST); die;
		
	    if(!empty($request->ids) && !empty($request->values))
	    {
	    $data_array = array_combine($request->ids,$request->values);
	    if(!empty($data_array))
	    {
	    $html = "";
	    
		if($request->rowCount>0){
			$i=$request->rowCount+1;
		}else{
			$i=1;
		}
		
	
	    foreach($data_array as $key1=>$d_arr)
	    {
	    $quotation = DB::table('items')->where('id',$key1)->get()->first();
		$quotation = (array) $quotation;

        $vendor_item = '';
        $get_item = '';
        $vendor_brand = '';
        $get_discount = '';
        $discount = '';

        //START- netrate by vendor and item
        if(!empty($request->vendor_name) && !empty($key1)){
            //echo 'netrate by vendor and item<br>';
            $vendor_item = get_netrate_by_vendor_item($request->vendor_name,$key1);
            if(!empty($vendor_item)){
                 $net_rate = $vendor_item;  
            }		   
        }

        // netrate by item
	   if(empty($vendor_item))
	   {
	       //echo 'netrate by item<br>';
		  $get_item  = get_netrate_by_item_vendor($key1); 
		  //pr($get_item); die;
		  if(!empty($get_item)){
		     $net_rate = $get_item;  
		  }
	   }
	   //END- netrate by vendor and item


        //START- Discount by vendor and brand

       //Discount by vendor and brand
	   if(empty($vendor_item) && empty($get_item)){
        if(!empty($request->vendor_name) && !empty($quotation['brand'])){
           // echo 'Discount by vendor and brand<br>';
            $vendor_brand = get_discount_by_vendor_brand($request->vendor_name,GetBrandId($quotation['brand']));   
            if(!empty($vendor_brand)){
                 $discount = $vendor_brand['discount']; 
                 $discount_by = $vendor_brand['discount_by']; 
                 $net_rate = ($discount_by==1)?$quotation['list_price']:$quotation['mrp'];
                 
            }
        }
        }

        // Discount by brand
	   if(empty($vendor_item) && empty($get_item)){
        if(empty($vendor_brand))
         {
             //echo 'Discount by brand<br>';
            $get_discount  = get_discount_by_brand_vendor(GetBrandId($quotation['brand'])); 
            if(!empty($get_discount)){
               $discount = $get_discount['discount']; 
               $discount_by = $get_discount['discount_by']; 
               $net_rate = ($discount_by==1)?$quotation['list_price']:$quotation['mrp']; 
               
            }
         }
         
         }


         if(empty($vendor_item) && empty($get_item) && empty($discount)){
            if(empty($get_item) && empty($discount))
            {
               // echo 'List Price<br>';
                $net_rate = $quotation['list_price'];
               
                 
            }
        }


        if(!empty($discount))
	   {
	       $discount_data = $discount;
	   }else{
	       $discount_data = 0;
	   }
       if(!empty($net_rate))
	   {
	       $net_rate = $net_rate;
	   }else{
	       $net_rate = 0;
	   }

       
       if(trim($quotation['tax_rate'])=='NULL'){
        $tax_rate = 0;
       }else{
        $tax_rate = $quotation['tax_rate'];
       }
	   //echo $net_rate; die;
	   $total_disc = ($net_rate * $discount_data)/100;

	    $html .='<tr id="data-row-'.$i.'"><td class="task_left_fix" data-text="Sl">'.$i.'</td><td class="task_left_fix" data-text="Sl"><input type="text" value="" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td><td class="task_left_fix" data-text="Item"><input type="text" readonly value="'.$quotation['name'].'" name="row['.$i.'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required /><input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$quotation['id'].'" /></td><td data-text="Vendor SKU"> <input type="text" readonly value="'.$quotation['vendor_sku'].'" name="row['.$i.'][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required /> </td><td data-text="SAP Code"> <input type="text" readonly name="row['.$i.'][sap_code]" value="" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" /></td><td data-text="HSN Code"> <input type="text" readonly value="'.$quotation['hsn_code'].'" name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'.$i.'" required /> </td><td data-text="Tax"> <input type="text" value="'.$tax_rate.'" name="row['.$i.'][tax_rate]" cus="'.$i.'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required /> </td><td data-text="Packing Name"> <input type="text" readonly value="'.$quotation['packing_name'].'" name="row['.$i.'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> </td><td data-text="List Price"> <input type="text" readonly value="" name="row['.$i.'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> </td><td data-text="Rate"> <input type="text" value="'.$net_rate.'" name="row['.$i.'][rate]" cus="'.$i.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" /> </td><td data-text="Stock"> <input type="text" value="'.$quotation['stock'].'" readonly name="row['.$i.'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> </td><td data-text="MRP"> <input type="text" readonly value="'.$quotation['mrp'].'" name="row['.$i.'][mrp]" cus="'.$i.'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> </td><td data-text="Dis %"> <input type="number" min="0" value="'.$discount.'" name="row['.$i.'][discount_per]" cus="'.$i.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" /> </td><td data-text="Discount"> <input type="number" readonly value="" name="row['.$i.'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" /> </td><td data-text="Quantity"> <input type="number" min="1" required value="'.$d_arr.'" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" /> </td><td data-text="Adv Amount (%)"><input type="number" name="row['.$i.'][adv_amount]" cus="'.$i.'" class="autocomplete-dynamic adv_amount form-control" id="adv_amount_rel_'.$i.'" /></td><td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right"></td><td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right"></td><td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right"></td><input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" value="" name="row['.$i.'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" value="" name="row['.$i.'][tax_amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" value="" name="row['.$i.'][amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
	    $i++;
	    }
	    
	    return $html;
	    }
	    }
	}
	
	public function purchase_net_rate_search_qu(Request $request)
	{
	    //pr($request->all()); die;
        $object = DB::table('group_of_company')->select('g_company_name')->whereRaw("FIND_IN_SET(?, g_company_name) > 0", [$request->customer_id])->first();
	    $k = json_decode(json_encode($object), true);
        $query = DB::table('tbl_quotation')->select('id','quotation_no','created_at','SO_No','SO_Date')->where('customer_name',$request->customer_id);  
        if($request->quotation_no!=''){
        $query->where('quotation_no', 'LIKE', '%'.$request->quotation_no.'%');  
        $quotation_no =$request->quotation_no; 
        }
        $data_list =$query->whereIn('customer_name',explode(',',$k['g_company_name']))->where('quotation_status','Approved');
        $data_list =$query->orderBy('quotation_no', 'asc')->paginate(50);
        $data_list_count =$query->orderBy('quotation_no', 'asc')->count();
        $html = "";
	    if(!empty($data_list)){
	    foreach($data_list as $de){
            $de = (array)$de;
	        $html .='<tr>
                     <td><input type="checkbox" name="chk_search[]" class="chk_search" value="'.$de['id'].'"></td>
                     <td>'.$de['quotation_no'].'</td>
                     <td>'.date('d-m-Y h:i:s',strtotime($de['created_at'])).'</td>
                     <td>'.$de['SO_No'].'</td>
                     <td>'.$de['SO_Date'].'</td>
                 </tr>';
	    }
	    }
	    $arr = array('html'=>$html,'record_count'=>$data_list_count);
	    return json_encode($arr);
	}
	
	
	
	public function purchase_net_rate_search(Request $request)
    {
       
            $query = DB::table('items');
            if($request->vendor_sku!=''){
            $query->where('vendor_sku', 'LIKE', checkChar($request->vendor_sku));  
            $vendor_sku =$request->vendor_sku; 
            }
            if($request->name!=''){
            $query->where('name', 'LIKE', checkChar($request->name));  
            $name =$request->name; 
            }
            
            if($request->synonyms!=''){
            $query->where('synonyms', 'LIKE', '%'.$request->synonyms.'%');  
            $synonyms =$request->synonyms; 
            }
            
            if($request->grade!=''){
            $query->where('grade', 'LIKE', checkChar($request->grade));  
            $grade =$request->grade; 
            }
            if($request->brand!=''){
            $query->where('brand', 'LIKE', checkChar($request->brand));  
            $brand =$request->brand; 
            }
            if($request->packing_name!=''){
            $query->where('packing_name', 'LIKE', checkChar($request->packing_name));  
            $packing_name =$request->packing_name; 
            }
            if($request->hsn_code!=''){
            $query->where('hsn_code', 'LIKE', checkChar($request->hsn_code));  
            $hsn_code =$request->hsn_code; 
            }
            if($request->is_verified!=''){
            $query->where('is_verified',$request->is_verified);  
            $is_verified =$request->is_verified; 
            }
            //$data_list =$query->orderBy('id', 'DESC')->get()->toArray();
            $object =$query->orderBy('id', 'DESC')->paginate(10);
            $data_list = json_decode(json_encode($object), true);
            //pr($data_list); die;
            $data_list_count =$query->orderBy('id', 'DESC')->count();
            $html = '';
            $html .='<table class="table table-bordered t1" id="data-table">
              <thead>
                <th class="bg-info">Apply</th>
                <th class="bg-info">Vendor SKU</th>
                <th class="bg-info">Group Name</th>
                <th class="bg-info">HSN Code</th>
                <th class="bg-info">Grade</th>
                <th class="bg-info">Brand</th>
                <th class="bg-info">Packing Name</th>
                <th class="bg-info">List Price</th>
                <th class="bg-info">MRP</th>
                <!--<th class="bg-info">Net Rate</th>-->
                <th class="bg-info">Stock</th>
                  </thead>
              <tbody id="results">'; 
            if(!empty($data_list['data']))
            {
            foreach($data_list['data'] as $user)
            {
            $html .='<tr>
                <td style="text-align:center;" class="white-space-normal item-edit"><input validate="'.getItemNameData($user['id']).'" id="'.$user['id'].'" value="'.$user['vendor_sku'].'-'.$user['name'].'-'.$user['hsn_code'].'-'.$user['grade'].'-'.$user['brand'].'-'.$user['packing_name'].'-'.$user['list_price'].'-'.$user['mrp'].'-'.$user['net_rate'].'-'.$user['stock'].'" name="apply-radio" type="checkbox">&nbsp;<a target="_blank" href="'.url('admin/editdata/items/'.$user['id'].'/35/').'"><i class="icon md-edit" aria-hidden="true"></i></a></td>
                <td style="text-align:center;" class="white-space-normal">'.$user['vendor_sku'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['name'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['hsn_code'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['grade'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['brand'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['packing_name'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['list_price'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['mrp'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['stock'].'</td>
               </tr>';
                
             }}else{
            $html .='<tr>
                <td colspan="8">No record found.</td>
              </tr>
              </tbody>
            </table>';
            }
            
            $arr = array('html' => $html,'record_count' => $data_list_count);
            
            return json_encode($arr);
    }
    
    
    public function purchase_net_rate_search_po(Request $request)
    {
     $query = DB::table('items')->where('user_role','!=','1');
            if($request->vendor_sku!=''){
            $query->where('vendor_sku', 'LIKE', checkChar($request->vendor_sku));  
            $vendor_sku =$request->vendor_sku; 
            }
            if($request->name!=''){
            $query->where('name', 'LIKE', checkChar($request->name));  
            $name =$request->name; 
            }
            
            if($request->synonyms!=''){
            $query->where('synonyms', 'LIKE', '%'.$request->synonyms.'%');  
            $synonyms =$request->synonyms; 
            }
            
            if($request->grade!=''){
            $query->where('grade', 'LIKE', checkChar($request->grade));  
            $grade =$request->grade; 
            }
            if($request->brand!=''){
            $query->where('brand', 'LIKE', checkChar($request->brand));  
            $brand =$request->brand; 
            }
            if($request->packing_name!=''){
            $query->where('packing_name', 'LIKE', checkChar($request->packing_name));  
            $packing_name =$request->packing_name; 
            }
            if($request->hsn_code!=''){
            $query->where('hsn_code', 'LIKE', checkChar($request->hsn_code));  
            $hsn_code =$request->hsn_code; 
            }
            if($request->is_verified!=''){
            $query->where('is_verified',$request->is_verified);  
            $is_verified =$request->is_verified; 
            }
            //$data_list =$query->orderBy('id', 'DESC')->get()->toArray();
            $data_list =$query->orderBy('id', 'DESC')->paginate(10);
            $data_list_count =$query->orderBy('id', 'DESC')->count();
            $html = '';
            $html .='<table class="table table-bordered t1" id="data-table">
              <thead>
                <th class="bg-info">Apply</th>
                <th class="bg-info">Vendor SKU</th>
                <th class="bg-info">Group Name</th>
                <th class="bg-info">HSN Code</th>
                <th class="bg-info">Grade</th>
                <th class="bg-info">Brand</th>
                <th class="bg-info">Packing Name</th>
                <th class="bg-info">List Price</th>
                <th class="bg-info">MRP</th>
                <!--<th class="bg-info">Net Rate</th>-->
                <th class="bg-info">Stock</th>
                  </thead>
              <tbody id="results">'; 
            if(!empty($data_list))
            {
            foreach($data_list as $user)
            {
            $html .='<tr>
                <td style="text-align:center;" class="white-space-normal"><input id="'.$user[id].'" value="'.$user['vendor_sku'].'-'.$user['name'].'-'.$user['hsn_code'].'-'.$user['grade'].'-'.$user['brand'].'-'.$user['packing_name'].'-'.$user['list_price'].'-'.$user['mrp'].'-'.$user['net_rate'].'-'.$user['stock'].'" name="apply-radio" type="radio"></td>
                <td style="text-align:center;" class="white-space-normal">'.$user['vendor_sku'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['name'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['hsn_code'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['grade'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['brand'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['packing_name'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['list_price'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['mrp'].'</td>
                <td style="text-align:center;" class="white-space-normal">'.$user['stock'].'</td>
               </tr>';
                
             }}else{
            $html .='<tr>
                <td colspan="8">No record found.</td>
              </tr>
              </tbody>
            </table>';
            }
            
            $arr = array('html' => $html,'record_count' => $data_list_count);
            
            return json_encode($arr);    
    }
    
    public function purchaseorder_ref_no_search(Request $request)
	{
	    //pr($request->all()); die;
	    if($request->purchaseorder_id==''){
	    $purchaseorder_ref_no = SearchDataMatch($request->purchaseorder_ref_no);
	    $count = DB::table('tbl_purchase_order')->where('purchaseorder_ref_no_search',$purchaseorder_ref_no)->count();
	    }else{
	    $purchaseorder_ref_no = SearchDataMatch($request->purchaseorder_ref_no);
	    $count = DB::table('tbl_purchase_order')->where('purchaseorder_ref_no_search',$purchaseorder_ref_no)->where('id','!=',$request->purchaseorder_id)->count();   
	    }
	    if($count>0){
	        return 1;
	    }else{
	        return 0;
	    }
	}
	
	public function item_search_data_po(Request $request)
	{
	  $itemlist = DB::table('items')->where('id', $request->id)->first();
       $oid = (array) $itemlist['id'];
	   $id = $oid['oid'];
	   
	   $customer_item = '';
	   $get_item = '';
	   $customer_brand = '';
	   $get_discount = '';
	   $sap_code = '';
	   // netrate by customer and item
	   if(!empty($request->customer_name) && !empty($request->id)){
            $colname = $request->id;
            $object = DB::table("tbl_sapcode")
            ->where('customer_id',$request->customer_name)
            ->where('product_id', 'like', '%'.$colname.'%')
            ->first();
            $sap_code =  $object['sap_code'];
	   }else{
	       $sap_code =  '';
	   }
	   
	   
	   if(!empty($request->customer_name) && !empty($request->id)){
	       //echo 'netrate by customer and item<br>';
		   $customer_item = get_netrate_by_customer_item($request->customer_name,$request->id);
		   if(!empty($customer_item)){
			    $net_rate = $customer_item;  
		   }		   
	   }
	   
	   //echo $customer_item; die;
	   
	   // netrate by item
	   if(empty($customer_item))
	   {
	       //echo 'netrate by item<br>';
		  $get_item  = get_netrate_by_item($request->id); 
		  //pr($get_item); die;
		  if(!empty($get_item)){
		     $net_rate = $get_item;  
		  }
	   }
	   
	   
	   
	   // Discount by customer and brand
	   if(empty($customer_item) && empty($get_item)){
	   if(!empty($request->customer_name) && !empty($itemlist['brand'])){
		  // echo 'Discount by customer and brand<br>';
	       //echo $itemlist['brand']; die;
	       $customer_brand = get_discount_by_customer_brand($request->customer_name,$itemlist['brand']);   
	       //pr($customer_brand); die;
	       if(!empty($customer_brand)){
			    $discount = $customer_brand;  
		   }
	   }
	   }
	   //echo $customer_brand; die;
	   
	   // Discount by brand
	   if(empty($customer_item) && empty($get_item)){
	  if(empty($customer_brand))
	   {
		   //echo 'Discount by brand<br>';
		  $get_discount  = get_discount_by_brand($itemlist['brand']); 
		  //pr($get_item); die;
		  if(!empty($get_discount)){
		     $discount = $get_discount;  
		  }
	   }
	   
	   }
	   
	   if(empty($customer_item) && empty($get_item)){
		   if(empty($get_item))
		   {
			   //echo 'List Price<br>';
			   $net_rate = $itemlist['list_price'];
				
		   }
	   }
	   
	   if(!empty($discount))
	   {
	       $discount_data = $discount;
	   }else{
	       $discount_data = 0;
	   }
	   //echo $net_rate;
	   $total_disc = ($net_rate * $discount_data)/100;
      $arr = json_encode(array('id'=>$id,'name'=>$itemlist['name'],'description'=>preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$itemlist['description']),'vendor_sku' => $itemlist['vendor_sku'],'hsn_code' => $itemlist['hsn_code'],
       'tax_rate' => $itemlist['tax_rate'],'grade' => $itemlist['grade'],'brand' => $itemlist['brand'],
       'packing_name' => $itemlist['packing_name'],'list_price' => $itemlist['list_price'],'mrp'=>$itemlist['mrp'],
       'stock' => $itemlist['stock'],'net_rate'=>$net_rate,'dis_per' => $discount_data,'total_disc' => $total_disc,'sap_code' => $sap_code));
       return $arr;  
	}

    public function ajaxVendorDetails($vendor_id)
	{
	  
	  $object = DB::table('vendors')->where('id',$vendor_id)->first();
      $data = json_decode(json_encode($object), true);
      

      $name = array();
	  $d = array();
      if($data['advance_required']!='')
      {
        $advance_required = $data['advance_required'];
      }
      else
      {
        $advance_required = ''; 
      }
      
      $object_dept = DB::table('tbl_department_name_data_vendor')->where('c_id',$vendor_id)->get()->toArray();
      $dept = json_decode(json_encode($object_dept), true);

	  
	  $category = $data['category'];
	  $object_categories = DB::table('customer_categories')->where('category',$category)->first();
      $customer_categories = json_decode(json_encode($object_categories), true);
      //pr($data); die;
	  if($customer_categories['delivery_day']!='')
	  {
	   $today = time();
	   $delivery_date = date('d-m-Y',strtotime('+'.$customer_categories['delivery_day'].' days', $today));
	  }else{
	   $delivery_date = date('d-m-Y');  
	  }
      if(!empty($dept)){
	  foreach($dept as $names){
	      $d['department'] = getDepartmentName($names['d_id']);
	      $d['name']=$names['name'];
	      $d['label']=$names['name'];
	      $d['mobile']=$names['mobile_no'];
	      $d['email']=$names['email'];
	      $d['delivery_date'] = $delivery_date;
          $d['advance_required'] = $advance_required;
	      array_push($name,$d);
	  }
    }else{
        $d['advance_required'] = $advance_required;
        $d['delivery_date'] = $delivery_date;
        array_push($name,$d);
    }
      return json_encode($name);
	}
	
	
}