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

class QuotationController extends Controller
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
		$quotationlist = DB::table('tbl_quotation')->orderBy('_id','DESC')->paginate(30);
		}else{
		    $query = DB::table('tbl_quotation');
            if($request->quotation_no!=''){
            $query->where('quotation_no', 'LIKE','%'.$request->quotation_no.'%');
            }
            if($request->quotation_priority!=''){
            $query->where('quotation_priority', 'LIKE','%'.$request->quotation_priority.'%');
            }
            if($request->quotation_date!=''){
            $query->where('quotation_date', 'LIKE','%'.date('Y-m-d',strtotime($request->quotation_date)).'%');
            }
            if($request->quotation_ref_no!=''){
            $query->where('quotation_ref_no', 'LIKE','%'.$request->quotation_ref_no.'%');
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
            if($request->quotation_status!=''){
            $query->where('quotation_status',$request->quotation_status);  
            }
            if($request->city!=''){
            $da = DB::table('customers')->select('_id')->where('h_city', 'LIKE','%'.$request->city.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
            array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('customer_name',$arr);
            }
            $quotationlist =$query->orderBy('_id', 'DESC')->paginate(30);
		}
		 //pr($quotationlist);die; 
		return view('general.quotation.quotationlist',['quotationlist'=>$quotationlist,'request'=>$request]);
	 }

	 /* for search test Start */
	public function search_test()
    {
		$crm_data = array(
								'crm_data'=>array(
														'c_id'=>"5eaab598d0490470cb15e6c2",
														'c_value'=>"Preeti Tiwari"
												)
								
							);
			//pr($crm_data); die;
		//echo $up = DB::table('pi_fms_bulk_data')->where('crm_data.c_id', '5eaab598d0490470cb15e6c2')->update($crm_data); die('===DONE==');
		//echo $up = DB::table('pi_fms_sample_data')->where('crm_data.c_id', '5eaab598d0490470cb15e6c2')->update($crm_data); die('===DONE==');
		//echo $up = DB::table('samplecards_fms_data')->where('crm_data.c_id', '5eaab598d0490470cb15e6c2')->update($crm_data); die('===DONE==');
        return view('general.purchaseInvoice.auto_search_test');
    }
 
    public function autocomplete(Request $request)
    {
          $search = $request->get('term');
      
			//$result = User::where('name', 'LIKE', '%'. $search. '%')->get();
			//$result = ArticleModel::where('article_no', 'LIKE', '%'. $search. '%')->get();
			/* $result = ArticleModel::chunk(1000, function($articles){
				
			}); */
			/* $data  = array();
			$result = DB::table('articles')->chunk(1000, function ($articles) {
							foreach ($articles as $article) {
								$dt = DB::table('article_no')->where('article_no', 'LIKE', '%'. $search. '%')->get();
								array_push($data,$dt);
							}
						}); */
			$result = DB::table('articles')->limit(10)->get();
			pr($result); die();
			//$result = array('hi'=>'kkk');
          return response()->json($result);
            
    } 
	 /* for search test END */
	 
	 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->count();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $SrNo = DB::table('tbl_config_item')->first();
        if(empty($_POST)){
		return view('general.quotation.quotationcreate',['datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo['row'],'itemlist_count'=>$itemlist_count]);
        }else{
            //pr($request->all()); die;
            
            if($request->quotation_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            
            $arr = array(
                            'quotation_type'                =>  $request->quotation_type,
                            'quotation_srno'                =>  $request->quotation_srno,
                            'quotation_no'                  =>  $request->quotation_no,
                            'quotation_priority'            =>  $request->quotation_priority,
                            'customer_name'                 =>  $request->customer_name,
                            'quotation_date'                =>  date('Y-m-d',strtotime($request->quotation_date)),
                            'quotation_due_date'            =>  date('Y-m-d',strtotime($request->quotation_due_date)),
                            'quotation_ref_date'            =>  date('Y-m-d',strtotime($request->quotation_ref_date)),
                            'quotation_remarks'             =>  $request->quotation_remarks,
                            'quotation_ref_no'              =>  $request->quotation_ref_no,
                            'quotation_ref_no_search'       =>  SearchDataMatch($request->quotation_ref_no),
                            'quotation_status'              =>  $request->quotation_status,
                            'quotation_approved'            =>  $request->quotation_approved,
                            'quotation_contact_name'        =>  $request->quotation_contact_name,
                            'quotation_department_name'     =>  $request->quotation_department_name,
                            'quotation_contact_phone'       =>  $request->quotation_contact_phone,
                            'quotation_contact_email'       =>  $request->quotation_contact_email,
                            'quotation_subtotal'            =>  $request->quotation_subtotal,
                            'quotation_saletax'             =>  $request->quotation_saletax,
                            'quotation_tax_amount'          =>  $request->quotation_tax_amount,
                            'quotation_grand_total'         =>  $request->quotation_grand_total,
                            'other_charges_val'             =>  $request->other_charges_val,
                            'other_charges_name'            =>  $request->other_charges_name,
                            'created_by'                    =>  Auth::user()->id,
                            'approved_by'                   =>  $approved_by,
                            'created_date'                  =>  date('Y-m-d H:i:s')
                            
                        );
            $last_id = DB::table('tbl_quotation')->insertGetId($arr);
            $oid = (array) $last_id;
            $last_id = $oid['oid'];
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'           =>$rows['cust_ref_no'],
                    'item_name'             =>$rows['item_name'],
                    'item_id'               =>$rows['item_id'],
                    'comment'               =>$rows['comment'],
                    'description'           =>$rows['description'],
                    'vendor_sku'            =>$rows['vendor_sku'],
                    'sap_code'              =>$rows['sap_code'],
                    'hsn_code'              =>$rows['hsn_code'],
                    'tax_rate'              =>$rows['tax_rate'],
                    'grade'                 =>$rows['grade'],
                    'brand'                 =>$rows['brand'],
                    'packing_name'          =>$rows['packing_name'],
                    'list_price'            =>$rows['list_price'],
                    'rate'                  =>$rows['rate'],
                    'stock'                 =>$rows['stock'],
                    'mrp'                   =>$rows['mrp'],
                    'discount_per'          =>$rows['discount_per'],
                    'discount'              =>$rows['discount'],
                    'quantity'              =>$rows['quantity'],
                    'net_rate'              =>$rows['net_rate'],
                    'tax_amount'            =>$rows['tax_amount'],
                    'amount'                =>$rows['amount'],
                    'customer_id'           =>$request->customer_name,
                    'quotation_id'          =>$last_id,
                    'sale_order_item_satus' => 0,
                    'created_date'          => date('Y-m-d H:i:s'),
                    );
            DB::table('tbl_quotation_item')->insertGetId($data);
            }			
            }
            DB::table('tbl_config_item')->update(array('row'=>$request->quotation_srno+1));
			
            return redirect()->route('quotation_list')->with('success', 'Quotation saved successfully.');
            
        }
    }
    
    public function item_search_data(Request $request)
    {
       $html = '';
       if($request->rowCount==1)
       {
       if($request->data_count==0)
       {
       $i=1;
       }else{
       $i = ($request->rowCount+1);   
       }
       }else if($request->rowCount>1){
       $i = ($request->rowCount+1);   
       }
       else
       {
       $i=1;   
       }
       foreach($request->id as $Ids){
       $itemlist = DB::table('items')->where('_id', $Ids)->first();
       $oid = (array) $itemlist['_id'];
	   $id = $oid['oid'];
	   
	   $customer_item = '';
	   $get_item = '';
	   $customer_brand = '';
	   $get_discount = '';
	   $sap_code = '';
	   // netrate by customer and item
	   if(!empty($request->customer_name) && !empty($Ids)){
            $colname = $request->id;
            $object = DB::table("tbl_sapcode")
            ->where('customer_id',$request->customer_name)
            ->where('product_id', 'like', '%'.$colname.'%')
            ->first();
            $sap_code =  $object['sap_code'];
	   }else{
	       $sap_code =  '';
	   }
	   
	   
	   if(!empty($request->customer_name) && !empty($Ids)){
	       //echo 'netrate by customer and item<br>';
		   $customer_item = get_netrate_by_customer_item($request->customer_name,$Ids);
		   if(!empty($customer_item)){
			    $net_rate = $customer_item;  
		   }		   
	   }
	   
	   //echo $customer_item; die;
	   
	   // netrate by item
	   if(empty($customer_item))
	   {
	       //echo 'netrate by item<br>';
		  $get_item  = get_netrate_by_item($Ids); 
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
      
       $html .='<tr id="data-row-'.$i.'">
                            <td id="'.$id.'" class="item-details" data-text="Sl">'.$i.'</td>
                            <td data-text="Sl"><input type="text" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td>
							<td data-text="Item">	
							<input type="text" readonly name="row['.$i.'][item_name]" value="'.$itemlist['name'].'-'.$itemlist['brand'].'" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required />
							<input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$id.'" />
                            </td>
                            <td data-text="Comment">						  
						    <input type="text" name="row['.$i.'][comment]" class="autocomplete-dynamic form-control" id="comment_rel_'.$i.'" />
                            </td>
                            <td data-text="Item Description">						  
						    <input type="text" name="row['.$i.'][description]" value="'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$itemlist['description']).'" class="autocomplete-dynamic form-control" id="description_rel_'.$i.'" />
                            </td>
                          <td data-text="Vendor SKU">						  
						<input type="text" readonly name="row['.$i.'][vendor_sku]" value="'.$itemlist['vendor_sku'].'" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required />
                            </td>
                            <td data-text="SAP Code">						  
						<input type="text" readonly name="row['.$i.'][sap_code]" value="'.$sap_code.'" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" />
                            </td>
                          <td data-text="HSN Code">
                              <input type="text" readonly name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" value="'.$itemlist['hsn_code'].'" id="hsn_code_rel_'.$i.'" required />
                              </td>
                          <td data-text="Tax">
                              <input type="text" name="row['.$i.'][tax_rate]" cus="'.$i.'" value="'.$itemlist['tax_rate'].'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required />
                              </td>
                          <td data-text="Packing Name">
                              <input type="text" readonly name="row['.$i.'][packing_name]" value="'.$itemlist['packing_name'].'" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> 
                            </td>
                          <td data-text="List Price">
                              <input type="text" readonly name="row['.$i.'][list_price]" value="'.$itemlist['list_price'].'" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> 
                            </td>
                            <td data-text="Rate">
                              <input type="text" name="row['.$i.'][rate]" cus="'.$i.'" value="'.$net_rate.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" />
                          </td>
                          <td data-text="Stock">
                              <input type="text" readonly name="row['.$i.'][stock]" value="'.$itemlist['stock'].'" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> 
                              </td>
                          <td data-text="MRP">
                            <input type="text" readonly name="row['.$i.'][mrp]" cus="'.$i.'" value="'.$itemlist['mrp'].'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> 
                            </td>
                             <td data-text="Dis %">
                              <input type="number" name="row['.$i.'][discount_per]" cus="'.$i.'" value="'.$discount_data.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" />
                          </td>
                          <td data-text="Discount">
                              <input type="number" readonly name="row['.$i.'][discount]" value="'.$total_disc.'" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" />
                          </td>
                          <td data-text="Quantity">
                              <input type="number" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" />
                              </td>
                              <td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right"></td>
                              <td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right"></td>
                          <td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right"></td>
                          <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" name="row['.$i.'][net_rate]" />
                          <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" name="row['.$i.'][tax_amount]" />
                          <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" name="row['.$i.'][amount]" />
                          <td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                        </tr>';
        $i++;}
       
       return $html;
    }
    
    public function item_search_data_quotation(Request $request)
    {
        $itemlist = DB::table('tbl_quotation_item')->where('customer_id', $request->customer_name)->where('item_id', $request->id)->orderBy('_id','desc')->first();
        $list = DB::table('tbl_quotation')->where('_id', $itemlist['quotation_id'])->orderBy('_id','desc')->first();
        if($list['created_date']==''){
            $created_date = '';
        }else{
           $created_date = date('d-m-Y H:i:s',strtotime($list['created_date'])); 
        }
        $arr = json_encode(array('quotation_ref_no' => $list['quotation_ref_no'],'SrNo' => $list['quotation_srno'],'created_date' => $created_date,'rate'=>$itemlist['rate']));
        return $arr;
    }
	
	public function create_old()
    {
	    $items = ArticleModel::get();
		$customer = RegisterForm::get();
		$p = PurchaseInvoice::max('order_number');
		if($p==''){
		$po_number = 'PO-0001';
		}else{
		$p1 = $p+1;
		if($p1<=9){
		$d = '000'.$p1;
		}else if($p1<=99){
		$d = '00'.$p1;
		}else if($p1<=999){
		$d = '0'.$p1;
		}else if($p1<=9999){
		$d = $p1;
		}
		$po_number = 'PO-'.$d;
		}
		
		return view('general.purchaseInvoice.purchaseinvoicecreate_old',['po_number' => $po_number,'items' => $items,'customer' => $customer]);
    }

	
    public function store(Request $request)
    {
		include(app_path() . '/googlesheet/index.php');
		checkPermission('add_pi');
		$this->validate($request, [
			'customer_name'  => 'required',
			'buyer_name' => 'required',
		]);
		
		$data_inv = DB::table('inv_config')->first();
		$p = $data_inv['last_inv'];
		if($p==''){
		$po_number = '0001';
		}else{
		$po_number = $p+1;
		}
		
		$financial_year = get_financial_year();

		$data  = array(
		"po_serial_number"			=> str_pad($po_number,4,"0", STR_PAD_LEFT),
		"customer_name" 			=> $request->customer_name,
		"customer_address"     		=> $request->customer_address,
		"customer_city"         	=> $request->customer_city,
		"customer_country"         	=> $request->customer_country,
		"customer_state"         	=> $request->customer_state,
		"customer_contact_person"   => $request->customer_contact_person,
		"customer_phone"   			=> $request->customer_phone,
		"order_sampling_type"		=> $request->order_sampling_type,
		"buyer_name"   				=> $request->buyer_name,
		"buyer_address"   			=> $request->buyer_address,
		"buyer_city"   				=> $request->buyer_city,
		"buyer_country"   			=> $request->buyer_country,
		"buyer_state"   			=> $request->buyer_state,
		"buyer_contact_person"   	=> $request->buyer_contact_person,
		"buyer_phone"   			=> $request->buyer_phone,
		"gst_no"   					=> $request->gst_no,
		"buyer_gst_no"   			=> $request->buyer_gst_no,
		"sales_agent"   => $request->sales_agent,
		"brand_name"   				=> $request->brand_name,
		"order_type"   				=> $request->order_type,
		"fob_sample_approval"   	=> $request->fob_sample_approval,
		"fpt_testing_approval"   	=> $request->fpt_testing_approval,
		"payment_term"   			=> $request->payment_term,
		"quantity_variation"   		=> $request->quantity_variation,
		"buyer_po_number"   		=> $request->buyer_po_number,
		"status"   					=> $request->status,
		"mode_of_transport"   		=> $request->mode_of_transport,
		"company_of_transport"   	=> $request->company_of_transport,
		"crm_enquiry_no"   			=> $request->crm_enquiry_no,
		"crm_assign"   				=> $request->crm_assign,
		"merchant_name"   			=> $request->merchant_name,
		//"row"   					=> $request->row,
		"subtotal"   				=> $request->subtotal,
		"sampling_charge"   		=> $request->sampling_charge,
		"delivery_charge"   		=> $request->delivery_charge,
		"gst"   					=> $request->gst,
		"total"   					=> $request->total,
		"proforma_invoice_date"    => $request->proforma_invoice_date.' '.date('H:i:s'),
		"approve"   				=> $request->approve,
		'financial_year' => $financial_year,
		"created_at"				=> date('Y-m-d H:i:s')
		); 
		//pr($data);die;
		$last_id = DB::table('tbl_purchase_invoice')->insertGetId($data);
		$oid = (array) $last_id;
		$last_id = $oid['oid'];
		
		$count = count($request->row);
		for($i=1;$i<=$count;$i++){
		$data = array("performa_invoice_id"=>$last_id,"item"=>$request->row[$i]['item'],"colour"=>$request->row[$i]['colour'],"description"=>$request->row[$i]['description'],"remark"=>$request->row[$i]['remark'],"bulkqty"=>$request->row[$i]['bulkqty'],"ssqty"=>$request->row[$i]['ssqty'],"unit"=>$request->row[$i]['unit'],"lab_dip"=>$request->row[$i]['lab_dip'],"etd"=>$request->row[$i]['etd'],"hsn_code"=>$request->row[$i]['hsn_code'],"unit_price"=>$request->row[$i]['unit_price'],"gst"=>$request->row[$i]['gst'],"amount"=>$request->row[$i]['amount']);
		DB::table('tbl_purchase_invoice_item_data')->insert(array($data));
		}
		DB::table('inv_config')->update(array('last_inv'=>$po_number,'last_yr'=>date('Y')));	
		
		
		$purchase_data = PurchaseInvoice::find($last_id);
		//pr($purchase_data); die;
		$invoice_num = 'PI-POS-'.$purchase_data->po_serial_number.'-R1';
		$view = \View::make('pi_inv_pdf',['purchase_data' => $purchase_data, 'purchase_data_row' => $request->row, 'invoice_num'=>$invoice_num]);
		$html_content = $view->render();
		PDF::SetTitle('Quotation '.$purchase_data->po_serial_number);
		PDF::SetMargins(5, 5);
		PDF::SetFont('helvetica', '', 10);
		PDF::SetHeaderMargin(0);
		PDF::SetAutoPageBreak(TRUE);
		PDF::SetPrintHeader(TRUE);
		PDF::AddPage('P', 'A4');
		PDF::writeHTML($html_content, true, false, true, false, '');
		PDF::lastPage();
		//$filename = date('Ymdhis').'.pdf';
		//  PI-POS-202000020-R6
		
		/* $filename = 'PI-POS-'.date('Y').'-'.$purchase_data->po_serial_number.'-R1.pdf';
		@mkdir(public_path('/pi_invoice/'.$purchase_data->po_serial_number));
		PDF::Output(public_path('/pi_invoice/'.$purchase_data->po_serial_number.'/').$filename, 'F'); */
		
		$filename = 'PI-POS-'.$financial_year.'-'.$purchase_data->po_serial_number.'.pdf';
		
		@mkdir(public_path('/pi_invoice/'.$financial_year.'/'));
		@mkdir(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number));
		PDF::Output(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number.'/').$filename, 'F');
		
		/* Start Add PI Data into google sheet */
		
			$customer_address = $request->customer_address!=""?$request->customer_address:"";
			$customer_city = $request->customer_city!=""?$request->customer_city:"";
			$customer_state = $request->customer_state!=""?$request->customer_state:"";
			$customer_country = $request->customer_country!=""?$request->customer_country:"";
			$customer_contact_person = $request->customer_contact_person!=""?$request->customer_contact_person:"";
			$gst_no = $request->gst_no!=''?$request->gst_no:'';
			$customer_phone = $request->customer_phone!=''?$request->customer_phone:'';
			
			//buyer 
			$buyer_address = $request->buyer_address!=''?$request->buyer_address:'';
			$buyer_country = $request->buyer_country!=''?$request->buyer_country:'';
			$buyer_city = $request->buyer_city!=''?$request->buyer_city:'';
			$buyer_state = $request->buyer_state!=''?$request->buyer_state:'';
			$buyer_contact_person = $request->buyer_contact_person!=''?$request->buyer_contact_person:'';
			$buyer_gst_no = $request->buyer_gst_no!=''?$request->buyer_gst_no:'';
			$buyer_phone = $request->buyer_phone!=''?$request->buyer_phone:'';
			
			
			$order_type = $request->order_type!=''?$request->order_type:'';
			$order_sampling_type = $request->order_sampling_type!=''?$request->order_sampling_type:'';
			$fob_sample_approval = $request->fob_sample_approval!=''?$request->fob_sample_approval:'';
			$fpt_testing_approval = $request->fpt_testing_approval!=''?$request->fpt_testing_approval:'';
			$payment_term = $request->payment_term!=''?$request->payment_term:'';
			$quantity_variation = $request->quantity_variation!=''?$request->quantity_variation:'';
			$buyer_po_number = $request->buyer_po_number!=''?$request->buyer_po_number:'';
			$status = $request->status!=''?$request->status:'';
			$mode_of_transport = $request->mode_of_transport!=''?$request->mode_of_transport:'';
			$company_of_transport = $request->company_of_transport!=''?$request->company_of_transport:'';
			$crm_enquiry_no = $request->crm_enquiry_no!=''?$request->crm_enquiry_no:'';
			$proforma_invoice_date = $request->proforma_invoice_date!=''?$request->proforma_invoice_date:'';
			
			dataspredsheet([[
				GetBuyerName($request->customer_name), 
				$customer_address, 
				$customer_country, 
				$customer_state, 
				$customer_state, 
				$customer_contact_person, 
				$gst_no, 
				$customer_phone,
				GetBuyerName($request->buyer_name),
				$buyer_address,
				$buyer_country,
				$buyer_city,
				$buyer_state,
				$buyer_contact_person,
				$buyer_gst_no,
				$buyer_phone,
				getAgentDetails($request->sales_agent),
				GetBrandName($request->brand_name),
				$order_type,
				$order_sampling_type,
				$fob_sample_approval,
				$fpt_testing_approval,
				$payment_term,
				$quantity_variation,
				$buyer_po_number,
				$status,
				$mode_of_transport,
				$company_of_transport,
				$crm_enquiry_no,
				$proforma_invoice_date
			]],"1rqyIlt2xHn2JbFJG86xzO5etM3SiKcXRb_SfOR-SsBU","A1!A:AD");
			
		/* END Add PI Data into google sheet */
		
		if($last_id)
		{
			$pi_version['pi_version'] = array(
												'r1'=>$filename
											);
			DB::table('tbl_purchase_invoice')->where('_id', $last_id)->update($pi_version);
			
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice added successfully.');
		}else{
		return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice not stored.');
		}
		
    }
	
	public function store_part_shipped(Request $request, $id)
    {
		include(app_path() . '/googlesheet/index.php');
		checkPermission('add_pi');
		$this->validate($request, [
			'customer_name'  => 'required',
			'buyer_name' => 'required',
		]);
		
		
		$p_invoice = DB::table('tbl_purchase_invoice')->where('_id',$id)->first();
		
		if(array_key_exists('partshipped_done', $p_invoice)){
			$p_no = $p_invoice['partshipped_done']+1;
		}else{
			$p_no=1;
		}
		
		$financial_year = get_financial_year();
		$data  = array(
		"po_serial_number"			=> str_pad($request->po_serial_number,4,"0", STR_PAD_LEFT),
		"customer_name" 			=> $request->customer_name,
		"customer_address"     		=> $request->customer_address,
		"customer_country"         	=> $request->customer_country,
		"customer_city"         	=> $request->customer_city,
		"customer_state"         	=> $request->customer_state,
		"customer_contact_person"   => $request->customer_contact_person,
		"customer_phone"   			=> $request->customer_phone,
		"order_sampling_type"		=> $request->order_sampling_type,
		"buyer_name"   				=> $request->buyer_name,
		"buyer_address"   			=> $request->buyer_address,
		"buyer_country"         	=> $request->buyer_country,
		"buyer_city"   				=> $request->buyer_city,
		"buyer_state"   			=> $request->buyer_state,
		"buyer_contact_person"   	=> $request->buyer_contact_person,
		"buyer_phone"   			=> $request->buyer_phone,
		"gst_no"   					=> $request->gst_no,
		"buyer_gst_no"   			=> $request->buyer_gst_no,
		"sales_agent"   => $request->sales_agent,
		"brand_name"   				=> $request->brand_name,
		"order_type"   				=> $request->order_type,
		"fob_sample_approval"   	=> $request->fob_sample_approval,
		"fpt_testing_approval"   	=> $request->fpt_testing_approval,
		"payment_term"   			=> $request->payment_term,
		"quantity_variation"   		=> $request->quantity_variation,
		"buyer_po_number"   		=> $request->buyer_po_number,
		"status"   					=> $request->status,
		"mode_of_transport"   		=> $request->mode_of_transport,
		"company_of_transport"   	=> $request->company_of_transport,
		"crm_enquiry_no"   			=> $request->crm_enquiry_no,
		"crm_assign"   				=> $request->crm_assign,
		"merchant_name"   			=> $request->merchant_name,
		//"row"   					=> $request->row,
		"subtotal"   				=> $request->subtotal,
		"sampling_charge"   		=> $request->sampling_charge,
		"delivery_charge"   		=> $request->delivery_charge,
		"gst"   					=> $request->gst,
		"total"   					=> $request->total,
		"proforma_invoice_date"    => $request->proforma_invoice_date.' '.date('H:i:s'),
		"approve"   				=> $request->approve,
		'financial_year' => $financial_year,
		'partshipped_parent' => $id,
		'partshipped_no' => $p_no,
		"created_at"				=> date('Y-m-d H:i:s')
		); 

		$last_id = DB::table('tbl_purchase_invoice')->insertGetId($data);
		$oid = (array) $last_id;
		$last_id = $oid['oid'];
		
		// Update parent pi for part shipped			
		DB::table('tbl_purchase_invoice')->where('_id',$id)->update(array('partshipped_done'=>$p_no));
		
		$count = count($request->row);
		for($i=1;$i<=$count;$i++){
			$data = array(
							"performa_invoice_id"=>$last_id,
							"item"=>$request->row[$i]['item'],
							"colour"=>$request->row[$i]['colour'],
							"description"=>$request->row[$i]['description'],
							"remark"=>$request->row[$i]['remark'],
							"bulkqty"=>$request->row[$i]['bulkqty'],
							"ssqty"=>$request->row[$i]['ssqty'],
							"unit"=>$request->row[$i]['unit'],
							"lab_dip"=>$request->row[$i]['lab_dip'],
							"etd"=>$request->row[$i]['etd'],
							"hsn_code"=>$request->row[$i]['hsn_code'],
							"unit_price"=>$request->row[$i]['unit_price'],
							"gst"=>$request->row[$i]['gst'],
							"amount"=>$request->row[$i]['amount']
						);
			DB::table('tbl_purchase_invoice_item_data')->insert(array($data));
		}
		
		$purchase_data = PurchaseInvoice::find($last_id);
		//pr($purchase_data); die;
		$part_no = '-P'.$p_no;
		$view = \View::make('pi_partshipped_inv_pdf',['purchase_data' => $purchase_data, 'purchase_data_row' => $request->row, 'partshipped_no' => $part_no]);
		//echo $view; die;
		$html_content = $view->render();
		PDF::SetTitle('Proforma Part Shipped Invoice '.$purchase_data->po_serial_number);
		PDF::SetMargins(5, 5);
		PDF::SetHeaderMargin(0);
		PDF::SetAutoPageBreak(TRUE);
		PDF::SetPrintHeader(TRUE);
		PDF::AddPage('P', 'A4');
		PDF::writeHTML($html_content, true, false, true, false, '');
		PDF::lastPage();
		
		
		$filename = 'PI-POS-'.$financial_year.'-'.$purchase_data->po_serial_number.'-P'.$p_no.'.pdf';
		
		@mkdir(public_path('/pi_invoice/'.$financial_year.'/'));
		@mkdir(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number.'-P'.$p_no));
		PDF::Output(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number.'-P'.$p_no.'/').$filename, 'F');
		
		
		if($last_id)
		{
			$pi_version['pi_version'] = array(
												'r1'=>$filename
											);
			DB::table('tbl_purchase_invoice')->where('_id', $last_id)->update($pi_version);
			
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice Part Shipped created successfully.');
		}else{
		return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice not Part Shipped.');
		}	
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $edit_purchase_invoice = DB::table('tbl_purchase_invoice')->where('_id',$id)->first();
		//$items = ArticleModel::get();
		$items = ArticleModel::limit(100)->get();
		$customer = RegisterForm::get();
		
		return view('general.purchaseInvoice.purchaseinvoiceshow',['items' => $items,'customer' => $customer,'edit_purchase_invoice' => $edit_purchase_invoice]);
    }
	
	public function view($id)
	{
	    $quotation_details = DB::table('tbl_quotation')->where('_id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$quotation_details_revised = DB::table('tbl_quotation_revised')->select('_id','revised_date')->where('revised_id',$id)->orderBy('revised_date','ASC')->get()->toArray();
		//pr($quotation_details_revised); die;
		return view('general.quotation.quotationview',['quotation_details_revised'=>$quotation_details_revised,'quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges]);
		
	}
	
	public function quotation_revised($id,$revised_id)
	{
        $quotation_details = DB::table('tbl_quotation_revised')->where('_id',$id)->where('revised_id',$revised_id)->get()->first();
        $quotation_item_details = DB::table('tbl_quotation_item_revised')->where('quotation_id',$id)->where('revised_id',$revised_id)->get()->toArray();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray(); 
        return view('general.quotation.quotationrevised',['quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges]);
	}
	
	public function quotation_print($id)
	{
	    $quotation_details = DB::table('tbl_quotation')->where('_id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$company_details = DB::table('company_details')->first();
		//pr($quotation_details); die;
		return view('general.quotation.quotationprint',['quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'company_details'=>$company_details]);
	}
	
	public function create_part_shipped($id)
    {
		checkPermission('edit_pi');
        $edit_purchase_invoice = DB::table('tbl_purchase_invoice')->where('_id',$id)->first();
		//$items = ArticleModel::get();
		$items = ArticleModel::select('_id', 'article_no')->get();
		//$customer = RegisterForm::where('status', "=", 1)->get();
		$customer =array();
		return view('general.purchaseInvoice.create_part_shipped',['items' => $items,'customer' => $customer,'edit_purchase_invoice' => $edit_purchase_invoice]);
    }
	
	public function searchInvoice(Request $request){
		$post = $request->all();
		//pr($post); die;
		$search	   = $request->invoice;
		$search_by = $request->invoice_code;
		
		if($request->has('invoice_code')){
			
			if($search=='company_name'){
				//echo $search_by; die;
				$data = DB::table('register_form')->select('id','company_name')->where('company_name','LIKE', '%'.$search_by.'%')->get()->toArray();
				 //pr($data); die;
				$idsArr = array();
				foreach($data as $key=>$val){
					
					$oid = $val['_id'];
					$id = (array) $oid;
					$id = $id['oid'];
					array_push($idsArr,$id);
				}
				/* pr($idsArr);
				pr($data); die('==='); */
				$purchaseinvoicelist = DB::table('tbl_purchase_invoice')->whereIn('customer_name',$idsArr)->paginate(1000); 
				//pr($purchaseinvoicelist); die('==='); 
				
			}else{
				$purchaseinvoicelist = DB::table('tbl_purchase_invoice')->where($request->invoice,'LIKE', '%' .$request->invoice_code. '%')->paginate(20); 
			}
			
    	}else{
    		$purchaseinvoicelist = DB::table('tbl_purchase_invoice')->paginate(20);
			$purchaseinvoicelist['search']	= $request->invoice;
			$purchaseinvoicelist['search_by'] = $request->invoice_code;
    	}
		return view('general.purchaseInvoice.purchaseinvoicelist', compact('purchaseinvoicelist'))->with('search', $search)->with('search_by', $search_by);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		$quotation_details = DB::table('tbl_quotation')->where('_id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->count();
		//pr($quotation_item_details); die;
		if(empty($_POST)){
		return view('general.quotation.quotationedit',['datalist'=>$itemlist,'quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'itemlist_count'=>$itemlist_count]);
		}else{
		    
            //pr($request->all()); die;
        $quotation_details = DB::table('tbl_quotation')->where('_id',$id)->get()->first();
        $quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        
        unset($quotation_details['_id']);
        $quotation_details['revised_id'] = $id;
        $quotation_details['revised_date'] = date('Y-m-d H:i:s');
        $last_id = DB::table('tbl_quotation_revised')->insertGetId($quotation_details);
        $oid = (array) $last_id;
        $last_id = $oid['oid'];
        foreach($quotation_item_details as $details_item){
        unset($details_item['_id']);
        unset($details_item['quotation_id']);
        $details_item['revised_id'] = $id;
        $details_item['quotation_id'] = $last_id;
        $details_item['revised_date'] = date('Y-m-d H:i:s');
        DB::table('tbl_quotation_item_revised')->insert(array($details_item));
        }
        //pr($quotation_details); die;
            if($request->quotation_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            $arr = array(
                            'quotation_type'                =>  $request->quotation_type,
                            'quotation_srno'                =>  $request->quotation_srno,
                            'quotation_no'                  =>  $request->quotation_no,
                            'quotation_priority'            =>  $request->quotation_priority,
                            'customer_name'                 =>  $request->customer_name,
                            'quotation_date'                =>  date('Y-m-d',strtotime($request->quotation_date)),
                            'quotation_due_date'            =>  date('Y-m-d',strtotime($request->quotation_due_date)),
                            'quotation_ref_date'            =>  date('Y-m-d',strtotime($request->quotation_ref_date)),
                            'quotation_remarks'             =>  $request->quotation_remarks,
                            'quotation_ref_no'              =>  $request->quotation_ref_no,
                            'quotation_ref_no_search'       =>  SearchDataMatch($request->quotation_ref_no),
                            'quotation_status'              =>  $request->quotation_status,
                            'quotation_approved'            =>  $request->quotation_approved,
                            'quotation_contact_name'        =>  $request->quotation_contact_name,
                            'quotation_department_name'     =>  $request->quotation_department_name,
                            'quotation_contact_phone'       =>  $request->quotation_contact_phone,
                            'quotation_contact_email'       =>  $request->quotation_contact_email,
                            'quotation_subtotal'            =>  $request->quotation_subtotal,
                            'quotation_saletax'             =>  $request->quotation_saletax,
                            'quotation_tax_amount'          =>  $request->quotation_tax_amount,
                            'quotation_grand_total'         =>  $request->quotation_grand_total,
                            'other_charges_val'             =>  $request->other_charges_val,
                            'other_charges_name'            =>  $request->other_charges_name,
                            'created_by'                    =>  Auth::user()->id,
                            'approved_by'                   =>  $approved_by
                            
                        );
            $last_id = DB::table('tbl_quotation')->where('_id',$id)->update($arr);
            DB::table('tbl_quotation_item')->where('quotation_id',$id)->delete();
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'   =>$rows['cust_ref_no'],
                    'item_name'     =>$rows['item_name'],
                    'item_id'       =>$rows['item_id'],
                    'comment'=>$rows['comment'],
                    'description'=>$rows['description'],
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
                    'quotation_id'  =>$id,
                    'created_date'  => date('Y-m-d H:i:s'),
                    );
                    //pr($data); die;
            DB::table('tbl_quotation_item')->insertGetId($data);
            }			
            }
			
            return redirect()->route('quotation_list')->with('success', 'Quotation Updated successfully.');
            
        
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		
		checkPermission('edit_pi');
		$this->validate($request, [
			'customer_name'  => 'required',
			'buyer_name' => 'required',
		]);
		//pr($_POST); die;
		$financial_year = $request->financial_year;
		$data  = array(
		"customer_name" 			=> $request->customer_name,
		"customer_address"     		=> $request->customer_address,
		"customer_country"         	=> $request->customer_country,
		"customer_city"         	=> $request->customer_city,
		"customer_state"         	=> $request->customer_state,
		"customer_contact_person"   => $request->customer_contact_person,
		"order_sampling_type"		=> $request->order_sampling_type,
		"customer_phone"   			=> $request->customer_phone,
		"buyer_name"   				=> $request->buyer_name,
		"buyer_address"   			=> $request->buyer_address,
		"buyer_country"   			=> $request->buyer_country,
		"buyer_city"   				=> $request->buyer_city,
		"buyer_state"   			=> $request->buyer_state,
		"buyer_contact_person"   	=> $request->buyer_contact_person,
		"buyer_phone"   			=> $request->buyer_phone,
		"gst_no"   					=> $request->gst_no,
		"buyer_gst_no"   			=> $request->buyer_gst_no,
		"sales_agent"   => $request->sales_agent,
		"brand_name"   				=> $request->brand_name,
		"order_type"   				=> $request->order_type,
		"fob_sample_approval"   	=> $request->fob_sample_approval,
		"fpt_testing_approval"   	=> $request->fpt_testing_approval,
		"payment_term"   			=> $request->payment_term,
		"quantity_variation"   		=> $request->quantity_variation,
		"buyer_po_number"   		=> $request->buyer_po_number,
		"status"   					=> $request->status,
		"mode_of_transport"   		=> $request->mode_of_transport,
		"company_of_transport"   	=> $request->company_of_transport,
		"crm_enquiry_no"   			=> $request->crm_enquiry_no,
		"crm_assign"   				=> $request->crm_assign,
		"merchant_name"   			=> $request->merchant_name,
		//"row"   					=> $request->row,
		"subtotal"   				=> $request->subtotal,
		"sampling_charge"   		=> $request->sampling_charge,
		"delivery_charge"   		=> $request->delivery_charge,
		"total"   					=> $request->total,
		"gst"   					=> $request->gst,
		"proforma_invoice_date"    => $request->proforma_invoice_date.' '.date('H:i:s'),
		"approve"   				=> $request->approve,
		); 
		$purchase_invoice_update = DB::table('tbl_purchase_invoice')->where('_id',$id)->update($data);	
		
		$count = count($request->row);
		
		//$purchase_data = PurchaseInvoice::find($id);
		
		$inv_data = DB::table('tbl_purchase_invoice')->where('_id',$id)->get()->toArray();
		if(array_key_exists('data_status', $inv_data[0]) && $inv_data[0]['data_status']==1){
			$data_status=1;
		}else{
			$data_status=0;
		}

		if($data_status==1){
			/* new code start  */
			$order_type = $inv_data[0]['order_type'];
			$fms_data =array();
			$fms_data['invoice_no']= $inv_data[0]['po_serial_number'];
			
			$buyer_data = array(
										'b_id'=>$inv_data[0]['buyer_name'],
										'b_value'=>GetBuyerName($inv_data[0]['buyer_name'])
								);
			
			$customer_data = array(
										'c_id'=>$inv_data[0]['customer_name'],
										'c_value'=>GetBuyerName($inv_data[0]['customer_name'])
								);
				
			$s_person_data = array(
									's_id'=>$inv_data[0]['sales_agent'],
									's_value'=>getAgentMerchant($inv_data[0]['sales_agent'])
								);
								
			$brand_data = array(
									'b_id'=>$inv_data[0]['brand_name'],
									'b_value'=>GetBrandName($inv_data[0]['brand_name'])
								);
			
			
			$fms_data['pi_date']= $inv_data[0]['proforma_invoice_date'];
			$fms_data['buyer_data'] = $buyer_data;
			$fms_data['customer_data'] = $customer_data;
			$fms_data['s_person_data'] = $s_person_data;
			$fms_data['brand_data'] = $brand_data;
			$fms_data['pi_status'] = $inv_data[0]['status'];		
			
			/* CRM assign */
			$crm_data='';
			if(array_key_exists('crm_assign', $inv_data[0]) && $inv_data[0]['crm_assign']!=''){
				$crm_data=array(
									'c_id'=>$inv_data[0]['crm_assign'],
									'c_value'=>getAgentMerchant($inv_data[0]['crm_assign'])
								);
			}
			
			
			/* Merchant assign */	
			$merchant_data ='';
			if(array_key_exists('merchant_name', $inv_data[0]) && $inv_data[0]['merchant_name']!=''){
				$merchant_data=array(
									'm_id'=>$inv_data[0]['merchant_name'],
									'm_value'=>getAgentMerchant($inv_data[0]['merchant_name'])
								);
			}
				
			$fms_data['crm_data'] = $crm_data;
			$fms_data['merchant_data'] = $merchant_data;
			
			$fms_data['fob_sample'] = $inv_data[0]['fob_sample_approval'];
			$fms_data['fpt_testing'] = $inv_data[0]['fpt_testing_approval'];
			$fms_data['payment_term'] = $inv_data[0]['payment_term'];
			$fms_data['mode_of_transport'] = $inv_data[0]['mode_of_transport'];
			/* New code END */
			
			
			for($i=1;$i<=$count;$i++){
			$data = array("performa_invoice_id"=>$id,"item"=>$request->row[$i]['item'],"colour"=>$request->row[$i]['colour'],"description"=>$request->row[$i]['description'],"remark"=>$request->row[$i]['remark'],"bulkqty"=>$request->row[$i]['bulkqty'],"ssqty"=>$request->row[$i]['ssqty'],"unit"=>$request->row[$i]['unit'],"lab_dip"=>$request->row[$i]['lab_dip'],"etd"=>$request->row[$i]['etd'],"hsn_code"=>$request->row[$i]['hsn_code'],"unit_price"=>$request->row[$i]['unit_price'],"gst"=>$request->row[$i]['gst'],"amount"=>$request->row[$i]['amount']);
			
			DB::table('tbl_purchase_invoice_item_data')->where('_id',$request->row[$i]['row_id'])->update($data);
			
				/* Now update FMS data collection  depend on FMS type*/
				$fms_data['etd']=$request->row[$i]['etd'];
				
				$fms_data['item_data'] = array(
										'i_id'=>$request->row[$i]['item'],
										'i_value'=>getItemName($request->row[$i]['item'])
									);
									
				$fms_data['color'] = $request->row[$i]['colour'];
				$fms_data['bulkqty'] = $request->row[$i]['bulkqty'];
				$fms_data['ssqty'] = $request->row[$i]['ssqty'];
				$fms_data['unit'] = $request->row[$i]['unit'];
				$fms_data['unit_price'] = $request->row[$i]['unit_price'];
				$fms_data['lab_dip'] = $request->row[$i]['lab_dip'];
				
				
				$fms_id='';
				$fms_table='';
				if($order_type=='Bulk'){
					$fms_id='5e79feefd049043d090fdbb2';
					$fms_table='pi_fms_bulk_data';				
					
				}elseif($order_type=='Sampling'){
					$fms_id='5eb254aa0f0e751788000094';
					$fms_table='pi_fms_sample_data';
				}			
				DB::table($fms_table)->where('pi_id', $id)->where('item_row',$request->row[$i]['row_id'])->update($fms_data);
			
			
			}
		}else{
			DB::table('tbl_purchase_invoice_item_data')->where('performa_invoice_id',$id)->delete();
		
			for($i=1;$i<=$count;$i++){
			$data = array("performa_invoice_id"=>$id,"item"=>$request->row[$i]['item'],"colour"=>$request->row[$i]['colour'],"description"=>$request->row[$i]['description'],"remark"=>$request->row[$i]['remark'],"bulkqty"=>$request->row[$i]['bulkqty'],"ssqty"=>$request->row[$i]['ssqty'],"unit"=>$request->row[$i]['unit'],"lab_dip"=>$request->row[$i]['lab_dip'],"etd"=>$request->row[$i]['etd'],"hsn_code"=>$request->row[$i]['hsn_code'],"unit_price"=>$request->row[$i]['unit_price'],"gst"=>$request->row[$i]['gst'],"amount"=>$request->row[$i]['amount']);
			DB::table('tbl_purchase_invoice_item_data')->insertGetId($data);
			}
		}
		
		$purchase_data = PurchaseInvoice::find($id);
		//pr($purchase_data); die;
		
		$pidata = getPiVersionsById($id); 
		$pi_cnt_version = count($pidata['pi_version']);
		
		$invoice_num = 'PI-POS-'.$purchase_data->po_serial_number.'-R'.($pi_cnt_version+1);
		
		$view = \View::make('pi_inv_pdf',['purchase_data' => $purchase_data, 'purchase_data_row' => $request->row,'invoice_num'=>$invoice_num]);
		$html_content = $view->render();
		PDF::SetTitle('Proforma Invoice '.$purchase_data->po_serial_number);
		PDF::SetMargins(5, 5);
		PDF::SetHeaderMargin(0);
		PDF::SetAutoPageBreak(TRUE);
		PDF::SetPrintHeader(TRUE);
		PDF::AddPage('P', 'A4');
		PDF::writeHTML($html_content, true, false, true, false, '');
		PDF::lastPage();

		$filename = 'PI-POS-'.$financial_year.'-'.$purchase_data->po_serial_number.'-R'.($pi_cnt_version).'.pdf';
		PDF::Output(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number.'/').$filename, 'F');
		
		if($purchase_invoice_update)
		{
			$verKey = 'r'.($pi_cnt_version+1);;
			$pidata['pi_version'][$verKey]=$filename;
			$pi_version['pi_version'] = $pidata['pi_version'];
			DB::table('tbl_purchase_invoice')->where('_id',$id)->update($pi_version);
			
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice update successfully.');
		}else{
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice not updated.');
		}
    }
	
	
	/* ************* Update Partshipped Invoice */
	
	public function invoiceupdate_partshipped(Request $request, $id)
    {
		checkPermission('edit_pi');
		$this->validate($request, [
			'customer_name'  => 'required',
			'buyer_name' => 'required',
		]);
		//pr($_POST); die;
		$financial_year = $request->financial_year;
		$p_no = $request->partshipped_no;
		$data  = array(
		"customer_name" 			=> $request->customer_name,
		"customer_address"     		=> $request->customer_address,
		"customer_city"         	=> $request->customer_city,
		"customer_state"         	=> $request->customer_state,
		"customer_contact_person"   => $request->customer_contact_person,
		"order_sampling_type"		=> $request->order_sampling_type,
		"customer_phone"   			=> $request->customer_phone,
		"buyer_name"   				=> $request->buyer_name,
		"buyer_address"   			=> $request->buyer_address,
		"buyer_city"   				=> $request->buyer_city,
		"buyer_state"   			=> $request->buyer_state,
		"buyer_contact_person"   	=> $request->buyer_contact_person,
		"buyer_phone"   			=> $request->buyer_phone,
		"gst_no"   					=> $request->gst_no,
		"buyer_gst_no"   			=> $request->buyer_gst_no,
		"sales_agent"   => $request->sales_agent,
		"brand_name"   				=> $request->brand_name,
		"order_type"   				=> $request->order_type,
		"fob_sample_approval"   	=> $request->fob_sample_approval,
		"fpt_testing_approval"   	=> $request->fpt_testing_approval,
		"payment_term"   			=> $request->payment_term,
		"quantity_variation"   		=> $request->quantity_variation,
		"buyer_po_number"   		=> $request->buyer_po_number,
		"status"   					=> $request->status,
		"mode_of_transport"   		=> $request->mode_of_transport,
		"company_of_transport"   	=> $request->company_of_transport,
		"crm_enquiry_no"   			=> $request->crm_enquiry_no,
		"crm_assign"   				=> $request->crm_assign,
		"merchant_name"   			=> $request->merchant_name,
		//"row"   					=> $request->row,
		"subtotal"   				=> $request->subtotal,
		"sampling_charge"   		=> $request->sampling_charge,
		"delivery_charge"   		=> $request->delivery_charge,
		"total"   					=> $request->total,
		"gst"   					=> $request->gst,
		"proforma_invoice_date"    => $request->proforma_invoice_date.' '.date('H:i:s'),
		"approve"   				=> $request->approve,
		); 
		$purchase_invoice_update = DB::table('tbl_purchase_invoice')->where('_id',$id)->update($data);	
		
		$count = count($request->row);
		
		//$purchase_data = PurchaseInvoice::find($id);
		
		$inv_data = DB::table('tbl_purchase_invoice')->where('_id',$id)->get()->toArray();
		if(array_key_exists('data_status', $inv_data[0]) && $inv_data[0]['data_status']==1){
			$data_status=1;
		}else{
			$data_status=0;
		}

		if($data_status==1){
			/* new code start  */
			$order_type = $inv_data[0]['order_type'];
			$fms_data =array();
			$fms_data['invoice_no']= $inv_data[0]['po_serial_number'];
			
			$buyer_data = array(
										'b_id'=>$inv_data[0]['buyer_name'],
										'b_value'=>GetBuyerName($inv_data[0]['buyer_name'])
								);
			
			$customer_data = array(
										'c_id'=>$inv_data[0]['customer_name'],
										'c_value'=>GetBuyerName($inv_data[0]['customer_name'])
								);
				
			$s_person_data = array(
									's_id'=>$inv_data[0]['sales_agent'],
									's_value'=>getAgentMerchant($inv_data[0]['sales_agent'])
								);
								
			$brand_data = array(
									'b_id'=>$inv_data[0]['brand_name'],
									'b_value'=>GetBrandName($inv_data[0]['brand_name'])
								);
			
			
			$fms_data['pi_date']= $inv_data[0]['proforma_invoice_date'];
			$fms_data['buyer_data'] = $buyer_data;
			$fms_data['customer_data'] = $customer_data;
			$fms_data['s_person_data'] = $s_person_data;
			$fms_data['brand_data'] = $brand_data;
			$fms_data['pi_status'] = $inv_data[0]['status'];		
			
			/* CRM assign */
			$crm_data='';
			if(array_key_exists('crm_assign', $inv_data[0]) && $inv_data[0]['crm_assign']!=''){
				$crm_data=array(
									'c_id'=>$inv_data[0]['crm_assign'],
									'c_value'=>getAgentMerchant($inv_data[0]['crm_assign'])
								);
			}
			
			
			/* Merchant assign */	
			$merchant_data ='';
			if(array_key_exists('merchant_name', $inv_data[0]) && $inv_data[0]['merchant_name']!=''){
				$merchant_data=array(
									'm_id'=>$inv_data[0]['merchant_name'],
									'm_value'=>getAgentMerchant($inv_data[0]['merchant_name'])
								);
			}
				
			$fms_data['crm_data'] = $crm_data;
			$fms_data['merchant_data'] = $merchant_data;
			
			$fms_data['fob_sample'] = $inv_data[0]['fob_sample_approval'];
			$fms_data['fpt_testing'] = $inv_data[0]['fpt_testing_approval'];
			$fms_data['payment_term'] = $inv_data[0]['payment_term'];
			$fms_data['mode_of_transport'] = $inv_data[0]['mode_of_transport'];
			/* New code END */
			
			
			for($i=1;$i<=$count;$i++){
			$data = array("performa_invoice_id"=>$id,"item"=>$request->row[$i]['item'],"colour"=>$request->row[$i]['colour'],"description"=>$request->row[$i]['description'],"remark"=>$request->row[$i]['remark'],"bulkqty"=>$request->row[$i]['bulkqty'],"ssqty"=>$request->row[$i]['ssqty'],"unit"=>$request->row[$i]['unit'],"lab_dip"=>$request->row[$i]['lab_dip'],"etd"=>$request->row[$i]['etd'],"hsn_code"=>$request->row[$i]['hsn_code'],"unit_price"=>$request->row[$i]['unit_price'],"gst"=>$request->row[$i]['gst'],"amount"=>$request->row[$i]['amount']);
			
			DB::table('tbl_purchase_invoice_item_data')->where('_id',$request->row[$i]['row_id'])->update($data);
			
				/* Now update FMS data collection  depend on FMS type*/
				$fms_data['etd']=$request->row[$i]['etd'];
				
				$fms_data['item_data'] = array(
										'i_id'=>$request->row[$i]['item'],
										'i_value'=>getItemName($request->row[$i]['item'])
									);
									
				$fms_data['color'] = $request->row[$i]['colour'];
				$fms_data['bulkqty'] = $request->row[$i]['bulkqty'];
				$fms_data['ssqty'] = $request->row[$i]['ssqty'];
				$fms_data['unit'] = $request->row[$i]['unit'];
				$fms_data['unit_price'] = $request->row[$i]['unit_price'];
				$fms_data['lab_dip'] = $request->row[$i]['lab_dip'];
				
				
				$fms_id='';
				$fms_table='';
				if($order_type=='Bulk'){
					$fms_id='5e79feefd049043d090fdbb2';
					$fms_table='pi_fms_bulk_data';				
					
				}elseif($order_type=='Sampling'){
					$fms_id='5eb254aa0f0e751788000094';
					$fms_table='pi_fms_sample_data';
				}			
				DB::table($fms_table)->where('pi_id', $id)->where('item_row',$request->row[$i]['row_id'])->update($fms_data);
			
			
			}
		}else{
			DB::table('tbl_purchase_invoice_item_data')->where('performa_invoice_id',$id)->delete();
		
			for($i=1;$i<=$count;$i++){
			$data = array("performa_invoice_id"=>$id,"item"=>$request->row[$i]['item'],"colour"=>$request->row[$i]['colour'],"description"=>$request->row[$i]['description'],"remark"=>$request->row[$i]['remark'],"bulkqty"=>$request->row[$i]['bulkqty'],"ssqty"=>$request->row[$i]['ssqty'],"unit"=>$request->row[$i]['unit'],"lab_dip"=>$request->row[$i]['lab_dip'],"etd"=>$request->row[$i]['etd'],"hsn_code"=>$request->row[$i]['hsn_code'],"unit_price"=>$request->row[$i]['unit_price'],"gst"=>$request->row[$i]['gst'],"amount"=>$request->row[$i]['amount']);
			DB::table('tbl_purchase_invoice_item_data')->insertGetId($data);
			}
		}
		
		$purchase_data = PurchaseInvoice::find($id);
		//pr($purchase_data); die;
		$pidata = getPiVersionsById($id); 
		$pi_cnt_version = count($pidata['pi_version']);
		//$invoice_num = 'PI-POS-'.$purchase_data->po_serial_number.'-R'.($pi_cnt_version+1);
		
		
		$part_no = '-P'.$p_no.'-R'.($pi_cnt_version);
		$view = \View::make('pi_partshipped_inv_pdf',['purchase_data' => $purchase_data, 'purchase_data_row' => $request->row, 'partshipped_no' => $part_no]);
		
		$html_content = $view->render();
		PDF::SetTitle('Proforma Part Shipped Invoice '.$purchase_data->po_serial_number);
		PDF::SetMargins(5, 5);
		PDF::SetHeaderMargin(0);
		PDF::SetAutoPageBreak(TRUE);
		PDF::SetPrintHeader(TRUE);
		PDF::AddPage('P', 'A4');
		PDF::writeHTML($html_content, true, false, true, false, '');
		PDF::lastPage();

		$filename = 'PI-POS-'.$financial_year.'-'.$purchase_data->po_serial_number.$part_no.'.pdf';
		PDF::Output(public_path('/pi_invoice/'.$financial_year.'/'.$purchase_data->po_serial_number.'-P'.$p_no.'/').$filename, 'F');
		
		if($purchase_invoice_update)
		{
			$verKey = 'r'.($pi_cnt_version+1);;
			$pidata['pi_version'][$verKey]=$filename;
			$pi_version['pi_version'] = $pidata['pi_version'];
			DB::table('tbl_purchase_invoice')->where('_id',$id)->update($pi_version);
			
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice Part Shipped update successfully.');
		}else{
			return redirect()->route('invoicelisting')->with('success', 'Proforma Invoice Part Shipped not updated.');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		//checkPermission('delete_pi');
         if($id!='') {
			DB::table('tbl_quotation')->where('_id', '=', $id)->delete();
			DB::table('tbl_quotation_item')->where('quotation_id', '=', $id)->delete();
		}
		Session::flash('success', 'Quotation deleted successfully!');
		return redirect()->route('quotation_list')->with('danger', 'Quotation deleted successfully.');
    } 
	
	public function PurchaseExport(){
		ini_set('max_execution_time', 3600);
		ini_set('memory_limit','64M');
	
		$filename = "all_proforma.csv";
		$fp = fopen('php://output', 'w');
		
		//define column name
		$header = array(
			'customer_name',
			'customer_address',
			'customer_city', 
			'customer_state', 
			'customer_contact_person',
			'gst_no', 
			'customer_phone',
			'buyer_name', 
			'buyer_address', 
			'buyer_city', 
			'buyer_state',
			'buyer_contact_person',
			'buyer_gst_no', 
			'buyer_phone', 
			'sales_agent',
			'brand_name', 
			'order_type', 
			'sampling_charge',
			'fob_sample_approval',
			'fpt_testing_approval',
			'payment_term',
			'quantity_variation',
			'buyer_po_number',
			'status',
			'mode_of_transport', 
			'company_of_transport', 
			'crm_enquiry_no', 
			'proforma_invoice_date'
		);	
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('tbl_purchase_invoice')->select('customer_name', 'customer_address', 'customer_city', 'customer_state', 'customer_contact_person', 'gst_no', 'customer_phone', 'buyer_name', 'buyer_address', 'buyer_city', 'buyer_state', 'buyer_contact_person', 'buyer_gst_no', 'buyer_phone', 'sales_agent', 'brand_name', 'order_type', 'order_sampling_type', 'fob_sample_approval', 'fpt_testing_approval', 'payment_term', 'quantity_variation', 'buyer_po_number', 'status', 'mode_of_transport', 'company_of_transport', 'crm_enquiry_no', 'proforma_invoice_date')->get()->toarray();
		/* pr($data); die; */
		$i=0;
		foreach($data as $row){
			$i++;
			$rowData = array(
				GetBuyerName($row['customer_name']),
				$row['customer_address'],
				$row['customer_city'], 
				$row['customer_state'], 
				$row['customer_contact_person'], 
				$row['gst_no'], 
				$row['customer_phone'],
				GetBuyerName($row['buyer_name']),
				$row['buyer_address'],
				$row['buyer_city'],
				$row['buyer_state'],
				$row['buyer_contact_person'],
				$row['buyer_gst_no'],
				$row['buyer_phone'],
				getAgentDetails($row['sales_agent']),
				GetBrandName($row['brand_name']),
				$row['order_type'],
				$row['order_sampling_type'],
				$row['fob_sample_approval'],
				$row['fpt_testing_approval'],
				$row['payment_term'],
				$row['quantity_variation'],
				$row['buyer_po_number'],
				$row['status'],
				$row['mode_of_transport'],
				$row['company_of_transport'],
				$row['crm_enquiry_no'],
				$row['proforma_invoice_date']
			);
			/* if($i==2){
				pr($rowData); die;
			} */
			fputcsv($fp, $rowData);			
		}
		exit;
	}
	
	
	public function getmatcharticle(){
				$searchTerm = $_GET['term'];
				$mainData=array();
				$data=array();
				$resArr = array();
				
					$data = DB::table('items')
						->select('*')
						->limit(100)
						->where('article_no','like', '%'.$searchTerm.'%')
						->get()
						->toArray();
						
					foreach($data as $row){ 
						$oid = (array) $row['_id'];
						$id = $oid['oid'];
						$data['id'] = $id; 
						$data['colour'] = $row['colour']; 
						$data['factory_code'] = $row['factory_code']; 
						$data['description'] = addslashes(str_replace("'","",$row['description'])); 
						$data['fabric_finish'] = addslashes(str_replace("'","",$row['fabric_finish'])); 
						$data['composition'] = addslashes(str_replace("'","",$row['composition'])); 
						$data['count_construct'] = addslashes(str_replace("'","",$row['count_construct']));
						$data['width'] = addslashes(str_replace("'","",$row['width']));
						
						if(array_key_exists('max_price', $row)){
							$data['max_price'] = addslashes(str_replace("'","",$row['max_price']));
						}else{
							$data['max_price'] = '';
						}
						if(array_key_exists('unit', $row)){
							$data['unit'] = addslashes(str_replace("'","",$row['unit']));
						}else{
							$data['unit'] = '';
						}
						
						$data['gsm'] = addslashes(str_replace("'","",$row['gsm']));
						$data['hsn_code'] = getHSNDetails($row['hsn_code']);
						$data['gst_code'] = getHSNgstDetails($row['hsn_code']);
						$data['value'] = $row['article_no']; 
						array_push($mainData, $data); 
					}			
				echo json_encode($mainData);
				exit;
	}
	
	public function ajaxCustomerDetails($customer_id)
	{
	  //$arr = array("Biju","Sanju");
	  $data = DB::table('customers')->where('_id',$customer_id)->first();
	  $name = array();
	  $d = array();
	  $category = $data['category'];
	  $customer_categories = DB::table('customer_categories')->where('category',$category)->first();
	  if($customer_categories['delivery_day']!='')
	  {
	   $today = time();
	   $delivery_date = date('d-m-Y',strtotime('+'.$customer_categories['delivery_day'].' days', $today));
	  }else{
	   $delivery_date = date('d-m-Y');  
	  }
	  foreach($data['create_departments'][0]['departments_repeat'] as $names){
	      $d['department'] = $data['create_departments'][0]['department_name'];
	      $d['name']=$names['name'];
	      $d['label']=$names['name'];
	      $d['mobile']=$names['mobile_no'];
	      $d['email']=$data['email'];
	      $d['delivery_date'] = $delivery_date;
	      array_push($name,$d);
	  }
      return json_encode($name);
	}
	
	public function ajaxCustomerDetails_delivery_date($customer_id)
	{
	  $data = DB::table('customers')->where('_id',$customer_id)->first(); 
	  $category = $data['category'];
	  $customer_categories = DB::table('customer_categories')->where('category',$category)->first();
	  if($customer_categories['delivery_day']!='')
	  {
	   $today = time();
	   return $delivery_date = date('d-m-Y',strtotime('+'.$customer_categories['delivery_day'].' days', $today));
	  }else{
	   return $delivery_date = date('d-m-Y');  
	  }
	}
	
	public function ajaxBuyerDetails($buyer_id)
	{
	  $select_data = RegisterForm::find($buyer_id);
	  $buy_add = buyerDeliveryAddress($buyer_id,'');
	  $arr = array('buyer_address' => $buy_add,'buyer_city' => $select_data['city'],'buyer_country' => @$select_data['country'],'buyer_state' => $select_data['state'],'buyer_contact_person'=> $select_data['contact_person_dispatch'],'buyer_phone'=> $select_data['mobile_no_dispatch'],'gst' => $select_data['gst']);
      return json_encode($arr);
	}
	
	public function ajaxItemData(Request $request, $id)
	{
	  $select_data = ArticleModel::find($id);
	  $color = explode(',',$select_data['colour']);
	  $color_html = '';
	  $color_html .= '<option value="">--Select--</option>';
	  for($i=0; $i<count($color); $i++){
		  $color_html .= '<option value="'.$color[$i].'">'.$color[$i].'</option>';
	  }
	  $arr = array('colour' => $color_html,'description' => $select_data['description'], 'hsn_code' => getHSNDetails($select_data['hsn_code']),'gst_code' => getHSNgstDetails($select_data['hsn_code']));
      return json_encode($arr);
	}
	
	
	public function ajax_insert_pi_into_fms(Request $request)
	{
		$pi_id = $request->pi_id;
		//echo $pi_id; die('hii');
	 
		$pi_data = getPiDataByPiId($pi_id);
		/* check data status */
		$data_inserted = 'no';
		if(array_key_exists('data_status', $pi_data) && $pi_data['data_status']==1){
			echo $data_inserted = 'yes';
			exit;
		}
		
		/* echo $pi_data['order_type']; 
		pr($pi_data); die; */ 
		/* check order type */
		if($pi_data['order_type']=='Sampling'){
		  //$fms_id = '5eabe7fb0f0e751a64005ef5'; //PI-Sampling
		  $fms_id = '5eb254aa0f0e751788000094'; //PI-Sampling
		}elseif($pi_data['order_type']=='Bulk'){
			$fms_id = '5e79feefd049043d090fdbb2'; //PI-Bulk
		}
	  //echo $fms_id; die;
	   
	  if($pi_id==''){
		   $result = array(
							'status' =>false,
							'description' => 'pi_id is empty'
						);
	  }else{
			// here we start insert data into PI FMS data	
			// get fms data
			$fms_data = DB::table('fmss')->where('_id',$fms_id)->get()->toArray();
			$fms_data_tbl = $fms_data[0]['fms_table'];
			$fms_type = $fms_data[0]['fms_type'];
			
			// get PI data by pi_id 
			$inv_data = DB::table('tbl_purchase_invoice')->where('_id',$pi_id)->get()->toArray();
			$pi_date = $inv_data[0]['proforma_invoice_date'];
			$invoice_no = $inv_data[0]['po_serial_number'];
			
			$buyer_data = array(
									'b_id'=>$inv_data[0]['buyer_name'],
									'b_value'=>GetBuyerName($inv_data[0]['buyer_name'])
								);
								
			$customer_data = array(
									'c_id'=>$inv_data[0]['customer_name'],
									'c_value'=>GetBuyerName($inv_data[0]['customer_name'])
								);
			
			$s_person_data = array(
									's_id'=>$inv_data[0]['sales_agent'],
									's_value'=>getAgentMerchant($inv_data[0]['sales_agent'])
								);
								
			$brand_data = array(
									'b_id'=>$inv_data[0]['brand_name'],
									'b_value'=>GetBrandName($inv_data[0]['brand_name'])
								);
			
			$pi_status = $inv_data[0]['status'];
			$fob_sample = $inv_data[0]['fob_sample_approval'];
			$fpt_testing = $inv_data[0]['fpt_testing_approval'];
			$payment_term = $inv_data[0]['payment_term'];
			$mode_of_transport = $inv_data[0]['mode_of_transport'];
			$buyer_po_number = $inv_data[0]['buyer_po_number'];
			
			
			/* CRM assign */
			$crm_data='';
			if(array_key_exists('crm_assign', $inv_data[0]) && $inv_data[0]['crm_assign']!=''){
				$crm_data=array(
									'c_id'=>$inv_data[0]['crm_assign'],
									'c_value'=>getAgentMerchant($inv_data[0]['crm_assign'])
								);
			}
			
			
			/* Merchant assign */	
			$merchant_data ='';
			if(array_key_exists('merchant_name', $inv_data[0]) && $inv_data[0]['merchant_name']!=''){
				$merchant_data=array(
									'm_id'=>$inv_data[0]['merchant_name'],
									'm_value'=>getAgentMerchant($inv_data[0]['merchant_name'])
								);
			}
			
				try{
						$data = array();						
						$pi_rows = DB::table('tbl_purchase_invoice_item_data')->where('performa_invoice_id',$pi_id)->get()->toArray();
						$data['pi_id']=$pi_id;
						$data['fms_id']=$fms_id;
						 //pr($pi_rows); die('==row--');
						foreach($pi_rows as $key=>$row){
							/* echo $pi_date; pr($row); die('==row--'); */
							/* step data */
							$stepkArr = [];
							$stepsIds = get_step_id_by_fms_id($fms_id);
							//pr($stepsIds); die;
							$i=0;
							foreach($stepsIds as $skey=>$sval){
								$stepkArr[]= (array)$sval['_id'];
								$stepArrMain[]= $stepkArr[$i]['oid'];
								$i++;
							}
							$i=0;
							foreach($stepArrMain as $step)
							{
								$s_plane_dt = getStepDataByStepId($step);
								$fms_when = $s_plane_dt['fms_when'];
								$planedDt = '';
								
								/* ******** for step linked with task ******* */
								if (array_key_exists("task_id_link",$fms_when) && $s_plane_dt['fms_when_type']==1)
								{
									$Hr 	= $fms_when['after_task_time'];
									//$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($pi_date . " + ".$Hr." hours"));									
								}
								
								
								
								/* ******** for step linked with step ******* */
								
								/* ******** for step linked with ETD ******* */
								if (array_key_exists("task_id_link",$fms_when) && $s_plane_dt['fms_when_type']==9)
								{
									$Hr 	= $fms_when['before_etd_time'];
									//$dec_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($row['etd'] . " - ".$Hr." hours"));
								}
								/* ******** for step linked with ETD End ******* */
								
								if($s_plane_dt['fms_when_type']==3 && array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='order_sampling')
								{
									$data[$step] = array(
														'planed'=>'',
														'actual'=>''
													);
													
								}elseif (array_key_exists("after_step_id",$fms_when))
								{
									// get previous step plened_date
									$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
									
									$Hr 	= $fms_when['time_hr'];
									//$inc_dt = ceil($Hr/8);
									
									$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$Hr." hours"));
								}
								
								/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
								if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){
									$data[$step] = array(
														'none_date'=>'',
														'input_type'=>$s_plane_dt['fms_when']['input_type']
														);
								}elseif($s_plane_dt['fms_when_type']==6){
									$data[$step] = array(
														'fms_when_type'=>$s_plane_dt['fms_when']['fms_when_type'],
														'input_type'=>$s_plane_dt['fms_when']['input_type']
														);
								}else if($s_plane_dt['fms_when_type']==4 && $s_plane_dt['fms_when']['input_type']=='notes'){
									$data[$step] = array(
														'notes'=>''
														);
								}else if($s_plane_dt['fms_when_type']==13){
									$data[$step] = array(
														'dropdownlist'=>''
														);
								}else if($s_plane_dt['fms_when_type']==14){
									
									if (array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='issue_po'){
										$data[$step] = array(
															'planed'=>'',
															'actual'=>'',
															'pi_number'=>'',
														);
									}else{
										$data[$step] = array(
														'planed'=>'',
														'actual'=>'',
														);
									}
														
								}else if($s_plane_dt['fms_when_type']==15){
									$data[$step] = array(
															'calendar'=>''
														);
								}else if($s_plane_dt['fms_when_type']==16){
									$data[$step] = array(
															'pl_qty'=>'',
															'act_qty'=>''
														);
								}else if($s_plane_dt['fms_when_type']==17){
									$data[$step] = array(
															'inv_date'=>'',
															'inv_no'=>'',
															'inv_amount'=>''
														);
								}else if(array_key_exists("task_id_link",$fms_when) && $s_plane_dt['fms_when_type']==12)
								{
									$Hr 	= $fms_when['after_task_time'];
									//$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($pi_date . " + ".$Hr." hours"));
									
									$planedDt = checkSunday($planedDt);
									
									$data[$step] = array(
														'planed'=>$planedDt,
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>''
													);
													
								}else if($s_plane_dt['fms_when_type']==11)
								{
									$data[$step] = array(
														'planed'=>'',
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>''
													);
													
								}else if($s_plane_dt['fms_when_type']==12 && array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='resolve_query')
								{
									$data[$step] = array(
														'planed'=>'',
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>''
													);
													
								}else if(array_key_exists("depend_steps_appr",$s_plane_dt) && $s_plane_dt['fms_when_type']==12)
								{
									$data[$step] = array(
														'planed'=>'',
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>''
													);
													
								}else{
									$planedDt = checkSunday($planedDt);
									$data[$step] = array(
														'planed'=>$planedDt,
														'actual'=>''
													);
								}
								$i++;
							} /* step data end*/
							//pr($data);
							// insert data into FMS
							$data['etd']=$row['etd'];
							
							/* new code start  */	
							$oid = (array) $row['_id'];
							$item_row = $oid['oid'];
							$data['item_row']= $item_row;
							$data['invoice_no']= $invoice_no;
							$data['pi_date']= $pi_date;
							$data['buyer_data'] = $buyer_data;
							$data['customer_data'] = $customer_data;
							$data['s_person_data'] = $s_person_data;
							$data['brand_data'] = $brand_data;
							$data['pi_status'] = $pi_status;
							
							$data['item_data'] = array(
														'i_id'=>$row['item'],
														'i_value'=>getItemName($row['item'])
													);
													
							$data['color'] = $row['colour'];
							$data['bulkqty'] = $row['bulkqty'];
							$data['ssqty'] = $row['ssqty'];
							$data['unit'] = $row['unit'];
							$data['unit_price'] = $row['unit_price'];
							$data['lab_dip'] = $row['lab_dip'];
							
							$data['crm_data'] = $crm_data;
							$data['merchant_data'] = $merchant_data;
							
							$data['fob_sample'] = $fob_sample;
							$data['fpt_testing'] = $fpt_testing;
							$data['payment_term'] = $payment_term;
							$data['mode_of_transport'] = $mode_of_transport;
							$data['buyer_po_number'] = $buyer_po_number;
							/* New code END */	
							
							/* check if it is partshipped PI */
							if(array_key_exists('partshipped_no', $pi_data) && $pi_data['partshipped_no']>0){
								$data['partshipped_no'] = $pi_data['partshipped_no'];
							}
							
							//pr($data); die('kk');
							
							$inserted = DB::table($fms_data_tbl)->insertGetId($data);
						}  /* for loop end */
						
						if($inserted)
						{
							/* update main invoice table */
							DB::table('tbl_purchase_invoice')->where('_id',$pi_id)->update(['data_status' => 1]);
							$result = array(
										'status' =>true,
										'msg' => 'PI data inserted into FMS'
										);
						}else{
							 $result = array(
											'status' =>false,
											'msg' => 'PI data not inserted into FMS'
										);
						}
			
			}catch(Exception $ex){
				DB::rollback();
			}	
		}
		return json_encode($result);
		die;
	}
	
	/* auto search customer */
	public function ajaxCustomerSearch()
	{
		//pr($_GET);
		$data = array();
		if(isset($_GET)){
			if($_GET['query']!=''){
				$company_name = $_GET['query'];
				$data = DB::table('register_form')->select('company_name')->where('company_name','like', '%'.$company_name.'%')->get()->toArray();
				echo json_encode($data); die;
			}else{
				echo json_encode($data); die;
			}
			
		}else{
			echo json_encode($data); die;
		}		
	}
	
	
	public function quotation_pdf($id)
	{
	    $quotation_details = DB::table('tbl_quotation')->where('_id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$company_details = DB::table('company_details')->first();
		$view = \View::make('general.quotation.quotationpdf',['quotation_details' => $quotation_details, 'quotation_item_details' => $quotation_item_details, 'other_charges'=>$other_charges, 'company_details' => $company_details]);
		$html_content = $view->render(); 
 		PDF::SetTitle('Quotation');
		PDF::SetFont('helvetica', '', 8);
 		PDF::AddPage('L');
 		PDF::writeHTML($html_content, true, false, true, false, '');
 		$filename = $quotation_details['quotation_no'].'.pdf';
 		PDF::Output($filename,'D');
 		exit;
// 		//$pdf->output($filename, 'I');
// 		@mkdir(public_path('/quotation'));
// 		PDF::Output(public_path('/quotation/').$filename, 'F');
// 		PDF::reset();

// PDF::SetTitle('Hello World');
//   PDF::AddPage();
//   PDF::Write(0, 'Hello World');
//   PDF::Output('hello_world.pdf','D');
//   exit;
	}
	
	public function sale_quotation_transaction(Request $request)
	{
	    $quotation_details = DB::table('tbl_quotation_item')->orderBy('created_date','DESC')->get()->toArray();
	    //pr($quotation_details); die;
	    if(!$_POST){
	    return view('general.quotation.sale_quotation_transaction',['quotation_details' =>$quotation_details]);
	    }else{
	        $query = DB::table('tbl_quotation_item');
	         if($request->customer_name!=''){
            $query->where('customer_id',$request->customer_name);  
            }
            if($request->quotation_no!=''){
            $da = DB::table('tbl_quotation')->select('_id')->where('quotation_no', 'LIKE','%'.$request->quotation_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('quotation_id',$arr);
            }
            if($request->quotation_ref_no!=''){
            $da = DB::table('tbl_quotation')->select('_id')->where('quotation_ref_no', 'LIKE','%'.$request->quotation_ref_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('quotation_id',$arr);
            }
            if($request->quotation_ref_date!=''){
            $da = DB::table('tbl_quotation')->select('_id')->where('quotation_ref_date', 'LIKE','%'.date('Y-m-d',strtotime($request->quotation_ref_date)).'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['_id']);
            }
            $query->whereIn('quotation_id',$arr);
            }
            if($request->item_name!=''){
            $query->where('item_name', 'LIKE','%'.$request->item_name.'%');
            }
            $quotation_details =$query->orderBy('created_date', 'DESC')->get()->toArray();
	        return view('general.quotation.sale_quotation_transaction',['quotation_details' =>$quotation_details,'request'=>$request]);
	    }
	}
	
	
	public function quotation_ref_no_search(Request $request)
	{
	    if($request->quotation_id==''){
	    $quotation_ref_no = SearchDataMatch($request->quotation_ref_no);
	    $count = DB::table('tbl_quotation')->where('quotation_ref_no_search',$quotation_ref_no)->count();
	    }else{
	    $quotation_ref_no = SearchDataMatch($request->quotation_ref_no);
	    $count = DB::table('tbl_quotation')->where('quotation_ref_no_search',$quotation_ref_no)->where('_id','!=',$request->quotation_id)->count();   
	    }
	    if($count>0){
	        return 1;
	    }else{
	        return 0;
	    }
	}
	
	
}