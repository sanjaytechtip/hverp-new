<?php
//when update foldername or name of the file run cmd on root --> composer dump-autoload
use App\Fms;
use App\Fmstask;
use App\Setting;
use App\DropdownList;
use App\User;
use App\Item;
use App\Client;
use App\Fmsstep;
use App\Fmsdatas;
use App\General\DropdownModel;
use App\General\ArticleModel;
use Illuminate\Support\Facades\Hash;

use MongoDB\BSON\UTCDateTime;
error_reporting(0);



if(!function_exists('pr'))
{
	function pr($arr){
		echo "<pre>"; 
		print_r($arr);
		echo "</pre>";
	}
}

if(!function_exists('getUserNameMongo')){
		function getUserNameMongo($id){
			if(isset($id) && !empty($id)){
				$output =  User::where('_id',$id)->first();
				return $output['full_name'];
								 
			}
		}
}

if(!function_exists('checkPermission'))
{
	function checkPermission($per_val=''){
		$currentUserId = Auth::user()->id;		
		$userlist = DB::table('users')->where('id',$currentUserId)->whereIn('role', [$per_val])->first();
		if(!empty($userlist) || $currentUserId=='5c5966020f0e7526c00021eb'){
			return true;
		}else{ 
			//return true;
			$url = url('/admin/usernotallow');
			header('Location: '.$url); die();
		}
	}
}

/* Starting for PP Fms */

/* if(!function_exists('getItemColor'))
{
	function getItemColor(){
		$data = array(
			'red'    => 'Red',
			'yellow' => 'Yellow',
			'blue'   => 'Blue',
			'black'  => 'Black',
			'pink'   => 'Pink',
			'green'  => 'Green',
		);
		return $data;		
	}
} */
if(!function_exists('getOptionArray'))
{
	function getOptionArray($key='colour'){
		$res = '';
		$data = DB::table('dropdown_items')
				->where('key', '=', $key='')
				->get()->toArray();
		/* echo "<pre>"; print_r($data); die; */
		if(!empty($data)){
			$items = $data[0]['items'];
			if($items!=''){
				$res = $items;
			}else{
				$res = '';
			}
		}
		
		return $res;	
	}
}
if(!function_exists('getStatus'))
{
	function getStatus($data=0){
		$array = array('1'=>'Approve', '0'=>'Pending', '2'=> 'Denied');
		
		return $array[$data];	
	}
}
if(!function_exists('getAgentList'))
{
	function getAgentList($inputname='', $firstLabel='---Select---', $selected=''){
		$data = DB::table('agents')
				->select('name')
				->get()->toArray();
				
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" ><option value="">'.$firstLabel.'</option>';
				foreach($data as $row)
				{
					if($selected==$row['_id']){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['name'].'</option>';
				}
				 echo $dropdownHtml .= '</select>';
			}
	}
}
if(!function_exists('getBrandsDropDown'))
{
	function getBrandsDropDown($inputname='', $firstLabel='---Select---', $selected='', $opid=""){
		$data = DB::table('brand')
				->select('*')
				->get()->toArray();
				
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'"  '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($data as $row)
				{
					if($selected==$row['_id']){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['brand'].'</option>';
				}
				 echo $dropdownHtml .= '</select>';
			}
	}
}

// getSupplierData
if(!function_exists('getSupplierData'))
{
	function getSupplierData($id=''){
		if($id!=''){
			$suppliers = DB::table('supplier_form')->select('_id','company_name')->where('_id', $id)->get()->toArray();
		}else{
			$suppliers = DB::table('supplier_form')->select('_id','company_name')->get()->toArray();
		} 
		//$suppliers = DB::table('supplier_form')->select('_id','company_name')->get()->toArray();
		//pr($suppliers);
		return $suppliers;
	}
}


if(!function_exists('getSupplierDataById'))
{
	function getSupplierDataById($id){
		$suppliers = DB::table('supplier_form')->where('_id', $id)->get()->toArray();
		return $suppliers[0];
	}
}

/* if(!function_exists('buyerDeliveryAddress'))
{
	function buyerDeliveryAddress($id,$selected=''){
		$data = DB::table('register_form')
				->select('delivery_address')
				->where('_id', '=', $id)
				->first();
				
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				$dropdownHtml = '';
				foreach($data['delivery_address'] as $key=>$row)
				{
					if($selected==$row){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					
					if($row!=''){
					$dropdownHtml .= '<option '.$sel.' value="'.$row.'">'.$row.'</option>';
					}
				}
				 return $dropdownHtml;
			}
	}
} */


if(!function_exists('buyerDeliveryAddress'))
{
	function buyerDeliveryAddress($id, $selected=''){
		$data = DB::table('register_form')
				->select('company_address','delivery_address')
				->where('_id', '=', $id)
				->first();		
			if(!empty($data)){
					$dropdownHtml = '';
					$already_sel=0;
					if($selected==$data['company_address']){
						$dropdownHtml .= '<option selected value="'.$data['company_address'].'">'.$data['company_address'].'</option>';
						$already_sel=1;
					}else{
						$dropdownHtml .= '<option selected value="'.$data['company_address'].'">'.$data['company_address'].'</option>';
					}
				
				
					foreach($data['delivery_address'] as $key=>$row)
					{
						if($selected==$row && $already_sel==0){
							$sel = ' selected';
						}else{
							$sel = '';
						}
						
						if($row!=''){
						$dropdownHtml .= '<option '.$sel.' value="'.$row.'">'.$row.'</option>';
						}
					}
				return $dropdownHtml;
			}
	}
}

if(!function_exists('customerDeliveryAddress'))
{
	function customerDeliveryAddress($id, $selected=''){
		$data = DB::table('register_form')
				->select('company_address','delivery_address')
				->where('_id', '=', $id)
				->first();		
		if(!empty($data)){
				$dropdownHtml = '';
				$already_sel=0;
				if($selected==$data['company_address']){
					$dropdownHtml .= '<option selected value="'.$data['company_address'].'">'.$data['company_address'].'</option>';
					$already_sel=1;
				}else{
					$dropdownHtml .= '<option selected value="'.$data['company_address'].'">'.$data['company_address'].'</option>';
				}
				
				
				foreach($data['delivery_address'] as $key=>$row)
				{
					if($selected==$row && $already_sel==0){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					
					if($row!=''){
					$dropdownHtml .= '<option '.$sel.' value="'.$row.'">'.$row.'</option>';
					}
				}
				 return $dropdownHtml;
			
			
				 //return $data['company_address'];
			}
	}
}


if(!function_exists('GetBuyerName'))
{
	function GetBuyerName($id){
		$data = DB::table('register_form')
				->select('company_name')
				->where('_id', '=', $id)
				->first();
				
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				 return $data['company_name'];
			}
	}
}

if(!function_exists('getCustomerId'))
{
	function getCustomerId($company_name){
		$data = DB::table('register_form')
				->select('*')
				->where('company_name', 'LIKE', '%' .$company_name. '%')
				->first();
				
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				 return $data['_id'];
			}
	}
}

if(!function_exists('GetBrandName'))
{
	function GetBrandName($id){
		$object = DB::table('brand')
				->select('brand')
				->where('id', '=', $id)
				->first();
				$data = json_decode(json_encode($object), true);	
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				 return $data['brand'];
			}
	}
}

if(!function_exists('GetBrandId'))
{
	function GetBrandId($brand_name){
		$object = DB::table('brand')
				->select('*')
				->where('brand', 'LIKE', '%' .$brand_name. '%')
				->first();
				$data = json_decode(json_encode($object), true);
		//echo "<pre>"; print_r($data); die;		
		if(!empty($data)){
				return $data['id'];
			}
	}
}


if(!function_exists('getOptionDropdown')) {
	function getOptionDropdown($key='colour',$inputname='',$firstLabel='---Select---',$selected='', $opid=""){
		$items = getOptionArray($key);
		if(!empty($items)){
			$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
			foreach($items as $key=>$val) {
				if($selected==$key){
					$sel = ' selected';
				}else{
					$sel = '';
				}
				$dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
			}
			return $dropdownHtml .= '</select>';
		}			
	}
}

if(!function_exists('documents_required_supplier')) {
	function documents_required_supplier($key='colour',$inputname='', $checked='',$opid="" ){
		$items = getOptionArray($key);
		if(!empty($items)){
			$dropdownHtml = '<br>';
			foreach($items as $key=>$val) {
				if($checked==$key){
					$sel = ' checked';
				}else{
					$sel = '';
				}
				$dropdownHtml .= '<input type="checkbox" name="'.$inputname.'" '.$opid.' '.$sel.' value="'.$key.'"><label for="'.$key.'">'.$val.'</label><br>';
			}
			return $dropdownHtml;
		}			
	}
}

