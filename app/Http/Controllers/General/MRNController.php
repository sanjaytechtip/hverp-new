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

class MRNController extends Controller
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
			 //pr($request);die; 
			//die('pppffff'); 
		$mrnlist = DB::table('tbl_mrn_item')->orderBy('mrn_id','DESC')->paginate(30);
		//pr($mrnlist); die;
		}else{
			//die('ppasdasdp');
		    //pr($request->all()); die; mrn_bill_date
		    $query = DB::table('tbl_mrn_item');
            
            if($request->mrn_receipt_no!=''){
            $da = DB::table('tbl_mrn')->select('id')->where('mrn_receipt_no', 'LIKE','%'.$request->mrn_receipt_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array) $da_d;
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('mrn_id',$arr);
            }
            
            if($request->mrn_bill_no!=''){
            $da = DB::table('tbl_mrn')->select('id')->where('mrn_bill_no', 'LIKE','%'.$request->mrn_bill_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array) $da_d;
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('mrn_id',$arr);
            }
            
            if($request->mrn_bill_date!=''){
				$mrn_bill_date  = date('Y-m-d', strtotime($request->mrn_bill_date));
            $da = DB::table('tbl_mrn')->select('id')->where('mrn_bill_date', 'LIKE','%'.$mrn_bill_date.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array) $da_d;
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('mrn_id',$arr);
            }
            
           
            
            if($request->mrn_challan_no!=''){
            $da = DB::table('tbl_mrn')->select('id')->where('mrn_challan_no', 'LIKE','%'.$request->mrn_challan_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array) $da_d;
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('mrn_id',$arr);
            }
            
             if($request->created_by!=''){
            $da = DB::table('tbl_mrn')->select('id')->where('created_by', 'LIKE',$request->created_by)->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array) $da_d;
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('mrn_id',$arr);
            }
            
            
            if($request->supplier_name!=''){
            $query->where('supplier_name',$request->supplier_name);  
            }
            
            $mrnlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($request);die; 
		 $object_transport = DB::table('transport')->select('id','transport')->orderBy('transport','ASC')->get()->toArray();
		 $transport = json_decode(json_encode($object_transport), true);
		 //pr($transport); die;
		return view('general.mrn.mrnlist',['mrnlist'=>$mrnlist,'request'=>$request,'transport'=>$transport]);
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
	 // SrNo
    public function create(Request $request) {
        $purchaselist = DB::table('tbl_purchase_order')->orderBy('id', 'DESC')->paginate(100);
        $purchaselist_count = DB::table('tbl_purchase_order')->orderBy('id', 'DESC')->count();
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$object_transport = DB::table('transport')->orderBy('transport', 'ASC')->get()->toArray();
		$transport = json_decode(json_encode($object_transport), true);
		
        $SrNo = DB::table('tbl_config_mrn')->get()->first();
        if(empty($_POST)){
		return view('general.mrn.mrncreate',['datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo->row,'itemlist_count'=>$itemlist_count,'purchaselist'=>$purchaselist,'purchaselist_count'=>$purchaselist_count,'transport'=>$transport]);
        }else{
            //pr($request->all()); die;
            //pr($_POST); die;
			if ($request->hasFile('grn_upload')) {
				$path = public_path('uploads/mrn');
				$file = $request->file('grn_upload');
				$fileName = uniqid() . '_' . trim($file->getClientOriginalName());
				$file->move($path, $fileName);
				}else{
				$fileName = "";	
				}
            $arr = array(
                            'mrn_receipt_no'                =>  $request->mrn_receipt_no,
                            'mrn_type'                      =>  $request->mrn_type,
                            'supplier_name'                 =>  $request->supplier_name,
                            'mrn_date'                      =>  date('Y-m-d',strtotime($request->mrn_date)),
                            'mrn_bill_date'                 =>  date('Y-m-d',strtotime($request->mrn_bill_date)),
                            'mrn_remarks'                   =>  $request->mrn_remarks,
                            'mrn_bill_no'                   =>  $request->mrn_bill_no,
                            'mrn_challan_no'                =>  $request->mrn_challan_no,
                            'mrn_eway_bill'                 =>  $request->mrn_eway_bill,
                            'mrn_subtotal'                  =>  $request->mrn_subtotal,
                            'mrn_tax_amount'                =>  $request->mrn_tax_amount,
                            'mrn_grand_total'               =>  $request->mrn_grand_total,
                            'other_charges_val'             =>  $request->other_charges_val,
                            'other_charges_name'            =>  $request->other_charges_name,
							'grn'							=>  $request->grn,
							'transport_id'					=>  $request->transport,
							'grn_upload'					=>  $fileName,
							'box_received'					=>  $request->box_received,
							'box_pending'					=>  $request->box_pending,
							'box_dispatch'					=>  $request->box_dispatch,
							'receiver_id'					=>  $request->receiver_id,
                            'created_by'                    =>  Auth::user()->id
                            
                        );
            $last_id = DB::table('tbl_mrn')->insertGetId($arr);

            //$oid = (array) $last_id;
            //$last_id = $oid['oid'];
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'purchase_order_id'     =>  $rows['purchase_order_id'],
                    'item_name'             =>  $rows['item_name'],
                    'item_id'               =>  $rows['item_id'],
                    'tax_rate'              =>  $rows['tax_rate'],
                    'rate'                  =>  $rows['rate'],
                    'mrp'                   =>  $rows['mrp'],
                    'discount_per'          =>  $rows['discount_per'],
                    'discount'              =>  $rows['discount'],
                    'quantity'              =>  $rows['quantity'],
                    'net_rate'              =>  $rows['net_rate'],
                    'tax_amount'            =>  $rows['tax_amount'],
                    'amount'                =>  $rows['amount'],
                    'supplier_name'         =>  $request->supplier_name,
                    'mrn_id'                =>  $last_id,
                    'created_at'          =>  date('Y-m-d H:i:s'),
                    'inward_qty'            => "0"
                    );
            DB::table('tbl_mrn_item')->insertGetId($data);
            }			
            }
            DB::table('tbl_config_mrn')->update(array('row'=>$request->mrn_receipt_no+1));
			
            return redirect()->route('mrn_list')->with('success', 'MRN saved successfully.');
            
        }
    }
    
    public function item_search_data_mrn(Request $request)
    {
        //pr($request->all()); die;
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
    
    public function item_search_data_quotation(Request $request)
    {
        $itemlist = DB::table('tbl_quotation_item')->where('customer_id', $request->customer_name)->where('item_id', $request->id)->orderBy('id','desc')->first();
        $list = DB::table('tbl_quotation')->where('id', $itemlist['quotation_id'])->orderBy('id','desc')->first();
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
			DB::table('tbl_purchase_invoice')->where('id', $last_id)->update($pi_version);
			
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
		
		
		$p_invoice = DB::table('tbl_purchase_invoice')->where('id',$id)->first();
		
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
		DB::table('tbl_purchase_invoice')->where('id',$id)->update(array('partshipped_done'=>$p_no));
		
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
			DB::table('tbl_purchase_invoice')->where('id', $last_id)->update($pi_version);
			
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
       $edit_purchase_invoice = DB::table('tbl_purchase_invoice')->where('id',$id)->first();
		//$items = ArticleModel::get();
		$items = ArticleModel::limit(100)->get();
		$customer = RegisterForm::get();
		
		return view('general.purchaseInvoice.purchaseinvoiceshow',['items' => $items,'customer' => $customer,'edit_purchase_invoice' => $edit_purchase_invoice]);
    }
	
	public function view($id)
	{
	    $quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$quotation_details_revised = DB::table('tbl_quotation_revised')->select('id','revised_date')->where('revised_id',$id)->orderBy('revised_date','ASC')->get()->toArray();
		//pr($quotation_details_revised); die;
		return view('general.quotation.quotationview',['quotation_details_revised'=>$quotation_details_revised,'quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges]);
		
	}
	
	public function quotation_revised($id,$revised_id)
	{
        $quotation_details = DB::table('tbl_quotation_revised')->where('id',$id)->where('revised_id',$revised_id)->get()->first();
        $quotation_item_details = DB::table('tbl_quotation_item_revised')->where('quotation_id',$id)->where('revised_id',$revised_id)->get()->toArray();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray(); 
        return view('general.quotation.quotationrevised',['quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges]);
	}
	
	public function quotation_print($id)
	{
	    $quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
		$quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		$company_details = DB::table('company_details')->first();
		//pr($quotation_details); die;
		return view('general.quotation.quotationprint',['quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'company_details'=>$company_details]);
	}
	
	public function create_part_shipped($id)
    {
		checkPermission('edit_pi');
        $edit_purchase_invoice = DB::table('tbl_purchase_invoice')->where('id',$id)->first();
		//$items = ArticleModel::get();
		$items = ArticleModel::select('id', 'article_no')->get();
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
					
					$oid = $val['id'];
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
		//pr($_POST); die;
        $purchaselist = DB::table('tbl_purchase_order')->orderBy('id', 'DESC')->paginate(100);
        $purchaselist_count = DB::table('tbl_purchase_order')->orderBy('id', 'DESC')->count();
		$mrn_details = DB::table('tbl_mrn')->where('id',$id)->get()->first();
		$mrn_details = (array) $mrn_details;
		$mrn_item_details = DB::table('tbl_mrn_item')->where('mrn_id',$id)->get()->toArray();
		$other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
		//pr($other_charges); die;
		$itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
		$object_transport = DB::table('transport')->orderBy('transport', 'ASC')->get()->toArray();
		$transport = json_decode(json_encode($object_transport), true);
		//pr($quotation_item_details); die; 
		if(empty($_POST)){
		return view('general.mrn.mrnedit',['datalist'=>$itemlist,'mrn_details'=>$mrn_details,'mrn_item_details'=>$mrn_item_details,'other_charges'=>$other_charges,'itemlist_count'=>$itemlist_count,'purchaselist'=>$purchaselist,'purchaselist_count'=>$purchaselist_count,'transport'=>$transport]);
		}else{
		    
            //pr($request->all()); die; 
        $mrn_details = DB::table('tbl_mrn')->where('id',$id)->get()->first();
		$mrn_details = (array) $mrn_details;
        $mrn_item_details = DB::table('tbl_mrn_item')->where('mrn_id',$id)->get()->toArray();
        $other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        
        unset($mrn_details['id']);
		if ($request->hasFile('grn_upload')) {
		$path = public_path('uploads/mrn');
		$file = $request->file('grn_upload');
		$fileName = uniqid() . '_' . trim($file->getClientOriginalName());
		$file->move($path, $fileName);
		}else if(!empty($request->grn_file_hidden)){
		$fileName = $request->grn_file_hidden;
		}else{
		$fileName = "";	
		}
        //pr($quotation_details); die;
    
            $arr = array(
                            'mrn_receipt_no'                =>  $request->mrn_receipt_no,
                            'mrn_type'                      =>  $request->mrn_type,
                            'supplier_name'                 =>  $request->supplier_name,
                            'mrn_date'                      =>  date('Y-m-d',strtotime($request->mrn_date)),
                            'mrn_bill_date'                 =>  date('Y-m-d',strtotime($request->mrn_bill_date)),
                            'mrn_remarks'                   =>  $request->mrn_remarks,
                            'mrn_bill_no'                   =>  $request->mrn_bill_no,
                            'mrn_challan_no'                =>  $request->mrn_challan_no,
                            'mrn_eway_bill'                 =>  $request->mrn_eway_bill,
                            'mrn_subtotal'                  =>  $request->mrn_subtotal,
                            'mrn_tax_amount'                =>  $request->mrn_tax_amount,
                            'mrn_grand_total'               =>  $request->mrn_grand_total,
							'grn'							=>  $request->grn,
							'transport_id'					=>  $request->transport,
							'grn_upload'					=>  $fileName,
							'box_received'					=>  $request->box_received,
							'box_pending'					=>  $request->box_pending,
							'box_dispatch'					=>  $request->box_dispatch,
							'receiver_id'					=>  $request->receiver_id,
                            'created_by'                    =>  Auth::user()->id,
                            
                        );
            $last_id = DB::table('tbl_mrn')->where('id',$id)->update($arr);
            DB::table('tbl_mrn_item')->where('mrn_id',$id)->delete();
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'item_name'     =>$rows['item_name'],
                    'item_id'       =>$rows['item_id'],
                    'tax_rate'      =>$rows['tax_rate'],
                    'rate'          =>$rows['rate'],
                    'mrp'           =>$rows['mrp'],
                    'discount_per'  =>$rows['discount_per'],
                    'discount'      =>$rows['discount'],
                    'quantity'      =>$rows['quantity'],
                    'net_rate'      =>$rows['net_rate'],
                    'tax_amount'    =>$rows['tax_amount'],
                    'amount'        =>$rows['amount'],
                    'supplier_name' =>$request->supplier_name,
                    'mrn_id'        =>$id,
                    'created_at'  => date('Y-m-d H:i:s'),
                    );
                    //pr($data); die;
            DB::table('tbl_mrn_item')->insertGetId($data);
            }			
            }
			
            return redirect()->route('mrn_list')->with('success', 'MRN Updated successfully.');
            
        
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
		"sales_agent"   			=> $request->sales_agent,
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
		$purchase_invoice_update = DB::table('tbl_purchase_invoice')->where('id',$id)->update($data);	
		
		$count = count($request->row);
		
		//$purchase_data = PurchaseInvoice::find($id);
		
		$inv_data = DB::table('tbl_purchase_invoice')->where('id',$id)->get()->toArray();
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
			
			DB::table('tbl_purchase_invoice_item_data')->where('id',$request->row[$i]['row_id'])->update($data);
			
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
			DB::table('tbl_purchase_invoice')->where('id',$id)->update($pi_version);
			
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
		$purchase_invoice_update = DB::table('tbl_purchase_invoice')->where('id',$id)->update($data);	
		
		$count = count($request->row);
		
		//$purchase_data = PurchaseInvoice::find($id);
		
		$inv_data = DB::table('tbl_purchase_invoice')->where('id',$id)->get()->toArray();
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
			
			DB::table('tbl_purchase_invoice_item_data')->where('id',$request->row[$i]['row_id'])->update($data);
			
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
			DB::table('tbl_purchase_invoice')->where('id',$id)->update($pi_version);
			
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
			DB::table('tbl_mrn')->where('id', '=', $id)->delete();
			DB::table('tbl_mrn_item')->where('mrn_id', '=', $id)->delete();
		}
		Session::flash('success', 'MRN deleted successfully!');
		return redirect()->route('mrn_list')->with('danger', 'MRN deleted successfully.');
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
						$oid = (array) $row['id'];
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
	  $data = DB::table('customers')->where('id',$customer_id)->first();
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
	  $data = DB::table('customers')->where('id',$customer_id)->first(); 
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
			$fms_data = DB::table('fmss')->where('id',$fms_id)->get()->toArray();
			$fms_data_tbl = $fms_data[0]['fms_table'];
			$fms_type = $fms_data[0]['fms_type'];
			
			// get PI data by pi_id 
			$inv_data = DB::table('tbl_purchase_invoice')->where('id',$pi_id)->get()->toArray();
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
								$stepkArr[]= (array)$sval['id'];
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
							$oid = (array) $row['id'];
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
							DB::table('tbl_purchase_invoice')->where('id',$pi_id)->update(['data_status' => 1]);
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
	    $quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
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
            $da = DB::table('tbl_quotation')->select('id')->where('quotation_no', 'LIKE','%'.$request->quotation_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('quotation_id',$arr);
            }
            if($request->quotation_ref_no!=''){
            $da = DB::table('tbl_quotation')->select('id')->where('quotation_ref_no', 'LIKE','%'.$request->quotation_ref_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('quotation_id',$arr);
            }
            if($request->quotation_ref_date!=''){
            $da = DB::table('tbl_quotation')->select('id')->where('quotation_ref_date', 'LIKE','%'.date('Y-m-d',strtotime($request->quotation_ref_date)).'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
                array_push($arr,(string)$da_d['id']);
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
	    $count = DB::table('tbl_quotation')->where('quotation_ref_no_search',$quotation_ref_no)->where('id','!=',$request->quotation_id)->count();   
	    }
	    if($count>0){
	        return 1;
	    }else{
	        return 0;
	    }
	}
	
	
            public function submit_inward_balance(Request $request)
            {
				//pr($_POST); die;
				
            //pr($request->all()); die;
            $table_name = 'tbl_balance_stock';
            $mfg_date = explode('/',$request->mfg_date);
            $expiry_date = explode('/',$request->expiry_date);
			if($request->retest_date!=''){
			$retest = explode('/',$request->retest_date);
			$retest_date = $retest[2].'-'.$retest[1].'-'.$retest[0];
			}else{
			$retest_date = "";	
			}

			if ($request->hasFile('coa_file')) {
			$path = public_path('uploads/stock');
			$file = $request->file('coa_file');
			$fileName = uniqid() . '_' . trim($file->getClientOriginalName());
			$file->move($path, $fileName);
			}else{
			$fileName = "";	
			}
            
			$insert = array(
									"item_id"           =>  $request->item_id,
									"room_no"           =>  $request->room,
									"rack_no"           =>  $request->rack_no,
									"batch_no"          =>  $request->batch_no,
									"mfg_date"          =>  $mfg_date[2].'-'.$mfg_date[1].'-'.$mfg_date[0],
									"expiry_date"       =>  $expiry_date[2].'-'.$expiry_date[1].'-'.$expiry_date[0],
									"retest_date"		=>  $retest_date,
									"coa"				=>  $request->coa,
									"coa_file"			=>  $fileName,
									"reject_qty"		=>  $request->reject_qty,
									"breakage_qty"		=>  $request->breakage_qty,
									"shortage_qty"		=>  $request->shortage_qty,
									"quantity"          =>  $request->quantity,
									"user_id"           =>  Auth::user()->id,
									"mrp"               =>  $request->mrp,
									"loc_cen"           =>  $request->loc_cen,
									"rate"              =>  $request->rate,
									"amount"            =>  $request->amount,
									"tra_type"          =>  1,
									"type"              =>  2,
									"created_at"        =>  date('Y-m-d H:i:s'),
									"updated_at"        =>  date('Y-m-d H:i:s'),
								);

            $insert_data = DB::table($table_name)->insert($insert);
			balanceStockUpdate($request->item_id);
			
            if($insert_data){
                
            $select_qty = DB::table('tbl_mrn_item')->where('item_id',$request->item_id)->where('mrn_id',$request->mrn_id)->get()->first(); 			
            if($select_qty->inward_qty=='' || $select_qty->inward_qty==0)
            {
            $update_data = array('inward_qty' => $request->quantity);
            DB::table('tbl_mrn_item')->where('item_id',$request->item_id)->where('mrn_id',$request->mrn_id)->update($update_data);  
            }else{
            $update_data = array('inward_qty' => $request->quantity+$select_qty->inward_qty);
            DB::table('tbl_mrn_item')->where('item_id',$request->item_id)->where('mrn_id',$request->mrn_id)->update($update_data);    
            }
            
            if($select_qty->purchase_order_id!='')
            {
            $select_qty_po = DB::table('tbl_purchase_order_item')->where('id',$select_qty->purchase_order_id)->get()->first(); 

			if($select_qty_po->inward_qty=='' || $select_qty_po->inward_qty==0)
            {
            $update_data_po = array('inward_qty' => $request->quantity);
            DB::table('tbl_purchase_order_item')->where('id',$select_qty->purchase_order_id)->update($update_data_po);  
            }else{
            $update_data_po = array('inward_qty' => $request->quantity+$select_qty_po->inward_qty);
            DB::table('tbl_purchase_order_item')->where('id',$select_qty->purchase_order_id)->update($update_data_po);  
            }   
            }
            
            $select_item_data = DB::table('items')->where('id',$request->item_id)->get()->first(); 
            
            // if($select_item_data->stock==0)
            // {
            // $update_data_item = array('stock' => $request->quantity+$select_item_data->stock);
            // DB::table('items')->where('id',$request->item_id)->update($update_data_item);  
            // }else{
            // $update_data_item = array('stock' => $request->quantity+$select_item_data->stock);
            // DB::table('items')->where('id',$request->item_id)->update($update_data_item);  
            // } 
            
            return 1;
            }
            
            }
        
		public function stock_inward_image_remove(Request $request)
		{
        if(!empty($request->id))
		{
			$object = DB::table('tbl_balance_stock')->where('id',$request->id)->first();
			$data = json_decode(json_encode($object), true);
			$image_file = $data['coa_file'];
			if(!empty($image_file)){
				@unlink(public_path('uploads/stock/'.$image_file));
			}
			DB::table('tbl_balance_stock')->where('id',$request->id)->update(array('coa_file'=>""));	
		}
        echo 1;
		}

		
		public function mrn_image_remove(Request $request)
		{
        if(!empty($request->id))
		{
			$object = DB::table('tbl_mrn')->where('id',$request->id)->first();
			$data = json_decode(json_encode($object), true);
			$image_file = $data['grn_upload'];
			if(!empty($image_file)){
				@unlink(public_path('uploads/mrn/'.$image_file));
			}
			DB::table('tbl_mrn')->where('id',$request->id)->update(array('grn_upload'=>""));	
		}
        echo 1;
		}
            
        public function stock_inward_list(Request $request) {
		if(!$_POST){
		$stockinwardlist = DB::table('tbl_balance_stock')->where('type',2)->orderBy('id','DESC')->paginate(30);
		//pr($stockinwardlist); die;
		}else{
		    $query = DB::table('tbl_balance_stock')->where('type',2);
            if($request->room_no!=''){
            $da = DB::table('room')->select('id')->where('room_no', 'LIKE','%'.$request->room_no.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('room_no',$arr);
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
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            $stockinwardlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($openingstocklist);die; 
		return view('general.stockinward.stockinwardlist',['stockinwardlist'=>$stockinwardlist,'request'=>$request]);
	 }
	 
	public function rejected_qty_list(Request $request) {
		if(!$_POST){
		$stockinwardlist = DB::table('tbl_balance_stock')->where('reject_qty','!=','')->orderBy('id','DESC')->paginate(30);
		//pr($stockinwardlist); die;
		}else{
		    $query = DB::table('tbl_balance_stock')->where('reject_qty','!=','');
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            $stockinwardlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($openingstocklist);die; 
		return view('general.rejected_qty.rejected_qtylist',['stockinwardlist'=>$stockinwardlist,'request'=>$request]);
	 } 

	 public function breakage_qty_list(Request $request) {
		if(!$_POST){
		$stockinwardlist = DB::table('tbl_balance_stock')->where('breakage_qty','!=','')->orderBy('id','DESC')->paginate(30);
		//pr($stockinwardlist); die;
		}else{
		    $query = DB::table('tbl_balance_stock')->where('breakage_qty','!=','');
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            $stockinwardlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($openingstocklist);die; 
		return view('general.breakage_qty.breakage_qtylist',['stockinwardlist'=>$stockinwardlist,'request'=>$request]);
	 } 

	 public function shortage_qty_list(Request $request) {
		if(!$_POST){
		$stockinwardlist = DB::table('tbl_balance_stock')->where('shortage_qty','!=','')->orderBy('id','DESC')->paginate(30);
		//pr($stockinwardlist); die;
		}else{
		    $query = DB::table('tbl_balance_stock')->where('shortage_qty','!=','');
            
            if($request->item_name!=''){
            $da = DB::table('items')->select('id')->where('name', 'LIKE','%'.$request->item_name.'%')->get()->toArray();
            $arr = array();
            foreach($da as $da_d){
				$da_d = (array)$da_d;
            array_push($arr,(string)$da_d['id']);
            }
            $query->whereIn('item_id',$arr);
            }
            
            if($request->batch_no!=''){
            $query->where('batch_no', 'LIKE','%'.$request->batch_no.'%');
            }
            
            $stockinwardlist =$query->orderBy('id', 'DESC')->paginate(30);
		}
		 //pr($openingstocklist);die; 
		return view('general.shortage_qty.shortage_qtylist',['stockinwardlist'=>$stockinwardlist,'request'=>$request]);
	 } 
	 public function update_stock_inward(Request $request)
	     {
	         $table_name = 'tbl_balance_stock';
	         $mfg_date = explode('/',$request->mfg_date);
	         $expiry_date = explode('/',$request->expiry_date);
			 if($request->retest_date!=''){
				$retest = explode('/',$request->retest_date);
				$retest_date = $retest[2].'-'.$retest[1].'-'.$retest[0];
				}else{
				$retest_date = "";	
				}
	
				if ($request->hasFile('coa_file')) {
				$path = public_path('uploads/stock');
				$file = $request->file('coa_file');
				$fileName = uniqid() . '_' . trim($file->getClientOriginalName());
				$file->move($path, $fileName);
				}
				else if(!empty($request->coa_file_hidden))
				{
				$fileName = $request->coa_file_hidden;
				}
				else
				{
				$fileName = "";	
				}
			$update = array(
									"item_id"           =>  $request->item_id,
									"room_no"           =>  $request->room,
									"rack_no"           =>  $request->rack_no,
									"batch_no"          =>  $request->batch_no,
									"mfg_date"          =>  $mfg_date[2].'-'.$mfg_date[1].'-'.$mfg_date[0],
									"expiry_date"       =>  $expiry_date[2].'-'.$expiry_date[1].'-'.$expiry_date[0],
									"retest_date"		=>  $retest_date,
									"coa"				=>  $request->coa,
									"coa_file"			=>  $fileName,
									"reject_qty"		=>  $request->reject_qty,
									"breakage_qty"		=>  $request->breakage_qty,
									"shortage_qty"		=>  $request->shortage_qty,
									"quantity"          =>  $request->quantity,
									"user_id"           =>  Auth::user()->id,
									"mrp"               =>  $request->mrp,
									"loc_cen"           =>  $request->loc_cen,
									"rate"              =>  $request->rate,
									"amount"            =>  $request->amount,
									"updated_at"        =>  date('Y-m-d H:i:s')
								);

					
             $update_data = DB::table($table_name)->where('id',$request->id)->update($update);
			 balanceStockUpdate($request->item_id);
              return 1;
	     }
	     
	     public function view_mrn(Request $request, $id)
	     {
	       $view_mrn = DB::table('tbl_mrn')->where('id',$id)->first();
		   $view_mrn = (array)$view_mrn;
	       $view_mrn_item = DB::table('tbl_mrn_item')->where('mrn_id',$id)->get()->toArray();
	       //pr($view_mrn_item); die;
	       return view('general.mrn.view_mrn',['view_mrn'=>$view_mrn,'view_mrn_item'=>$view_mrn_item]); 
	     }
	     
        public function ajaxPurchaseItems(Request $request)
        {
			
        if(!empty($request->purchase_id))
        {
        $html = "";
        if($request->rowCount_d_val==0)
        {
        $i=1;
        }else{
        $i = $request->rowCount_d_val+1;
        }
        $purchase_details = DB::table('tbl_purchase_order')->where('id',$request->purchase_id[0])->first();
		if(!empty($purchase_details)){
			$purchase_details = (array)$purchase_details;
		}
		
        //pr($purchase_details);pr($request->all()); die; 
        foreach($request->purchase_id as $purchase_id){
        $purchase = DB::table('tbl_purchase_order_item')->where('purchase_order_id',$purchase_id)->get()->toArray();
        if(!empty($purchase)){
        foreach($purchase as $key=>$de){
			$de = (array) $de;
        $html .='<tr class="pur-san" id="data-row-'.$i.'"><td data-text="Sl"><span class="count-row-new">'.$i.'</span></td><input type="hidden" name="row['.$i.'][purchase_order_id]" value="'.$de['id'].'"><td data-text="Item"><input type="text" readonly value="'.$de['item_name'].'" name="row['.$i.'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required /><input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$de['item_id'].'" /></td><td data-text="Tax"> <input type="text" value="'.$de['tax_rate'].'" name="row['.$i.'][tax_rate]" cus="'.$i.'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required /> </td><td data-text="Rate"> <input type="text" value="'.$de['rate'].'" name="row['.$i.'][rate]" cus="'.$i.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" /> </td><td data-text="MRP"> <input type="text" readonly value="'.$de['mrp'].'" name="row['.$i.'][mrp]" cus="'.$i.'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> </td><td data-text="Dis %"> <input min="0" type="number" value="'.$de['discount_per'].'" name="row['.$i.'][discount_per]" cus="'.$i.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" /> </td><td data-text="Discount"> <input type="number" readonly value="'.$de['discount'].'" name="row['.$i.'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" /> </td><td data-text="Quantity"> <input min="1" required type="number" value="'.$de['quantity'].'" name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" /> </td><td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right">'.number_format($de['net_rate'],2,'.','').'</td><td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right">'.number_format($de['tax_amount'],2,'.','').'</td><td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right">'.number_format($de['amount'],2,'.','').'</td><input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" value="'.$de['net_rate'].'" name="row['.$i.'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" value="'.$de['tax_amount'].'" name="row['.$i.'][tax_amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" value="'.$de['amount'].'" name="row['.$i.'][amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
        $i++;
        }
        }
        }
        $other_charges = "";
        $sum=0;
        if(!empty($purchase_details['other_charges_name']))
        {
        foreach($purchase_details['other_charges_name'] as $key=>$charges){
        $other_charges .='<tr id="san-'.$key.'"><td>'.$charges.'</td><td class="ch-val">'.$purchase_details['other_charges_val'][$key].'</td><td><a href="javascript:void(0);" onclick="return removeOther('.$key.')" class="btn btn-danger waves-effect waves-classic"><i class="icon md-minus" style="margin:0;"></i></a></td><input type="hidden" name="other_charges_name[]" value="'.$charges.'"><input type="hidden" name="other_charges_val[]" value="'.$purchase_details['other_charges_val'][$key].'" ></tr>';
        $sum = $sum+$purchase_details['other_charges_val'][$key];
        }
        
        $other_charges .='<tr id="results-charges"><td><strong>Total</strong></td><td id="total-other">'.$sum.'</td><td></td></tr>';
        }
        $arr = array('html'=>$html,'purchase_saletax'=>$purchase_details['purchase_saletax'],'other_charges'=>$other_charges);
        return json_encode($arr);
        }
        }
        
        
        public function purchase_search_po(Request $request)
        {
          //pr($request->all()); die;
	    $query = DB::table('tbl_purchase_order');  
        if($request->purchase_no!=''){
        $query->where('purchaseorder_no', 'LIKE', '%'.$request->purchase_no.'%');  
        $purchase_no =$request->purchase_no; 
        }
        
        $data_list =$query->orderBy('purchaseorder_no', 'asc')->paginate(100);
        $data_list_count =$query->orderBy('purchaseorder_no', 'asc')->count();
        $html = "";
	    if(!empty($data_list)){
	    foreach($data_list as $de){
			$de = (array) $de;
	        $html .='<tr>
                     <td><input type="checkbox" name="chk_search[]" class="chk_search" value="'.$de['id'].'"></td>
                     <td>'.$de['purchaseorder_no'].'</td>
                     <td>'.date('d-m-Y h:i:s',strtotime($de['created_at'])).'</td>
                 </tr>';
	    }
	    }
	    $arr = array('html'=>$html,'record_count'=>$data_list_count);
	    return json_encode($arr);  
        }
        
        
        public function ajaxSearchPurchase(Request $request)
        {
          //pr($request->all()); die;
	    $query = DB::table('tbl_purchase_order');  
        if($request->purchase_no!=''){
        $query->where('purchaseorder_no', 'LIKE', '%'.$request->purchase_no.'%');  
        $purchase_no =$request->purchase_no; 
        }
        
        $data_list =$query->orderBy('purchaseorder_no', 'asc')->paginate(100);
        $data_list_count =$query->orderBy('purchaseorder_no', 'asc')->count();
        $html = "";
	    if(!empty($data_list)){
	    foreach($data_list as $de){
			$de = (array)$de;
	        $html .='<tr>
                     <td><input type="checkbox" name="chk_search[]" class="chk_search" value="'.$de['id'].'"></td>
                     <td>'.$de['purchaseorder_no'].'</td>
                     <td>'.date('d-m-Y h:i:s',strtotime($de['created_at'])).'</td>
                 </tr>';
	    }
	    }
	    $arr = array('html'=>$html,'record_count'=>$data_list_count);
	    return json_encode($arr);     
        }
        
        public function purchase_net_rate_search_mrn(Request $request)
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
            //$data_list =$query->orderBy('_id', 'DESC')->get()->toArray();
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
                <td style="text-align:center;" class="white-space-normal"><input id="'.$user['id'].'" value="'.$user['vendor_sku'].'-'.$user['name'].'-'.$user['hsn_code'].'-'.$user['grade'].'-'.$user['brand'].'-'.$user['packing_name'].'-'.$user['list_price'].'-'.$user['mrp'].'-'.$user['net_rate'].'-'.$user['stock'].'" name="apply-radio" type="radio"></td>
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
	
	
}