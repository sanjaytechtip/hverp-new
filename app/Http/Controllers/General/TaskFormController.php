<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\PurchaseInvoice;
use DB;
use Session;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class TaskFormController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

	
    
	 
    public function create() {
		return view('general.taskform.taskformcreate');
    }
    
    
    public function create_multiple() {
		return view('general.taskform.taskformcreate_multiple');
    }
    
    public function tasklist(Request $req) {
        if(!$_POST){
		$data = DB::table('tbl_add_task')->orderBy('_id', 'DESC')->paginate(5);
		$tbl_column = '';
		$i_name = '';
        }else{
        $data = DB::table('tbl_add_task')->where($req->tbl_column, 'like', '%'.$req->i_name.'%')->orderBy('_id', 'DESC')->paginate(5); 
        $tbl_column = $req->tbl_column;
		$i_name = $req->i_name;
        }
		//pr($data); die;
		return view('general.taskform.tasklist',['datas'=>$data,'tbl_column'=>$tbl_column,'i_name'=>$i_name]);
    }
    
    public function delete_task(Request $req, $id)
    {
     DB::table('tbl_add_task')->where('_id',$id)->delete();
     return redirect('admin/tasklist')->with('success', 'Task form deleted Successfully.');   
    }
    
    public function edit_task(Request $request, $id)
    {
		if(!$_POST){
		 $row = DB::table('tbl_add_task')->where('_id',$id)->get()->first(); 
		 //pr($row['data_status']); die;
		 return view('general.taskform.taskedit',['data'=>$row]);
		}else{
			$updateData = array(
						"department_name"   =>      $request->department_name,
						"customer_name"     =>      $request->customer_name,
						"subject_name"      =>      addslashes($request->subject_name),
						"date_time"         =>      date('Y-m-d H:i:s',strtotime($request->get('date_time'))),
						"category_name"     =>      $request->category_name,
						"user_name"         =>      $request->user_name
						);
						DB::table('tbl_add_task')->where('_id',$id)->update($updateData);
						return redirect('admin/tasklist')->with('success', 'Task form updated Successfully.');
		}
    }
    
    public function ajax_search_ticket_number(Request $request)
    {
        $slect_deparment = DB::table('tbl_department')->where('id',$request->dept_id)->get()->first();
        $table_name = 'tbl_tasks_email';
        $select_dept = DB::table($table_name)->select('ticket_number')->where('department_name',$request->dept_id)->where('status',"0")->get()->toArray();
        foreach($select_dept as $row){
        $id = $row->id;
        $resArr[$row->ticket_number]= addslashes(str_replace("'","",$row->ticket_number));
        }
        return json_encode($resArr);
    }
    
    
    public function ajax_search_task_category(Request $request)
    {
        $select_dept = DB::table('task_categories')->where('department',$request->dept_id)->get()->toArray();
        foreach($select_dept as $row){
        $id = $row->id; 
        $resArr[$id]= addslashes(str_replace("'","",$row->task_category));
        }
        return json_encode($resArr);
    }
    
    public function add_task_form(Request $request)
    {
                   // pr($request->all()); die;
                    if($request->task==1){
                    $rows_data = DB::table('tbl_config')->get()->first();
                    $ticket_number = $rows_data->row+1;
                    //$mid = nextAutoincrementId('tbl_add_task','mid');
                    $slect_deparment = DB::table('tbl_department')->where('id',$request->department_name)->get()->first();
                    $table_name = 'tbl_tasks_email';
                    
                    $insertDataDepartment = array(
													"department_name"   =>      $request->department_name,
													"customer_name"     =>      $request->customer_name,
													"subject_name"      =>      addslashes($request->subject_name),
													"date_time"         =>      date('Y-m-d H:i:s',strtotime($request->date_time)),
													"category_name"     =>      $request->category_name,
													"ticket_number"     =>      'HV'.$ticket_number,
													"user_name"         =>      $request->user_name,
													"user_id"           =>      Auth::user()->id,
													"status"            =>      "0",
													"replies_status"    =>      "999",
													"task_notes"        =>      addslashes($request->task_notes),
													"created_at"        =>      date('Y-m-d H:i:s')
												);
                    
							//pr($insertDataDepartment); die;
							$insId = DB::table($table_name)->insertGetId($insertDataDepartment);
							DB::table('tbl_config')->update(array('row'=>$ticket_number));
							
							$repArr = array(
											"fms_data_id"=>$insId,
											"type"=>0,
											"planned"=>date('Y-m-d H:i:s'),
											"user_id"=>Auth::user()->id,
											"difference"=>0,
											"created_at"=>date('Y-m-d H:i:s'),
											"updated_at"=>date('Y-m-d H:i:s'),
										);
							
							$repId = insertFmsreplyData($repArr);
							
                    }else{
							
							$table_name = 'tbl_tasks_email';
							$select_data = DB::table($table_name)->where('ticket_number',$request->ticket_number)->where('department_name',$request->department_name)->get()->first();
							
							$fms_data_id = $select_data->id;
							
							$replies_arr = array("replies_status"    =>      "999");
							
							DB::table($table_name)->where('ticket_number',$request->ticket_number)->where('department_name',$request->department_name)->update($replies_arr);
							
							$repArr = array(
											"fms_data_id"=>$fms_data_id,
											"type"=>0,
											"planned"=>date('Y-m-d H:i:s', strtotime($request->date_time)),
											"user_id"=>Auth::user()->id,
											"difference"=>0,
											"created_at"=>date('Y-m-d H:i:s'),
											"updated_at"=>date('Y-m-d H:i:s')
										);
							//pr($repArr); die;
							$repId = insertFmsreplyData($repArr);
                    }
                    return redirect('admin/task-form-create')->with('success', 'Task form added Successfully.');
    }
    
    public function add_task_form_multiple(Request $request)
    {
                   
                    //pr($request->row); die; 
                    foreach($request->row as $data)
                    {
						if($data['task']==1){
							$rows_data = DB::table('tbl_config')->get()->first();
							$ticket_number = $rows_data->row+1;
							//$mid = nextAutoincrementId('tbl_add_task','mid');
							$slect_deparment = DB::table('tbl_department')->where('id',$data['department_name'])->get()->first();
							$table_name = 'tbl_tasks_email';
							
							
							$insertDataDepartment = array(
															"department_name"   =>      $data['department_name'],
															"customer_name"     =>      $data['customer_name'],
															"subject_name"      =>      addslashes($data['subject_name']),
															"date_time"         =>      date('Y-m-d H:i:s',strtotime($data['date_time'])),
															"category_name"     =>      $data['category_name'],
															"ticket_number"     =>      'HV'.$ticket_number,
															"user_name"         =>      $data['user_name'],
															"user_id"           =>      Auth::user()->id,
															"status"            =>      "0",
															"replies_status"    =>      "999",
															"task_notes"        =>      addslashes($data['task_notes']),
															"created_at"        =>      date('Y-m-d H:i:s'),
														);
							
							//DB::table($table_name)->insert($insertDataDepartment);
							$insId = DB::table($table_name)->insertGetId($insertDataDepartment);
							
							$repArr = array(
											"fms_data_id"=>$insId,
											"type"=>0,
											"planned"=>date('Y-m-d H:i:s'),
											"user_id"=>Auth::user()->id,
											"difference"=>0,
											"created_at"=>date('Y-m-d H:i:s'),
											"updated_at"=>date('Y-m-d H:i:s'),
										);
							
							$repId = insertFmsreplyData($repArr);
							
							//$insert = DB::table('tbl_add_task')->insert(array($insertData));
							DB::table('tbl_config')->update(array('row'=>$ticket_number));
						}else{
							//pr($data);
							$table_name = 'tbl_tasks_email';
							$select_data = DB::table($table_name)->where('ticket_number',$data['ticket_number'])->where('department_name',$data['department_name'])->get()->first();
							//pr($select_data);
							//$previous_arr = (array) $select_data->replies;
							
							$fms_data_id = $select_data->id;
		
							/* $new_arr = json_encode(array(array("type"=>"","planned"=>date('Y-m-d H:i:s', strtotime($data['date_time'])),"actual"=>"","user_id"=>Auth::user()->id,"difference"=>""))); */

							$row = array("replies_status"=>"999");
							
							DB::table($table_name)->where('ticket_number',$data['ticket_number'])->where('department_name',$data['department_name'])->update($row);
							
							$repArr = array(
											"fms_data_id"=>$fms_data_id,
											"type"=>0,
											"planned"=>date('Y-m-d H:i:s', strtotime($data['date_time'])),
											"user_id"=>Auth::user()->id,
											"difference"=>0,
											"created_at"=>date('Y-m-d H:i:s'),
											"updated_at"=>date('Y-m-d H:i:s'),
										);
							
							$repId = insertFmsreplyData($repArr);
							
						}
                    }
                    return redirect('admin/task-form-create-multiple')->with('success', 'Task form added Successfully.');
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
			
			//$inq_data = DB::table('tbl_add_task')->where('_id',$inq_id)->get()->toArray();
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