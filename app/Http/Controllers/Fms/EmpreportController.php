<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Fmsdata;
use App\Fmsmodel\EmpreportModel;
use DB;

class EmpreportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //echo "jdfhgd"; die;
		$fms_id = "5d0cd734d0490449dd7eb122";
		$fabdatas = Fmsdata::where('fms_id', '=', $fms_id)
						->orderBy('created_at', 'asc')->limit(10)->get()->toarray();
		/* 
		echo "<pre>";
		print_r($fabdatas);
		die;
		  */
		$data = array();
		return view('fms.employeereport', compact('fabdatas', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response EmpreportModel
     */
    public function store(Request $request)
    {
        //
    }
	
	public function getEmployeereport(Request $request)
    {
		$s_id='';
		$search_type='';
		$activeData=array();
		$fms_type = "";
		if(!empty($request)){
			//pr($_POST); die;
			$search_type = $request->search_type;
			$fms_type = $request->fms_type;			
			$empreport = new EmpreportModel();
			$inputData = $request->all();
			//pr($inputData); die;
			$m_data = $empreport->getEmployeereport($inputData);
			$data =	$m_data['row_data'];
			$data['fms_ids'] =	$m_data['fms_ids'];
			
			//pr($data); die("==from===controller==");
			/* foreach($datas as $rows){
				foreach($rows as $val){
					echo  $val['fabricator']."<br/>==";
				}				
			}
			die('--controller--'); */
			$s_id =	$m_data['s_id'];
			$activeData['search_id'] = $m_data['s_id'];
			$activeData['search_type'] = $search_type;
			$activeData['dt_from'] = $request->dt_from;
			$activeData['dt_to'] = $request->dt_to;
			$activeData['fms_type'] = $request->fms_type;	
			//pr($activeData); die;
		}else{
			$data=array();
			//$search_type='';
			$activeData['search_id'] = '';
			$activeData['search_type'] = '';
			$activeData['dt_from'] = '';
			$activeData['dt_to'] = '';
			$activeData['fms_type'] = '';
			
		}
		//pr($activeData); die;
		//pr($dataNew);		
		//return view('fms.employeereport', compact('data'))->with('search_type', $search_type)->with('search_id', $s_id);
		
		//echo $fms_type."<<<<========="; die('----fms_type---');
		if($fms_type=='custom_fms'){
			return view('fms.employeereport_custom', compact('data', 'activeData'));
		}else{
			return view('fms.employeereport', compact('data', 'activeData'));
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	/* 
		"fms_when":{ 
			  "input_type":"master_name",
			  "fms_when_type":"7"
		   }
		   
		   DB::collection('users')->where(
				'Personal_info.hobbies',
				'elemMatch',
				[ 'games' => 'cricket' ]
			)->get()
			
			5d0cd8fed049044a2a0c4a2a:
							built_options:"7"
							built_options_id:"master_name
	*/
	
	public function report_testing(){
		
		//$data = DB::table('fmsdatas')->get();
			//->where('5d0cd8fed049044a2a0c4a2a','elemMatch',[ 'built_options_id' => '7' ])->get();
			
		$data = DB::table('fmsdatas')
					->where('fms_id', '5d0cd734d0490449dd7eb122')
					->where('5d0cd8fed049044a2a0c4a28.fb_id','149')->get()->toarray(); 
				
		/* 
		5d0cd8fed049044a2a0c4a2a:
								built_options:"7"
								built_options_id:"master_name"
								
		after update--
		5d0cd8fed049044a2a0c4a2a:
								built_options:"7"
								built_options_id:"master_name"
								dropdown_id:"103
		
		*/
				
			/// for master
		/* $data = DB::table('fmsdatas')
				->where('fms_id', '5d0cd734d0490449dd7eb122')
				->where('5d0cd8fed049044a2a0c4a2a.dropdown_id','103')->get()->toarray(); */
				
			pr($data); die;
	}
	
	public function emp_payment(Request $request){
		//pr($_POST); die(9999999);
		/* return "emp_payment==="; */
		$inputData = $request->all();
		//pr($inputData); die('controller ==jdfghdfjughu---');
		$empreport = new EmpreportModel();
		$m_data = $empreport->emp_payment($inputData);
		//pr($inputData);  pr($m_data); die('controller ==jdfghdfjughu---');
		/* $m_data = $empreport->getEmployeereport($inputData);
		$data =	$m_data['row_data']; */
		if($m_data){
			return redirect()->route('payment_list')->with('success', 'Payment saved successfully.');
		}
		
	}
	
	public function payment_list(){
		$empreport = new EmpreportModel();
		$payment_data = $empreport->emp_payment_list();
		$data['data'] = $payment_data;
		return view('fms.payment_list', compact('data'));
	}
	
	public function payment_view($inv_no){
		
		$empreport = new EmpreportModel();
		$all_data = $empreport->payment_view($inv_no);
		//pr($all_data); die;
		//$data['data'] = $payment_data;
		return view('fms.payment_view', compact('all_data'));
	}
	

	public function payment_print($inv_no){
		$empreport = new EmpreportModel();
		$all_data = $empreport->payment_view($inv_no);
		return view('fms.payment_print', compact('all_data'));
	}
	
	public function payment_edit($inv_no){
		
		$empreport = new EmpreportModel();
		$all_data = $empreport->payment_view($inv_no);
		//pr($payment_data); die;
		//$data['data'] = $payment_data;
		return view('fms.payment_edit', compact('all_data'));
	}
	
	
	public function payment_update(Request $request){
		//pr($_POST); die(9999999);
		/* return "emp_payment==="; */
		$inputData = $request->all();
		//pr($inputData); die("--controller--");
		
		$empreport = new EmpreportModel();
		$m_data = $empreport->payment_update($inputData);
		//pr($inputData);  pr($m_data); die('controller ==jdfghdfjughu---');
		/* $m_data = $empreport->getEmployeereport($inputData);
		$data =	$m_data['row_data']; */
		if($m_data){
			return redirect()->route('payment_list')->with('success', 'Payment updated successfully.');
		}else{
			return redirect()->route('payment_list')->with('success', 'Payment not updated!');
		}
	}
	
	public function findpaymentorder(Request $request){
		$inputData = $request->all();
		$orderData = new EmpreportModel();
		$o_data = $orderData->findpaymentorder($inputData);
		//pr($o_data);  die;
		if($o_data){
			return json_encode($o_data['inv']);
		}else{
			return $o_data['inv']='';
		}
		
		die;
	}
	
	public function test()
    { 
		die('==testing-all--');
		/* $dbData = DB::table('fmsdatas')->select('inv')
							->whereNotNull('inv')->get()->toarray(); */
							
		$dbData = DB::table('fmsdatas')->select('_id','inv')
							->whereNotNull('inv')
							->get()->toarray();
		
		pr($dbData); die('==testing-all--');
		
		foreach($dbData as $key=>$ids){
			$id_no = (array) $ids['_id'];
			$id = $id_no['oid'];
			//pr($id); die;
			$inv = $ids['inv'];
			//var_dump($id); die;
			
			if(!empty($inv)){
				//pr($inv);
				$inv2 = array(
								'inv'=>array(
												'fabricator_list'=>$inv
											)
							);
				/* DB::table('fmsdatas')
					->where('_id', $id)
					->update($inv2);
				echo "==update-id==".$id."--inv==<br>"; */
				
				//die;
			}else{
				echo "==blank=id===".$id."--inv==<br>";
			}
			
			//die;
			//pr($ids['oid']); die;
			//echo $ids->oid; die;			
			
		}
		
				die('====');			
							
				pr($dbData); die;
        die('====3333');
    }
	
}
