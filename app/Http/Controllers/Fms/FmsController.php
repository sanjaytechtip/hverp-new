<?php
namespace App\Http\Controllers\Fms;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Fms;
use App\Fmsstep;
use App\Fmstask;
use App\Fmsdata;

use DB;

use Auth;

class FmsController extends Controller
{
	protected $fms,$fmsstep,$fmstask;
	
	public function __construct()
	{
		$this->fms = new Fms();
		$this->fmsstep = new Fmsstep();
		$this->fmstask = new Fmstask();
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//$fmslists = $this->fms::all();
		$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		//$fmslists = $this->fms::all()->orderBy('_id','desc');
		//pr($fmslists); die; 
        return view('fms.fmslist', compact('fmslists'))->with('no', 1);
    }
	
	 
	
	public function mis($uid='')
    {
		
		$user = auth()->user();
		$user_role = $user->user_role;
		
		//echo $user_role; die;
		 if(($user_role==1 || $user_role==2) && $uid!=''){
			$uid = $uid;
		}elseif(($user_role!=1 || $user_role!=2)){
			$uid = Auth::user()->id;
		}
		
		$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		
		if($uid!=''){
			$mis_sum_bulk = DB::table('mis_bulk')->sum($uid);
		}else{
			$mis_sum_bulk = '';
		}
		
		//pr($mis_sum_bulk); echo $mis_sum_bulk; die;
        return view('fms.mis', compact('fmslists'))->with('no', 1)->with('mis_sum_bulk', $mis_sum_bulk)->with('crntUid', $uid);
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
	
	
	public function createsteps($fms_id)
	{
		
		/* $fabric_comments_lists = DB::table('dropdownlists')->select('_id', 'dropdown_name')->where('dropdown_name', 'Fabric Comments')->get()->toArray();		
		$custDp = '';
		foreach($fabric_comments_lists as $fabric){
			$custDp .= '<option value="'.$fabric['_id'].'">'.$fabric['dropdown_name'].'</option>';
		} */
		
		$builtOptionList = buildOptionsListStep();
		//pr($builtOptionList); die;
		//return view('fms.createsteps')->with('fms_id', $fms_id)->with('custom_dropdown', $custDp)->with('builtOptionList', $builtOptionList);
		return view('fms.createsteps')->with('fms_id', $fms_id)->with('builtOptionList', $builtOptionList);
	}
	
	public function storesteps(Request $request)
	{
		//echo "<pre>"; print_r($_POST); die;		
		$fms_id = $request->fms_id;		
		try{
			$i=1;
			$stepArr =array();
			foreach($request->row as $rkey=>$val)
			{
					$myArr = array();
					if($i==1)
					{
						if($val['fms_when_type']==1)
						{
							$myArr = array(
										'task_id_link'=>$val['task_id_link'],
										'after_task_time'=>$val['after_task_time']
									   );
						}elseif($val['fms_when_type']==4)
						{
							$myArr = array(
										'fms_when_type'=>$val['fms_when_type'],
										'input_type'=>$val['fms_when']
										);
						}else if($val['fms_when_type']==12){			
									$myArr = array(
													'task_id_link'=>$val['task_id_link'],
													'after_task_time'=>$val['after_task_time'],
													'fms_when_type'=>$val['fms_when_type'],
													'dropdown'=>$val['dropdown'],
												);
												
						}else{
							$myArr = array(
										'fms_when_type'=>$val['fms_when_type'],
										'input_type'=>$val['fms_when']
										);
						}
						
						$dataArr = array(
										'fms_id'=>$fms_id,
										'order_no'=>(int)$rkey,
										'step'=>$rkey,
										'fms_what'=>$val['fms_what'],
										'fms_who'=>$val['fms_who'],
										'fms_when_type'=>$val['fms_when_type'],
										'fms_when'=>$myArr,
										'fms_stage'=>$val['fms_stage'],
										'user_right'=>''
									);
					
					
						//echo "<pre>"; print_r($dataArr);  die;  
					
						$Fmsstep = $this->fmsstep::create($dataArr);
						
						$stepArr['step'.$i]=$Fmsstep->_id;
						
					}else{
						if(!empty($val['fms_when']) && ($val['fms_when']=='custom_date' || $val['fms_when']=='notes' || $val['fms_when']=='none'))
						{
							$myArr = array(
											'input_type'=>$val['fms_when'],
											'fms_when_type'=>$val['fms_when_type']
										);
										
						}else{
								if($val['fms_when_type']==1)
								{
									$myArr = array(
												'task_id_link'=>$val['task_id_link'],
												'after_task_time'=>$val['after_task_time']
											   );
								}else if($val['fms_when_type']==12){
									/* $myArr = array(
														'after_step_id'=>$stepArr[$val['fms_when']],
														'time_hr'=>$val['fms_when_val'],
														'fms_when_type'=>$val['fms_when_type']
													); */
													
									$myArr = array(
													'task_id_link'=>$val['task_id_link'],
													'after_task_time'=>$val['after_task_time'],
													'fms_when_type'=>$val['fms_when_type'],
													'dropdown'=>$val['dropdown'],
												);
												
								}else if($val['fms_when_type']==13){				
									$myArr = array(
													'fms_when_type'=>$val['fms_when_type'],
													'dropdownlist'=>$val['fms_when'],
												);
												
								}else if($val['fms_when_type']==3){
									$myArr = array(
										'after_step_id'=>$stepArr[$val['fms_when']],
										'time_hr'=>$val['fms_when_val'],
										'fms_when_type'=>$val['fms_when_type']
										);
								}else if($val['fms_when_type']==6){
									$myArr = array(
											'input_type'=>$val['fms_when'],
											'fms_when_type'=>$val['fms_when_type']
										);
								}else if($val['fms_when_type']==8){
									$myArr = array(
											'input_type'=>$val['fms_when'],
											'fms_when_type'=>$val['fms_when_type']
										);
								}else if($val['fms_when_type']==7){
									$myArr = array(
											'input_type'=>$val['fms_when'],
											'fms_when_type'=>$val['fms_when_type']
										);
								}else if($val['fms_when_type']==9){
									$myArr = array(
												'task_id_link'=>$val['task_id_link'],
												'before_etd_time'=>$val['before_etd_time']
											   );
								}else if($val['fms_when_type']==11){
									$myArr = array(
													'input_type'=>'date_with_dropdown',
													'after_step_id'=>$stepArr[$val['fms_when']],
													'time_hr'=>$val['fms_when_val'],
													'fms_when_type'=>$val['fms_when_type'],
													'dropdown'=>$val['dropdown'],
												);
								}elseif($val['fms_when_type']==14){
									$myArr = array(
													'fms_when_type'=>$val['fms_when_type'],
													'input_type'=>$val['fms_when']
												);
								}elseif($val['fms_when_type']==15){
									$myArr = array(
										'fms_when_type'=>$val['fms_when_type'],
										'input_type'=>$val['fms_when']
										);
								}else{
										$myArr = array(
													'fms_when_type'=>$val['fms_when_type'],
													'input_type'=>$val['fms_when']
													);
									}
								
							
						}						
						
						if($val['fms_when_type']==1)
						{
							$myArr = array(
											'task_id_link'=>$val['task_id_link'],
											'after_task_time'=>$val['after_task_time']
										);
						}
						
						$dataArr = array(
										'fms_id'=>$fms_id,
										'order_no'=>(int)$rkey,
										'step'=>$rkey,
										'fms_what'=>$val['fms_what'],
										'fms_who'=>$val['fms_who'],
										'fms_when_type'=>$val['fms_when_type'],
										'fms_when'=>$myArr,
										'fms_stage'=>$val['fms_stage'],
										'user_right'=>''
									);
									
						//echo "<pre> 2222"; print_r($dataArr);  die;						
						$Fmsstep = $this->fmsstep::create($dataArr);
						$stepArr['step'.$i]=$Fmsstep->_id;					
					}
					
				$i++;
			}
			if($Fmsstep)
			{
				return redirect()->route('fmslist')->with('success','Steps created successfully');
			}else{
				return redirect()->route('fmslist')->with('success', 'Steps not created.');
			}
		}catch(Exception $ex){
			DB::rollback();
		}
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		//echo "<pre>"; print_r($_POST); die;
		$this->validate($request, array(
			'fms_name'=>'required|unique:fmss|max:255'
		));
		
		try{
			
			$fmsName = strtolower($request->fms_name);
			$fmsTable =  str_replace(' ', '_', $fmsName).'_data';
			
			$fms = $this->fms::create([
				'fms_name'=>$request->fms_name,
				'fms_type'=>$request->fms_type,
				'fms_table'=>$fmsTable
			]); 
			
			Schema::create($fmsTable);			
			DB::table($fmsTable)->insert(
				['test' => 'test data']
			);
			
			
			$i=1;
			$stepArr =array();
			foreach($request->row as $rkey=>$val)
			{
				
				$fmstask = $this->fmstask::create([
					'fms_id'=>$fms->_id,
					'order_no'=>(int)$val['step'],
					'task_name'=>$val['task_name'],
					'input_type'=>$val['input_type'],
					'field_type'=>$val['field_type']
					]);
				
				$i++;
			}
			
			if($fmstask)
			{
				$data = array(
							'success'=>'FMS created successfully. ',
							'fms_id'=>$fms->_id
							);
				
				DB::commit();
				//return redirect()->route('fmslist')->with($data);
				return redirect()->route('createfms')->with('success', 'FMS created successfully.');
			}else{
				DB::rollback();
				return redirect()->route('createfms')->with('success', 'FMS not creates.');
			}
		}catch(Exception $ex){
			DB::rollback();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		
        //$fmsdata = Fms::where('_id', '=', $id)->get()->toArray();
		$fmsdata = Fms::find($id);
        $steps_rows = Fmsstep::where('fms_id', '=', $id)->get();
		//echo "<pre>"; print_r($fmsdata); die;
        return view('fms.fms_edit', compact('fmsdata', 'steps_rows', 'id'))->with('no', 1);
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
		echo "<pre>";
		print_r($request->row);
		return "kkkkkk".$id;
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
	
	/**
		copy specified resource from storage
	**/
	public function fmscopy($id)
	{
		$fms = Fms::find($id)->get()->toArray();
        $fmsstep = Fmsstep::where('fms_id', '=', $id)->get()->toArray();
        $fmstask = Fmstask::where('fms_id', '=', $id)->get()->toArray();
        $fmsdata = Fmsdata::where('fms_id', '=', $id)->get()->toArray();
		
		echo "<pre>fms==>>"; print_r($fms);     echo "</pre>";
		echo "<pre>fmsstep==>>"; print_r($fmsstep); echo "</pre>";
		echo "<pre>fmstask==>>"; print_r($fmstask); echo "</pre>";
		echo "<pre>fmsdata====>>>"; print_r($fmsdata); echo "</pre>";
		die;
		return "kkkkkk".$id;
	}
	
	public function ajax_stepUserPermitSubmit(Request $request)
	{
		/* echo "<pre>";
		print_r($_POST);
		die; */
		$step_id = $request->get('step_id');
		unset($_POST['_token']);
		unset($_POST['step_id']);
		unset($_POST['user_id']);
		unset($_POST['fms_what_new']);
		unset($_POST['fms_how_new']);
		unset($_POST['fms_when_new']);
		unset($_POST['temlate_url']);
		 
		// fms_how_new fms_when_new
		$fms_what_new = $request->get('fms_what_new');
		$fms_how_new = $request->get('fms_how_new');
		$fms_when_new = $request->get('fms_when_new');
		$temlate_url = $request->get('temlate_url');
		
		$dataArr = array(
						'user_right'=>$_POST,
						'fms_what'=>$fms_what_new,
						'fms_how_new'=>$fms_how_new,
						'fms_when_new'=>$fms_when_new,					
						'temlate_url'=>$temlate_url,				
						);
		$upId = DB::table('fmssteps')
				->where('_id', $step_id)
				->update($dataArr); 
        if($upId)
		{
			echo "success";
			exit;
		}else{
			echo "failed";
			exit;
		}
		exit;
	}
	
	public function checkStep($step_id, $user_id,$right)
	{
		//return $step_id.'-----'.$user_id.'-----'.$right; 
		 
		$data = Fmsstep::where('_id', '=', $step_id)->get();
		return $step_id.'-----'.$user_id."====<pre>"; print_r($data);
	}
	
	public function ajax_stepUserPermitModel()
	{
		//echo "<pre>"; print_r($_GET);
		if(!empty($_GET) && $_GET['fms_id']!='' && $_GET['step_id']!='')
		{
			$step_id = $_GET['step_id'];
			$data = DB::table('fmssteps')
				->select('user_right')
				->where('_id', $step_id) 
				->get()->toArray();
			$res =  $data[0]['user_right'];
			return $res;
			exit;			
		}else{
			echo 'Try again.';
		}
		exit;
	}
	
	public function insertpatching($fms_id){
		$patchingData = DB::table('fmssteps')
								->select('_id','fms_what')
								->where('fms_id', $fms_id) 
								->get()->toArray();
		//pr($patchingData); die;
		$patchingHtml = '<ul style="list-style:none;">';
		$p=0;
		
		if(!empty($patchingData)) {
			foreach($patchingData as $pkey=>$pval){
				$p++;
				$patchingHtml .= '<li>';
									$patchingHtml .= '<div class="checkbox-custom checkbox-warning">';
										$patchingHtml .= '<input type="checkbox" name="patching[r_num][steps][]" value="'.$pval['_id'].'" id="pt_r_num_'.$p.'">';
										$patchingHtml .= '<label for="pt_r_num_'.$p.'">'.$pval['fms_what'].'</label>';
									$patchingHtml .= '</div>';
								$patchingHtml .= '</li>';
			}
		}else{
			$patchingHtml .= '<li> <a href="'.route('createsteps', $fms_id).'" class="text-danger font-weight-bold">Create Steps first</li>';
		}
			
		
		$patchingHtml .= '</ul>';
		
		$patchingHtml = str_replace("'","",$patchingHtml);
		
		
		//echo $patchingHtml; die;
		
		return view('fms.insertpatching')->with('fms_id', $fms_id)->with('patchingHtml', $patchingHtml);
	}
	
	public function savepatching(Request $request){
		//dd($request); die;
		//echo "<pre>"; print_r($request->post()); die;
		
		$fms_id = $request->post('fms_id');
		
		// get only patching array 
		$patchingMainArr = array();
		$patchingArr = $request->post('patching');
		$dataArr = array();
		foreach($patchingArr as $key=>$val){
			//echo "<pre>"; print_r($val); echo "</pre>";
			
			$p_name = str_replace(' ', '_', $val['name']);
			$patching_name = strtolower($p_name);
			$dataArr[$patching_name] = array(
												'p_name'=>$val['name'],
												'steps_id'=>$val['steps']
											);
							//echo "<pre>"; print_r($dataArr);  				
										//array_push($patchingMainArr,$dataArr);
		}
		$newArr['patching']=$dataArr;
		//echo "<pre>"; print_r($newArr);  die;
		
		$updateId = DB::table('fmss')->where('_id', $fms_id)->update($newArr);
		
		if($updateId)
			{
				return redirect()->route('fmslist')->with('success','Patching added successfully.');
			}else{
				return redirect()->route('insertpatching', $fms_id)->with('success', 'Pleaase try again!');
			}
		
	}
	
	public function ajax_getpatchingids(Request $request) {
		$fms_id = $request->get('fms_id');
		$patching_id = $request->get('patching_id');
		$data = DB::table('fmss')
					->select('patching')
					->where('_id', $fms_id) 
					->get()->toArray();
		//pr($data[0]['patching'][$patching_id]); die;
		$patching_step_arr = $data[0]['patching'][$patching_id];
		return json_encode($patching_step_arr); die;
	}
	
	public function fms_setting($fms_id){
		getFmsDetailsById($fms_id);
		return view('fms.fms_setting')->with('fms_id', $fms_id);
	}
	
	public function update_fms(Request $request){
		//pr($_POST);  die;
		$fms_id 	=  $request->post('fms_id');
		$fms_name 	=  $request->post('fms_name');
		$store_room_id	=  $request->post('store_room_id');
		
		$updateArr = array(
							'fms_name'=>$fms_name,
							'store_room_id'=>$store_room_id
						  );
		$upId = DB::table('fmss')
					->where('_id', $fms_id)
					->update($updateArr);
		if($upId){
			return redirect()->route('fmslist')->with('success','FMS updated successfully');
		}else{
			return redirect()->route('fmslist')->with('error','FMS not updated!');
		}
	}
	
	
}
