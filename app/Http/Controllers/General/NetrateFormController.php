<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use App\Item;
use DB;
use Session;
use PDF;
use Route;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class NetrateFormController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

	
    
	 
    public function create() {
        //userPermission(Route::current()->getName());
		$object= DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->limit(10)->get()->toArray();
        $itemlist = json_decode(json_encode($object), true);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->count();
        //pr($itemlist); die;
		return view('general.netrateform.netrateformcreate',['datalist'=>$itemlist,'itemlist_count'=>$itemlist_count]);
    }
    
    public function netratelist(Request $req) {
        userPermission(Route::current()->getName());
        if(!$_POST){
		$data = DB::table('tbl_netrate')->orderBy('id', 'DESC')->paginate(50);
		$customer_name ='';
		$product_name = '';
        }else{
            //pr($req->all()); die;
            $query = DB::table('tbl_netrate');
            if($req->customer_name!=''){
            $query->where('customer_id',$req->customer_name);  
            $customer_name =$req->customer_name; 
            }
            if($req->product_name!=''){
            $query->where('product_name', 'LIKE','%'.$req->product_name.'%');  
            $product_name =$req->product_name; 
            }
            $data =$query->orderBy('id', 'DESC')->paginate(50);
        }
		//pr($data); die;
		return view('general.netrateform.netratelist',['datas'=>$data,'product_name'=>$product_name,'customer_name'=>$customer_name]);
    }
    
    public function net_rate_search(Request $request)
    {
       
            $query = DB::table('items');
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
			$object =$query->orderBy('id', 'DESC')->paginate(10);
            $data_list = json_decode(json_encode($object), true);
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
            if(!empty($data_list['data']))
            {
            foreach($data_list['data'] as $user)
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
    
    public function delete_netrate(Request $req, $id)
    {
     //userPermission(Route::current()->getName());
     DB::table('tbl_netrate')->where('id',$id)->delete();
     return redirect('admin/netratelist')->with('success', 'Net Rate deleted Successfully.');   
    }
    
    public function netrate_data_update(Request $request) {
            $updated_on = date('Y-m-d H:i:s');
			$updateData = array(
				"date_rate"         =>      date('Y-m-d',strtotime($request->get('date_rate'))),
				"net_rate"          =>      $request->net_rate
			);
			DB::table('tbl_netrate')->where('id',$request->id)->update($updateData);
			$arr = array('till_date' => globalDateformatNet(date('Y-m-d',strtotime($request->get('date_rate')))),'net_rate' => $request->net_rate,'updated_on' => globalDateformatNet($updated_on));
			return json_encode($arr);
    }
    
    public function add_netrate_form(Request $request)
    {
        //pr($request->all()); die;
                    if($request->customer_name==''){
                        $customer_name = 0;
                    }else{
                        $customer_name = $request->customer_name;
                    }
                    $insertData = array(
                    "option"            =>      $request->option,
                    "customer_id"       =>      $customer_name,
                    "customer_name"     =>      getCustomerNamefromId($customer_name),
                    "product_id"        =>      $request->product_id,
                    "product_name"      =>      $request->product,
                    "date_rate"         =>      date('Y-m-d',strtotime($request->get('date_rate'))),
                    "net_rate"          =>      $request->net_rate
                    );
					//pr($insertData); die;
                    $insert = DB::table('tbl_netrate')->insert(array($insertData));
                    return redirect('admin/netrate-form-create')->with('success', 'Net Rate added Successfully.');
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
	
  

	
}