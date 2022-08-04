<?php

namespace App\Http\Controllers\Vendor;
use App\Models\Form;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use DB;
use Session;
use Route;
error_reporting(0);
class VendorController extends Controller
{
    
    public function index(Request $request) {
        $vendor_list = DB::table('vendors')->orderBy('id', 'DESC')->paginate(100);
        //pr($vendor_list); die;
        $tbl_column = '';
        $i_name = '';
        if(!empty($_POST))
        {
            $query = DB::table('vendors');
            if($request->tbl_column!=''){
            $query->where($request->tbl_column, 'LIKE','%'.$request->i_name.'%');
            }
           
            $vendor_list =$query->orderBy('id', 'DESC')->paginate(100);
            $tbl_column = $request->tbl_column;
            $i_name = $request->i_name;
            
        }
		return view('vendor.vendorlist',compact('vendor_list','tbl_column','i_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = DB::table('segments')
				->select('*')
				->get()->toArray();
		$segments = json_decode(json_encode($object), true);

        $object_customer_categories = DB::table('customer_categories')
				->select('*')
				->get()->toArray();
		$customer_categories = json_decode(json_encode($object_customer_categories), true);

        $object_department = DB::table('tbl_department')
        ->select('*')
        ->orderBy('department_name','ASC')
        ->get()->toArray();
        $departments = json_decode(json_encode($object_department), true);

        $object_designation = DB::table('tbl_designation')
        ->select('*')
        ->orderBy('designation','ASC')
        ->get()->toArray();
        $designations = json_decode(json_encode($object_designation), true);

        $object_process = DB::table('processes')
        ->select('*')
        ->orderBy('process','ASC')
        ->get()->toArray();
        $processes = json_decode(json_encode($object_process), true);

        
        $object_payment_terms = DB::table('payment_terms')
        ->select('*')
        ->orderBy('payment_terms','ASC')
        ->get()->toArray();
        $payment_terms = json_decode(json_encode($object_payment_terms), true);
        

		return view('vendor.add_vendor',['segments'=>$segments,'customer_categories'=>$customer_categories,'departments'=>$departments,'designations'=>$designations,'processes'=>$processes,'payment_terms'=>$payment_terms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		
		//pr($request->all()); die;

        //pr($request->create_departments); die;
        $form_data_array = array(
            'block_unblock'         => $request->block_unblock,
            'register_type'         => $request->register_type,
            'medical_type'          => $request->medical_type,
            'company_name'          => $request->company_name,
            'h_area'                => $request->h_area,
            'h_landmark'            => $request->h_landmark,
            'h_country'             => $request->h_country,
            'h_city'                => $request->h_city,
            'h_state'               => $request->h_state,
            'h_pin_code'            => $request->h_pin_code,
            'same_billing'          => $request->same_billing,
            'b_area'                => $request->b_area,
            'b_landmark'            => $request->b_landmark,
            'b_country'             => $request->b_country,
            'b_city'                => $request->b_city,
            'b_state'               => $request->b_state,
            'b_pin_code'            => $request->b_pin_code,
            'same_shipping'         => $request->same_shipping,
            's_area'                => $request->s_area,
            's_landmark'            => $request->s_landmark,
            's_country'             => $request->s_country,
            's_city'                => $request->s_city,
            's_state'               => $request->s_state,
            's_pin_code'            => $request->s_pin_code,
            's_google_location'     => $request->s_google_location,
            'email'                 => $request->email,
            'website'               => $request->website,
            'contact_number'        => $request->contact_number,
            'directors_name'        => $request->directors_name,
            'contact_no'            => $request->contact_no,
            '2nd_directors_name'    => $_POST['2nd_directors_name'],
            '2nd_contact_no'        => $_POST['2nd_contact_no'],
            'responsible_emp'       => $request->responsible_emp,
            'responsible_contact_no'=> $request->responsible_contact_no,
            'GST_number'            => $request->GST_number,
            'PAN_number'            => $request->PAN_number,
            'TAN_number'            => $request->TAN_number,
            'advance_required'      => $request->advance_required,
            'credit_limit'          => $request->credit_limit,
            'credit_days'           => $request->credit_days,
            'state_code'            => $request->state_code,
            'brand'                 => $request->brand[0],
            'drug_license_number'   => $request->drug_license_number,
            'bank_account_details'      => $request->bank_account_details,
            'bank_account_name'         => $request->bank_account_name,
            'IFSC_code'                 => $request->IFSC_code
        );
        $last_id = DB::table('vendors')->insertGetId($form_data_array);

        if(!empty($request->create_departments) && !empty($request->create_departments[0]['department_name']))
        {
            foreach($request->create_departments as $dept)
            {
                $array_dept = array(
                    'department_name' => $dept['department_name'],
                    'c_id'            => $last_id,
                );
                $last_id_dept = DB::table('tbl_department_name_vendor')->insertGetId($array_dept);
                if(!empty($dept['departments_repeat']))
                {
                    foreach($dept['departments_repeat'] as $dept_rep){
                        $array_dept_rep = array(
                            'c_id'          => $last_id,
                            'd_id'          => $last_id_dept,
                            'name'          => $dept_rep['name'],
                            'designation'   => $dept_rep['designation'],
                            'mobile_no'     => $dept_rep['mobile_no'],
                            'email'         => $dept_rep['email'],
                            'whatsapp_no'   => $dept_rep['whatsapp_no']
                        );
                    DB::table('tbl_department_name_data_vendor')->insertGetId($array_dept_rep);
                    }
                }
            }
        }

        if(!empty($request->create_depot_address) && !empty($request->create_depot_address[0]['create_depot_address']))
        {
            foreach($request->create_depot_address as $depot)
            {
                $array_depot = array(
                    'create_depot_address'      => $depot['create_depot_address'],
                    'c_id'                      => $last_id,
                    'd_area'                    => $depot['d_area'],
                    'd_landmark'                => $depot['d_landmark'],
                    'd_country'                 => $depot['d_country'],
                    'd_city'                    => $depot['d_city'],
                    'd_state'                   => $depot['d_state'],
                    'd_pin_code'                => $depot['d_pin_code']
                );
                DB::table('tbl_deport_address_vendor')->insertGetId($array_depot);
                
            }
        }

        if(!empty($request->create_customer_item_sap) && !empty($request->create_customer_item_sap[0]['create_customer_brand']))
        {
            foreach($request->create_customer_item_sap as $sap)
            {
                $array_sap = array(
                    'create_customer_brand'     => $sap['create_customer_brand'],
                    'c_id'                      => $last_id,
                    'create_customer_price'     => $sap['create_customer_price']
                );
                DB::table('tbl_customer_item_sap')->insertGetId($array_sap);
                
            }
        }
		//die;
        return redirect()->route('vendorlist')->with('success', 'Vendor added successfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		$vendor = Vendor::find($id);
        $object_department_name_vendor = DB::table('tbl_department_name_vendor')->where('c_id',$vendor['id'])->get()->toArray();
        $department_name = json_decode(json_encode($object_department_name_vendor), true);

        $object = DB::table('segments')
				->select('*')
				->get()->toArray();
		$segments = json_decode(json_encode($object), true);

        $object_customer_categories = DB::table('customer_categories')
				->select('*')
				->get()->toArray();
		$customer_categories = json_decode(json_encode($object_customer_categories), true);

        $object_department = DB::table('tbl_department')
        ->select('*')
        ->orderBy('department_name','ASC')
        ->get()->toArray();
        $departments = json_decode(json_encode($object_department), true);

        $object_designation = DB::table('tbl_designation')
        ->select('*')
        ->orderBy('designation','ASC')
        ->get()->toArray();
        $designations = json_decode(json_encode($object_designation), true);

        $object_process = DB::table('processes')
        ->select('*')
        ->orderBy('process','ASC')
        ->get()->toArray();
        $processes = json_decode(json_encode($object_process), true);

        
        $object_payment_terms = DB::table('payment_terms')
        ->select('*')
        ->orderBy('payment_terms','ASC')
        ->get()->toArray();
        $payment_terms = json_decode(json_encode($object_payment_terms), true);

        $depot_address = DB::table('tbl_deport_address_vendor')->where('c_id',$id)
        ->get()->toArray(); 
        $depot_address_data = json_decode(json_encode($depot_address), true);

        $customer_item_sap = DB::table('tbl_customer_item_sap')->where('c_id',$id)
        ->get()->toArray(); 
        $customer_item_sap_data = json_decode(json_encode($customer_item_sap), true);

        $object_brand = DB::table('brand')
				->select('*')
				->get()->toArray();
		$brand_dataUnit = json_decode(json_encode($object_brand), true);
        //pr($brand_dataUnit); die;
        if(!empty($_POST))
        {
            //pr($request->all()); die;
            $form_data_array = array(
            'block_unblock'         => $request->block_unblock,
            'register_type'         => $request->register_type,
            'medical_type'          => $request->medical_type,
            'company_name'          => $request->company_name,
            'h_area'                => $request->h_area,
            'h_landmark'            => $request->h_landmark,
            'h_country'             => $request->h_country,
            'h_city'                => $request->h_city,
            'h_state'               => $request->h_state,
            'h_pin_code'            => $request->h_pin_code,
            'same_billing'          => $request->same_billing,
            'b_area'                => $request->b_area,
            'b_landmark'            => $request->b_landmark,
            'b_country'             => $request->b_country,
            'b_city'                => $request->b_city,
            'b_state'               => $request->b_state,
            'b_pin_code'            => $request->b_pin_code,
            'same_shipping'         => $request->same_shipping,
            's_area'                => $request->s_area,
            's_landmark'            => $request->s_landmark,
            's_country'             => $request->s_country,
            's_city'                => $request->s_city,
            's_state'               => $request->s_state,
            's_pin_code'            => $request->s_pin_code,
            's_google_location'     => $request->s_google_location,
            'email'                 => $request->email,
            'website'               => $request->website,
            'contact_number'        => $request->contact_number,
            'directors_name'        => $request->directors_name,
            'contact_no'            => $request->contact_no,
            '2nd_directors_name'    => $_POST['2nd_directors_name'],
            '2nd_contact_no'        => $_POST['2nd_contact_no'],
            'responsible_emp'       => $request->responsible_emp,
            'responsible_contact_no'=> $request->responsible_contact_no,
            'GST_number'            => $request->GST_number,
            'PAN_number'            => $request->PAN_number,
            'TAN_number'            => $request->TAN_number,
            'advance_required'      => $request->advance_required,
            'credit_limit'          => $request->credit_limit,
            'credit_days'           => $request->credit_days,
            'state_code'            => $request->state_code,
            'brand'                 => $request->brand[0],
            'drug_license_number'   => $request->drug_license_number,
            'bank_account_details'      => $request->bank_account_details,
            'bank_account_name'         => $request->bank_account_name,
            'IFSC_code'                 => $request->IFSC_code
            );
            DB::table('vendors')->where('id',$id)->update($form_data_array);


            if(!empty($request->create_depot_address) && !empty($request->create_depot_address[0]['create_depot_address']))
            {
            foreach($request->create_depot_address as $depot)
            {
            $array_depot = array(
            'create_depot_address'      => $depot['create_depot_address'],
            'c_id'                      => $id,
            'd_area'                    => $depot['d_area'],
            'd_landmark'                => $depot['d_landmark'],
            'd_country'                 => $depot['d_country'],
            'd_city'                    => $depot['d_city'],
            'd_state'                   => $depot['d_state'],
            'd_pin_code'                => $depot['d_pin_code']
            );
            if(!empty($depot['depot_id'])){
                DB::table('tbl_deport_address_vendor')->where('id',$dept['depot_id'])->update($array_depot);    
                }else{
                $last_id_dept = DB::table('tbl_deport_address_vendor')->insertGetId($array_depot);
                }
            

            }
            }

            if(!empty($request->create_customer_item_sap) && !empty($request->create_customer_item_sap[0]['create_customer_brand']))
            {
                foreach($request->create_customer_item_sap as $sap)
                {
                    $array_sap = array(
                        'create_customer_brand'     => $sap['create_customer_brand'],
                        'c_id'                      => $id,
                        'create_customer_price'     => $sap['create_customer_price']
                    );

                   
                    if(!empty($sap['customer_brand_id'])){
                        DB::table('tbl_customer_item_sap')->where('id',$sap['customer_brand_id'])->update($array_sap);    
                        }else{
                        $last_id_dept = DB::table('tbl_customer_item_sap')->insertGetId($array_sap);
                        }
                    
                }
            }
    
            if(!empty($request->create_departments) && !empty($request->create_departments[0]['department_name']))
            {
                foreach($request->create_departments as $dept)
                {
                    $array_dept = array(
                        'department_name' => $dept['department_name'],
                        'c_id'          => $id,
                    );
                    if(!empty($dept['dept_name_id'])){
                    DB::table('tbl_department_name_vendor')->where('id',$dept['dept_name_id'])->update($array_dept);    
                    }else{
                    $last_id_dept = DB::table('tbl_department_name_vendor')->insertGetId($array_dept);
                    }
                    if(!empty($dept['departments_repeat']))
                    {
                        foreach($dept['departments_repeat'] as $dept_rep){
                            if(!empty($dept['dept_name_id'])){
                                $last_id_dept_u = $dept['dept_name_id'];
                            }else{
                                $last_id_dept_u = $last_id_dept;
                            }
                            $array_dept_rep = array(
                                'c_id'          => $id,
                                'd_id'          => $last_id_dept_u,
                                'name'          => $dept_rep['name'],
                                'designation'   => $dept_rep['designation'],
                                'mobile_no'     => $dept_rep['mobile_no'],
                                'email'         => $dept_rep['email'],
                                'whatsapp_no'   => $dept_rep['whatsapp_no']
                            );
                            if(!empty($dept_rep['dept_name_data_id']))
                            {
                            DB::table('tbl_department_name_data_vendor')->where('id',$dept_rep['dept_name_data_id'])->update($array_dept_rep);
                            }else{
                            DB::table('tbl_department_name_data_vendor')->insertGetId($array_dept_rep);
                            }
                        }
                    }
                }
            }
            //die;
            return redirect()->route('vendorlist')->with('success', 'Vendor updated successfully.');
        }

        return view('vendor.edit_vendor', compact('user', 'id', 'vendor', 'department_name','segments','customer_categories','departments','designations','processes','payment_terms','depot_address_data','customer_item_sap_data','brand_dataUnit'));
    }
	
	

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $vendor = Vendor::find($id);
        $vendor->delete();
        DB::table('tbl_department_name_vendor')->where('c_id',$id)->delete();
        DB::table('tbl_department_name_data_vendor')->where('c_id',$id)->delete();
        DB::table('tbl_customer_item_sap')->where('c_id',$id)->delete();
        DB::table('tbl_deport_address_vendor')->where('c_id',$id)->delete();
        return redirect('admin/vendor-list')->with('success','Vendor has been deleted sucessfully!');
    }

    public function export_data_master_vendor(Request $request, $type)
    {
    if($type==0){
    $object = DB::table('vendors')->orderBy('id','DESC')->get()->toArray();
    }else{
    $object = DB::table('vendors')->orderBy('id','DESC')->limit(intval($type))->get()->toArray();    
    }
    $array = json_decode(json_encode($object), true);
    $filename = "vendor.csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['id'],$d['created_on']);
	  array_push($arr,$d);
	  }
      $header = array('block_unblock','register_type','medical_type','company_name','h_area','h_landmark','h_country','h_city','h_state','h_pin_code',
	    'b_area','b_landmark','b_country','b_city','b_state','b_pin_code','s_area','s_landmark','s_country','s_city','s_state','s_pin_code',
	    's_google_location','email','website','contact_number','directors_name','contact_no','2nd_directors_name','2nd_contact_no','responsible_emp','responsible_contact_no',
	    'GST_number','PAN_number','TAN_number','advance_required','credit_limit','credit_days','state_code','brand','drug_license_number',
	    'quotation_invoice_limit','bank_account_details','bank_account_name','IFSC_code');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $ar = array();
        foreach($array as $data){
            $d1['block_unblock']= $data['block_unblock'];
            $d1['register_type']= $data['register_type'];
            $d1['medical_type']= $data['medical_type'];
            $d1['company_name']= $data['company_name'];
            $d1['h_area']= $data['h_area'];
            $d1['h_landmark']= $data['h_landmark'];
            $d1['h_country']= $data['h_country'];
            $d1['h_city']= $data['h_city'];
            $d1['h_state']= $data['h_state'];
            $d1['h_pin_code']= $data['h_pin_code'];
            $d1['b_area']= $data['b_area'];
            $d1['b_landmark']= $data['b_landmark'];
            $d1['b_country']= $data['b_country'];
            $d1['b_city']= $data['b_city'];
            $d1['b_state']= $data['b_state'];
            $d1['b_pin_code']= $data['b_pin_code'];
            $d1['s_area']= $data['s_area'];
            $d1['s_landmark']= $data['s_landmark'];
            $d1['s_country']= $data['s_country'];
            $d1['s_city']= $data['s_city'];
            $d1['s_state']= $data['s_state'];
            $d1['s_pin_code']= $data['s_pin_code'];
            $d1['s_google_location']= $data['s_google_location'];
            $d1['email']= $data['email'];
            $d1['website']= $data['website'];
            $d1['contact_number']= $data['contact_number'];
            $d1['directors_name']= $data['directors_name'];
            $d1['contact_no']= $data['contact_no'];
            $d1['2nd_directors_name']= $data['2nd_directors_name'];
            $d1['2nd_contact_no']= $data['2nd_contact_no'];
            $d1['responsible_emp']= $data['responsible_emp'];
            $d1['responsible_contact_no']= $data['responsible_contact_no'];
            $d1['GST_number']= $data['GST_number'];
            $d1['PAN_number']= $data['PAN_number'];
            $d1['TAN_number']= $data['TAN_number'];
            $d1['advance_required']= $data['advance_required'];
            $d1['credit_limit']= $data['credit_limit'];
            $d1['credit_days']= $data['credit_days'];
            $d1['state_code']= $data['state_code'];
            $d1['brand']= $data['brand'];
            $d1['drug_license_number']= $data['drug_license_number'];
            $d1['quotation_invoice_limit']= $data['quotation_invoice_limit'];
            $d1['bank_account_details']= $data['bank_account_details'];
            $d1['bank_account_name']= $data['bank_account_name'];
            $d1['IFSC_code']= $data['IFSC_code'];
            fputcsv($fp, $d1);
        }
    }

    public function import_insert_data_vendor($data,$table_name,$keys)
    {
        //pr($data); 
       // pr($keys);
       //die;
        $arr = array();
        $msg = array();
        $update_array = array();
        $insert_array = array();
        $d1 = array();
        foreach($data as $key=>$datas){
        $datas_array = @array_combine($keys,$datas);
        array_push($arr,$datas_array);
        }   
        //pr($arr); die;
        if($table_name=='vendors')
        {
         foreach($arr as $ar){
         $datacheck = DB::table('vendors')->where('company_name', '=', $ar['company_name'])->get()->count();
         if($datacheck==0){
             
          $d1['block_unblock']                    = checkNullDatas(utf8_encode($ar['block_unblock']));
          $d1['register_type']                    = checkNullDatas(utf8_encode($ar['register_type']));
          $d1['medical_type']                     = checkNullDatas(utf8_encode($ar['medical_type']));
          $d1['company_name']                     = checkNullDatas(utf8_encode($ar['company_name']));
          $d1['h_area']                           = checkNullDatas(utf8_encode($ar['h_area']));
          $d1['h_landmark']                       = checkNullDatas(utf8_encode($ar['h_landmark']));
          $d1['h_country']                        = checkNullDatas(utf8_encode($ar['h_country']));
          $d1['h_city']                           = checkNullDatas(utf8_encode($ar['h_city']));
          $d1['h_state']                          = checkNullDatas(utf8_encode($ar['h_state']));
          $d1['h_pin_code']                       = checkNullDatas(utf8_encode($ar['h_pin_code']));
          $d1['b_area']                           = checkNullDatas(utf8_encode($ar['b_area']));
          $d1['b_landmark']                       = checkNullDatas(utf8_encode($ar['b_landmark']));
          $d1['b_country']                        = checkNullDatas(utf8_encode($ar['b_country']));
          $d1['b_city']                           = checkNullDatas(utf8_encode($ar['b_city']));
          $d1['b_state']                          = checkNullDatas(utf8_encode($ar['b_state']));
          $d1['b_pin_code']                       = checkNullDatas(utf8_encode($ar['b_pin_code']));
          $d1['s_area']                           = checkNullDatas(utf8_encode($ar['s_area']));
          $d1['s_landmark']                       = checkNullDatas(utf8_encode($ar['s_landmark']));
          $d1['s_country']                        = checkNullDatas(utf8_encode($ar['s_country']));
          $d1['s_city']                           = checkNullDatas(utf8_encode($ar['s_city']));
          $d1['s_state']                          = checkNullDatas(utf8_encode($ar['s_state']));
          $d1['s_pin_code']                       = checkNullDatas(utf8_encode($ar['s_pin_code']));
          $d1['s_google_location']                = checkNullDatas(utf8_encode($ar['s_google_location']));
          $d1['email']                            = checkNullDatas(utf8_encode($ar['email']));
          $d1['website']                          = checkNullDatas(utf8_encode($ar['website']));
          $d1['contact_number']                   = checkNullDatas(utf8_encode($ar['contact_number']));
          $d1['directors_name']                   = checkNullDatas(utf8_encode($ar['directors_name']));
          $d1['contact_no']                       = checkNullDatas(utf8_encode($ar['contact_no']));
          $d1['2nd_directors_name']               = checkNullDatas(utf8_encode($ar['2nd_directors_name']));
          $d1['2nd_contact_no']                   = checkNullDatas(utf8_encode($ar['2nd_contact_no']));
          $d1['responsible_emp']                  = checkNullDatas(utf8_encode($ar['responsible_emp']));
          $d1['responsible_contact_no']           = checkNullDatas(utf8_encode($ar['responsible_contact_no']));
          $d1['GST_number']                       = checkNullDatas(utf8_encode($ar['GST_number']));
          $d1['PAN_number']                       = checkNullDatas(utf8_encode($ar['PAN_number']));
          $d1['TAN_number']                       = checkNullDatas(utf8_encode($ar['TAN_number']));
          $d1['advance_required']                 = checkNullDatas(utf8_encode($ar['advance_required']));
          $d1['credit_limit']                     = checkNullDatas(utf8_encode($ar['credit_limit']));
          $d1['credit_days']                      = checkNullDatas(utf8_encode($ar['credit_days']));
          $d1['state_code']                       = checkNullDatas(utf8_encode($ar['state_code']));
          $d1['brand']                            = checkNullDatas(utf8_encode($ar['brand']));
          $d1['drug_license_number']              = checkNullDatas(utf8_encode($ar['drug_license_number']));
          $d1['quotation_invoice_limit']          = checkNullDatas(utf8_encode($ar['quotation_invoice_limit']));
          $d1['bank_account_details']             = checkNullDatas(utf8_encode($ar['bank_account_details']));
          $d1['bank_account_name']                = checkNullDatas(utf8_encode($ar['bank_account_name']));
          $d1['IFSC_code']                        = checkNullDatas(utf8_encode($ar['IFSC_code']));
         
          //pr($d1); die;
            DB::table('vendors')->insertGetId($d1);
            
         }
         }
        }
       // pr($arr); die;
       if($table_name!='items'){
        $msg['msg'] = 'Data imported successfully.';   
        }else{
        if(count($update_array)>0){
        $msg_update_array = count($update_array).' Records ('.implode(', ',$update_array).') already exist.';
        }else{
        $msg_update_array = '';    
        }
        $msg['msg'] = count($insert_array).' Records imported successfully.'. $msg_update_array;
        }
        
        return $msg;
    }

    public function import_data_master_vendor(Request $request)
    {
        if(isset($_POST['importSubmit'])){
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            if(!empty($_FILES['result_file']['name']) && in_array($_FILES['result_file']['type'], $csvMimes)){
                if(is_uploaded_file($_FILES['result_file']['tmp_name'])){
                          //header('Content-Type: text/html; charset=UTF-8');
                          $csv = array_map("str_getcsv", file($_FILES['result_file']['tmp_name'],FILE_SKIP_EMPTY_LINES));
                          //pr($csv); die;
                          $file = fopen($_FILES['result_file']['name'], "r");
                          $keys = array_shift($csv);
                          $data = $this->import_insert_data_vendor($csv,'vendors',$keys);
                          //pr($data); die;
                          return redirect()->to(url('admin/vendor-list/'))->with('success', $data['msg']);
                      }
                   }
                }
    }

    public function ajax_vendor_status_update(Request $request)
    {
      $update = array('block_unblock'=>$request->status);
      DB::table('vendors')->where('id',$request->id)->update($update);
      echo 1;
    }

}
   