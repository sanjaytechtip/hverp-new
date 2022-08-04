<?php
namespace App\Http\Controllers\Fms;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



use DB;

use Auth;

class TaskFmsController extends Controller
{
	//protected $fms,$fmsstep,$fmstask;
	
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$fmslists = DB::table('tbl_department')->where('id','61c1e535d049043677667e26')->orderBy('department_name')->get()->toArray();
		//$fmslists = DB::table('tbl_department')->orderBy('department_name')->get()->toArray();
        return view('fms.taskfmslist', compact('fmslists'))->with('no', 1);
    }
	
	public function taskview(Request $req){
	    //pr($req->all()); die('====');
	    //echo auth::user()->id; die;
	    if(getUserFMSrole()==1){
	    $query = DB::table('tbl_tasks_email');
	    if(!empty($req->ticket_number))
	    {
	    $query->where('ticket_number','LIKE','%'.$req->ticket_number.'%');    
	    }
	    if(!empty($req->status))
	    {
	    if($req->status==99)
	    {
	        $status = 0;
	    }else{
	        $status = $req->status;
	    }
	    $query->where('status','LIKE','%'.$status.'%');    
	    }
	    if(!empty($req->subject_name))
	    {
	    $query->where('subject_name','LIKE','%'.$req->subject_name.'%');    
	    }
	    if(!empty($req->from_date) && !empty($req->to_date))
	    {
	    $from_date = date('Y-m-d',strtotime($req->from_date)); 
	    $to_date = date('Y-m-d',strtotime($req->to_date."+ 1 day"));
	    $query->where('date_time','>=',$from_date);
	    $query->where('date_time','<',$to_date);
	    }
	    if(!empty($req->from_date_create) && !empty($req->to_date_create))
	    {
	    $from_date = date('Y-m-d',strtotime($req->from_date_create)); 
	    $to_date = date('Y-m-d',strtotime($req->to_date_create."+ 1 day"));
	    $query->where('created_at','>=',$from_date);
	    $query->where('created_at','<',$to_date);
	    }
	    if(!empty($req->customer_name))
	    {
	    $query->whereIn('customer_name',$req->customer_name);
	    }
	    if(!empty($req->user_name))
	    {
	    $query->whereIn('user_name',$req->user_name);
	    }
	    if(!empty($req->item_category))
	    {
	    $query->whereIn('category_name',$req->item_category);
	    }
	    if(!empty($req->department))
	    {
	    $query->whereIn('department_name',$req->department);
	    }
	    if(!empty($req->task_notes))
	    {
	    $query->where('task_notes','LIKE','%'.$req->task_notes.'%');
	    }
	    if(empty($req->status))
	    {
	    $query->where('status','0');
	    }
	    if(!empty($req->replies_status))
	    {
	    $query->where('replies_status',$req->replies_status);
	    }
	    if(!empty($req->user_id))
	    {
	    $query->whereIn('user_id',$req->user_id);
	    }
	    $query->orderBy('id','DESC');
	    $dept=$query->paginate(100);
	    }else{
			
	    $query = DB::table('tbl_tasks_email');
	    //$query->where('department_name',$id);
	    if(!empty($req->ticket_number))
	    {
	    $query->where('ticket_number','LIKE','%'.$req->ticket_number.'%');    
	    }
	    if(!empty($req->status))
	    {
	    if($req->status==99)
	    {
	        $status = 0;
	    }else{
	        $status = $req->status;
	    }
	    $query->where('status','LIKE','%'.$status.'%');    
	    }
	    if(!empty($req->subject_name))
	    {
	    $query->where('subject_name','LIKE','%'.$req->subject_name.'%');    
	    }
	    if(!empty($req->from_date) && !empty($req->to_date))
	    {
	    $from_date = date('Y-m-d',strtotime($req->from_date)); 
	    $to_date = date('Y-m-d',strtotime($req->to_date."+ 1 day"));
	    $query->where('date_time','>=',$from_date);
	    $query->where('date_time','<',$to_date);
	    }
	    if(!empty($req->from_date_create) && !empty($req->to_date_create))
	    {
	    $from_date = date('Y-m-d',strtotime($req->from_date_create)); 
	    $to_date = date('Y-m-d',strtotime($req->to_date_create."+ 1 day"));
	    $query->where('created_at','>=',$from_date);
	    $query->where('created_at','<',$to_date);
	    }
	    if(!empty($req->customer_name))
	    {
	    $query->whereIn('customer_name',$req->customer_name);
	    }
	    if(!empty($req->user_name))
	    {
	    $query->whereIn('user_name',$req->user_name);
	    }
	    if(!empty($req->item_category))
	    {
	    $query->whereIn('category_name',$req->item_category);
	    }
	    if(!empty($req->department))
	    {
	    $query->whereIn('department_name',$req->department);
	    }
	    if(!empty($req->task_notes))
	    {
	    $query->where('task_notes','LIKE','%'.$req->task_notes.'%');
	    }
	    $query->where('user_name',auth::user()->id);
	    if(empty($req->status))
	    {
	    $query->where('status','0');
	    }
	    if(!empty($req->user_id))
	    {
	    $query->whereIn('user_id',$req->user_id);
	    }
	    if(!empty($req->replies_status))
	    {
	    $query->where('replies_status',$req->replies_status);
	    }
	    $query->orderBy('id','DESC');
	    $dept=$query->paginate(100);
	    }
		//pr($dept); die;
	    $userlist = DB::table('users')->select('id','full_name')->where('status', '1')->where('user_role', '!=', '1')->orderBy('full_name','ASC')->get()->toArray();
		$userlist = json_decode(json_encode($userlist), true);
	    $assigned_by = DB::table('users')->select('id','full_name')->where('status', '1')->orderBy('full_name','ASC')->get()->toArray();
		$assigned_by = json_decode(json_encode($assigned_by), true);
	    //pr($assigned_by); die; task_categories
	    $companylist = DB::table('customers')->where('block_unblock', '=', '1')->orderBy('company_name','ASC')->get()->toArray();
		$companylist = json_decode(json_encode($companylist), true);
	    $item_categories = DB::table('task_categories')->orderBy('department','ASC')->get()->toArray();
		$item_categories = json_decode(json_encode($item_categories), true);
	    $departmentlist = DB::table('tbl_department')->orderBy('department_name','ASC')->get()->toArray();
		$departmentlist = json_decode(json_encode($departmentlist), true);
		//pr($departmentlist); die;
	    foreach($companylist as $row){
        //$oid = (array) $row['_id'];
        $id_cus = $row['id']; 
        $resArr[$id_cus]= $row['company_name'];
        }
        foreach($userlist as $row){
        //$oid = (array) $row['_id'];
        $id_user = $row['id']; 
        $resArr_us[$id_user]= $row['full_name'];
        }
        
        foreach($assigned_by as $row){
		 $assign_user = $row['id']; 
        $resArr_assign[$assign_user]= $row['full_name'];
        }
        
        foreach($item_categories as $row){
       
        $id_user = $row['id']; 
        $resArr_cat[$id_user]= $row['task_category'];
        }
         //pr($departmentlist); die;
        foreach($departmentlist as $row){
 
        $id_user = $row['id']; 
        $resArr_dept[$id_user]= $row['department_name'];
        }
		//$dept = json_decode(json_encode($dept), true);
       //pr($dept); die;
		return view('fms.taskfmslist_fmsdata',["data"=>$dept,'req'=>$req,'companylist'=>$companylist,'item_cat_arr'=>$resArr_cat,'userlist'=>$userlist,'userlist_arr'=>$resArr_us,'assigned_by_user'=>$assigned_by,'assigned_by_arr'=>$resArr_assign,'cuslist_arr'=>$resArr,'departmentlist'=>$departmentlist,'resArr_dept'=>$resArr_dept,'item_categories'=>$item_categories]);
	    
	} 
	
	
	function group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}
	
	public function ajax_date_update_data(Request $request)
	{
		//pr($_POST); die('hii');
	    $row = [];
	    $department_id = explode('-',$request->id_array);
	    $id = $department_id[0];
	    $array_key = $department_id[1]; 
		
	    $rep_data = DB::table('tbl_fms_data_reply')->where('fms_data_id',$id)->get()->toArray();
		//pr($rep_data);
		$reply = (array) $rep_data[$array_key]; 
		$rep_id = $reply['id'];
		$reply['user_id'] =Auth::user()->id ;
		$reply['planned'] = date('Y-m-d H:i:s',strtotime($request->date_update_db));
		$reply['updated_at'] = date('Y-m-d H:i:s');
		
		unset($reply['id']);
		unset($reply['actual']);
		//pr($reply); die('final');
		
        $up = DB::table('tbl_fms_data_reply')->where('id',$rep_id)->update($reply);
        echo 1;
	}
	
	
	public function ajax_insert_department_data(Request $request)
	{   
		//pr($_POST);
	    $department_id = explode('-',$request->department_id);
	    $id = $department_id[0];
	    $array_key = $department_id[1];
	    
        $table_name = 'tbl_tasks_email';
        $select_array = DB::table($table_name)->where('id',$id)->get()->first();
		
		//pr($select_array);
		$rep_data = DB::table('tbl_fms_data_reply')->where('fms_data_id',$id)->get()->toArray();
		//pr($rep_data); 
		$rep_arr = (array) $rep_data[$array_key];
		//pr($rep_arr); 
		
		$rep_id = $rep_arr['id'];
		$planned_date_time = $rep_arr['planned'];
		$actual_date_time = date('Y-m-d H:i:s');
		
		$difference = $this->datetimecal($planned_date_time,$actual_date_time);
		
		$rep_update_arr = array(
									'type'=>$request->type,
									'actual'=>date('Y-m-d H:i:s'),
									'user_id'=>Auth::user()->id,
									'difference'=>$difference,
								);
		DB::table('tbl_fms_data_reply')->where('id',$rep_id)->update($rep_update_arr);
		//pr($rep_update_arr); die;
		
        DB::table($table_name)->where('id',$id)->update(array('replies_status'=>"1"));
        if($difference<'2' && $request->type==1) 
        {$diff = 'greentext';} 
        else if($difference<'2' && $request->type==2) 
        {$diff = 'greentab';} 
        else if($difference>='2' && $request->type==1) 
        {$diff = 'redtext';} 
        else if($difference>='2' && $request->type==2) 
        {$diff = 'redtab';} 
        $ar = array('actual_date_time'=>globalDateformatFMSDate($actual_date_time),'diff'=>$diff);
        return json_encode($ar);
	}
	
	public function ajax_insert_department_data_old(Request $request)
	{   $row = [];
	    $department_id = explode('-',$request->department_id);
	    $id = $department_id[0];
	    $array_key = $department_id[1];
	    //$slect_deparment = DB::table('tbl_department')->where('id',$request->table_id)->get()->first();
		
        $table_name = 'tbl_tasks_email';
        $select_array = DB::table($table_name)->where('id',$id)->get()->first();
        $array_select = $select_array['replies'];
        $planned_date_time = $array_select[$array_key]['planned'];
        $actual_date_time = date('Y-m-d H:i:s');
        $difference = $this->datetimecal($planned_date_time,$actual_date_time);
        if(!empty($array_select))
        {
        $previous_arr = $array_select;
        }else{
        $previous_arr = array();    
        }
        $new_arr = array($array_key=>array('type'=>$request->type,"planned"=>$planned_date_time,"actual"=>$actual_date_time,"user_id"=>Auth::user()->id,"difference"=>$difference));
        $saved_array = array_replace($previous_arr,$new_arr);
        $row['replies'] =$saved_array; 
        // if($request->type==2)
        // {
        // $row['status'] = "1"; 
        // }
        //pr($saved_array); die;
        DB::table($table_name)->where('_id',$id)->update($row);
        DB::table($table_name)->where('_id',$id)->update(array('replies_status'=>"1"));
        if($difference<'2' && $request->type==1) 
        {$diff = 'greentext';} 
        else if($difference<'2' && $request->type==2) 
        {$diff = 'greentab';} 
        else if($difference>='2' && $request->type==1) 
        {$diff = 'redtext';} 
        else if($difference>='2' && $request->type==2) 
        {$diff = 'redtab';} 
        $ar = array('actual_date_time'=>globalDateformatFMSDate($actual_date_time),'diff'=>$diff);
        return json_encode($ar);
	}
	
	public function ajax_status_update(Request $request)
	{
	    //pr($request->all()); die;
	    //$slect_deparment = DB::table('tbl_department')->where('id',$request->table_id)->get()->first();
        $table_name = 'tbl_tasks_email';
	    $arr = array('status'=>$request->status_value_update);
	    DB::table($table_name)->where('id',$request->department_id_status)->update($arr);
	    echo $request->status_value_update;
	}
	
	
	public function ajax_status_update_reopen(Request $request)
	{
	    //pr($request->all()); die;
	    //$slect_deparment = DB::table('tbl_department')->where('_id',$request->table_id)->get()->first();
        $table_name = 'tbl_tasks_email';
	    $arr = array('status'=>"0");
	    DB::table($table_name)->where('id',$request->department_id)->update($arr);
	    echo 1;
	}
	
	function datetimecal($planned_date_time,$actual_date_time)
	{
	    $date1 = strtotime($planned_date_time);
  $date2 = strtotime($actual_date_time);
 
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
 
  // Print the result
  return $hours;
	}
	
	

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fms.createfms');
    }
	


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		Fms::where('_id', $id)->delete();
		Fmsstep::where('fms_id', $id)->delete();
		Fmstask::where('fms_id', $id)->delete();
		Fmsdata::where('fms_id', $id)->delete();
		return redirect()->route('fmslist')->with('success', 'FMS Deleted successfully.');
    }
	
	public function ajax_select_notes_data(Request $req)
	{
		$data = DB::table('tbl_tasks_email')->where('id',$req->id)->get()->first();
		$data = (array) $data;
		if(!empty($data['task_notes']))
		{
			return $data['task_notes'];
		}else{
			return '';
		}
	}
	
	public function ajax_update_notes_data(Request $req)
	{
      $arr = array('task_notes'=>addslashes($req->notes_value_update));
      DB::table('tbl_tasks_email')->where('id',$req->id)->update($arr);
	  echo 1; 
	}
	
}
