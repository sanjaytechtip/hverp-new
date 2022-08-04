<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\General\StaffModel;
use DB;
use Session;
use App\User;
error_reporting(0);

class StaffController extends Controller
{
	function __construct(){
		$this->staffmodel = new StaffModel();
	}
	
    public function index() {
		checkPermission('view_staff');
		$staffs = User::where('member_id', '=', getCompanyId())->paginate(20);
		return view('general.staff_management.stafflist')->with('staffs', $staffs);
    }
	
	public function usernotallow(){
		return view('general.staff_management.user_notallow');
	}

    public function create(Request $request) {
		checkPermission('add_staff');
		return view('general.staff_management.add_staff');
    }

    public function store(Request $request) {
		checkPermission('add_staff');
        $this->validate($request, [
			'name'  	  => 'required',
			'father_name' => 'required',
			'mobile' 	  => 'required',
			'email' 	  => 'unique:users,email',
			'phone_no'    => 'required',
			'address' 	  => 'required',
			'dob' 		  => 'required',
			'designation' => 'required',
			'branch' 	  => 'required',
		]);
		//$user->access_fms_id = $request->get('access_fms_id');
		$data = array(
			"name"  	  => $request->name,
			"father_name" => $request->father_name,
			"mobile"	  => $request->mobile,
			"email"		  => $request->email,
			"phone_no"    => $request->phone_no,
			"address" 	  => $request->address,
			"per_address" => $request->per_address,
			"dob"		  => $request->dob,
			"member_id"   => getCompanyId(),
			"designation" => getArrayKey($request->designation),
			"branch"	  => $request->branch,
			"user_role"   => $request->user_role,
			"password"    => Hash::make($request->org_password),
			"org_password"=> $request->org_password,
			"user_role_assigned" => $request->user_role_assigned,
			"access_fms_id" => $request->access_fms_id,
			"user_status" => "1",
			"created_at"  => date('Y-m-d H:i:s')
		);
		
		$insert = DB::table('users')->insert($data);
		if($insert){			
			Session::flash('success', 'Staff Added Successfully');
			return redirect('admin/stafflist');
		} else{ 
			return view('general.staff_management.add_staff');
		}
    }
	
	public function edit($id) {
		checkPermission('edit_staff');
        $editStaff = DB::table('users')->where('_id',$id)->first();
		return view('general.staff_management.edit_staff')->with('editStaff', $editStaff);
    }

    public function view($id) {
		checkPermission('view_staff');
		if(!checkPermission('view_staff')){
			return redirect()->route('usernotallow');
		}
        $viewStaff = DB::table('users')->where('_id',$id)->first();
		return view('general.staff_management.view_staff')->with('viewStaff', $viewStaff);
    }
	
	public function searchStaff (Request $request){
		/* pr($_POST);die; */
		$search	   = $request->staff;
		$search_by = $request->staff_code;
		
		if($request->has('staff_code')){
			$staffs = DB::table('users')->where($request->staff,'LIKE', '%' .$request->staff_code. '%')->paginate(20); 
    	}else{
    		$staffs = DB::table('users')->paginate(20);
			$staffs['search']	= $request->staff;
			$staffs['search_by'] = $request->staff_code;
    	}
		return view('general.staff_management.stafflist', compact('staffs'))->with('search', $search)->with('search_by', $search_by);
	}
	
	public function staffrole($id) {
        $viewStaff = DB::table('users')->where('_id',$id)->first();
		return view('general.staff_management.view_staffroles')->with('viewStaff', $viewStaff)->with('staff_id', $id);
    }
	
	public function addstaffrole(Request $request){
		
		$staff_id = $request->staff_id;
		$role = $request->role;
		//pr($role); die('--from controller--');		
		$updated = DB::table('users')->where('_id', $staff_id)->update(array('role'=>$role));
		Session::flash('success', 'Staff role updated successfully!');
		return redirect('admin/stafflist');
	}

    public function update(Request $request, $id) {
		checkPermission('edit_staff');
		$pass = $request->password;
		if($request->user_role_assigned==1)
		{
		if(!empty($pass) && $pass != $request->org_password){
			$password = Hash::make($request->password);
			$org_password = $request->password;
		}else{
		$password = Hash::make($request->hidden_passowrd);
		$org_password = $request->hidden_passowrd;
		}
		}else{
		$password = '';
		$org_password = '';
		}
		
        $this->validate($request, [
			'name'  	  => 'required',
			'father_name' => 'required',
			'mobile' 	  => 'required',
			'email' 	  => 'required',
			'phone_no'    => 'required',
			'address' 	  => 'required',
			'dob' 		  => 'required',
			'designation' => 'required',
			'branch' 	  => 'required',
		]);
		
		
		$Update_Staff = array(
			"name"        => $request->name,
			"father_name" => $request->father_name,
			"mobile"	  => $request->mobile,
			"email"		  => $request->email,
			"phone_no"    => $request->phone_no,
			"address"	  => $request->address,
			"per_address" => $request->per_address,
			"dob"		  => $request->dob,
			"designation" => getArrayKey($request->designation),
			"branch"	  => $request->branch,
			"user_role_assigned" => $request->user_role_assigned,
			"access_fms_id" => $request->access_fms_id,
			"user_role"   => $request->user_role,
			"password"    => $password,
			"org_password"=> $org_password, 
			"updated_at"  => date('Y-m-d H:i:s')
		);
		DB::table('users')->where('_id', $id)->update($Update_Staff);
		Session::flash('success', 'Staff updated successfully!');
		return redirect('admin/stafflist');
    }

