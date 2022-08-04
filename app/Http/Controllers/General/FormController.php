<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use Excel;
use PDF;
use Auth;
use MyApp\VendorProductsImport;
use App\AgentsExport\ListExport;
use App\Netrate;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Route;
error_reporting(0);
@ini_set('memory_limit','12048M');
class FormController extends Controller
{
	function __construct(){
		//$this->agentmodel = new AgentModel();
	}
	
    public function index() {
		$forms = DB::table('tbl_form')->orderBy('_id', 'DESC')->get();
		return view('general.form_management.formlist')->with('forms', $forms);
    }	
    
    public function viewform(Request $request, $id)
    {
    $forms = DB::table('tbl_form')->where('_id', $id)->get();
    //pr($forms); die;
    return view('general.form_management.viewform',['forms'=>$forms]);
    }
    
    public function formvalidate(Request $request)
    {
    $k = getModuleList('user');
    //pr($k); die;
    
    return view('general.form_management.formvalidate');   
    }
    
    public function formupdate(Request $request, $form_id)
    {  
        //pr($request->all()); die;
        $forms = DB::table('tbl_form')->where('_id', $form_id)->first();
        //pr($forms); die;
        $image = array();
        if(!empty($request->file())) {
            foreach($request->file() as $key=>$files)
            {
            $fileName = time().'_'.$request->$key->getClientOriginalName();
            request()->$key->move(public_path('uploads/images'), $fileName);
            $image[$key]=$fileName;
            }
            
        }
        
        $data = array_merge($_POST,$image);
        //pr($data); die;
        postInsertData($data,$_POST['table_name']);
        return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data added successfully.');
        
    }

    public function createform(Request $request) {
		return view('general.form_management.createform');
    }
    
    public function createformnew(Request $request) {
		return view('general.form_management.createformnew');
    }

    public function store(Request $request) {
	   if(!empty($request->row)){
			$rows = array();
			foreach($request->row as $row){
				$rows[] = $row; 
			}
		}
		
		//pr($rows); die;
		
       $data = array(
	   'form_name' 		=> $request->form_name,
	   'module_name'	=> $request->module_name,
	   'table_name'     => $request->table_name,
	   'import'         => $request->import,
	   'export'         => $request->export_data,
	   'row'            => $rows
	   );
	   $insert = DB::table('tbl_form')->insert(array($data));
	   return redirect()->route('formlist')->with('success', 'Form added successfully.');
    }
	
    
    public function listdata(Request $request, $id) {
		die();
        userPermission(Route::current()->getName(),'listdata/'.$id);
        $forms = DB::table('tbl_form')->where('_id', $id)->get();
        $table_name = $forms[0]['table_name'];
        if(!empty($_POST)){
        //pr($request->all()); die;
        if($forms[0]['_id']=='619c7679d0490455f258a8f2'){
            $query = DB::table($table_name)->where('user_role','!=','1');
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
            $query->where('is_verified', 'LIKE', checkChar($request->is_verified));  
            $is_verified =$request->is_verified; 
            }
            $data_list =$query->orderBy('_id', 'DESC')->paginate(50);
         //pr($data_list); die;  
        }else{
        $data_list = DB::table($table_name)->where('user_role','!=','1')->where($request->tbl_column, 'LIKE', checkChar($request->i_name))->orderBy('_id', 'DESC')->paginate(50);
        $tbl_column = $request->tbl_column;
        $i_name = $request->i_name;
        }
        }else{
        if($forms[0]['_id']=='617f8983d0490476da34a012'){
        $data_list = DB::table($table_name)->where('user_role','!=','1')->orderBy('_id', 'DESC')->paginate(50);    
        }else{
        $data_list = DB::table($table_name)->where('user_role','!=','1')->orderBy('_id', 'DESC')->paginate(100);
        }
        $tbl_column = '';
        $i_name = '';
        $vendor_sku ='';
        $name ='';
        $grade = '';
        $brand = '';
        $packing_name ='';
        $hsn_code = '';
        $is_verified = '';
        $synonyms = '';
        }
        //pr($data_list); die;
        $module_data = getModuleList($forms[0]['module_name']);
		if(!empty($module_data)){
			$finalArr= array();
			foreach($module_data as $row){
				if(isset($row['show_list']) &&  $row['show_list']==1){
					$finalArr[$row['field_name']] = $row['label_name'];					
				}
			}
		}
		//pr($forms); die;
        /* pr($module_data);
        pr($user_data); die; */
        return view('general.form_management.viewformlist',['datalist'=>$data_list, 'finalArr'=>$finalArr,'forms'=>$forms,'tbl_column'=>$tbl_column,'i_name'=>$i_name,'vendor_sku'=>$vendor_sku,'name'=>$name,'grade'=>$grade,'brand'=>$brand,'packing_name'=>$packing_name,'hsn_code'=>$hsn_code,'is_verified'=>$is_verified,'synonyms'=>$synonyms]);
    }
    
    public function userassign($id)
	{
	$users_assign = DB::table('roles')->whereNotNull('role_category','<>','')->orderBy('order','asc')->get();
	//pr($users_assign); die;
	$user_data = User::where('_id',$id)->first();
	$added_modules = $user_data->userrole;
	//pr($added_modules); die;
	return view('user.user_assign',['users_assign'=>$users_assign,'user_id'=>$id,'added_modules'=>$added_modules]);
	}
	
	public function userassignupdate(Request $request)
	{
	 //pr($request->all()); die;
	 $data = array('userrole'=>$request->userrole);
	 User::where('_id',$request->user_id)->update($data);
	 return redirect('admin/userassign/'.$request->user_id)->with('success', 'User role update successfully');
	}
    
    public function editdata(Request $request, $table, $id, $form_id)
    {
     //userPermission(Route::current()->getName(),'editdata/'.$table);
     $user_edit_details = DB::table($table)->where('_id',$id)->first(); 
     //echo $user_edit_details["create_departments"][0]["department_name"];die;
     //pr($user_edit_details); die;
     $forms = DB::table('tbl_form')->where('_id', $form_id)->get();
     $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->paginate(10);
     $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->count();
     //pr($forms); die;
     return view('general.form_management.vieweditdatalist',['user_edit_details'=>$user_edit_details, 'forms'=>$forms,'table'=>$table,'id'=>$id,'form_id'=>$form_id,'datalist'=>$itemlist,'itemlist_count'=>$itemlist_count]);
    }
    
    public function edit_formupdate(Request $request, $id, $form_id)
    {
        //pr($request->all());die;
        $forms = DB::table('tbl_form')->where('_id', $form_id)->get();
        $image = array();
        if(!empty($request->file())) {
            foreach($request->file() as $key=>$files)
            {
            $fileName = time().'_'.$request->$key->getClientOriginalName();
            request()->$key->move(public_path('uploads/images'), $fileName);
            $image[$key]=$fileName;
            }
            
        }
        
        $data = array_merge($_POST,$image);
        if($form_id=='617fe586d049045cf838a502')
        {
        brandUpdate($data,$id);
        }
        if($form_id=='617bdabdd0490401e7794262')
        {
        gradeUpdate($data,$id);
        }
        if($form_id=='617bbfc3d049044bde0079b2')
        {
        HSNcodeUpdate($data,$id);
        }
        postUpdateData($data,$forms[0]['table_name'],$id);
        return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data updated successfully.');
    }
    
    public function formedit($id) {
        $forms = DB::table('tbl_form')->where('_id', $id)->get();
		$tbldataCount = DB::table($forms[0]['table_name'])->count();
        //pr($forms);die;
        return view('general.form_management.vieweditform',['forms'=>$forms,'tbldataCount'=>$tbldataCount]);
    }
	
	public function formpreview($id) {
        $formpreview = DB::table('tbl_form')->where('_id',$id)->first();
		return view('general.form_management.previewform')->with('formpreview', $formpreview);
    }
	
	public function adddata($id) {
	    userPermission(Route::current()->getName(),'adddata/'.$id);
        $adddata = DB::table('tbl_form')->where('_id',$id)->first();
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->count();
        //pr($itemlist); die;
		return view('general.form_management.adddata',['adddata'=>$adddata,'datalist'=>$itemlist,'itemlist_count'=>$itemlist_count]);
    }
	
