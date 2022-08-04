<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\SupplierModel;
use Session;
use Image;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class SupplierController extends Controller
{
	function __construct(){
		/* $this->SupplierModel = new SupplierModel(); */
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		checkPermission('view_supplier');
		$suppliers = DB::table('supplier_form')->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.supplier_management.supplierlisting')->with('suppliers', $suppliers);
	}
	 
	public function searchBuyer(Request $request) {
			//pr($_POST); die;
			$search=$request->buyer;
		    $search_by=$request->order_no;
			
			if($search=='sales_person'){
				$buyerlisting = DB::table('register_form')->where('agent', 'LIKE',"%{$request->order_no}%")->paginate(20); 
			}elseif($request->has('order_no')){
				
				$buyerlisting = DB::table('register_form')->where($request->buyer, 'LIKE',"%{$request->order_no}%")->paginate(20); 
				//pr($buyerlisting);die;
			}else{
				$buyerlisting = DB::table('register_form')->paginate(20);
				$articles['search']=$request->buyer;
				$articles['search_by']=$request->order_no;
			}
		
		/* pr($search);die; */
        return view('BuyerManagement.registerUser.buyerlisting', compact('buyerlisting'))->with('search', $search)->with('search_by', $search_by);
    }

    /**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {		
		checkPermission('add_supplier');
		return view('general.supplier_management.add_supplier');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		include(app_path() . '/googlesheet/index.php');
		checkPermission('add_supplier');
		$this->validate($request, [
			'company_name'   => 'required',
			'contact_person' => 'required',
			'location' 	     => 'required',
			'country' 	     => 'required',
			'city' 	         => 'required',
			'contact_no'     => 'required',
			'email_id' 	     => 'required',
		]);
		
		if($request->gst_reg_copy_img !=''){
			$image = $request->file('gst_reg_copy_img');
			$imageName = $image->getClientOriginalName();
			$destinationPath = ('uploads/supplier/gst_reg_copy_images');
			$image->move($destinationPath, $imageName);
		}else{
			$imageName = '';
		}
		if($request->pan_card_copy_img !=''){
			$image1 = $request->file('pan_card_copy_img');
			$imageName1 = $image1->getClientOriginalName();
			$destinationPath = ('uploads/supplier/pan_card_copy_images');
			$image1->move($destinationPath, $imageName1);
		}else{
			$imageName1 = '';
		}
		if($request->cncl_chk_copy_img !=''){
			$image2 = $request->file('cncl_chk_copy_img');
			$imageName2 = $image2->getClientOriginalName();
			$destinationPath = ('uploads/supplier/cncl_chk_copy_images');
			$image2->move($destinationPath, $imageName2);
		}else{
			$imageName2 = '';
		}
		if($request->company_name!=''){
			$company = trim($request->company_name);
			//$company_name = str_replace(" ","-",$company);
			$company_name = $company;
		}
		
		$data = array(
			"company_name"   => $company_name,
			"contact_person" => $request->contact_person,
			"location"	     => $request->location,
			"address"		 => $request->address,
			"country"    	 => $request->country,
			"state"    		 => $request->state,
			"city" 	  		 => $request->city,
			"contact_no" 	 => $request->contact_no,
			"email_id"		 => $request->email_id,
			"pan_no"   		 => $request->pan_no,
			"gst"   		 => $request->gst,
			"bank_name"   	 => $request->bank_name,
			"account_no"  	 => $request->account_no,
			"ifsc_code"   	 => $request->ifsc_code,
			"branch_name"    => $request->branch_name,
			"payment_terms"  => $request->payment_terms,
			"gst_reg_copy_img" 	=> 	$imageName,
			"pan_card_copy_img" => 	$imageName1,
			"cncl_chk_copy_img" => 	$imageName2,
			"created_at"  	 => date('Y-m-d H:i:s')
		);
		/* pr($data); die; */
		$insert = DB::table('supplier_form')->insert(array($data));
		
		dataspredsheet([[
			$request->company_name, 
			$request->contact_person, 
			$request->location, 
			$request->address, 
			$request->country, 
			$request->state, 
			$request->city,
			$request->contact_no,
			$request->email_id,
			$request->pan_no,
			$request->gst,
			$request->bank_name,
			$request->account_no,
			$request->ifsc_code,
			$request->branch_name,
			$request->payment_terms
		]],"1e_pB1rPVn6JBNYnyU4kaFPbWbsz6XB4FxNUn4yVvrAk","A1!A:S");
		
		if($insert){			
			Session::flash('success', 'Supplier Added Successfully');
			return redirect('admin/supplierlisting');
		} else{ 
			return view('general.supplier_management.add_supplier');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
       	$showsupplier = DB::table('supplier_form')->where('_id',$id)->first();
		return view('general.supplier_management.supplierview')->with('showsupplier', $showsupplier); 
    }
	
	public function updateView(Request $request, $id) {
	 
	          if($request->status==1){
			  $deny_notes = '';
			  $mail_message = 'Your Account is Approved.';
			  }else{
			  $deny_notes = $request->deny_notes;
			  $mail_message = 'Your Account is Denied.';
			  }
	 
			   $this->validate($request, [
			   'marchant_assign' => 'required',
			   'status' => 'required',
			   'payment_term' => 'required',
		
	    	]); 
	    	$registerdata = array(
				 "marchant_assign"=>$request->marchant_assign,
				 'status' => $request->status,
			     'payment_term' => $request->payment_term, 
				 'deny_notes' => $deny_notes,
				  );
		$cc_mail = explode(',',getUserByDesignationEmail($request->marchant_assign));
		DB::table('register_form')->where('_id', $id)->update($registerdata);
		$selected_data = DB::table('register_form')->where('_id', $id)->first();
		$email_send_approved_client = selectEmailTemplate('buyer-registration-approved-client');
		$email_send_approved_admin = selectEmailTemplate('buyer-registration-approved-admin');
        $email_send_denied_client = selectEmailTemplate('buyer-registration-denied-client');
		$email_send_denied_admin = selectEmailTemplate('buyer-registration-denied-admin');
		
		$emailFindReplace = array(
						'##Email-Id (MD)##' => $selected_data['email_id_md'],
						'##COMPANY_NAME##' => $selected_data['company_name'],
						'##COMPANY_ADDRESS##' => $selected_data['company_address'],
						'##STATE##' => $selected_data['state'],
						'##CITY##' => $selected_data['city'],
						'##MD_NAME##' => $selected_data['name_of_md'],
						'##MD_MOBILE##' => $selected_data['mobile_no_md'],
						'##MD_LANDLINE##' => $selected_data['landline_no_comp'],
						'##MD_EMAIL##' => $selected_data['email_id_md'],
						'##COMPANY_TYPE##' => $selected_data['company_type'],
						'##GST_NO##' => $selected_data['gst'],
						'##PAN_NO##' => $selected_data['pan_no'],
						'##SALES_PERSON_OF_PASHUPATI##' => $selected_data['agent'],
						'##COMPANY_SIZE##' => $selected_data['size_of_company'],
						'##NO_OF_EMPLOYEE##' => $selected_data['no_of_employee'],
						'##GARMENT_CATEGORY##' => implode(',',$selected_data['garment_category']),
						'##DELIVERY_ADDRESS##' => implode(',',$selected_data['delivery_address']),
						'##DISPATCH_CONTACT_PERSON##' => $selected_data['contact_person_dispatch'],
						'##DISPATCH_MOBILE_NO##' => $selected_data['mobile_no_dispatch'],
						'##DISPATCH_EMAIL_ID##' => $selected_data['email_id_dispatch'],
						'##MERCHANT_NAME1##' => $selected_data['name_merchant_1'],
						'##MERCHANT_MOBILE1##' => $selected_data['mobile_merch1'],
						'##MERCHANT_EMAIL1##' => $selected_data['email_merch1'],
						'##MERCHANT_NAME2##' => $selected_data['name_merchant_2'],
						'##MERCHANT_MOBILE2##' => $selected_data['mobile_merch2'],
						'##MERCHANT_EMAIL2##' => $selected_data['email_merch2'],
						'##ACCOUNT_HEAD_NAME##' => $selected_data['name_account_head'],
						'##ACCOUNT_HEAD_MOBILE##' => $selected_data['mobile_ac_head'],
						'##EMAIL_ACCOUNT_HEAD##' => $selected_data['email_ac_head'],
						'##ACCOUNT_NAME1##' => $selected_data['name_account_1'],
						'##ACCOUNT_MOBILE1##' => $selected_data['mobile_ac_1'],
						'##ACCOUNT_EMAIL1##' => $selected_data['email_ac_1'],
						'##ACCOUNT_NAME2##' => $selected_data['name_account_2'],
						'##ACCOUNT_MOBILE2##' => $selected_data['mobile_ac_2'],
						'##ACCOUNT_EMAIL2##' => $selected_data['email_ac_2'],
						'##BANK_NAME##' => $selected_data['bank_name'],
						'##BANK_ADDRESS##' => $selected_data['bank_address'],
						'##ACCOUNT_TYPE##' => $selected_data['account_type'],
						'##IFSC_CODE##' => $selected_data['ifsc_code'],
						'##ACCOUNT_NUM##' => $selected_data['account_no'],
						'##MICR_CODE##' => $selected_data['micr_code']
					);
			
		/*$message = "Hello ".$request->name_of_md.",<br><br>".$mail_message."<br><br>".$deny_notes;
			$datamail = array(
			'from'=> emailsetting()['from_emil'],
			'type'=>'user_account_information_from_admin',
			'subject'=>'Registration Confirmation',
			'message' => $message,
			);*/
			//pr($datamail); die;
			if($request->status==1){
			$datamail = array(
					'from'=> strtr($email_send_approved_client['from'], $emailFindReplace),
					'type'=>'user_account_information_from_admin',
					'subject'=>strtr($email_send_approved_client['subject'], $emailFindReplace),
					'message' => strtr($email_send_approved_client['email_text'], $emailFindReplace),
					);
					
		Mail::to(strtr($email_send_approved_client['to'], $emailFindReplace))->send(new SendMail($datamail));
		foreach(array_filter($cc_mail) as $ccmailsend){
		    Mail::to($ccmailsend)->send(new SendMail($datamail));
		}
		
		$datamail_admin = array(
					'from'=> strtr($email_send_approved_admin['from'], $emailFindReplace),
					'type'=>'user_account_information_from_admin',
					'subject'=>strtr($email_send_approved_admin['subject'], $emailFindReplace),
					'message' => strtr($email_send_approved_admin['email_text'], $emailFindReplace),
					);
		
	          Mail::to(strtr($email_send_approved_admin['to'], $emailFindReplace))->send(new SendMail($datamail_admin));
	          foreach(array_filter($cc_mail) as $ccmailsend){
		    Mail::to($ccmailsend)->send(new SendMail($datamail_admin));
		}
		
			}else{
			   $datamail = array(
					'from'=> strtr($email_send_denied_client['from'], $emailFindReplace),
					'type'=>'user_account_information_from_admin',
					'subject'=>strtr($email_send_denied_client['subject'], $emailFindReplace),
					'message' => strtr($email_send_denied_client['email_text'], $emailFindReplace),
					);
					
		Mail::to(strtr($email_send_denied_client['to'], $emailFindReplace))->send(new SendMail($datamail));
		foreach(array_filter($cc_mail) as $ccmailsend){
		    Mail::to($ccmailsend)->send(new SendMail($datamail));
		}
		
		$datamail_admin = array(
					'from'=> strtr($email_send_denied_admin['from'], $emailFindReplace),
					'type'=>'user_account_information_from_admin',
					'subject'=>strtr($email_send_denied_admin['subject'], $emailFindReplace),
					'message' => strtr($email_send_denied_admin['email_text'], $emailFindReplace),
					);
		
	          Mail::to(strtr($email_send_denied_admin['to'], $emailFindReplace))->send(new SendMail($datamail_admin)); 
	          foreach(array_filter($cc_mail) as $ccmailsend){
		    Mail::to($ccmailsend)->send(new SendMail($datamail_admin));
		}
			}
		if($request->status==1){$status = 'Account has been Approved.';}else if($request->status==2){$status = 'Account has been Denied';}
		Session::flash('success', $status);
		return redirect('admin/buyerlisting');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		checkPermission('edit_supplier');
		$editsupplier = DB::table('supplier_form')->where('_id',$id)->first();
		return view('general.supplier_management.edit_supplier')->with('editsupplier', $editsupplier);
    }
	
    public function update(Request $request, $id) {
		checkPermission('edit_supplier');
		$this->validate($request, [
			'company_name'   => 'required',
			'contact_person' => 'required',
			'state' 	     => 'required',
			'city' 	         => 'required',
			'contact_no'     => 'required',
			'email_id' 	     => 'required',
			'gst' 		     => 'required',
			'bank_name'      => 'required',
		]);
		
		if($request->gst_reg_copy_img !=''){
			$image = $request->file('gst_reg_copy_img');
			$imageName = $image->getClientOriginalName();
			$destinationPath = ('uploads/supplier/gst_reg_copy_images');
			$image->move($destinationPath, $imageName);
		}else{
			$imageName = $request->gst_reg_copy_img_hidden;
		}
		
		if($request->pan_card_copy_img !=''){
			$image1 = $request->file('pan_card_copy_img');
			$imageName1 = $image1->getClientOriginalName();
			$destinationPath = ('uploads/supplier/pan_card_copy_images');
			$image1->move($destinationPath, $imageName1);
		}else{
			$imageName1 = $request->pan_card_copy_img_hidden;
		}
		
		if($request->cncl_chk_copy_img !=''){
			$image2 = $request->file('cncl_chk_copy_img');
			$imageName2 = $image2->getClientOriginalName();
			$destinationPath = ('uploads/supplier/cncl_chk_copy_images');
			$image2->move($destinationPath, $imageName2);
		}else{
			$imageName2 = $request->cncl_chk_copy_img_hidden;
		}
		if($request->company_name!=''){
			$company = trim($request->company_name);
			//$company_name = str_replace(" ","-",$company);
			$company_name = $company;
		}
		
		$updatedata = array(
			"company_name"   => $company_name,
			"contact_person" => $request->contact_person,
			"location"	     => $request->location,
			"address"		 => $request->address,
			"country"    	 => $request->country,
			"state"    		 => $request->state,
			"city" 	  		 => $request->city,
			"contact_no" 	 => $request->contact_no,
			"email_id"		 => $request->email_id,
			"pan_no"   		 => $request->pan_no,
			"gst"   		 => $request->gst,
			"bank_name"   	 => $request->bank_name,
			"account_no"  	 => $request->account_no,
			"ifsc_code"   	 => $request->ifsc_code,
			"branch_name"    => $request->branch_name,
			"payment_terms"  => $request->payment_terms,
			"gst_reg_copy_img"  => $imageName,
			"pan_card_copy_img" => $imageName1,
			"cncl_chk_copy_img" => $imageName2,
			"updated_at"  	 => date('Y-m-d H:i:s')
		);
		/* pr($updatedata); die; */
		DB::table('supplier_form')->where('_id', $id)->update($updatedata);
		Session::flash('success', 'Updated successfully!');
		return redirect('admin/supplierlisting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		checkPermission('delete_supplier');
        if($id!='') {
			DB::table('supplier_form')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Supplier deleted successfully!');
		return redirect()->route('supplierlisting')->with('danger', 'Supplier deleted successfully.'); 
    }
	
	public function searchSupplier(Request $request){
		/* pr($_POST); die;  */
		$search	   = $request->supplier;
		$search_by = $request->supplier_code;
		
		if($request->has('supplier_code')){
			$suppliers = DB::table('supplier_form')->where($request->supplier,'LIKE', '%' .$request->supplier_code. '%')->paginate(20); 
    	}else{
    		$suppliers = DB::table('supplier_form')->paginate(20);
			$suppliers['search']	= $request->supplier;
			$suppliers['search_by'] = $request->supplier_code;
    	}
		return view('general.supplier_management.supplierlisting', compact('suppliers'))->with('search', $search)->with('search_by', $search_by);
	}
	
	public function SupplierImport(Request $request) {
		if ($request->input('submit') != null ){
			$file = $request->file('file');
	 
			$filename  = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath  = $file->getRealPath();
			$fileSize  = $file->getSize();
			$mimeType  = $file->getMimeType();
		
			$valid_extension = array("csv");
			$maxFileSize = 2097152;
			if(in_array(strtolower($extension),$valid_extension)){
				if($fileSize <= $maxFileSize){
					$location = ('uploads/csv'); /* Upload file */
					$file->move($location, $filename); /* Import CSV to Database */
					$filepath = ($location."/".$filename);
					$file = fopen($filepath,"r");  /* Reading file */
					$importData_arr = array();
					$i = 1;
					$flag = true;
					while (($filedata = fgetcsv($file, 1000000, ",")) !== FALSE) {
						$num = count($filedata );
						if($flag) { $flag = false; continue; }
						for ($c=0; $c < $num; $c++) {
							$importData_arr[$i][] = $filedata [$c];
						}
						$i++;
					}
					fclose($file);
					foreach($importData_arr as $importData){
						$insertData[] = array(
							"company_name"   => $importData[0],
							"contact_person" => $importData[1],
							"location" 		 => $importData[2],
							"address"    	 => $importData[3],
							"country"    	 => $importData[4],
							"state" 	  	 => $importData[5],
							"city"  		 => $importData[5],
							"contact_no"	 => $importData[7],
							"email_id" 		 => $importData[8],
							"pan_no" 		 => $importData[9],
							"gst" 			 => $importData[10],
							"bank_name" 	 => $importData[11],
							"account_no" 	 => $importData[12],
							"ifsc_code"  	 => $importData[13],
							"branch_name" 	 => $importData[14],
							"payment_terms"  => $importData[15],
							"created_at" 	 => date('Y-m-d H:i:s'),
							"import"         => 1
						);
					}
					$insert = DB::table('supplier_form')->insert($insertData);
					/* echo "<pre>"; print_r(array($insertData)); die; */
					Session::flash('success','CSV Imported Successfully.');
				}else{
					Session::flash('success','File too large. File must be less than 2MB.');
				}
			}else{
				Session::flash('success','Invalid File Extension.');
			}
		}
		return redirect('admin/supplierlisting');
	}
	
	public function exportSupplier(){
		ini_set('max_execution_time', 3600);
		ini_set('memory_limit','64M');	
	
		$filename = "supplier.csv";
		$fp = fopen('php://output', 'w');
		
		//define column name
		$header = array(
			'company_name',
			'contact_person',
			'location', 
			'address',
			'country',
			'state', 
			'city',
			'contact_no', 
			'email_id', 
			'pan_no', 
			'gst', 
			'bank_name', 
			'account_no', 
			'ifsc_code', 
			'branch_name', 
			'payment_terms'
		);	

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('supplier_form')->select('company_name', 'contact_person', 'location', 'address', 'country', 'state', 'city', 'contact_no', 'email_id', 'pan_no', 'gst', 'bank_name', 'account_no', 'ifsc_code', 'branch_name', 'payment_terms')->orderBy('created_at', 'DESC')->get()->toarray();

		$i=0;
		foreach($data as $row){ 
			$i++;
			
			$rowData = array(
				$row['company_name'],
				$row['contact_person'],
				$row['location'], 
				$row['address'], 
				$row['country'], 
				$row['state'], 
				$row['city'], 
				$row['contact_no'], 
				$row['email_id'],
				$row['pan_no'],
				$row['gst'],
				$row['bank_name'],
				$row['account_no'],
				$row['ifsc_code'],
				$row['branch_name'],
				$row['payment_terms']
			);	
			/* if($i==2){
				pr($rowData); die;
			} */
			fputcsv($fp, $rowData);			
		}
		exit;
	}
}