    public function destroy($id) {
		checkPermission('delete_staff');
        if($id!='') {
			DB::table('users')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Staff deleted successfully!');
		return redirect('admin/stafflist');
    }
	
	public function exportStaff(){
		ini_set('max_execution_time', 3600);
		ini_set('memory_limit','64M');	
	
		$filename = "all_staff.csv";
		$fp = fopen('php://output', 'w');
		
		//define column name
		$header = array(
			'name',
			'father_name',
			'mobile', 
			'email',
			'phone_no', 
			'address',
			'per_address', 
			'dob',  
			'designation', 
			'branch', 
			'user_role'
		);	

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('users')->select('name', 'father_name', 'mobile', 'email', 'phone_no', 'address', 'per_address', 'dob', 'designation', 'branch', 'user_role')->orderBy('created_at', 'DESC')->get()->toarray();

		$i=0;
		foreach($data as $row){ 
			$i++;
			
			$rowData = array(
				$row['name'],
				$row['father_name'],
				$row['mobile'], 
				$row['email'], 
				$row['phone_no'], 
				$row['address'], 
				$row['per_address'], 
				$row['dob'],
				implode(',', $row['designation']),
				$row['branch'],
				$row['user_role'],
			);	
			/* if($i==2){
				pr($rowData); die;
			} */
			fputcsv($fp, $rowData);			
		}
		exit;
	}
	
	
	public function user_replace(){
		 return view('general.staff_management.user_replace');
	}
	
	public function user_update(Request $request){
		$post  = $request->all();
		if(!empty($post)){
		//pr($post); die;
		// Old user data
		$old_user_name = trim($post['old_name']);
		$old_user_email = trim($post['old_email']);
		
		// New user data
		$new_user_name = trim($post['new_name']);
		$new_user_email = trim($post['new_email']);
		
		/* check user is unique by email id */
		$data = DB::table('users')->select('name', 'email', 'user_role', 'designation')->where('email','=',$old_user_email)->get()->toarray();
		//pr($data); die;
		if(empty($data)){
			die('Email id not match, please check.');
		}
		$cnt_user = count($data);
		if($cnt_user>1){
			echo 'Please check email-id="'.$old_user_email.'". it assign more than one user.';
			exit();
		}elseif($new_user_name=='' || $new_user_email==''){
			echo 'New name OR email is blank, Please check.';
			exit();
		}elseif($cnt_user==1){
			$u_oid = (array) $data[0]['_id'];
			$u_id = $u_oid['oid'];
			$u_name = $data[0]['name'];
			$u_designation = $data[0]['designation'];
			
			// first update users table 
			$userData = array("name"=>$new_user_name,"email"=>$new_user_email);
			$up2 = DB::table('users')->where('_id',$u_id)->update($userData);
			
			/* [designation] => Array
                (
                    [sales_agent] => sales_agent
                    [crm] => crm
                    [merchant] => merchant
                ) 
				
				s_person_data:
						s_id:"5eaaacf3d049046939450c42"
						s_value:"Mr. Ashwani Sharma"
				---
				crm_data:
				c_id:"5eb53dcdd049046d67591ff2"
				c_value:"Ms. Vaishali Singhal"
				---
				
				merchant_data:
				m_id:"5eb53178d0490461fb449cd2"
				m_value:"Ms. Rashmi Sharma"
				
				//'sales_executive,sales_agent,management_sales_executive'
				*/
				//pi_fms_bulk_data
				//pi_fms_sample_data
			$msg = 'All changes done.';
			if(array_key_exists('sales_executive', $u_designation) || array_key_exists('sales_agent', $u_designation) || array_key_exists('management_sales_executive', $u_designation))
			{
				//echo "sales_agent==="; die;
				$updateData = array(
									's_person_data'=>array(
														's_id'=>$u_id,
														's_value'=>$new_user_name
														)
									);
				
				$up = DB::table('pi_fms_bulk_data')->where('s_person_data.s_id',$u_id)->update($updateData);
				$up1 = DB::table('pi_fms_sample_data')->where('s_person_data.s_id',$u_id)->update($updateData);
				$up1 = DB::table('samplecards_fms_data')->where('s_person_data.s_id',$u_id)->update($updateData);
				if($up){
						$msg = 'DONE Updated sales person.';
				}
				
			}
			if(array_key_exists('crm', $u_designation)){
				//echo "crm===";
				$updateData = array(
									'crm_data'=>array(
													'c_id'=>$u_id,
													'c_value'=>$new_user_name
													)
									);
				
				$up = DB::table('pi_fms_bulk_data')->where('crm_data.c_id',$u_id)->update($updateData);
				$up1 = DB::table('pi_fms_sample_data')->where('crm_data.c_id',$u_id)->update($updateData);
				$up1 = DB::table('samplecards_fms_data')->where('crm_data.c_id',$u_id)->update($updateData);
				if($up){
					$msg = 'DONE Updated CRM.';
				}
			}
			if(array_key_exists('merchant', $u_designation)){
				//echo "merchant==";
				$updateData = array(
									'merchant_data'=>array(
													'm_id'=>$u_id,
													'm_value'=>$new_user_name
													)
									);
				
				$up = DB::table('pi_fms_bulk_data')->where('merchant_data.m_id',$u_id)->update($updateData);
				$up1 = DB::table('pi_fms_sample_data')->where('merchant_data.m_id',$u_id)->update($updateData);
				$up1 = DB::table('samplecards_fms_data')->where('merchant_data.m_id',$u_id)->update($updateData);
				if($up){
					$msg = 'DONE Updated merchant.';
				}
			}
			
		}
		$msg = 'All changes done.';
	}else{
		$msg = 'Please fill all required fields.';
	}
	
		Session::flash('success', $msg);
		return redirect('admin/user_replace');
		
	}
}