if(!function_exists('getOptionDropdownNovalue'))
{
	function getOptionDropdownNovalue($key='colour',$inputname='',$firstLabel='---Select---',$selected='', $opid=""){

		$items = getOptionArray($key);
		
		if(!empty($items)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($items as $key=>$val)
				{
					if($selected==$val){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.'>'.$val.'</option>';
				}
				return $dropdownHtml .= '</select>';
			}			
	}
}

if(!function_exists('getOptionDropdownSmall'))
{
	function getOptionDropdownSmall($key='colour',$inputname='',$firstLabel='---Select---',$selected='', $opid=""){

		$items = getOptionArray($key);
		
		if(!empty($items)){
				$dropdownHtml = '<select class="form-control" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($items as $key=>$val)
				{
					if($selected==$key){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
				}
				return $dropdownHtml .= '</select>';
			}			
	}
}


if(!function_exists('getOptionDropdownSmall'))
{
	function getOptionDropdownSmall($key='colour',$inputname='',$firstLabel='---Select---',$selected='', $opid=""){

		$items = getOptionArray($key);
		
		if(!empty($items)){
				$dropdownHtml = '<select class="form-control" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($items as $key=>$val)
				{
					if($selected==$key){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
				}
				return $dropdownHtml .= '</select>';
			}			
	}
}

if(!function_exists('getOptionDropdownInvoice'))
{
	function getOptionDropdownInvoice($key='colour',$inputname='',$firstLabel='---Select---',$selected=''){

		$items = getOptionArray($key);
		
		if(!empty($items)){
				$dropdownHtml = '<option value="">'.$firstLabel.'</option>';
				foreach($items as $key=>$val)
				{
					if($selected==$key){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
				}
				return $dropdownHtml;
			}			
	}
}

if(!function_exists('getOptionHSNCode'))
{
	function getOptionHSNCode($key='hsncode',$inputname='',$firstLabel='---Select---',$selected=''){

		$items = DB::table('hsncode')->get();
	 //pr($items); die;
		if(!empty($items)){
				$dropdownHtml = '<option value="">'.$firstLabel.'</option>';
				foreach($items as $key=>$val)
				{
				$dropdownHtml .= '<option value="'.$val['id'].'">'.$val['hsn_code'].'</option>';
				}
				return $dropdownHtml;
			}			
	}
}

if(!function_exists('getState'))
{
	function getState(){
		$items = DB::table('state_cities')
				->select('name')
				->get()->toArray();
		//echo "<pre>"; print_r($items); die;
		$res =array();
		if(!empty($items)){
			
			foreach($items as $row){
				$res[] = $row['name'];
			}
			
		}
		return $res;	
	}
} 


if(!function_exists('getAgentDetails'))
{
	function getAgentDetails($id){
	    if($id!=0)
		{
		$data = DB::table('users')->where('id', '=', $id)->first();
		if($data!=''){
			$res = $data['full_name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}
/* Start Dhruv 30-03-2020 For Agent/Sales Person and Merchant Name in brand list */

if(!function_exists('getAgentMerchant'))
{
	function getAgentMerchant($id){
	    if($id!=0)
		{
		$data = DB::table('users')->where('_id', '=', $id)->first();
		if($data!=''){
			$res = $data['name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}

/* End Dhruv 30-03-2020 For Agent/Sales Person and Merchant Name in brand list */

function globalEmailTemplate(){
	     $name = array(
				'company_name' => '##COMPANY_NAME##',
				'company_address' => '##COMPANY_ADDRESS##',
				'state' => '##STATE##',
				'city' => '##CITY##',
				'name_of_md' => '##MD_NAME##',
				'mobile_no_md' => '##MD_MOBILE##',
				'landline_no_comp' => '##MD_LANDLINE##',
				'email_id_md' => '##MD_EMAIL#',
				'company_type' => '##COMPANY_TYPE##',
				'gst' => '##GST_NO##',
				'pan_no' => '##PAN_NO##',
				'agent' => '##SALES_PERSON_OF_PASHUPATI##',
				'size_of_company' => '##COMPANY_SIZE##',
				'no_of_employee' => '##NO_OF_EMPLOYEE##',
				'garment_category' => '##GARMENT_CATEGORY##',
				'delivery_address' => '##DELIVERY_ADDRESS##',
				'contact_person_dispatch' => '##DISPATCH_CONTACT_PERSON##',
				'mobile_no_dispatch' => '##DISPATCH_MOBILE_NO##',
				'email_id_dispatch' => '##DISPATCH_EMAIL_ID##',
				'name_merchant_1' => '##MERCHANT_NAME1##',
				'mobile_merch1' => '##MERCHANT_MOBILE1##',
				'email_merch1' => '##MERCHANT_EMAIL1##',
				'name_merchant_2' => '##MERCHANT_NAME2##',
				'mobile_merch2' => '##MERCHANT_MOBILE2##',
				'email_merch2' => '##MERCHANT_EMAIL2##',
				'name_account_head' => '##ACCOUNT_HEAD_NAME##',
				'mobile_ac_head' => '##ACCOUNT_HEAD_MOBILE##',
				'email_ac_head' => '##EMAIL_ACCOUNT_HEAD##',
				'name_account_1' => '##ACCOUNT_NAME1##',
				'mobile_ac_1' => '##ACCOUNT_MOBILE1##',
				'email_ac_1' => '##ACCOUNT_EMAIL1##',
				'name_account_2' => '##ACCOUNT_NAME2##',
				'mobile_ac_2' => '##ACCOUNT_MOBILE2##',
				'email_ac_2' => '##ACCOUNT_EMAIL2##',
				'bank_name' => '##BANK_NAME##',
				'bank_address' => '##BANK_ADDRESS##',
				'account_type' => '##ACCOUNT_TYPE##',
				'ifsc_code' => '##IFSC_CODE##',
				'account_no' => '##ACCOUNT_NUM##',
				'micr_code' => '##MICR_CODE##',
				'gst_reg_copy' => '##GST_REGISTRATION_COPY##',
				'pan_card_copy' => '##PAN_CARD_COPY##',
				'cncl_chk_copy' => '##CANCELLED_CHEQUE_COPY##',
			);
	    return $name;				
	}


if(!function_exists('getCitiesByState'))
{
	function getCitiesByState($name){
		$items = DB::table('state_cities')
				->select('cities')
				->where('name', '=', $name)
				->get()->toArray();
				
		//echo "<pre>"; print_r($items[0]['cities']); die;
		$res =array();
		if(!empty($items[0]['cities'])){			
			foreach($items[0]['cities'] as $key=>$val){
				$res[] = $val;
			}
			
		}
		return $res;	
	}
}

/* Ending of PP Fms */

if(!function_exists('getFmsNameById'))
{
	function getFmsNameById($fms_id){
		$data = DB::table('fmss')
				->where('_id', '=', $fms_id)
				->get()->toArray();
		//echo "<pre>"; print_r($data[0]['fms_name']); die;
		$fms_name = $data[0]['fms_name'];
		if($fms_name!=''){
			$res = $fms_name;
		}else{
			$res = '';
		}
		return $res;		
	}
}

if(!function_exists('getFmsDetailsById'))
{
	function getFmsDetailsById($fms_id){
		$data = DB::table('fmss')
				->where('_id', '=', $fms_id)
				->get()->toArray();
		$res = $data[0];
		return $res;		
	}
}

if(!function_exists('getUserList'))
{
	function getUserList($fms_id){
		$currentUserId = Auth::user()->id;		
		$userlist = DB::table('users')->whereIn('access_fms_id', [$fms_id])->get();
		//echo "<pre>"; print_r($userlist );
		return $userlist;		
	}
}

if(!function_exists('getUserFMSrole'))
{
	function getUserFMSrole(){
		$currentUserId = Auth::user()->id;		
		$userlist = DB::table('users')->where('id', $currentUserId)->get()->first();
		//echo "<pre>"; print_r($userlist );
		
		if(@$userlist->fms_role!='non_admin')
		{
		return 1;		
		}else{
		return 0;  
		}
	}
}

if(!function_exists('getAllUserList'))
{
	function getAllUserList(){	
		$userlist = DB::table('users')->select('id','name','email', 'user_role')->where('status', '1')->where('user_role', '!=', '1')->get()->toArray();
		//echo "<pre>"; print_r($userlist ); die;
		return $userlist;		
	}
}




if(!function_exists('getDropdownListById'))
{
	function getDropdownListById($id){
		$dropdownlist = DropdownList::where('id', '=', $id)->get()->toArray();
		$list_label = json_decode($dropdownlist[0]['labels']);
		//echo "<pre>"; print_r($list_label);
		
		$dropdownHtml='';
		if(!empty($list_label)){
			$dropdownHtml = '<ul>';
			foreach($list_label as $label)
			{
				$dropdownHtml .= '<li>'.$label->label_name.'</li>';
			} 
		
			return $dropdownHtml .= '</ul>';
		}else{
			return 'No data found';
		}
		
		
		
	}
}

if(!function_exists('getDropdownListingById'))
{
	function getDropdownListingById($id){  
	
	$dropdownlist = DB::table('dropdown_items')->where('id', '=', $id)->get()->toArray();
	$sorted_array = $dropdownlist[0]['items'];
	asort($sorted_array);
		$dropdownHtml='';
		if(!empty($sorted_array)){
			$dropdownHtml = '<ul>';
			foreach($sorted_array as $label)
			{
				$dropdownHtml .= '<li>'.$label.'</li>';
			} 
		
			return $dropdownHtml .= '</ul>';
		}else{
			return 'No data found';
		}
		
		
	}
}


if(!function_exists('getcompanyTypeListingById'))
{
	function getcompanyTypeListingById($id){  
	
	$dropdownlist = DB::table('companyType_items')->where('id', '=', $id)->get()->toArray();
	$sorted_array = $dropdownlist[0]['items'];
	asort($sorted_array);
		$dropdownHtml='';
		if(!empty($sorted_array)){
			$dropdownHtml = '<ul>';
			foreach($sorted_array as $label)
			{
				$dropdownHtml .= '<li>'.$label.'</li>';
			} 
		
			return $dropdownHtml .= '</ul>';
		}else{
			return 'No data found';
		}
		
		
	}
}


if(!function_exists('DateFormate')){
function DateFormate($date = '', $format = ''){
    if($date == '' && $format == ''){
		return '';
	}else if($date != '' && $format != ''){
		return date($format,strtotime($date));
	}else if($date != '' && $format == ''){
		return date('d M, Y',strtotime($date));
	}
}
}



if(!function_exists('getTaskListByFmsId'))
{
	function getTaskListByFmsId($fms_id){
		$taskdatas = Fmstask::where('fms_id', '=', $fms_id)->get()->toArray();
		//echo "<pre>"; print_r($taskdatas); die;
		$dropdownHtml='';
		if(!empty($taskdatas)){
				$dropdownHtml = '<select class="form-control" name="row[r_num][after_task]" required=""><option value="">--- Select ---</option>';
				foreach($taskdatas as $key=>$val)
				{
					$dropdownHtml .= '<option>'.$val['task_name'].'</option>';
				}
				return $dropdownHtml .= '</select>';
			}else{
				return 'No data found';
			}
		
	}
}



if(!function_exists('getTaskInput'))
{
	function getTaskInput($task_id, $field_name){
		$taskdatas = Fmstask::where('_id', '=', $task_id)->get()->toArray();
		//echo $taskdatas[0]['input_type']; die;
		//echo "<pre>"; print_r($taskdatas); die; 
		//echo $field_name; die;
		
		$dropdownHtml='';
		if(!empty($taskdatas[0]['input_type'])=='custom_fields'){
			return $dropdownHtml .= '<input type="'.$taskdatas[0]['custom_type'].'" name="'.$field_name.'">';
		}else{
			return 'No data found';
		}
		
	}
}


if(!function_exists('builtOptionsList'))
{
	function builtOptionsList($task_id, $dropdown_type){
		
		if($dropdown_type=='orders')
		{
			$orderApiData = get_stock_order_api(); // from helper_api.php
			
			$dropdownHtml='';
			if(!empty($orderApiData)){
				$dropdownHtml = '<select class="form-control" name="row[r_num][task]['.$task_id.']" required=""><option value="">--- Select ---</option>';
				$k=0;
				foreach($orderApiData as $data)
				{
					$order = (array) $data;
					$order_no = explode('_', $order['o_unique_no']);
					
					$dropdownHtml .= '<option>'.$order_no[0].'</option>';
				}
				return $dropdownHtml .= '</select>';
			}else{
				return 'No data found';
			}
		}else if($dropdown_type=='employee')
		{
			$usrApiData = getuserapi(); // from helper_api.php
			
			$dropdownHtml='';
			if(!empty($usrApiData)){
				$dropdownHtml = '<select class="form-control" name="row[r_num][task]['.$task_id.']" required=""><option value="">--- Select ---</option>';
				$k=0;
				foreach($usrApiData as $data)
				{
					$user = (array) $data;
					$dropdownHtml .= '<option>'.$user['user_fname'].' '.$user['user_lname'].'</option>';
				}
				return $dropdownHtml .= '</select>';
			}else{
				return 'No data found';
			}
		}else if($dropdown_type=='items')
		{
			$itemdata = get_item_list_api(); // from helper_api.php
			$dropdownHtml='';
			if(!empty($itemdata)){
				$dropdownHtml = '<select class="form-control" name="row[r_num][task]['.$task_id.']" required=""><option value="">--- Select ---</option>';
				foreach($itemdata as $data)
				{
					//$item = (array) $data;
					$dropdownHtml .= '<option>'.$data.'</option>';
				}
				$dropdownHtml .= '</select>';
				return addslashes($dropdownHtml);
			}else{
				return 'No data found';
			}
			
			
		}else if($dropdown_type=='fabricators')
		{
			$htmlSteps='';
				
				$fbdata = get_fabricator_api();
				$dropdownHtml='';
				if(!empty($fbdata)){
					$dropdownHtml = '<select class="form-control" name="row[r_num][task]['.$task_id.']" required=""><option value="">--- Select ---</option>';
					$k=0;
					
					$in_house='';
					$Outsource='';
					foreach($fbdata as $data)
					{
						$fbname = (array) $data;
						
						if($fbname['designation']==7){
							$in_house .= '<option>'.$fbname['f_name'].'</option>';
						}else{
							$Outsource .= '<option>'.$fbname['f_name'].'</option>';
						}						
					}
					
					$dropdownHtml .= '<optgroup label="Fabricator (in-House)">';
					$dropdownHtml .= $in_house;
					$dropdownHtml .='</optgroup>';
					
					$dropdownHtml .= '<optgroup label="Fabricator (Outsource)">';
					$dropdownHtml .= $Outsource;
					$dropdownHtml .='</optgroup>';
					
					$dropdownHtml .= '</select>';
				}
				$htmlSteps.=$dropdownHtml;
			
			return $htmlSteps;
			
		} else{
			return 'No data found';
		}
	}
}

if(!function_exists('customOptionsList'))
{
	function customOptionsList($inputName=''){
		$dropdownlist = DropdownList::select('_id','dropdown_name')->get()->toArray();
		$dropdownHtml='';
		if(!empty($dropdownlist)){
			$dropdownHtml = '<select class="form-control custom_type" name="'.$inputName.'"><option value="">--- Select ---</option>';
			foreach($dropdownlist as $key=>$val)
			{
				$dropdownHtml .= '<option value='.$val['_id'].'>'.$val['dropdown_name'].'</option>';
			} 
		
			return $dropdownHtml .= '</select>';
		}else{
			return 'No data found';
		}
		
	}
}


if(!function_exists('buildOptionsList'))
{
	function buildOptionsList($inputName=''){
		
		if(!empty($inputName)){
			$dropdownHtml = '<select class="form-control custom_type" name="'.$inputName.'"><option value="">--- Select ---</option>';
			$dropdownHtml .= '<option value="employee">Employee</option>';		
			$dropdownHtml .= '<option value="items">Items</option>';		
			$dropdownHtml .= '<option value="fabricators">Fabricators</option>';
			$dropdownHtml .= '<option value="order_embroidery">Order Embroidery</option>';
			$dropdownHtml .= '<option value="client_list">Client Name</option>';
			$dropdownHtml .= '<option value="sample_designer">Sample Designer</option>';
			return $dropdownHtml .= '</select>';
		}else{
			return 'No data found';
		}
		
	}
}


if(!function_exists('buildOptionsListStep'))
{
	function buildOptionsListStep(){
		$dropdownHtml='';
		/* $dropdownHtml .= '<option value="order_embroidery">Order Embroidery</option>';
		$dropdownHtml .= '<option value="fabric_name">Fabric Name</option>';
		$dropdownHtml .= '<option value="master_name">Master Name</option>';
		$dropdownHtml .= '<option value="tailor_name">Tailor Name</option>';
		$dropdownHtml .= '<option value="patchers_name">Patchers Name</option>';
		$dropdownHtml .= '<option value="embroider_name">Embroider Name</option>'; */
		$dropdownHtml .= '<option value="option_1">Option 1</option>';
		$dropdownHtml .= '<option value="option_2">Option 2</option>';
		return $dropdownHtml;
	}
}

if(!function_exists('customOptionsListData'))
{
	function customOptionsListData($task_id, $dropdown_id){
		
		//echo "<pre>".$task_id.'====='.$dropdown_id; die;		
		$dropdownlist = DropdownList::where('_id', '=', $dropdown_id)->get()->toarray();		
		//echo "<pre>"; print_r($dropdownlist); die;
		
		$labels = $dropdownlist[0]['labels'];  
		$labels = json_decode($labels);
		
		$dropdownHtml='';
		if(!empty($dropdownlist)){
			$dropdownHtml = '<select class="form-control" name="row[r_num][task]['.$task_id.']" required=""><option value="">--- Select ---</option>';
			foreach($labels as $label)
			{
				$dropdownHtml .= '<option value='.$label->label_id.'>'.$label->label_name.'</option>';
			} 
		
			return $dropdownHtml .= '</select>';
		}else{
			return 'No data found';
		} 
			
	}
}

if(!function_exists('getLeftMenu'))
{
	function getLeftMenu(){
		//$fmsdata = Fmstask::where('fms_id', '=', $fms_id)->get()->toarray();
		return $fmsdata = Setting::all();
		/* echo "<pre>";
		print_r($fmsdata); 
		die; */
		//return $fmsdata->count();
	}
}

if(!function_exists('isTaskDone'))
{
	function isTaskDone($fms_id){
		//$fmsdata = Fmstask::where('fms_id', '=', $fms_id)->get()->toarray();
		return $fmsdata = Fmstask::where('fms_id', '=', $fms_id)->count();
		/* echo "<pre>";
		print_r($fmsdata); */
		//return $fmsdata->count();
	}
}

function getName()
{
	/* $data = Fms::all();
	echo "<pre>"; print_r($data);  */
	return "hiiii 22";
	/* $fms = new Fms;
	echo $fms->getFmsData().'</pre>'; die; */
}


function getCurUserField($field = '_id')
{
	$user = auth()->user();
	//echo'<pre>';print($user);echo'</pre>';die;
	//print($user->email);
	return $user->$field;
}

function getCompanyId()
{
	$user = auth()->user();
	//echo'<pre>';print($user);echo'</pre>';die;
	if($user->member_id ==0){ // from super admin to admin/user
		return $user->_id;
	}else{
		return $user->member_id;
	}
}

function weekDays(){
	
	$days = [	'Sunday',
				'Monday',
				'Tuesday',
				'Wednesday',
				'Thursday',
				'Friday',
				'Saturday'
			];
			
	return 	$days;	
}

function user_role(){
						
		$role = array(
				//'1' => 'Super Admin',
				'2' => 'Admin',
				'3' => 'Staff'
			);
			
	return 	$role;				
	
}

function user_role_name($index = '2')
{
						
		$role = user_role();
		//$index = (int)$index ;	
		return 	$role[$index];				
	
}

if(!function_exists('globalDateformat()'))
{
	function globalDateformat($date)
	{
		$new_date = date('d, M Y H:i:s', strtotime($date));		
		return $new_date;
		
		
	}
	
}

if(!function_exists('globalDateformatFMSDate()'))
{
	function globalDateformatFMSDate($date)
	{
		$new_date = date('d.m.Y H:i', strtotime($date));		
		return $new_date;
		
		
	}
	
}

if(!function_exists('globalDateformatItem()'))
{
	function globalDateformatItem($date)
	{
	    if($date!=''){
		$new_date = date('d-M-Y', strtotime($date));		
		return $new_date;
	    }else{
	        return '';
	    }
		
		
	}
	
}


if(!function_exists('globalDateformatNet()'))
{
	function globalDateformatNet($date)
	{
	    if($date!=''){
		$new_date = date('d-m-Y', strtotime($date));		
		return $new_date;
	    }else{
	        return '';
	    }
		
		
	}
	
}


if(!function_exists('globalDateformatFMS()'))
{
	function globalDateformatFMS($date)
	{
		$new_date = date('d-m-Y H:i:s', strtotime($date));		
		return $new_date;
		
		
	}
	
}


if(!function_exists('getDateFieldTaskByFmsId'))
{
	function getDateFieldTaskByFmsId($fms_id)
	{
		/* $dateTask = Fmstask::where('fms_id', '=', $fms_id)
		->where('field_type', '=', 'pi_date')->orWhere('field_type', '=', 'po_date')->get()->toArray();	 */	
	
		$dateTask = Fmstask::where('fms_id', '=', $fms_id)
		->where(function ($q) {
						$q->where('field_type', '=', 'pi_date')->orWhere('field_type', '=', 'po_date');
					})->get()->toArray();
		
			$dropdownHtml='';
			if(!empty($dateTask)){
				$dropdownHtml = '<select class="form-control task_id_link" name="row[r_num][task_id_link]" onchange="taskDateDropdown(this.value, this.id)" id="task_r_num" ><option value="">--- Select ---</option>';
				foreach($dateTask as $key=>$val)
				{
					$dropdownHtml .= '<option value="'.$val['_id'].'">'.$val['task_name'].'</option>';
				}
				return $dropdownHtml .= '</select>';
			}else{
				return 'No data found';
			}
	}
}

if(!function_exists('changeDateToDmy()'))
{
	function changeDateToDmy($date)
	{
		if(strtotime($date)>0)
		{
			$new_date = date('d-m-Y', strtotime($date));		
			return $new_date;
		}else{
			return '';
		}
		
		
		
	}
	
}

if(!function_exists('changeDateToDmyHi_old()'))
{
	function changeDateToDmyHi_old($date)
	{
		if(strtotime($date)>0)
		{
			$new_date = date('d.m.y H:i', strtotime($date));		
			return $new_date;
		}else{
			return '';
		}
	}
}

if(!function_exists('changeDateToDmyHi()'))
{
	function changeDateToDmyHi($date)
	{
		if(strtotime($date)>0)
		{
			$new_date = date('d.m.y', strtotime($date));		
			return $new_date;
		}else{
			return '';
		}
	}
}


if(!function_exists('dateCompare()'))
{
	function dateCompare($planed_date, $actual_date)
	{	
		/* $planed_date_check = date('Y/m/d H:i', strtotime($planed_date));
		$actual_date_check = date('Y/m/d H:i', strtotime($actual_date)); */
		
		$planed_date_check = date('Y/m/d', strtotime($planed_date));
		
		if($actual_date!=''){
			$actual_date_check = date('Y/m/d', strtotime($actual_date));
		}else{
			$actual_date_check = '';
		}
		
		
		$today = date('Y/m/d');
		
		$resHtml='';
		if($planed_date_check!='' && $actual_date_check==''){
			$ylColor='';
			if($today>=$planed_date_check){
				$ylColor=BG_YELLOW;
			}
			
			$resHtml= array(
									'bgcolor'=>$ylColor,
									'textcolor'=>'#757575',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
								
		}else if($planed_date_check>$actual_date_check && $actual_date_check!='')
		{
			$resHtml= array(
									'bgcolor'=>BG_GREEN,  // Green: 99da72
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
			
		}else if($planed_date_check==$actual_date_check && $actual_date_check!=''){
			$resHtml= array(
									'bgcolor'=>BG_GREEN,
									'textcolor'=>'#757575',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
								
		}else{
			$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
		}
		
		if($planed_date=='none' && $actual_date_check!='')
		{
			$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#757575',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
		}
		
		return $resHtml;
		
	}
	
}


if(!function_exists('client_payterm_arr'))    
{
    function client_payterm_arr()
	{
		$data = array(
						'pday1'=>'07',
						'pday2'=>'30',
						'pday3'=>'45',
						'pday4'=>'60',
						'pday5'=>'90',
						'pday6'=>'More than 90',
					);		
		return $data;
	}
}


if(!function_exists('item_cat_arr'))    
{
    function item_cat_arr()
	{
		$data = \DB::table('category')->get()->toArray();
		return $data;
	}
}

if(!function_exists('item_element_arr'))    
{
    function item_element_arr()
	{
		$data = \DB::table('elements')->get()->toArray();		
		return $data;
	}
}

if(!function_exists('all_fms_list'))    
{
    function all_fms_list()
	{
		$data = \DB::table('fmss')->get()->toArray();		
		return $data;
	}
}



if(!function_exists('user_fms_roles'))
{
    function user_fms_roles()
	{
		$data = array(
							'1'=>'Can Create FMS',
							'2'=>'Can Manage FMS',
							'3'=>'Can Only view FMS',
					);		
		return $data;
	}
}



if(!function_exists('fms_stage_arr'))
{
    function fms_stage_arr()
	{
		$data = array(
						'1'=>'Not Started',
						'2'=>'Fabric Ready',
						'3'=>'Embr. Ready',
						'4'=>'On Stitching',
						'5'=>'No Patching',
						'6'=>'Patching 1 Done',
						'7'=>'Stitched',
						'8'=>'Patching 2 Done',
						'9'=>'Hemming',
						'10'=>'Finished',
						'11'=>'Packed',
					);		
		return $data;
	}
}


if(!function_exists('fms_show_stage_arr'))
{
    function fms_show_stage_arr()
	{
		$data = array(
			'Packed',
			'Finished',
			'Hemming',
			'Patching 2 Done',
			'Stitched',
			'Patching 1 Done',
			'No Patching',
			'On Stitching',
			'Embr. Ready',
			'Fabric Ready',
			'Not Started'
			);		
		return $data;
	}
}

if(!function_exists('get_task_data_by_fms_id'))
{
    function get_task_data_by_fms_id($fms_id, $custom_type_val)
	{
		$dateTask = Fmstask::where('fms_id', '=', $fms_id)->where('custom_type', '=', $custom_type_val)->get()->toArray();
		
		if(!empty($dateTask[0])){
			
			return $dateTask[0];
			
		}else{
			return 'No data found';
		}
		exit;
		
	}
}


if(!function_exists('get_fabricator_data_details'))
{
    function get_fabricator_data_details($fabdatas, $fms_id)
	{	
			$item_list_with_price = get_item_list_with_price_api();
			/* pr($item_list_with_price['item_list_with_price']);
			die('===helper==');  */
			$fms_id = $fabdatas[0]['fms_id'];
		
			$itemArr = get_task_data_by_fms_id($fms_id, 'items');
			$orderArr = get_task_data_by_fms_id($fms_id, 'orders');
			$qtyArr = get_task_data_by_fms_id($fms_id, 'qty');


			/* echo "<pre>===="; print_r($qtyArr); 
			echo "<pre>"; print_r($itemArr); 
			echo "<pre>"; print_r($orderArr);  die; */

			$finalArr = array();
			$fbfinalArr = array();
			foreach($fabdatas as $fbkey=>$fbval){
				/* pr($fbval); 
				pr($itemArr); 
				die; */
				$item_name = $fbval[$itemArr['_id']];
				
				$order_numArr = $fbval[$orderArr['_id']];
				
				$order_qty = $fbval[$qtyArr['_id']];
				
				$finalArr['order_no'] = $order_numArr;
				
				$finalArr['order_qty'] = $order_qty;
				//echo "<pre>"; print_r($order_numArr);
				
				//echo "<pre>"; print_r($fbval['fabricator']);
				$finalArr['fabricator'] = $fbval['fabricator'];
				$finalArr['fb_id'] = $fbval['fb_id'];
				
				foreach($item_list_with_price['item_list_with_price'] as $ikey=>$ival){
				//echo $fbval[$itemArr['_id']]."===<br>";
				/* pr($ival); 
				echo $item_name.'===';
				die; */
					$itemvalArr = $ival;
					if($itemvalArr['i_id']==$item_name)
					{
						//return $itemvalArr;
						//echo "<pre>"; print_r($itemvalArr);
						$finalArr['item']=$itemvalArr;
					}
				
				}
				
				array_push($fbfinalArr, $finalArr);
				
			}
		
		//pr($fbfinalArr); die;
		if(!empty($fbfinalArr)){
			return $fbfinalArr;
		}else{
			return 'No data found';
		}
		exit;		
	}
}

if(!function_exists('get_fms_babricators_by_fms_id'))
{
    function get_fms_babricators_by_fms_id($fms_id='')
	{
		//$dateTask = Fmsdatas::where('fms_id', '=', $fms_id)->get()->toArray();
		
		if($fms_id!='')
		{
			$data = \DB::table('fmsdatas')
				->select('fabricator', 'fb_id')
				->where('fabricator', '!=', null)
				->where('fb_id', '!=', null)
				->where('fms_id', '=', $fms_id)
				->get()
				->toArray();
		}else{
			$data = \DB::table('fmsdatas')
				->select('fabricator', 'fb_id')
				->where('fabricator', '!=', null)
				->where('fb_id', '!=', null)
				->get()
				->toArray();
		}
				
		
		if(!empty($data)){
			
			return $data;
			
		}else{
			return 'No data found';
		}
		exit;
		
	}
}



if(!function_exists('getPatchingByfmsId'))
{
    function getPatchingByfmsId($fms_id)
	{
		$data = \DB::table('fmss')
				->where('_id', '=', $fms_id)
				->get()
				->toArray();		
		
		if(!empty($data[0]) && is_array($data[0]['patching'])){
			
			//return $data[0]['patching'];
			$pdata = $data[0]['patching'];
			return $pdata;
			/* 
			foreach($pdata as $pkey=>$pval) {
				echo $pkey.'=====<br>';
				echo $pval['p_name'].'=====<br>';
				echo "<pre>"; print_r($pval['steps_id']);
			}
			echo "<pre>"; print_r($pdata); die; */
			
		}else{
			return "";
		}
		exit;
		
	}
}


if(!function_exists('get_task_id_by_fms_id'))
{
    function get_task_id_by_fms_id($fms_id)
	{
		$data = \DB::table('fmstasks')
				->select('_id')
				->where('fms_id', '=', $fms_id)
				->orderBy('step', 'asc')
				->get()
				->toArray();
	
		if(!empty($data)){
			return $data;
		}else{
			return 'No data found';
		}
		exit;
	}
}

if(!function_exists('get_step_id_by_fms_id'))
{
    function get_step_id_by_fms_id($fms_id)
	{
		$data = \DB::table('fmssteps')
				->select()
				->where('fms_id', '=', $fms_id)
				->get()
				->toArray();
	
		if(!empty($data)){
			return $data;
		}else{
			return 'No data found';
		}
		exit;
	}
}

if(!function_exists('getStepDataByStepId'))
{
    function getStepDataByStepId($step_id)
	{
		$data = \DB::table('fmssteps')
				->select()
				->where('_id', '=', $step_id)
				->orderBy('step', 'asc')
				->get()
				->toArray();
	
		if(!empty($data)){
			return $data[0];
		}else{
			return 'No data found';
		}
		exit;
	}
}

					
if(!function_exists('getStageTaskIdByFmsId'))
{
    function getStageTaskIdByFmsId($fms_id)
	{
		/* $data = \DB::table('fmstasks')
				->select()
				->where('fms_id', '=', $fms_id)
				->where('field_type', '=', 'pi_status')
				->get()
				->toArray();  */
				
		$data = \DB::table('fmstasks')
				->select()
				->where('fms_id', '=', $fms_id)
				->where(function ($q) {
						$q->where('field_type', '=', 'pi_status')->orWhere('field_type', '=', 'po_status');
					})
				->get()
				->toArray();
				
		//pr($data); die;
	
		if(!empty($data)){
			return $data[0];
		}else{
			return 'No data found';
		}
		exit;
	}
}

if(!function_exists('userHasRight'))
{
    function userHasRight()
	{
		
		$user = auth()->user();
		$user_role = $user->user_role;
		if($user_role==1 || $user_role==2){
			return true;
		}else{
			return false;
		}
	}
}
 
if(!function_exists('checkStepEndtime'))
{
    function checkStepEndtime($planed)
	{
		$dt1 = $planed;
		$dt2 = date('Y-m-d H:i:s');
		$diff = strtotime($dt1) - strtotime($dt2);
		return $hours = $diff / ( 60 * 60 );
		
	}
}


if(!function_exists('getBuiltOptionlistById'))
{
    function getBuiltOptionlistById($id)
	{
		
		$data = \DB::table('dropdownlists')
				->select()
				->where('_id', '=', $id)
				->get()
				->toArray();
				$labels = json_decode($data[0]['labels']);
				$labelArr = array();
				foreach($labels as $label){
					array_push($labelArr, $label->label_name);
				} 
		return $labelArr;
	}
}

/***** get next step details by current step ID ***/

if(!function_exists('getNextStepDetailsByCurrentStepId'))
{
    function getNextStepDetailsByCurrentStepId($step_id, $fms_id)
	{
		
		$data = \DB::table('fmssteps')
				->select()
				->where('_id', '>', $step_id)
				->where('fms_id', '=', $fms_id)
				->orderBy('_id','asc')
				->first();
		return $data;
	}
}

if(!function_exists('getFmsdataDetailByDataId'))
{
    function getFmsdataDetailByDataId($data_id)
	{
		$data = \DB::table('fmsdatas')
				->select()
				->where('_id', $data_id)
				->get()
				->toArray();
	
		if(!empty($data)){
			return $data[0];
		}else{
			return '';
		}
		exit;
	}
}

if(!function_exists('getFmsType'))
{
    function getFmsType()
	{
		$data = array(
						'pi_fms_bulk'=>'PI FMS Bulk',
						'pi_fms_sample'=>'PI FMS Sample',
						'po_fms_import'=>'PO FMS Import',
						'po_fms_local'=>'PO FMS Local',
						'custom_order'=>'Custom Order',
						'stock_order'=>'Stock Order',
					);
	
		return $data;
		
	}
}

/* Update step id data inside fmsdata table  */


if(!function_exists('updateStepidIntoDtata')){
	function updateStepidIntoDtata($step, $data_id){
			$s_plane_dt = getStepDataByStepId($step);
			$fms_when = $s_plane_dt['fms_when'];
			$planedDt = '';
			$data = array();
			/* ******** for step linked with task ******* */
			/* if (array_key_exists("task_id_link",$fms_when))
			{
				$Hr 	= $fms_when['after_task_time'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
			} */
			
			/* ******** for step linked with step ******* */
			/* if (array_key_exists("after_step_id",$fms_when))
			{
				// get previous step plened_date
				$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
				
				$Hr 	= $fms_when['time_hr'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date. " + ".$inc_dt." day"));
			} */
			
			/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
			if($s_plane_dt['fms_when_type']==6){					
				$data[$step] = array(
									'fms_when_type'=>$s_plane_dt['fms_when']['fms_when_type'],
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}else if($s_plane_dt['fms_when_type']==7){
				$data[$step] = array(
									'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
									'built_options_id'=>$s_plane_dt['fms_when']['input_type']
									);
			}else{
				$data[$step] = array(
									'planed'=>$planedDt,
									'actual'=>''
								);
			}
			
			$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($data); 
			if($upId){
				return "success";
			}else{
				return "failed";
			}
	}
}

/* if(!function_exists('checkUser')){
	function checkUser($nameRoot){
		
		if(userHasRight()){
			echo $nameRoot.'11111==========='; die;
			return true;
		}else{
			echo $nameRoot.'2222==========='; die;
			return redirect()->route($nameRoot);
		}
	}
} */

/* if(!function_exists('getTimeDelay')){
	function getDataDetailByTaskIdAndDataId($dataId, $taskId, $step_id){
		$data = \DB::table('fmsdatas')
				->select($taskId, $step_id)
				->where('_id', $dataId)
				->get()
				->toArray();
		return $data[0];
	}
} */

if(!function_exists('diffrenceBtTwoDateInMint')){
	function diffrenceBtTwoDateInMint($startDet, $endDet){
		
		/* echo "<br>";
		echo "startDet=".$startDet.'endDet=='.$endDet; 
		echo "<br>"; */
		
		$t1 = strtotime( $endDet );
		$t2 = strtotime( $startDet );
		
		$diff = $t1 - $t2;
		//$hours = $diff / ( 60 * 60 ); //HOURS
		$totalMint = ($diff / ( 60 * 60 ))*60; //MINUTS
		return ($totalMint*37.5)/100; // 37.5 == 9 working hr
	}
}

if(!function_exists('diffrenceBtTwoDateInHr_old')){
	function diffrenceBtTwoDateInHr_old($startDet, $endDet){
				 
			// Declare and define two dates 
			$date1 = strtotime($startDet);  
			$date2 = strtotime($endDet);  
			  
			// Formulate the Difference between two dates 
			$diff = abs($date2 - $date1);  
			  
			  
			// To get the year divide the resultant date into 
			// total seconds in a year (365*60*60*24) 
			$years = floor($diff / (365*60*60*24));  
			  
			  
			// To get the month, subtract it with years and 
			// divide the resultant date into 
			// total seconds in a month (30*60*60*24) 
			$months = floor(($diff - $years * 365*60*60*24) 
										   / (30*60*60*24));  
			  
			  
			// To get the day, subtract it with years and  
			// months and divide the resultant date into 
			// total seconds in a days (60*60*24) 
			$days = floor(($diff - $years * 365*60*60*24 -  
						 $months*30*60*60*24)/ (60*60*24)); 
			  
			  
			// To get the hour, subtract it with years,  
			// months & seconds and divide the resultant 
			// date into total seconds in a hours (60*60) 
			$hours = floor(($diff - $years * 365*60*60*24  
				   - $months*30*60*60*24 - $days*60*60*24) 
											   / (60*60));  
			  
			  
			// To get the minutes, subtract it with years, 
			// months, seconds and hours and divide the  
			// resultant date into total seconds i.e. 60 
			$minutes = floor(($diff - $years * 365*60*60*24  
					 - $months*30*60*60*24 - $days*60*60*24  
									  - $hours*60*60)/ 60);  
			  
			  
			// To get the minutes, subtract it with years, 
			// months, seconds, hours and minutes  
			$seconds = floor(($diff - $years * 365*60*60*24  
					 - $months*30*60*60*24 - $days*60*60*24 
							- $hours*60*60 - $minutes*60));  
			  
			// Print the result 
			//printf("%d years, %d months, %d days, %d hours, ". "%d minutes, %d seconds", $years, $months, $days, $hours, $minutes, $seconds); 
			 $totalHr = ($years*12*30*8)+($months*30*8)+($days*8)+($hours/8)+($minutes/60);
			 return round($totalHr, 2);

	}
}

if(!function_exists('dateDiff')){
	function dateDiff($date1, $date2)
	{
	  $date1_ts = strtotime($date1);
	  $date2_ts = strtotime($date2);
	  $diff = $date2_ts - $date1_ts;
	  return round($diff / 86400);
	}
}


if(!function_exists('getTimeDelay')){
	function getTimeDelay($currentData, $stepData, $step_id){
			//return $dataId; 
			//pr($dataId); die('==datadetails==');
			$data = array();
			$pr_step_planed_date='';
			//$s_plane_dt = getStepDataByStepId($step_id);
			//pr($s_plane_dt); die;
			$fms_when = $stepData['fms_when'];
			
			$planedDt = '';
			/* ******** for step linked with task ******* */
			//$datakDetails = getFmsdataDetailByDataId($dataId);
			$datakDetails = $currentData;
			
			//pr($datakDetails); die('==datadetails==');
			$delayPercent='';
			
			if (array_key_exists("task_id_link",$fms_when))
			{
				$task_id 	= $fms_when['task_id_link'];
				
				//$datakDetails = getDataDetailByTaskIdAndDataId($dataId, $task_id, $step_id);
				//echo "--pppp";
				//pr($datakDetails).'---ooooo'; die;

				$o_date = $datakDetails[$task_id]; /* here o_date is prev planned dt*/
				
				//echo $o_date.'====7777';
				
				$currentStepData = $datakDetails[$step_id];
				
				//pr($o_date).'---ooooo'; 
				//pr($currentStepData).'---ooooo'; 
				
				$currentStepPlnDet = $currentStepData['planed'];
				
				$actual_date = $currentStepData['actual'];
				if($actual_date!=''){
					$actual_date = $currentStepData['actual'];
				}else{
					$actual_date = date('Y-m-d H:i:s');
				}
				
				$currentStepActDet = $actual_date;
				
				//echo "<br><br>";
				$timeAvailable = diffrenceBtTwoDateInMint($o_date, $currentStepPlnDet);
				//echo 'avai=='.$timeAvailable;
				//echo "<br><br>";
				$timedelay = diffrenceBtTwoDateInMint($currentStepPlnDet, $currentStepActDet);
				
				//echo 'del=='.$timedelay; 
				//echo "<br><br>";
				//echo $timedelay = diffrenceBtTwoDateInHr($currentStepActDet, $o_date);
				
				//echo "<br><br>delay%=";
				if($timedelay>0 && $timeAvailable>0){
					$delayPercent = ($timedelay/$timeAvailable)*100;
				}else{
					$delayPercent =0;
				}
				
				
				/* 
					total time av = current Planned - prev Planned (o_date)
					total delay = current Actual - current planned 
				
				*/
				//return $timeAvailable.'=='.$timedelay.'==='.$delayPercent;
				
				return round($delayPercent,2);
			}
			
			/* ******** for step linked with step ******* */
			if (array_key_exists("after_step_id",$fms_when))
			{
				/////////
				//pr($fms_when);
				$prevStepid = $fms_when['after_step_id'];
				//pr($prevStepid);
				$prevStepData = $datakDetails[$prevStepid];
				//pr($prevStepData);
				$prevStepPlanned = $prevStepData['planed'];
				
				$currentStepData = $datakDetails[$step_id]; 
				
				$currentStepPlnDet = $currentStepData['planed'];
				
				//$currentStepActDet = $currentStepData['actual'];
				
				$actual_date = $currentStepData['actual'];
				if($actual_date!=''){
					$actual_date = $currentStepData['actual'];
				}else{
					$actual_date = date('Y-m-d H:i:s');
				}
				
				$currentStepActDet = $actual_date;
				
				//echo "<br><br>";
				$timeAvailable = diffrenceBtTwoDateInMint($prevStepPlanned, $currentStepPlnDet);
				//echo "<br><br>";
				$timedelay = diffrenceBtTwoDateInMint($currentStepPlnDet, $currentStepActDet);
				//echo "<br><br>";
				//echo $timedelay = diffrenceBtTwoDateInHr($currentStepActDet, $o_date);
				
				//echo "<br><br>delay%=";
				//$delayPercent = ($timedelay/$timeAvailable)*100;
				if($timedelay>0 && $timeAvailable>0){
					$delayPercent = ($timedelay/$timeAvailable)*100;
				}else{
					$delayPercent =0;
				}
				
				/* 
					total time av = current Planned - prev Planned (o_date)
					total delay = current Actual - current planned 
				*/
				return round($delayPercent,2);
				/////////
				
				//pr($fms_when); die;
				// get previous step plened_date
				//$pr_step_planed_date = $fms_when['after_step_id']['planed'];
				//$pr_step_planed_date = $fms_when['after_step_id'];
				
				//$Hr 	= $fms_when['time_hr'];
				//$inc_dt = ceil($Hr/8);				
				//$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
			}
			return round($delayPercent,2);
				//return $planedDt.'=========='.$pr_step_planed_date;
		//return $curentStepId.'=======';
	}
	
}
	
	
	/* for fmsdata dropdown */
	if(!function_exists('fms_order_range')){
		function fms_order_range($fms_id, $order_range='', $limit=500){
			
			$task_order = \DB::table('fmstasks')
						->select('id')
						->where('fms_id', $fms_id)
						->where('input_type', 'custom_fields')
						->where('custom_type', 'orders')
						->get()
						->toArray();
			
			$task_order_oid = (array) $task_order[0]['_id'];
			$task_order_id = $task_order_oid['oid'];
			
			$data = array();
			$data_min = \DB::table('fmsdatas')
					->where('fms_id', $fms_id)
					->min($task_order_id);
			
			$data_max = \DB::table('fmsdatas')
					->where('fms_id', $fms_id)
					->max($task_order_id);
			
			$min_3= substr($data_min, 0, 2);
			$min_3 = $min_3."000";
			$min_lower = (int) $min_3;
			
			$dropdownHtml = '';
			$selected = '';
			$value = '';
			for($min_lower; $min_lower<=$data_max; $min_lower+=$limit){
					
					$value = ($min_lower+1).'-'.($min_lower+$limit);
					$selected = $value==$order_range?' selected':'';
					$dropdownHtml .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
			}
			return $dropdownHtml;
		}
	}
	
	/* for fmsdata month dropdown */
	if(!function_exists('fms_order_month_dropdown')){
		function fms_order_month_dropdown($fms_id, $order_range=''){
			$data_max = \DB::table('fmsdatas')
						->where('fms_id', $fms_id)
					->max('created_at');
					
			$datetime = $data_max->toDateTime();

			$datetime = (array) $datetime;
			//echo date('m-t', strtotime( $datetime['date'] )); die;
			/* echo date('Y-m-t', strtotime( $datetime['date'] ));
			pr($datetime); die;
			$date_end = new UTCDateTime($data_max);
			pr($date_end); die; */
			
			$maxMonth = date('m', strtotime( $datetime['date'] ));
			$dropdownHtml='';
			for($m=$maxMonth; $m>=6; $m--){
				$dateName = date('F', mktime(0, 0, 0, $m, 1));
				if($maxMonth!=$m){
					$m = $m<10?'0'.$m:$m;
				}
				$selected = $m==$order_range?' selected':'';
				$dropdownHtml.='<option value="'.$m.'" '.$selected.'>'.$dateName.'</option>'; 
			}
						
			return $dropdownHtml;
			
			
			
		}
	}
	
	if(!function_exists('fms_order_month_dropdown_new1')){
		function fms_order_month_dropdown_new1($fms_id, $order_range=''){
			$data_max = \DB::table('fmsdatas')
						->where('fms_id', $fms_id)
					->max('created_at');



					
			/* $data_min = \DB::table('fmsdatas')
						->where('fms_id', $fms_id)
					->min('created_at'); */
			if(empty($data_max))
			{
				return false;
			}
			//pr($data_max); die(0000);
					
			$datetime = $data_max->toDateTime();
			$datetime = (array) $datetime;			
			$maxMonth = date('m', strtotime( $datetime['date'] ));
			
			$maxDate = date('Y-m-1', strtotime( $datetime['date'] ));
			$maxYear = date('Y', strtotime( $datetime['date'] ));
			
			$maxYearM = date('Y-m-1', strtotime( $datetime['date'] ));
			
			/* $datetime_min = $data_min->toDateTime();
			$datetime_min = (array) $datetime_min;			
			$minMonth = date('m', strtotime( $datetime_min['date'] ));
			$minYear = date('Y', strtotime( $datetime_min['date'] )); */
			
			
			$dropdown='';
			//$dropdown.= "<select>";
			$cyr = 2020;
			foreach(range($maxYear, $cyr - 1) as $year) {
				$dropdown.="<optgroup label=\"$year\">";
				// foreach month
				foreach (range(1, 12) as $month) {
					$time = strtotime("$year-$month-01");
					$currentYm = strtotime("$year-$month");
					$selected="";
					if(strtotime($order_range)==$time){
						$selected=" selected";
					}
					
					if($time>strtotime("2019-05-01")){
					$dropdown.="<option value=".date("Y-m-d", $time)." ".$selected.">".date("F", $time)."</option>";
					}
					if(strtotime($maxYearM)==$currentYm){
						$dropdown.="</optgroup>";
						break;
					}
				}
				$dropdown.="</optgroup>";        
			}
			return $dropdown;
		}
	}
	
	if(!function_exists('fms_order_month_dropdown_new')){
		function fms_order_month_dropdown_new($fms_id, $order_range=''){
	
			/* get order task id */
			$taskId = getTaskIdByColumnValue($fms_id, 'date');
			$oid = (array) $taskId['_id'];
			$taskId = $oid['oid'];
			
			
			$data_max = \DB::table('fmsdatas')
						->where('fms_id', $fms_id)
					->max($taskId);
					
			if(empty($data_max))
			{
				return false;
			}
					
			/* $datetime = $data_max->toDateTime();
			$datetime = (array) $datetime;			
			$maxMonth = date('m', strtotime( $datetime['date'] ));
			
			$maxDate = date('Y-m-1', strtotime( $datetime['date'] ));
			$maxYear = date('Y', strtotime( $datetime['date'] ));
			
			$maxYearM = date('Y-m-1', strtotime( $datetime['date'] )); */	
			$maxMonth = date('m', strtotime( $data_max ));
			
			$maxDate = date('Y-m-1', strtotime( $data_max ));
			$maxYear = date('Y', strtotime( $data_max ));
			
			$maxYearM = date('Y-m-1', strtotime( $data_max ));
			
			
			
			
			/* $datetime_min = $data_min->toDateTime();
			$datetime_min = (array) $datetime_min;			
			$minMonth = date('m', strtotime( $datetime_min['date'] ));
			$minYear = date('Y', strtotime( $datetime_min['date'] )); */
			
			
			$dropdown='';
			//$dropdown.= "<select>";
			$cyr = 2020;
			foreach(range($maxYear, $cyr - 1) as $year) {
				$dropdown.="<optgroup label=\"$year\">";
				// foreach month
				foreach (range(1, 12) as $month) {
					$time = strtotime("$year-$month-01");
					$currentYm = strtotime("$year-$month");
					$selected="";
					if(strtotime($order_range)==$time){
						$selected=" selected";
					}
					
					if($time>strtotime("2019-05-01")){
					$dropdown.="<option value=".date("Y-m-d", $time)." ".$selected.">".date("F", $time)."</option>";
					}
					if(strtotime($maxYearM)==$currentYm){
						$dropdown.="</optgroup>";
						break;
					}
				}
				$dropdown.="</optgroup>";        
			}
			return $dropdown;
		}
	}
	
	
	//// getUserIdAccessOfThisFms
	if(!function_exists('getUserIdAccessOfThisFms')){
		function getUserIdAccessOfThisFms($fms_id) 
		{
			$userArr=array();
			$userArr_new=array();
			$data= \DB::table('users')
					->select('*')
					->where('status', 1)
					->where('access_fms_id', $fms_id)->get()->toArray();
			
			foreach($data as $row){
				$oid = (array) $row['_id'];
				$userArr[$oid['oid']]=array(
												'score'=>0,
												'count'=>0,
												'full_name'=>$row['full_name'],
												'email'=>$row['email']
											);
			}
			
			/* pr($userArr); 
			die; */ 
			return $userArr;
			//pr($data); die;
		}
	}
	
	
	if(!function_exists('empReportArr')){
		function empReportArr() 
		{
			$empArr = array(
								'fabricator_list'=>'Fabricator',
								'patchers_list'=>"Patcher's",
								'tailor_list'=>'Tailor',
								'h_embroider_list'=>"H. Embroider",
								'm_embroider_list'=>"M. Embroider"
							);
			return $empArr;
		}
	}
	

	if(!function_exists('getStepIdByName'))
	{
		function getStepIdByName($fms_id, $search_by)
		{
			if($search_by=='fabricator_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','fabricator')->get()->toarray();
						
			}elseif($search_by=='tailor_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','tailor_name')->get()->toarray();
						
			}elseif($search_by=='master_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','master_name')->get()->toarray();
						
			}elseif($search_by=='patchers_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','patchers_name')->get()->toarray();
						
			}elseif($search_by=='h_embroider_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','h_embroider_name')->get()->toarray();
						
			}elseif($search_by=='m_embroider_list')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('fms_when.input_type','m_embroider_name')->get()->toarray();
						
			}elseif($search_by=='st_comp')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('status','st_comp')->get()->toarray();
						
			}elseif($search_by=='st_com')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('process','st_com')->get()->toarray();
						
			}elseif($search_by=='emb_comp')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('status','emb_comp')->get()->toarray();
						
			}elseif($search_by=='pm_check')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('pm_check','pm_check')->get()->toarray();
						
			}elseif($search_by=='patc_com')
			{
				$data = DB::table('fmssteps')
						->where('fms_id', $fms_id)
						->where('status','patc_com')->get()->toarray();
						
			}else{
				return false;
			}
			
			if(!empty($data)){
				return $data[0];
			}else{
				return false;
			}
			
			
		}
	}
	
	if(!function_exists('getTaskIdByColumnValue')){
		function getTaskIdByColumnValue($fms_id, $col_value) 
		{
			$data = DB::table('fmstasks')
						->where('fms_id', $fms_id)
						->where('custom_type', $col_value)->get()->toarray();
				//pr($data); die;
			return $data[0];
		}
	}

	// 
	
	if(!function_exists('dateDiffInDays')){
		function dateDiffInDays($date1, $date2)
		{
			$diff = strtotime($date2) - strtotime($date1); 
			return abs(round($diff / 86400)); 
		}
	}
	
	
	/* 10 JAN 2020  is_steps_completed("5d0cd734d0490449dd7eb122", "5df0ac5ed049043afd78bfe2") */
	if(!function_exists('is_steps_completed')){
		function is_steps_completed($fms_id, $data_id, $patch){
		//$fms_id = "5d0cd734d0490449dd7eb122";
		//$data_id = "5df876d8d049044e097405a2"; // all completed
		//$data_id = "5e0adb41d0490469690e1372"; // all empty
		//$data_id = "5df0ac5ed049043afd78bfe2"; // all testing 
		
		$stepsDataIds = DB::table('fmssteps')
						->select('id')
						->where('fms_id', '=', $fms_id)
						->where('fms_when.input_type', '!=', 'notes')
						->where('fms_when.input_type', '!=', '5d0327970f0e7527d8007155')
						->where('pm_check', '!=', 'pm_check')
						->orderBy('step', 'asc')
						->get()
						->toArray();
		
		$dataIds = array();
				
				foreach($stepsDataIds as $key=>$ids){
					$id = (array) $ids['_id'];
					$oid = $id['oid'];
					array_push($dataIds,$oid);
				}
				
				//pr($dataIds);				
				
				$query	 = DB::table('fmsdatas')->select('id');				
				foreach($dataIds as $dId){
					$query->addSelect($dId);
				}
				$query->where('_id', $data_id);
				$allData = $query->get()->toArray();
				unset($allData[0]['_id']);				
				$finalResults = $allData[0];				
				$notComp=1;
				
				foreach($finalResults as $keys=>$res){
						//pr($res); die;
					if($keys=="5d0cd8fed049044a2a0c4a28" && !array_key_exists('fb_id', $res)){
						$notComp=0; 
						break;
					}else if($keys=="5d0cd8fed049044a2a0c4a2f" && !array_key_exists('dropdown_id', $res) && $patch == 1){
						$notComp=0; 
						break;
					}elseif(array_key_exists('actual', $res)){
						if($res['actual']==""){
							$notComp=0; 
							break;
						}
					}
				}
				
				if($notComp==0){
					//echo "--NOt Completed--";
					return false;
				}else{
					//echo "----Completed----";
					return true;
				}				
				//pr($allData[0]); 
				die;
		}
	}
	
if(!function_exists('getSizeOfCompany'))
{
	function getSizeOfCompany(){
		$res = array('Large Corporate (More than Rs.100cr)','SME (Rs. 10-100Cr)','Small Scale (Less than Rs. 10 Cr)');
		return $res;	
	}
}
if(!function_exists('getNumberOfEmployees'))
{
	function getNumberOfEmployees(){
		$res = array('More than 500 persons','50-500 persons','Less than 50 persons');
		return $res;	
	}
}
if(!function_exists('getGarmentCategory'))
{
	function getGarmentCategory(){
		$res = array('Sportswear','Jackets','Ladies Wear','Blazers/Bottoms','Other');
		return $res;	
	}
}
if(!function_exists('getHSNList'))
{
	function getHSNList($inputname='', $firstLabel='---Select---', $selected=''){
		$data = DB::table('hsncode')
				->select('hsn_code')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die;		 */
		if(!empty($data)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" ><option value="">'.$firstLabel.'</option>';
				foreach($data as $row)
				{
					if($selected==$row['_id']){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['hsn_code'].'</option>';
				}
				 echo $dropdownHtml .= '</select>';
			}
	}
}	
	
if(!function_exists('getHSNDetails'))
{
	function getHSNDetails($id){
	    if($id!=0)
		{
		$data = DB::table('hsncode')->where('_id', '=', $id)->first();
		if($data!=''){
			$res = $data['hsn_code'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}	


if(!function_exists('getHSNgstDetails'))
{
	function getHSNgstDetails($id){
	    if($id!=0)
		{
		$data = DB::table('hsncode')->where('_id', '=', $id)->first();
		if($data!=''){
			$res = $data['gstn'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}	

	 
	  
if(!function_exists('colorDataDetails'))
{
	function colorDataDetails($id,$selected=''){
	    if($id!=0)
		{
		$select_data = ArticleModel::find($id);
		$color = explode(',',$select_data['colour']);
		$color_html = '';
		
		for($i=0; $i<count($color); $i++){
		if($selected==$color[$i]){
		$sel = ' selected';
		}else{
		$sel = '';
		}
		$color_html .= '<option '.$sel.' value="'.$color[$i].'">'.$color[$i].'</option>';
		}	
		return $color_html;
		}else{
		return $res='';
		}		
	}
}	

if(!function_exists('numberTowords'))
{
	function numberTowords($num){
	    if($num!=0)
			{
			$ones = array( 
			1 => "one", 
			2 => "two", 
			3 => "three", 
			4 => "four", 
			5 => "five", 
			6 => "six", 
			7 => "seven", 
			8 => "eight", 
			9 => "nine", 
			10 => "ten", 
			11 => "eleven", 
			12 => "twelve", 
			13 => "thirteen", 
			14 => "fourteen", 
			15 => "fifteen", 
			16 => "sixteen", 
			17 => "seventeen", 
			18 => "eighteen", 
			19 => "nineteen" 
			); 
			$tens = array( 
			1 => "ten",
			2 => "twenty", 
			3 => "thirty", 
			4 => "forty", 
			5 => "fifty", 
			6 => "sixty", 
			7 => "seventy", 
			8 => "eighty", 
			9 => "ninety" 
			); 
			$hundreds = array( 
			"hundred", 
			"thousand", 
			"million", 
			"billion", 
			"trillion", 
			"quadrillion" 
			); //limit t quadrillion 
			$num = number_format(@$num,2,".",","); 
			$num_arr = explode(".",@$num); 
			$wholenum = @$num_arr[0]; 
			$decnum = @$num_arr[1]; 
			$whole_arr = array_reverse(explode(",",@$wholenum)); 
			krsort($whole_arr); 
			$rettxt = ""; 
			foreach(@$whole_arr as $key => $i){ 
			if($i < 20){ 
			$rettxt .= @$ones[$i]; 
			}elseif($i < 100){ 
			$rettxt .= @$tens[substr($i,0,1)]; 
			$rettxt .= " ".@$ones[substr($i,1,1)]; 
			}else{ 
			$rettxt .= @$ones[substr($i,0,1)]." ".@$hundreds[0]; 
			$rettxt .= " ".@$tens[substr($i,1,1)]; 
			$rettxt .= " ".@$ones[substr($i,2,1)]; 
			} 
			if($key > 0){ 
			$rettxt .= " ".@$hundreds[$key]." "; 
			} 
			} 
			if(@$decnum > 0){ 
			$rettxt .= " and "; 
			if(@$decnum < 20){ 
			$rettxt .= @$ones[$decnum]; 
			}elseif(@$decnum < 100){ 
			$rettxt .= @$tens[substr(@$decnum,0,1)]; 
			$rettxt .= " ".@$ones[substr($decnum,1,1)]; 
			} 
			} 
			return ucwords($rettxt); 
			
			}else{
		return $res='0.00';
		}		
	}
}	
	
if(!function_exists('uploads_path'))
{
	function uploads_path($folder=''){
	    return url('uploads/'.$folder);		
	}
}


if(!function_exists('emailsetting'))
{
	function emailsetting(){
	    return $array = array('from_emil' =>'sanjeev.kumar@eyeforweb.com','admin_email'=>'sanjeev.kumar@eyeforweb.com');
	}
}


if(!function_exists('getArrayKey'))
       {
		function getArrayKey($array)
        {
		$rows = array();
			foreach($array as $val){
				$rows[str_replace(' ','_',strtolower($val))] = $val; 
			}
			return $rows;
		}
		
	  }
	  
	  if(!function_exists('getUserByDesignation'))
       {
		function getUserByDesignation($array,$inputname='',$firstLabel='--Select--',$selected='', $opid="")
        {
			if($array!='')
			{
				$arr_data = explode(',',$array);
				$data = array();
				foreach($arr_data as $val)
				{
				$d = str_replace(' ','_',strtolower($val));
				$staffs = DB::table('users')
				->select('*')
				->where('status', 1)
                ->where('designation.'.$d, 'like', $d)
                ->get()->toArray();	
				/* echo "<pre>"; print_r($staffs); die; */
				//array_push($data,$staffs);
				
				foreach($staffs as $res){
					array_push($data,$res);
				}
				
				}
				//$results = $data[0];
				$results = $data;
				
				$dropdownArr = array();
				//return $results;
				if(!empty($results)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($results as $row)
				{
					if($selected==$row['_id']){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					
					
					
					//$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['name'].'</option>';
					
					if(!in_array($row['_id'],$dropdownArr)){
						$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['name'].'</option>';
					}
					
					array_push($dropdownArr,$row['_id']);
					
				}
				 echo $dropdownHtml .= '</select>';
			  }
			}
		   }
		
	  }
	  
	  
	  
	   if(!function_exists('getSalesAgent'))
       {
		function getSalesAgent($array,$inputname='',$firstLabel='--Select--',$selected='', $opid="")
        {
			if($array!='')
			{
				$arr_data = explode(',',$array);
				$data = array();
				foreach($arr_data as $val)
				{
				$d = str_replace(' ','_',strtolower($val));
				$staffs = DB::table('users')
				->select('*')
				->where('status', 1)
                ->where('designation.'.$d, 'like', $d)
                ->get()->toArray();	
				//echo "<pre>"; print_r($staffs); die; 
				
				foreach($staffs as $res){
					array_push($data,$res);
				}
				
				}
				//$results = $data[0];
				$results = $data;
				
				//return $results;
				if(!empty($results)){
				$dropdownHtml = '<select class="form-control required" name="'.$inputname.'" '.$opid.'><option value="">'.$firstLabel.'</option>';
				foreach($results as $row)
				{
					if($selected==$row['_id']){
						$sel = ' selected';
					}else{
						$sel = '';
					}
					$dropdownHtml .= '<option '.$sel.' value="'.$row['_id'].'">'.$row['name'].'</option>';
				}
				 echo $dropdownHtml .= '</select>';
			  }
			}
		   }
		
	  }
	  
	  // Sanjay 23-03-2020
	 
if(!function_exists('getCustomerName'))
{
	function getCustomerName($id){
	    if($id!=0)
		{
		$data = DB::table('register_form')->where('_id', '=', $id)->first();
		if($data!=''){
			$res = $data['company_name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}	


if(!function_exists('getSalesPerson'))
{
	function getSalesPerson($id){
	    if($id!=0)
		{
		$data = DB::table('users')->where('id', '=', $id)->first();
		if($data!=''){
			$res = $data['name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}	

/* Start Dhruv*/

if(!function_exists('getAllStaffById')) {
	function getAllStaffById($name){
		if($name!=''){
			$data = User::where('name',trim($name))->first();
			return $users_id = $data['_id'];
		}else{
			return '';
		}
	}
}

if(!function_exists('getItemById')) {
	function getItemById($name){
		if($name!=''){
			$data = DB::table('items')->where('name', '=', $name)->get()->first();
			$oid = (array) $data['_id'];
			return $id = $oid['oid'];
		}else{
			return '';
		}
	}
}

if(!function_exists('getRoomById')) {
	function getRoomById($name){
		if($name!=''){
			$data = DB::table('room')->where('room_no', '=', $name)->get()->first();
			$oid = (array) $data['_id'];
			return $id = $oid['oid'];
		}else{
			return '';
		}
	}
}

if(!function_exists('getRackById')) {
	function getRackById($name){
		if($name!=''){
			$data = DB::table('rack')->where('rack_name', '=', $name)->get()->first();
			$oid = (array) $data['_id'];
			return $id = $oid['oid'];
		}else{
			return '';
		}
	}
}

if(!function_exists('getHSNIdByCode')) {
	function getHSNIdByCode($hsn_code){
		if($hsn_code!=''){
			$data = DB::table('hsncode')->where('hsn_code', $hsn_code)->get()->toArray();
			if(!empty($data)){
				$data = $data [0];
				$oid = (array) $data['_id'];
				return $id = $oid['oid'];
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
}

/* End Dhruv*/

if(!function_exists('getItemName'))
{
	function getItemName($id){
	    if($id!=0)
		{
		$data = DB::table('items')->where('id', '=', $id)->first();
		if($data!=''){
			$res = $data['name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getRoomName'))
{
	function getRoomName($id){
	    if($id!=0)
		{
		$data = DB::table('room')->where('id', '=', $id)->first();
		if($data!=''){
			$res = $data['room_no'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getRackName'))
{
	function getRackName($id){
	    if($id!=0)
		{
		$object = DB::table('rack')->where('id', '=', $id)->first();
		$data = json_decode(json_encode($object), true);
		if($data!=''){
			$res = $data['rack_name'];
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getArticleIdByName'))
{
	function getArticleIdByName($article_no){
		//var_dump($article_no); die; POS#1465
	    if($article_no!='')
		{
			
			$data = DB::table('articles')->select('article_no')->where('article_no', 'LIKE', '%'.$article_no.'%')->first();
			//pr($data); die('hii33eei');
			if($data!=''){
				$o_id = (array) $data['_id'];
				$articlet_id = $o_id['oid'];
				$res = $articlet_id;
			}else{
				$res = '';
			}
			return $res;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getPIPDFdata'))
{
	function getPIPDFdata($id, $path=''){
	    if($id!='')
		{
		$files = glob("public/pi_invoice/".$path.$id."/*.*"); 
		$d = array();
		for ($i=0; $i<count($files); $i++) {
		$num = explode('/',$files[$i]);
		array_push($d,end($num));
		}
		return $d;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getPOPDFdata'))
{
	function getPOPDFdata($id, $path=''){
	    if($id!='')
		{
		$files = glob("public/po_invoice/".$path.$id."/*.*"); 
		$d = array();
		for ($i=0; $i<count($files); $i++) {
		$num = explode('/',$files[$i]);
		array_push($d,end($num));
		}
		return $d;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('getPDFdata'))
{
	function getPDFdata($id, $path=''){
	    if($id!='')
		{
		$files = glob("public/".$path.$id."/*.*"); 
		$d = array();
		for ($i=0; $i<count($files); $i++) {
		$num = explode('/',$files[$i]);
		array_push($d,end($num));
		}
		return $d;
		}else{
		return $res='';
		}		
	}
}

if(!function_exists('selectEmailTemplate'))
{
function selectEmailTemplate($template=''){
 if($template!=''){
   $data = DB::table('emails')->where('key_name',$template)->first();
   if(!empty($data)){
   $res = $data;
   return $res;
   }else{
   return $res='';
   }
 }
}
}

	if(!function_exists('getUserByDesignationEmail'))
    {
		function getUserByDesignationEmail($array)
        {
			if($array!='')
			{
				$arr_data = explode(',',$array);
				$data = array();
				foreach($arr_data as $val)
				{
				$d = str_replace(' ','_',strtolower($val));
				$staffs = DB::table('users')
				->select('email')
				->where('status', 1)
                ->where('designation.'.$d, 'like', $val)
                ->get()->toArray();	
				array_push($data,$staffs);
				}
				$results = $data[0];
				//return $results;
				if(!empty($results)){
				$dropdownHtml = '';
				foreach($results as $row)
				{
				$dropdownHtml .= $row['email'].',';
				}
				 return $dropdownHtml;
			  }
			}
		}
	}
	  
		/* shoyeb == 27 march 2020 */	 
		if(!function_exists('getPiDataByPiId'))
		{
			function getPiDataByPiId($Pi_id){
				if($Pi_id!='')
				{
					$data = DB::table('tbl_purchase_invoice')->where('_id', $Pi_id)->get()->first();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}
		
		if(!function_exists('getInqDataByInqId'))
		{
			function getInqDataByInqId($id){
				if($id!='')
				{
					$data = DB::table('tbl_add_inquiry')->where('_id', $id)->get()->first();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}

		if(!function_exists('getPiRowDataByPiId'))
		{
			function getPiRowDataByPiId($Pi_id){
				if($Pi_id!='')
				{
					$data = DB::table('tbl_purchase_invoice_item_data')->where('performa_invoice_id', '=', $Pi_id)->get()->toArray();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}
		
		if(!function_exists('getSampleCardRowDataById'))
		{
			function getSampleCardRowDataById($id){
				if($id!='')
				{
					$data = DB::table('tbl_buyer_sample_card_data')->where('invoice_id_sample_card', '=', $id)->get()->toArray();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
					return $res='';
				}		
			}
		}
		
		if(!function_exists('getPoRowDataByPoId'))
		{
			function getPoRowDataByPoId($Po_id){
				if($Po_id!='')
				{
					$data = DB::table('tbl_pp_purchase_invoice_item_data')->where('performa_invoice_id', '=', $Po_id)->get()->toArray();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}
		
		if(!function_exists('getPoDataByPoId'))
		{
			function getPoDataByPoId($Po_id){
				if($Po_id!='')
				{
					$data = DB::table('tbl_pp_purchase_invoice')->where('_id', $Po_id)->get()->first();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}
		
		if(!function_exists('getPoRowDataByPoId'))
		{
			function getPoRowDataByPoId($Po_id){
				if($Po_id!='')
				{
					$data = DB::table('tbl_pp_purchase_invoice_item_data')->where('performa_invoice_id', '=', $Po_id)->get()->toArray();
					if(!empty($data)){
						$res = $data;
					}else{
						$res = '';
					}
					return $res;
				}else{
				return $res='';
				}		
			}
		}
		
		if(!function_exists('getEtdDateFieldTaskByFmsId'))
		{
			function getEtdDateFieldTaskByFmsId($fms_id)
			{
				$dateTask = Fmstask::where('fms_id', '=', $fms_id)
				->where('field_type', '=', 'etd')->get()->toArray();		
				
					$dropdownHtml='';
					if(!empty($dateTask)){
						$dropdownHtml = '<select class="form-control task_id_link" name="row[r_num][task_id_link]" onchange="taskDateDropdown(this.value, this.id)" id="task_r_num" ><option value="">--- Select ---</option>';
						foreach($dateTask as $key=>$val)
						{
							$dropdownHtml .= '<option value="'.$val['_id'].'">'.$val['task_name'].'</option>';
						}
						return $dropdownHtml .= '</select>';
					}else{
						return 'No data found';
					}
			}
		}
		
		if(!function_exists('getPiVersionsById'))
		{
			function getPiVersionsById($pi_id)
			{
				$data = DB::table('tbl_purchase_invoice')->select('pi_version')->where('_id',$pi_id)->get()->toArray();
					if(!empty($data)){
						$res = $data[0];
					}else{
						$res = '';
					}
				return $res;
			}
		}
		
		if(!function_exists('getInqVersionsById'))
		{
			function getInqVersionsById($pi_id)
			{
				$data = DB::table('tbl_add_inquiry')->select('*')->where('_id',$pi_id)->get()->toArray();
					if(!empty($data)){
						$res = $data[0];
					}else{
						$res = '';
					}
				return $res;
			}
		}
		
		if(!function_exists('getPuInvVersionsById'))
		{
			function getPuInvVersionsById($pi_id)
			{
				$data = DB::table('tbl_pp_purchase_invoice')->select('pi_version')->where('_id',$pi_id)->get()->toArray();
					if(!empty($data)){
						$res = $data[0];
					}else{
						$res = '';
					}
				return $res;
			}
		}
		
		
		if(!function_exists('getAllStepDataByFmsId'))
		{
			function getAllStepDataByFmsId($fms_id)
			{
				$data = \DB::table('fmssteps')
						->select()
						->where('fms_id', '=', $fms_id)
						->get()
						->toArray();
			
				if(!empty($data)){
					return $data;
				}else{
					return 'No data found';
				}
				exit;
			}
		}
		
		if(!function_exists('getFmsdataDetailByTbaleNameAndDataId'))
		{
			function getFmsdataDetailByTbaleNameAndDataId($table_name,$data_id)
			{
				$data = \DB::table($table_name)
						->select()
						->where('_id', $data_id)
						->get()
						->toArray();
			
				if(!empty($data)){
					return $data[0];
				}else{
					return '';
				}
				exit;
			}
		}
		
		if(!function_exists('getAllArticles'))
		{
			function getAllArticles($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('articles')
						->select('id', 'article_no')
						->where('_id', $id)
						->get()
						->toArray();
					if(!empty($data[0])){
						return $data[0];
					}else{
						return array();
					}
				}else{
					$data = \DB::table('articles')
						//->select('id', 'article_no', 'colour','description', 'hsn_code','gst_code')
						->select('id', 'article_no', 'colour', 'factory_code','composition','count_construct','gsm','width', 'description', 'fabric_finish','hsn_code')
						->get()
						->toArray();
						
						foreach($data as $row){
							$oid = (array) $row['_id'];
							$id = $oid['oid'];
							//$resArr[$id]= str_replace("'","",$row['article_no']); 'description' => preg_quote($row['description']), 
							
							$resArr[$id]= array(
													'article'=>str_replace("'","",$row['article_no']),
													'colour'=>$row['colour'],
													'description' => addslashes(str_replace("'","",$row['description'])), 
													'composition' => addslashes(str_replace("'","",$row['composition'])), 
													'count_construct' => addslashes(str_replace("'","",$row['count_construct'])), 
													'width' => addslashes(str_replace("'","",$row['width'])), 
													'gsm' => addslashes(str_replace("'","",$row['gsm'])), 
													'hsn_code' => getHSNDetails($row['hsn_code']),
													'gst_code' => getHSNgstDetails($row['hsn_code'])
												);
							
							//pr($resArr); die;
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllArticlesPurchase'))
		{
			function getAllArticlesPurchase($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('articles')
						->select('id', 'article_no')
						->where('_id', $id)
						->limit(10)
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('articles')
						->select('id', 'article_no', 'colour', 'factory_code','composition','count_construct','gsm','width', 'description', 'fabric_finish','hsn_code')
						->limit(10)
						->get()
						->toArray();
						
					foreach($data as $row){
						$oid = (array) $row['_id'];
						$id = $oid['oid'];
							
						
					$factory_code='';
					if(array_key_exists('factory_code',$row) && trim($row['factory_code'])!=''){ 
						$factory_code=preg_replace('/[^a-zA-Z0-9áÁéÉíÍóÓöÖőŐúÚüÜűŰ \.\,\?\!\:\;\-\+\<\>\%\~\€\$\[\]\{\}\@\&\#\*\"\„\”\/]/', '',$row['factory_code']);
					}
					$composition='';
					if(array_key_exists('composition',$row) && trim($row['composition'])!=''){ 
						$composition=addslashes(str_replace("'","",$row['composition']));
					}
					$count_construct='';
					if(array_key_exists('count_construct',$row) && trim($row['count_construct'])!=''){ 
						$count_construct=addslashes(str_replace("'","",$row['count_construct']));
					}
					
					$gsm='';
					if(array_key_exists('gsm',$row) && trim($row['gsm'])!=''){ 
						$gsm=addslashes(str_replace("'","",$row['gsm']));
					}
					$width='';
					if(array_key_exists('width',$row) && trim($row['width'])!=''){
						$width=addslashes(str_replace("'","",$row['width']));;
						
					}
					$description='';
					if(array_key_exists('description',$row) && trim($row['description'])!=''){ 
						$description=addslashes(str_replace("'","",$row['description']));
					}
					$fabric_finish='';
					if(array_key_exists('fabric_finish',$row) && trim($row['fabric_finish'])!=''){ 
						$fabric_finish=addslashes(preg_replace('/[^a-zA-Z0-9áÁéÉíÍóÓöÖőŐúÚüÜűŰ \.\,\?\!\:\;\-\+\<\>\%\~\€\$\[\]\{\}\@\&\#\*\"\„\”\/]/', '',$row['fabric_finish']));
					}
							
					$resArr[$id]= array(
								'article'=>str_replace("'","",$row['article_no']),
								'colour'=>$row['colour'],
								"factory_code" => $factory_code, 
								"composition" => $composition, 
								"count_construct" => $count_construct,
								"gsm" => $gsm,
								"width" => $width,
								'description' => $description, 
								'fabric_finish' =>$fabric_finish, 
								'hsn_code' => getHSNDetails($row['hsn_code']),
								'gst_code' => getHSNgstDetails($row['hsn_code'])
							);				
			
							//pr($resArr); die;
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllBuyer'))
		{
			function getAllBuyer($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('customers')
						->select('id', 'company_name')
						->where('id', $id)
						->where('block_unblock','=','1')
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('customers')
						->select('id', 'company_name')
						->where('block_unblock','=','1')
						->orWhere('block_unblock','=',1)
						->get()
						->toArray();
						//pr($data);die;
						foreach($data as $row){
							$resArr[$row->id]= addslashes(str_replace("'","",$row->company_name));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllVendor'))
		{
			function getAllVendor($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('vendors')
						->select('id', 'company_name')
						->where('_id', $id)
						->where('block_unblock','=','1')
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('vendors')
						->select('id', 'company_name')
						->where('block_unblock','=','1')
						->orWhere('block_unblock','=',1)
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id'];
							
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['company_name']));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		
		
		if(!function_exists('getAllRoom'))
		{
			function getAllRoom($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('room')
						->select('id', 'room_no')
						->where('id', $id)
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('room')
						->select('id', 'room_no')
						->get()
						->toArray();
						
						foreach($data as $row){
							//$oid = (array) $row['_id'];
							//$id = $oid['oid']; 
							$row = (array) $row;
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['room_no']));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		
		if(!function_exists('getAllRack'))
		{
			function getAllRack($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('rack')
						->select('id', 'rack_name')
						->where('id', $id)
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('rack')
						->select('id', 'rack_name')
						->get()
						->toArray();
						
						foreach($data as $row){
							/* $oid = (array) $row['_id'];
							$id = $oid['oid'];  */
							$row = (array) $row;
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['rack_name']));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getItemGroup'))
		{
			function getItemGroup($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					 $object= \DB::table('tbl_Item_group')
						->select('id', 'Item_group')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
					return $data[0];
				}else{
					$object = \DB::table('tbl_Item_group')
						->select('id', 'Item_group')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){

							$id = $row['id']; 
							$resArr[addslashes(str_replace("'","",$row['Item_group']))]= addslashes(str_replace("'","",$row['Item_group']));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		
		
			if(!function_exists('getAllBuyerData'))
		{
			function getAllBuyerData($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$object = \DB::table('customers')
						->select('id', 'company_name')
						->where('id', $id)
						->where('block_unblock','=','1')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
					return $data[0];
				}else{
					$object = \DB::table('customers')
						->select('id', 'company_name')
						->where('block_unblock','=','1')
						->orWhere('block_unblock','=',1)
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['company_name']));
						}
				}
				return $resArr;
				exit;
			}
		}
		
		
		if(!function_exists('getAllVendorData'))
		{
			function getAllVendorData($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('vendors')
							->select('id', 'company_name')
							->where('id', $id)
							->where('block_unblock','=','1')
							->get()
							->toArray();
					return $data[0];
				}else{
					$data = \DB::table('vendors')
							->select('id', 'company_name')
							->where('block_unblock','=','1')
							->orWhere('block_unblock','=',1)
							->get()
							->toArray();
						
						foreach($data as $row){
							//pr($row); die;
							//$oid = (array) $row['id'];
							$id = $row->id; 
							$resArr[$id]= addslashes(str_replace("'","",$row->company_name));
						}
				}
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllBuyerDataCity'))
		{
			function getAllBuyerDataCity($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$object = \DB::table('customers')
						->select('id', 'company_name','h_city')
						->where('id', $id)
						->where('block_unblock','=','1')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
					return $data[0];
				}else{
					$object = \DB::table('customers')
						->select('id', 'company_name','h_city')
						->where('block_unblock','=','1')
						->orWhere('block_unblock','=',1)
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							if(!empty($row['h_city']))
							{
							    $city = $row['h_city'];
							}else{
							    $city = "";
							}
							$resArr[$id]= array('name'=>addslashes(str_replace("'","",$row['company_name'])),'city'=>$city);
						}
				}
				return $resArr;
				exit;
			}
		}
		
		
		if(!function_exists('getAllVendor'))
		{
			function getAllVendor($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('vendors')
						->select('_id', 'company_name')
						->where('_id', $id)
						->where('block_unblock','=','1')
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('vendors')
						->select('_id', 'company_name')
						->where('block_unblock','=','1')
						->orWhere('block_unblock','=',1)
						->get()
						->toArray();
						
						foreach($data as $row){
							$oid = (array) $row['_id'];
							$id = $oid['oid']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['company_name']));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		
		function getDepartmentName($id)
		{

			$object = \DB::table('tbl_department_name')
			->where('id', $id)
			->get()
			->first();
			$da = json_decode(json_encode($object), true);
		 	
		 $object_data = \DB::table('tbl_department')
						->where('id', $da['department_name'])
						->get()
						->first(); 
						$data = json_decode(json_encode($object_data), true);
			return $data['department_name'];
		}
		
		function getCustomerNamefromId($id)
		{
			$data = \DB::table('customers')
						->where('id', $id)
						->get()
						->first(); 
			$data = (array)$data;
		 return $data['company_name'];  
		}
		
		function getCustomerIdfromName($customer_name)
		{
			$data = \DB::table('customers')
						->where('company_name', $customer_name)
						->get()
						->first(); 
			$data = (array)$data;
		 return $data['id'];  
		}
		
		function getVendorNamefromId($id)
		{
		  $data = \DB::table('vendors')
						->where('id', $id)
						->get()
						->first(); 
			$data = (array)$data;
		 return $data['company_name'];  
		} 
		
		
		function getLocationName($id)
		{
		  $data = \DB::table('tbl_location')
						->where('id', $id)
						->get()
						->first(); 
			$data = (array)$data;
		 return $data['location_name'];  
		}
		
		function getCategoryName($id)
		{
		  $data = \DB::table('customer_categories')
						->where('id', $id)
						->get()
						->first(); 
			$data = (array)$data;
		 return $data['category'];  
		}
		
		function getTaskCategoryName($id)
		{
		  $data = \DB::table('task_categories')
						->where('id', $id)
						->get()
						->first();
			$data = (array)$data;
		 return $data['task_category'];  
		}
		
		if(!function_exists('getAllDepart'))
		{
			function getAllDepart()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('tbl_department')
						->select('id', 'department_name')
						->get()
						->toArray();
						
						foreach($data as $row){
							$id =  $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->department_name));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllLoc'))
		{
			function getAllLoc()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('tbl_location')
						->select('id', 'location_name')
						->get()
						->toArray();
						
						foreach($data as $row){
							$oid = (array) $row['_id'];
							$id = $oid['oid']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['location_name']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllCat'))
		{
			function getAllCat()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('customer_categories')
						->select('id', 'category')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['category']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllBrand'))
		{
			function getAllBrand()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('brand')
						->select('id', 'brand')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['brand']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllCustomer'))
		{
			function getAllCustomer()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('customers')
						->select('id', 'company_name', 'email')
						->where('block_unblock','=',1)
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id'];
							$resArr[$id]= addslashes(str_replace("'","",$row['company_name'])).' ('.$row['email'].')';
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllGroup'))
		{
			function getAllGroup()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('group_of_company')
						->select('id', 'group_name')
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['group_name']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllItems'))
		{
			function getAllItems()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('items')
							->select('id', 'name')
							->where('active_deactive','=','1')
							->orWhere('active_deactive','=',1)
							->get()
							->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['name']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		
		
		if(!function_exists('getAllusers'))
		{
			function getAllusers()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('users')
						->select('*')
						->where('status', 1)
						->get()
						->toArray();
						$user_edit_details = json_decode(json_encode($data), true);
						
						foreach($user_edit_details as $row){
							$id =$row['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$row['full_name']));
						}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllusersData'))
		{
			function getAllusersData()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('users')
						->select('*')
						->where('status', 1)
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						
						foreach($data as $row){
							$id = $row['id'];; 
							$resArr[$id]= addslashes(str_replace("'","",$row['full_name']));
						}
				
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllusersDataList'))
		{
			function getAllusersDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('users')
						->select('*')
						->where('status', 1)
						->get()
						->toArray();
						$data = json_decode(json_encode($object), true);
						foreach($data as $row){
							$id = $row['id'];
							$resArr[$id]= addslashes(str_replace("'","",$row['full_name']));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllitemsDataList'))
		{
			function getAllitemsDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id'];
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['name'])).'-'.$rowdata['grade'].'-'.$rowdata['packing_name'].'-'.$rowdata['brand'].'-'.$rowdata['vendor_sku'];
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllitemsVendorSKUList'))
		{
			function getAllitemsVendorSKUList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id'];
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['vendor_sku']));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllitemsBrandList'))
		{
			function getAllitemsBrandList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id'];
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['brand']));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllitemsPackingList'))
		{
			function getAllitemsPackingList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['packing_name']));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllitemsGradeList'))
		{
			function getAllitemsGradeList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$rowdata = (array) $row;
							$id = $rowdata['id']; 
							$resArr[$id]= addslashes(str_replace("'","",$rowdata['grade']));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllrackDataList'))
		{
			function getAllrackDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('rack')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->rack_name));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllbrandDataList'))
		{
			function getAllbrandDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							//$oid = (array) $row['id'];
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->brand));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllproductsDataList'))
		{
			function getAllproductsDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('items')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->vendor_sku));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllroomsDataList'))
		{
			function getAllroomsDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('room')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->room_no));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllracksDataList'))
		{
			function getAllracksDataList()
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('rack')
						->select('*')
						->get()
						->toArray();
						
						foreach($data as $row){
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->rack_name));
						}
				
				return $resArr;
				exit;
			}
		}
		
		if(!function_exists('getAllSupplier'))
		{
			function getAllSupplier($id='')
			{
				
				$data=array();
				$resArr = array();
				if($id!=''){
					$data = \DB::table('supplier_form')
						->select('id', 'company_name')
						->where('id', $id)
						->get()
						->toArray();
					return $data[0];
					
				}else{
					$data = \DB::table('supplier_form')
						->select('id', 'company_name')
						->get()
						->toArray();
						
						foreach($data as $row){
							$id = $row->id;
							$resArr[$id]= addslashes(str_replace("'","",$row->company_name));
						}
				}
				return json_encode($resArr);
				exit;
			}
		}
		
		if(!function_exists('getAllPiRefrences'))
		{
			function getAllPiRefrences($id='')
			{
				$data=array();
				if($id!=''){
					$data = \DB::table('tbl_purchase_invoice')
						->select('id', 'po_serial_number', 'created_at')
						->where('id', $id)
						->where('data_status', 1)
						->where('pi_order_status', '!=','Closed')
						->OrWhere('pi_order_status', '!=','Part Shipped')
						->get()
						->toArray();
					return $data[0];
				}else{
					$data = \DB::table('tbl_purchase_invoice')
						->select('id', 'po_serial_number', 'created_at')
						->where('data_status', 1)
						->where('pi_order_status', '!=','Closed')
						->OrWhere('pi_order_status', '!=','Part Shipped')
						->get()
						->toArray();
						return $data;
				}
			}
		}
		
		
	if(!function_exists('emailSend'))
	{
		function emailSend($to,$data){
			$to = $to;
			$subject = $data['subject']; 
			$message = $data['message']; 
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: ' . $data['from'] . "\r\n" . 
			'X-Mailer: PHP/' . phpversion();
			$mail = @mail($to, $subject, $message, $headers);
			if($mail){ 
				return true; 
			}else{ 
				return false; 
			} 
		}
	}
	
	if(!function_exists('get_financial_year'))
	{
		function get_financial_year(){
			if (date('m') <= 3) {
				$financial_year = (date('y')-1) . '-' . date('y');
			} else {
				$financial_year = date('y') . '-' . (date('y') + 1);
			}
			return $financial_year;
		}
	}
	
	if(!function_exists('get_financial_year_full'))
	{
		function get_financial_year_full(){
			if (date('m') <= 3) {
				$financial_year['last'] = array(
											'start'=>'01-04-'.(date('Y')-2),
											'end'=>'31-03-'.(date('Y')-1),
										);
										
				$financial_year['current'] = array(
											'start'=>'01-04-'.(date('Y')-1),
											'end'=>'31-03-'.date('Y'),
										);
			} else {
				
				$financial_year['last'] = array(
											'start'=>'01-04-'.(date('Y')-1),
											'end'=>'31-03-'.date('Y'),
										);
										
				$financial_year['current'] = array(
											'start'=>'01-04-'.date('Y'),
											'end'=>'31-03-'.(date('Y') + 1),
										);
			}
			
			
			return $financial_year;
		}
	}
	
	if(!function_exists('get_last_pi_and_cnt'))
	{
		function get_last_pi_and_cnt(){
			$users = DB::table('users')
			->where('status', 1)
                ->offset(10)
                ->limit(5)
                ->get();
		}
	}
	
	if(!function_exists('get_item_from_fms_data'))
	{
		function get_item_from_fms_data($fms_type){
				
				$fms_data_table='';
				if($fms_type =='bulk'){
					$fms_data_table='pi_fms_bulk_data';
				}elseif($fms_type =='sampling'){
					$fms_data_table='pi_fms_sample_data';
				}elseif($fms_type =='import'){
					$fms_data_table='po_fms_import_data';
				}elseif($fms_type =='local'){
					$fms_data_table='po_fms_local_data';
				}elseif($fms_type =='sample_cards'){
					$fms_data_table='samplecards_fms_data';
				}
							
				$data = DB::table($fms_data_table)
					->select('item_data.i_value')
					->where('item_data.i_value','!=', '')
					->get()
					->unique('item_data.i_value')
					->toArray();
				return $data;
		}
	}
	
	if(!function_exists('get_sales_person_from_fms_data'))
	{
		function get_sales_person_from_fms_data($fms_type){
				
				$fms_data_table='';
				if($fms_type =='bulk'){
					$fms_data_table='pi_fms_bulk_data';
				}elseif($fms_type =='sampling'){
					$fms_data_table='pi_fms_sample_data';
				}elseif($fms_type =='import'){
					$fms_data_table='po_fms_import_data';
				}elseif($fms_type =='local'){
					$fms_data_table='po_fms_local_data';
				}elseif($fms_type =='sample_cards'){
					$fms_data_table='samplecards_fms_data';
				}
							
				$data = DB::table($fms_data_table)
					->select('s_person_data.s_value')
					->where('s_person_data.s_value','!=', '')
					->get()
					->unique('s_person_data.s_value')
					->toArray();
				return $data;
		}
	}
	
	
	if(!function_exists('isEmptyCheck'))
	{
		function isEmptyCheck($data){
			if($data!=''){
				return $data;
			}else{
				return '';
			}
		}
	}
	
	if(!function_exists('getCurrentUserMis'))
	{
		function getCurrentUserMis($currentUserId){
			$mis_sum_data = DB::table('mis_bulk')->orderBy('d')->get()->toArray();
			$mis_arr= array();
			$mis_arr_temp= array();
			if(!empty($mis_sum_data)){
				foreach($mis_sum_data as $skey=>$sval){
					//pr($sval); die;
					if(array_key_exists($currentUserId, $sval)){
						
						if(!array_key_exists($sval['d'], $mis_arr)){
							$mis_arr[$sval['d']] = array($sval['s']=>$sval[$currentUserId]);
						}else{
							
							$mis_arr[$sval['d']][$sval['s']] = $sval[$currentUserId];
						}
						/* pr($mis_arr); die; */
					}
				}
			}
			//pr($mis_arr); die('Helper');
			if($mis_arr!=''){
				return $mis_arr;
			}else{
				return '';
			}
		}
	}
	
	
	if(!function_exists('getUserIdByName'))
	{
		function getUserIdByName($name){
			//echo $name; die;
			if($name!='')
			{
			$data = DB::table('users')->select('id')->where('name', '=', $name)->first();
			//pr($data['_id']); die;
			if($data!=''){
				$oid = (array) $data['_id'];
				return $oid['oid'];
				//pr($oid); die;
			}else{
				$res = '';
			}
			return $res;
			}else{
			return $res='';
			}		
		}
	}
	
	if(!function_exists('checkSunday'))
	{
		function checkSunday($date){
			$pld =  date('D', strtotime($date));
			if($pld=='Sun'){
				$pld = date('Y-m-d H:i:s', strtotime('+1 day '.$date));
			}else{
				$pld=$date;
			}
			return $pld;
		}
	}
	
	if(!function_exists('getStepIdByKpiId'))
	{
		function getStepIdByKpiId($kpi){
			$data = DB::table('fms_kpi')->where('_id', '=',$kpi)->first();
			return $data;
		}
	}
	
		
		if(!function_exists('getWeekDate'))
		{
			function getWeekDate(){				
				$data = array();
				$previous_week = strtotime("-1 week +1 day");
				$start_week = strtotime("last monday midnight",$previous_week);
				$end_week = strtotime("next sunday",$start_week);

				$start_day = date("Y-m-d",$start_week);				
				$end_day = date("Y-m-d", $end_week);
			
	
				$data['last_week'] = array(
												'mis_from'=>$start_day,
												'mis_to'=>$end_day,
											);
				
				 
				
				$current_week = strtotime("+1 day");
				$start_week = strtotime("last monday midnight",$current_week);
				$end_week = strtotime("next sunday",$start_week);

				$start_day = date("Y-m-d", $start_week);				
				$end_day = date("Y-m-d", $end_week);
				
				$data['current_week'] = array(
												'mis_from'=>$start_day,
												'mis_to'=>$end_day,
											);
											
				return $data;
				
			}
		}
			
			
			
			
	

	if(!function_exists('getWeekData'))
	{
		function getWeekData($fms_id, $user_id, $kpi_id, $mis_period){
			$data = array();
			$w_data = getWeekDate();
			//echo $fms_id.'====='.$user_id.'====='.$kpi_id.'====='.$mis_period; die;
			//pr($w_data); die('===');
			$last_week = $w_data['last_week'];
			$curr_week = $w_data['current_week'];
			// get last week score
			$last_data =   DB::table('kpi_target')
					->where('fms_id', '=',$fms_id)
					->where('user_id', '=',$user_id)
					->where('kpi_id', '=',$kpi_id)
					->where('mis_period', '=',$mis_period)
					->where('mis_from', '=',$last_week['mis_from'])
					->where('mis_to', '=',$last_week['mis_to'])
					->first();
			// get current week score
			$curr_data = DB::table('kpi_target')
					->where('fms_id', '=',$fms_id)
					->where('user_id', '=',$user_id)
					->where('kpi_id', '=',$kpi_id)
					->where('mis_period', '=',$mis_period)
					->where('mis_from', '=',$curr_week['mis_from'])
					->where('mis_to', '=',$curr_week['mis_to'])
					->first();
					
			
			
			$data['last_w'] = $last_data;
			$data['curr_w'] = $curr_data;
			return $data;
		}
	}
	

	if(!function_exists('getallKpiByFmsAndUseId'))
	{
		function getallKpiByFmsAndUseId($fms_id, $user_id){
			$kpi_data = DB::table('fms_kpi')
						->where('fms', '=',$fms_id)
						->where('user', '=',$user_id)
						->get()->toArray();
			return $kpi_data;
		}
	}
	
	if(!function_exists('getallKpiFMSByUseId'))
	{
		function getAllKpiFMSByUseId($user_id){
			$kpi_data = DB::table('fms_kpi')
						->where('user', '=',$user_id)
						->get()->unique('fms')->toArray();
			return $kpi_data;
		}
	}
	
	if(!function_exists('dateTotime'))
	{
		function dateTotime($date){
			if($date!=''){
				$datetime = date('Y-m-d', strtotime($date));
				$datetimev = strtotime($datetime);
				return $datetimev;
			}else{
				return '';
			}
			
		}
	}
	
	if(!function_exists('getUserDetailById'))
	{
		function getUserDetailById($id){
			if($id!=0)
			{
			$data = DB::table('users')->where('id', '=', $id)->first();
			if($data!=''){
				$res = $data;
			}else{
				$res = '';
			}
			return $res;
			}else{
			return $res='';
			}		
		}
	}
	
	if(!function_exists('getDataforMISScore'))
	{
		function getDataforMISScore($fms_id,$user_id, $step_id, $kp_id, $mis_period, $mis_from, $mis_to){ 
					//die('--11--');
					$resData = array();
					$pending_data = array();
					$fms_data = array();
					$dateArray = array();
					if($mis_period=='last_week'){
						$previous_week = strtotime("-1 week +1 day");
						$start_week = strtotime("last monday midnight",$previous_week);
						$end_week = strtotime("next sunday",$start_week);
						
						$start_day = date("Y-m-d",$start_week);				
						$end_day = date("Y-m-d", $end_week);
						
						/* increase 1 day due to in query b/w datate */
						$end_day = date("Y-m-d", strtotime('+1 day'.$end_day));				
						$dateArray = array($start_day, $end_day);				
						//pr($dateArray);	die('1');				
					}elseif($mis_period=='custom_period'){
						
						$start_day = date("Y-m-d", strtotime($mis_from));
						$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
						$dateArray = array($start_day, $end_day);
					}elseif($mis_period=='till_date'){
						
						$start_day = date("2020-05-30");
						$toDay = date('Y-m-d');
						$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
						$dateArray = array($start_day, $end_day);
					}
					
					//pr($dateArray); die;
					$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
					//pr($fms); die;
					$fms = $fms[0];
					$fms_table= $fms['fms_table'];
					$fms_type= $fms['fms_type'];
					//pr($fms); die;
					if($mis_period=='till_date'){
						$start_day = date('Y-m-d');
					}
					
					$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day)));
					//pr($dateArrayPending); die('1');
					/* get user designation by id  merchant crm*/
					
					$whereUserQuery='';
					$udata = getUserDetailById($user_id);
					//pr($udata['designation']); die;
					$normal_user=0;
					if(array_key_exists('designation', $udata)){
						$d_data = $udata['designation'];
						if(array_key_exists('merchant', $d_data)){
							$whereUserQuery='merchant_data.m_id';
						}elseif(array_key_exists('crm', $d_data)){
							$whereUserQuery='crm_data.c_id';
						}elseif(array_key_exists('sales_executive', $d_data) || array_key_exists('sales_agent', $d_data) || array_key_exists('management_sales_executive', $d_data)){
							$whereUserQuery='s_person_data.s_id';
						}else{
							// for temperary
							//$whereUserQuery='merchant_data.m_id';
							$whereUserQuery=$step_id.'.user_id';
							$normal_user=1;
						}
					}else{
						$whereUserQuery='merchant_data.m_id';
					}
					$old_pending = array();
					
					/* for old pending */
					if($fms_type=='pi_fms_bulk'){
						//echo $step_id; die;
						//pr($dateArrayPending); die;
						/* seprate for step#17 and for non crm, merchant and sales_person */
						if($normal_user==1){
								$pending_data =	DB::table($fms_table)
												->select('invoice_no',$step_id)
												->where('fms_id',$fms_id)
												//->where($whereUserQuery,'=',$user_id)
												->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
												->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
												->where($step_id.'.actual', '=','')
												->whereBetween($step_id.'.planed', $dateArrayPending)
												->get()->toArray();
									//pr($pending_data); die;
					
								if(!empty($pending_data)){
									$old_pending[$kp_id]=count($pending_data);
									$resData['old_pending']=$old_pending;
								}else{
									$old_pending[$kp_id]=0;
									$resData['old_pending']=$old_pending;
								}
							
								$fms_data[$kp_id] 	= 	DB::table($fms_table)
														->select('fms_id','invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
														->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
														->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
														->whereBetween($step_id.'.planed', $dateArray)
														->get()->toArray();
														
								//pr($dateArray);
								//pr($fms_data); die;
						}else{
							//echo $whereUserQuery.'====user_id='.$user_id; pr($dateArrayPending); pr($dateArray); die('--CMS-user--');
							/* this loop will run when user is CMS user */
								$pending_data 	=	DB::table($fms_table)
										->select('invoice_no',$step_id)
										->where('fms_id',$fms_id)
										->where($whereUserQuery,'=',$user_id)
										->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
										->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
										->where($step_id.'.actual', '=','')
										->whereBetween($step_id.'.planed', $dateArrayPending)
										->get()->toArray();
					
								if(!empty($pending_data)){
									$old_pending[$kp_id]=count($pending_data);
									$resData['old_pending']=$old_pending;
								}else{
									$old_pending[$kp_id]=0;
									$resData['old_pending']=$old_pending;
								}
								//pr($resData); die;
								$fms_data[$kp_id] 	= 	DB::table($fms_table)
														->select('fms_id','invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where($whereUserQuery,'=',$user_id)
														->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
														->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
														->whereBetween($step_id.'.planed', $dateArray)
														->get()->toArray();
						}
												
						
						//pr($fms_data);
					}else if($fms_type=='pi_fms_sample'){
						
						$whereArr = array();
						if($step_id=='5eb25b870f0e750c5c0020d3'){
							$whereArr[]=array(
										'5eb25b870f0e750c5c0020d2.dropdown','=','No'
									);
						}
						
						if($normal_user==1){
								$pending_data 	=	DB::table($fms_table)
													->select('invoice_no',$step_id)
													->where('fms_id',$fms_id)
													->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->where($whereArr)
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->get()->toArray();
										
													if(!empty($pending_data)){
														$old_pending[$kp_id]=count($pending_data);
														$resData['old_pending']=$old_pending;
													}else{
														$old_pending[$kp_id]=0;
														$resData['old_pending']=$old_pending;
													}
							
								$fms_data[$kp_id] 	= 	DB::table($fms_table)
														->select('fms_id','invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where($whereArr)
														->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
														->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
														->whereBetween($step_id.'.planed', $dateArray)
														->get()->toArray();
														
								//pr($dateArray);
								//pr($fms_data); die;
						}else{
							$pending_data 	= 	DB::table($fms_table)
												->select('invoice_no',$step_id)
												->where('fms_id',$fms_id)
												->where($whereUserQuery, '=',$user_id)
												->where($whereArr)
												->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
												->where($step_id.'.actual', '=','')
												->whereBetween($step_id.'.planed', $dateArrayPending)
												->get()->toArray();
					
					
							if(!empty($pending_data)){
								$old_pending[$kp_id]=count($pending_data);
								$resData['old_pending']=$old_pending;
							}else{
									$old_pending[$kp_id]=0;
									$resData['old_pending']=$old_pending;
								}
					
							$fms_data[$kp_id] 	= 	DB::table($fms_table)
													->select('fms_id','invoice_no',$step_id)
													->where('fms_id',$fms_id)
													->where($whereArr)
													->where($whereUserQuery, '=',$user_id)
													->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
													->whereBetween($step_id.'.planed', $dateArray)
													->get()->toArray();
						}
							//pr($dateArray);
						//echo $step_id.'===u-id='.$user_id.'---fms='.$fms_id.'===fms-table'.$fms_table;
						//pr($fms_data); die;
					}else if($fms_type=='po_fms_import'){

								$pending_data 	=	DB::table($fms_table)
										->select('invoice_no',$step_id)
										->where('fms_id',$fms_id)
										->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
										->where($step_id.'.actual', '=','')
										->whereBetween($step_id.'.planed', $dateArrayPending)
										->get()->toArray();
										
					
								if(!empty($pending_data)){
									$old_pending[$kp_id]=count($pending_data);
									$resData['old_pending']=$old_pending;
								}else{
									$old_pending[$kp_id]=0;
									$resData['old_pending']=$old_pending;
								}
							
								$fms_data[$kp_id] 	= 	DB::table($fms_table)
														->select('fms_id','invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
														->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
														->whereBetween($step_id.'.planed', $dateArray)
														->get()->toArray();
														
								//pr($dateArray);
								//pr($fms_data); die;
						
							//pr($dateArray);
						//echo $step_id.'===u-id='.$user_id.'---fms='.$fms_id.'===fms-table'.$fms_table;
						//pr($fms_data); die;
					}else if($fms_type=='po_fms_local'){

								$pending_data 	=	DB::table($fms_table)
										->select('invoice_no',$step_id)
										->where('fms_id',$fms_id)
										->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
										->where($step_id.'.actual', '=','')
										->whereBetween($step_id.'.planed', $dateArrayPending)
										->get()->toArray();
										
								
								if(!empty($pending_data)){
									$old_pending[$kp_id]=count($pending_data);
									$resData['old_pending']=$old_pending;
								}else{
									$old_pending[$kp_id]=0;
									$resData['old_pending']=$old_pending;
								}
							
								$fms_data[$kp_id] 	= 	DB::table($fms_table)
														->select('fms_id','invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
														->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
														->whereBetween($step_id.'.planed', $dateArray)
														->get()->toArray();
														
								//pr($pending_data);
								//echo "<br/>===";
								//pr($fms_data); die;
						
							//pr($dateArray);
						//echo $step_id.'===u-id='.$user_id.'---fms='.$fms_id.'===fms-table'.$fms_table;
						//pr($fms_data); die;
					}else if($fms_type=='sample_cards'){
										if($normal_user==1){
												$pending_data 	=	DB::table($fms_table)
														->select('invoice_no',$step_id)
														->where('fms_id',$fms_id)
														//->where($whereUserQuery,'=',$user_id)
														->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
														->where($step_id.'.actual', '=','')
														->whereBetween($step_id.'.planed', $dateArrayPending)
														->get()->toArray();
														
									
												if(!empty($pending_data)){
													$old_pending[$kp_id]=count($pending_data);
													$resData['old_pending']=$old_pending;
												}else{
													$old_pending[$kp_id]=0;
													$resData['old_pending']=$old_pending;
												}
											
												$fms_data[$kp_id] 	= 	DB::table($fms_table)
																		->select('fms_id','invoice_no',$step_id)
																		->where('fms_id',$fms_id)
																		->where(function($q) use ($step_id, $user_id){
																			   $q->where($step_id.'.user_id',"=",$user_id)
																				->orWhere($step_id.'.actual',"=","");
																			})
																		->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
																		->whereBetween($step_id.'.planed', $dateArray)
																		->get()->toArray();
																		
												//pr($dateArray);
											//pr($fms_data); die;
										}else{
											$pending_data 	= 	DB::table($fms_table)
														->select('invoice_no',$step_id)
														->where('fms_id',$fms_id)
														->where($whereUserQuery, '=',$user_id)
														->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
														->where($step_id.'.actual', '=','')
														->whereBetween($step_id.'.planed', $dateArrayPending)
														->get()->toArray();
									
									
											if(!empty($pending_data)){
												$old_pending[$kp_id]=count($pending_data);
												$resData['old_pending']=$old_pending;
											}else{
													$old_pending[$kp_id]=0;
													$resData['old_pending']=$old_pending;
												}
									
											$fms_data[$kp_id] 	= 	DB::table($fms_table)
																->select('fms_id','invoice_no',$step_id)
																->where('fms_id',$fms_id)
																->where($whereUserQuery, '=',$user_id)
																->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
																->whereBetween($step_id.'.planed', $dateArray)
																->get()->toArray();
										}
											//pr($dateArray);
										//echo $step_id.'===u-id='.$user_id.'---fms='.$fms_id.'===fms-table'.$fms_table;
										//pr($fms_data); die;
									
					}
					
					$resData['fms_data']=$fms_data;
					return $resData;
				
		}
	}
	
	if(!function_exists('getDataforMISScoreOcFms'))
	{
		function getDataforMISScoreOcFms($fms_id,$user_id, $step_id, $kp_id, $mis_period, $mis_from, $mis_to){
					//die('--222--');
					$resData = array();
					$pending_data = array();
					$fms_data = array();
					$dateArray = array();
					if($mis_period=='last_week'){
						$previous_week = strtotime("-1 week +1 day");
						$start_week = strtotime("last monday midnight",$previous_week);
						$end_week = strtotime("next sunday",$start_week);
						
						$start_day = date("Y-m-d",$start_week);				
						$end_day = date("Y-m-d", $end_week);
						
						/* increase 1 day due to in query b/w datate */
						$end_day = date("Y-m-d", strtotime('+1 day'.$end_day));				
						$dateArray = array($start_day, $end_day);				
						//pr($dateArray);	die('1');				
					}elseif($mis_period=='custom_period'){
						
						$start_day = date("Y-m-d", strtotime($mis_from));
						$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
						$dateArray = array($start_day, $end_day);
					}elseif($mis_period=='till_date'){
						
						$start_day = date("2020-05-30");
						$toDay = date('Y-m-d');
						$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
						$dateArray = array($start_day, $end_day);
					}
					
					//pr($dateArray); die;
					$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
					//pr($fms); die;
					$fms = $fms[0];
					$fms_table= $fms['fms_table'];
					$fms_type= $fms['fms_type'];
					
					if($mis_period=='till_date'){
						$start_day = date('Y-m-d');
					}
					
					$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day)));
					//pr($dateArrayPending);	die('1');
					
					$whereUserQuery='';
					$udata = getUserDetailById($user_id);
					if(array_key_exists('designation', $udata)){
						$d_data = $udata['designation'];
						if(array_key_exists('merchant', $d_data)){
							$whereUserQuery='merchant_data.m_id';
						}elseif(array_key_exists('crm', $d_data)){
							$whereUserQuery='crm_data.c_id';
						}elseif(array_key_exists('sales_executive', $d_data) || array_key_exists('sales_agent', $d_data) || array_key_exists('management_sales_executive', $d_data)){
							$whereUserQuery='s_person_data.s_id';
						}else{
							// for temperary
							$whereUserQuery=$step_id.'.user_id';
						}
					}else{
						$whereUserQuery=$step_id.'.user_id';
					}	
					/* 5e9946af0f0e75131c0037f5 = step 16 */
					/* 5e9946b00f0e75131c0037f6 = step 17 */
					//pr($dateArray); die;
						$fms_data[$kp_id] 	= 	DB::table($fms_table)
												->select('fms_id','invoice_no','unit','bulkqty',$step_id,'5e9946b00f0e75131c0037f6')
												->where('fms_id',$fms_id)
												->where(function($q) use ($user_id){
													   $q->where('5e9946af0f0e75131c0037f5.user_id',"=",$user_id)
														->orWhere('5e9946b00f0e75131c0037f6.user_id',"=",$user_id);
													})
												->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
												->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
												->whereBetween('5e9946b00f0e75131c0037f6.planed', $dateArray)
												->get()->toArray();
					
					//pr($fms_data); die('helper');
					//die;
					$resData['fms_data']=$fms_data;
					return $resData;
				
		}
	}
	
	
	if(!function_exists('isCMSUser'))
	{
		function isCMSUser($user_id){
					/* cmsuser = CRM, Merchant and Sales_person user */
					$cmsuser=0;
					$udata = getUserDetailById($user_id);
					if(array_key_exists('designation', $udata)){
						$d_data = $udata['designation'];
						if(array_key_exists('merchant', $d_data)){
							$cmsuser=1;
						}elseif(array_key_exists('crm', $d_data)){
							$cmsuser=1;
						}elseif(array_key_exists('sales_executive', $d_data) || array_key_exists('sales_agent', $d_data) || array_key_exists('management_sales_executive', $d_data)){
							$cmsuser=1;
						}else{
							$cmsuser=0;
						}
					}else{
						$cmsuser=0;
					}
				return $cmsuser;
		}
	}
	
	
	if(!function_exists('UpdateS7planned'))
	{
		function UpdateS7planned($data_id, $step_id, $s7_planned){
				$data[$step_id] = array(
									'planed'=>$s7_planned,
									'actual'=>''
								);
				$upId = DB::table('pi_fms_sample_data')
					->where('_id', $data_id)
					->update($data); 
				if($upId){
					return $s7_planned;
				}else{
					return "";
				}
		}
	}
	
	if(!function_exists('UpdateplannedActual'))
	{
		function UpdateplannedActual($fms_id, $data_id, $step_id, $planned){
				//echo $fms_id.'===='.$data_id.'===='.$step_id.'===='.$planned; die('===');
				$fms_data = DB::table('fmss')->where('_id',$fms_id)->get()->toArray();
				$fms_data_tbl = $fms_data[0]['fms_table'];
				$data[$step_id] = array(
										'planed'=>$planned,
										'actual'=>''
									);
				$upId = DB::table($fms_data_tbl)
						->where('_id', $data_id)
						->update($data); 
				if($upId){
					return $planned;
				}else{
					return "";
				}
		}
	}
	
	if(!function_exists('UpdateplannedActualPiNo'))
	{
		function UpdateplannedActualPiNo($fms_id, $data_id, $step_id, $planned){
				//echo $fms_id.'===='.$data_id.'===='.$step_id.'===='.$planned; die('===');
				$fms_data = DB::table('fmss')->where('_id',$fms_id)->get()->toArray();
				$fms_data_tbl = $fms_data[0]['fms_table'];
				$data[$step_id] = array(
										'planed'=>$planned,
										'actual'=>'',
										'pi_number'=>''
									);
				$upId = DB::table($fms_data_tbl)
						->where('_id', $data_id)
						->update($data); 
				if($upId){
					return $planned;
				}else{
					return "";
				}
		}
	}
	
	if(!function_exists('GetBuyerDataById'))
	{
		function GetBuyerDataById($id){
			$data = DB::table('register_form')
					->where('_id', '=', $id)
					->first();	
			if(!empty($data)){
					 return $data;
			}else{
				return array();
			}
		}
	}
	
	if(!function_exists('verifyBuyerLogin'))
	{
		function verifyBuyerLogin($mdemail, $password){
			$data = DB::table('register_form')
					->where('user_login_id',$mdemail)
					->where('org_password',$password)
					->first();	
			if(!empty($data)){
					 return $data;
			}else{
				return array();
			}
		}
	}
	
	if(!function_exists('verifyBrandLogin'))
	{
		function verifyBrandLogin($mdemail, $password){
			$data = DB::table('brand')
					->where('user_login_id',$mdemail)
					->where('org_password',$password)
					->first();	
			if(!empty($data)){
					 return $data;
			}else{
				return array();
			}
		}
	}
	
	if(!function_exists('getBrandsList'))
	{
		function getBrandsList(){
			$data = DB::table('brand')->get()->toArray();
			$resArr = array();
			foreach($data as $row){
				$oid = (array) $row['_id'];
				$id = $oid['oid']; 
				$resArr[$id]= addslashes(str_replace("'","",$row['brand']));
			}
						
			return json_encode($resArr);
			exit;
		}
	}
	
	
	//
	if(!function_exists('multi_attach_mail'))
	{
		function multi_attach_mail($to,$cc, $subject, $message, $senderEmail, $senderName, $files = array()){
 
			$from = $senderName." <".$senderEmail.">";  
			$headers = "From: $from"."\r\n" ;
			$headers .= "Reply-To: ".$senderEmail."\r\n";
			$headers .= "CC: $cc"; 
		 
			// Boundary  
			$semi_rand = md5(time());  
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
		 
			// Headers for attachment  
			$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";  
		 
			// Multipart boundary  
			$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
			"Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";  
		 
			// Preparing attachment 
			if(!empty($files)){ 
				for($i=0;$i<count($files);$i++){ 
					if(is_file($files[$i])){ 
						$file_name = basename($files[$i]); 
						$file_size = filesize($files[$i]); 
						 
						$message .= "--{$mime_boundary}\n"; 
						$fp =    @fopen($files[$i], "rb"); 
						$data =  @fread($fp, $file_size); 
						@fclose($fp); 
						$data = chunk_split(base64_encode($data)); 
						$message .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\n" .  
						"Content-Description: ".$file_name."\n" . 
						"Content-Disposition: attachment;\n" . " filename=\"".$file_name."\"; size=".$file_size.";\n" .  
						"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
					} 
				} 
			} 
			 
			$message .= "--{$mime_boundary}--"; 
			$returnpath = "-f" . $senderEmail; 
			 
			// Send email 
			$mail = @mail($to, $subject, $message, $headers, $returnpath);  
			 
			// Return true, if email sent, otherwise return false 
			if($mail){ 
				return true; 
			}else{ 
				return false; 
			} 
		}
	}
	
	
	if(!function_exists('getArticleByCategory'))
	{
		function getArticleByCategory($cat_list){
			$mainData = array();
			if(!empty($cat_list)){
				foreach($cat_list as $cat){
					$catArr = explode('-', $cat);
					//pr($catArr);
					$category = $catArr[0];
					$sales_stage = $catArr[1];
				$thisdb = DB::table('articles')->select('article_no','max_price','unit','description','composition','count_construct','gsm','width','category','sales_stage');
					$thisdb->where('category','like','%'.$category.'%');
					$thisdb->where('sales_stage', $sales_stage);
					$data = $thisdb->get()->toArray();
					foreach($data as $res){
						$mainData[] = $res;
					}
					
					//pr($data);
				}
				//pr($mainData); die('--helper--');
				//$cat_arr = 
			}
			return $mainData;
		}
	}
	


	if(!function_exists('getCurrentUserDetails'))
	{
		function getCurrentUserDetails(){
				$currentUserId = Auth::user()->id;
				$data = DB::table('users')->where('id',$currentUserId)->get()->toArray();
				//pr($data); die;
				if(!empty($data)){
					return $data[0];
				}else{
					return array();
				}
				
		}
	}

	if(!function_exists('getOptionValueByKeyValue'))
	{
		function getOptionValueByKeyValue($key,$itemKey){
			$res = '';
			$data = DB::table('dropdown_items')
					->where('key', '=', $key)
					->first();
			if(!empty($data)){
				if(array_key_exists($itemKey, $data['items'])){
					$res = $data['items'][$itemKey];
				}else{
					return $res;
				}
				
			}
			return $res;	
		}
	}
	
	
	if(!function_exists('getPstData'))
	{
		function getPstData($user_id, $currentUserId='', $currentRole=''){
			 //var_dump($currentRole); die;
			//echo $user_id.'===kkkk=='.$currentUserId.'==tttt==='.$currentRole; die;
			
			if($currentRole=='1'){
				// for admin
				$data = DB::table('pst_buyer_brand')
						->get()->toArray();
			}elseif($currentRole!='1' && $user_id!=''){
				// for buyer/brand
				//pr(' buyer/brand'); die;
				$data = DB::table('pst_buyer_brand')
					->where('user_id', '=', $user_id)
					->get()->toArray();
			}else{
					// for agent merchant
					$udata = getUserDetailById($currentUserId);
					//pr($udata); die;
					if(array_key_exists('designation', $udata)){
			
						$d_data = $udata['designation'];
						
						if(array_key_exists('merchant', $d_data)){
							
							$data = DB::table('pst_buyer_brand')
									->where('merchant_name', $currentUserId)
									->get()->toArray();
							
						}elseif(array_key_exists('sales_executive', $d_data) || array_key_exists('sales_agent', $d_data) || array_key_exists('management_sales_executive', $d_data)){
							$data = DB::table('pst_buyer_brand')
									->where('agent', $currentUserId)
									->get()->toArray();
						}
					}
			}
				
			//pr($data); die('all');
			if(!empty($data)){
						return $data;
			}else{
				return array();
			}
			
		}
	}
	
	if(!function_exists('getBuyerBrandData'))
	{
		function getBuyerBrandData($user_id, $login_type){
			if($login_type=='buyer_login'){
				$data = DB::table('register_form')
					->where('_id', '=', $user_id)
					->get()->first();
			}elseif($login_type=='brand_login'){
				$data = DB::table('brand')
					->where('_id', '=', $user_id)
					->get()->first();
			}
			
			if(!empty($data)){
				return $data;
			}else{
				return array();
			}	
		}
	}
	
	if(!function_exists('isMerchantSalesPerson'))
	{
		function isMerchantSalesPerson($currentUserId){			
			$udata = getUserDetailById($currentUserId);
			//pr($udata); die;
			if(array_key_exists('designation', $udata)){
	
				$d_data = $udata['designation'];
				
				if(array_key_exists('merchant', $d_data)){
					return true;
				}elseif(array_key_exists('sales_executive', $d_data) || array_key_exists('sales_agent', $d_data) || array_key_exists('management_sales_executive', $d_data)){
					return true;
				}else{
					return false;
				}
			}else{
					return false;
			}
		}
	}
	
	if(!function_exists('isMerchant'))
	{
		function isMerchant($currentUserId){			
			$udata = getUserDetailById($currentUserId);
			//pr($udata); die;
			if(array_key_exists('designation', $udata)){
	
				$d_data = $udata['designation'];
				
				if(array_key_exists('merchant', $d_data)){
					return true;
				}else{
					return false;
				}
			}else{
					return false;
			}
		}
	}

	
	if(!function_exists('get_Pichart_bulk_data'))
	{
		function get_Pichart_bulk_data($queryArr, $pi_from, $pi_to){
			$currentUserId = Auth::user()->id;
			//echo $currentUserId; die;
			if($pi_from!='' && $pi_to!=''){
							//pr($queryArr); die;
							/* for FOC FMS*/
							/* for FMS OC */
							$fms_id = '5e79feefd049043d090fdbb2';							
							$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
							$fms = $fms[0];
							$fms_table= $fms['fms_table'];
							
							$whereIn = array();
							$where = array(
											array(
												'fms_id', '=', $fms_id
											)
										);
										
							// pi date 
							$pi_from_dt =date('Y-m-d', strtotime($pi_from));
							
							$where[]=array(
								'pi_date','>=',$pi_from_dt
							);
						
							
							$pi_to_dt =date('Y-m-d', strtotime($pi_to."+ 1 day"));							
							$where[]=array(
								'pi_date','<',$pi_to_dt
							);
							
							
							// Buyer Name
							if(array_key_exists('customer_data', $queryArr) && $queryArr['customer_data']!=''){
								$where[]=array(
										'customer_data.c_value','=',trim($queryArr['customer_data'])
									);
							}
							
							// Brand Name
							if(array_key_exists('brand_data', $queryArr) && $queryArr['brand_data']!=''){
								$where[]=array(
										'brand_data.b_value','=',trim($queryArr['brand_data'])
									);
							}
							
							
							//Remove restriction from Chetan Sir, Nilesh and all Merchants.
							if(!isMerchant($currentUserId) && $currentUserId!='5eb92e42d049046ac25f7922' && $currentUserId!='5eb92f65d049046bd9393f92'){
								if(!empty($queryArr['s_person_data'])){
									$whereIn['s_person_data.s_value']=$queryArr['s_person_data'];
								}
							}
							
														
							// merchant_data							
							if(!empty($queryArr['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($queryArr['merchant_data'])
									);
							}
							
							
							
							// remove cancel
							$where[]=array(
										'5e8ef2c80f0e75228400661b.dropdown','!=','Cancelled' 
									);
									
							$where[]=array(
										'5e9a9ecb0f0e750494006a35.dropdownlist','!=','Cancelled' 
									);
							//pr($where); die;  
							$all_data 	= DB::table($fms_table)->select('invoice_no','bulkqty','unit','unit_price','etd','5e9946af0f0e75131c0037f5','5e9946b00f0e75131c0037f6','5e9a9ecb0f0e750494006a35','5e9a9ecb0f0e750494006a33');
							$all_data 	= $all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
								$all_data 	= $all_data->get()->toArray();
							
							//pr($all_data); die;
							$all_order =  count($all_data);
							if(!empty($all_data)){
								$or_closed_part=0;
							$or_pending=0;
							$or_pending_future=0;
							
							$total_qty=0;
							$order_closed=0;
							$order_pending=0;
							$order_pending_future=0;
							$total_pi=0;
							$invoice_amnt=0;
							$Mtr=0; $Kg =0; $Yd =0;	 $Pcs =0;	
							
							$Mtr_cl=0; $Kg_cl=0; $Yd_cl=0; $Pcs_cl=0;
							$Mtr_pd=0; $Kg_pd=0; $Yd_pd=0; $Pcs_pd=0;
							$Mtr_pd_n=0; $Kg_pd_n=0; $Yd_pd_n=0; $Pcs_pd_n=0;
							
							$order_closed_cnt=0;
							$order_pending_cnt=0;
							$order_pending_fu_cnt=0;
							$pendingArr = array();
							$noOrdArr = array();
							
							$cnt_partshipped = 0;
							
							//$unit_price
							$inv_val=0;
							$inv_val_cl=0;
							$inv_val_pd=0;
							$inv_val_pd_n=0;
							
							foreach($all_data as $row){
								
								$oid = (array) $row['_id'];
								$id = $oid['oid'];
								if($row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='Part Shipped'){
									$cnt_partshipped = $cnt_partshipped+1;
								}
								
								if($row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='Closed' && $row['5e9a9ecb0f0e750494006a35']['dropdownlist']!='Part Shipped'){
									$or_closed_part = $or_closed_part+$row['bulkqty'];
									$order_closed_cnt = $order_closed_cnt+1;
									
									if($row['5e9946af0f0e75131c0037f5']['act_qty']!='' && $row['5e9946af0f0e75131c0037f5']['act_qty']>0){
										
										
										/* if($row['unit']=='Mtr'){
										$Mtr_cl +=$row['bulkqty'];
						
										}elseif($row['unit']=='Kg'){
											$Kg_cl +=$row['bulkqty'];
										}elseif($row['unit']=='Yd'){
											$Yd_cl +=$row['bulkqty'];
										}elseif($row['unit']=='Pcs'){
											$Pcs_cl +=$row['bulkqty'];
										} */
										
										if($row['unit']=='Mtr'){
										$Mtr_cl +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
						
										}elseif($row['unit']=='Kg'){
											$Kg_cl +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}elseif($row['unit']=='Yd'){
											$Yd_cl +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}elseif($row['unit']=='Pcs'){
											$Pcs_cl +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}
										
										$inv_val_cl = $inv_val_cl+($row['unit_price']*$row['5e9946af0f0e75131c0037f5']['act_qty']);
									}
									
								
								}
								
								if(($row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='Pending' || $row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='') && strtotime($row['etd'])<strtotime(date('Y-m-d'))){
									array_push($pendingArr, $id);
									$or_pending= $or_pending+$row['bulkqty'];
									$order_pending_cnt= $order_pending_cnt+1;
									
									if($row['unit']=='Mtr'){
										$Mtr_pd +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_pd +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_pd +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_pd +=$row['bulkqty'];
									}
									
									if($row['5e9946af0f0e75131c0037f5']['act_qty']!='' && $row['5e9946af0f0e75131c0037f5']['act_qty']>0){
										$inv_val_pd = $inv_val_pd+($row['unit_price']*$row['5e9946af0f0e75131c0037f5']['act_qty']);
									}
									
								}elseif(($row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='Pending' || $row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='') && !in_array($id,$pendingArr) && strtotime($row['etd'])>=strtotime(date('Y-m-d'))){
									$or_pending_future= $or_pending_future+$row['bulkqty'];
									$order_pending_fu_cnt= $order_pending_fu_cnt+1;
									
									if($row['unit']=='Mtr'){
										$Mtr_pd_n +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_pd_n +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_pd_n +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_pd_n +=$row['bulkqty'];
									}
									
									if($row['5e9946af0f0e75131c0037f5']['act_qty']!='' && $row['5e9946af0f0e75131c0037f5']['act_qty']>0){
										$inv_val_pd_n = $inv_val_pd_n+($row['unit_price']*$row['5e9946af0f0e75131c0037f5']['act_qty']);
									}
									
								}else{
									array_push($noOrdArr, $id);
								}
								
								$total_qty=$total_qty+$row['bulkqty'];
								if($row['5e9a9ecb0f0e750494006a33']['inv_amount']!=''){
									$invoice_amnt= $invoice_amnt+$row['5e9a9ecb0f0e750494006a33']['inv_amount'];
								}
								$total_pi=$total_pi+1;
								
								if($row['5e9a9ecb0f0e750494006a35']['dropdownlist']!='Part Shipped'){
									if($row['unit']=='Mtr'){
										$Mtr +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs +=$row['bulkqty'];
									}
									//pr($row); die;
									//$inv_val = $inv_val+($row['unit_price']*$row['5e9946af0f0e75131c0037f5']['act_qty']);
									$inv_val = $inv_val+($row['unit_price']*$row['bulkqty']);
								}
							}
							
							$all_order = ($all_order - $cnt_partshipped);
							//pr($pendingArr); die;
							
							
							$order_closed = number_format((($or_closed_part)),2,'.','');
							$order_pending = number_format((($or_pending)),2,'.','');
							$order_pending_future = number_format((($or_pending_future)),2,'.','');
							$total_qty = number_format((($total_qty)),2,'.','');
							
							/* echo $order_closed; 
							echo '<br/>'; 
							echo $or_closed_part.'===='.$or_pending_blank.'==tottal==='.$total_qty; die; */
							//die('helper==');
							
							$res['fms_bulk'] = array(
													'no_ord'=>$noOrdArr,
													'order_closed'=>$order_closed,
													'order_pending'=>$order_pending,
													'order_pending_future'=>$order_pending_future,
													'total_qty'=>$total_qty,
													'total_pi'=>$total_pi,
													'invoice_amnt'=>$invoice_amnt,
													'all_order'=>$all_order,
													'order_closed_cnt'=>$order_closed_cnt,
													'order_pending_cnt'=>$order_pending_cnt,
													'order_pending_fu_cnt'=>$order_pending_fu_cnt,
													'qty_unit_wise_total'=>array(
																				'Mtr'=>$Mtr,
																				'Kg'=>$Kg,
																				'Yd'=>$Yd,
																				'Pcs'=>$Pcs
																			),
													'qty_unit_wise_close'=>array(
																				'Mtr'=>$Mtr_cl,
																				'Kg'=>$Kg_cl,
																				'Yd'=>$Yd_cl,
																				'Pcs'=>$Pcs_cl,
																				'inv_val_cl'=>$inv_val_cl,
																			),
													'qty_unit_wise_pending'=>array(
																				'Mtr'=>$Mtr_pd,
																				'Kg'=>$Kg_pd,
																				'Yd'=>$Yd_pd,
																				'Pcs'=>$Pcs_pd,
																				'inv_val_pd'=>$inv_val_pd,
																			),
													'qty_unit_wise_pending_not'=>array(
																				'Mtr'=>$Mtr_pd_n,
																				'Kg'=>$Kg_pd_n,
																				'Yd'=>$Yd_pd_n,
																				'Pcs'=>$Pcs_pd_n,
																				'inv_val_pd_n'=>$inv_val_pd_n,
																			),
													'inv_val'=>$inv_val,
										);
							//pr($res); die;
							return $res;
							}else{
								$res['fms_bulk'] = array(
											'order_closed'=>0,
											'order_pending'=>0,
											'order_pending_future'=>0,
											'total_qty'=>0,
											'total_pi'=>0,
											'invoice_amnt'=>0,
										);
							//pr($res); die;
							return $res;
							}
							
			}
			
			
		}
	}
	
	
	if(!function_exists('get_Pichart_sampling_data'))
	{
		function get_Pichart_sampling_data($queryArr, $pi_from, $pi_to){
			$currentUserId = Auth::user()->id;
			if($pi_from!='' && $pi_to!=''){
							//pr($queryArr); die;
							$fms_id = '5eb254aa0f0e751788000094';							
							$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
							$fms = $fms[0];
							$fms_table= $fms['fms_table'];
							
							$whereIn = array();
							$where = array(
											array(
												'fms_id', '=', $fms_id
											)
										);
										
							// pi date 
							$pi_from_dt =date('Y-m-d', strtotime($pi_from));
							
							$where[]=array(
								'pi_date','>=',$pi_from_dt
							);
						
							
							$pi_to_dt =date('Y-m-d', strtotime($pi_to."+ 1 day"));							
							$where[]=array(
								'pi_date','<',$pi_to_dt
							);
							
							
							// Buyer Name
							if(array_key_exists('customer_data', $queryArr) && $queryArr['customer_data']!=''){
								$where[]=array(
										'customer_data.c_value','=',trim($queryArr['customer_data'])
									);
							}
							
							// Brand Name
							if(array_key_exists('brand_data', $queryArr) && $queryArr['brand_data']!=''){
								$where[]=array(
										'brand_data.b_value','=',trim($queryArr['brand_data'])
									);
							}
							
							
							if(!isMerchant($currentUserId) && $currentUserId!='5eb92e42d049046ac25f7922' && $currentUserId!='5eb92f65d049046bd9393f92'){
								if(!empty($queryArr['s_person_data'])){
									$whereIn['s_person_data.s_value']=$queryArr['s_person_data'];
								}
							}
														
							// merchant_data							
							if(!empty($queryArr['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($queryArr['merchant_data'])
									);
							}
							
							
							// remove cancel
							$where[]=array(
										'5eb2ac560f0e750c5c0020eb.dropdownlist','!=','Cancelled' 
									);
							//pr($where); die; 5e9a9ecb0f0e750494006a35
							$all_data 	= DB::table($fms_table)->select('invoice_no','bulkqty','unit','etd','5eb2ac560f0e750c5c0020eb');
							$all_data 	= $all_data->where($where);						
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							$all_data 	= $all_data->get()->toArray();
							//pr($all_data); die;
							$all_order =  count($all_data);
						
						if(!empty($all_data)){
							$or_closed_part=0;
							$or_pending=0;
							$or_pending_future=0;
							
							$total_qty=0;
							$order_closed=0;
							$order_closed_cnt=0;
							$order_pending_cnt=0;
							$order_pending_fu_cnt=0;
							
							$order_pending=0;
							
							$order_pending_future=0;
							$Mtr=0; $Kg =0; $Yd =0;	 $Pcs =0;
							
							$Mtr_cl=0; $Kg_cl=0; $Yd_cl=0; $Pcs_cl=0;
							$Mtr_pd=0; $Kg_pd=0; $Yd_pd=0; $Pcs_pd=0;
							$Mtr_pd_n=0; $Kg_pd_n=0; $Yd_pd_n=0; $Pcs_pd_n=0;
							
							
							$pendingArr = array();
							$noOrdArr = array();
							
							$cnt_partshipped = 0;
							
							
							foreach($all_data as $row){
								
								$oid = (array) $row['_id'];
								$id = $oid['oid'];
								
								if($row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='Part Shipped'){
									$cnt_partshipped = $cnt_partshipped+1;
								}
								
								if($row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='Closed' && $row['5eb2ac560f0e750c5c0020eb']['dropdownlist']!='Part Shipped'){
									//pr($row); die('kkk');
									$or_closed_part= $or_closed_part+$row['bulkqty'];
									$order_closed_cnt= $order_closed_cnt+1;
									
									if($row['unit']=='Mtr'){
										$Mtr_cl +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_cl +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_cl +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_cl +=$row['bulkqty'];
									}
									
								}elseif(($row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='Pending' || $row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='') && !in_array($id,$pendingArr) && strtotime($row['etd'])<strtotime(date('Y-m-d'))){
									array_push($pendingArr, $id);
									$or_pending= $or_pending+$row['bulkqty'];
									$order_pending_cnt= $order_pending_cnt+1;
									
									if($row['unit']=='Mtr'){
										$Mtr_pd +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_pd +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_pd +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_pd +=$row['bulkqty'];
									}
									
								}elseif(($row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='Pending' || $row['5eb2ac560f0e750c5c0020eb']['dropdownlist']=='') && strtotime($row['etd'])>=strtotime(date('Y-m-d'))){
									$or_pending_future= $or_pending_future+$row['bulkqty'];
									$order_pending_fu_cnt= $order_pending_fu_cnt+1;
									
									if($row['unit']=='Mtr'){
										$Mtr_pd_n +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_pd_n +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_pd_n +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_pd_n +=$row['bulkqty'];
									}
									
								}else{
									array_push($noOrdArr, $id);
								}
								
								$total_qty=$total_qty+$row['bulkqty'];
								
								if($row['5eb2ac560f0e750c5c0020eb']['dropdownlist']!='Part Shipped'){
									if($row['unit']=='Mtr'){
										$Mtr +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs +=$row['bulkqty'];
									}
								}
							}
							
							$all_order = ($all_order - $cnt_partshipped);
							
							$order_closed = number_format((($or_closed_part)),2,'.','');
							$order_pending = number_format((($or_pending)),2,'.','');
							$order_pending_future = number_format((($or_pending_future)),2,'.','');
							$total_qty = number_format((($total_qty)),2,'.','');
							
							/* $res['fms_sampling'] = array(
															'order_closed'=>$order_closed,
															'order_pending'=>$order_pending,
															'order_pending_future'=>$order_pending_future,
															'total_qty'=>$total_qty,
															'all_order'=>$all_order,
															'order_closed_cnt'=>$order_closed_cnt,
															'order_pending_cnt'=>$order_pending_cnt,
															'order_pending_fu_cnt'=>$order_pending_fu_cnt,
															'qty_unit_wise'=>array(
																								'Mtr'=>$Mtr,
																								'Kg'=>$Kg,
																								'Yd'=>$Yd,
																								'Pcs'=>$Pcs
																							),
										); */
							$res['fms_sampling'] = array(
															'no_ord'=>$noOrdArr,
															'order_closed'=>$order_closed,
															'order_pending'=>$order_pending,
															'order_pending_future'=>$order_pending_future,
															'total_qty'=>$total_qty,
															'total_pi'=>$total_pi,
															'invoice_amnt'=>$invoice_amnt,
															'all_order'=>$all_order,
															'order_closed_cnt'=>$order_closed_cnt,
															'order_pending_cnt'=>$order_pending_cnt,
															'order_pending_fu_cnt'=>$order_pending_fu_cnt,
															'qty_unit_wise_total'=>array(
																						'Mtr'=>$Mtr,
																						'Kg'=>$Kg,
																						'Yd'=>$Yd,
																						'Pcs'=>$Pcs
																					),
															'qty_unit_wise_close'=>array(
																						'Mtr'=>$Mtr_cl,
																						'Kg'=>$Kg_cl,
																						'Yd'=>$Yd_cl,
																						'Pcs'=>$Pcs_cl,
																					),
															'qty_unit_wise_pending'=>array(
																						'Mtr'=>$Mtr_pd,
																						'Kg'=>$Kg_pd,
																						'Yd'=>$Yd_pd,
																						'Pcs'=>$Pcs_pd,
																					),
															'qty_unit_wise_pending_not'=>array(
																						'Mtr'=>$Mtr_pd_n,
																						'Kg'=>$Kg_pd_n,
																						'Yd'=>$Yd_pd_n,
																						'Pcs'=>$Pcs_pd_n,
																					)
													);
							//pr($res); die;
							return $res;
						}else{
							$res['fms_sampling'] = array(
											'order_closed'=>0,
											'order_pending'=>0,
											'order_pending_future'=>0,
											'total_qty'=>0,
											'all_order'=>0,
											'order_closed_cnt'=>0,
											'order_pending_cnt'=>0,
											'order_pending_fu_cnt'=>0,
											'qty_unit_wise'=>array(
																				'Mtr'=>0,
																				'Kg'=>0,
																				'Yd'=>0,
																				'Pcs'=>0
																			),
										);
							//pr($res); die;
							return $res;
						}	
							
			}
			
			
		}
	}
	
	if(!function_exists('get_Pichart_samplecard_data'))
	{
		function get_Pichart_samplecard_data($queryArr, $pi_from, $pi_to){
			if($pi_from!='' && $pi_to!=''){
							//pr($queryArr); die();
							$fms_id = '5f3522250f0e7503b80030d7';							
							$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
							$fms = $fms[0];
							$fms_table= $fms['fms_table'];
							
							$whereIn = array();
							$where = array(
											array(
												'fms_id', '=', $fms_id
											)
										);
										
							// pi date 
							$pi_from_dt =date('Y-m-d', strtotime($pi_from));
							
							$where[]=array(
								'order_date','>=',$pi_from_dt
							);
						
							
							$pi_to_dt =date('Y-m-d', strtotime($pi_to."+ 1 day"));							
							$where[]=array(
								'order_date','<',$pi_to_dt
							); 
							
							// Buyer Name
							if(array_key_exists('customer_data', $queryArr) && $queryArr['customer_data']!=''){
								$where[]=array(
										'customer_data.c_value','=',trim($queryArr['customer_data'])
									);
							}
							
							// Brand Name
							if(array_key_exists('brand_data', $queryArr) && $queryArr['brand_data']!=''){
								$where[]=array(
										'brand_data.b_value','=',trim($queryArr['brand_data'])
									);
							}
							
							
							if(!isMerchant($currentUserId) && $currentUserId!='5eb92e42d049046ac25f7922' && $currentUserId!='5eb92f65d049046bd9393f92'){
								if(!empty($queryArr['s_person_data'])){
									$whereIn['s_person_data.s_value']=$queryArr['s_person_data'];
								}
							}
														
							// merchant_data							
							if(!empty($queryArr['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($queryArr['merchant_data'])
									);
							}
							
							// remove cance
							$where[]=array(
										'5f36806e0f0e751744007a49.dropdownlist','!=','Cancelled' 
									);
						
							$all_data 	= DB::table($fms_table)->select('5f36806e0f0e751744007a49');
							$all_data 	= $all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							$all_data 	= $all_data->get()->toArray();
							
							//pr($all_data); die('card');
							$all_order =  count($all_data);
							
							$or_closed_part=0;
							$or_pending=0;
							$or_pending_future=0;
							
							$total_qty=0;
							$order_closed=0;
							$order_pending=0;
							$order_pending_future=0;
							
							$order_closed_cnt=0;
							$order_pending_cnt=0;
							$order_pending_fu_cnt=0;
							
							
							foreach($all_data as $row){
								
								$plymd = date('Y-m-d', strtotime($row['5f36806e0f0e751744007a44']['planed']));
								
								if($row['5f36806e0f0e751744007a49']['dropdownlist']=='Closed'){
									$or_closed_part= $or_closed_part+1;
								}
								
								
								if($row['5f36806e0f0e751744007a49']['dropdownlist']=='' && strtotime($plymd)<strtotime(date('Y-m-d'))){
									$or_pending= $or_pending+1;
								}
								$total_qty=$total_qty+1;
								
								if($row['5f36806e0f0e751744007a49']['dropdownlist']=='' && strtotime($plymd)>=strtotime(date('Y-m-d'))){
									$or_pending_future= $or_pending_future+1;
								}
							}
							
							$res['fms_samplecard'] = array(
											'order_closed'=>$or_closed_part,
											'order_pending'=>$or_pending,
											'order_pending_future'=>$or_pending_future,
											'total_qty'=>$total_qty,
											'all_order'=>$all_order,
										);
							//pr($res); die;
							return $res;
							
			}
			
			
		}
	}
	
	if(!function_exists('getbuyerbrandmis'))
	{
		function getbuyerbrandmis($queryArr){
			//pr($queryArr); die;
			if($queryArr['pi_from']!='' && $queryArr['pi_to']!=''){
							//pr($queryArr); die;
							/* for FOC FMS*/
							/* for FMS OC */
							$pi_from = $queryArr['pi_from'];
							$pi_to = $queryArr['pi_to'];
							
							$fms_id = '5e79feefd049043d090fdbb2';							
							$fms = Fms::where('_id', '=', $fms_id)->get()->toarray();
							$fms = $fms[0];
							$fms_table= $fms['fms_table'];
							
							$whereIn = array();
							$where = array(
											array(
												'fms_id', '=', $fms_id
											)
										);
										
							// pi date 
							$pi_from_dt =date('Y-m-d', strtotime($pi_from));
							
							$where[]=array(
								'pi_date','>=',$pi_from_dt
							);
						
							
							$pi_to_dt =date('Y-m-d', strtotime($pi_to."+ 1 day"));							
							$where[]=array(
								'pi_date','<',$pi_to_dt
							);
							
							
							// Buyer Name
							if(array_key_exists('customer_data', $queryArr) && $queryArr['customer_data']!=''){
								$where[]=array(
										'customer_data.c_value','=',trim($queryArr['customer_data'])
									);
							}
							
							// Brand Name
							if(array_key_exists('brand_data', $queryArr) && $queryArr['brand_data']!=''){
								$where[]=array(
										'brand_data.b_value','=',trim($queryArr['brand_data'])
									);
							}
							
							
							// remove cance
							$where[]=array(
										'5e8ef2c80f0e75228400661b.dropdown','!=','Cancelled' 
									);
									
							$where[]=array(
										'5e9a9ecb0f0e750494006a35.dropdownlist','!=','Cancelled' 
									);
							//pr($where); die;
							$all_data 	= DB::table($fms_table)->select('unit','bulkqty','etd','5e9a9ecb0f0e750494006a35','5e9946af0f0e75131c0037f5','5e9946b00f0e75131c0037f6');
							$all_data 	= $all_data->where($where)->get()->toArray();
							
							// 5e9946b00f0e75131c0037f6 == step#17 
							//pr($all_data); die;
							
							$or_closed_part=0;
							$or_pending=0;
							$or_pending_future=0;
							
							$total_qty=0;
							$order_closed=0;
							$dispatch_qty=0;
							$order_pending=0;
							$order_pending_future=0;
							
							$quantity_dispatched_in_time=0;
							
							$delay_15_days=0;
							$delay_more_15_days=0;
							
							//pr($all_data); die;
							$Mtr_to_be=0; $Kg_to_be=0; $Yd_to_be=0; $Pcs_to_be=0;
							$Mtr_dis=0; $Kg_dis=0; $Yd_dis=0; $Pcs_dis=0;
							$Mtr_in_time=0; $Kg_in_time=0; $Yd_in_time=0; $Pcs_in_time=0;
							$Mtr_15d_delay=0; $Kg_15d_delay=0; $Yd_15d_delay=0; $Pcs_15d_delay=0;
							$Mtr_more15d_delay=0; $Kg_more15d_delay=0; $Yd_more15d_delay=0; $Pcs_more15d_delay=0;
							///5e9946af0f0e75131c0037f5
							
							foreach($all_data as $row){
								
								/* for qty Quantity to be Dispatched during the period */
								if($row['5e9a9ecb0f0e750494006a35']['dropdownlist']!='Part Shipped'){
									if($row['unit']=='Mtr'){
										$Mtr_to_be +=$row['bulkqty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_to_be +=$row['bulkqty'];
									}elseif($row['unit']=='Yd'){
										$Yd_to_be +=$row['bulkqty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_to_be +=$row['bulkqty'];
									}
								}
								
								/* Quantity Dispatched during the period */
								if($row['5e9946af0f0e75131c0037f5']['act_qty']!='' && $row['5e9a9ecb0f0e750494006a35']['dropdownlist']=='Closed'){
									//$dispatch_qty= $dispatch_qty+$row['5e9946af0f0e75131c0037f5']['act_qty'];
									if($row['unit']=='Mtr'){
										$Mtr_dis +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
						
									}elseif($row['unit']=='Kg'){
										$Kg_dis +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
									}elseif($row['unit']=='Yd'){
										$Yd_dis +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
									}elseif($row['unit']=='Pcs'){
										$Pcs_dis +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
									}
									
									
									/* for  */
									$etd = date('Y-m-d', strtotime($row['etd']));
									$step17Act = date('Y-m-d', strtotime($row['5e9946b00f0e75131c0037f6']['actual']));
									if(strtotime($etd)>=strtotime($step17Act)){
										//$quantity_dispatched_in_time= $quantity_dispatched_in_time+$row['bulkqty'];
										if($row['unit']=='Mtr'){
											$Mtr_in_time +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}elseif($row['unit']=='Kg'){
											$Kg_in_time +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}elseif($row['unit']=='Yd'){
											$Yd_in_time +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}elseif($row['unit']=='Pcs'){
											$Pcs_in_time +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
										}
									
									}
									
									if($etd!='' && $step17Act!=''){
										$diffDay = getDateDiff($etd,$step17Act);
										if($diffDay>0 && $diffDay<=15){
											//$delay_15_days	=	$delay_15_days+$row['bulkqty'];
											if($row['unit']=='Mtr'){
												$Mtr_15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Kg'){
												$Kg_15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Yd'){
												$Yd_15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Pcs'){
												$Pcs_15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}
										}elseif($diffDay>15){
											//$delay_more_15_days	=	$delay_more_15_days+$row['bulkqty'];
											if($row['unit']=='Mtr'){
												$Mtr_more15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Kg'){
												$Kg_more15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Yd'){
												$Yd_more15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}elseif($row['unit']=='Pcs'){
												$Pcs_more15d_delay +=$row['5e9946af0f0e75131c0037f5']['act_qty'];
											}
										}
									}
									/* for  */
								}
								
							}
							
							
							$qty_to_be = array(
													'Mtr'=>$Mtr_to_be,
													'Kg'=>$Kg_to_be,
													'Yd'=>$Yd_to_be,
													'Pcs'=>$Pcs_to_be
												);
							$qty_dis = array(
													'Mtr'=>$Mtr_dis,
													'Kg'=>$Kg_dis,
													'Yd'=>$Yd_dis,
													'Pcs'=>$Pcs_dis
												);
												
							$qty_in_time = array(
													'Mtr'=>$Mtr_in_time,
													'Kg'=>$Kg_in_time,
													'Yd'=>$Yd_in_time,
													'Pcs'=>$Pcs_in_time
												);
							
							$qty_15d_delay = array(
													'Mtr'=>$Mtr_15d_delay,
													'Kg'=>$Kg_15d_delay,
													'Yd'=>$Yd_15d_delay,
													'Pcs'=>$Pcs_15d_delay
												);
							
							$qty_more15d_delay = array(
													'Mtr'=>$Mtr_more15d_delay,
													'Kg'=>$Kg_more15d_delay,
													'Yd'=>$Yd_more15d_delay,
													'Pcs'=>$Pcs_more15d_delay
												);
							/* pr($all_data);
							pr($qty_in_time);
							pr($qty_15d_delay);
							pr($qty_more15d_delay); */
							//pr($qty_to_be); 
							//die;
							//die('helper==');
							$order_closed = $or_closed_part;
							$order_pending = $or_pending;
							
							
							$res['fms_bulk'] = array(
											'qty_to_be'=>$qty_to_be,
											'qty_dis'=>$qty_dis,
											'qty_in_time'=>$qty_in_time,
											'qty_15d_delay'=>$qty_15d_delay,
											'qty_more15d_delay'=>$qty_more15d_delay,
											'order_closed'=>$order_closed,
											'dispatch_qty'=>$dispatch_qty,
											'order_pending'=>$order_pending,
											'total_qty'=>$total_qty,
											'quantity_dispatched_in_time'=>$quantity_dispatched_in_time,
											'delay_15_days'=>$delay_15_days,
											'delay_more_15_days'=>$delay_more_15_days,
										);
							//pr($res); die;
							return $res;
							
							
			}
			
			
		}
	}
	
	
	if(!function_exists('getDateDiff'))
	{
		function getDateDiff($pl, $ac){
			$pl = date('Y-m-d', strtotime($pl));
			$ac = date('Y-m-d', strtotime($ac));
			$start_date = strtotime($pl);
			$end_date = strtotime($ac);
			
			// if res -ev the before done
			return $dateDiff  = ($end_date - $start_date)/60/60/24;
		}
	}
	
	if(!function_exists('get_item_from_purchase_data'))
	{
		function get_item_from_purchase_data($type){	
				
				/* if($type=='item'){
					$data = DB::table('stock_purchase_data')
						->select('item_data.i_value')
						->where('item_data.i_value','!=', '')
						->get()
						->unique('item_data.i_value')
						->toArray();
				}elseif($type=='color'){
					$data = DB::table('stock_purchase_data')
							->select('color')
							->where('color','!=', '')
							->get()
							->unique('color')
							->toArray();
				}elseif($type=='po_no'){
					$data = DB::table('stock_purchase_data')
							->select('po_no')
							->where('po_no','!=', '')
							->get()
							->unique('po_no')
							->toArray();
				}elseif($type=='lot_no'){
					$data = DB::table('stock_purchase_data')
							->select('lot_no')
							->where('lot_no','!=', '')
							->get()
							->unique('lot_no')
							->toArray();
				} */
				
				if($type=='item'){
					$data = DB::table('dss_data')
						->select('item_data.i_value')
						->where('item_data.i_value','!=', '')
						->get()
						->unique('item_data.i_value')
						->toArray();
				}elseif($type=='color'){
					$data = DB::table('dss_data')
							->select('color')
							->where('color','!=', '')
							->get()
							->unique('color')
							->toArray();
				}elseif($type=='po_no'){
					$data = DB::table('dss_data')
							->select('po_no')
							->where('po_no','!=', '')
							->get()
							->unique('po_no')
							->toArray();
				}elseif($type=='lot_no'){
					$data = DB::table('dss_data')
							->select('lot_no')
							->where('lot_no','!=', '')
							->get()
							->unique('lot_no')
							->toArray();
				}
				return $data;
		}
	}
	
	
	if(!function_exists('checkDuplicateRIC'))
	{
		function checkDuplicateRIC($RIC){
				
				$data = DB::table('stock_purchase_data')->where('RIC', $RIC)->get()->first();
				$data2 = DB::table('stock_purchase_data_draft')->where('RIC', $RIC)->get()->toArray();
				$data2_count =  count($data2);
				//echo $data2_count;
				//pr($data);
				//pr($data2); die;
				
				if(!empty($data) || $data2_count>1){
					return true;
				}else{
					return false;
				}
		}
	}
	
	if(!function_exists('checkDuplicateSalesRIC'))
	{
		function checkDuplicateSalesRIC($RIC){
				
				$data = DB::table('stock_sales_data')->where('RIC', $RIC)->get()->first();
				$data2 = DB::table('stock_sales_data_draft')->where('RIC', $RIC)->get()->toArray();
				$data2_count =  count($data2);
				//echo $data2_count;
				//pr($data);
				//pr($data2); die;
				
				if(!empty($data) || $data2_count>1){
					return true;
				}else{
					return false;
				}
		}
	}
	
	if(!function_exists('getPurchseDataByRic'))
	{
		function getPurchseDataByRic($RIC){
				$data = DB::table('dss_data')->where('RIC', $RIC)->get()->first();
				return $data;
		}
	}
	
	/************ HV Project Function Start ******************/
	
	if(!function_exists('getLayout'))
	{
		function getLayout(){
				$data = array('1'=>'Col 1','2'=>'Col 2','3'=>'Col 3','4'=>'Col 4');
				return $data;
		}
	}
	
	if(!function_exists('getModuleName'))
	{
		function getModuleName(){
				$data = array('users'=>'Users','item'=>'Item','buyer'=>'Buyer','seller'=>'Seller');
				return $data;
		}
	}
	
	if(!function_exists('getFieldName'))
	{
		function getFieldName(){
				$data = array('1'=>'Text','2'=>'Password','3'=>'Email','4'=>'Number','5'=>'Textarea','6'=>'Radio','7'=>'Checkbox','8'=>'Multi Checkbox','9'=>'Select','10'=>'Multi Select','11'=>'Image','12'=>'File','13'=>'Button','14'=>'Submit','15'=>'Text Message','16'=>'Date','17'=>'Color','17'=>'Other');
				/* $data = array(
							 '0'=>array('label' => 'Basic Text',
										'option' => array('text'=>'Text','password'=>'Password','email'=>'Email','number'=>'Number','url'=>'URL','hidden'=>'Hidden')
								  ),
							 '1'=>array('label' => 'Textarea',
										'option' => array('textarea'=>'Textarea','editor'=>'Editor')
								  ),
							 '2'=>array('label' => 'Choice',
										'option' => array('select'=>'Select','multiselect'=>'Multi Select','checkbox'=>'Checkbox','radio'=>'Radio Button','url'=>'URL','hidden'=>'Hidden')
								  ),	  
							 '3'=>array('label' => 'Upload',
										'option' => array('file'=>'File')
								  ),
							 '4'=>array('label' => 'jQuery',
										'option' => array('date'=>'Date Picker','color'=>'Color Picker')
								  ),
							 '5'=>array('label' => 'Content',
										'option' => array('content'=>'Content/Message')
								  ),
							 '6'=>array('label' => 'Dynamic Field',
										'option' => array('function'=>'Function/Dynamic')
								  )
							 ); */
				return $data;
		}
	}
	if(!function_exists('field_type_array'))
	{
		function field_type_array(){
				$data = array(
							 '0'=>array('label' => 'Basic Text',
										'option' => array('text'=>'Text','password'=>'Password','email'=>'Email','mobile'=>'Mobile','number'=>'Number','percentage'=>'Percentage','url'=>'URL','hidden'=>'Hidden','submit'=>'Submit','button'=>'Button','reset'=>'Reset','repeat'=>'Repeat','hr'=>'Hr','div'=>'Div')
								  ),
							 '1'=>array('label' => 'Textarea',
										'option' => array('textarea'=>'Textarea')
								  ),
							 '2'=>array('label' => 'Choice',
										'option' => array('dropdown'=>'Dropdown','multiselect'=>'Multi Select','checkbox'=>'Checkbox','radio'=>'Radio Button')
								  ),	  
							 '3'=>array('label' => 'Upload',
										'option' => array('file'=>'File')
								  ),
							 '4'=>array('label' => 'jQuery',
										'option' => array('date'=>'Date Picker','color'=>'Color Picker')
								  ),
							 '5'=>array('label' => 'Content',
										'option' => array('content'=>'Content/Message')
								  ),
							 '6'=>array('label' => 'Dynamic Field',
										'option' => array('function'=>'Function/Dynamic')
								  )
							 ); 
				return $data;
		}
	}
	if(!function_exists('field_type_dropdown'))
	{
		function field_type_dropdown($name='row[0][field_type]',$row_val='',$selected=''){
				$data = field_type_array();
				$html = '<select class="form-control field_type required" cus="'.$row_val.'" id="field_type" name="'.$name.'" data-placeholder="Select Field">';
				$html .= '<option value="">-- Select --</option>';
				foreach($data as $key=>$module_val){
					$html .= '<optgroup label="'.$module_val['label'].'">';
					
					foreach($module_val['option'] as $row=>$val){
					    if($row==$selected){
					        $sel = 'selected';
					    }else if($row=='text'){
					        $sel = 'selected';
					    }else{
					        $sel='';
					    }
						$html .= '<option '.$sel.' value="'.$row.'">'.$val.'</option>';
					
					}
				}	
				$html .= '</select>';
				return $html;
		}
	}
	
	if(!function_exists('getConditional'))
	{
		function getConditional($module_name='',$name='',$row_val='',$selected=''){
				$data = DB::table('tbl_form')->select('*')->where('module_name', $module_name)->get();
				$html = '<select class="form-control con" cus="'.$row_val.'" id="conditional" name="'.$name.'" data-placeholder="Select Field">';
				$html .= '<option value="">-- Select --</option>';
				foreach($data[0]['row'] as $key=>$module_val)
				{
				if($module_val['field_type']=='checkbox' || $module_val['field_type']=='dropdown' || $module_val['field_type']=='multiselect' || $module_val['field_type']=='radio'){
                if($module_val['field_name']==$selected){
                $sel = 'selected';
                }else{
                $sel='';
                }
				$html .= '<option '.$sel.' value="'.$module_val['field_name'].'">'.$module_val['field_name'].'</option>'; 
				} 
				}
				$html .= '</select>';
				return $html;
		}
	}
	
	if(!function_exists('getSubConditional'))
	{
		function getSubConditional($module_name='',$conditional='',$row_val='',$value=''){
				$data = DB::table('tbl_form')->select('*')->where('module_name',$module_name)->get();
                $form_data = array();
                //pr($data); die;
                foreach($data[0]['row'] as $key=>$d)
                {
                if($d['field_name']==$conditional){
                array_push($form_data,$d);
                }
                //pr($d);
                }
        
                $html = '<select class="form-control sub_cond-'.$row_val.'" id="'.$row_val.'" name="row['.$row_val.'][sub_cond]"><option value="">--Select--</option>';
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

// if(!function_exists('getConditional'))
// 	{
// 		function getConditional($module_name='',$name='',$row_val='',$value=''){
// 				$data = DB::table('tbl_form')->select('row')->where('module_name', $module_name)->get();
// 				$html = '<select class="form-control con" cus="'.$row_val.'" id="conditional" name="'.$name.'" data-placeholder="Select Field">';
// 				$html .= '<option value="">-- Select --</option>';
// 				foreach($data[0]['row'] as $key=>$module_val)
// 				{
// 				if(empty($module_val['is_disable'])){
// 				    if(!empty($module_val['field_type_value'])){
// 				       	$optionVal = explode("\r\n", $module_val['field_type_value']);
// 				//pr($optionVal);
				
// 				if(!empty($optionVal)){
// 					foreach($optionVal as $val){
// 						$option = explode(":", $val);
// 						$selected = (!empty($value) && $value == $option[0])? 'selected=""':'';
// 						$html .= '<option value="'.$option[0].'" '.$selected.'>'.$option[1].'</option>';
// 					}					
// 				} 
// 				    }
// 				}
// 				}
// 				$html .= '</select>';
// 				return $html;
// 		}
// 	}
	
	if(!function_exists('postInsertData'))
	{
		function postInsertData($data,$table){
		    if($data['multiinsert']=='multiinsert')
            {
            foreach($data as $key=>$val){
            if(is_array($val)){
            foreach($val as $rows){
            DB::table($table)->insert(array($rows));
            } 
            }
            
            }
		    }else if($data['customer']=='customer'){
		    unset($data['_token'],$data['submit'],$data['table_name'],$data['reset']);
		    DB::table($table)->insert(array($data));  
		    }else if($table=='items'){
				$form_array = array(
					'active_deactive'	=> $data['active_deactive'],
					'vendor_sku'		=> $data['vendor_sku'],
					'name'				=> $data['name'],
					'qr_code'			=> $data['qr_code'],
					'synonyms'			=> $data['synonyms'],
					'hsn_code'			=> $data['hsn_code'],
					'tax_rate'			=> $data['tax_rate'],
					'type'				=> $data['type'],
					'sub_type'			=> $data['sub_type'],
					'grade'				=> $data['grade'],
					'brand'				=> $data['brand'],
					'description'		=> $data['description'],
					'coa_applicable'	=> $data['coa_applicable'],
					'unit'				=> $data['unit'],
					'pack_size'			=> $data['pack_size'],
					'packing_name'		=> $data['packing_name'],
					'list_price'		=> $data['list_price'],
					'unit_price'		=> $data['unit_price'],
					'mrp'				=> $data['mrp'],
					'net_rate'			=> $data['net_rate'],
					'stock'				=> $data['stock'],
					'specific_gravity'	=> $data['specific_gravity'],
					'minimum_order_pack'=> $data['minimum_order_pack'],
					'storage_conditions'=> $data['storage_conditions'],
					'safety_sheet'		=> $data['safety_sheet'],
					'eud'				=> $data['eud'],
					'shelf_life'		=> $data['shelf_life'],
					'is_verified'		=> $data['is_verified'],
					
				);
				DB::table($table)->insert(array($form_array));
			}else{
		    if($data['password']!=''){
		    $arr_action = array('password'=>Hash::make($data['password']),'ori_password'=>$data['password']);
		    }else{
		     $arr_action = array('password'=>'','ori_password'=>'');   
		    }
		    unset($data['_token'],$data['submit'],$data['table_name'],$data['reset'],$data['password']);
			$merge = array_merge($data, $arr_action);
			DB::table($table)->insert(array($merge));
		}
		}
	}

	function custom_filter($array) { 
		$temp = [];
	  array_walk($array, function($item,$key) use (&$temp){
		  foreach($item as $key=>$value)
			 $temp[$key] = $value;
	  });
	  return $temp;
	} 
	
	if(!function_exists('postUpdateData'))
	{
		function postUpdateData($data,$table,$id){
			//pr($data); die;
		    if($data['multiinsert']=='multiinsert')
		    {
		      
		     foreach($data as $key=>$val){
            if(is_array($val)){
            foreach($val as $rows){
            //pr($rows);
            DB::table($table)->where('id',$id)->update($rows);
            } 
            }
            
            }
		     
		    }else if($data['customer']=='customer'){
		    unset($data['_token'],$data['submit'],$data['table_name'],$data['reset']);
		    DB::table($table)->where('_id',$id)->update($data);  
		    }else{
		    if($data['password']!=''){
		    $arr_action = array('password'=>Hash::make($data['password']),'ori_password'=>$data['password']);
		    }else{
		     $arr_action = array('password'=>'','ori_password'=>'');   
		    }
			if($table=='users'){
				if(!empty($data['location'])){
				$data['location'] =implode(',',$data['location']);
				}
				if(!empty($data['customers'])){
				$data['customers'] =implode(',',$data['customers']);
				}
			}
		    unset($data['_token'],$data['submit'],$data['table_name'],$data['reset'],$data['password'],$data['updated_on'],$data['customer'],$data['created_on']);
			$merge = array_merge($data, $arr_action);
			
			if($table=='items'){
				unset($merge['password'],$merge['ori_password']);
			}
			DB::table($table)->where('id',$id)->update($merge);
		    }
		}
	}
	
	if(!function_exists('getModuleList'))
	{
		function getModuleList($id){
			$object = DB::table('tbl_form_data')->where('f_id',$id)->get()->toArray();
			$data = json_decode(json_encode($object), true);
			$form_data = array();
			$k = array();
            //pr($data); die;
			foreach($data as $key=>$d)
			{
			
			if(!empty($d['show_in_list']) && ($d['field_type']!='repeat')){
			if($d['label_name']!=''){
			$k['label_name'] = $d['label_name'];
			}else{
			$k['label_name'] = ucwords(str_replace('_',' ',$d['field_name']));
			}
			if($d['field_name']=='desig_multiple')
			{
			$k['field_name'] = 'user_role_erp';   
			}else{
			$k['field_name'] = $d['field_name'];
			$k['show_list'] = $d['show_in_list'];
			}
			array_push($form_data,$k);
			}else{
			if(!empty($d['repeat'])){
			 foreach($d['repeat'] as $dd)
			 {
			  if(!empty($dd['show_list'])){
                    if($dd['label_name']!=''){
                    $k['label_name'] = $dd['label_name'];
                    }else{
                    $k['label_name'] = ucwords(str_replace('_',' ',$dd['field_name']));
                    }
                    $k['field_name'] = $dd['field_name'];
                    $k['show_list'] = $dd['show_in_list'];
			        array_push($form_data,$k);
			  }  
			 }
			}
			}
			}
			//die;
			//pr($form_data); die;
			$arr_action = array(array('label_name'=>'Action','field_name'=>'action','show_list'=>1));
			$merge = array_merge($form_data, $arr_action);
			//pr($merge); die;
			return $merge;
		}
	}
	
	if(!function_exists('getValidationList'))
	{
		function getValidationList($module){
			$object = DB::table('tbl_form')->select('*')->where('module_name',$module)->first();
			$data = json_decode(json_encode($object), true);
			$object2 = DB::table('tbl_form')->where('id',$data['id'])->get();
            $adddata = json_decode(json_encode($object2), true);
			$form_data = array();
			$v = array();
            //pr($data); die;
			foreach($adddata as $key=>$d)
			{
			if(!empty($d['is_required'])){
			$v['field_name']        = $d['field_name'];
			$v['max_length']   = $d['max_length'];
			$v['required_message']  = $d['required_message'];
			$v['field_type']        = $d['field_type'];
			$v['conditional']       = $d['conditional'];
			$v['sub_cond']          = $d['sub_cond'];
			$v['restrictions']      = $d['restrictions'];
			$v['min_length']        = $d['min_length'];
			$v['is_unique']         = $d['is_unique'];
			array_push($form_data,$v);
			}
			}
			
			return $form_data;
		}
	}
	
	if(!function_exists('getConditionalList'))
	{
		function getConditionalList($module){
			$data = DB::table('tbl_form')->select('*')->where('module_name',$module)->get();
			$object = DB::table('tbl_form')->select('*')->where('module_name',$module)->first();
			$data = json_decode(json_encode($object), true);
			$object2 = DB::table('tbl_form')->where('id',$data['id'])->get();
            $adddata = json_decode(json_encode($object2), true);
			
			$form_data = array();
			$v = array();
            //pr($data); die;
			foreach($adddata as $key=>$d)
			{
			$v['field_name']        = $d['field_name'];
			$v['field_type']        = $d['field_type'];
			$v['conditional']        = $d['conditional'];
			$v['sub_cond']        = $d['sub_cond'];
			array_push($form_data,$v);
			}
			
			return $form_data;
		}
	}
	
if(!function_exists('renderHr'))
{
	function renderHr($data,$edit_data = '',$repeat=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			
			$html = '<div class="form-box '.$class.'">';
			$html .= '<hr>';
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderDiv'))
{
	function renderDiv($data,$edit_data = '',$repeat=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			
			$html = '<div class="form-box '.$class.'">';

			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderTextRepFirst'))
{
	function renderTextRepFirst($data,$key='',$edit_data = '', $field_name='',$master=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$is_array = (!empty($data['is_array']) && empty($data['repeat']))? '[]':'';
			$is_unique = (!empty($data['is_unique']))? '1':'';
			$is_readonly = (!empty($data['is_readonly']))? 'readonly':'';
			
            $rep = $field_name.'['.$key.']['.$data['field_name'].']';
            
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			if($data['field_type']=='percentage'){
			    $max = 'max=100';
			    $min = 'min=0';
			    $type = 'number';
			}else if($data['field_type']=='number'){
			    $max = '';
			    $min = 'min=0';
			    $type =$data['field_type'];
			    }else{
			    $max = '';
			    $min = '';
			    $type =$data['field_type'];
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			if($master==0){
			if(empty($edit_data[$field_name][$key][$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$field_name][$key][$data['field_name']];
			}
			}else{
			 if(empty($edit_data[$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$data['field_name']];
			}   
			}
			
			if($is_unique==1){
			    //$d = $data['field_name'];
			$html .= '<input onblur="return uniqueValueCheck(this);" autocomplete="off" '.$max.' '.$min.' type="'.$type.'" cus="'.$data['field_name'].'" name="'.$rep.'" class="'.$data['field_name'].'-['.$key.'] form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';    
			}else{
			$html .= '<input autocomplete="off" '.$max.' '.$min.' '.$is_readonly.' type="'.$type.'" name="'.$rep.'" class="'.$data['field_name'].'-['.$key.'] form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
			}
	        if(!empty($data['field_instructions']) && $data['html_id']=='search'){
	         $html .= '<a data-toggle="modal" data-target="#myModal-search" class="form-note search-pop-up" cus="'.$rep.'" href="javascript:void(0);">'.$data['field_instructions'].'</a>';   
	        }
			else if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderCheckboxRepFirst'))
{
  function renderCheckboxRepFirst($data,$key='',$edit_data = '', $field_name='',$master=''){
  
       if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			$html = '<div class="form-box col'.$layout.'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
            $rep = $field_name.'['.$key.']['.$data['field_name'].']';

			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				
				if(!empty($optionVal)){
					$html .= '<div class="inline-radio-box inline-radio">';
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($edit_data[$data['field_name']]) && $edit_data[$data['field_name']] == $option[0])? 'checked':'';
						$html .= '<label><input type="checkbox" cus="['.$key.']" value="'.$option[0].'" class=" '.$data['field_name'].' '.$class.'" id="'.$id.'" '.$selected.' '.$required.' name="'.$rep.'">'.$option[1].'</label>';
					}
					$html .= '</div>';	
				}
			}
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}
  }
}

if(!function_exists('renderHiddenRepFirst'))
{
	function renderHiddenRepFirst($data,$key='',$edit_data = '', $field_name='',$master=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$is_array = (!empty($data['is_array']) && empty($data['repeat']))? '[]':'';
			$is_unique = (!empty($data['is_unique']))? '1':'';
			
            $rep = $field_name.'['.$key.']['.$data['field_name'].']';
            
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			if($data['field_type']=='percentage'){
			    $max = 'max=100';
			    $min = 'min=0';
			    $type = 'number';
			}else if($data['field_type']=='number'){
			    $max = '';
			    $min = 'min=0';
			    $type =$data['field_type'];
			    }else{
			    $max = '';
			    $min = '';
			    $type =$data['field_type'];
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			if($master==0){
			if(empty($edit_data[$field_name][$key][$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$field_name][$key][$data['field_name']];
			}
			}else{
			 if(empty($edit_data[$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$data['field_name']];
			}   
			}
			
			if($is_unique==1){
			    //$d = $data['field_name'];
			$html .= '<input onblur="return uniqueValueCheck(this);" autocomplete="off" '.$max.' '.$min.' type="'.$type.'" cus="'.$data['field_name'].'" name="'.$rep.'" class="'.$data['field_name'].'-['.$key.'] form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';    
			}else{
			$html .= '<input autocomplete="off" '.$max.' '.$min.' type="'.$type.'" name="'.$rep.'" class="'.$data['field_name'].'-['.$key.'] form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
			}
	        if(!empty($data['field_instructions']) && $data['html_id']=='search'){
	         $html .= '<a data-toggle="modal" data-target="#myModal-search" class="form-note search-pop-up" cus="'.$rep.'" href="javascript:void(0);">'.$data['field_instructions'].'</a>';   
	        }
			else if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderTextRepSecond'))
{
	function renderTextRepSecond($data,$key='',$rey='',$edit_data = '', $field_name_first='',$field_name_second=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$is_array = (!empty($data['is_array']) && empty($data['repeat']))? '[]':'';
			
            $rep = $field_name_first.'['.$key.']['.$field_name_second.']['.$rey.']['.$data['field_name'].']';
            
            
            
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			if($data['field_type']=='percentage'){
			    $max = 'max=100';
			    $min = 'min=0';
			    $type = 'number';
			}else if($data['field_type']=='number'){
			    $max = '';
			    $min = 'min=0';
			    $type =$data['field_type'];
			    }else{
			    $max = '';
			    $min = '';
			    $type =$data['field_type'];
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			//echo $edit_data[$data['field_name']]; die;
			if(empty($edit_data[$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$data['field_name']];
			}
			
			$html .= '<input autocomplete="off" '.$max.' '.$min.' type="'.$type.'" name="'.$rep.'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderDropdownRepFirst'))
{
	function renderDropdownRepFirst($data, $key='', $edit_data='', $field_name='',$master=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			
            $rep = $field_name.'['.$key.']['.$data['field_name'].']';
            
			$html .= '<select cus="['.$key.']" cus_name="'.$data['field_name'].'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" name="'.$rep.'"  '.$required.'>
			<option value="">--Select--</option>';
			
		
			
			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				if($master==0){
					if(empty($edit_data[$field_name][$key][$data['field_name']]))
			    {
			      $value = '';
			    }else{
			      $value = $edit_data[$field_name][$key][$data['field_name']];
			    }
				}else{
				   	if(empty($edit_data[$data['field_name']]))
			    {
			      $value = '';
			    }else{
			      $value = $edit_data[$data['field_name']];
			    } 
				}
				
			//echo $edit_data[$field_name][$key][$data['field_name']];
				
				if(!empty($optionVal)){
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($value) && $value == $option[0])? 'selected=""':'';
						$html .= '<option value="'.$option[0].'" '.$selected.'>'.$option[1].'</option>';
					}					
				}
			}
			$html .= '</select>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
	
	if(!function_exists('renderText'))
{
	function renderText($data,$edit_data = '',$repeat='',$repeat_field='',$i=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$is_array = (!empty($data['is_array']) && empty($data['repeat']))? '[]':'';
			$is_unique = (!empty($data['is_unique']))? '1':'';
			//$read_only = (!empty($data['is_readonly']))? 'readonly':'';
			if(!empty($edit_data) && $data['is_readonly']==1){$read_only = 'readonly';}else{$read_only = '';}
			
			if(!empty($repeat) && $repeat==1){
            $rep = 'row['.$repeat_field.']['.$i.']'.'['.$data['field_name'].']';
            }else if(!empty($repeat) && $repeat==2){
            $rep = 'row['.$repeat_field.']'.'[departments]['.$data['field_name'].']';
            }
            else if(empty($repeat) && !empty($data['is_array']))
            {
            $rep = $data['field_name'].'[]';   
            }else{
            $rep = $data['field_name'];
            }
			
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			if($data['field_type']=='percentage'){
			    $max = 'max=100';
			    $min = 'min=0';
			    $type = 'number';
			}else if($data['field_type']=='number'){
			    $max = '';
			    $min = 'min=0';
			    $type =$data['field_type'];
			    }else{
			    $max = '';
			    $min = '';
			    $type =$data['field_type'];
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			if(empty($edit_data[$data['field_name']]))
			{
			    $value='';
			}else{
			    $value = $edit_data[$data['field_name']];
			}
			if($is_unique==1){
			$html .= '<input onblur="return uniqueValueCheck(this);" autocomplete="off" '.$max.' '.$min.' type="'.$type.'" cus="'.$data['field_name'].'" name="'.$rep.'" '.$read_only.' class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
			}else{
			$html .= '<input autocomplete="off" '.$max.' '.$min.' type="'.$type.'" name="'.$rep.'" '.$read_only.' class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
			}
	        if(!empty($data['field_instructions']) && $data['html_id']=='link')
            {
               $html .='<a class="form-note" target="_blank" href="'.$data['field_instructions'].'">Google Map</a>'; 
            }else if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}


if(!function_exists('renderHidden'))
{
	function renderHidden($data,$edit_data = ''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$is_array = (!empty($data['is_array']) && empty($data['repeat']))? '[]':'';
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			
			$html = '';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			if(empty($edit_data[$data['field_name']]))
			{
			    if(empty($edit_data[$data['field_name']]) && $data['field_name']=='updated_on'){
			    $value= date('Y-m-d H:i:s');
			    }else{
			    $value='';    
			    }
			    
			    if(empty($edit_data[$data['field_name']]) && $data['field_name']=='multiinsert'){
			    $value= 'multiinsert';
			    }else{
			    $value='';    
			    }
			    
			}else{
			    $value = $edit_data[$data['field_name']];
			}
			
			$html .= '<input autocomplete="off" type="'.$data['field_type'].'" name="'.$data['field_name'].'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="'.$value.'" '.$max_length.' '.$min_length.' '.$required.'>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			
			
			return $html;
		}	
	}
}

if(!function_exists('ArrayrenderTextRender'))
{
    function ArrayrenderTextRender($data,$key='',$rey='',$edit_data = '',$repeat_field='',$i=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$html = '<div class="form-box rows line_1">';
			if($rey!=0 && ($key!=$rey)){
			$html .='<div class="form-box more-rep-div-add rep-div-add" id="rep-'.$rey.'"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="'.$rey.'" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>';
			//$html .= '<hr/>';
			    
			}
			
			 if(!empty($data['repeat_new'])){
				foreach($data['repeat_new'] as $row){
					switch ($row['field_type']) {
					  case "text":
						 $html .= renderTextRepSecond($row,$key,$rey,$edit_data,$repeat_field,$data['field_name']);
						break;
						case "number":
						 $html .= renderTextRepSecond($row,$key,$rey,$edit_data,$repeat_field,$data['field_name']);
						break;
						case "function":
						 $html .= renderCustomFunctionSecond($row,$key,$rey,$edit_data,$repeat_field,$data['field_name']);
						break;
					  
					}
				}
				if($i==1){
				$javascript_new = '<script>$(function(){
           var j=100;
           $(document).on("click",".'.$data['field_name'].'",function() {
               j++;';		
				   $script_new = '<div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>';
				foreach($data['repeat_new'] as $row){
					switch ($row['field_type']) {
					  case "text":
						 $script_new .= renderTextRepSecond($row,$key,$rey,'',$repeat_field,$data['field_name']);
						break;	
						case "number":
						 $script_new .= renderTextRepSecond($row,$key,$rey,'',$repeat_field,$data['field_name']);
						break;
						case "function":
						 $script_new .= renderCustomFunctionSecond($row,$key,$rey,'',$repeat_field,$data['field_name']);
						break;
					}
				}
				$script_new2 .= '<div class="form-box more-rep-div-add rep-div-add" id="rep-__id__">'.minify_html($script_new).'</div>';
				$javascript_new .= "var ".$data['field_name']."='".$script_new2."';";
				$javascript_new .= 'var last_data_new = '.$data['field_name'].'.replaceAll("##", j).replaceAll("__D__",j).replaceAll("__id__",j).replaceAll("[0]","["+j+"]");
              var data = $(this).closest("div.line_repeat_1").find("input").attr("name");
              var result1=data.split("]["); var result2 = result1[0].split("[");
              var data_new_jquery = last_data_new.replaceAll(result2[0]+"["+j+"]",result2[0]+"["+result2[1]+"]");
              $(this).before(data_new_jquery);
           });
        });</script>';
        
				}else{
				$javascript_new = '';   
				}
			
			$html .= '</div>';
			if($rey!=0 && ($key!=$rey)){
			$html .='</div>';
			}
			
			$html .= '<div class="form-box add-more-add '.$data["field_name"].'" cus="0" style="float:right; margin-top:10px;"><a href="javascript:void(0);">+</a>'.$javascript_new.'</div>';
			
		}
			return $html;
		}	
	}
}

if(!function_exists('ArrayrenderText'))
{
	function ArrayrenderText($data,$key='',$edit_data = '',$val='',$field='',$count='',$t='',$master=''){
		//pr($data); die;
		//echo $master.'asdasd';
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			if($key==0){
			    $css = '';
			}else{
			    $css = 'rep-div-add-repeat2';
			}
			$html = '<div class="form-box rows '.$css.' line_repeat_1" id="rep-'.$key.'">';
			if($key!=0){
			   $html .= '<div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="'.$key.'" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>';
			}
			 if(!empty($data['repeat'])){
				foreach($data['repeat'] as $row){
					switch ($row['field_type']) {
					    case "text":
						 $html .= renderTextRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
					    case "number":
						 $html .= renderTextRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "percentage":
						 $html .= renderTextRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "dropdown":
						 $html .= renderDropdownRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "textarea":
						 $html .= renderTextareaRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "hidden":
						 $html .= renderHiddenRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "checkbox":
						 $html .= renderCheckboxRepFirst($row,$key,$edit_data,$data['field_name'],$master='');
						break;
						case "function":
						    if(!empty($val)){
						        $d = $val;
						    }else{
						        $d = $edit_data;
						    }
						 $html .= renderCustomFunctionRepFirst($row,$key,$d,$data['field_name'],$master='');
						 //pr($edit_data);
						break;
						case "repeat":
						    //pr($edit_data[$row['field_name']]); //die;
						    //echo $row['field_name'];die;
						    
							if(!empty($edit_data[$data['field_name']][$key][$row['field_name']])){
								$count1 = count($edit_data[$data['field_name']][$key][$row['field_name']]);
								}else{
								$count1 = 0;
								}
								//echo $count1;
						    //pr($count1); 
						    if($count1==0 && !empty($edit_data)){
						     $html .= ArrayrenderTextRender($row,0,0,$edit_data,$data['field_name'],1);   
						    }else if($count1==1 && empty($edit_data)){
						     $html .= ArrayrenderTextRender($row,0,0,$edit_data,$data['field_name'],1);   
						    }else{
						        $k==1;
								//pr($edit_data); die;
								if(!empty($edit_data[$data['field_name']][$key][$row['field_name']])){
						        foreach($edit_data[$data['field_name']][$key][$row['field_name']] as $rey=>$val){
						        if($k==1){$t=1;}else{$t=0;}
						        $html .= ArrayrenderTextRender($row,$key,$rey,$val,$data['field_name'],$t);
						        $k++;}
								}else{
									$html .= ArrayrenderTextRender($row,0,0,'',$data['field_name'],1);  	
								}
						    }
					    break;
					}
				}
				$javascript = '<script>$(function(){
           var j=$(".line_repeat_1").length;
           $(document).on("click","#'.$data['field_name'].'",function() {
               j++;';
				
				$script = '<div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>';
				foreach($data['repeat'] as $row){
					switch ($row['field_type']) {
					  	case "text":
						 $script .= renderTextRepFirst($row,0,'',$data['field_name'],'');
						break;	
					    case "number":
						 $script .= renderTextRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "percentage":
						 $script .= renderTextRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "dropdown":
						 $script .= renderDropdownRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "textarea":
						 $script .= renderTextareaRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "hidden":
						 $script .= renderHiddenRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "checkbox":
						 $script .= renderCheckboxRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "function":
						 $script .= renderCustomFunctionRepFirst($row,0,'',$data['field_name'],'');
						break;
						case "repeat":
					    $script .= ArrayrenderTextRender($row,'0','0','',$data['field_name'],0);
					    break;
					  
					}
				}
				$script2 .= '<div class="form-box rep-div-add line_repeat_1" id="rep-__id__">'.minify_html($script).'</div>';
				$javascript .= "var ".$data['field_name']." ='".$script2."'";
				$javascript .= ";";
				$javascript .= 'var last_data = '.$data['field_name'].'.replaceAll("##", j).replaceAll("__D__",j).replaceAll("__id__",j).replaceAll("[0]","["+j+"]");
              $(this).parent().before(last_data);
           });
        });</script>';
				
				
			}
			$html .= '</div>';
			//if($key!=0){
				//echo $count.'-'.$t.'-'.$master;
			if($count>=$t){
			    if(empty($edit_data)){
			$html .= '<div class="form-box add-more" style="float:right; margin-top:10px;"><a id="'.$data['field_name'].'" href="javascript:void(0);">+ Add More</a>'.$javascript .'</div>';
			
			    }
			    if(!empty($edit_data)){
			//$html .= '<div class="form-box add-more" style="float:right; margin-top:10px;"><a id="'.$data['field_name'].'" href="javascript:void(0);">+ Add More</a>'.$javascript .'</div>';
			
			    }
			    }
			//}else if($k==0 && empty($edit_data)){
			//$html .= '<div class="form-box add-more" style="float:right; margin-top:10px;"><a id="'.$data['field_name'].'" href="javascript:void(0);">+ '.$count.'-'.$t.'</a>'.$javascript .'</div>';    
			//}
			
			return $html;
		}	
	}
}

if(!function_exists('renderTextPassword'))
{
	function renderTextPassword($data,$edit_data = ''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			
			$id = (!empty($data['html_id']))? $data['html_id']:'password';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			$maxlength = (!empty($data['max_length']))? $data['max_length']:'';
			$minlength = (!empty($data['min_length']))? $data['min_length']:'';
			$error = $max_length = '';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if($maxlength!=''){
			 $max_length = 'maxlength="'.$data['max_length'].'"';
			}
			if($minlength!=''){
			 $min_length = 'minlength="'.$data['min_length'].'"';
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			if(empty($edit_data['ori_password']))
			{
			    $value='';
			}else{
			    $value = $edit_data['ori_password'];
			}
			$html .= '<div class="form-box-toggle"><input autocomplete="off" type="password" name="password" class="form-control password error" id="password" placeholder="Password" value="'.$value.'" maxlength="25" required="" aria-required="true" aria-invalid="true"><i class="far fa-eye" id="togglePassword"></i></div><label id="password-error" class="error" for="password" style="display: none;"></label>';
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderSubmit'))
{
	function renderSubmit($data){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			
			$html = '<div class="form-box col'.$layout.'">';
						
			$html .= '<input type="'.$data['field_type'].'" name="'.$data['field_name'].'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" value="'.$data['label_name'].'">';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderFile'))
{
	function renderFile($data,$edit_data=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			if(empty($edit_data[$data['field_name']])){
			 $required=$required;
			}else{
			 $required = '';
			}
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}

			if($data['field_name']=='eud_file'){
				$file = "application/pdf";
			}else{
				$file = "image/*";
			}
			
			$html .= '<input type="file" accept="'.$file.'" name="'.$data['field_name'].'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" value="" '.$required.'>';
            if(!empty($edit_data[$data['field_name']])){
            $html .= '<div class="form-box delete-img" id="'.$data['field_name'].'">
            <div class="remove_row"><a href="javascript:void(0)" title="Delete Image" field_name="'.$data['field_name'].'" cus="'.$edit_data['id'].'" class="delete-image"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>
            <a target="_blank" href="'.url('public/uploads/images/'.$edit_data[$data['field_name']]).'">
			Upload File
			</a></div>';
            }
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderTextarea'))
{
	function renderTextarea($data, $edit_data='',$repeat=''){
		//pr($edit_data); die;
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
            if(!empty($repeat)){
            $rep = 'row['.$repeat.']'.'['.$data['field_name'].']';
            }
            else if(empty($repeat) && !empty($data['is_array']))
            {
            $rep = $data['field_name'].'[]';   
            }else{
            $rep = $data['field_name'];
            }
			
			$html = '<div class="form-box col'.$layout.'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			if(!empty($edit_data[$data['field_name']])){
				$text_data = $edit_data[$data['field_name']];
			}else{
				$text_data = '';
			}

			$html .= '<textarea name="'.$rep.'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$required.'>'.$text_data.'</textarea>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderTextareaRepFirst'))
{
	function renderTextareaRepFirst($data,$key='',$edit_data = '', $field_name='',$master=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
            $is_unique = (!empty($data['is_unique']))? '1':'';
            $rep = $field_name.'['.$key.']['.$data['field_name'].']';
			
			$html = '<div class="form-box col'.$layout.'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
			if($master==0){
			    $value = $edit_data[$field_name][$key][$data['field_name']];
			}else{
				//echo  $edit_data[$data['field_name']];
			    
				if(!empty($edit_data[$data['field_name']]))
				{
					$value = $edit_data[$data['field_name']];
				}else{
					$value = '';
				}
				
			}
			
			if($is_unique==1){
			 $html .= '<textarea onblur="return uniqueValueCheck(this);" name="'.$rep.'" cus="'.$data['field_name'].'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$required.'>'.$value.'</textarea>';   
			}else{
			$html .= '<textarea name="'.$rep.'" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$required.'>'.$value.'</textarea>';
			}
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}

if(!function_exists('renderContent'))
{
	function renderContent($data){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html = '<div class="form-box form-box-heading">'.$data['label_name'].'</div>';	
			}
			
			return $html;
		}	
	}
}
if(!function_exists('renderDropdown'))
{
	function renderDropdown($data, $edit_data='',$repeat='',$repeat_field='',$i=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
			
				if(!empty($repeat) && $repeat==1){
            $rep = 'row['.$repeat_field.']['.$i.']'.'['.$data['field_name'].']';
            }else if(!empty($repeat) && $repeat==2){
            $rep = 'row['.$repeat_field.']'.'[departments]['.$data['field_name'].']';
            }
            else if(empty($repeat) && !empty($data['is_array']))
            {
            $rep = $data['field_name'].'[]';   
            }else{
            $rep = $data['field_name'];
            }
			$html .= '<select class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" name="'.$rep.'"  '.$required.'>
			<option value="">--Select--</option>';
			
			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				
				if(empty($edit_data[$data['field_name']]))
			    {
			      $value = '';
			    }else{
			      $value = $edit_data[$data['field_name']];
			    }
			
				
				if(!empty($optionVal)){
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($value) && $value == $option[0])? 'selected=""':'';
						$html .= '<option value="'.$option[0].'" '.$selected.'>'.$option[1].'</option>';
					}					
				}
			}
			$html .= '</select>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderDropdownMulti'))
{
	function renderDropdownMulti($data, $edit_data=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].'</label>';	
			}
            
			$html .= '<select data-plugin="select2" class="form-control '.$data['field_name'].' '.$class.'" id="'.$id.'" placeholder="'.$placeholder.'" name="'.$data['field_name'].'[]" multiple  '.$required.'>
			<option value="">--Select--</option>';
			
			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				
				if(empty($edit_data[$data['field_name']])){
				    $value = '';
				}else{
				    $value = $edit_data[$data['field_name']];
				}
				
				if(!empty($optionVal)){
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($value) && in_array($option[0],$value))? 'selected=""':'';
						$html .= '<option value="'.$option[0].'" '.$selected.'>'.$option[1].'</option>';
					}					
				}
			}
			$html .= '</select>';
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderCheckbox'))
{
	function renderCheckbox($data, $edit_data=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			$html = '<div class="form-box col'.$layout.'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}


			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				
				if(!empty($optionVal)){
					$html .= '<div class="inline-radio-box inline-radio">';
					foreach($optionVal as $val){
						$option = explode(":", $val);
						$selected = (!empty($edit_data[$data['field_name']]) && $edit_data[$data['field_name']] == $option[0])? 'checked':'';
						$html .= '<label><input type="checkbox" value="'.$option[0].'" class=" '.$data['field_name'].' '.$class.'" id="'.$id.'" '.$selected.' '.$required.' name="'.$data['field_name'].'">'.$option[1].'</label>';
					}
					$html .= '</div>';	
				}
			}
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderRadio'))
{
	function renderRadio($data,$edit_data=''){
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			$html = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$html .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}


			if(!empty($data['field_type_value'])){
				$optionVal = explode("\r\n", $data['field_type_value']);
				//pr($optionVal);
				
				if(!empty($optionVal)){
					$html .= '<div class="inline-radio-box inline-radio">';
					foreach($optionVal as $val){
						$option = explode(":", $val);
						if(empty($edit_data[$data['field_name']])){
						$selected = (!empty($option[2]))? 'checked="checked"':'';
						}else{
						$selected = (!empty($edit_data[$data['field_name']]) && $edit_data[$data['field_name']] == $option[0])? 'checked':'';
						}
						$html .= '<label><input type="radio" value="'.$option[0].'" class=" '.$data['field_name'].' '.$class.'" id="'.$id.'" '.$selected.' '.$required.' name="'.$data['field_name'].'">'.$option[1].'</label>';
					}
					$html .='<label id="'.$data['field_name'].'-error" class="error" for="'.$data['field_name'].'"></label>';
					$html .= '</div>';	
				}
			}
      
	  
			if(!empty($data['field_instructions'])){
				$html .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$html .= '</div>';
			
			return $html;
		}	
	}
}
if(!function_exists('renderCustomFunction'))
{
	function renderCustomFunction($data,$key='',$edit_data='',$repeat='',$master=''){
	      //pr($data); die;
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}				
			if(!empty($data['field_type_value'])){
				
				if(!empty($repeat)){
				$rep = 'row['.$repeat.']'.'['.$data['field_name'].']';
				}
				else if(empty($repeat) && !empty($data['is_array']))
				{
				$rep = $data['field_name'].'[]';   
				}else{
				$rep = $data['field_name'];
				}
				//pr($edit_data); die;
				$html = call_user_func($data['field_type_value'],$data,$edit_data,$rep,$master='');	
			}
			
			return $html;
		}	
	}
}

if(!function_exists('renderCustomFunctionRepFirst'))
{
	function renderCustomFunctionRepFirst($data,$key,$edit_data = '', $field_name='',$master=''){
		//echo $edit_data; die;
		//pr($edit_data); die;
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}				
			if(!empty($data['field_type_value'])){
				$rep = $field_name.'['.$key.']['.$data['field_name'].']';
				//pr($data); die;
				$html = call_user_func($data['field_type_value'],$data,$edit_data,$rep,$master='');	
			}
			
			return $html;
		}	
	}
}


if(!function_exists('renderCustomFunctionSecond'))
{
	function renderCustomFunctionSecond($data,$key='',$rey='',$edit_data = '', $field_name_first='',$field_name_second=''){
		
		if(!empty($data)){
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}				
			if(!empty($data['field_type_value'])){
				
				$rep = $field_name_first.'['.$key.']['.$field_name_second.']['.$rey.']['.$data['field_name'].']';
				//pr($edit_data); die;
				$html = call_user_func($data['field_type_value'],$data,$edit_data,$rep='',0);	
			}
			
			return $html;
		}	
	}
}

if(!function_exists('arrayToDropDown'))
{
	function arrayToDropDown($data,$dataArr, $name, $firstLabel='--Select--', $selected=''){
	    //pr($selected); //die;
		if(!empty($dataArr)){
			
			if(!empty($data['is_disable']) && array_key_exists('is_disable',$data) ){
				return '';
			}
			$id = (!empty($data['html_id']))? $data['html_id']:'';
			$class = (!empty($data['html_class']))? $data['html_class']:'';
			$placeholder = (!empty($data['placeholder']))? $data['placeholder']:'';
			$layout = (!empty($data['layout']))? $data['layout']:'';
			$required = (!empty($data['is_required']))? 'required':'';
			if($required!=''){
			 $error = '<span class="required">*</span>';
			}
			
			$search = (!empty($data['html_id']))? 'data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"':'';
			
			$dropdownHtml = '<div class="form-box col'.$layout.' '.$data['field_name'].'">';
			
			if(!empty($data['label_name']) && array_key_exists('label_name',$data)){
				$dropdownHtml .= '<label for="'.$data['field_name'].'">'.$data['label_name'].$error.'</label>';	
			}
                $multiple = '';
                if($data['is_array']==1 && $data['html_class']=='multiple'){$multiple = 'multiple="multiple" data-plugin="select2"';}else{$multiple='';}
				$dropdownHtml .= '<select class="form-control '.$class.'" '.$multiple.' '.$search.' name="'.$name.'"><option value="">'.$firstLabel.'</option>';
				foreach($dataArr as $key=>$val)
				{
					if($data['html_class']!='multiple')
					{
					if($selected==$key){
						$sel = ' selected';
					}else{
						$sel = '';
					}
				}else{
					$company_name = explode(',', $selected);
					//pr($val['id']);
					if(in_array( $key,$company_name)){

						$sel = ' selected="selected"';

					}else{										
						$sel = '';
					}
				}
					$dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$val.'</option>';
				}
				$dropdownHtml .= '</select>';
			if(!empty($data['field_instructions'])){
				$dropdownHtml .= '<span class="form-note">'.$data['field_instructions'].'</span>';	
			}
			$dropdownHtml .= '</div>';	
				 return $dropdownHtml;
			}
	}
}
if(!function_exists('get_unit'))
{
	function get_unit($data,$edit_data='',$rep=''){
		$object = DB::table('unit')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		//echo "<pre>"; print_r($data); die; 
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['unit']] = $val['unit'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Unit--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_unit_by_mid'))
{
	function get_unit_by_mid($data,$edit_data='',$rep=''){
		$object = DB::table('unit')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		//echo "<pre>"; print_r($data); die; 
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['id']] = $val['unit'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Unit--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_customers'))
{
	function get_customers($data,$edit_data='',$rep=''){
		$object = DB::table('customers')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		//echo "<pre>"; print_r($data); die; 
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['id']] = $val['company_name'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Customers--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_custom_room'))
{
	function get_custom_room($data,$edit_data='',$rep=''){
		$dataUnit = DB::table('room')
				->select('*')
				->get()->toArray();
				
		//echo "<pre>"; print_r($data); die; 
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['room_no']] = $val['room_no'];
			}
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Room--',$edit_data[$data['field_name']]);	
		return $html;
	}
}

if(!function_exists('get_cas_number'))
{
	function get_cas_number($data,$edit_data='',$rep=''){
		
		$dataUnit = DB::table('cas_number')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['cas_number']] = $val['cas_number'];
			}
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select CAS Number--',$edit_data[$data['field_name']]);	
		return $html;
	}
}

if(!function_exists('get_hsn_code'))
{
	function get_hsn_code($data,$edit_data='',$rep=''){
		
		$object = DB::table('hsn_taxes')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['hsn_code']] = $val['hsn_code'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select HSN Code--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_department'))
{
	function get_department($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_department')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['id']] = $val['department_name'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Department--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_designation'))
{
	function get_designation($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_designation')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['designation']] = $val['designation'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Designation--',$edit_data_data);	
		return $html;
	}
}




if(!function_exists('get_Item_group'))
{
	function get_Item_group($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_item_group')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($dataUnit); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['Item_group']] = $val['Item_group'];
			}
		}
		$name = $rep;
		
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
	
		
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Item Group--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_item_type'))
{
	function get_item_type($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_item_type')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['item_type']] = $val['item_type'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Item Type--',$edit_data_data);	
		return $html;
	}
}



if(!function_exists('get_item_sub_type'))
{
	function get_item_sub_type($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_item_sub_type')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['item_sub_type']] = $val['item_sub_type'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Item Sub Type--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_location'))
{
	function get_location($data,$edit_data='',$rep=''){
		
		$dataUnit = DB::table('tbl_location')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		
		$dataUnit_details = json_decode(json_encode($dataUnit), true);
		//pr($dataUnit_details); die;
		$dataArr = array();	
		if(!empty($dataUnit_details)){
			foreach($dataUnit_details as $val){
				$dataArr[$val['id']] = $val['location_name'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Location--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_fmss_list'))
{
	function get_fmss_list($data,$edit_data='',$rep=''){
		
		$object = DB::table('fmss')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		$dataUnit = json_decode(json_encode($object), true);	
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[(string)$val['_id']] = $val['fms_name'];
			}
		}
		 //echo "<pre>"; print_r($dataArr); die; 
		 if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_grade'))
{
	function get_grade($data,$edit_data='',$rep=''){
		
		$object = DB::table('grades')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);			
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['grade']] = $val['grade'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Grade--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_pack_size'))
{
	function get_pack_size($data,$edit_data='',$rep=''){
		
		$object = DB::table('pack_size')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		//echo "<pre>"; print_r($edit_data); die; 
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['pack_size']] = $val['pack_size'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Pack Size--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_type_of_pack'))
{
	function get_type_of_pack($data,$edit_data='',$rep=''){
		
		$object = DB::table('tbl_type_of_pack')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);			
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['id']] = $val['type_of_pack'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Type of Pack--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_storage_conditions'))
{
	function get_storage_conditions($data,$edit_data='',$rep=''){
		
		$object = DB::table('storage_condation')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);			
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['storage']] = $val['storage'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Storage Condition--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_tax_rate'))
{
	function get_tax_rate($data,$edit_data='',$rep=''){
		
		$object = DB::table('hsn_taxes')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);			
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['tax']] = $val['tax'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Tax Rate--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_brand'))
{
	function get_brand($data,$edit_data='',$rep=''){
		
		$object = DB::table('brand')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['brand']] = $val['brand'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Brand--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_segment'))
{
	function get_segment($data,$edit_data='',$rep=''){
		
		$object = DB::table('segments')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['segment']] = $val['segment'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Segment--',$edit_data_data);	
		return $html;
	}
}


if(!function_exists('get_category'))
{
	function get_category($data,$edit_data='',$rep=''){
		
		$dataUnit = DB::table('customer_categories')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['category']] = $val['category'];
			}
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Category--',$edit_data[$data['field_name']]);	
		return $html;
	}
}

if(!function_exists('item_category'))
{
	function item_category($data,$edit_data='',$rep=''){
		
		$object = DB::table('item_categories')
				->select('*')
				->get()->toArray();
		$dataUnit = json_decode(json_encode($object), true);		
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['item_category']] = $val['item_category'];
			}
		}
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Category--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_process'))
{
	function get_process($data,$edit_data='',$rep=''){
		
		$dataUnit = DB::table('processes')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['process']] = $val['process'];
			}
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Process--',$edit_data[$data['field_name']]);	
		return $html;
	}
}

if(!function_exists('get_payment_terms'))
{
	function get_payment_terms($data,$edit_data='',$rep=''){
		
		$dataUnit = DB::table('payment_terms')
				->select('*')
				->get()->toArray();
				
		/* echo "<pre>"; print_r($data); die; */
		$dataArr = array();	
		if(!empty($dataUnit)){
			foreach($dataUnit as $val){
				$dataArr[$val['payment_terms']] = $val['payment_terms'];
			}
		}
		$name = $rep;
		$html = arrayToDropDown($data, $dataArr,$name,'--Select Payment Terms--',$edit_data[$data['field_name']]);	
		return $html;
	}
}

if(!function_exists('month_dropdown'))
{
	function month_dropdown($data,$edit_data='',$rep=''){
		
		$dataArr = array(   '01'=>'January',
							'02'=>'February',
							'03'=>'March',
							'04'=>'April',
							'05'=>'May',
							'06'=>'June',
							'07'=>'July',
							'08'=>'August',
							'09'=>'September',
							'10'=>'October',
							'11'=>'November',
							'12'=>'December');	
		
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		$html = arrayToDropDown($data,$dataArr,$name,'--Select Month--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_preference'))
{
	function get_preference($data='',$edit_data='',$rep=''){
		//echo $edit_data[$data['field_name']]; die;
		
		$dataArr = array(   'brand'=>'Brand',
							'in_stock'=>'In Stock',
							'price'=>'Price');	
		
		$name = $rep;
		if(!empty($edit_data[$data['field_name']]))
		{
			$edit_data_data = $edit_data[$data['field_name']];
		}else{
			$edit_data_data= '';
		}
		
		$html = arrayToDropDown($data,$dataArr,$name,'--Select Preference--',$edit_data_data);	
		return $html;
	}
}

if(!function_exists('get_items'))
{
	function get_items($data='',$edit_data='',$rep=''){
		//pr($edit_data);die;
		$dataArr = array(   'item1'=>'Item A',
							'item2'=>'Item B',
							'item3'=>'Item C',
							'item4'=>'Item D');	
		//echo 'sdfsdfsdf'.pr($data); die;
		
		$name = $rep;
		$html = arrayToDropDown($data,$dataArr,$name,'--Select Item--',$edit_data[$data['field_name']]);	
		return $html;
	}
}



if(!function_exists('GetFormByModule'))
{
    
	function GetFormByModule($module, $id = '', $table_name='',$method='POST', $action='', $redirectTo = '',$edit_data = ''){
	    if(empty($action)){
	        $form_url = '';
	    }else{
	        $form_url = url('admin/'.$action);
	    }
		$object = DB::table('tbl_form')->where('module_name',$module)->get()->first();
		$formData = json_decode(json_encode($object), true);
		
		if(!empty($formData)){
			$html = '<h2 class="form-box form-box-heading">'.$formData['form_name'].'</h2>
						<div class="custom-form-box">						
						<form class="custom-form" action="'.$form_url.'" id="'.$id.'" method="'.$method.'" enctype="multipart/form-data">
						'.csrf_field().'<input type="hidden" name="table_name" value="'.$table_name.'">';
			/* $object2 = DB::table('tbl_form_data')->where('f_id',$formData['id'])->get()->toArray(); */
			$object2 = DB::table('tbl_form_data')->where('f_id',$formData['id'])->orderBy('field_order','ASC')->get()->toArray();
			//$object2 = DB::table('tbl_form_data')->where('f_id',$formData['id'])->get()->toArray();
			$formDataRow = json_decode(json_encode($object2), true);
			$row_data = array();
			
			//pr($formDataRow ); die;

			foreach($formDataRow as $rows){
				//pr($rows);

				if($rows['parent_id']==0){
					$row_data['row'][$rows['id']]=$rows;
				}
				if($rows['parent_id']!=0 && $rows['sub_parent_id']==0){
					$row_data['row'][$rows['parent_id']]['repeat'][$rows['id']]=$rows;
					//pr($rows);
				}
				if($rows['sub_parent_id']!=0){
					$row_data['row'][$rows['parent_id']]['repeat'][$rows['sub_parent_id']]['repeat_new'][]=$rows;
				}
				
			}
			//pr($row_data); die;			
			if(!empty($row_data['row'])){
			    $master =0;
				foreach($row_data['row'] as $row){
				    
                    if($row['field_type'] =='hidden' && $row['field_name'] =='multiinsert'){
                    $master = 1;
                    }
					switch ($row['field_type']) {
					    case "div":
					      $html .= renderDiv($row,$edit_data);
					      break;
					  case "hr":
					      $html .= renderHr($row,$edit_data);
					      break;
					  case "text":
						 $html .= renderText($row,$edit_data);
						break;	
					  case "repeat":
						if(!empty($edit_data[$row['field_name']])){
						$count = count($edit_data[$row['field_name']]);
						}else{
						$count = 0;
						}

						
						if($count==1 || $count==0){
							//echo $master;
							//echo '1-'.$row['field_name'];
						$html .= ArrayrenderText($row,0,$edit_data,'',$row['field_name'],1,1,$master='');
						//pr($row);
						}else{
							//echo '2-'.$row['field_name'];
						$t=1;
						foreach($edit_data[$row['field_name']] as $key=>$val){
						$html .= ArrayrenderText($row,$key,$edit_data,$val,$row['field_name'],$count,$t,$master='');
						$html .= '<hr/>';
						$t++;
						}
						}
					      break;
					  case "email":
						 $html .= renderText($row,$edit_data);
						break;
					  case "password":
					      if(empty($edit_data)){
						 $html .= renderTextPassword($row,$edit_data);
					      }else{
					     $html .= renderTextPassword($row,$edit_data);   
					      }
						break;
					  case "url":
						 $html .= renderText($row,$edit_data);
						break;
					  case "hidden":
						 $html .= renderHidden($row,$edit_data);
						break;		
					  case "number":
						 $html .= renderText($row,$edit_data);
						break;
						case "percentage":
						 $html .= renderText($row,$edit_data);
						break;
					  case "mobile":
						 $html .= renderText($row,$edit_data);
						break;	
					  case "date":
						 $html .= renderText($row,$edit_data);
						break;
					  case "submit":
						 $html .= renderSubmit($row);
						break;		
					  case "button":
						 $html .= renderSubmit($row);
						break;		
					  case "reset":
						 $html .= renderSubmit($row);
						break;				
					  case "dropdown":
						 $html .= renderDropdown($row,$edit_data);
						break;
					  case "multiselect":
						 $html .= renderDropdownMulti($row,$edit_data);
						break;	
					 case "checkbox":
						 $html .= renderCheckbox($row,$edit_data);
						break;
					 case "content":
						 $html .= renderContent($row);
						break;	
					 case "textarea":
						 $html .= renderTextarea($row,$edit_data);
						break;
					 case "radio":
						 $html .= renderRadio($row,$edit_data);
						break;	
					 case "file":
						 $html .= renderFile($row,$edit_data);
						break;
					 case "editor":
						break;	
					 case "function":
						 $html .= renderCustomFunction($row,0,$edit_data,'',$master='');
						break;	
					  default:
						$html .= "not created ".$row['field_type']."<br>";
					}
				}
			}	
			$html .=	'<input type="hidden" name="created_on" value="'.date('Y-m-d h:i:s').'"></form>
					</div>';	
			return $html;			
		}
		
	}
}

function getUserRole($user_role_erp,$desig_multiple){
    if($user_role_erp!=''){
        return ucwords(str_replace('_',' ',$user_role_erp));
    }else if($desig_multiple!=''){
        $desigarr=array();
        foreach($desig_multiple as $desigId){
        array_push($desigarr,ucwords($desigId));
        }
        return implode(", ",$desigarr);
    }
}

function getRestrictions($name,$id,$value='')
{
    $data = array('lettersonly'=>'Only Alphabets','email'=>'Email Format','password'=>'Password');
    
    $dropdownHtml = '<select id="restrictions-'.$id.'" class="form-control" name="'.$name.'" ><option value="">-- Select --</option>';
    foreach($data as $key=>$rows){
            if($key==$value){
            $sel = ' selected';
            }else{
            $sel = '';
            }
            $dropdownHtml .= '<option '.$sel.' value="'.$key.'">'.$rows.'</option>';
    }
    return $dropdownHtml .='</select>';
}

function minify_html($html)
{
   $search = array(
    '/(\n|^)(\x20+|\t)/',
    '/(\n|^)\/\/(.*?)(\n|$)/',
    '/\n/',
    '/\<\!--.*?-->/',
    '/(\x20+|\t)/', # Delete multispace (Without \n)
    '/\>\s+\</', # strip whitespaces between tags
    '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
    '/=\s+(\"|\')/'); # strip whitespaces between = "'

   $replace = array(
    "\n",
    "\n",
    " ",
    "",
    " ",
    "><",
    "$1>",
    "=$1");

    $html = preg_replace($search,$replace,$html);
    return $html;
}

function userMenuAccessRight($userarray,$menu)
{
	 return true; die;
    $role = Auth::user()->userrole;
    $user = auth()->user();
    if(empty($userarray) && ($user->fms_role=='admin' || $user->user_role==1)){
        return true;
    }else if(@in_array($menu,$userarray)){
        return true;
    }else{
        return false;
    }
}

function getUserAccess(){
    return $userrole = Auth::user()->userrole;
}

if(!function_exists('getRoleListingById')) {
	function getRoleListingById($id){ 
		$dropdownlist = DB::table('roles')->where('_id', '=', $id)->get()->toArray();	
		$sorted_array = $dropdownlist[0]['items'];	
		asort($sorted_array);	
		$dropdownHtml='';	
		if(!empty($sorted_array)){	
			$dropdownHtml = '<ul>';	
			foreach($sorted_array as $label) {
				$dropdownHtml .= '<li>'.$label.'</li>';		
			} 					
			return $dropdownHtml .= '</ul>';	
		}else{
			return 'No data found';		
		}	
	}
}

if ( ! function_exists('dateshow'))
{
      
	function dateshow($date){
	    $sec=strtotime($date);
	    //echo $sec;
	     if(!isEmpty($date) && !empty($sec) && $sec>0){
            $ci=date('d-m-Y',strtotime($date));
            return $ci;
        } else {
	   return false;
      
       }
	
}
}

if ( ! function_exists('isEmpty'))
{
	function isEmpty($var){
		if(empty($var) || trim($var) == "" || $var == NULL) return true;
		else return false;
	}
}

if(!function_exists('userPermission'))
{
    function userPermission($method, $slug='')
	{
	 	
		$user = auth()->user();
		$userrole = $user->userrole;
		if($user->fms_role=='admin' || $user->user_role==1){
		    return true;
		}
		else if(!empty($slug) && @in_array($slug,$userrole)){
			return true;
		}
		else if(@in_array($method,$userrole))
		{
		return true;
		}
		else
		{
	    header('location: https://themesofwp.com/hverp/admin/not-permission');
		exit;
		//return true;
		}
	}
}



if(!function_exists('checkGrade'))
{
    function checkGrade($grade)
	{
		if($grade!='')
		{
		$datacheck = DB::table('grades')->where('grade', '=', $grade)->get()->count();
		if($datacheck==0){
		    $data = array('grade'=>checkNullDatas($grade));
		    DB::table('grades')->insert(array($data));
		 }
		}
	}
}

if(!function_exists('checkBrand'))
{
    function checkBrand($brand)
	{
		if($brand!='')
		{
		$datacheck = DB::table('brand')->where('brand', '=', $brand)->get()->count();
		if($datacheck==0){
		    $data = array('brand'=>checkNullDatas($brand));
		    DB::table('brand')->insert(array($data));
		 }
		}
	}
}




if(!function_exists('checkNullDatas'))
{
    function checkNullDatas($data)
	{
		
		  if($data=='NULL' || $data=='.'  || $data=='null' || $data=='..' || $data==NULL){
		      return "";
		  }else{
		      return $data;
		  }
	}
}

if(!function_exists('checkHSNCodeTax'))
{
    function checkHSNCodeTax($hsn_code,$tax)
	{
		if($hsn_code!='')
		{
		$datacheck = DB::table('hsn_taxes')->where('hsn_code', '=', $hsn_code)->where('tax', '=', $tax)->get()->count();
		if($datacheck==0){
		    $data = array('hsn_code'=>$hsn_code,'tax'=>$tax);
		    DB::table('hsn_taxes')->insert(array($data));
		 }
		}
	}
}



if(!function_exists('brandUpdate'))
{
    function brandUpdate($data,$id)
	{
		if(!empty($data))
		{
		 $data_brand_select = DB::table('brand')->where('_id', '=', $id)->get()->first();
		 $data_brand = $data['repeat'][0]['brand'];
		 DB::table('items')->where('brand',$data_brand_select['brand'])->orWhere('brand',strtolower($data_brand_select['brand']))->orWhere('brand',strtoupper($data_brand_select['brand']))->orWhere('brand',ucwords($data_brand_select['brand']))->update(array('brand'=>$data_brand));
		}
	}
}


if(!function_exists('gradeUpdate'))
{
    function gradeUpdate($data,$id)
	{
		if(!empty($data))
		{
		 $data_grade_select = DB::table('grades')->where('_id', '=', $id)->get()->first();
		 $data_grade = $data['repeat'][0]['grade'];
		 DB::table('items')->where('grade',$data_grade_select['grade'])->orWhere('grade',strtolower($data_grade_select['grade']))->orWhere('grade',strtoupper($data_grade_select['grade']))->orWhere('grade',ucwords($data_grade_select['grade']))->update(array('grade'=>$data_grade));
		}
	}
}

if(!function_exists('checkChar'))
{
    function checkChar($data)
	{
		if(!empty($data))
		{
        if (strpos($data, '%') !== false) {
        return $data;
        }else{
        return $data.'%';   
        }
		}else{
		    return $data;
		}
	}
}

if(!function_exists('HSNcodeUpdate'))
{
    function HSNcodeUpdate($data,$id)
	{
		if(!empty($data))
		{
		 $data_hsn_select = DB::table('hsn_taxes')->where('_id', '=', $id)->get()->first();
		 $data_hsn = $data['repeat'][0]['hsn_code'];
		 DB::table('items')->where('hsn_code',$data_hsn_select['hsn_code'])->orWhere('hsn_code',strtolower($data_hsn_select['hsn_code']))->orWhere('hsn_code',strtoupper($data_hsn_select['hsn_code']))->orWhere('hsn_code',ucwords($data_hsn_select['hsn_code']))->update(array('hsn_code'=>$data_hsn));
		}
	}
}
if(!function_exists('getProductItem'))
{
    function getProductItem($id)
	{
		if(!empty($id))
		{
		 $data = DB::table('items')->where('_id', '=', $id)->get()->first();
		 return $data['name'];
		}
	}
}




if(!function_exists('nextAutoincrementId'))
{
    function nextAutoincrementId($table, $field)
	{
		if(!empty($field))
		{
		    return DB::table($table)->max($field)+1;
           
		}
		
	}
}

if(!function_exists('midUpdate'))
{
    function midUpdate($table, $field)
	{
		if(!empty($field))
		{
		    $data_original = DB::table($table)->get();
	         $array = json_decode(json_encode($data_original), true);
	         $mid = 1;
	         foreach($array as $datas)
	         {
	         $oid = $datas['_id']['$oid'];
	         $arr = array('mid'=>(String)$mid);
	         DB::table($table)->where('_id',$oid)->update($arr);
	         $mid++;
	         }
           
		}
		
	}
}


if(!function_exists('getRoleCategory'))
{
    function getRoleCategory($id)
	{
		if(!empty($id))
		{
		     $data_original = DB::table('role_category')->where('_id',$id)->first();
	         return $data_original['role_category'];
           
		}else{
		    return '';
		}
		
		
	}
}


if(!function_exists('get_netrate_by_customer_item'))
{
    function get_netrate_by_customer_item($customer_id,$product_id)
	{
		if(!empty($customer_id))
		{
		     $object = DB::table('tbl_netrate')->where('customer_id', $customer_id)->where('product_id', $product_id)->where('date_rate','>=',date('Y-m-d'))->first();
             $data = json_decode(json_encode($object), true);
			 return $data['net_rate']; 
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('get_netrate_by_vendor_item'))
{
    function get_netrate_by_vendor_item($vendor_id,$product_id)
	{
		if(!empty($vendor_id))
		{
		     $object = DB::table('tbl_purchase_netrate')->select('net_rate')->where('vendor_id', $vendor_id)->where('product_id', $product_id)->where('date_rate','>=',date('Y-m-d'))->first();
             $data = json_decode(json_encode($object), true);
			 return $data['net_rate']; 
		}else{
		    return '';
		}
		
		
	}
}



if(!function_exists('get_netrate_by_item'))
{
    function get_netrate_by_item($product_id)
	{
		if(!empty($product_id))
		{
		     $object = DB::table('tbl_netrate')->select('id','net_rate')->where('customer_id',0)->where('product_id', $product_id)->where('date_rate','>=',date('Y-m-d'))->first();
             $data = json_decode(json_encode($object), true);
			 return $data['net_rate']; 
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('get_netrate_by_item_vendor'))
{
    function get_netrate_by_item_vendor($product_id)
	{
		if(!empty($product_id))
		{
		     $object = DB::table('tbl_purchase_netrate')->select('id','net_rate')->where('vendor_id',0)->where('product_id', $product_id)->where('date_rate','>=',date('Y-m-d'))->first();
             $data = json_decode(json_encode($object), true);
			 return $data['net_rate']; 
		}else{
		    return '';
		}
		
		
	}
}


if(!function_exists('get_discount_by_customer_brand'))
{
    function get_discount_by_customer_brand($customer_id,$brand_id)
	{
		if(!empty($brand_id))
		{
		     $object = DB::table('tbl_discount')->select('id','discount','discount_by')->where('customer_id', $customer_id)->where('brand_name', $brand_id)->where('valid_till','>=',date('Y-m-d'))->first();  
             $data = json_decode(json_encode($object), true);
			 return $data; 
		}else{
		    return '';
		}
		
		
	}
}


if(!function_exists('get_discount_by_vendor_brand'))
{
    function get_discount_by_vendor_brand($vendor_id,$brand_id)
	{
		if(!empty($brand_id))
		{
		     $object = DB::table('tbl_purchase_discount')->select('id','discount','discount_by')->where('vendor_id', $vendor_id)->where('brand_id', $brand_id)->where('valid_till','>=',date('Y-m-d'))->first();  
             $data = json_decode(json_encode($object), true);
			 return $data; 
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('get_discount_by_brand'))
{
    function get_discount_by_brand($brand_id)
	{
		if(!empty($brand_id))
		{
		     $object = DB::table('tbl_discount')->select('id','discount','discount_by')->where('customer_id', 0)->where('brand_name', $brand_id)->where('valid_till','>=',date('Y-m-d'))->first();  
             $data = json_decode(json_encode($object), true);
			 return $data; 
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('get_discount_by_brand_vendor'))
{
    function get_discount_by_brand_vendor($brand_id)
	{
		if(!empty($brand_id))
		{
		     $object = DB::table('tbl_purchase_discount')->select('id','discount','discount_by')->where('vendor_id', 0)->where('brand_id', $brand_id)->where('valid_till','>=',date('Y-m-d'))->first();  
             $data = json_decode(json_encode($object), true);
			 return $data; 
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('getCustomerAddress'))
{
    function getCustomerAddress($customer_id)
	{
		if(!empty($customer_id))
		{
		     $object = DB::table('customers')->where('id', $customer_id)->first(); 
			 $data = json_decode(json_encode($object), true); 
             return $data; 
		}else{
		    return '';
		}
		
		
	}
}



if(!function_exists('getGroupDetails'))
{
    function getGroupDetails($id)
	{
		if(!empty($id))
		{
		     $object = DB::table('group_of_company')->where('id', $id)->first();  
             $data = json_decode(json_encode($object), true); 
			 return $data; 
		}else{
		    return '';
		}
		
		
	}
}



if(!function_exists('getQuotationItemDetails')){	
    function getQuotationItemDetails($id)
    {	    
        if($id!='')		
        {		
            $object = DB::table('tbl_quotation')->where('_id', '=',$id)->get()->first();
			$data = json_decode(json_encode($object), true); 		
            if(!empty($data))
            {			
                $res = $data;		
                
            }else{			
                $res = '';		
                
            }		
            return $res;		
            
        }else{		
            return $res='';		
            
        }			
        
    }
    
}


if(!function_exists('getSaleOrderItemDetails')){	
    function getSaleOrderItemDetails($id)
    {	    
        if($id!='')		
        {		
            $object = DB::table('tbl_sale_order')->where('_id', '=',$id)->get()->first();
			$data = json_decode(json_encode($object), true); 		
            if(!empty($data))
            {			
                $res = $data;		
                
            }else{			
                $res = '';		
                
            }		
            return $res;		
            
        }else{		
            return $res='';		
            
        }			
        
    }
    
}


if(!function_exists('getUserNameByID')){	
    function getUserNameByID($id)
    {	    
        if($id!=0)		
        {		
            $object = DB::table('users')->where('id', '=', $id)->first();	
			$data = json_decode(json_encode($object), true);	
            if($data!='')
            {			
                $res = $data['full_name'];		
                
            }else{			
                $res = '';		
                
            }		
            return $res;		
            
        }else{		
            return $res='';		
            
        }			
        
    }
    
}	


if(!function_exists('SearchDataMatch')){	
    function SearchDataMatch($str)
    {	    
        if($str!='')		
        {		
            $s = str_replace('/','-',$str);
            $res = preg_replace('/[\s-]+/', '', $s);
            return strtolower($res);		
            
        }else{		
            return $res='';		
            
        }			
        
    }
    
}	


if(!function_exists('rateAverage')){	
    function rateAverage($id)
    {	    
        if($id!='')		
        {		
            $data = DB::table('tbl_balance_stock')->where('item_id',$id)->get()->toArray();		
            $data_count = DB::table('tbl_balance_stock')->where('item_id',$id)->count();
            if(!empty($data))
            {
                $sum=0;
                foreach($data as $list)
                {
                    $sum = $sum+$list->rate;
                }
                return number_format($sum/$data_count,2);
            }else{
                return $res='';
            }
        }else{		
            return $res='';		
            
        }			
        
    }
    
}	

if(!function_exists('rateQuantity')){	
    function rateQuantity($id)
    {	    
        if($id!='')		
        {		
            $data = DB::table('tbl_balance_stock')->where('item_id',$id)->get()->toArray();		
            if(!empty($data))
            {
                $sum=0;
                foreach($data as $list)
                {
                    $sum = $sum+$list->quantity;
                }
                return $sum;
            }else{
                return $res='';
            }
        }else{		
            return $res='';		
            
        }			
        
    }
    
}

if(!function_exists('mrnDetails')){	
    function mrnDetails($id)
    {	    
        if($id!='')		
        {		
            $data = DB::table('tbl_mrn')->where('id',$id)->get()->first();		
            if(!empty($data))
            {	
				$data = (array) $data;
                return $data;
            }else{
                return '';
            }
        }else{		
            return '';		
            
        }			
        
    }
    
}


if(!function_exists('getAllitemsById'))
		{
			function getAllitemsById($id)
			{
				
				$data=array();
				$resArr = array();
				
					$object = \DB::table('items')
						->select('*')
						->where('id',$id)
						->get()
						->first();
						$data = json_decode(json_encode($object), true);
							$resArr= addslashes(str_replace("'","",$data['name'])).'-'.$data['grade'].'-'.$data['packing_name'].'-'.$data['brand'].'-'.$data['vendor_sku'];
				
				return $resArr;
				exit;
			}
		}


if(!function_exists('getStockQuantity'))
		{
			function getStockQuantity($id)
			{
				
				$data=array();
				$resArr = array();
				
					$data = \DB::table('tbl_balance_stock')
						->where('item_id',$id)
						->get()
						->toArray();
						$sum=0;
						if(!empty($data))
						{
						    foreach($data as $qty)
						    {
						     $sum = $sum+$qty->quantity;   
						    }
						    return $sum;
						}else{
						    return 0;
						}
						
			}
		}

if(!function_exists('getTotalQuantity')){	
    function getTotalQuantity($ids)
    {	    
        if($ids!='')		
        {		
            $data = DB::table('tbl_sale_order_item')->where('sale_order_id',$ids)->get()->toArray();		
            // if(!empty($data))
            // {
            //     $sum=0;
            //     foreach($data as $list)
            //     {
            //         $sum = $sum+$list['quantity'];
            //     }
            //     return $sum;
            // }else{
            //     return $res='';
            // }
            return count($data);
        }else{		
            return $res='';		
            
        }		
        
    }
    
}


if(!function_exists('getCustomerEmail'))
{
    function getCustomerEmail($customer_id)
	{
		if(!empty($customer_id))
		{
		     $object = DB::table('customers')->where('id', $customer_id)->first();
			 $data = json_decode(json_encode($object), true); 
		     if(!empty($data))
		     {
             return strtolower($data['email']);
		     }else{
		     return '';
		     }
		}else{
		    return '';
		}
		
		
	}
}

if(!function_exists('getStockTotalQuantity')){	
    function getStockTotalQuantity($ids)
    {	    
        //pr($id); //die;
        if($ids!='')		
        {		
            $object = DB::table('tbl_sale_order_item')->where('sale_order_id',$ids)->get()->toArray();	
			$data = json_decode(json_encode($object), true);
			//pr($data); die; 	
            if(!empty($data))
            {
                $sum=0;
                $d = 0;
                $t = 0;
                foreach($data as $list)
                {
					
                    $object_data_item = DB::table('items')->where('id',$list['item_id'])->get()->first();
					$data_item = json_decode(json_encode($object_data_item), true); 
					//pr($data_item);
					if(!empty($data_item['stock'])){
                    $sum = $data_item['stock'];
					}else{
					$sum = 0;	
					}
                    
                    $object_data_challan = DB::table('tbl_oc_item')->where('o_item_id',$list['id'])->get()->toArray();
					$data_challan = json_decode(json_encode($object_data_challan), true);
                    $sum_challan = 0;
                    foreach($data_challan as $challan)
                    {
                     $sum_challan + $challan['dispatch_qty'];   
                    }
                    $balance_qty = $list['quantity'];
                    $remain_qty = $balance_qty-$sum_challan;
                    if($remain_qty<=$sum)
                    {
                        $t = 1;
                        $d = $d+$t;
                    }else if($remain_qty<$sum && $sum=0){
                        $t = 0;
                        $d = $d+$t;
                    }
                    

                }
                //$par = ($t==0)?' (Partial)':'';
                return $d;
                
            }else{
                return $res='';
            }
        }else{		
            return $res='';		
            
        }		
        
    }
    
}


if(!function_exists('getSalesOrderItems')){	
    function getSalesOrderItems($id)
    {
     $data = DB::table('tbl_sale_order_item')->where('sale_order_id',$id)->get()->toArray();
     return $data;
    }
}



if(!function_exists('getSaleOrderNo')){	
    function getSaleOrderNo($id)
    {
     $object = DB::table('tbl_sale_order')->where('id',$id)->get()->first();
	 $data = json_decode(json_encode($object), true);
     return $data['saleorder_no'];
    }
}


if(!function_exists('getSaleOrderDetails')){	
    function getSaleOrderDetails($saleorder_no)
    {
     $data = DB::table('tbl_sale_order')->where('saleorder_no',$saleorder_no)->get()->first();
     return $data;
    }
}


if(!function_exists('getRowsItems')){	
    function getRowsItems($name, $customer_id, $type)
    {
    if($type==0){
     $data_single_order = getSingleOrder($customer_id);
	 if(!empty($data_single_order)){
		$object = DB::table('tbl_sale_order_item')->where('item_id',$name)->where('customer_id',$customer_id)->whereNotIn('sale_order_id',$data_single_order)->get()->toArray();
	 }else{
		$object = DB::table('tbl_sale_order_item')->where('item_id',$name)->where('customer_id',$customer_id)->get()->toArray();
	 }
     
	 $data = json_decode(json_encode($object), true);
    }else{
      $object = DB::table('tbl_sale_order_item')->where('item_id',$name)->where('customer_id',$customer_id)->get()->toArray(); 
	  $data = json_decode(json_encode($object), true); 
    }
     if(count($data)>1){
         return count($data);
     }else{
     return '';
     }
    }
}




if(!function_exists('getStockItem')){	
    function getStockItem($id)
    {
     $object = DB::table('items')->where('id',$id)->get()->first();
	 $data = json_decode(json_encode($object), true);
     return $data['stock'];
     if($data['stock']!='')
     {
         return $data['stock'];
     }else{
         return 0;
     }
    }
}



if(!function_exists('getDispatchOrder')){	
    function getDispatchOrder($customer_id,$item_id,$sale_order_no)
    {
     $object = DB::table('tbl_oc_item')->where('customer_id',$customer_id)->where('item_id',$item_id)->where('sale_order_no',$sale_order_no)->get();
	 $data = json_decode(json_encode($object), true);
        $sum=0;
        if(!empty($data))
        {
        foreach($data as $qty)
        {
        $sum = $sum+$qty['dispatch_qty'];   
        }
        return $sum;
        }else{
        return 0;
        }
     //return count($data);
    }
}



if(!function_exists('getTotalDispatchQuantity')){	
    function getTotalDispatchQuantity($o_item_id)
    {
     $object = DB::table('tbl_oc_item')->where('o_item_id',$o_item_id)->get()->toArray();
	 $data = json_decode(json_encode($object), true); 
        $sum=0;
        if(!empty($data))
        {
        foreach($data as $qty)
        {
        $sum = $sum+$qty['dispatch_qty'];   
        }
        return $sum;
        }else{
        return 0;
        }
     //return count($data);
    }
}

if(!function_exists('getDispatchOrderData')){	
    function getDispatchOrderData($customer_id,$sale_order_id,$sale_order_no)
    {
     $object = DB::table('tbl_sale_order_item')->where('sale_order_id',trim($sale_order_id))->where('completed',1)->get()->toArray();
	 $data = json_decode(json_encode($object), true); 
    //  $sum=0;
    //  foreach($data as $d){
    //      $object_data_c = DB::table('tbl_oc_item')->where('customer_id',$customer_id)->where('item_id',$d['item_id'])->where('sale_order_no',$sale_order_no)->get()->toArray();
	// 	 $data_c = json_decode(json_encode($object_data_c), true); 
    //      //pr($data_c); 
    //      $sum = $sum+count($data_c);
    //  }
     return count($data);
     }
}


if(!function_exists('ChallanItemCount')){	
    function ChallanItemCount($ids)
    {
     $id = (array) $ids;
	 $oid = $id['oid'];
     $data = DB::table('tbl_oc_item')->where('oc_id',$oid)->get();
     return count($data);
    }
}

if(!function_exists('getChallanDetails')){	
    function getChallanDetails($id)
    {
     $data = DB::table('tbl_oc_item')->where('challan_id',$id)->first();
     return $data;
    }
}


if(!function_exists('getChaDetails')){	
    function getChaDetails($ids)
    {
     $data = DB::table('tbl_oc')->where('id',$ids)->first();
     return $data;
    }
}

if(!function_exists('getOrderedQty')){	
    function getOrderedQty($id)
    {
     $data = DB::table('tbl_purchase_order_item')->where('item_id',$id)->get()->toArray();
        if(!empty($data))
        {
        $sum=0;
        foreach($data as $list)
        {
        $sum = $sum+$list['quantity'];
        }
        if($sum>0)
        {
        return $sum .' Ordered';
        }else{
        return '';
        }
        }else{
        return $res='';
        }
    }
}




if(!function_exists('getItemDetails')){	
    function getItemDetails($id)
    {
     $data = DB::table('tbl_balance_stock')->where('item_id',$id)->orderBy('_id','DESC')->get()->first();   
     if(!empty($data)){
         return $data;
     }else{
         return '';
     }
    }
}



if(!function_exists('getSingleOrder')){	
    function getSingleOrder($customer_id)
    {
	 $object = DB::table('tbl_sale_order')->select('id')->where('customer_name',$customer_id)->where('single_order','yes')->orderBy('id','DESC')->get(); 
	 $data = json_decode(json_encode($object), true);  
     if(!empty($data)){
         $ids = array();
         foreach($data as $key=>$order_id)
         {
             $id = $order_id['id'];
             array_push($ids,$id);
         }
         return $ids;
     }else{
         return '';
     }
    }
}



if(!function_exists('getItemAmount')){	
    function getItemAmount($sale_order_id)
    {
        $object = DB::table('tbl_sale_order_item')->select('amount')->where('id',$sale_order_id)->first();
		$data = json_decode(json_encode($object), true);
        return $data['amount'];
    }
}

if(!function_exists('getCustomerAdvPayment')){	
    function getCustomerAdvPayment($customer_id)
    {
        $object = DB::table('customers')->select('adv_amount')->where('id',$customer_id)->first();
		$data = json_decode(json_encode($object), true);
        if($data['adv_amount']!='')
        {
        return $data['adv_amount'];
        }else{
        return 0;    
        }
    }
}




if(!function_exists('getSaleOrderId')){	
    function getSaleOrderId($id)
    {
        $object = DB::table('tbl_sale_order_item')->select('sale_order_id')->where('id',$id)->first();
		$data = json_decode(json_encode($object), true);
        return $data['sale_order_id'];
    }
}



if(!function_exists('ItemWiseadvPayment')){	
    function ItemWiseadvPayment($id)
    {
        $object = DB::table('tbl_sale_order')->select('adv_amount')->where('id',$id)->first();
		$data = json_decode(json_encode($object), true);
        if($data['adv_amount']!='')
        {
        return $data['adv_amount'];
        }else{
            return 0;
        }
    }
}


if(!function_exists('getChallanCount')){	
    function getChallanCount($id)
    {
        $data = DB::table('tbl_oc_item')->where('sale_order_no',$id)->count();
        return $data;
    }
}


if(!function_exists('getQuantityItem')){	
    function getQuantityItem($id)
    {
        $object = DB::table('tbl_sale_order_item')->where('id',$id)->first();
		$data = json_decode(json_encode($object), true);
        return $data['quantity'];
    }
}



if(!function_exists('makeChallanBtn')){	
    function makeChallanBtn($qty,$dispatch,$pending,$stock,$single_order_type)
    {
        // $data = DB::table('tbl_sale_order_item')->where('_id',$id)->first();
        // return $data['quantity'];
        if($qty==$stock && $single_order_type='yes'){
            if($qty==$dispatch){
            return 0;
            }else{
            return 1;  
            }
        }else{
            return 0;
        }
    }
}



if(!function_exists('getCheckCompletedOrder')){	
    function getCheckCompletedOrder($ids)
    {
        $data = DB::table('tbl_quotation_item')->where('quotation_id',$ids)->where('sale_order_item_satus',0)->get()->count();
        return $data;
    }
}


if(!function_exists('getDynamicTable')){	
    function getDynamicTable($field_type, $field_name, $field_length, $nullable)
    {
        $ftype = 'string';
        $field_len = '';
        $null = '';
        switch (true) {
              case($field_type == "text" || $field_type == "password" || $field_type == "email" || $field_type == "mobile" || $field_type == "number" || $field_type == "percentage" || $field_type == "url" || $field_type == "hidden" || $field_type == "dropdown" || $field_type == "multiselect" || $field_type == "checkbox" || $field_type == "radio" || $field_type == "file" || $field_type == "date" || $field_type == "color" || $field_type == "function"):
                $ftype          = 'string';
                $field_len   = $field_length;
                $null    = $nullable;
              break; 
			  case($field_type == "textarea"):
                $ftype          = 'text';
                $field_len   = '';
                $null    = $nullable;
              break;
            }

         return array('field_name'=>$field_name,'type'=>$ftype,'length'=>$field_len,'is_nullable'=>$null);
    }
}


if(!function_exists('getDepartmentNameData')){	
    function getDepartmentNameData($c_id,$d_id)
    {
		$object = DB::table('tbl_department_name_data')->where('c_id',$c_id)->where('d_id',$d_id)->get()->toArray();
		$data = json_decode(json_encode($object), true);
        return $data;	
	}
}


if(!function_exists('getDepartmentNameDataVendor')){	
    function getDepartmentNameDataVendor($c_id,$d_id)
    {
		$object = DB::table('tbl_department_name_data_vendor')->where('c_id',$c_id)->where('d_id',$d_id)->get()->toArray();
		$data = json_decode(json_encode($object), true);
        return $data;	
	}
}

if(!function_exists('insertFmsreplyData')){
    function insertFmsreplyData($repArr)
    {
		$id = DB::table('tbl_fms_data_reply')->insertGetId($repArr);
        return $id;	
	}
}

if(!function_exists('getFmsreplyDataByFmsId')){
    function getFmsreplyDataByFmsId($id)
    {
		$data = DB::table('tbl_fms_data_reply')->where('fms_data_id',$id)->get()->toArray();
        return $data;	
	}
}

if(!function_exists('getOrderItemList')){
    function getOrderItemList($id)
    {
		$object = DB::table('tbl_sale_order_item')->where('sale_order_id',$id)->orderBy('item_id','ASC')->get(); 
		$data = json_decode(json_encode($object), true);
        return $data;	
	}
}


if(!function_exists('getBalanceQuantity')){
    function getBalanceQuantity($id)
    {
		//$object = DB::table('tbl_balance_stock')->where('item_id',$id)->orderBy('expiry_date','ASC')->get(); 
		//$today_date = date('Y-m-d');
		$object = DB::table('tbl_balance_stock')->select('tbl_balance_stock.*','room.room_no','rack.rack_name')->join('room','room.id','=','tbl_balance_stock.room_no')->join('rack','rack.id','=','tbl_balance_stock.rack_no')->where('tbl_balance_stock.item_id',$id)->orderBy('expiry_date','ASC')->get(); 
		$data = json_decode(json_encode($object), true);
		//pr($data);
        return $data;	
	}
}

if(!function_exists('getUnitNameFromId'))
{
	function getUnitNameFromId($id=''){
		$object = DB::table('unit')->where('id',$id)->get()->first();
		$dataUnit = json_decode(json_encode($object), true);		
		if($dataUnit['unit']!=''){
            return $dataUnit['unit'];
		}else{
			return '';
		}
	}
}


if(!function_exists('getPackNameFromId'))
{
	function getPackNameFromId($id=''){
		$object = DB::table('type_of_pack')->where('id',$id)->get()->first();
		$dataUnit = json_decode(json_encode($object), true);		
		if($dataUnit['type_of_pack']!=''){
            return $dataUnit['type_of_pack'];
		}else{
			return '';
		}
	}
}


if(!function_exists('mbConvertEncoding'))
{
	function mbConvertEncoding($name=''){
		if($name!=''){
		return mb_convert_encoding($name,"HTML-ENTITIES","UTF-8");
		}else{
		return '';	
		}
	}
}


if(!function_exists('getRevisedOrder'))
{
	function getRevisedOrder($id){
		if($id!=''){
			$object = DB::table('tbl_quotation_revised')->where('revised_id',$id)->get()->count();
			return $object;
		}else{
		return '';	
		}
	}
}

if(!function_exists('balanceStockUpdate'))
{
	function balanceStockUpdate($item_id){
		$quantity_total=$hold_qty=$complete_qty=0;
		if(!empty($item_id))
		{
			$hold_qty = DB::table('tbl_oc_item')
			->where('item_id', $item_id)
			->where('status',0)
			->sum('dispatch_qty');

			$complete_qty = DB::table('tbl_oc_item')
			->where('item_id', $item_id)
			->where('status',1)
			->sum('dispatch_qty');

			$quantity_total = DB::table('tbl_balance_stock')
			->where('item_id', $item_id)
			->sum('quantity');
			//echo $quantity_total.'-'.$hold_qty.'-'.$complete_qty; die;
		$update_qty = $quantity_total-$hold_qty-$complete_qty;	
		DB::table('items')->where('id',$item_id)->update(array('stock'=>$update_qty,'hold_qty'=>$hold_qty));
		return $update_qty;
		}else{
		return '';
		}
	}
}

if(!function_exists('getGSThsnTax'))
{
	function getGSThsnTax($hsn_code){
		if($hsn_code!=''){
			$object = DB::table('hsn_taxes')->select('tax')->where('hsn_code',$hsn_code)->first();
			$data = json_decode(json_encode($object), true);
			if($data['tax']=='' || $data['tax']==0)
			{
			return 0;
			}else{
			return $data['tax'];
			}
		}else{
		return 0;	
		}
	}
}

if(!function_exists('getGSTItemTax'))
{
	function getGSTItemTax($item_id){
		if($item_id!=''){
			$object = DB::table('items')->select('hsn_code')->where('id',$item_id)->first();
			$data = json_decode(json_encode($object), true);
			if($data['hsn_code']=='' || $data['hsn_code']==0)
			{
			return 0;
			}else{
			return getGSThsnTax($data['hsn_code']);
			}
		}else{
		return 0;	
		}
	}
}


if(!function_exists('getItemStocks'))
{
	function getItemStocks($item_id){
		if($item_id!=''){
			$object = DB::table('items')->select('stock')->where('id',$item_id)->first();
			$data = json_decode(json_encode($object), true);
			if($data['stock']=='' || $data['stock']==0)
			{
			return 0;
			}else{
			return $data['stock'];
			}
		}else{
		return 0;	
		}
	}
}

if(!function_exists('getOpeningStocksQty'))
{
	function getOpeningStocksQty($item_id){
		if($item_id!=''){
			$qty = DB::table('tbl_balance_stock')
			->where('item_id', $item_id)
			->where('type',1)
			->sum('quantity');
			return $qty;
		}else{
		return 0;	
		}
	}
}

if(!function_exists('getInwardStocksQty'))
{
	function getInwardStocksQty($item_id){
		if($item_id!=''){
			$qty = DB::table('tbl_balance_stock')
			->where('item_id', $item_id)
			->where('type',2)
			->sum('quantity');
			return $qty;
		}else{
		return 0;	
		}
	}
}



if(!function_exists('getoCDetails'))
{
	function getoCDetails($id){
		if($id!=''){
			$qty = DB::table('tbl_sale_order_item')
			->where('id', $id)
			->first();
			return $dataUnit = json_decode(json_encode($qty), true);		
		}else{
		return '';	
		}
	}
}

if(!function_exists('getRecurringData'))
{
	function getRecurringData(){
		$array = array('6'=>'Everyday','5'=>'On a particular day of week','4'=>'On a particular day every 2 weeks','3'=>'On a particular date of the month','2'=>'On a particular date every quarter','1'=>'On a particular date every year');
		return $array;
	}
}



if(!function_exists('get_recurring_type_data'))
{
	function get_recurring_type_data($recurring_type='',$date_of_yearly='',$month_of_yearly='',$date_of_quaterly='',$month_of_quaterly='',$date_of_month='',$date_of_forthnigthly='',$day_of_week=''){
		if($recurring_type==1)
		{
		$html = '';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-12"><select required name="date_of_yearly" class="form-control" id="date_of_yearly"><option value="">Choose Date</option>';
		for($i = 1;$i<=31;$i++){
		if($date_of_yearly==$i) {$selected = "selected";}else{$selected = '';}
		$html .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		if($month_of_yearly=="01"){$jan_sel = "selected";}else{$jan_sel = "";}
		if($month_of_yearly=="02"){$feb_sel = "selected";}else{$feb_sel = "";}
		if($month_of_yearly=="03"){$mar_sel = "selected";}else{$mar_sel = "";}
		if($month_of_yearly=="04"){$apr_sel = "selected";}else{$apr_sel = "";}
		if($month_of_yearly=="05"){$may_sel = "selected";}else{$may_sel = "";}
		if($month_of_yearly=="06"){$jun_sel = "selected";}else{$jun_sel = "";}
		if($month_of_yearly=="07"){$jul_sel = "selected";}else{$jul_sel = "";}
		if($month_of_yearly=="08"){$aug_sel = "selected";}else{$aug_sel = "";}
		if($month_of_yearly=="09"){$sep_sel = "selected";}else{$sep_sel = "";}
		if($month_of_yearly=="10"){$oct_sel = "selected";}else{$oct_sel = "";}
		if($month_of_yearly=="11"){$nov_sel = "selected";}else{$nov_sel = "";}
		if($month_of_yearly=="12"){$dec_sel = "selected";}else{$dec_sel = "";}
		$html .= '</select></div><div class="col-lg-6 col-md-6 col-sm-12"><select required name="month_of_yearly" id="month_of_yearly" class="form-control"><option value="">Choose Month</option>';
		$html .= '<option '.$jan_sel.' value="01">January</option><option '.$feb_sel.' value="02">February</option><option '.$mar_sel.' value="03">March</option><option '.$apr_sel.' value="04">April</option><option '.$may_sel.' value="05">May</option><option '.$jun_sel.' value="06">June</option><option '.$jul_sel.' value="07">July</option><option '.$aug_sel.' value="08">August</option><option '.$sep_sel.' value="09">September</option><option '.$oct_sel.' value="10">October</option><option '.$nov_sel.' value="11">November</option><option '.$dec_sel.' value="12">December</option></select></div>';
		return $html;
		}

		else if($recurring_type==2)
		{
		$html = '';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-12"><select required name="date_of_quaterly" class="form-control" id="date_of_quaterly"><option value="">Choose Date</option>';
		for($i = 1;$i<=31;$i++){
		if($date_of_quaterly==$i) {$selected = "selected";}else{$selected = '';}
		$html .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		if($month_of_quaterly=="01/04/07/10"){$jan_apr_sel = "selected";}else{$jan_apr_sel = "";}
		if($month_of_quaterly=="02/05/08/11"){$feb_may_sel = "selected";}else{$feb_may_sel = "";}
		if($month_of_quaterly=="03/06/09/12"){$mar_jun_sel = "selected";}else{$mar_jun_sel = "";}
		$html .= '</select></div><div class="col-lg-6 col-md-6 col-sm-12"><select required name="month_of_quaterly" id="month_of_quaterly" class="form-control"><option value="">Choose Month</option>';
		$html .= '<option '.$jan_apr_sel.' value="01/04/07/10">Jan/Apr/Jul/Oct</option><option '.$feb_may_sel.' value="02/05/08/11">Feb/May/Aug/Nov</option><option '.$mar_jun_sel.' value="03/06/09/12">Mar/Jun/Sep/Dec</option></select></div>';
		return $html;
		}

		else if($recurring_type==3)
		{
		$html = '';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-12"><select required name="date_of_month" class="form-control" id="date_of_month"><option value="">Choose Date</option>';
		for($i = 1;$i<=31;$i++){
		if($date_of_month==$i) {$selected = "selected";}else{$selected = '';}
		$html .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		$html .= '</select></div>';
		return $html;
		}

		else if($recurring_type==4)
		{
		$html = '';

		if($date_of_forthnigthly=="1"){$d_f_m_sel = "selected";}else{$d_f_m_sel = "";}
		if($date_of_forthnigthly=="2"){$d_f_t_sel = "selected";}else{$d_f_t_sel = "";}
		if($date_of_forthnigthly=="3"){$d_f_w_sel = "selected";}else{$d_f_w_sel = "";}
		if($date_of_forthnigthly=="4"){$d_f_th_sel = "selected";}else{$d_f_th_sel = "";}
		if($date_of_forthnigthly=="5"){$d_f_f_sel = "selected";}else{$d_f_f_sel = "";}
		if($date_of_forthnigthly=="6"){$d_f_sa_sel = "selected";}else{$d_f_sa_sel = "";}
		if($date_of_forthnigthly=="0"){$d_f_su_sel = "selected";}else{$d_f_su_sel = "";}

		$html .= '<div class="col-lg-6 col-md-6 col-sm-12"><select required name="date_of_forthnigthly" class="form-control" id="date_of_fortnightly"><option value="">Choose Day</option>';
		$html .= '<option '.$d_f_m_sel.' value="1">Monday</option><option '.$d_f_t_sel.' value="2">Tuesday</option><option '.$d_f_w_sel.' value="3">Wednesday</option><option '.$d_f_th_sel.' value="4">Thursday</option><option '.$d_f_f_sel.' value="5">Friday</option><option '.$d_f_sa_sel.' value="6">Saturday</option><option '.$d_f_su_sel.' value="0">Sunday</option>';
		$html .= '</select></div>';
		return $html;
		}

		else if($recurring_type==5)
		{
		if($day_of_week=="Monday"){$d_f_m_sel = "selected";}else{$d_f_m_sel = "";}
		if($day_of_week=="Tuesday"){$d_f_t_sel = "selected";}else{$d_f_t_sel = "";}
		if($day_of_week=="Wednesday"){$d_f_w_sel = "selected";}else{$d_f_w_sel = "";}
		if($day_of_week=="Thursday"){$d_f_th_sel = "selected";}else{$d_f_th_sel = "";}
		if($day_of_week=="Friday"){$d_f_f_sel = "selected";}else{$d_f_f_sel = "";}
		if($day_of_week=="Saturday"){$d_f_sa_sel = "selected";}else{$d_f_sa_sel = "";}
		if($day_of_week=="Sunday"){$d_f_su_sel = "selected";}else{$d_f_su_sel = "";}

		$html = '';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-12"><select required name="day_of_week" class="form-control" id="day_of_week"><option value="">Choose Day</option>';
		$html .= '<option '.$d_f_m_sel.' value="Monday">Monday</option><option '.$d_f_t_sel.' value="Tuesday">Tuesday</option><option '.$d_f_w_sel.' value="Wednesday">Wednesday</option><option '.$d_f_th_sel.' value="Thursday">Thursday</option><option '.$d_f_f_sel.' value="Friday">Friday</option><option '.$d_f_sa_sel.' value="Saturday">Saturday</option><option '.$d_f_su_sel.' value="Sunday">Sunday</option>';
		$html .= '</select></div>';
		return $html;
		}else{
		return '';	
		}
	}
}


if(!function_exists('getItemNameData'))
{
	function getItemNameData($id){
	    if($id!=0)
		{
		$object = DB::table('items')->where('id', '=', $id)->first();
		$data = json_decode(json_encode($object), true);
		if($data!=''){
			$html = '';
			if($data['vendor_sku']==''){$html .='Vendor SKU';}
			if($data['name']==''){$html .='Item Name,';}
			if($data['hsn_code']==''){$html .='HSN Code,';}
			if($data['grade']==''){$html .='Grade,';}
			if($data['brand']==''){$html .='Brand,';}
			if($data['packing_name']==''){$html .='Packing Name,';}
			if($data['list_price']==''){$html .='List Price,';}
			if($data['mrp']==''){$html .='MRP';}
			$html_sc = rtrim($html,',');
			$res = $html_sc;
		}else{
			$res = '';
		}
		return $res;
		}else{
		return $res='';
		}		
	}
}



/************ HV Project Function End ******************/