// 	public function listdata($id) {
//         $users= DB::table('user')->where('_id',$id)->get();
// 		$moduleName= 'user';
// 		$show_list = getModuleList($moduleName);
// 		//pr($show_list); die;
// 		return view('general.form_management.listdata',compact('users','show_list'));
//     }
	
    public function formupdate_data(Request $request, $id) {
        //pr($request->all()); die;
        if(!empty($request->row)){
			$rows = array();
			foreach($request->row as $row){
				$rows[] = $row; 
			}
		}
		
		//pr($rows); die;
		
       $data = array(
	   'form_name' 		=> $request->form_name,
	   'module_name'	=> $request->module_name,
	   'table_name'     => $request->table_name,
	   'import'         => $request->import,
	   'export'         => $request->export_data,
	   'row'            => $rows
	   );
	   $update = DB::table('tbl_form')->where('_id',$id)->update($data);
	   return redirect()->route('formlist')->with('success', 'Form updated successfully.');
    }
	
    public function destroy($id) {
        if($id!='') {
			DB::table('tbl_form')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Form deleted successfully!');
		return redirect('admin/formlist');
    }
    
    public function deletedata(Request $request, $table, $id, $form_id)
    {
    userPermission(Route::current()->getName(),'deletedata/'.$table);
    if($id!='') 
    {
	DB::table($table)->where('_id', '=', $id)->delete();
	}
    return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data deleted successfully.');   
    }
    
    public function conditional(Request $request)
    {
        //pr($request->all()); die;
        $con_val = $request->con_val;
        if($con_val!=''){
        $data = DB::table('tbl_form')->select('row')->where('module_name',$request->module_name)->get();
        $form_data = array();
        //pr($data); die;
        foreach($data[0]['row'] as $key=>$d)
        {
        if($d['field_name']==$con_val){
        array_push($form_data,$d);
        }
        //pr($d);
        }
		//return pr($form_data);
		
		$html = '<select class="form-control sub_cond-'.$request->id.'" id="'.$request->id.'" name="row['.$request->id.'][sub_cond]"><option value="">--Select--</option>';
			if(!empty($form_data[0]['field_type_value'])){
				$optionVal = explode("\r\n", $form_data[0]['field_type_value']);
				//pr($optionVal);
				
				if(!empty($optionVal)){
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($value) && $value == $option[0])? 'selected=""':'';
						$html .= '<option value="'.$option[0].'" '.$selected.'>'.$option[1].'</option>';
					}					
				}
			}
			$html .= '</select>';
		return $html;
        }
    }
	
	public function AgentImport(Request $request) {    
	    /* echo "Hellllo"; die; */
		if ($request->input('submit') != null ){
			$file = $request->file('file');
			// File Details 
			$filename  = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath  = $file->getRealPath();
			$fileSize  = $file->getSize();
			$mimeType  = $file->getMimeType();
			
			// Valid File Extensions
			$valid_extension = array("csv");
			// 2MB in Bytes
			$maxFileSize = 2097152; 
			// Check file extension
			if(in_array(strtolower($extension),$valid_extension)){
				// Check file size
				if($fileSize <= $maxFileSize){
					// File upload location
					$location = 'uploads'; // Upload file
					$file->move($location,$filename); // Import CSV to Database
					$filepath = public_path($location."/".$filename);
					$file = fopen($filepath,"r");  // Reading file
					$importData_arr = array();
					$i = 1;
					while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
						$num = count($filedata );
						for ($c=0; $c < $num; $c++) {
							$importData_arr[$i][] = $filedata [$c];
						}
						$i++;
					}
					fclose($file);
					// Insert to MySQL database
					foreach($importData_arr as $importData){
						/* echo "<pre>"; print_r($importData[3]); die; */
						$insertData = array(
							"name"  => $importData[0],
							"email" => $importData[1],
							"phone" => $importData[2],
							"created_at"  => date('Y-m-d H:i:s')
						);
						/* Page::insertData($insertData); */
					}
					$insert = DB::table('agents')->insert(array($insertData));
					/* echo "<pre>"; print_r($insertData); die; */
					Session::flash('message','Import Successful.');
				}else{
					Session::flash('message','File too large. File must be less than 2MB.');
				}
			}else{
				Session::flash('message','Invalid File Extension.');
			}
		}
		return redirect('admin/agentlist');
	}
	
	public function emailchecker(Request $request)
	{
	     //echo 1;
	     //pr($request->all()); die;
	    if($request->email!='')
	    {
	     $data = DB::table($request->table_name)->select('email')->where('email', $request->email)->first(); 
	     $email = $data['email'];
	     if($email==''){
	         echo 'true';
	     }else{
	         echo 'false';
	     }
	    }
	}
	
	public function image_delete(Request $req)
	{
	   DB::table($req->tbl_name)->where('id', $req->id)->update(array($req->field_name=>'')); 
	   echo 1;
	}
	
	public function agentExport(){
		/* echo "Hello"; die; */
		return Excel::download(new ListExport, 'agent.xlsx');
	}
	
	public function import_insert_data($data,$table_name,$keys)
	{
	  //setlocale(LC_ALL, 'en_US.UTF-8');
	  //pr($data); die;
	  $arr = array();
	  $msg = array();
	  $update_array = array();
	  $insert_array = array();
	  $d = array();
	  if($table_name=='tbl_Item_group'){
	  $mid = nextAutoincrementId('tbl_Item_group','mid');
	  foreach($data as $key=>$datas){
	  $d[1]=utf8_encode($datas[0]);
	  $datas_array = array_combine($keys,$d);
	  $datas_array['mid']=(String)$mid;
	  $datas_array['created_on'] = date('Y-m-d h:i:s');
	  array_push($arr,$datas_array);
	  $mid++;
	  }
	  }else{
	      //pr($data);die;
	      $mid = nextAutoincrementId($table_name,'mid');
	    foreach($data as $key=>$datas){

	  $datas_array = array_combine($keys,$datas);
	  //pr($keys);die;
	  $datas_array['mid']=(String)$mid;
	  $datas_array['created_on'] = date('Y-m-d h:i:s');
	  array_push($arr,$datas_array);
	  $mid++;
	  }   
	  }
	  //pr($arr);die;
	  if($table_name!='items'){
	  if(!empty($arr)){
	      if($table_name=='customers')
	      {
	       foreach($arr as $ar){
	       $datacheck = DB::table('customers')->where('company_name', '=', $ar['company_name'])->get()->count();
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
            $d1['drug_license_number']              = checkNullDatas(utf8_encode($ar['drug_license_number']));
            $d1['segment']                          = checkNullDatas(utf8_encode($ar['segment']));
            $d1['category']                         = checkNullDatas(utf8_encode($ar['category']));
            $d1['quotation_invoice_limit']          = checkNullDatas(utf8_encode($ar['quotation_invoice_limit']));
            $d1['bank_account_details']             = checkNullDatas(utf8_encode($ar['bank_account_details']));
            $d1['bank_account_name']                = checkNullDatas(utf8_encode($ar['bank_account_name']));
            $d1['IFSC_code']                        = checkNullDatas(utf8_encode($ar['IFSC_code']));
            $d1['choose_process']                   = checkNullDatas(utf8_encode($ar['choose_process']));
            $d1['favorite_products']                = checkNullDatas(utf8_encode($ar['favorite_products']));
            $d1['add_favorite_products']            = checkNullDatas(utf8_encode($ar['add_favorite_products']));
            $d1['preference_1']                     = checkNullDatas(utf8_encode($ar['preference_1']));
            $d1['preference_2']                     = checkNullDatas(utf8_encode($ar['preference_2']));
            $d1['preference_3']                     = checkNullDatas(utf8_encode($ar['preference_3']));
            $d1['single_so_single_lot']             = checkNullDatas(utf8_encode($ar['single_so_single_lot']));
            $d1['separate_bills_for_separate_SOs']  = checkNullDatas(utf8_encode($ar['separate_bills_for_separate_SOs']));
            $d1['payment_terms']                    = checkNullDatas(utf8_encode($ar['payment_terms']));
            $d1['customer']                         = checkNullDatas(utf8_encode($ar['customer']));
	           DB::table($table_name)->insert(array($d1));
	           //pr($d1);
	       }
	       }
	      }else if($table_name=='vendors'){
	       
	       foreach($arr as $ar){
	           DB::table('vendors')->where('company_name', '=', '')->delete();
	       $datacheck = DB::table('vendors')->where('company_name', '=', $ar['company_name'])->get()->count();
	       if($datacheck==0){
	           //pr($ar);die;
	        $d1['block_unblock']                        = checkNullDatas(utf8_encode($ar['block_unblock']));
            $d1['register_type']                        = checkNullDatas(utf8_encode($ar['register_type']));
            $d1['medical_type']                         = checkNullDatas(utf8_encode($ar['medical_type']));
            $d1['company_name']                         = checkNullDatas(utf8_encode($ar['company_name']));
            $d1['h_area']                               = checkNullDatas(utf8_encode($ar['h_area']));
            $d1['h_landmark']                           = checkNullDatas(utf8_encode($ar['h_landmark']));
            $d1['h_country']                            = checkNullDatas(utf8_encode($ar['h_country']));
            $d1['h_city']                               = checkNullDatas(utf8_encode($ar['h_city']));
            $d1['h_state']                              = checkNullDatas(utf8_encode($ar['h_state']));
            $d1['h_pin_code']                           = checkNullDatas(utf8_encode($ar['h_pin_code']));
            $d1['b_area']                               = checkNullDatas(utf8_encode($ar['b_area']));
            $d1['b_landmark']                           = checkNullDatas(utf8_encode($ar['b_landmark']));
            $d1['b_country']                            = checkNullDatas(utf8_encode($ar['b_country']));
            $d1['b_city']                               = checkNullDatas(utf8_encode($ar['b_city']));
            $d1['b_state']                              = checkNullDatas(utf8_encode($ar['b_state']));
            $d1['b_pin_code']                           = checkNullDatas(utf8_encode($ar['b_pin_code']));
            $d1['s_area']                               = checkNullDatas(utf8_encode($ar['s_area']));
            $d1['s_landmark']                           = checkNullDatas(utf8_encode($ar['s_landmark']));
            $d1['s_country']                            = checkNullDatas(utf8_encode($ar['s_country']));
            $d1['s_city']                               = checkNullDatas(utf8_encode($ar['s_city']));
            $d1['s_state']                              = checkNullDatas(utf8_encode($ar['s_state']));
            $d1['s_pin_code']                           = checkNullDatas(utf8_encode($ar['s_pin_code']));
            $d1['create_depot_address'][0]['d_area']    = checkNullDatas(utf8_encode($ar['d_area']));
            $d1['create_depot_address'][0]['d_landmark']= checkNullDatas(utf8_encode($ar['d_landmark']));
            $d1['create_depot_address'][0]['d_country'] = checkNullDatas(utf8_encode($ar['d_country']));
            $d1['create_depot_address'][0]['d_city']    = checkNullDatas(utf8_encode($ar['d_city']));
            $d1['create_depot_address'][0]['d_state']   = checkNullDatas(utf8_encode($ar['d_state']));
            $d1['create_depot_address'][0]['d_pin_code']= checkNullDatas(utf8_encode($ar['d_pin_code']));
            $d1['s_google_location']                    = checkNullDatas(utf8_encode($ar['s_google_location']));
            $d1['email']                                = checkNullDatas(utf8_encode($ar['email']));
            $d1['website']                              = checkNullDatas(utf8_encode($ar['website']));
            $d1['contact_number']                       = checkNullDatas(utf8_encode($ar['contact_number']));
            $d1['directors_name']                       = checkNullDatas(utf8_encode($ar['directors_name']));
            $d1['contact_no']                           = checkNullDatas(utf8_encode($ar['contact_no']));
            $d1['2nd_directors_name']                   = checkNullDatas(utf8_encode($ar['2nd_directors_name']));
            $d1['2nd_contact_no']                       = checkNullDatas(utf8_encode($ar['2nd_contact_no']));
            $d1['responsible_emp']                      = checkNullDatas(utf8_encode($ar['responsible_emp']));
            $d1['responsible_contact_no']               = checkNullDatas(utf8_encode($ar['responsible_contact_no']));
            $d1['GST_number']                           = checkNullDatas(utf8_encode($ar['GST_number']));
            $d1['PAN_number']                           = checkNullDatas(utf8_encode($ar['PAN_number']));
            $d1['TAN_number']                           = checkNullDatas(utf8_encode($ar['TAN_number']));
            $d1['advance_required']                     = checkNullDatas(utf8_encode($ar['advance_required']));
            $d1['credit_limit']                         = checkNullDatas(utf8_encode($ar['credit_limit']));
            $d1['credit_days']                          = checkNullDatas(utf8_encode($ar['credit_days']));
            $d1['state_code']                           = checkNullDatas(utf8_encode($ar['state_code']));
            $d1['drug_license_number']                  = checkNullDatas(utf8_encode($ar['drug_license_number']));
            $d1['segment']                              = checkNullDatas(utf8_encode($ar['segment']));
            $d1['category']                             = checkNullDatas(utf8_encode($ar['category']));
            $d1['quotation_invoice_limit']              = checkNullDatas(utf8_encode($ar['quotation_invoice_limit']));
            $d1['bank_account_details']                 = checkNullDatas(utf8_encode($ar['bank_account_details']));
            $d1['bank_account_name']                    = checkNullDatas(utf8_encode($ar['bank_account_name']));
            $d1['IFSC_code']                            = checkNullDatas(utf8_encode($ar['IFSC_code']));
	           DB::table($table_name)->insert(array($d1));
	           //pr($d1);
	       }
	       }   
	      }else{
	      DB::table($table_name)->insert($arr); 
	      }
	  }
	  }else{
	  if(!empty($arr)){
	      //pr($arr); die;
	  foreach($arr as $ar){
	      //echo 1; die;
	      $mid = nextAutoincrementId('items','mid');
	      checkGrade(checkNullDatas(utf8_encode($ar['grade'])));
	      checkBrand(checkNullDatas(utf8_encode($ar['brand'])));
	      checkHSNCodeTax(checkNullDatas($ar['hsn_code']),$ar['tax_rate']);
	      $vendor_sku = utf8_encode($ar['vendor_sku']);
	      $datacheck = DB::table('items')->where('vendor_sku', '=', $vendor_sku)->get()->count();
	      if($datacheck==1){
	        array_push($update_array,$vendor_sku);
	        //$data = array('vendor_sku'=>$ar['vendor_sku'],'mrp'=>checkNullDatas($ar['mrp']),'unit_price'=>checkNullDatas($ar['unit_price']),'updated_on'=> date('Y-m-d H:i:s'));
	        //pr($data); 
	        //DB::table($table_name)->where('vendor_sku', '=', $ar['vendor_sku'])->update($data);  
	      }else{
	        array_push($insert_array,$vendor_sku);
	        $data = array('mid'=>$mid,'active_deactive'=>$ar['active_deactive'],'vendor_sku'=>utf8_encode($ar['vendor_sku']),'name'=>checkNullDatas(utf8_encode($ar['name'])),'grade'=>checkNullDatas(utf8_encode($ar['grade'])),'brand'=>checkNullDatas(utf8_encode($ar['brand'])),'packing_name'=>checkNullDatas(utf8_encode($ar['packing_name'])),'hsn_code'=>checkNullDatas(utf8_encode($ar['hsn_code'])),'mrp'=>checkNullDatas($ar['mrp']),'stock'=>checkNullDatas($ar['stock']),'is_verified'=>$ar['is_verified'],'tax_rate'=>checkNullDatas($ar['tax_rate']),'created_on'=> date('Y-m-d H:i:s'),'updated_on'=> date('Y-m-d H:i:s'));
	        //pr($data); die;
	        DB::table($table_name)->insert(array($data));  
	      }
	      $mid++;
	  }  
	  }
	  }
	  
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
	
	
	public function import_insert_data_update($data,$table_name,$keys)
	{
	  $update_array = array();
	  $arr = array();
	  $msg = array();
	  foreach($data as $datas){
	  $datas_array = array_combine($keys,$datas);
	  $datas_array['created_on'] = date('Y-m-d h:i:s');
	  array_push($arr,$datas_array);
	  }
	  //pr($arr); die;
	  if($table_name!='items'){
	  DB::table($table_name)->insert($arr); 
	  }else{
	  if(!empty($arr)){
	  foreach($arr as $ar){
	      $datacheck = DB::table('items')->where('vendor_sku', '=', $ar['vendor_sku'])->get()->count();
	      if($datacheck==1){
	        array_push($update_array,$ar['vendor_sku']);
	        $data = array('vendor_sku'=>$ar['vendor_sku'],'mrp'=>checkNullDatas($ar['mrp']),'unit_price'=>checkNullDatas($ar['unit_price']),'updated_on'=> date('Y-m-d H:i:s'));
	        //pr($data); 
	        DB::table($table_name)->where('vendor_sku', '=', $ar['vendor_sku'])->update($data);  
	      }
	  }  
	  }
	  }
	  
	  
	  if(count($update_array)>0){
	  $msg_update_array = count($update_array).' Records Updated Successfully.';
	  }
	  $msg['msg'] = $msg_update_array;
	  return $msg;
	}
	
	public function export_data_master(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    $header = array_keys(end($arr));
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $ar = array();
        foreach($arr as $k=>$d1){
            fputcsv($fp, $d1);
        }
        
	}
	
	public function export_data_master_item(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('_id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('_id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['_id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    //$header = array_keys(end($arr));
	    $header = array('active_deactive','vendor_sku','name','grade','brand','packing_name','hsn_code','mrp','stock','tax_rate','customer','description','list_price','minimum_order_pack','net_rate',
	    'pack_size','shelf_life','specific_gravity','storage_conditions','sub_type','synonyms','type','unit','unit_price','is_verified');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $ar = array();
        foreach($array as $data){
            $d1['active_deactive']= $data['active_deactive'];
            $d1['vendor_sku']= $data['vendor_sku'];
            $d1['name']= $data['name'];
            $d1['grade']= $data['grade'];
            $d1['brand']= $data['brand'];
            $d1['packing_name']= $data['packing_name'];
            $d1['hsn_code']= $data['hsn_code'];
            $d1['mrp']= $data['mrp'];
            $d1['stock']= $data['stock'];
            $d1['tax_rate']= $data['tax_rate'];
            $d1['customer']= $data['customer'];
            $d1['description']= $data['description'];
            $d1['list_price']= $data['list_price'];
            $d1['minimum_order_pack']= $data['minimum_order_pack'];
            $d1['net_rate']= $data['net_rate'];
            $d1['pack_size']= $data['pack_size'];
            $d1['shelf_life']= $data['shelf_life'];
            $d1['specific_gravity']= $data['specific_gravity'];
            $d1['storage_conditions']= $data['storage_conditions'];
            $d1['sub_type']= $data['sub_type'];
            $d1['synonyms']= $data['synonyms'];
            $d1['type']= $data['type'];
            $d1['unit']= $data['unit'];
            $d1['unit_price']= $data['unit_price'];
            $d1['is_verified']= $data['is_verified'];
            fputcsv($fp, $d1);
        }
        
	}
	
	
	public function export_data_master_customer(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('_id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('_id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['_id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    //$header = array_keys(end($arr));
	    $header = array('block_unblock','register_type','medical_type','company_name','h_area','h_landmark','h_country','h_city','h_state','h_pin_code',
	    'b_area','b_landmark','b_country','b_city','b_state','b_pin_code','s_area','s_landmark','s_country','s_city','s_state','s_pin_code',
	    's_google_location','email','website','contact_number','directors_name','contact_no','2nd_directors_name','2nd_contact_no','responsible_emp','responsible_contact_no',
	    'GST_number','PAN_number','TAN_number','advance_required','credit_limit','credit_days','state_code','drug_license_number','segment','category',
	    'quotation_invoice_limit','bank_account_details','bank_account_name','IFSC_code','choose_process','favorite_products','add_favorite_products','preference_1',
	    'preference_2','preference_3','single_so_single_lot','separate_bills_for_separate_SOs','payment_terms','customer');
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
            $d1['drug_license_number']= $data['drug_license_number'];
            $d1['segment']= $data['segment'];
            $d1['category']= $data['category'];
            $d1['quotation_invoice_limit']= $data['quotation_invoice_limit'];
            $d1['bank_account_details']= $data['bank_account_details'];
            $d1['bank_account_name']= $data['bank_account_name'];
            $d1['IFSC_code']= $data['IFSC_code'];
            $d1['choose_process']= $data['choose_process'];
            $d1['favorite_products']= $data['favorite_products'];
            $d1['add_favorite_products']= $data['add_favorite_products'];
            $d1['preference_1']= $data['preference_1'];
            $d1['preference_2']= $data['preference_2'];
            $d1['preference_3']= $data['preference_3'];
            $d1['single_so_single_lot']= $data['single_so_single_lot'];
            $d1['separate_bills_for_separate_SOs']= $data['separate_bills_for_separate_SOs'];
            $d1['payment_terms']= $data['payment_terms'];
            $d1['customer']= $data['customer'];
            fputcsv($fp, $d1);
        }
        
	}
	
	public function export_data_master_vendor(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('_id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('_id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['_id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    //$header = array_keys(end($arr));
	    $header = array('block_unblock','register_type','medical_type','company_name','h_area','h_landmark','h_country','h_city','h_state','h_pin_code',
	    'b_area','b_landmark','b_country','b_city','b_state','b_pin_code','s_area','s_landmark','s_country','s_city','s_state','s_pin_code','d_area','d_landmark','d_country','d_city','d_state','d_pin_code',
	    's_google_location','email','website','contact_number','directors_name','contact_no','2nd_directors_name','2nd_contact_no','responsible_emp','responsible_contact_no',
	    'GST_number','PAN_number','TAN_number','advance_required','credit_limit','credit_days','state_code','drug_license_number','segment','category',
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
            $d1['d_area']= $data['create_depot_address'][0]['d_area'];
            $d1['d_landmark']= $data['create_depot_address'][0]['d_landmark'];
            $d1['d_country']= $data['create_depot_address'][0]['d_country'];
            $d1['d_city']= $data['create_depot_address'][0]['d_city'];
            $d1['d_state']= $data['create_depot_address'][0]['d_state'];
            $d1['d_pin_code']= $data['create_depot_address'][0]['d_pin_code'];
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
            $d1['drug_license_number']= $data['drug_license_number'];
            $d1['segment']= $data['segment'];
            $d1['category']= $data['category'];
            $d1['quotation_invoice_limit']= $data['quotation_invoice_limit'];
            $d1['bank_account_details']= $data['bank_account_details'];
            $d1['bank_account_name']= $data['bank_account_name'];
            $d1['IFSC_code']= $data['IFSC_code'];
            fputcsv($fp, $d1);
        }
        
	}
	
	public function export_data_master_update(Request $req)
	{
	 $filename = "Item_update.csv";  
	 $fp = fopen('php://output', 'w');
	 $header = array("vendor_sku","mrp","unit_price");
	 header('Content-type: application/csv');
     header('Content-Disposition: attachment; filename='.$filename);
     fputcsv($fp, $header);
     $arr = array(array('vendor_sku'=>'3345dffd','mrp'=>'20000','unit_price'=>'2000'));
     foreach($arr as $k=>$d1){
            fputcsv($fp, $d1);
        }
    fclose($fp);
	}
	
	public function import_data_master(Request $req)
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
                         $data = $this->import_insert_data($csv,$req->table_name,$keys);
                         //pr($data); die;
                         return redirect()->to(url('admin/listdata/'.$req->id))->with('success', $data['msg']);
	                 }
	              }
	           }
	     }
	     
	public function import_data_master_update(Request $req)
	{
	   if(isset($_POST['importSubmit'])){
	       $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
	       if(!empty($_FILES['result_file']['name']) && in_array($_FILES['result_file']['type'], $csvMimes)){
	           if(is_uploaded_file($_FILES['result_file']['tmp_name'])){
	                     $csv = array_map("str_getcsv", file($_FILES['result_file']['tmp_name'],FILE_SKIP_EMPTY_LINES));
                         $keys = array_shift($csv);
                         $data = $this->import_insert_data_update($csv,$req->table_name,$keys);
                         //pr($data); die;
                         return redirect()->to(url('admin/listdata/'.$req->id))->with('success', $data['msg']);
	                 }
	              }
	           }
	     }
	     function uniqueValueCheck(Request $req)
	     {
	       //echo strtoupper($req->input_val); die;
	       $data = DB::table($req->table_name)->where($req->field_name,$req->input_val)->orWhere($req->field_name,strtolower($req->input_val))->orWhere($req->field_name,strtoupper($req->input_val))->orWhere($req->field_name,ucwords($req->input_val))->get()->first();
	       //pr($data); die;
	       $data_name = strtolower($data[$req->field_name]);
	       if($data_name==strtolower($req->input_val)){
	           echo 1;
	       }else{
	           echo 0;
	       }
	     }
	     
	     function hsn_task_rate(Request $req)
	     {
	      $data = DB::table('hsn_taxes')->where('hsn_code',$req->hsn_code_val)->get()->first();
	      return $data['tax'];
	     }
	     
	     function uniqueValueCheckPackSize(Request $req)
	     {
	      $data_original = DB::table('pack_size')->where('pack_size',$req->pack_size_val)->where('unit',$req->unit_val)->get()->first(); 
	      if($data_original['pack_size']!='' && $data_original['unit']!=''){
	          return 1;
	      }else{
	          return 0;
	      }
	     }
	     
	     function uniqueValueCheckTaskCategory(Request $req)
	     {
	     //pr($req); die;
	      $data_original = DB::table('task_categories')->where('department',$req->dept_val)->where('task_category',$req->task_category_val)->get()->first(); 
	      if($data_original['department']!='' && $data_original['task_category']!=''){
	          return 1;
	      }else{
	          return 0;
	      }   
	     }
	     
	     function uniqueValueCheckPackSizeEdit(Request $req)
	     {
	      $data = DB::table('pack_size')->where('_id',$req->id)->get()->first();
	      $data_original = DB::table('pack_size')->where('pack_size',$req->pack_size_val)->where('unit',$req->unit_val)->get()->first(); 
	      if($data_original['pack_size']!='' && $data_original['unit']!='' && $data_original['_id']!=$req->id){
	          $arr = array('pack_size'=>$data['pack_size'],'unit'=>$data['unit']);
	          echo json_encode($arr);
	      }else{
	          echo  0;
	      }   
	     }
	     
	     function mrp_data_update(Request $req)
	     {
	         $data = array('mrp'=>$req->mrp_update,'updated_on'=>date('Y-m-d H:i:s'));
	         DB::table('items')->where('_id',$req->mrp_id)->update($data);
	         return $req->mrp_update;
	     }
	     
        
	     
	     function uniqueValueCheckEdit(Request $req)
	     {
	       $data_original = DB::table($req->table_name)->where('_id',$req->id)->get()->first();
	       $data = DB::table($req->table_name)->where($req->field_name,$req->input_val)->orWhere($req->field_name,strtolower($req->input_val))->orWhere($req->field_name,strtoupper($req->input_val))->orWhere($req->field_name,ucwords($req->input_val))->get()->first();
	       //pr($data); die;
	       $data_name = strtolower($data[$req->field_name]);
	       if($data_name==strtolower($req->input_val) && $data['_id']!=$req->id){
	           echo $data_original[$req->field_name];
	       }else{
	           echo 0;
	       }
	     }
	     
	     
	     function ajax_item_details(Request $req)
	     {
	       $data_original = DB::table('items')->where('_id',$req->id)->get()->first();
	       $html = '';
	       $html .= '<tr>';
	       $html .= '<th>Status</th>';
	       if($data_original['active_deactive']==1){
	       $html .= '<td>Active</td>';
	       }else{
	       $html .= '<td>Deactive</td>';    
	       }
	       $html .= '<th>Synonyms</th>';
	       $html .= '<td>'.$data_original['synonyms'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Tax Rate</th>';
	       $html .= '<td>'.$data_original['tax_rate'].'</td>';
	       $html .= '<th>Description</th>';
	       $html .= '<td>'.$data_original['description'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Item Type</th>';
	       $html .= '<td>'.$data_original['type'].'</td>';
	       $html .= '<th>Item Sub type</th>';
	       $html .= '<td>'.$data_original['sub_type'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>COA Applicable?</th>';
	       $html .= '<td>'.$data_original['coa_applicable'].'</td>';
	       $html .= '<th>Unit</th>';
	       $html .= '<td>'.$data_original['unit'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Pack Size</th>';
	       $html .= '<td>'.$data_original['pack_size'].'</td>';
	       $html .= '<th>Unit Price</th>';
	       $html .= '<td>'.$data_original['unit_price'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Specific Gravity <br/>(optional in case of chemicals)</th>';
	       $html .= '<td>'.$data_original['specific_gravity'].'</td>';
	       $html .= '<th>Minimum Order Pack</th>';
	       $html .= '<td>'.$data_original['minimum_order_pack'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Storage Conditions</th>';
	       $html .= '<td>'.$data_original['storage_conditions'].'</td>';
	       $html .= '<th>EUD <br/>(End User Declaration)</th>';
	       $html .= '<td>'.$data_original['eud'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Shelf Life</th>';
	       $html .= '<td>'.$data_original['shelf_life'].'</td>';
	       $html .= '<th>Is Verified</th>';
	       $html .= '<td>'.$data_original['is_verified'].'</td>';
	       $html .= '</tr>';
	       
	       return $html;  
	     }
	     
	     public function ajax_department_details(Request $req)
	     {
	         $data = DB::table('customers')->select('create_departments')->where('_id',$req->id)->get()->first();
	         //pr($data);
	     }
	     
	     
	     function aggregation(){
	         //$bookings = Netrate::select('_id', 'item_id', 'item_name', 'user');
	         //$items = User::select('_id', 'user','full_name')->get()->toArray();
	         //pr($items); die;
	         $start = 0;
	         $limit = 3;
	         $search = 'some';
	         $var = 'books_selling_data';
$res = DB::collection("books_selling_data")->raw(function($collection) use ($search, $start, $limit) {
        return $collection->aggregate([
                ['$lookup' => [
                    'from' => "books",
                    'localField' => 'mid',
                    'foreignField' => 'mid',
                    'as' => 'title'
                ]],
                ['$unwind' => [ 
                'path' => '$title', 'preserveNullAndEmptyArrays' => True
                ]],
                
                ['$lookup' => [
                    'from' => "book_price",
                    'localField' => 'mid',
                    'foreignField' => 'mid',
                    'as' => 'price'
                ]],
                ['$unwind' => [ 
                'path' => '$price', 'preserveNullAndEmptyArrays' => True
                ]],
                ['$match' => [
                '$or' => [
                ['title.title' => [ '$regex' => $search ]],
           
                ]
                ]],
                ['$skip' => $start],
                ['$limit' => $limit],
            ]);
        });
        
        //$array = $res->toQuery()->paginate(1);
        $array = json_decode(json_encode($res->toArray()), true);
            //pr($array); die;
            $html = '<table border="1"><th>ISBN</th><th>Title</th>';
            foreach($array as $data){
            $html .='<tr><td>'.$data["isbn"].'</td><td>'.$data["title"]["title"].'</td></tr>';
            }
            $html .= '</table>';
            echo $html;
	     }
	     
	     public function update_opening_balance(Request $request)
	     {
	         $table_name = 'tbl_balance_stock';
	         $mfg_date = explode('/',$request->mfg_date);
	         $expiry_date = explode('/',$request->expiry_date);
	         $update = array(
                    "item_id"           =>  $request->item_id,
                    "room_no"           =>  $request->room,
                    "rack_no"           =>  $request->rack_no,
                    "batch_no"          =>  $request->batch_no,
                    "mfg_date"          =>  $mfg_date[2].'-'.$mfg_date[0].'-'.$mfg_date[1],
                    "expiry_date"       =>  $expiry_date[2].'-'.$expiry_date[0].'-'.$expiry_date[1],
                    "quantity"          =>  $request->quantity,
                    "user_id"           =>  Auth::user()->id,
                    "mrp"               =>  $request->mrp,
                    "loc_cen"           =>  $request->loc_cen,
                    "rate"              =>  $request->rate,
                    "amount"            =>  $request->amount
                    );
             $update_data = DB::table($table_name)->where('_id',$request->id)->update($update);
             if($update_data){
                 return 1;
             }
	     }
	     
	     public function import_opening_stock(Request $request)
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
                         $data = $this->import_insert_data_opening_stock($csv,'tbl_balance_stock',$keys);
                         //pr($data); die;
                         //pr($csv); die;
                         return redirect()->to(url('admin/opening-stock-list'))->with('success', 'Data Successfully Uploaded.');
	                 }
	              }
	           }
	         
	     }
	     
            public function import_insert_data_opening_stock($data,$table_name,$keys)
            {
            $update_array = array();
            $arr = array();
            $msg = array();
            foreach($data as $datas){
            $datas_array = array_combine($keys,$datas);
            $datas_array['created_on'] = date('Y-m-d h:i:s');
            array_push($arr,$datas_array);
            }
            //pr($arr); //die;
           
            if(!empty($arr)){
            foreach($arr as $ar){
            $mid = nextAutoincrementId('tbl_balance_stock','mid');
            $insert = array(array(
                "mid"           => (String)$mid,
                "item_id"       => getItemById($ar['Item Name']),
                "room_no"       => getRoomById($ar['Room Name']),
                "rack_no"       => getRackById($ar['Rack Name']),
                "batch_no"      => $ar['Batch Name'],
                "mfg_date"      => $ar['Mfg Date'],
                "expiry_date"   => $ar['Expiry Date'],
                "quantity"      => $ar['Quantity'],
                "mrp"           => $ar['MRP'],
                "loc_cen"       => $ar['Type'],
                "rate"          => $ar['Rate'],
                "amount"        => $ar['Amount'],
                "tra_type"      => 1,
                "type"          => 1,
                "user_id"       => Auth::user()->id,
                "created_date"  => date('Y-m-d h:i:s')
                ));
            DB::table("tbl_balance_stock")->insert($insert);
            }  
            }
            }
            
            
            public function export_opening_stock(Request $request)
            {
            //echo 1; die;   
            
            $data = DB::table('tbl_balance_stock')->orderBy('_id','DESC')->limit(1)->first();
            $array = json_decode(json_encode($data), true);
            //pr($array); die;
            $filename = "Opening-Stock.csv";
            $fp = fopen('php://output', 'w');
            $header = array("Item Name","Room Name","Rack Name","Batch Name","Mfg Date","Expiry Date","Quantity","MRP","Type","Rate","Amount");
            $arr = array(array('Item Name'=>getItemName($data['item_id']),'Room Name'=>getRoomName($data['room_no']),'Rack Name'=>getRackName($data['rack_no']),'Batch Name'=>$data['batch_no'],'Mfg Date'=>$data['mfg_date'],
            'Expiry Date'=>$data['expiry_date'],'Quantity'=>$data['quantity'],'MRP'=>$data['mrp'],'Type'=>$data['loc_cen'],'Rate'=>$data['rate'],'Amount'=>$data['amount']));
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            fputcsv($fp, $header);
            $ar = array();
            foreach($arr as $k=>$d1){
            fputcsv($fp, $d1);
            }  
            }
            
            public function submit_opening_balance(Request $request)
            {
				 //pr($request->all()); die();
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
									"g_id"           	=>  $request->group_id,
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
									"type"              =>  1,
									"remaining"         =>  $request->quantity,
									"created_at"        =>  date('Y-m-d H:i:s'),
									"updated_at"        =>  date('Y-m-d H:i:s'),
								);
				//pr($insert); die;
				$insert_data = DB::table('tbl_balance_stock')->insert($insert);
                balanceStockUpdate($request->item_id);
				if($insert_data){
					return 1;
				}
            }
			
			public function item_details(){
				
				if(!empty($_GET) && array_key_exists('type',$_GET) && $_GET['type']!=''){
						$type=$_GET['type'];
				}else{
					$type=1;
				}
				
				$query = DB::table('tbl_balance_stock')->where('type', $type);

				if(!empty($_GET) && array_key_exists('i_id',$_GET) && $_GET['i_id']!=''){
					$item_id = $_GET['i_id'];
					$query->where('item_id', $item_id);
				}
				if(!empty($_GET) && array_key_exists('loc_cen',$_GET) && $_GET['loc_cen']!=''){
					$loc_cen = $_GET['loc_cen'];
					if($loc_cen==1){
						$loc_cen = "LOCAL";
					}else{
						$loc_cen = "CENTRAL";
					}
					$query->where('loc_cen', $loc_cen);
				}

				if(!empty($_GET) && array_key_exists('room',$_GET) && $_GET['room']!=''){
					$room_id = $_GET['room'];
					$query->where('room_no', $room_id);
				}

				if(!empty($_GET) && array_key_exists('rack',$_GET) && $_GET['rack']!=''){
					$rack_id = $_GET['rack'];
					$query->where('rack_no', $rack_id);
				}

				if(!empty($_GET) && array_key_exists('batch',$_GET) && $_GET['batch']!=''){
					$batch = $_GET['batch'];
					$query->where('batch_no','like', '%'.$batch.'%');
				}

				if(!empty($_GET) && array_key_exists('mfg_date',$_GET) && $_GET['mfg_date']!=''){
				$query->where('mfg_date', 'LIKE','%'.date('Y-m-d',strtotime($_GET['mfg_date'])).'%');
				}

				if(!empty($_GET) && array_key_exists('expiry_date',$_GET) && $_GET['expiry_date']!=''){
				$query->where('expiry_date', 'LIKE','%'.date('Y-m-d',strtotime($_GET['expiry_date'])).'%');
				}

				$i_data = $query->paginate(1000);
				
				$item_data = DB::table('tbl_balance_stock as t1')
							->leftJoin('items as t2', 't1.item_id', '=', 't2.id')
							->select('t2.id', 't2.name')
							->get();
				//pr($item_data); die;
				$room_data_object = DB::table('room')->get()->toArray();
				$room_data = json_decode(json_encode($room_data_object), true);

				$rack_data_object = DB::table('rack')->get()->toArray();
				$rack_data = json_decode(json_encode($rack_data_object), true);

				return view('form_management.item_detail_list',['datalist'=>$i_data,'item_data'=>$item_data,'room_data'=>$room_data,'rack_data'=>$rack_data]);
			}

			public function item_view_details(Request $request, $id)
			{
				$object = DB::table('tbl_balance_stock')->where('id', $id)->first();
				$array = json_decode(json_encode($object), true);
				$item_data = DB::table('tbl_balance_stock as t1')
							->leftJoin('items as t2', 't1.item_id', '=', 't2.id')
							->select('t2.id', 't2.name')
							->get();
				return view('form_management.item_view_details',['datalist'=>$array,'item_data'=>$item_data]);
			}

			public function item_details_barcode(Request $request)
			{
				
				//echo'<pre>';print_r($_POST); echo'</pre>';die;
				if(!empty($request->barcode)){
					
					$data = DB::table('tbl_balance_stock as t1')
							->leftJoin('items as t2', 't1.item_id', '=', 't2.id')
							->select('t1.id','t2.name','t2.vendor_sku','t1.batch_no','t1.item_id' )
							->whereIn('t1.id', $request->barcode)
							->get()
							->toArray();
				}else{
					$data = array();
				}
				return view('form_management.item_details_barcode',['data'=>$data]);
			}
	
            
            
}