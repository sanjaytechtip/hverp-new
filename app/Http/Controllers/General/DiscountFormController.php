<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use DB;
use Session;
use PDF;
use Route;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class DiscountFormController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

	
    
	 
    public function create() {
        userPermission(Route::current()->getName());
		return view('general.discountform.discountformcreate');
    }
    
    public function discountlist(Request $req) {
        //userPermission(Route::current()->getName());
        if(!$_POST){
		$data = DB::table('tbl_discount')->orderBy('id', 'DESC')->paginate(50);
		$customer_name =''; 
		$brand_name = '';
        }else{
        $query = DB::table('tbl_discount');
        if($req->customer_name!=''){
        $query->where('customer_id',$req->customer_name);  
        $customer_name =$req->customer_name; 
        }
        if($req->brand_name!=''){
        $query->where('brand_name', 'LIKE','%'.$req->brand_name.'%');  
        $brand_name =$req->brand_name; 
        }
        $data =$query->orderBy('id', 'DESC')->paginate(50);
        }
		
		//pr($data); die;
		return view('general.discountform.discountlist',['datas'=>$data,'customer_name'=>$customer_name,'brand_name'=>$brand_name]);
    }
    
    public function delete_discount(Request $req, $id)
    {
     //userPermission(Route::current()->getName());
     DB::table('tbl_discount')->where('id',$id)->delete();
     return redirect('admin/discountlist')->with('success', 'Discount deleted Successfully.');   
    }
    
    public function edit_discount(Request $request, $id) {
        userPermission(Route::current()->getName());
		if(!$_POST){
			$row = DB::table('tbl_add_task')->where('_id',$id)->get()->first(); 
			/* //pr($row['data_status']); die; */
			return view('general.discountform.discountedit',['data'=>$row]);
		}else{
			$updateData = array(
				"department_name"   =>      $request->department_name,
				"customer_name"     =>      $request->customer_name,
				"subject_name"      =>      addslashes($request->subject_name),
				"date_time"         =>      date('Y-m-d H:i:s',strtotime($request->get('date_time'))),
				"category_name"     =>      $request->category_name,
				"user_name"         =>      $request->user_name
			);
			DB::table('tbl_add_task')->where('id',$id)->update($updateData);
			return redirect('admin/discountlist')->with('success', 'Task form updated Successfully.');
		}
    }
    
    public function add_discount_form(Request $request)
    {
        
                    if($request->option==1 || $request->option==2){
                    if($request->customer_name==''){
                        $customer_name = 0;
                    }else{
                        $customer_name = $request->customer_name;
                    }
                    $insertData = array(
                    "option"            =>      $request->option,
                    "customer_id"       =>      $customer_name,
                    "customer_name"     =>      getCustomerNamefromId($customer_name),
                    "brand_id"          =>      $request->brand,
                    "brand_name"        =>      getBrandName($request->brand),
                    "valid_till"        =>      date('Y-m-d',strtotime($request->get('valid_till'))),
                    "discount"          =>      $request->discount,
					"discount_by"		=>      $request->discount_by
                    );
                    //pr($insertData); die;
                    $insert = DB::table('tbl_discount')->insert(array($insertData));
                    }else{
                    $get_group_details = getGroupDetails($request->group_name);
					$data = explode(',',$get_group_details['g_company_name']);
                    foreach($data as $customer_name)
                    {
                    $insertData = array(
                    "option"            =>      "2",
                    "customer_id"       =>      $customer_name,
                    "customer_name"     =>      getCustomerNamefromId($customer_name),
                    "brand_id"          =>      $request->brand,
                    "brand_name"        =>      getBrandName($request->brand),
                    "valid_till"        =>      date('Y-m-d',strtotime($request->get('valid_till'))),
                    "discount"          =>      $request->discount,
					"discount_by"		=>      $request->discount_by
                    );
                    //pr($insertData); die;
                    $select_query = DB::table('tbl_discount')->where('customer_id',$customer_name)->where('brand_id',$request->brand)->count();
                    if($select_query<=0){
                    $insert = DB::table('tbl_discount')->insert(array($insertData)); 
                    }else{
                    $update = DB::table('tbl_discount')->where('customer_id',$customer_name)->where('brand_id',$request->brand)->update($insertData);    
                    }
                    }
                    }
                    return redirect('admin/discountlist')->with('success', 'Discount added Successfully.');
                    
    }
    
    public function ajax_insert_task_into_fms(Request $request)
    {
       $inq_id = $request->id;
       $inq_data = getInqDataByInqId($inq_id);
       $data_inserted = 'no';
		if(array_key_exists('data_status', $inq_data) && $inq_data['data_status']==1){
			echo $data_inserted = 'yes';
			exit;
		}
		$fms_id = '61bc2c6ad0490460690d5d92';
		if($inq_id==''){
            $result = array(
            'status' =>false,
            'description' => 'inq_id is empty'
            );
		}else{
		    $fms_data = DB::table('fmss')->where('_id',$fms_id)->get()->toArray();
			$fms_data_tbl = $fms_data[0]['fms_table'];
			$fms_type = $fms_data[0]['fms_type'];
			
			$inq_data = DB::table('tbl_add_task')->where('_id',$inq_id)->get()->toArray();
			$stepkArr = [];
			$stepsIds = get_step_id_by_fms_id($fms_id);
			$data = array();
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
								}else if($s_plane_dt['fms_when_type']==3 && $s_plane_dt['fms_when']['input_type']=='task_status'){
									$data[$step] = array(
														'task_status'=>''
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
							}
			
			$ticket_number = $inq_data[0]['ticket_number'];
			$date_time = $inq_data[0]['date_time'];
			$department_name = $inq_data[0]['department_name'];
			$customer_name = $inq_data[0]['customer_name'];
			$subject_name = $inq_data[0]['subject_name'];
			$category_name = $inq_data[0]['category_name'];
			$user_name     = $inq_data[0]['user_name'];
			$data['ticket_number']      = $ticket_number;
			$data['date_time']          = $date_time;
			$data['department_name']    = $department_name;
			$data['customer_name']      = $customer_name;
			$data['subject_name']       = $subject_name;
			$data['category_name']      = $category_name;
			$data['user_name']          = $user_name;
			$data['fms_id']             = $fms_id;
			$data['pi_id']              = $inq_id;
			$inserted = DB::table($fms_data_tbl)->insertGetId($data);
            if($inserted)
            {
            DB::table('tbl_add_task')->where('_id',$inq_id)->update(['data_status' => 1]);
            $result = array(
            'status' =>true,
            'msg' => 'TASK data inserted into FMS'
            );
            }else{
            $result = array(
            'status' =>false,
            'msg' => 'TASK data not inserted into FMS'
            );
            }
		    return json_encode($result);
		    die;
		}
		
    }
	
    
    public function discount_data_update(Request $request) {
            //($request->all()); die;
            $updated_on = date('Y-m-d H:i:s');
			$updateData = array(
				"valid_till"         	=>      date('Y-m-d',strtotime($request->get('date_rate'))),
				"discount"          	=>      $request->net_rate,
				"discount_by"        	=>      $request->discount_by
			);
			DB::table('tbl_discount')->where('id',$request->id)->update($updateData);
			if($request->discount_by==1){
				$discount_by = 'List Price';
			}else{
				$discount_by = 'MRP';
			}
			$arr = array('till_date' => globalDateformatNet(date('Y-m-d',strtotime($request->get('date_rate')))),'net_rate' => $request->net_rate,'updated_on' => globalDateformatNet($updated_on),'discount_by'=>$discount_by);
			return json_encode($arr);
    }

	public function get_purchase_discount_data(Request $request)
	{
		if($request->brand_id!=''){
		$valid_date = date('Y-m-d',strtotime($request->get('valid')));
		$object = DB::table('tbl_purchase_discount')->where('brand_id',$request->brand_id)->orderBy('id','DESC')->first();
		$da = json_decode(json_encode($object), true);
		return $da['discount'];
		}else{
		return "";	
		}
	}

	
	public function get_purchase_net_rate_data(Request $request)
	{
		$object = DB::table('tbl_purchase_netrate');
		if($request->product_id!=''){
		$object->where('product_id',$request->product_id);
		}
		if($request->customer_name!=''){
		$object->where('customer_id',$request->customer_name);
		}
		$query = $object->orderBy('id','DESC')->first();
		$da = json_decode(json_encode($query), true);
		if($da['net_rate']!='')
		{
		return $da['net_rate'];
		}else{
		return "";	
		}
		
	}

	
}