<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BuyerSampleCard;
use DB;
use Session;
use PDF;
error_reporting(0);

use App\User;

class BuyerSampleCardController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		checkPermission('view_pi');
		$buyersamplecardlist = DB::table('tbl_buyer_sample_card')->orderBy('created_at','-1')->paginate(30);
		//pr($buyersamplecardlist);die; 
		return view('general.buyerSamplecard.buyersamplecardlist')->with('buyersamplecardlist', $buyersamplecardlist);
	}

	
	/* for search test END */
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		return view('general.buyerSamplecard.buyerSamplecardcreate');
    }
	
	
	public function store(Request $request)
    {
		//pr($_POST); die('Sample Card');
		//checkPermission('add_pi');
		$this->validate($request, [
							'customer_name'  => 'required',
						]);
		
		$financial_year = get_financial_year();
		
		$data  = array(
						"customer_id"    => $request->customer_id,
						"customer_name"    => $request->customer_name,
						"customer_type"    => $request->customer_type,
						"address"   	   => $request->address,
						"city"   		   => $request->city,
						"name"   		   => $request->name,
						"phone"  		   => $request->phone,
						"season"   		   => $request->season,
						"email"  		   => trim($request->email),
						"sales_person"     => $request->sales_person,
						"enquiry_type"     => $request->enquiry_type,
						"crm_enquery"      => $request->crm_enquery,
						"crm_assign"   		=> $request->crm_assign,
						"merchant_name"   	=> $request->merchant_name,
						"subtotal"  	   => $request->subtotal,
						"delivery_charge"  => $request->delivery_charge,
						"total"   		   => $request->total,
						"financial_year"   => $financial_year,
						"order_date"       => date('Y-m-d H:i:s'),
						"created_at"       => date('Y-m-d H:i:s')
					); 
		//pr($data);die;
		$last_id = DB::table('tbl_buyer_sample_card')->insertGetId($data);
		$oid = (array) $last_id;
		$last_id = $oid['oid'];		
		$count = count($request->row);
		
		for($i=1;$i<=$count;$i++){
			if($request->row[$i]['item']!=''){
				$data = array(
						"invoice_id_sample_card"=>$last_id,
						"item"=>$request->row[$i]['item'],
						"description"=>$request->row[$i]['description'],
						"remark"=>$request->row[$i]['remark'],
						"unit_price"=>$request->row[$i]['unit_price'],
						"unit"=>$request->row[$i]['unit'],
						"amount"=>$request->row[$i]['amount']
					);
				DB::table('tbl_buyer_sample_card_data')->insert($data);
			}
		}
		
		if($last_id)
		{
			return redirect()->route('buyersamplecardlisting')->with('success', 'Buyer Sample Card added successfully.');
		}else{
			return redirect()->route('buyersamplecardlisting')->with('success', 'Buyer Sample Card not stored.');
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
		$edit_invoice = DB::table('tbl_buyer_sample_card')->where('_id',$id)->first();
		$customer =array();
		return view('general.buyerSamplecard.buyersamplecardshow',['customer' => $customer,'edit_invoice' => $edit_invoice]);
    }
	
    public function searchSamplecard(Request $request){
		$search	   = $request->sample_card;
		$search_by = $request->sample_card_code;
		$customer_id = getCustomerId($search_by);
		
		if($customer_id!=''){
			$buyersamplecardlist = DB::table('tbl_buyer_sample_card')->where($request->sample_card, 'LIKE', '%' . $customer_id . '%' )->paginate(20);
    	}else{
    		$buyersamplecardlist = DB::table('tbl_buyer_sample_card')->paginate(20);
			$buyersamplecardlist['search']	  = $request->sample_card;
			$buyersamplecardlist['search_by'] = $request->sample_card_code;
    	}
		/* pr($buyersamplecardlist); die; */
		return view('general.buyerSamplecard.buyersamplecardlist', compact('buyersamplecardlist'))->with('search', $search)->with('search_by', $search_by);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		checkPermission('edit_pi');
        $edit_invoice = DB::table('tbl_buyer_sample_card')->where('_id',$id)->first();
		//pr($edit_purchase_invoice); die;
		$customer =array();
		return view('general.buyerSamplecard.buyerSamplecardedit',['customer' => $customer,'edit_invoice' => $edit_invoice]);
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
		checkPermission('edit_pi');
		$this->validate($request, ['customer_name'  => 'required']);
		//pr($_POST); die;
		$financial_year = $request->financial_year;
		$data  = array(
						"customer_id"    => $request->customer_id,
						"customer_name"    => $request->customer_name,
						"customer_type"    => $request->customer_type,
						"address"   	   => $request->address,
						"city"   		   => $request->city,
						"name"   		   => $request->name,
						"phone"  		   => $request->phone,
						"season"   		   => $request->season,
						"email"  		   => trim($request->email),
						"sales_person"     => $request->sales_person,
						"enquiry_type"     => $request->enquiry_type,
						"crm_enquery"      => $request->crm_enquery,
						"crm_assign"   		=> $request->crm_assign,
						"merchant_name"   	=> $request->merchant_name,
						"subtotal"  	   => $request->subtotal,
						"delivery_charge"  => $request->delivery_charge,
						"total"   		   => $request->total,
						"financial_year"   => $financial_year,
						"updated_at"       => date('Y-m-d H:i:s')
					); 
		$invoice_update = DB::table('tbl_buyer_sample_card')->where('_id',$id)->update($data);	
		$count = count($request->row);
		$inv_data = DB::table('tbl_buyer_sample_card')->where('_id',$id)->get()->toArray();
		if(array_key_exists('data_status', $inv_data[0]) && $inv_data[0]['data_status']==1){
			$data_status=1;
		}else{
			$data_status=0;
		}

		if($data_status==1){
			$fms_data =array();
						
			/* $customer_data = array(
									'c_id'=>$inv_data[0]['customer_name'],
									'c_value'=>GetBuyerName($inv_data[0]['customer_id'])
								); */
								
			$customer_data = array(
									'c_id'=>$inv_data[0]['customer_id'],
									'c_value'=>$inv_data[0]['customer_name']
								);
			
			$s_person_data = array(
									's_id'=>$inv_data[0]['sales_person'],
									's_value'=>getAgentMerchant($inv_data[0]['sales_person'])
								);
								
			/* CRM assign */
			$crm_data='';
			if(array_key_exists('crm_assign', $inv_data[0]) && $inv_data[0]['crm_assign']!=''){
				$crm_data=array(
									'c_id'=>$inv_data[0]['crm_assign'],
									'c_value'=>getAgentMerchant($inv_data[0]['crm_assign'])
								);
			}
			
			
			/* Merchant assign */	
			$merchant_data ='';
			if(array_key_exists('merchant_name', $inv_data[0]) && $inv_data[0]['merchant_name']!=''){
				$merchant_data=array(
									'm_id'=>$inv_data[0]['merchant_name'],
									'm_value'=>getAgentMerchant($inv_data[0]['merchant_name'])
								);
			}
			
			$fms_data['customer_data'] = $customer_data;
			$fms_data['s_person_data'] = $s_person_data;
			
			$fms_data['crm_data'] = $crm_data;
			$fms_data['merchant_data'] = $merchant_data;
			$fms_data['enquiry_type'] = $inv_data[0]['enquiry_type']; 
			$fms_data['customer_type'] = $inv_data[0]['customer_type'];
			$fms_data['name'] = $inv_data[0]['name'];
			$fms_data['phone'] = $inv_data[0]['phone'];
			
			for($i=1;$i<=$count;$i++){
					
					if($request->row[$i]['item']!=''){
						$data = array(
								"invoice_id_sample_card"=>$id,
								"item"=>$request->row[$i]['item'],
								"description"=>$request->row[$i]['description'],
								"remark"=>$request->row[$i]['remark'],
								"unit_price"=>$request->row[$i]['unit_price'],
								"unit"=>$request->row[$i]['unit'],
								"amount"=>$request->row[$i]['amount']
							);
						DB::table('tbl_buyer_sample_card_data')->where('_id',$request->row[$i]['row_id'])->update($data);
				
						/* Now update FMS data collection  depend on FMS type*/
					
						$fms_data['item_data'] = array(
													'i_id'=>$request->row[$i]['item'],
													'i_value'=>getItemName($request->row[$i]['item'])
												);
										
					
						$fms_data['unit_price'] = $request->row[$i]['unit_price'];
					
						DB::table('samplecards_fms_data')->where('sample_card_id', $id)->where('item_row',$request->row[$i]['row_id'])->update($fms_data);
					}
					
			}
		}else{
				DB::table('tbl_buyer_sample_card_data')->where('invoice_id_sample_card',$id)->delete();
				for($i=1;$i<=$count;$i++){
					if($request->row[$i]['item']!=''){
						$data = array(
								"invoice_id_sample_card"=>$id,
								"item"=>$request->row[$i]['item'],
								"description"=>$request->row[$i]['description'],
								"remark"=>$request->row[$i]['remark'],
								"unit_price"=>$request->row[$i]['unit_price'],
								"unit"=>$request->row[$i]['unit'],
								"amount"=>$request->row[$i]['amount']
							);
						DB::table('tbl_buyer_sample_card_data')->insert($data);
					}
				}
		}
		
		
		if($invoice_update)
		{
			return redirect()->route('buyersamplecardlisting')->with('success', 'Buyer Sample Card update successfully.');
		}else{
			return redirect()->route('buyersamplecardlisting')->with('success', 'Buyer Sample Card not updated.');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		//checkPermission('delete_pi');
         if($id!='') {
			DB::table('tbl_buyer_sample_card')->where('_id', '=', $id)->delete();
			DB::table('tbl_buyer_sample_card_data')->where('invoice_id_sample_card', '=', $id)->delete();
		}
		Session::flash('success', 'Order deleted successfully!');
		return redirect()->route('buyersamplecardlisting')->with('danger', 'Order deleted successfully.');
    }
	
	public function ajaxCustomerDetailsByname($customer_name)
	{	
		//echo $customer_name; die;
		$select_data = DB::table('register_form')
					->select('delivery_address','city','mobile_no_dispatch','email_id_md','crm_assign','merchant_name')
					->where('company_name','like', '%'.$customer_name.'%')
					->first();	
		$get_id = (array) $select_data['_id'];
		$cust_id = $get_id['oid'];
		
		/* pr($get_id);
		pr($select_data);
		die; */
	 
	  $arr = array('customer_id' =>$cust_id,'customer_delivery_address' =>  $select_data['delivery_address'][0],'customer_city' => $select_data['city'],'customer_phone'=> $select_data['mobile_no_dispatch'], 'crm_assign'=>$select_data['crm_assign'], 'merchant_name'=>$select_data['merchant_name'], 'email_id_md'=>$select_data['email_id_md']);
		//pr($arr); die;
      return json_encode($arr);
	}
	
	
	public function ajax_insert_samplecard_into_fms(Request $request)
	{
		$pi_id = $request->pi_id;
		//echo $pi_id; die('hii');
	 
		$pi_data = getSampleCardRowDataById($pi_id);
		/* check data status */
		$data_inserted = 'no';
		if(array_key_exists('data_status', $pi_data) && $pi_data['data_status']==1){
			echo $data_inserted = 'yes';
			exit;
		}
		
		/* echo $pi_data['order_type']; 
		pr($pi_data); die; */ 
		/* check order type */
		$fms_id = '5f3522250f0e7503b80030d7'; // BUYER SAMPLE CARDS ORDER
	  //echo $fms_id; die;
	   
	  if($pi_id==''){
		   $result = array(
							'status' =>false,
							'description' => 'pi_id is empty'
						);
	  }else{
			// here we start insert data into PI FMS data	
			// get fms data
			$fms_data = DB::table('fmss')->where('_id',$fms_id)->get()->toArray();
			$fms_data_tbl = $fms_data[0]['fms_table'];
			$fms_type = $fms_data[0]['fms_type'];
			//pr($fms_data); die;
			
			// get PI data by pi_id 
			$inv_data = DB::table('tbl_buyer_sample_card')->where('_id',$pi_id)->get()->toArray();
			$pi_date = $inv_data[0]['created_at'];
			
			$customer_type = $inv_data[0]['customer_type'];
			$name = $inv_data[0]['name'];
			$phone = $inv_data[0]['phone'];

			$enquiry_type = $inv_data[0]['enquiry_type'];
							
			/* $customer_data = array(
									'c_id'=>$inv_data[0]['customer_name'],
									'c_value'=>GetBuyerName($inv_data[0]['customer_name'])
								); */
								
			$customer_data = array(
									'c_id'=>$inv_data[0]['customer_id'],
									'c_value'=>$inv_data[0]['customer_name']
								);
			
			$s_person_data = array(
									's_id'=>$inv_data[0]['sales_person'],
									's_value'=>getAgentMerchant($inv_data[0]['sales_person'])
								);
								
			/* CRM assign */
			$crm_data='';
			if(array_key_exists('crm_assign', $inv_data[0]) && $inv_data[0]['crm_assign']!=''){
				$crm_data=array(
									'c_id'=>$inv_data[0]['crm_assign'],
									'c_value'=>getAgentMerchant($inv_data[0]['crm_assign'])
								);
			}
			
			
			/* Merchant assign */	
			$merchant_data ='';
			if(array_key_exists('merchant_name', $inv_data[0]) && $inv_data[0]['merchant_name']!=''){
				$merchant_data=array(
									'm_id'=>$inv_data[0]['merchant_name'],
									'm_value'=>getAgentMerchant($inv_data[0]['merchant_name'])
								);
			}
			
			$pi_status = $inv_data[0]['status'];
		
				try{
						$data = array();						
						$pi_rows = DB::table('tbl_buyer_sample_card_data')->where('invoice_id_sample_card',$pi_id)->get()->toArray();
						$data['sample_card_id']=$pi_id;
						$data['fms_id']=$fms_id;
						 //pr($pi_rows); die('==row--');
						foreach($pi_rows as $key=>$row){
							/* echo $pi_date; pr($row); die('==row--'); */
							/* step data */
							$stepkArr = [];
							$stepsIds = get_step_id_by_fms_id($fms_id);
							//pr($stepsIds); die;
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
								
								if($s_plane_dt['fms_when_type']==3 && array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='if_fabric_is_not')
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
															'awb_no'=>'',
															'courier_company'=>''
														);
								}else if(array_key_exists("task_id_link",$fms_when) && $s_plane_dt['fms_when_type']==12)
								{
									$Hr 	= $fms_when['after_task_time'];
									//$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($pi_date . " + ".$Hr." hours"));
									
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
													
								}else if($s_plane_dt['fms_when_type']==12 && array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='arrange_sampling')
								{
									$data[$step] = array(
														'planed'=>'',
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>''
													);
													
								}else if($s_plane_dt['fms_when_type']==12 && array_key_exists("process",$s_plane_dt) && $s_plane_dt['process']=='sampling_fabric')
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
									$data[$step] = array(
														'planed'=>$planedDt,
														'actual'=>''
													);
								}
								$i++;
							} /* step data end*/
							//pr($data);
							
							/* new code start  */	
							$oid = (array) $row['_id'];
							$item_row = $oid['oid'];
							$data['item_row']= $item_row;
							$data['order_date']= $pi_date;
							$data['customer_data'] = $customer_data;
							
							$data['s_person_data'] = $s_person_data;
							$data['crm_data'] = $crm_data;
							$data['merchant_data'] = $merchant_data;
							
							$data['enquiry_type'] = $enquiry_type;
							
							$data['customer_type'] = $customer_type;
							$data['name'] = $name;
							$data['phone'] = $phone;
							
							$data['pi_status'] = $pi_status;
							$data['item_data'] = array(
														'i_id'=>$row['item'],
														'i_value'=>getItemName($row['item'])
													);
							$data['unit_price'] = $row['unit_price'];
							$data['unit'] = $row['unit'];
							$data['remark'] = $row['remark'];
							/* New code END */	
			
							//pr($data); die('kk');
							$inserted = DB::table($fms_data_tbl)->insertGetId($data);
						} /* for loop end */
						
						if($inserted)
						{
							/* update main invoice table */
							DB::table('tbl_buyer_sample_card')->where('_id',$pi_id)->update(['data_status' => 1]);
							$result = array(
												'status' =>true,
												'msg' => 'Data inserted into FMS'
											);
						}else{
							 $result = array(
											'status' =>false,
											'msg' => 'Data not inserted into FMS'
										);
						}
			
			}catch(Exception $ex){
				DB::rollback();
			}	
		}
		return json_encode($result);
		die;
	}
	
	/* auto search customer */
	public function ajaxCustomerSearch()
	{
		//pr($_GET);
		$data = array();
		if(isset($_GET)){
			if($_GET['query']!=''){
				$company_name = $_GET['query'];
				$data = DB::table('register_form')->select('company_name')->where('company_name','like', '%'.$company_name.'%')->get()->toArray();
				echo json_encode($data); die;
			}else{
				echo json_encode($data); die;
			}
			
		}else{
			echo json_encode($data); die;
		}		
	}
	
	public function searchcustomer(){
		//die('hiii');
		//echo json_encode($_GET); exit;
		$searchTerm = $_GET['term']; 
		if(!empty($searchTerm)){
			$mainData=array();
			$dataarr=array();
			$where = '';
			//invoice_no
			if($_GET['searchby'] =='customer_name'){			
				$where = $_GET['searchby'];
				
				$data = DB::table('register_form')
					->select('company_name')
					->limit(100)
					->where('company_name','like', '%'.$searchTerm.'%')
					->get()
					->toArray();
				//echo '<pre>';print_r($data);	die;
				foreach($data as $row){ 
					$value = $row['company_name'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			
			}
			
			
			//echo '<pre>';print_r($mainData);	die;
			echo json_encode($mainData);
			exit;
		}
		
	
	
	
}