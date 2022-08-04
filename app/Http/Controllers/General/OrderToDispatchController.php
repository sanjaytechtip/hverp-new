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

use Mail;
use App\Mail\NotifyMail;

error_reporting(0);

use App\User;

class OrderToDispatchController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		//pr($saleorderlist); die;
		if(empty($_POST))
		{
		$saleorderlist = DB::table('tbl_sale_order')->orderBy('id','DESC')->paginate(50);
		}else{
		    //pr($request->all());
        $query = DB::table('tbl_sale_order');
        if($request->saleorder_no!=''){
        $query->where('saleorder_no', 'LIKE','%'.$request->saleorder_no.'%');
        }
        if($request->customer_name!=''){
        $query->where('customer_name',$request->customer_name);  
        }
        $saleorderlist =$query->orderBy('id', 'DESC')->paginate(50);
		}
		return view('general.ordertodispatch.ordertodispatchlist',['saleorderlist'=>$saleorderlist,'request'=>$request]);
	 }
	 
	 public function email_send(Request $request)
	 {
		 //pr($request->all());die;
	     $to        = explode(',',$request->email_to);
	     $from      = $request->email_from; 
	     $cc        = $request->email_cc; 
	     $bcc       = $request->email_bcc; 
		 $fromName  = "H.V.TECHNOLOGIES.";
		 $subject   = $request->email_subject;
		 $message_email   = $request->email_body;
		 $id        = $request->id;
		 
		 
        $object_saleorder_details = DB::table('tbl_sale_order')->where('id',$id)->get()->first();
        $saleorder_details = json_decode(json_encode($object_saleorder_details), true);
        $object_saleorder_item_details = DB::table('tbl_sale_order_item')->where('sale_order_id',$id)->get()->toArray();
        $saleorder_item_details = json_decode(json_encode($object_saleorder_item_details), true);
        $object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        $object_company_details = DB::table('company_details')->first();
        $company_details = json_decode(json_encode($object_company_details), true);
        $object_other_charges_data = DB::table('tbl_so_shipping_charge')->where('sale_order_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
        $view = \View::make('general.saleorder.saleorderpdf',['saleorder_details' => $saleorder_details, 'saleorder_item_details' => $saleorder_item_details, 'other_charges'=>$other_charges, 'company_details' => $company_details,'other_charges_data'=>$other_charges_data]);
        $html_content = $view->render(); 
        PDF::SetTitle('Quotation');
        PDF::SetFont('helvetica', '', 8);
        PDF::AddPage('L');
        PDF::writeHTML($html_content, true, false, true, false, '');
        $filename = $saleorder_details['saleorder_no'].'.pdf';
        PDF::Output(public_path('/uploads/sale_order_pdf/').$filename, 'F');
        
        $data["from"] = $from;
        $data["from_name"] = $fromName;
        $data["email"] = $to;
        //$data["email"] = 'sanjay.techvoi@gmail.com';
        if($cc!='')
        {
        $data["cc"] = $cc;
        }
        if($bcc!='')
        {
        $data["bcc"] = $bcc;
        }
        $data["subject"] = $subject;
        $data["body"] = $message_email;
        
        $files = [
        public_path('/uploads/sale_order_pdf/'.$filename)
        ];
        $data1 = array('name'=>"vikash Mirdha");
        Mail::send(['emails.demoMail'], $data, function($message)use($data, $files) {
		$message->from($data["from"], $data["from_name"])->to($data["email"]);
        if($data["cc"]!='')
        {
        $message->cc($data["cc"]); 
        }
        if($data["bcc"]!='')
        {
        $message->bcc($data["bcc"]); 
        }
		//->cc($data["cc"])
		$message->subject($data["subject"]);
        foreach ($files as $file){
        $message->attach($file);
        } 
        });
		 
		 $todays_date = date('Y-m-d H:i:s');
		 DB::table('tbl_sale_order')->where('id',$id)->update(array('email_send_date'=>$todays_date));
		 return date('d-m-Y H:i A',strtotime($todays_date));
		 
	 }
	 
	 public function ajaxOrderToDispatchItems(Request $request)
	 {
	    if(!empty($request->quotation_id))
	    {
	    $html = "";
        if($request->rowCount_d_val==0)
        {
        $i=1;
        }else{
        $i = $request->rowCount_d_val+1;
        }
	    $quotation_details = DB::table('tbl_quotation')->where('_id',$request->quotation_id[0])->first();
	    //pr($request->all()); die;
	    foreach($request->quotation_id as $quotation_id){
	    $quotation = DB::table('tbl_quotation_item')->where('quotation_id',$quotation_id)->get()->toArray();
	    if(!empty($quotation)){
	    foreach($quotation as $key=>$de){
	        $html .='<tr class="pur-san" id="data-row-'.$i.'"><input type="hidden" name="quotation_id" value="'.$quotation_id.'"><td data-text="Sl"><span class="count-row"></span></td><td data-text="Sl"><input type="text" value="'.$de['cust_ref_no'].'" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td><td data-text="Item"><input type="text" readonly value="'.$de['item_name'].'" name="row['.$i.'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required /><input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$de['item_id'].'" /></td><td data-text="Vendor SKU"> <input type="text" readonly value="'.$de['vendor_sku'].'" name="row['.$i.'][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required /> </td><td data-text="SAP Code"> <input type="text" readonly name="row['.$i.'][sap_code]" value="'.$de['sap_code'].'" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" /></td><td data-text="HSN Code"> <input type="text" readonly value="'.$de['hsn_code'].'" name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'.$i.'" required /> </td><td data-text="Tax"> <input type="text" value="'.$de['tax_rate'].'" name="row['.$i.'][tax_rate]" cus="'.$i.'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required /> </td><td data-text="Packing Name"> <input type="text" readonly value="'.$de['packing_name'].'" name="row['.$i.'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> </td><td data-text="List Price"> <input type="text" readonly value="'.$de['list_price'].'" name="row['.$i.'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> </td><td data-text="Rate"> <input type="text" value="'.$de['rate'].'" name="row['.$i.'][rate]" cus="'.$i.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" /> </td><td data-text="Stock"> <input type="text" value="'.$de['stock'].'" readonly name="row['.$i.'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> </td><td data-text="MRP"> <input type="text" readonly value="'.$de['mrp'].'" name="row['.$i.'][mrp]" cus="'.$i.'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> </td><td data-text="Dis %"> <input type="number" value="'.$de['discount_per'].'" name="row['.$i.'][discount_per]" cus="'.$i.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" /> </td><td data-text="Discount"> <input type="number" readonly value="'.$de['discount'].'" name="row['.$i.'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" /> </td><td data-text="Quantity"> <input type="number" value="'.$de['quantity'].'" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" /> </td><td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right">'.$de['net_rate'].'</td><td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right">'.$de['tax_amount'].'</td><td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right">'.$de['amount'].'</td><input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" value="'.$de['net_rate'].'" name="row['.$i.'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" value="'.$de['tax_amount'].'" name="row['.$i.'][tax_amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" value="'.$de['amount'].'" name="row['.$i.'][amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
	        $i++;
	    }
	    }
	    }
	    $other_charges = "";
	    $sum=0;
	    if(!empty($quotation_details['other_charges_name']))
	    {
	        foreach($quotation_details['other_charges_name'] as $key=>$charges){
	            $other_charges .='<tr id="san-'.$key.'"><td>'.$charges.'</td><td class="ch-val">'.$quotation_details['other_charges_val'][$key].'</td><td><a href="javascript:void(0);" onclick="return removeOther('.$key.')" class="btn btn-danger waves-effect waves-classic"><i class="icon md-minus" style="margin:0;"></i></a></td><input type="hidden" name="other_charges_name[]" value="'.$charges.'"><input type="hidden" name="other_charges_val[]" value="'.$quotation_details['other_charges_val'][$key].'" ></tr>';
	            $sum = $sum+$quotation_details['other_charges_val'][$key];
	        }
	        
	            $other_charges .='<tr id="results-charges"><td><strong>Total</strong></td><td id="total-other">'.$sum.'</td><td></td></tr>';
	    }
	    $arr = array('html'=>$html,'quotation_saletax'=>$quotation_details['quotation_saletax'],'other_charges'=>$other_charges,'saleorder_contact_name'=>$quotation_details['quotation_contact_name'],'saleorder_contact_department'=>$quotation_details['quotation_department_name'],'saleorder_contact_phone'=>$quotation_details['quotation_contact_phone'],'saleorder_contact_email'=>$quotation_details['quotation_contact_email']);
	    return json_encode($arr);
	    }
	}
	 
	 public function item_display(Request $request)
	 {
         //pr($request->all()); die;
	     if($request->single_order_type==0){
	     $data_single_order = getSingleOrder($request->id);
         if(!empty($data_single_order)){
         if(getCustomerAddress($request->id)['is_dealer']==1){
	     $object = DB::table('tbl_sale_order_item')->where('customer_id',$request->id)->whereNotIn('sale_order_id',$data_single_order)->orderBy('item_id','ASC')->get();
         }else{
         $object = DB::table('tbl_sale_order_item')->where('sale_order_id',$request->sale_order_id)->orderBy('item_id','ASC')->get();   
         }
        //echo 1;
        }else{
         $object = DB::table('tbl_sale_order_item')->where('customer_id',$request->id)->orderBy('item_id','ASC')->get();      
        //echo 2;
        }
         $data = json_decode(json_encode($object), true);
	     //return 1; die;
	     }else{
	     //$data_sale_order = DB::table('tbl_sale_order')->where('_id',$request->sale_order_id)->first();
	     $object = DB::table('tbl_sale_order_item')->where('sale_order_id',$request->sale_order_id)->orderBy('item_id','ASC')->get();  
         $data = json_decode(json_encode($object), true); 
	     //return pr($data); die;
	     }
         //pr($data); die;
	     if(!empty($data))
	     {
	      $html = '';
	      $items = array();
	      foreach($data as $key=>$datalist)
	      {    
            $display = false;
            if(!in_array($datalist["item_id"], $items)) {
            $items[] = $datalist["item_id"];
            $display = true;
            $i=1;
            } 
            if($datalist['quantity']==getDispatchOrder($request->id,$datalist['item_id'],getSaleOrderNo($datalist['sale_order_id'])))
            {
            //$style = "style=display:none;"; 
            }else{
            $style = '';    
            }
            $html .='<tr '.$style.'><input type="hidden" class="single_order_type" value="'.$request->single_order_type.'"><input type="hidden" name="cus_id" value="'.$request->id.'"><input type="hidden" name="item_id['.$key.']" value="'.$datalist['item_id'].'"><input type="hidden" name="o_item_id['.$key.']" value="'.$datalist['id'].'">';
            $html .= $display ? '<td rowspan="'.getRowsItems($datalist["item_id"],$request->id,$request->single_order_type).'" style="text-align:center;" class="white-space-normal">'.getAllitemsById($datalist["item_id"]).'</td>' : "";
            $html .='<td style="text-align:center;" class="white-space-normal">'.$datalist['quantity'].'</td>
            <td style="text-align:center;" class="white-space-normal">'.getSaleOrderNo($datalist['sale_order_id']).'<input type="hidden" name="sale_order_no['.$key.']" value="'.getSaleOrderNo($datalist['sale_order_id']).'"></td>
            <td style="text-align:center;" class="white-space-normal">'.getDispatchOrder($request->id,$datalist['item_id'],getSaleOrderNo($datalist['sale_order_id'])).'</td>
            <td style="text-align:center;" class="white-space-normal balance-qty-'.$key.'">'.($datalist['quantity']-getDispatchOrder($request->id,$datalist['item_id'],getSaleOrderNo($datalist['sale_order_id']))).'</td>';
            $html .= $display ? '<td rowspan="'.getRowsItems($datalist["item_id"],$request->id,$request->single_order_type).'" style="text-align:center;" class="white-space-normal">'.getStockItem($datalist['item_id']).'</td>' : "";
            $html .= $display ? '<td rowspan="'.getRowsItems($datalist["item_id"],$request->id,$request->single_order_type).'" style="text-align:center;" class="white-space-normal">'.getOrderedQty($datalist['item_id']).'</td>' : "";
            if(($datalist['quantity']-getDispatchOrder($request->id,$datalist['item_id'],getSaleOrderNo($datalist['sale_order_id'])))==0 || getStockItem($datalist['item_id'])==0){$readonly = "disabled='disabled'";}else{$readonly = '';}
            $html .='<td style="text-align:center;" class="white-space-normal"><input min="1" '.$readonly.' class="dispatch_qty dispatch_qty-'.$key.'" id="'.$key.'" name="dispatch_qty['.$key.']" style="width:70px;" type="number"></td>
            </tr>'; 
            $i++;
	      }
	      return $html;
	     }
	 }

	 public function add_replacement_order(Request $request)
	 {
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$other_charges = json_decode(json_encode($object_other_charges), true);
        $object_SrNo = DB::table('tbl_config_sale_order')->first();
		$SrNo  = json_decode(json_encode($object_SrNo), true);
	  if(!$_POST)
	  {
	  return view('general.ordertodispatch.addreplacementorder',['datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo['row'],'itemlist_count'=>$itemlist_count]);   
	  }else{
	     
	     //pr($request->all()); die; 
	      
	      if($request->saleorder_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            
            if($request->sale_order_type=='replacement_order'){
                $type = 2;
            }else{
                $type = 3;
            }
            
                        $arr = array(
                        'saleorder_type'                =>  $request->saleorder_type,
                        'saleorder_srno'                =>  $request->saleorder_srno,
                        'saleorder_no'                  =>  $request->saleorder_no,
                        'saleorder_priority'            =>  $request->saleorder_priority,
                        'customer_name'                 =>  $request->customer_name,
                        'saleorder_date'                =>  date('Y-m-d',strtotime($request->saleorder_date)),
                        'saleorder_due_date'            =>  date('Y-m-d',strtotime($request->saleorder_due_date)),
                        'saleorder_ref_date'            =>  date('Y-m-d',strtotime($request->saleorder_ref_date)),
                        'saleorder_remarks'             =>  $request->saleorder_remarks,
                        'saleorder_ref_no'              =>  $request->saleorder_ref_no,
                        'saleorder_ref_no_search'       =>  SearchDataMatch($request->saleorder_ref_no),
                        'saleorder_status'              =>  $request->saleorder_status,
                        'saleorder_approved'            =>  $request->saleorder_approved,
                        'saleorder_contact_department'  =>  $request->saleorder_contact_department,
                        'saleorder_contact_name'        =>  $request->saleorder_contact_name,
                        'saleorder_contact_phone'       =>  $request->saleorder_contact_phone,
                        'saleorder_contact_email'       =>  $request->saleorder_contact_email,
                        'saleorder_subtotal'            =>  $request->saleorder_subtotal,
                        'saleorder_saletax'             =>  $request->saleorder_saletax,
                        'saleorder_tax_amount'          =>  $request->saleorder_tax_amount,
                        'saleorder_grand_total'         =>  $request->saleorder_grand_total,
                        'created_by'                    =>  Auth::user()->id,
                        'approved_by'                   =>  $approved_by,
                        'type'                          =>  $type,
                        'sale_order_type'               =>  $request->sale_order_type
                        );
                        $last_id = DB::table('tbl_sale_order')->insertGetId($arr);
						if(!empty($request->other_charges_val)){
						foreach($request->other_charges_val as $key=>$rows_charges)
						{
						$data_charges = array(
						'sale_order_id'          => $last_id,
						'other_charges_name'     => $request->other_charges_name[$key],
						'other_charges_val'      => $rows_charges
						);
						DB::table('tbl_so_shipping_charge')->insertGetId($data_charges);
						}
						}
                        DB::table('tbl_quotation')->where('id',$request->quotation_id)->update(array('SO_No'=>$request->saleorder_no,'SO_Date'=>$request->saleorder_date));
                        
                        if(!empty($request->row)){
                        foreach($request->row as $rows)
                        {
                        $data = array(
                        'cust_ref_no'   =>$rows['cust_ref_no'],
                        'item_name'     =>$rows['item_name'],
                        'item_id'       =>$rows['item_id'],
                        'note'          =>$rows['note'],
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
                        'net_rate'      =>$rows['net_rate'],
                        'tax_amount'    =>$rows['tax_amount'],
                        'amount'        =>$rows['amount'],
                        'customer_id'   =>$request->customer_name,
                        'sale_order_id' =>$last_id,
                        'oc_id'    		=>$request->challan_id
                        );
                        DB::table('tbl_sale_order_item')->insertGetId($data);
                        }			
                        }
                        DB::table('tbl_config_sale_order')->update(array('row'=>$request->saleorder_srno+1));
			           return redirect()->route('order_to_dispatch')->with('success', 'Replacement Order saved successfully.');
                        
                        
                        
	  }
	 }
	 
	 public function add_direct_order(Request $request)
	 {
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$other_charges = json_decode(json_encode($object_other_charges), true);
        $object_SrNo = DB::table('tbl_config_sale_order')->first();
		$SrNo  = json_decode(json_encode($object_SrNo), true);
	  if(!$_POST)
	  {
	  return view('general.ordertodispatch.adddirectorder',['datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo['row'],'itemlist_count'=>$itemlist_count]);   
	  }else{
	      if($request->saleorder_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            
            if($request->sale_order_type=='replacement_order'){
                $type = 2;
            }else{
                $type = 3;
            }
            
                        $arr = array(
                        'saleorder_type'                =>  $request->saleorder_type,
                        'saleorder_srno'                =>  $request->saleorder_srno,
                        'saleorder_no'                  =>  $request->saleorder_no,
                        'saleorder_priority'            =>  $request->saleorder_priority,
                        'customer_name'                 =>  $request->customer_name,
                        'saleorder_date'                =>  date('Y-m-d',strtotime($request->saleorder_date)),
                        'saleorder_due_date'            =>  date('Y-m-d',strtotime($request->saleorder_due_date)),
                        'saleorder_ref_date'            =>  date('Y-m-d',strtotime($request->saleorder_ref_date)),
                        'saleorder_remarks'             =>  $request->saleorder_remarks,
                        'saleorder_ref_no'              =>  $request->saleorder_ref_no,
                        'saleorder_ref_no_search'       =>  SearchDataMatch($request->saleorder_ref_no),
                        'saleorder_status'              =>  $request->saleorder_status,
                        'saleorder_approved'            =>  $request->saleorder_approved,
                        'saleorder_contact_department'  =>  $request->saleorder_contact_department,
                        'saleorder_contact_name'        =>  $request->saleorder_contact_name,
                        'saleorder_contact_phone'       =>  $request->saleorder_contact_phone,
                        'saleorder_contact_email'       =>  $request->saleorder_contact_email,
                        'saleorder_subtotal'            =>  $request->saleorder_subtotal,
                        'saleorder_saletax'             =>  $request->saleorder_saletax,
                        'saleorder_tax_amount'          =>  $request->saleorder_tax_amount,
                        'saleorder_grand_total'         =>  $request->saleorder_grand_total,
                        'created_by'                    =>  Auth::user()->id,
                        'approved_by'                   =>  $approved_by,
                        'type'                          =>  $type,
                        'sale_order_type'               =>  $request->sale_order_type
                        );
                        $last_id = DB::table('tbl_sale_order')->insertGetId($arr);
						if(!empty($request->other_charges_val)){
						foreach($request->other_charges_val as $key=>$rows_charges)
						{
						$data_charges = array(
						'sale_order_id'          => $last_id,
						'other_charges_name'     => $request->other_charges_name[$key],
						'other_charges_val'      => $rows_charges
						);
						DB::table('tbl_so_shipping_charge')->insertGetId($data_charges);
						}
						}
                        DB::table('tbl_quotation')->where('id',$request->quotation_id)->update(array('SO_No'=>$request->saleorder_no,'SO_Date'=>$request->saleorder_date));
                        if(!empty($request->row)){
                        foreach($request->row as $rows)
                        {
                        $data = array(
                        'cust_ref_no'   =>$rows['cust_ref_no'],
                        'item_name'     =>$rows['item_name'],
                        'item_id'       =>$rows['item_id'],
                        'note'          =>$rows['note'],
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
                        'net_rate'      =>$rows['net_rate'],
                        'tax_amount'    =>$rows['tax_amount'],
                        'amount'        =>$rows['amount'],
                        'customer_id'   =>$request->customer_name,
                        'sale_order_id' =>$last_id
                        );
                        DB::table('tbl_sale_order_item')->insertGetId($data);
                        }			
                        }
                        DB::table('tbl_config_sale_order')->update(array('row'=>$request->saleorder_srno+1));
			           return redirect()->route('order_to_dispatch')->with('success', 'Direct Order saved successfully.');
                        
                        
                        
	  }
	 }
	 
	 public function make_oc(Request $request)
	 {
	   //pr($request->all());  die;
	   if(!empty($request->row))
	   {
	       
	       $customer_id     = $request['customer_id'];
	       $dispatch_date   = date('Y-m-d H:i:s');
	       $object_oc       = DB::table('tbl_config_oc')->first();
           $result_oc       = json_decode(json_encode($object_oc), true);
	       $oc_sr_no   = $result_oc['row']+1;
	       
	        $oc_no  = "OC".str_pad($oc_sr_no,4,"0",STR_PAD_LEFT);
	        $oc_data = array('oc_no'=>$oc_no,'dispatch_date'=>$dispatch_date,'customer_id'=>$customer_id,'user_id'=>Auth::user()->id);
	       
            $last_id = DB::table('tbl_oc')->insertGetId($oc_data);
           
	       foreach(array_filter($request->row) as $key=>$qty_chk)
	       {
	          if(!empty($qty_chk['dispatch_qty']))
              {
                $dispatch_qty = getTotalDispatchQuantity($qty_chk['o_item_id']);
                $order_qty = getQuantityItem($qty_chk['o_item_id']);
                if($order_qty==$dispatch_qty+$qty_chk['dispatch_qty']){
                    $completed = 1;
                }else{
                    $completed = 0; 
                }
                DB::table('tbl_sale_order_item')->where('id',$qty_chk['o_item_id'])->update(array('completed'=>$completed));
                $data = array(
                    'oc_id'           => $last_id,
                    'customer_id'     => $customer_id,
                    'sale_order_no'   => $qty_chk['sale_order_no'],
                    'dispatch_qty'    => $qty_chk['dispatch_qty'],
                    'item_id'         => $qty_chk['item_id'],
                    'o_item_id'       => $qty_chk['o_item_id'],
                    'item_stock_id'   => $qty_chk['item_stock_id'],
                    'user_id'         => Auth::user()->id
                 );
                 DB::table('tbl_oc_item')->insertGetId($data);
                 balanceStockUpdate($qty_chk['item_id']);
              }
	       }
           
	       DB::table('tbl_config_oc')->update(array('row'=>$oc_sr_no));
	       echo $last_id;
	   }
	 }
	
	public function ajax_oc_search_data(Request $request)
	 {
	     if(!empty($request->oc))
	     {
	         $object = DB::table('tbl_oc')->where('oc_no',$request->oc)->first(); 
			 $query = json_decode(json_encode($object), true);
	         $oc_id = $query['id'];
	         $oclist = DB::table('tbl_oc_item')->where('oc_id', 'LIKE', "%{$oc_id}%")->paginate(50);
             $data_list_count =DB::table('tbl_oc_item')->where('oc_id', 'LIKE', "%{$oc_id}%")->count();
	         $html = "";
	         if(!empty($oclist)){
	         foreach($oclist as $list)
	         {
			 $list = (array)$list;
	          $html .='<tr>
                     <td><input type="checkbox" name="chk_search[]" class="chk_search" value="'.$list['id'].'"></td>
                     <td class="white-space-normal">'.getAllitemsById($list['item_id']).'</td>
                     <td align="center">'.$list['dispatch_qty'].'</td>
                     <td>'.date('d-m-Y h:i:s',strtotime($list['created_at'])).'</td>
                 </tr>';   
	         }
	         }
	         $arr = array('html'=>$html,'record_count'=>$data_list_count);
	         return json_encode($arr);
	     }
	 }
	 
	  public function ajaxOCItems(Request $request)
	 {
	     //pr($request->challan_id); die;
	      $html = "";
	      $i=1;
	     foreach($request->oc_id as $oc_no){
	         $object = DB::table('tbl_oc_item')->where('id',$oc_no)->first();
			 $oc_data = json_decode(json_encode($object), true);
	         $object_items = DB::table('items')->where('id',$oc_data['item_id'])->first();
			 $items = json_decode(json_encode($object_items), true);
	         if($items['rate']==''){$rate = 0;}
	         if($items['discount_per']==''){$disc = 0;}
	             $html .='<tr class="pur-san" id="data-row-'.$i.'"><input type="hidden" name="row['.$i.'][challan_id]" value="'.$oc_data['oc_id'].'"><td data-text="Sl" class="task_left_fix"><span class="count-row"></span></td><td data-text="Cust Ref No." class="task_left_fix"><input type="text" value="" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td><td data-text="Item" class="task_left_fix"><input type="text" readonly value="'.$items['name'].'" name="row['.$i.'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required /><input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$items['_id'].'" /></td><td data-text="Vendor SKU"> <input type="text" readonly value="'.$items['vendor_sku'].'" name="row['.$i.'][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required /> </td><td data-text="SAP Code"> <input type="text" readonly name="row['.$i.'][sap_code]" value="'.$items['sap_code'].'" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" /></td><td data-text="HSN Code"> <input type="text" readonly value="'.$items['hsn_code'].'" name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'.$i.'" required /> </td><td data-text="Tax"> <input type="text" value="'.$items['tax_rate'].'" name="row['.$i.'][tax_rate]" cus="'.$i.'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required /> </td><td data-text="Packing Name"> <input type="text" readonly value="'.$items['packing_name'].'" name="row['.$i.'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> </td><td data-text="List Price"> <input type="text" readonly value="'.$items['list_price'].'" name="row['.$i.'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> </td><td data-text="Rate"> <input type="text" value="'.$rate.'" name="row['.$i.'][rate]" cus="'.$i.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" /> </td><td data-text="Stock"> <input type="text" value="'.$items['stock'].'" readonly name="row['.$i.'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> </td><td data-text="MRP"> <input type="text" readonly value="'.$items['mrp'].'" name="row['.$i.'][mrp]" cus="'.$i.'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> </td><td data-text="Dis %"> <input type="number" value="'.$disc.'" name="row['.$i.'][discount_per]" cus="'.$i.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" /> </td><td data-text="Discount"> <input type="number" readonly value="'.$items['discount'].'" name="row['.$i.'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" /> </td><td data-text="Quantity"> <input type="number" value="'.$oc_data['dispatch_qty'].'" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" /> </td><td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right">'.$items['net_rate'].'</td><td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right">'.$items['tax_amount'].'</td><td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right"></td><input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" value="'.$items['net_rate'].'" name="row['.$i.'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" value="'.$items['tax_amount'].'" name="row['.$i.'][tax_amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" value="'.$items['amount'].'" name="row['.$i.'][amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
	             $i++;
	        
	         
	     }
	     
	         $arr = array('html'=>$html);
	         return json_encode($arr);
	     
	 }
	 
	 public function orderTomakeOC(Request $request, $id)
	 {
     $order_obj = DB::table('tbl_sale_order')->select('tbl_sale_order.*','customers.is_dealer','customers.company_name')->join('customers', 'customers.id', '=', 'tbl_sale_order.customer_name')->where('tbl_sale_order.id',$id)->get()->first();
	 $order_list_sl = json_decode(json_encode($order_obj), true);

     if($order_list_sl['single_order']!='' && $order_list_sl['single_order']=='yes'){
	 $order_saleorderlist = DB::table('tbl_sale_order')->where('id',$id)->orderBy('id','DESC')->get()->toArray();
     }else if($order_list_sl['is_dealer']==1){
     $order_saleorderlist = DB::table('tbl_sale_order')->where('customer_name',$order_list_sl['customer_name'])->orderBy('id','DESC')->get()->toArray();
     }else{
     $order_saleorderlist = DB::table('tbl_sale_order')->where('id',$id)->orderBy('id','DESC')->get()->toArray();  
     }
	 $saleorderlist = json_decode(json_encode($order_saleorderlist), true);
	 return view('general.ordertodispatch.ordertomakeoc',['saleorderlist'=>$saleorderlist,'customer_name'=>$order_list_sl['company_name'],'customer_id'=>$order_list_sl['customer_name']]);
	 }

     public function get_department(Request $request)
     {
        $object = DB::table('tbl_department_name')->select('tbl_department_name.department_name','tbl_department.department_name','tbl_department.id')->leftJoin('tbl_department', 'tbl_department_name.department_name', '=', 'tbl_department.id')->where('c_id',$request->id)->get()->toArray();
        $dept_data = json_decode(json_encode($object), true);
        $html = "";
        $html .="<option value=''>Select</option>"; 
        if(!empty($dept_data)){
            foreach($dept_data as $dept)
            {
                $html .="<option value='".$dept['id']."'>".$dept['department_name']."</option>"; 
            }
        }
        return $html;
     }

     public function get_staff(Request $request)
     {
        $object = DB::table('tbl_department_name')->select('id')->where('c_id',$request->c_id)->where('department_name',$request->designation)->first();
        $dept_data = json_decode(json_encode($object), true);
        $html = '';
        if($dept_data['id']!='')
        {
            $object_dept_d = DB::table('tbl_department_name_data')->select('id','name','email')->where('d_id',$dept_data['id'])->get()->toArray();
            $dept_data_d = json_decode(json_encode($object_dept_d), true); 
            foreach($dept_data_d as $dept_multi_name){
                $html .="<option value='".$dept_multi_name['id']."'>".$dept_multi_name['name']."</option>"; 
            }
        }
        return $html;
     }

     public function get_staff_email(Request $request)
     {
        $object_dept_d = DB::table('tbl_department_name_data')->select('email')->whereIn('id',$request->id)->get()->toArray();
        $dept_data = json_decode(json_encode($object_dept_d), true);
        $email_array = array();
        if(!empty($dept_data)){
        foreach($dept_data as $data){
              array_push($email_array,$data['email']);
        }
        }
        if(!empty($email_array))
        {
            return implode(',',$email_array);
        }else{
            return '';
        }
        
     }
}