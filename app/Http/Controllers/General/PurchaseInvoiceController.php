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

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class PurchaseInvoiceController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		checkPermission('view_pi');
		$purchaseinvoicelist = DB::table('tbl_purchase_invoice')->orderBy('created_at','-1')->paginate(30);
		 //pr($purchaseinvoicelist);die; 
		return view('general.purchaseInvoice.purchaseinvoicelist')->with('purchaseinvoicelist', $purchaseinvoicelist);
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
    public function create() {
		checkPermission('add_pi');
	    //$items = ArticleModel::get();
	    $items = ArticleModel::select('_id', 'article_no')->get();		
		//$customer = RegisterForm::select('_id', 'company_name')->where('status', "=", 1)->get();
		$customer = array();			
		return view('general.purchaseInvoice.purchaseinvoicecreate',['items' => $items,'customer' => $customer]);
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
		PDF::SetTitle('Proforma Invoice '.$purchase_data->po_serial_number);
		PDF::SetMargins(5, 5);
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
    public function edit($id)
    {
		checkPermission('edit_pi');
        $edit_purchase_invoice = DB::table('tbl_purchase_invoice')->where('_id',$id)->first();
		//$items = ArticleModel::get();
		$items = ArticleModel::select('_id', 'article_no')->get();
		//$customer = RegisterForm::where('status', "=", 1)->get();
		$customer =array();
		return view('general.purchaseInvoice.purchaseinvoiceedit',['items' => $items,'customer' => $customer,'edit_purchase_invoice' => $edit_purchase_invoice]);
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
		checkPermission('delete_pi');
         if($id!='') {
			DB::table('tbl_purchase_invoice')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Invoice deleted successfully!');
		return redirect()->route('invoicelisting')->with('danger', 'Proforma Invoice deleted successfully.');
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
	
	public function ajaxCustomerDetails($customer_id)
	{
	  $select_data = RegisterForm::find($customer_id);
	  //$select_data = (array) $select_data;
	  $cus_add = customerDeliveryAddress($customer_id);
	  
	  $arr = array('customer_delivery_address' =>  $cus_add,'customer_city' => $select_data['city'],'customer_country' => @$select_data['country'],'customer_state' => $select_data['state'],'customer_contact_person'=> $select_data['contact_person_dispatch'],'customer_phone'=> $select_data['mobile_no_dispatch'],'gst' => $select_data['gst'],'payment_term' => $select_data['payment_term'], 'crm_assign'=>$select_data['crm_assign'], 'merchant_name'=>$select_data['merchant_name'], 'email_id_md'=>$select_data['email_id_md'], 'agent'=>$select_data['agent']);
	  //pr($arr); die;
      return json_encode($arr);
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
	
	public function getinvoiceemaildata(Request $request){
		$pi_id = $request->pi_id;
		$pi_data = getPiDataByPiId($pi_id);
		//pr($pi_data);  
		//pr($pi_version); 
		//die;
		$customer_id = $pi_data['customer_name'];
		$cdata = GetBuyerDataById($customer_id);
		
		$company_name = $cdata['company_name'];
		$name_of_md = $cdata['name_of_md'];
		
		$recipient = '';
		
		$email_id_md = $cdata['email_id_md'];
		$recipient .= $email_id_md !=''? $email_id_md.',' :'';
		
		$email_id_dispatch = $cdata['email_id_dispatch'];		
		$recipient .= $email_id_dispatch !=''? $email_id_dispatch.',' :'';
		
		$email_merch1 = $cdata['email_merch1'];
		$recipient .= $email_merch1 !=''? $email_merch1.',' :'';
		
		$email_merch2 = $cdata['email_merch2'];
		$recipient .= $email_merch2 !=''? $email_merch2.',' :'';

		
		$inv_no = $pi_data['po_serial_number'];
		$sales_id = $pi_data['sales_agent'];
		$merchant_id = $pi_data['merchant_name'];
		
		$sales_p_name = getAgentDetails($sales_id);
		
		$merchant_name = getAgentDetails($merchant_id);
		
		//pr($sales_p_data); 
		//pr($merchant_data);
		$data = array(
						'company_name'=>$company_name,
						'name_of_md'=>$name_of_md,
						'recipient'=>$recipient,
						'inv_no'=>$inv_no,
						'sales_p_name'=>$sales_p_name,
						'merchant_name'=>$merchant_name
					);
		$json_data = json_encode($data);
		echo $json_data;
		die;
	}
	
	public function seninvoicemail(Request $request){
		$post = $request->all();
		//pr($post); die;
		$pi_id = $post['pi_id'];
		$pi_data = getPiDataByPiId($pi_id);
		
		$sales_id = $pi_data['sales_agent'];
		$merchant_id = $pi_data['merchant_name'];
		
		$cc ='';
		$sales_data = getUserDetailById($sales_id);		
		$sales_email = $sales_data['email'];
		
		//$cc .= $sales_email !=''? $sales_email.',' :''; 
		
		$merchant_data = getUserDetailById($merchant_id);
		$merchant_email = $merchant_data['email'];
		
		//$cc .= $merchant_email !=''? $merchant_email.',' :''; 
		
		
		$c_user = getCurrentUserDetails(); 
		$u_email = $c_user['email'];
		$u_name = $c_user['name'];
		
		//$cc .= $u_email !=''? $u_email.',' :''; 
		
		//$cc = array('md8shoyeb@gmail.com');
		$cc = array($sales_email,$merchant_email,$u_email);
		
		$from_email=$u_email;
		
		$recipient .= $email_id_md !=''? $email_id_md.',' :''; 
		
		$customer_id = $pi_data['customer_name'];
		$cdata = GetBuyerDataById($customer_id);
		
		$company_name = $cdata['company_name'];
		
		$name_of_md = $cdata['name_of_md'];
		$email_id_md = $cdata['email_id_md'];
		
		$po_serial_number = $pi_data['po_serial_number'];
		
		if(array_key_exists('partshipped_parent', $pi_data) && $pi_data['partshipped_parent']!=''){
			$po_serial_number = $po_serial_number.'-P'.$pi_data['partshipped_no'];
		}
		
		$pi_pdf_version = end($pi_data['pi_version']);
		
		$financial_year = $pi_data['financial_year'];
		
		$customer = $post['customer'];		
		$message = $post['message'];
		
		$inv_type = $post['inv_type'];
		
		$pdf_path = '/'.$inv_type.'/'.$financial_year.'/'.$po_serial_number.'/'.$pi_pdf_version;
		
		/* mail function */
		
		$to = explode(',', $customer);
		//$to = 'shoyeb.at.techvoi@gmail.com';
		$from = $from_email; 
		$fromName = $u_name; 
		  
		$subject = 'Proforma Invoice No. '.$po_serial_number.', '.$company_name.': Positex';
		//echo $subject; die;
		// Attachment files
		
		/* attach agreement file dynamic */
		
		$view = \View::make('fabric_sale_agreement_pdf',['po_serial_number' => $po_serial_number, 'company_name' => $company_name]);
		$html_content = $view->render();
		PDF::SetTitle('Fabric Sale Agreement Positex: '.$po_serial_number);
		PDF::SetMargins(5, 5);
		PDF::SetHeaderMargin(0);
		PDF::SetAutoPageBreak(TRUE);
		PDF::SetPrintHeader(TRUE);
		PDF::AddPage('P', 'A4');
		PDF::writeHTML($html_content, true, false, true, false, '');
		PDF::lastPage();
		$filename = 'fabric_sale_agreement_'.$po_serial_number.'.pdf';
		PDF::Output(public_path('/sale_agreement/').$filename, 'F');
		
		//=== 21 may 2021 start		
		$data["from"] = $u_email;
		$data["from_name"] = $u_name;
		$data["email"] = $to;
		$data["cc"] = $cc;
        $data["subject"] = $subject;
        $data["body"] = $post['message'];
 
        $files = [
			public_path($pdf_path),
			public_path('/sale_agreement/'.$filename)
        ];  
		
		/* $files = [
			public_path($pdf_path)
        ];  */
  
        Mail::send([], $data, function($message)use($data, $files) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
					->cc($data["cc"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
 
           foreach ($files as $file){
                $message->attach($file);
            } 
            
        });
		
		DB::table('tbl_purchase_invoice')->where('_id',$pi_id)->update(['email_send' => 1]);
		echo '1';
		die;
		//pr($post);die;
	}
	
	
	public function sendenqemail_old(Request $request){
		$post = $request->all();
		$userLoginData = Session::get('userLogin');
		$login_type = $userLoginData['login_type'];
		$user_login_email = $userLoginData['user_login_id'];
		
		$ccArr = array();
		if($login_type=='buyer_login'){
			$sales_id = $userLoginData['agent'];
			$merchant_id = $userLoginData['merchant_name'];
		
			$sales_data = getUserDetailById($sales_id);		
			$sales_email = $sales_data['email'];
			
			if($sales_email!=''){
				array_push($ccArr,$sales_email);
			}
			
			$merchant_data = getUserDetailById($merchant_id);
			$merchant_email = $merchant_data['email'];
			if($merchant_email!=''){
				array_push($ccArr,$merchant_email);
			}
		}elseif($login_type=='brand_login'){
			$company_name = $userLoginData['brand_name'];
		}
		

		$cc = $ccArr;
		//$cc = array();
		
		$company_name = $userLoginData['company_name'];
		
		$to = 'sc@positex.in';
		//$to = 'shoyeb.at.techvoi@gmail.com';
		$from = $user_login_email; 
		$fromName = 'Positex Pvt. Ltd'; 
		 
		$subject = 'Enquiry: '.$company_name;
		
		$data["from"] = $from;
		$data["from_name"] = $company_name;
		$data["email"] = $to;
		
        $data["subject"] = $subject;
        $data["body"] = $post['message'];
		
		if(!empty($cc)){
			$data["cc"] = $cc;
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
					->cc($data["cc"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
		}else{
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
		}
		echo '1';
		die;
	}
	
	public function sendpstemail_old(Request $request){
		$post = $request->all();
		
		$userLoginData = Session::get('userLogin');
		$login_type = $userLoginData['login_type'];
		$user_login_email = $userLoginData['user_login_id'];
		$user_id = $userLoginData['user_id'];
		
		
		$ccArr = array();
		if($login_type=='buyer_login'){
			$sales_id = $userLoginData['agent'];
			$merchant_id = $userLoginData['merchant_name'];
		
			$sales_data = getUserDetailById($sales_id);		
			$sales_email = $sales_data['email'];
			
			if($sales_email!=''){
				array_push($ccArr,$sales_email);
			}
			
			$merchant_data = getUserDetailById($merchant_id);
			$merchant_email = $merchant_data['email'];
			if($merchant_email!=''){
				array_push($ccArr,$merchant_email);
			}
			
			$company_name = $userLoginData['company_name'];
			
		}elseif($login_type=='brand_login'){
			$company_name = $userLoginData['brand_name'];
		}
		

		//$cc = $ccArr;
		$cc = array();
		
		//$to = 'orders@positex.in';
		$to = 'shoyeb.at.techvoi@gmail.com';
		$from = $user_login_email; 
		$fromName = 'Positex Pvt. Ltd'; 
		 
		$subject = 'PST: '.$company_name;
		
		$data["from"] = $from;
		$data["from_name"] = $company_name;
		$data["email"] = $to;
		
        $data["subject"] = $subject;
        $data["body"] = $post['message'];
		
		$send=0;
		if(!empty($cc)){
			$data["cc"] = $cc;
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
					->cc($data["cc"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			$send=1;
		}else{
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			$send=1;
		}
		
		if($send==1){
			$pst_row=DB::table('pst_buyer_brand')->select('pst_no')->orderBy('id', 'DESC')->first();
			if(empty($pst_row)){
				$pst_no = '1001';
			}else{
				$pst_no = (int) $pst_row['pst_no'];
				$pst_no = ($pst_no+1);
			}
			/* send auto reply email */
			
			$data = array();
			$msg = '<p>Dear Sir/Madam,</p><p>Thanks for raising the PST for your concern, vide PST#'.$pst_no.' Date '.date('d M  Y').'.</p><p>We shall look into your problem/ feedback and provide reply within 24 hours.</p><p><br></p><p></p><p><br></p><p>Regards<br>Dilip<br>Positex Pvt Ltd.</p>';
			
			
			//$to =$user_login_email;
			$to = 'md8shoyeb@gmail.com';
			
			$from = 'orders@positex.in'; 
			$fromName = 'Positex Pvt. Ltd'; 

			$subject = 'Positex PST#'.$pst_no;

			$data["from"] = $from;
			$data["from_name"] = $fromName;
			$data["email"] = $to;

			$data["subject"] = $subject;
			$data["body"] = $msg;
		
			
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			
			
			
			
			
			$data = array();
			$data['pst_no'] = strval($pst_no);
			$data['problem_faced'] = $post['message'];
			$data['positex_remarks'] = '';
			$data['status'] = '0';
			$data['pst_closed_at'] = '';
			$data['login_type'] = $login_type;
			$data['user_login_email'] = $user_login_email;
			$data['user_id'] = $user_id;
			$data['created_at'] = date('Y-m-d H:i:s');
			$inserted = DB::table('pst_buyer_brand')->insertGetId($data);
			if($inserted){
				echo '1';
			}else{
				echo '0';
			}
			
		}else{
			echo '0';
		}	
		
		die;
	}
	
}