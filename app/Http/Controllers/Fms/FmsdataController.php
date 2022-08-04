<?php
namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Fmstask;
use App\Fms;
use App\Fmsstep;
use App\Fmsdata;
use DB;
use Redirect;
use Session;
use MongoDB\BSON\UTCDateTime;
use Auth;
class FmsdataController extends Controller
{
	protected $fmsdata;
	
	public function __construct()
	{
		$this->fmsdata = new Fmsdata();
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     **/
	
	public function index(Request $request, $id, $from='', $orderNum='')
    {
		//pr($_GET); die;
		$search_by ='';
		$search_text = '';
		$item_drop = '';
		$s_post=array();
		if(!empty($_POST) && isset($_POST)){
			$s_post = $_POST;
		}
		//pr($s_post);
		
		$all_order_qty='';
		$post = $request->all();
		//pr($post); die;
		if($id!=Session::get('sess_fms_id') || !isset($post['completed']) ){
			Session::forget('sess_fms_id');
			Session::forget('completed');
		}
		
		if(isset($post['completed']) && $post['completed']!=''){
			Session::put('completed', '1');
			Session::put('sess_fms_id', $id);
		}
		
		$fms_id = $id;
		
		/* check if buyer/brand login */
		$userLoginData = Session::get('userLogin');
		if(!empty($userLoginData)){
			$taskdatas = Fmstask::where('fms_id', '=', $fms_id)->orderBy('order_no', 'asc')->where('buyer_brand','1')->get()->toarray();
		}else{
			$taskdatas = Fmstask::where('fms_id', '=', $fms_id)->orderBy('order_no', 'asc')->where('in_fms', '!=', '0')->where('buyer_brand_dashboard', '!=', '1')->get()->toarray();
		}
		
		
		//pr($taskdatas); die;
		$orderStepId = '';
		$orderDateTaskId ='';
		$itemTaskId='';
		$item_id=0;
		
		
		foreach($taskdatas as $tk=>$tv){
			if($tv['field_type']=='orders'){
				$orderStepId=$tv['_id'];
			}elseif($tv['field_type']=='items'){
				$itemTaskId=$tv['_id'];
			}else if($tv['input_type']=='custom_fields' && $tv['field_type']=='date'){
				$orderDateTaskId=$tv['_id'];
			}else if($tv['input_type']=='custom_fields' && $tv['field_type']=='delivery_date'){
				$deliveryDateTaskId=$tv['_id'];
			}else if($tv['input_type']=='order_qty' && $tv['field_type']=='qty'){
				$qtyTaskId=$tv['_id'];
			}
		}
		//echo $orderStepId; die;		
		$pm_check_stepId = getStepIdByName($fms_id, 'pm_check');
		$pm_check_stepId = $pm_check_stepId['_id'];
		
		if(empty($taskdatas)){ return redirect()->route('fmslist')->with('success', 'No data found.'); }		
		$fms = Fms::where('_id', '=', $id)->get()->toarray();
		$fms = $fms[0];
		$fms_table= $fms['fms_table'];
		
		
		
		if(!empty($userLoginData)){
			//pr($userLoginData); die;
			$stepsdatas = Fmsstep::where('fms_id', '=', $id)->where('buyer_brand','1')->orderBy('order_no', 'asc')->get()->toarray();
			//die('ee');
		}else{
			$stepsdatas = Fmsstep::where('fms_id', '=', $id)->orderBy('order_no', 'asc')->get()->toarray();
		}
		
		//pr($stepsdatas); die;
	
		$fmsdatas=array();
		if($fms['fms_type']=='lead_to_quotation'){
			
			/* ///////////========== Update PI and PI row data into FMS data collection start =========///////////// */			
			$up = 0; // set 1 if want to update
			if($up==1){
				$fms_table_data = DB::table($fms_table)->get()->toArray();
				//$fms_table_data = DB::table($fms_table)->select('id','pi_id')->where('pi_id', '5f476c2ed049047af66a1fb2')->get()->toArray();

				$pi_index=0;							
				$oldPi='';
				$cn=1;
			
			foreach($fms_table_data as $fkey=>$fdata){
				$oid = (array) $fdata['_id'];
				$data_id = $oid['oid'];
				
						$pi_id=$fdata['pi_id'];
						if($oldPi!=$pi_id){
							$pi_index=0;
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];
						}else{
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];							
						}						
						$oldPi = $pi_id;
						//pr($pi_data); die;
						$pi_index++;
						if(is_array($piMain) && array_key_exists('buyer_po_number',  $piMain)){
							$buyer_po_number = $piMain['buyer_po_number'];
						}else{
							$buyer_po_number = '';
						}
						
						//echo $buyer_po_number; 
						//pr($piMain); die;
						//pr($piRows);
						
						// update data start
						/// Pi data 
						//$order_type = $piMain['order_type'];
						$fms_data =array();
						$fms_data['buyer_po_number']=$buyer_po_number;
						
						/* $buyer_data = array(
													'b_id'=>$piMain['buyer_name'],
													'b_value'=>trim(GetBuyerName($piMain['buyer_name']))
											);
											
						$customer_data = array(
													'c_id'=>$piMain['customer_name'],
													'c_value'=>trim(GetBuyerName($piMain['customer_name']))
											);
							
						$s_person_data = array(
												's_id'=>$piMain['sales_agent'],
												's_value'=>trim(getAgentMerchant($piMain['sales_agent']))
											);
											
						$brand_data = array(
												'b_id'=>$piMain['brand_name'],
												'b_value'=>trim(GetBrandName($piMain['brand_name']))
											); */
						
						//// CRM assign 
						/* $crm_data='';
						if(array_key_exists('crm_assign', $piMain) && $piMain['crm_assign']!=''){
							$crm_data=array(
												'c_id'=>$piMain['crm_assign'],
												'c_value'=>trim(getAgentMerchant($piMain['crm_assign']))
											);
						} */
						//// Merchant assign
						/* $merchant_data ='';
						if(array_key_exists('merchant_name', $piMain) && $piMain['merchant_name']!=''){
							$merchant_data=array(
												'm_id'=>$piMain['merchant_name'],
												'm_value'=>trim(getAgentMerchant($piMain['merchant_name']))
											);
						} */
						///Pi data
						
						// row data 
						//$fms_data['etd']=$piRows['etd'];
						$roid = (array) $piRows['_id'];
						$row_id = $roid['oid'];
						
						/* $fms_data['item_row']=$row_id;
						$fms_data['invoice_no']= $piMain['po_serial_number'];
						$fms_data['pi_date']= $piMain['proforma_invoice_date']; */
						
						/* $fms_data['buyer_data'] = $buyer_data;
						$fms_data['customer_data'] = $customer_data;
						$fms_data['s_person_data'] = $s_person_data;
						$fms_data['brand_data'] = $brand_data; */
						
						//$fms_data['pi_status'] = $piMain['status'];
						
						/* $fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>trim(getItemName($piRows['item']))
											); */
											
						/* $fms_data['color'] = $piRows['colour'];
						$fms_data['bulkqty'] = $piRows['bulkqty'];
						$fms_data['ssqty'] = $piRows['ssqty'];
						$fms_data['unit'] = $piRows['unit'];
						$fms_data['unit_price'] = $piRows['unit_price'];
						$fms_data['lab_dip'] = $piRows['lab_dip']; */
						
						/* $fms_data['crm_data'] = $crm_data;
						$fms_data['merchant_data'] = $merchant_data; */
						
						/* $fms_data['fob_sample'] = $piMain['fob_sample_approval'];
						$fms_data['fpt_testing'] = $piMain['fpt_testing_approval'];
						$fms_data['payment_term'] = $piMain['payment_term'];
						$fms_data['mode_of_transport'] = $piMain['mode_of_transport']; */
						
						//pr($fms_data); die;
						DB::table($fms_table)->where('_id', $data_id)->where('pi_id', $pi_id)->update($fms_data);
						// update data END
				
				echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
				$cn++;
				//die;
			}
			
			//pr($fms_table_data); die;
			die('DONE ALL');
		}			
			/* ///////////========== Update PI and PI row data into FMS data collection END =========/////////////// */
			
			/* $fmsdatas_pg 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate(50)->toarray();
							
			$all_data 	= DB::table($fms_table)
						->where('fms_id',$fms_id)
						->paginate(50);  5e8ef2c80f0e75228400661b */
						
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$orWhere = array();
							$whereIn = array();
							$where = array(
									array(
									'fms_id', '=', $fms_id
									)
								);
							
							$remove_cancel=0;
							// step#2
							if(!empty($_GET['5e8ef2c80f0e752284006618'])){
								if($_GET['5e8ef2c80f0e752284006618']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e752284006618.actual','=',''
									);
								}
								else if($_GET['5e8ef2c80f0e752284006618']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e752284006618.actual','!=',''
									);
								}
								
							}
							// step#3
							if(!empty($_GET['5e8ef2c80f0e752284006619'])){
								if($_GET['5e8ef2c80f0e752284006619']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e752284006619.actual','=',''
									);
									
									$where[]=array(
										'5e8ef2c80f0e752284006618.dropdown','=','Rejected'
									);
								}
								else if($_GET['5e8ef2c80f0e752284006619']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e752284006619.actual','!=',''
									);
								}
							}
							
							//step#4
							if(!empty($_GET['5e8ef2c80f0e75228400661a'])){
								if($_GET['5e8ef2c80f0e75228400661a']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e8ef2c80f0e75228400661a.dropdownlist','=',''
									);
								}else{
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e75228400661a.dropdownlist','=',$_GET['5e8ef2c80f0e75228400661a']
									);
								}
								
							}
							
							// step#5
							if(!empty($_GET['5e8ef2c80f0e75228400661b'])){
								if($_GET['5e8ef2c80f0e75228400661b']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e75228400661b.actual','=',''
									);
								}else if($_GET['5e8ef2c80f0e75228400661b']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e75228400661b.actual','!=',''
									);
								}else if($_GET['5e8ef2c80f0e75228400661b']=='Approved'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e75228400661b.dropdown','=','Approved'
									);
								}else if($_GET['5e8ef2c80f0e75228400661b']=='Cancelled'){
									$remove_cancel=1;
									$where[]=array(
									'5e8ef2c80f0e75228400661b.dropdown','=','Cancelled'
									);
								}
							}
							
							// step#6
							if(!empty($_GET['5e96e00c0f0e751ff8004712'])){
								if($_GET['5e96e00c0f0e751ff8004712']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
												'5e96e00c0f0e751ff8004712.actual','=',''
												);
									$where[]=array(
												'5e8ef2c80f0e75228400661a.dropdownlist','=','Not Available'
												);
												
									$where[]=array(
												'5e8ef2c80f0e75228400661b.dropdown','=','Approved'
												);
								}
								else if($_GET['5e96e00c0f0e751ff8004712']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e96e00c0f0e751ff8004712.actual','!=',''
									);
								}
							}
							
							// step#7							
							if(!empty($_GET['5e96e00c0f0e751ff8004713'])){
								if($_GET['5e96e00c0f0e751ff8004713']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e96e00c0f0e751ff8004713.notes','=',''
									);
									
								}else if($_GET['5e96e00c0f0e751ff8004713']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
										'5e96e00c0f0e751ff8004713.notes','!=',''
									);
									$where[]=array(
										'5e96e00c0f0e751ff8004713.comment','!=',''
									);
									
								}
								
							}
							
							// step#9
							if(!empty($_GET['5e9801ff0f0e750980005e47'])){
								if($_GET['5e9801ff0f0e750980005e47']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e47.planed','!=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e47.actual','=',''
									);
								}
								else if($_GET['5e9801ff0f0e750980005e47']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9801ff0f0e750980005e47.actual','!=',''
									);
								}
							}
							
							// step#10
							if(!empty($_GET['5e9801ff0f0e750980005e48'])){
								if($_GET['5e9801ff0f0e750980005e48']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e48.actual','=',''
									);
									
									$where[]=array(
										'lab_dip','=','Required'
									);
									
								}
								else if($_GET['5e9801ff0f0e750980005e48']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9801ff0f0e750980005e48.actual','!=',''
									);
								}
							}
							
							// step#11
							if(!empty($_GET['5e9801ff0f0e750980005e49'])){
								if($_GET['5e9801ff0f0e750980005e49']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e49.actual','=',''
									);
									
									$where[]=array(
										'fob_sample','=','Buyer'
									);
								}
								else if($_GET['5e9801ff0f0e750980005e49']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9801ff0f0e750980005e49.actual','!=',''
									);
								}
							}
							
							// step#12
							if(!empty($_GET['5e9801ff0f0e750980005e4a'])){
								if($_GET['5e9801ff0f0e750980005e4a']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e4a.planed','!=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e4a.actual','=',''
									);
									
									$where[]=array(
										'ssqty','>','0'
									);
								}
								else if($_GET['5e9801ff0f0e750980005e4a']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9801ff0f0e750980005e4a.actual','!=',''
									);
								}
							}
							
							// step#13
							if(!empty($_GET['5e9801ff0f0e750980005e4b'])){
								if($_GET['5e9801ff0f0e750980005e4b']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e4b.planed','!=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e4b.actual','=',''
									);
									
									$where[]=array(
										'fpt_testing','=','Required'
									);
								}
								else if($_GET['5e9801ff0f0e750980005e4b']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9801ff0f0e750980005e4b.actual','!=',''
									);
								}
							}
							
							// step#14
							if(!empty($_GET['5e9801ff0f0e750980005e4c'])){
								if($_GET['5e9801ff0f0e750980005e4c']=='Blank'){
									$remove_cancel=1;
									
									$where[]=array(
										'5e9801ff0f0e750980005e4c.actual','=',''
									);
									
								}
								else if($_GET['5e9801ff0f0e750980005e4c']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
										'5e9801ff0f0e750980005e4c.actual','!=',''
									);
								}
							}
							// step#15
							
							if(!empty($_GET['5e9801ff0f0e750980005e4d'])){								
								if(in_array('Blank', $_GET['5e9801ff0f0e750980005e4d'])){
									array_push($_GET['5e9801ff0f0e750980005e4d'], "");
								}
								$remove_cancel=1;
								$where[]=array(
											'payment_term','like','%pdc%'
									);
									
								$whereIn['5e9801ff0f0e750980005e4d.dropdownlist']=$_GET['5e9801ff0f0e750980005e4d'];
							}
							
							
							// step#16
							if(!empty($_GET['5e9946af0f0e75131c0037f5'])){
								if($_GET['5e9946af0f0e75131c0037f5']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9946af0f0e75131c0037f5.act_qty','=',''
									);
									
								}
								else if($_GET['5e9946af0f0e75131c0037f5']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
										'5e9946af0f0e75131c0037f5.act_qty','!=',''
									);
								}
							}
							
							// step#17
							if(!empty($_GET['5e9946b00f0e75131c0037f6'])){
								if($_GET['5e9946b00f0e75131c0037f6']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','=',''
									);
									
									$where[]=array(
									'5e9801ff0f0e750980005e4c.actual','!=',''
									);
								}
								else if($_GET['5e9946b00f0e75131c0037f6']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','!=',''
									);
								}
							}
							
							// step#18
							if(!empty($_GET['5e9946b00f0e75131c0037f7'])){
								if($_GET['5e9946b00f0e75131c0037f7']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','=',''
									);
									
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','!=',''
									);
								}
								else if($_GET['5e9946b00f0e75131c0037f7']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','!=',''
									);
								}
							}
							
							// step#19
							if(!empty($_GET['5e9946b00f0e75131c0037f8'])){
								if($_GET['5e9946b00f0e75131c0037f8']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9946b00f0e75131c0037f8.none_date','=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e4d.dropdownlist','=','COD'
									);
								}else if($_GET['5e9946b00f0e75131c0037f8']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
										'5e9946b00f0e75131c0037f8.none_date','!=',''
									);
								}
								
							}
							
							// step#20							
							if(!empty($_GET['5e9a9ecb0f0e750494006a33'])){
								if($_GET['5e9a9ecb0f0e750494006a33']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'5e9a9ecb0f0e750494006a33.inv_date','=',''
									);
									
								}else if($_GET['5e9a9ecb0f0e750494006a33']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
										'5e9a9ecb0f0e750494006a33.inv_date','!=',''
									);
								}
								
							}
							
							// step#21
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a34'])){
								if($_GET['5e9a9ecb0f0e750494006a34']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e9a9ecb0f0e750494006a34.actual','=',''
									);
									
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','!=',''
									);
								}
								else if($_GET['5e9a9ecb0f0e750494006a34']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9a9ecb0f0e750494006a34.actual','!=',''
									);
								}
							}
							
							// step#22
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a35'])){
								if(in_array('Blank', $_GET['5e9a9ecb0f0e750494006a35'])){
									$remove_cancel=1;
									array_push($_GET['5e9a9ecb0f0e750494006a35'], "");
								}
								$whereIn['5e9a9ecb0f0e750494006a35.dropdownlist']=$_GET['5e9a9ecb0f0e750494006a35'];
							}
							
							// step#23
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a36'])){
								if($_GET['5e9a9ecb0f0e750494006a36']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
									'5e9a9ecb0f0e750494006a36.actual','=',''
									);
									
									$where[]=array(
									'5e9a9ecb0f0e750494006a33.inv_date','!=',''
									);
								}
								else if($_GET['5e9a9ecb0f0e750494006a36']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9a9ecb0f0e750494006a36.actual','!=',''
									);
								}
							}
							
							// step#24 
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a37'])){
								if($_GET['5e9a9ecb0f0e750494006a37']=='Blank'){
									$remove_cancel=1;
									$where[]=array(
										'payment_term','like','%pdc%'
									);
									
									$where[]=array(
									'5e9a9ecb0f0e750494006a37.actual','=',''
									);
									
									$where[]=array(
									'5e9a9ecb0f0e750494006a33.inv_date','!=',''
									);
								}
								else if($_GET['5e9a9ecb0f0e750494006a37']=='Filled'){
									$remove_cancel=1;
									$where[]=array(
									'5e9a9ecb0f0e750494006a37.actual','!=',''
									);
								}
							}
							
							// step#25 
							
							/* 
								fup_from=01-09-2020&fup_to=30-09-2020
								$date_st = date('Y-m-01');						
								$date_en = date('Y-m-d', strtotime($date_st.' +1 month'));
											
								$date_start = new \DateTime($date_st);
								$date_end = new \DateTime($date_en);
							*/
							$fup_from ='';
							$fup_to ='';							
							if(!empty($_GET['fup_from']) || !empty($_GET['fup_to'])){
								
								if(!empty($_GET['fup_from'])){
									$fup_from =date('Y-m-d', strtotime($_GET['fup_from']));
									
									$where[]=array(
										'5ea6c5b4d770121a0c2f6c52.none_date','>=',$fup_from
									);
								}
								
								if(!empty($_GET['fup_to'])){
									$fup_to =date('Y-m-d', strtotime($_GET['fup_to']."+ 1 day"));
									
									$where[]=array(
										'5ea6c5b4d770121a0c2f6c52.none_date','<',$fup_to
									);
								}
								
								$where[]=array(
										'5ea6c5b4d770121a0c2f6c52.none_date','!=',''
									);
							}
							
							
							/* ****** Start Task DATA ***** */
							// Pi No.
							if(!empty($_GET['invoice_no'])){
								$where[]=array(
										'invoice_no','=',$_GET['invoice_no']
									);
							}
							
							// Pi Date 
							$pi_from ='';
							$pi_to ='';							
							if(!empty($_GET['pi_from']) || !empty($_GET['pi_to'])){
								
								if(!empty($_GET['pi_from'])){
									$pi_from =date('Y-m-d', strtotime($_GET['pi_from']));
									
									$where[]=array(
										'pi_date','>=',$pi_from
									);
								}
								
								if(!empty($_GET['pi_to'])){
									$pi_to =date('Y-m-d', strtotime($_GET['pi_to']."+ 1 day"));
									
									$where[]=array(
										'pi_date','<',$pi_to
									);
								}
								
							}
							
							// Buyer Name							
							if(!empty($_GET['customer_data'])){
								$where[]=array(
										'customer_data.c_value','=',trim($_GET['customer_data'])
									);
							}
							
							
							
							// brand_data Name							
							if(!empty($_GET['brand_data'])){
								$where[]=array(
										'brand_data.b_value','=',trim($_GET['brand_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								//pr($_GET['item_data']); die;
								/* $where[]=array(
										'item_data.i_value','=',$_GET['item_data']
									); */
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Colour							
							if(!empty($_GET['color'])){
								$where[]=array(
										'color','=',$_GET['color']
									);
							}
							
							// Sales Person
							if(!empty($_GET['s_person_data'])){
								$remove_cancel=1;
								/* $where[]=array(
										's_person_data.s_value','=',trim($_GET['s_person_data'])
									); */
								$whereIn['s_person_data.s_value']=$_GET['s_person_data'];
							}
							
							// crm_data					
							if(!empty($_GET['crm_data'])){
								$remove_cancel=1;
								$where[]=array(
										'crm_data.c_value','=',trim($_GET['crm_data'])
									);
							}
							
							// merchant_data							
							if(!empty($_GET['merchant_data'])){
								$remove_cancel=1;
								$where[]=array(
										'merchant_data.m_value','=',trim($_GET['merchant_data'])
									);
							}
							
							//PI Status
							if(!empty($_GET['pi_status'])){
								$where[]=array(
										'pi_status','=',$_GET['pi_status']
									);
							}
							/* ****** End Task DATA ***** */
							
							$limit=50;
							if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
								$limit=500;
							}
							
							
							
							/* if any step selected as blank the remove all cancelled order */
							if($remove_cancel==1){ 
								//die('===remove_cancel==');
								/* Remove cancelled order */
									
									if(!empty($_GET['5e8ef2c80f0e75228400661b']) && $_GET['5e8ef2c80f0e75228400661b']!='Cancelled'){
										$where[]=array(
											'5e8ef2c80f0e75228400661b.dropdown','!=','Cancelled'
										);
									}
									
									$where[]=array(
										'5e9a9ecb0f0e750494006a35.dropdownlist','!=','Cancelled'
									);
							}
							
								
							//echo '<pre>';print_r($where);	 5e8ef2c80f0e75228400661b S#5 id
							// if step#5 blank
							if(!empty($_GET['5e8ef2c80f0e75228400661b']) && $_GET['5e8ef2c80f0e75228400661b']=='Blank'){
								
								$fmsdatas_pg = DB::table($fms_table);
								$fmsdatas_pg = $fmsdatas_pg->where($where);
								$fmsdatas_pg = $fmsdatas_pg->where(function($q){
												   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
													 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
												});
								foreach($whereIn as $column=>$value){
									$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
								}				
								$fmsdatas_pg = $fmsdatas_pg->paginate($limit)->toarray();
							
								
								$all_data 	= DB::table($fms_table);
								$all_data 	 =	$all_data->where($where);
								$all_data 	 =	$all_data->where(function($q){
													   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
														 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
													});
								foreach($whereIn as $column=>$value){
									$all_data =	$all_data->whereIn($column,$value);
								}				
								$all_data 	 =	$all_data->paginate($limit);
												
							}elseif(!empty($_GET['5e9801ff0f0e750980005e4c']) && $_GET['5e9801ff0f0e750980005e4c']=='Blank'){
								/// if step#14 blank 
								//pr($where); pr($whereIn);  die;
								
								$fmsdatas_pg = DB::table($fms_table);
								$fmsdatas_pg =	$fmsdatas_pg->where($where);
												
												/* ->where(function($q){
												   $q->where('5e8ef2c80f0e75228400661b.dropdown','!=', 'Cancelled')
													 ->orWhere('5e9a9ecb0f0e750494006a35.dropdownlist','!=', 'Cancelled');
												}) */
												
								$fmsdatas_pg =	$fmsdatas_pg->where(function($q){
												   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
													 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
												});
								$fmsdatas_pg =	$fmsdatas_pg->where('5e8ef2c80f0e75228400661b.dropdown', 'Approved');
								foreach($whereIn as $column=>$value){
									$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
								}
								$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();
							
								$all_data 	 = DB::table($fms_table);
								$all_data 	=	$all_data->where($where);
												
												/* ->where(function($q){
												   $q->where('5e8ef2c80f0e75228400661b.dropdown','!=', 'Cancelled')
													 ->orWhere('5e9a9ecb0f0e750494006a35.dropdownlist','!=', 'Cancelled');
												}) */
												
								$all_data 	=	$all_data->where(function($q){
													   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
														 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
													});
								$all_data 	=	$all_data->where('5e8ef2c80f0e75228400661b.dropdown', 'Approved');
								
								foreach($whereIn as $column=>$value){
									$all_data =	$all_data->whereIn($column,$value);
								}
									
								$all_data 	=	$all_data->paginate($limit);
							}else{
									//pr($where);
									//die('jii');
									$fmsdatas_pg = DB::table($fms_table);
									$fmsdatas_pg =	$fmsdatas_pg->where($where);
									
									foreach($whereIn as $column=>$value){
										$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
									}
									
									$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

									
									$all_data 	= DB::table($fms_table);
									$all_data 	=	$all_data->where($where);
									
									
									foreach($whereIn as $column=>$value){
										$all_data =	$all_data->whereIn($column,$value);
									}
									$all_data 	=	$all_data->paginate($limit);
							}		
											
				}else{
					
						$limit=50;
						if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
							$limit=500;
						}
							
				$fmsdatas_pg 	= DB::table($fms_table)
									->where('fms_id',$fms_id)
									->paginate($limit)->toarray();
					
				$all_data 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit);
				}
						
						
			
			$fmsdatas = $fmsdatas_pg['data']; 
			
			//pr($fmsdatas); die;
  
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.pi_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.pi_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
			
			
		}else if($fms['fms_type']=='pi_fms_sample'){
				
			/* ///////////========== Update PI and PI row data into FMS data collection start =========///////////// */			
			$up = 0; // set 1 if want to update
			if($up==1){
				$fms_table_data = DB::table($fms_table)->select('id','pi_id')->get()->toArray();
				//$fms_table_data = DB::table($fms_table)->select('id','pi_id')->where('pi_id', '5f479aabd0490422d115e732')->get()->toArray();

				$pi_index=0;							
				$oldPi='';
				$cn=1;
			
			foreach($fms_table_data as $fkey=>$fdata){
				$oid = (array) $fdata['_id'];
				$data_id = $oid['oid'];
				
						$pi_id=$fdata['pi_id'];
						if($oldPi!=$pi_id){
							$pi_index=0;
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];
						}else{
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];							
						}						
						$oldPi = $pi_id;
						//pr($pi_data); die;
						$pi_index++;
						
						//pr($piMain);
						//pr($piRows);
						
						// update data start
						/// Pi data 
						//$order_type = $piMain['order_type'];
						$fms_data =array();

						if(is_array($piMain) && array_key_exists('buyer_po_number',  $piMain)){
							$buyer_po_number = $piMain['buyer_po_number'];
						}else{
							$buyer_po_number = '';
						}
						
						$fms_data['buyer_po_number']=$buyer_po_number;
						//echo $buyer_po_number; die('samle==');
						
						/* $buyer_data = array(
													'b_id'=>$piMain['buyer_name'],
													'b_value'=>trim(GetBuyerName($piMain['buyer_name']))
											);
											
						$customer_data = array(
													'c_id'=>$piMain['customer_name'],
													'c_value'=>trim(GetBuyerName($piMain['customer_name']))
											);
							
						$s_person_data = array(
												's_id'=>$piMain['sales_agent'],
												's_value'=>trim(getAgentMerchant($piMain['sales_agent']))
											);
											
						$brand_data = array(
												'b_id'=>$piMain['brand_name'],
												'b_value'=>trim(GetBrandName($piMain['brand_name']))
											); */
						
						//// CRM assign 
						/* $crm_data='';
						if(array_key_exists('crm_assign', $piMain) && $piMain['crm_assign']!=''){
							$crm_data=array(
												'c_id'=>$piMain['crm_assign'],
												'c_value'=>trim(getAgentMerchant($piMain['crm_assign']))
											);
						} */
						//// Merchant assign
						/* $merchant_data ='';
						if(array_key_exists('merchant_name', $piMain) && $piMain['merchant_name']!=''){
							$merchant_data=array(
												'm_id'=>$piMain['merchant_name'],
												'm_value'=>trim(getAgentMerchant($piMain['merchant_name']))
											);
						} */
						///Pi data
						
						// row data 
						//$fms_data['etd']=$piRows['etd'];
						$roid = (array) $piRows['_id'];
						$row_id = $roid['oid']; 
						
						/* $fms_data['item_row']=$row_id;
						$fms_data['invoice_no']= $piMain['po_serial_number'];
						$fms_data['pi_date']= $piMain['proforma_invoice_date']; */
						
						/* $fms_data['buyer_data'] = $buyer_data;
						$fms_data['customer_data'] = $customer_data;
						$fms_data['s_person_data'] = $s_person_data;
						$fms_data['brand_data'] = $brand_data; */
						
						//$fms_data['pi_status'] = $piMain['status'];
						
						/* $fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>trim(getItemName($piRows['item']))
											); */
											
						/* $fms_data['color'] = $piRows['colour'];
						$fms_data['bulkqty'] = $piRows['bulkqty'];
						$fms_data['ssqty'] = $piRows['ssqty'];
						$fms_data['unit'] = $piRows['unit'];
						$fms_data['unit_price'] = $piRows['unit_price'];
						$fms_data['lab_dip'] = $piRows['lab_dip']; */
						
						/* $fms_data['crm_data'] = $crm_data;
						$fms_data['merchant_data'] = $merchant_data; */
						
						/* $fms_data['fob_sample'] = $piMain['fob_sample_approval'];
						$fms_data['fpt_testing'] = $piMain['fpt_testing_approval'];
						$fms_data['payment_term'] = $piMain['payment_term'];
						$fms_data['mode_of_transport'] = $piMain['mode_of_transport']; */
						
						//pr($fms_data); die;
						DB::table($fms_table)->where('_id', $data_id)->where('pi_id', $pi_id)->update($fms_data);
						// update data END
				
				echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
				$cn++;
			}
			
			//pr($fms_table_data); die;
			die('DONE ALL');
		}			
			/* ///////////========== Update PI and PI row data into FMS data collection END =========/////////////// */
				
				$limit=50;
				if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
					$limit=500;
				}
			
				if(isset($_GET) && !empty($_GET)){
						$orWhere = array();
						$whereIn = array();
						$where = array(
								array(
										'fms_id', '=', $fms_id
									)
							);
							
							// step#1
							if(!empty($_GET['5eb25b870f0e750c5c0020d2'])){
								if($_GET['5eb25b870f0e750c5c0020d2']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d2.actual','=',''
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d2']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d2.actual','!=',''
									);
								}
								
							}
							
							// step#2
							if(!empty($_GET['5eb25b870f0e750c5c0020d3'])){
								if($_GET['5eb25b870f0e750c5c0020d3']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d3.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d2.dropdown','=','No'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d3']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d3.actual','!=',''
									);
								}
							}
							
							// step#3
							if(!empty($_GET['5eb25b870f0e750c5c0020d4'])){
								if($_GET['5eb25b870f0e750c5c0020d4']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d4.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d3.dropdown','=','No'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d4']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d4.actual','!=',''
									);
								}
							}
							
							// step#4
							if(!empty($_GET['5eb25b870f0e750c5c0020d5'])){
								if($_GET['5eb25b870f0e750c5c0020d5']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d5.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d3.dropdown','=','Yes'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d5']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d5.actual','!=',''
									);
								}
							}
							
							//step#5
							//step#6
							
							if(!empty($_GET['5eb25b870f0e750c5c0020d7'])){								
								if(in_array('Blank', $_GET['5eb25b870f0e750c5c0020d7'])){
									array_push($_GET['5eb25b870f0e750c5c0020d7'], "");
								}								
								$where[]=array(
											'5eb25b870f0e750c5c0020d2.dropdown','=','No'
									);
									
								$whereIn['5eb25b870f0e750c5c0020d7.dropdownlist']=$_GET['5eb25b870f0e750c5c0020d7'];
							}
							
							//step#7
							if(!empty($_GET['5eb2ac550f0e750c5c0020e8'])){
								if($_GET['5eb2ac550f0e750c5c0020e8']=='Blank'){
									$where[]=array(
									'5eb2ac550f0e750c5c0020e8.actual','=',''
									);
									
								$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','!=','Cancelled'
									);
									
								}
								else if($_GET['5eb2ac550f0e750c5c0020e8']=='Filled'){
									$where[]=array(
									'5eb2ac550f0e750c5c0020e8.actual','!=',''
									);
									
									$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','!=','Cancelled'
									);
								}
							}
							
							
							
							//step#8							
							if(!empty($_GET['5eb2ac560f0e750c5c0020e9'])){								
								if(in_array('Blank', $_GET['5eb2ac560f0e750c5c0020e9'])){
									array_push($_GET['5eb2ac560f0e750c5c0020e9'], "");
								}								
									
								$whereIn['5eb2ac560f0e750c5c0020e9.dropdownlist']=$_GET['5eb2ac560f0e750c5c0020e9'];
							}
							
							//step#9
							if(!empty($_GET['5eb2ac560f0e750c5c0020ea'])){
								if($_GET['5eb2ac560f0e750c5c0020ea']=='Blank'){
									$where[]=array(
										'5eb2ac560f0e750c5c0020ea.inv_date','=',''
									);
									
								}else if($_GET['5eb2ac560f0e750c5c0020ea']=='Filled'){
									$where[]=array(
										'5eb2ac560f0e750c5c0020ea.inv_date','!=',''
									);
								}
								
							}
							//step#10
							/* if(!empty($_GET['5eb2ac560f0e750c5c0020eb'])){
								$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','=',$_GET['5eb2ac560f0e750c5c0020eb']
									);
							} */
							
							if(!empty($_GET['5eb2ac560f0e750c5c0020eb'])){								
								if(in_array('Blank', $_GET['5eb2ac560f0e750c5c0020eb'])){
									array_push($_GET['5eb2ac560f0e750c5c0020eb'], "");
								}
								$whereIn['5eb2ac560f0e750c5c0020eb.dropdownlist']=$_GET['5eb2ac560f0e750c5c0020eb'];
							}
							
							/* ****** Start Task DATA ***** */
							// Pi No.
							if(!empty($_GET['invoice_no'])){
								$where[]=array(
										'invoice_no','=',$_GET['invoice_no']
									);
							}
							
							// Pi Date 
							$pi_from ='';
							$pi_to ='';							
							if(!empty($_GET['pi_from']) || !empty($_GET['pi_to'])){
								
								if(!empty($_GET['pi_from'])){
									$pi_from =date('Y-m-d', strtotime($_GET['pi_from']));
									
									$where[]=array(
										'pi_date','>=',$pi_from
									);
								}
								
								if(!empty($_GET['pi_to'])){
									$pi_to =date('Y-m-d', strtotime($_GET['pi_to']."+ 1 day"));
									
									$where[]=array(
										'pi_date','<',$pi_to
									);
								}
								
							}
							
							// Buyer Name							
							if(!empty($_GET['customer_data'])){
								$where[]=array(
										'customer_data.c_value','=',trim($_GET['customer_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								/* $where[]=array(
										'item_data.i_value','=',$_GET['item_data']
									); */
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Colour							
							if(!empty($_GET['color'])){
								$where[]=array(
										'color','=',$_GET['color']
									);
							}
							
							// Sales Person							
							if(!empty($_GET['s_person_data'])){
								/* $where[]=array(
										's_person_data.s_value','=',trim($_GET['s_person_data'])
									); */
									$whereIn['s_person_data.s_value']=$_GET['s_person_data'];
							}
							
							// crm_data					
							if(!empty($_GET['crm_data'])){
								$where[]=array(
										'crm_data.c_value','=',trim($_GET['crm_data'])
									);
							}
							
							// merchant_data							
							if(!empty($_GET['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($_GET['merchant_data'])
									);
							}
							
							// brand_data Name							
							if(!empty($_GET['brand_data'])){
								$where[]=array(
										'brand_data.b_value','=',trim($_GET['brand_data'])
									);
							}
							
							//PI Status
							if(!empty($_GET['pi_status'])){
								$where[]=array(
										'pi_status','=',$_GET['pi_status']
									);
							}
							/* ****** End Task DATA ***** */
							
							$fmsdatas_pg = DB::table($fms_table);
							$fmsdatas_pg =	$fmsdatas_pg->where($where);
							
							foreach($whereIn as $column=>$value){
								$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
							}
							
							$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

							
							$all_data 	= DB::table($fms_table);
							$all_data 	=	$all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							
							$all_data 	=	$all_data->paginate($limit);
					
				}else{
				$fmsdatas_pg 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit)->toarray();

				$all_data 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate($limit);
				}
				$fmsdatas = $fmsdatas_pg['data'];
			
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.pi_sampling_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.pi_sampling_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
			
			
		}else if($fms['fms_type']=='po_fms_import'){
			
				/* //////========== Update PO and PO row data into FMS data collection start =========///// */
				$up = 0; // set 1 if want to update
				if($up==1){
					$fms_table_data = DB::table($fms_table)->select('id','po_id')->get()->toArray();
					//$fms_table_data = DB::table($fms_table)->select('id','po_id')->where('po_id', '5f07f796d04904376a2d8f12')->get()->toArray();
					//pr($fms_table_data); die;
					$pi_index=0;							
					$oldPi='';
					$cn=1;
					
				foreach($fms_table_data as $fkey=>$fdata){
						$oid = (array) $fdata['_id'];
						$data_id = $oid['oid'];
					
							$pi_id=$fdata['po_id'];
							if($oldPi!=$pi_id){
								$pi_index=0;
								$piMain = getPoDataByPoId($pi_id);
								$piRows = getPoRowDataByPoId($pi_id)[$pi_index];
							}else{
								$piMain = getPoDataByPoId($pi_id);
								$piRows = getPoRowDataByPoId($pi_id)[$pi_index];							
							}						
							$oldPi = $pi_id;
							//pr($pi_data); die;
							$pi_index++;
							//pr($piMain);
							//pr($piRows);  die;
							
							// update data start
							/// Po data 
							$order_type = $piMain['order_type'];
							$fms_data =array();							
							/* 
							$fms_data['etd']=$piRows['etd'];
							$roid = (array) $piRows['_id'];
							$row_id = $roid['oid'];
							$fms_data['item_row']=$row_id;
							
							
							$supplier_data = array(
												's_id'=>$piMain['brand_name'],
												's_value'=>trim(getAllSupplier($piMain['brand_name'])['company_name'])
											);
							
							$fms_data['invoice_no']= $piMain['po_serial_number'];
							$fms_data['po_date']= $piMain['po_date'];
							$fms_data['supplier_data']= $supplier_data;
							
							$fms_data['po_type']= $piMain['po_type'];
							$fms_data['order_type']= $piMain['order_type'];
							
							$fms_data['fob_sample']= $piMain['fob_sample'];
							$fms_data['test_report']= $piMain['test_report'];
							$fms_data['inspection_report']= $piMain['inspection_report'];
							$fms_data['payment_terms']= $piMain['payment_terms'];
							$fms_data['incoterms']= $piMain['incoterms'];
							$fms_data['final_destination']= $piMain['final_destination'];
							$fms_data['mode_of_transport']= $piMain['mode_of_transport'];
							$fms_data['transit_time']= $piMain['transit_time'];
							$fms_data['pi_reference']= $piMain['pi_reference'];
							$fms_data['order_status']= $piMain['order_status'];
							$fms_data['documents_prepared_by']= $piMain['documents_prepared_by'];
							
						
							$fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>getItemName($piRows['item'])
											);
													
							$fms_data['color'] = $piRows['colour'];
							$fms_data['lab_dip'] = $piRows['lab_dip_no'];
							$fms_data['order_qty'] = $piRows['order_qty'];
							$fms_data['ss_qty'] = $piRows['ss_qty'];
							$fms_data['unit'] = $piRows['unit'];
							$fms_data['unit_price'] = $piRows['unit_price'];
							 */
							$fms_data['factory_code'] = $piRows['factory_code'];
							/* New code END */
							///pr($fms_data); die; 
							DB::table($fms_table)->where('_id', $data_id)->where('po_id', $pi_id)->update($fms_data);
							// update data END
					
					//echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
					echo $cn.'==data_id'.$data_id.'=====<br/>';
					$cn++;
				}
				//pr($fms_table_data); die;
				die('DONE ALL');
			}
			/* //////========== Update PO and PO row data into FMS data collection END =========////// */
			//die('EXT--');
			
			$limit=50;
			if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
				$limit=500;
			}
			
			if(isset($_GET) && !empty($_GET)){
						$orWhere = array();
						$whereIn = array();
						$where = array(
								array(
										'fms_id', '=', $fms_id
									)
							);
							
							// step#1
							if(!empty($_GET['5ee34aef0f0e7502dc003d27'])){
								if($_GET['5ee34aef0f0e7502dc003d27']=='Blank'){
									$where[]=array(
									'5ee34aef0f0e7502dc003d27.actual','=',''
									);
								}
								else if($_GET['5ee34aef0f0e7502dc003d27']=='Filled'){
									$where[]=array(
									'5ee34aef0f0e7502dc003d27.actual','!=',''
									);
								}
								
							}
							
							// step#2
							if(!empty($_GET['5f041a6a0b7de90f74c03de5'])){
								if($_GET['5f041a6a0b7de90f74c03de5']=='Blank'){
									$where[]=array(
									'5f041a6a0b7de90f74c03de5.actual','=',''
									);
									
								}
								else if($_GET['5f041a6a0b7de90f74c03de5']=='Filled'){
									$where[]=array(
									'5f041a6a0b7de90f74c03de5.actual','!=',''
									);
								}
								
								$where[]=array(
										'lab_dip','=','Pending'
									);
							}
							
							// step#3
							if(!empty($_GET['5ee34aef0f0e7502dc003d28'])){
								if($_GET['5ee34aef0f0e7502dc003d28']=='Blank'){
									$where[]=array(
									'5ee34aef0f0e7502dc003d28.actual_rev','=',''
									);
									
								}
								else if($_GET['5ee34aef0f0e7502dc003d28']=='Filled'){
									$where[]=array(
									'5ee34aef0f0e7502dc003d28.actual_rev','!=',''
									);
								}
								
							}
							// step#4
							if(!empty($_GET['5ee34aef0f0e7502dc003d29'])){
								if($_GET['5ee34aef0f0e7502dc003d29']=='Blank'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d28.actual_rev','!=',''
									);
									
									$where[]=array(
										'5ee34aef0f0e7502dc003d29.actual','=',''
									);
									
								}
								else if($_GET['5ee34aef0f0e7502dc003d29']=='Filled'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d29.actual','!=',''
									);
								}
								
							}
							
							//step#5
							if(!empty($_GET['5ee34aef0f0e7502dc003d2a'])){
								if($_GET['5ee34aef0f0e7502dc003d2a']=='Blank'){									
									$where[]=array(
										'5ee34aef0f0e7502dc003d2a.actual','=',''
									);
								}
								else if($_GET['5ee34aef0f0e7502dc003d2a']=='Filled'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2a.actual','!=',''
									);
								}
								
								$where[]=array(
										'fob_sample','=','Yes'
									);
								$where[]=array(
										'5ee7671f0f0e750a80007414.dropdownlist','!=','Cancelled'
									);
								
							}
							//step#6
							if(!empty($_GET['5ee34aef0f0e7502dc003d2b'])){
								if($_GET['5ee34aef0f0e7502dc003d2b']=='Blank'){									
									$where[]=array(
										'5ee34aef0f0e7502dc003d2b.actual','=',''
									);
								}
								else if($_GET['5ee34aef0f0e7502dc003d2b']=='Filled'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2b.actual','!=',''
									);
								}
								
								$where[]=array(
										'test_report','=','Yes'
									);
								$where[]=array(
										'5ee7671f0f0e750a80007414.dropdownlist','!=','Cancelled'
									);
							}
							//step#7
							if(!empty($_GET['5ee34aef0f0e7502dc003d2c'])){
								if($_GET['5ee34aef0f0e7502dc003d2c']=='Blank'){									
									$where[]=array(
										'5ee34aef0f0e7502dc003d2c.actual','=',''
									);
								}
								else if($_GET['5ee34aef0f0e7502dc003d2c']=='Filled'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2c.actual','!=',''
									);
								}
								
								$where[]=array(
										'inspection_report','=','Yes'
									);
								$where[]=array(
										'5ee7671f0f0e750a80007414.dropdownlist','!=','Cancelled'
									);
							}
							
							//step#8							
							if(!empty($_GET['5ee34aef0f0e7502dc003d2d'])){
								if($_GET['5ee34aef0f0e7502dc003d2d']=='Blank'){									
									$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','=',''
									);
								}
								else if($_GET['5ee34aef0f0e7502dc003d2d']=='Filled'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee7671f0f0e750a80007414.dropdownlist','!=','Cancelled'
									);
							}
							
							//step#9
							
							//step#10
							if(!empty($_GET['5ee7671f0f0e750a80007413'])){
								if($_GET['5ee7671f0f0e750a80007413']=='Blank'){									
									$where[]=array(
										'5ee7671f0f0e750a80007413.act_qty','=',''
									);
								}
								else if($_GET['5ee7671f0f0e750a80007413']=='Filled'){
									$where[]=array(
										'5ee7671f0f0e750a80007413.act_qty','!=',''
									);
								}
							}
							
							//step#11
							if(!empty($_GET['5ee7671f0f0e750a80007414'])){								
								if(in_array('Blank', $_GET['5ee7671f0f0e750a80007414'])){
									array_push($_GET['5ee7671f0f0e750a80007414'], "");
								}								
								
								$whereIn['5ee7671f0f0e750a80007414.dropdownlist']=$_GET['5ee7671f0f0e750a80007414'];
							}
							
							//step#12
							if(!empty($_GET['5ee767640f0e751c0c003313'])){
								if($_GET['5ee767640f0e751c0c003313']=='Blank'){									
									$where[]=array(
										'5ee767640f0e751c0c003313.actual','=',''
									);
								}
								else if($_GET['5ee767640f0e751c0c003313']=='Filled'){
									$where[]=array(
										'5ee767640f0e751c0c003313.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee7671f0f0e750a80007414.dropdownlist','=','Part Shipped'
									);
								
							}
							
							//step#13
							if(!empty($_GET['5ee8a0040f0e7507780076e8'])){
								if($_GET['5ee8a0040f0e7507780076e8']=='Blank'){									
									$where[]=array(
										'5ee8a0040f0e7507780076e8.actual','=',''
									);
								}
								else if($_GET['5ee8a0040f0e7507780076e8']=='Filled'){
									$where[]=array(
										'5ee8a0040f0e7507780076e8.actual','!=',''
									);
								}
							}
							
							//step#14
							if(!empty($_GET['5ee8a0040f0e7507780076e9'])){
								if($_GET['5ee8a0040f0e7507780076e9']=='Blank'){
									$where[]=array(
										'5ee8a0040f0e7507780076e9.inv_date','=',''
									);
									
								}else if($_GET['5ee8a0040f0e7507780076e9']=='Filled'){
									$where[]=array(
										'5ee8a0040f0e7507780076e9.inv_date','!=',''
									);
								}
								
							}
							//step#15
							if(!empty($_GET['5ee8a0040f0e7507780076ea'])){
								if($_GET['5ee8a0040f0e7507780076ea']=='Blank'){									
									$where[]=array(
										'5ee8a0040f0e7507780076ea.actual','=',''
									);
								}
								else if($_GET['5ee8a0040f0e7507780076ea']=='Filled'){
									$where[]=array(
										'5ee8a0040f0e7507780076ea.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','!=',''
									);
							}
							
							//step#16
							if(!empty($_GET['5ee8a0040f0e7507780076eb'])){
								if($_GET['5ee8a0040f0e7507780076eb']=='Blank'){									
									$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','=',''
									);
								}
								else if($_GET['5ee8a0040f0e7507780076eb']=='Filled'){
									$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076ea.actual','!=',''
									);
							}
							
							//step#17
							if(!empty($_GET['5ee8a0040f0e7507780076ec'])){
								if($_GET['5ee8a0040f0e7507780076ec']=='Blank'){									
									$where[]=array(
										'5ee8a0040f0e7507780076ec.actual','=',''
									);
								}
								else if($_GET['5ee8a0040f0e7507780076ec']=='Filled'){
									$where[]=array(
										'5ee8a0040f0e7507780076ec.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
							}
							
							//step#18
							if(!empty($_GET['5eeb11900f0e750a40003668'])){
								if($_GET['5eeb11900f0e750a40003668']=='Blank'){									
									$where[]=array(
										'5eeb11900f0e750a40003668.actual','=',''
									);
								}
								else if($_GET['5eeb11900f0e750a40003668']=='Filled'){
									$where[]=array(
										'5eeb11900f0e750a40003668.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076ec.dropdown','=','Fail'
									);
							}
							
							//step#19
							if(!empty($_GET['5eeb11900f0e750a40003669'])){
								if($_GET['5eeb11900f0e750a40003669']=='Blank'){									
									$where[]=array(
										'5eeb11900f0e750a40003669.actual','=',''
									);
								}
								else if($_GET['5eeb11900f0e750a40003669']=='Filled'){
									$where[]=array(
										'5eeb11900f0e750a40003669.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
							}
							
							//step#20
							if(!empty($_GET['5eeb11900f0e750a4000366a'])){
								if($_GET['5eeb11900f0e750a4000366a']=='Blank'){									
									$where[]=array(
										'5eeb11900f0e750a4000366a.actual','=',''
									);
								}
								else if($_GET['5eeb11900f0e750a4000366a']=='Filled'){
									$where[]=array(
										'5eeb11900f0e750a4000366a.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
							}
							
							//step#21
							if(!empty($_GET['5eeb11900f0e750a4000366b'])){
								if($_GET['5eeb11900f0e750a4000366b']=='Blank'){									
									$where[]=array(
										'5eeb11900f0e750a4000366b.actual','=',''
									);
								}
								else if($_GET['5eeb11900f0e750a4000366b']=='Filled'){
									$where[]=array(
										'5eeb11900f0e750a4000366b.actual','!=',''
									);
								}
								
								$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
							}
							
							//step#22
							if(!empty($_GET['5eeb11900f0e750a4000366c'])){								
								if(in_array('Blank', $_GET['5eeb11900f0e750a4000366c'])){
									array_push($_GET['5eeb11900f0e750a4000366c'], "");
								}								
								
								$whereIn['5eeb11900f0e750a4000366c.dropdownlist']=$_GET['5eeb11900f0e750a4000366c'];
							}
							
							//step#23
							if(!empty($_GET['5eeb11900f0e750a4000366d'])){
								if($_GET['5eeb11900f0e750a4000366d']=='Blank'){									
									$where[]=array(
										'5eeb11900f0e750a4000366d.actual','=',''
									);
								}
								else if($_GET['5eeb11900f0e750a4000366d']=='Filled'){
									$where[]=array(
										'5eeb11900f0e750a4000366d.actual','!=',''
									);
								}
								
								$where[]=array(
										'5eeb11900f0e750a4000366c.dropdownlist','=','Yes'
									);
								$where[]=array(
										'5ee8a0040f0e7507780076ea.actual','!=',''
									);
							}
							
							//step#24							
							if(!empty($_GET['5eeb11900f0e750a4000366e'])){								
								if($_GET['5eeb11900f0e750a4000366e']=='Under Production'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','=',''
									);
									$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','=',''
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','In Transit'
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','In House'
									);
									
								}elseif($_GET['5eeb11900f0e750a4000366e']=='In Transit'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','!=',''
									);
									$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','=',''
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','Under Production'
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','In House'
									);
									
								}elseif($_GET['5eeb11900f0e750a4000366e']=='In House'){
									$where[]=array(
										'5ee34aef0f0e7502dc003d2d.actual','!=',''
									);
									$where[]=array(
										'5ee8a0040f0e7507780076eb.actual','!=',''
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','Under Production'
									);
									
									$where[]=array(
										'5eeb11900f0e750a4000366e.dropdownlist','!=','In Transit'
									);
								}
							}
							
							/* ****** Start Task DATA ***** */
							// Pi No.
							if(!empty($_GET['invoice_no'])){
								$where[]=array(
										'invoice_no','=',$_GET['invoice_no']
									);
							}
							
							// Po Date 
							$po_from ='';
							$po_to ='';							
							if(!empty($_GET['po_from']) || !empty($_GET['po_to'])){
								
								if(!empty($_GET['po_from'])){
									$po_from =date('Y-m-d', strtotime($_GET['po_from']));
									
									$where[]=array(
										'po_date','>=',$po_from
									);
								}
								
								if(!empty($_GET['po_to'])){
									$po_to =date('Y-m-d', strtotime($_GET['po_to']."+ 1 day"));
									
									$where[]=array(
										'po_date','<',$po_to
									);
								}
								
							}
							
							// supplier_data							
							if(!empty($_GET['supplier_data'])){
								$where[]=array(
										'supplier_data.s_value','=',trim($_GET['supplier_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								/* $where[]=array(
										'item_data.i_value','=',$_GET['item_data']
									); */
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Colour							
							if(!empty($_GET['color'])){
								$where[]=array(
										'color','=',$_GET['color']
									);
							}
							
							
							
							//po_type
							if(!empty($_GET['po_type'])){
								$where[]=array(
										'po_type','=',$_GET['po_type']
									);
							}
							
							// final_destination
							if(!empty($_GET['final_destination'])){
								$where[]=array(
										'final_destination','=',$_GET['final_destination']
									);
							}
							/* ****** End Task DATA ***** */
							
							$fmsdatas_pg = DB::table($fms_table);
							$fmsdatas_pg =	$fmsdatas_pg->where($where);
							
							foreach($whereIn as $column=>$value){
								$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
							}
							
							$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

							
							$all_data 	= DB::table($fms_table);
							$all_data 	=	$all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							
							$all_data 	=	$all_data->paginate($limit);
					
				}else{
					$fmsdatas_pg 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate($limit)->toarray();
							
					$all_data 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit);
				}	
									
			$fmsdatas = $fmsdatas_pg['data'];	
				
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.po_import_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.po_import_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
			
		}else if($fms['fms_type']=='po_fms_local'){
			//echo $fms_table; die;
			/* //////========== Update PO and PO row data into FMS data collection start =========///// */
				$up = 0; // set 1 if want to update 
				if($up==1){
					$fms_table_data = DB::table($fms_table)->select('id','po_id')->get()->toArray();
					//$fms_table_data = DB::table($fms_table)->select('id','po_id')->where('po_id', '5f057b6fd0490469f849a572')->get()->toArray();
					//pr($fms_table_data); die;
					$pi_index=0;							
					$oldPi='';
					$cn=1;
					
				foreach($fms_table_data as $fkey=>$fdata){
						$oid = (array) $fdata['_id'];
						$data_id = $oid['oid'];
					
							$pi_id=$fdata['po_id'];
							if($oldPi!=$pi_id){
								$pi_index=0;
								$piMain = getPoDataByPoId($pi_id);
								$piRows = getPoRowDataByPoId($pi_id)[$pi_index];
							}else{
								$piMain = getPoDataByPoId($pi_id);
								$piRows = getPoRowDataByPoId($pi_id)[$pi_index];							
							}						
							$oldPi = $pi_id;
							//pr($pi_data); die;
							$pi_index++;
							
							//pr($piMain);
							//pr($piRows);  die;
							
							// update data start
							/// Po data 
							
							/* $order_type = $piMain['order_type'];
							$fms_data =array();							
							
							$fms_data['etd']=$piRows['etd'];
							$roid = (array) $piRows['_id'];
							$row_id = $roid['oid'];
							$fms_data['item_row']=$row_id;
							
						
							$supplier_data = array(
												's_id'=>$piMain['brand_name'],
												's_value'=>getAllSupplier($piMain['brand_name'])['company_name']
											);
							
							$fms_data['invoice_no']= $piMain['po_serial_number'];
							$fms_data['po_date']= $piMain['po_date'];
							$fms_data['supplier_data']= $supplier_data;
							
							$fms_data['po_type']= $piMain['po_type'];
							$fms_data['order_type']= $piMain['order_type'];
							
							$fms_data['fob_sample']= $piMain['fob_sample'];
							$fms_data['test_report']= $piMain['test_report'];
							$fms_data['inspection_report']= $piMain['inspection_report'];
							$fms_data['payment_terms']= $piMain['payment_terms'];
							$fms_data['incoterms']= $piMain['incoterms'];
							$fms_data['final_destination']= $piMain['final_destination'];
							$fms_data['mode_of_transport']= $piMain['mode_of_transport'];
							$fms_data['transit_time']= $piMain['transit_time'];
							$fms_data['pi_reference']= $piMain['pi_reference'];
							$fms_data['order_status']= $piMain['order_status'];
							$fms_data['documents_prepared_by']= $piMain['documents_prepared_by'];
							
						
							
							$fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>getItemName($piRows['item'])
											);
													
							$fms_data['color'] = $piRows['colour'];
							$fms_data['lab_dip'] = $piRows['lab_dip_no'];
							$fms_data['order_qty'] = $piRows['order_qty'];
							$fms_data['ss_qty'] = $piRows['ss_qty'];
							$fms_data['unit'] = $piRows['unit'];
							$fms_data['unit_price'] = $piRows['unit_price'];	
							 */
							
							$fms_data['6152d5f61ef129057899cd81'] = array(
																			'comment'=>$piRows['remark']
																		);
							
							/* New code END */
							///pr($fms_data); die;
							DB::table($fms_table)->where('_id', $data_id)->where('po_id', $pi_id)->update($fms_data);
							// update data END
					
					//echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
					echo $cn.'==data_id'.$data_id.'=====<br/>';
					$cn++;
				}
				
				//pr($fms_table_data); die;
				die('DONE ALL');
			}
			/* //////========== Update PO and PO row data into FMS data collection END =========////// */
			//die('EXT--');
			
			$limit=50;
			if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
				$limit=500;
			}
			
				if(isset($_GET) && !empty($_GET)){
						$orWhere = array();
						$whereIn = array();
						$where = array(
								array(
										'fms_id', '=', $fms_id
									)
							);
							
							// step#1
							if(!empty($_GET['5f06e3af2e977d1dd0e4c6ff'])){
								if($_GET['5f06e3af2e977d1dd0e4c6ff']=='Blank'){
									$where[]=array(
									'5f06e3af2e977d1dd0e4c6ff.actual','=',''
									);
								}
								else if($_GET['5f06e3af2e977d1dd0e4c6ff']=='Filled'){
									$where[]=array(
									'5f06e3af2e977d1dd0e4c6ff.actual','!=',''
									);
								}
								
							}
							
							// step#2
							if(!empty($_GET['5f06e3ce2e977d1dd0e4c700'])){
								if($_GET['5f06e3ce2e977d1dd0e4c700']=='Blank'){
									$where[]=array(
									'5f06e3ce2e977d1dd0e4c700.actual','=',''
									);
									
								}
								else if($_GET['5f06e3ce2e977d1dd0e4c700']=='Filled'){
									$where[]=array(
									'5f06e3ce2e977d1dd0e4c700.actual','!=',''
									);
								}
								
								$where[]=array(
										'lab_dip','=','Pending'
									);
							}
							
							// step#3
							if(!empty($_GET['5f06e3d52e977d1dd0e4c701'])){
								if($_GET['5f06e3d52e977d1dd0e4c701']=='Blank'){
									$where[]=array(
									'5f06e3d52e977d1dd0e4c701.actual_rev','=',''
									);
									
								}
								else if($_GET['5f06e3d52e977d1dd0e4c701']=='Filled'){
									$where[]=array(
									'5f06e3d52e977d1dd0e4c701.actual_rev','!=',''
									);
								}
								
							}
							// step#4
							if(!empty($_GET['5f06e3dc2e977d1dd0e4c702'])){
								if($_GET['5f06e3dc2e977d1dd0e4c702']=='Blank'){
									$where[]=array(
										'5f06e3d52e977d1dd0e4c701.actual_rev','!=',''
									);
									
									$where[]=array(
										'5f06e3dc2e977d1dd0e4c702.actual','=',''
									);
									
								}
								else if($_GET['5f06e3dc2e977d1dd0e4c702']=='Filled'){
									$where[]=array(
										'5f06e3dc2e977d1dd0e4c702.actual','!=',''
									);
								}
								
							}
							
							//step#5
							if(!empty($_GET['5f06e3e32e977d1dd0e4c703'])){
								if($_GET['5f06e3e32e977d1dd0e4c703']=='Blank'){									
									$where[]=array(
										'5f06e3e32e977d1dd0e4c703.actual','=',''
									);
								}
								else if($_GET['5f06e3e32e977d1dd0e4c703']=='Filled'){
									$where[]=array(
										'5f06e3e32e977d1dd0e4c703.actual','!=',''
									);
								}
								
								$where[]=array(
										'fob_sample','=','Yes'
									);
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','!=','Cancelled'
									);
								
							}
							//step#6
							if(!empty($_GET['5f06e3ec2e977d1dd0e4c704'])){
								if($_GET['5f06e3ec2e977d1dd0e4c704']=='Blank'){									
									$where[]=array(
										'5f06e3ec2e977d1dd0e4c704.actual','=',''
									);
								}
								else if($_GET['5f06e3ec2e977d1dd0e4c704']=='Filled'){
									$where[]=array(
										'5f06e3ec2e977d1dd0e4c704.actual','!=',''
									);
								}
								
								$where[]=array(
										'test_report','=','Yes'
									);
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','!=','Cancelled'
									);
							}
							//step#7
							if(!empty($_GET['5f06e3f32e977d1dd0e4c705'])){
								if($_GET['5f06e3f32e977d1dd0e4c705']=='Blank'){									
									$where[]=array(
										'5f06e3f32e977d1dd0e4c705.actual','=',''
									);
								}
								else if($_GET['5f06e3f32e977d1dd0e4c705']=='Filled'){
									$where[]=array(
										'5f06e3f32e977d1dd0e4c705.actual','!=',''
									);
								}
								
								$where[]=array(
										'inspection_report','=','Yes'
									);
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','!=','Cancelled'
									);
							}
							
							//step#8							
							if(!empty($_GET['5f06e3fb2e977d1dd0e4c706'])){
								if($_GET['5f06e3fb2e977d1dd0e4c706']=='Blank'){									
									$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','=',''
									);
								}
								else if($_GET['5f06e3fb2e977d1dd0e4c706']=='Filled'){
									$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','!=','Cancelled'
									);
							}
							
							//step#9
							
							//step#10
							if(!empty($_GET['5f06e40c2e977d1dd0e4c708'])){
								if($_GET['5f06e40c2e977d1dd0e4c708']=='Blank'){									
									$where[]=array(
										'5f06e40c2e977d1dd0e4c708.act_qty','=',''
									);
								}
								else if($_GET['5f06e40c2e977d1dd0e4c708']=='Filled'){
									$where[]=array(
										'5f06e40c2e977d1dd0e4c708.act_qty','!=',''
									);
								}
							}
							
							//step#11
							if(!empty($_GET['5f06e4142e977d1dd0e4c709'])){								
								if(in_array('Blank', $_GET['5f06e4142e977d1dd0e4c709'])){
									array_push($_GET['5f06e4142e977d1dd0e4c709'], "");
								}								
								
								$whereIn['5f06e4142e977d1dd0e4c709.dropdownlist']=$_GET['5f06e4142e977d1dd0e4c709'];
							}
							
							//step#12
							if(!empty($_GET['5f06e41c2e977d1dd0e4c70a'])){
								if($_GET['5f06e41c2e977d1dd0e4c70a']=='Blank'){									
									$where[]=array(
										'5f06e41c2e977d1dd0e4c70a.actual','=',''
									);
								}
								else if($_GET['5f06e41c2e977d1dd0e4c70a']=='Filled'){
									$where[]=array(
										'5f06e41c2e977d1dd0e4c70a.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','=','Part Shipped'
									);
								
							}
							
							//step#13
							if(!empty($_GET['5f06e4452e977d1dd0e4c70b'])){
								if($_GET['5f06e4452e977d1dd0e4c70b']=='Blank'){									
									$where[]=array(
										'5f06e4452e977d1dd0e4c70b.actual','=',''
									);
								}
								else if($_GET['5f06e4452e977d1dd0e4c70b']=='Filled'){
									$where[]=array(
										'5f06e4452e977d1dd0e4c70b.actual','!=',''
									);
								}
							}
							
							//step#14
							if(!empty($_GET['5f06e4552e977d1dd0e4c70c'])){
								if($_GET['5f06e4552e977d1dd0e4c70c']=='Blank'){
									$where[]=array(
										'5f06e4552e977d1dd0e4c70c.inv_date','=',''
									);
									
								}else if($_GET['5f06e4552e977d1dd0e4c70c']=='Filled'){
									$where[]=array(
										'5f06e4552e977d1dd0e4c70c.inv_date','!=',''
									);
								}
								
							}
							//step#15
							if(!empty($_GET['5f06e47c2e977d1dd0e4c70d'])){
								if($_GET['5f06e47c2e977d1dd0e4c70d']=='Blank'){									
									$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','=',''
									);
								}
								else if($_GET['5f06e47c2e977d1dd0e4c70d']=='Filled'){
									$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','!=',''
									);
									
								$where[]=array(
										'5f06e4142e977d1dd0e4c709.dropdownlist','!=','Cancelled'
									);
							}
							
							//step#16
							if(!empty($_GET['5f06e4902e977d1dd0e4c70e'])){
								if($_GET['5f06e4902e977d1dd0e4c70e']=='Blank'){									
									$where[]=array(
										'5f06e4902e977d1dd0e4c70e.actual','=',''
									);
								}
								else if($_GET['5f06e4902e977d1dd0e4c70e']=='Filled'){
									$where[]=array(
										'5f06e4902e977d1dd0e4c70e.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','!=',''
									);
							}
							
							//step#17
							if(!empty($_GET['5f06e4e82e977d1dd0e4c70f'])){
								if($_GET['5f06e4e82e977d1dd0e4c70f']=='Blank'){									
									$where[]=array(
										'5f06e4e82e977d1dd0e4c70f.actual','=',''
									);
								}
								else if($_GET['5f06e4e82e977d1dd0e4c70f']=='Filled'){
									$where[]=array(
										'5f06e4e82e977d1dd0e4c70f.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e4902e977d1dd0e4c70e.dropdown','=','Fail'
									);
							}
							
							//step#18
							if(!empty($_GET['5f06e5202e977d1dd0e4c710'])){
								if($_GET['5f06e5202e977d1dd0e4c710']=='Blank'){									
									$where[]=array(
										'5f06e5202e977d1dd0e4c710.actual','=',''
									);
								}
								else if($_GET['5f06e5202e977d1dd0e4c710']=='Filled'){
									$where[]=array(
										'5f06e5202e977d1dd0e4c710.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','!=',''
									);
							}
							
							//step#19						
							if(!empty($_GET['5f06e5582e977d1dd0e4c711'])){					
								if($_GET['5f06e5582e977d1dd0e4c711']=='Under Production'){
									$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','=',''
									);
									$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','=',''
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','In Transit'
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','In House'
									);
									
								}elseif($_GET['5f06e5582e977d1dd0e4c711']=='In Transit'){
									$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','!=',''
									);
									$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','=',''
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','Under Production'
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','In House'
									);
									
								}elseif($_GET['5f06e5582e977d1dd0e4c711']=='In House'){
									$where[]=array(
										'5f06e3fb2e977d1dd0e4c706.actual','!=',''
									);
									$where[]=array(
										'5f06e47c2e977d1dd0e4c70d.actual','!=',''
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','Under Production'
									);
									
									$where[]=array(
										'5f06e5582e977d1dd0e4c711.dropdownlist','!=','In Transit'
									);
								}
							}
							
							/* ****** Start Task DATA ***** */
							// Pi No.
							if(!empty($_GET['invoice_no'])){
								$where[]=array(
										'invoice_no','=',$_GET['invoice_no']
									);
							}
							
							// Po Date 
							$po_from ='';
							$po_to ='';							
							if(!empty($_GET['po_from']) || !empty($_GET['po_to'])){
								
								if(!empty($_GET['po_from'])){
									$po_from =date('Y-m-d', strtotime($_GET['po_from']));
									
									$where[]=array(
										'po_date','>=',$po_from
									);
								}
								
								if(!empty($_GET['po_to'])){
									$po_to =date('Y-m-d', strtotime($_GET['po_to']."+ 1 day"));
									
									$where[]=array(
										'po_date','<',$po_to
									);
								}
								
							}
							
							// supplier_data							
							if(!empty($_GET['supplier_data'])){
								$where[]=array(
										'supplier_data.s_value','=',trim($_GET['supplier_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								/* $where[]=array(
										'item_data.i_value','=',$_GET['item_data']
									); */
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Colour							
							if(!empty($_GET['color'])){
								$where[]=array(
										'color','=',$_GET['color']
									);
							}
							
							
							
							//po_type
							if(!empty($_GET['po_type'])){
								$where[]=array(
										'po_type','=',$_GET['po_type']
									);
							}
							/* ****** End Task DATA ***** */
							
							$fmsdatas_pg = DB::table($fms_table);
							$fmsdatas_pg =	$fmsdatas_pg->where($where);
							
							foreach($whereIn as $column=>$value){
								$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
							}
							
							$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

							
							$all_data 	= DB::table($fms_table);
							$all_data 	=	$all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							
							$all_data 	=	$all_data->paginate($limit);
					
				}else{
					$fmsdatas_pg 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate($limit)->toarray();
							
					$all_data 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit);
				}
							
				$fmsdatas = $fmsdatas_pg['data'];
				//pr($fmsdatas_pg); die();		
				
				if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
					return view('fms.po_local_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
				}else{
					return view('fms.po_local_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
				}
				
				
			
			//////
				
		}else if($fms['fms_type']=='sample_cards'){
			
				$limit=50;
				if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
					$limit=500;
				}
			
				if(isset($_GET) && !empty($_GET)){
						$orWhere = array();
						$whereIn = array();
						$where = array(
								array(
										'fms_id', '=', $fms_id
									)
							);
							
							// step#1
							if(!empty($_GET['5f36806e0f0e751744007a44'])){
								if($_GET['5f36806e0f0e751744007a44']=='Blank'){
									$where[]=array(
									'5f36806e0f0e751744007a44.actual','=',''
									);
								}
								else if($_GET['5f36806e0f0e751744007a44']=='Filled'){
									$where[]=array(
									'5f36806e0f0e751744007a44.actual','!=',''
									);
								}
								
							}
							
							// step#2
							if(!empty($_GET['5f36806e0f0e751744007a45'])){
								if($_GET['5f36806e0f0e751744007a45']=='Blank'){
									$where[]=array(
									'5f36806e0f0e751744007a45.actual','=',''
									);
									
								}
								else if($_GET['5f36806e0f0e751744007a45']=='Filled'){
									$where[]=array(
									'5f36806e0f0e751744007a45.actual','!=',''
									);
								}
								$where[]=array(
										'5f36806e0f0e751744007a44.dropdown','=','Not Available'
									);
							}
							
							// step#3
							if(!empty($_GET['5f36806e0f0e751744007a46'])){
								if($_GET['5f36806e0f0e751744007a46']=='Blank'){
									$where[]=array(
									'5f36806e0f0e751744007a46.actual','=',''
									);
									
								}
								else if($_GET['5f36806e0f0e751744007a46']=='Filled'){
									$where[]=array(
									'5f36806e0f0e751744007a46.actual','!=',''
									);
								}
								
								$where[]=array(
										'5f36806e0f0e751744007a45.actual','!=',''
									);
									
							}
							
							// step#4
							if(!empty($_GET['5f36806e0f0e751744007a47'])){
								if($_GET['5f36806e0f0e751744007a47']=='Blank'){
									$where[]=array(
														'5f36806e0f0e751744007a47.actual','=',''
													);
									
								}
								else if($_GET['5f36806e0f0e751744007a47']=='Filled'){
									$where[]=array(
														'5f36806e0f0e751744007a47.actual','!=',''
													);
								}
								$where[]=array(
												'5f36806e0f0e751744007a46.dropdown','=','Received'
											);
							}
							//step#5
							if(!empty($_GET['5f36806e0f0e751744007a48'])){
								if($_GET['5f36806e0f0e751744007a48']=='Blank'){
									$where[]=array(
										'5f36806e0f0e751744007a48.awb_no','=',''
									);
									
								}else if($_GET['5f36806e0f0e751744007a48']=='Filled'){
									$where[]=array(
										'5f36806e0f0e751744007a48.awb_no','!=',''
									);
								}
								
							}
							//step#6
							if(!empty($_GET['5f36806e0f0e751744007a49'])){
								if(in_array('Blank', $_GET['5f36806e0f0e751744007a49'])){
									array_push($_GET['5f36806e0f0e751744007a49'], "");
								}
								$whereIn['5f36806e0f0e751744007a49.dropdownlist']=$_GET['5f36806e0f0e751744007a49'];
							}
							
							
							/* ****** Start Task DATA ***** */
							
							
							// Pi Date 
							$pi_from ='';
							$pi_to ='';							
							if(!empty($_GET['pi_from']) || !empty($_GET['pi_to'])){
								
								if(!empty($_GET['pi_from'])){
									$pi_from =date('Y-m-d', strtotime($_GET['pi_from']));
									
									$where[]=array(
										'order_date','>=',$pi_from
									);
								}
								
								if(!empty($_GET['pi_to'])){
									$pi_to =date('Y-m-d', strtotime($_GET['pi_to']."+ 1 day"));
									
									$where[]=array(
										'order_date','<',$pi_to
									);
								}
								
							}
							
							// Buyer Name							
							if(!empty($_GET['customer_data'])){
								$where[]=array(
										'customer_data.c_value','=',trim($_GET['customer_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Sales Person							
							/* if(!empty($_GET['s_person_data'])){
								$where[]=array(
										's_person_data.s_value','=',trim($_GET['s_person_data'])
									);
							} */
							
							if(!empty($_GET['s_person_data'])){
								/* $where[]=array(
										's_person_data.s_value','=',trim($_GET['s_person_data'])
									); */
								$whereIn['s_person_data.s_value']=$_GET['s_person_data'];
							}
							
							// crm_data					
							if(!empty($_GET['crm_data'])){
								$where[]=array(
										'crm_data.c_value','=',trim($_GET['crm_data'])
									);
							}
							
							// merchant_data							
							if(!empty($_GET['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($_GET['merchant_data'])
									);
							}
							
							/* ****** End Task DATA ***** */
							
							$fmsdatas_pg = DB::table($fms_table);
							$fmsdatas_pg =	$fmsdatas_pg->where($where);
							
							foreach($whereIn as $column=>$value){
								$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
							}
							
							$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

							
							$all_data 	= DB::table($fms_table);
							$all_data 	=	$all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							
							$all_data 	=	$all_data->paginate($limit);
					
				}else{
				$fmsdatas_pg 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit)->toarray();

				$all_data 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate($limit);
				}
				$fmsdatas = $fmsdatas_pg['data'];
			
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.samplecard_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.samplecard_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
			
			
			
		}else if($fms['fms_type']=='stock_order'){
				
			/* ///////////========== Update PI and PI row data into FMS data collection start =========///////////// */			
			$up = 0; // set 1 if want to update
			if($up==1){
				$fms_table_data = DB::table($fms_table)->select('id','pi_id')->get()->toArray();
				//$fms_table_data = DB::table($fms_table)->select('id','pi_id')->where('pi_id', '5f479aabd0490422d115e732')->get()->toArray();

				$pi_index=0;							
				$oldPi='';
				$cn=1;
			
			foreach($fms_table_data as $fkey=>$fdata){
				$oid = (array) $fdata['_id'];
				$data_id = $oid['oid'];
				
						$pi_id=$fdata['pi_id'];
						if($oldPi!=$pi_id){
							$pi_index=0;
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];
						}else{
							$piMain = getPiDataByPiId($pi_id);
							$piRows = getPiRowDataByPiId($pi_id)[$pi_index];							
						}						
						$oldPi = $pi_id;
						//pr($pi_data); die;
						$pi_index++;
						
						//pr($piMain);
						//pr($piRows);
						
						// update data start
						/// Pi data 
						$order_type = $piMain['order_type'];
						$fms_data =array();
						
						
						$buyer_data = array(
													'b_id'=>$piMain['buyer_name'],
													'b_value'=>trim(GetBuyerName($piMain['buyer_name']))
											);
											
						$customer_data = array(
													'c_id'=>$piMain['customer_name'],
													'c_value'=>trim(GetBuyerName($piMain['customer_name']))
											);
							
						$s_person_data = array(
												's_id'=>$piMain['sales_agent'],
												's_value'=>trim(getAgentMerchant($piMain['sales_agent']))
											);
											
						$brand_data = array(
												'b_id'=>$piMain['brand_name'],
												'b_value'=>trim(GetBrandName($piMain['brand_name']))
											);
						
						//// CRM assign 
						$crm_data='';
						if(array_key_exists('crm_assign', $piMain) && $piMain['crm_assign']!=''){
							$crm_data=array(
												'c_id'=>$piMain['crm_assign'],
												'c_value'=>trim(getAgentMerchant($piMain['crm_assign']))
											);
						}
						//// Merchant assign
						$merchant_data ='';
						if(array_key_exists('merchant_name', $piMain) && $piMain['merchant_name']!=''){
							$merchant_data=array(
												'm_id'=>$piMain['merchant_name'],
												'm_value'=>trim(getAgentMerchant($piMain['merchant_name']))
											);
						}
						///Pi data
						
						// row data 
						$fms_data['etd']=$piRows['etd'];
						$roid = (array) $piRows['_id'];
						$row_id = $roid['oid'];
						
						/* $fms_data['item_row']=$row_id;
						$fms_data['invoice_no']= $piMain['po_serial_number'];
						$fms_data['pi_date']= $piMain['proforma_invoice_date']; */
						
						$fms_data['buyer_data'] = $buyer_data;
						$fms_data['customer_data'] = $customer_data;
						$fms_data['s_person_data'] = $s_person_data;
						$fms_data['brand_data'] = $brand_data;
						
						//$fms_data['pi_status'] = $piMain['status'];
						
						$fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>trim(getItemName($piRows['item']))
											);
											
						/* $fms_data['color'] = $piRows['colour'];
						$fms_data['bulkqty'] = $piRows['bulkqty'];
						$fms_data['ssqty'] = $piRows['ssqty'];
						$fms_data['unit'] = $piRows['unit'];
						$fms_data['unit_price'] = $piRows['unit_price'];
						$fms_data['lab_dip'] = $piRows['lab_dip']; */
						
						$fms_data['crm_data'] = $crm_data;
						$fms_data['merchant_data'] = $merchant_data;
						
						/* $fms_data['fob_sample'] = $piMain['fob_sample_approval'];
						$fms_data['fpt_testing'] = $piMain['fpt_testing_approval'];
						$fms_data['payment_term'] = $piMain['payment_term'];
						$fms_data['mode_of_transport'] = $piMain['mode_of_transport']; */
						
						//pr($fms_data); die;
						DB::table($fms_table)->where('_id', $data_id)->where('pi_id', $pi_id)->update($fms_data);
						// update data END
				
				echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
				$cn++;
			}
			
			//pr($fms_table_data); die;
			die('DONE ALL');
		}			
			/* ///////////========== Update PI and PI row data into FMS data collection END =========/////////////// */
				
				$limit=50;
				if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
					$limit=500;
				}
			
				if(isset($_GET) && !empty($_GET)){
						$orWhere = array();
						$whereIn = array();
						$where = array(
								array(
										'fms_id', '=', $fms_id
									)
							);
							
							// step#1
							if(!empty($_GET['5eb25b870f0e750c5c0020d2'])){
								if($_GET['5eb25b870f0e750c5c0020d2']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d2.actual','=',''
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d2']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d2.actual','!=',''
									);
								}
								
							}
							
							// step#2
							if(!empty($_GET['5eb25b870f0e750c5c0020d3'])){
								if($_GET['5eb25b870f0e750c5c0020d3']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d3.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d2.dropdown','=','No'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d3']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d3.actual','!=',''
									);
								}
							}
							
							// step#3
							if(!empty($_GET['5eb25b870f0e750c5c0020d4'])){
								if($_GET['5eb25b870f0e750c5c0020d4']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d4.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d3.dropdown','=','No'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d4']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d4.actual','!=',''
									);
								}
							}
							
							// step#4
							if(!empty($_GET['5eb25b870f0e750c5c0020d5'])){
								if($_GET['5eb25b870f0e750c5c0020d5']=='Blank'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d5.actual','=',''
									);
									
									$where[]=array(
										'5eb25b870f0e750c5c0020d3.dropdown','=','Yes'
									);
								}
								else if($_GET['5eb25b870f0e750c5c0020d5']=='Filled'){
									$where[]=array(
									'5eb25b870f0e750c5c0020d5.actual','!=',''
									);
								}
							}
							
							//step#5
							//step#6
							
							if(!empty($_GET['5eb25b870f0e750c5c0020d7'])){								
								if(in_array('Blank', $_GET['5eb25b870f0e750c5c0020d7'])){
									array_push($_GET['5eb25b870f0e750c5c0020d7'], "");
								}								
								$where[]=array(
											'5eb25b870f0e750c5c0020d2.dropdown','=','No'
									);
									
								$whereIn['5eb25b870f0e750c5c0020d7.dropdownlist']=$_GET['5eb25b870f0e750c5c0020d7'];
							}
							
							//step#7
							if(!empty($_GET['5eb2ac550f0e750c5c0020e8'])){
								if($_GET['5eb2ac550f0e750c5c0020e8']=='Blank'){
									$where[]=array(
									'5eb2ac550f0e750c5c0020e8.actual','=',''
									);
									
								$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','!=','Cancelled'
									);
									
								}
								else if($_GET['5eb2ac550f0e750c5c0020e8']=='Filled'){
									$where[]=array(
									'5eb2ac550f0e750c5c0020e8.actual','!=',''
									);
									
									$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','!=','Cancelled'
									);
								}
							}
							
							
							
							//step#8							
							if(!empty($_GET['5eb2ac560f0e750c5c0020e9'])){								
								if(in_array('Blank', $_GET['5eb2ac560f0e750c5c0020e9'])){
									array_push($_GET['5eb2ac560f0e750c5c0020e9'], "");
								}								
									
								$whereIn['5eb2ac560f0e750c5c0020e9.dropdownlist']=$_GET['5eb2ac560f0e750c5c0020e9'];
							}
							
							//step#9
							if(!empty($_GET['5eb2ac560f0e750c5c0020ea'])){
								if($_GET['5eb2ac560f0e750c5c0020ea']=='Blank'){
									$where[]=array(
										'5eb2ac560f0e750c5c0020ea.inv_date','=',''
									);
									
								}else if($_GET['5eb2ac560f0e750c5c0020ea']=='Filled'){
									$where[]=array(
										'5eb2ac560f0e750c5c0020ea.inv_date','!=',''
									);
								}
								
							}
							//step#10
							/* if(!empty($_GET['5eb2ac560f0e750c5c0020eb'])){
								$where[]=array(
									'5eb2ac560f0e750c5c0020eb.dropdownlist','=',$_GET['5eb2ac560f0e750c5c0020eb']
									);
							} */
							
							if(!empty($_GET['5eb2ac560f0e750c5c0020eb'])){								
								if(in_array('Blank', $_GET['5eb2ac560f0e750c5c0020eb'])){
									array_push($_GET['5eb2ac560f0e750c5c0020eb'], "");
								}
								$whereIn['5eb2ac560f0e750c5c0020eb.dropdownlist']=$_GET['5eb2ac560f0e750c5c0020eb'];
							}
							
							/* ****** Start Task DATA ***** */
							// Pi No.
							if(!empty($_GET['invoice_no'])){
								$where[]=array(
										'invoice_no','=',$_GET['invoice_no']
									);
							}
							
							// Pi Date 
							$pi_from ='';
							$pi_to ='';							
							if(!empty($_GET['pi_from']) || !empty($_GET['pi_to'])){
								
								if(!empty($_GET['pi_from'])){
									$pi_from =date('Y-m-d', strtotime($_GET['pi_from']));
									
									$where[]=array(
										'pi_date','>=',$pi_from
									);
								}
								
								if(!empty($_GET['pi_to'])){
									$pi_to =date('Y-m-d', strtotime($_GET['pi_to']."+ 1 day"));
									
									$where[]=array(
										'pi_date','<',$pi_to
									);
								}
								
							}
							
							// Buyer Name							
							if(!empty($_GET['customer_data'])){
								$where[]=array(
										'customer_data.c_value','=',trim($_GET['customer_data'])
									);
							}
							
							// Article No							
							if(!empty($_GET['item_data'])){
								/* $where[]=array(
										'item_data.i_value','=',$_GET['item_data']
									); */
								$whereIn['item_data.i_value']=$_GET['item_data'];
							}
							
							// Colour							
							if(!empty($_GET['color'])){
								$where[]=array(
										'color','=',$_GET['color']
									);
							}
							
							// Sales Person							
							if(!empty($_GET['s_person_data'])){
								$where[]=array(
										's_person_data.s_value','=',trim($_GET['s_person_data'])
									);
							}
							
							// crm_data					
							if(!empty($_GET['crm_data'])){
								$where[]=array(
										'crm_data.c_value','=',trim($_GET['crm_data'])
									);
							}
							
							// merchant_data							
							if(!empty($_GET['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($_GET['merchant_data'])
									);
							}
							
							//PI Status
							if(!empty($_GET['pi_status'])){
								$where[]=array(
										'pi_status','=',$_GET['pi_status']
									);
							}
							/* ****** End Task DATA ***** */
							
							$fmsdatas_pg = DB::table($fms_table);
							$fmsdatas_pg =	$fmsdatas_pg->where($where);
							
							foreach($whereIn as $column=>$value){
								$fmsdatas_pg =	$fmsdatas_pg->whereIn($column,$value);
							}
							
							$fmsdatas_pg =	$fmsdatas_pg->paginate($limit)->toarray();	

							
							$all_data 	= DB::table($fms_table);
							$all_data 	=	$all_data->where($where);
							
							foreach($whereIn as $column=>$value){
								$all_data =	$all_data->whereIn($column,$value);
							}
							
							$all_data 	=	$all_data->paginate($limit);
					
				}else{
				$fmsdatas_pg 	= DB::table($fms_table)
								->where('fms_id',$fms_id)
								->paginate($limit)->toarray();

				$all_data 	= DB::table($fms_table)
							->where('fms_id',$fms_id)
							->paginate($limit);
				}
				$fmsdatas = $fmsdatas_pg['data'];
			
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.pi_sampling_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.pi_sampling_fmsdata',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
			
			
		}
    }

	
	public function fms_dashboard($id,$from='')
    {
		$fms_id = $id;
		
		$taskdatasAll = 	Fmstask::where('fms_id', '=', $id)
								->orderBy('task_step', 'asc')->get()->toarray();
		
		$taskdatas = 	Fmstask::where('fms_id', '=', $id)
								->where('dashboard', '!=', '0')
								->orderBy('task_step', 'asc')->get()->toarray();
		
		
		$orderStepId = '';
		$orderDateTaskId ='';
		foreach($taskdatasAll as $tk=>$tv){
			if($tv['field_type']=='orders'){
				$orderStepId=$tv['_id'];
			}else if($tv['input_type']=='custom_fields' && $tv['field_type']=='date'){
				$orderDateTaskId=$tv['_id'];
			}
		}
		//echo $orderDateTaskId; die;
		
		if(empty($taskdatas)){ return redirect()->route('fmslist')->with('success', 'No data found.'); }
		
		$fms = Fms::where('_id', '=', $id)->get()->toarray();
		$fms = $fms[0];
		
		$stepsdatas = 	Fmsstep::where('fms_id', '=', $id)
						->where('fms_id', '=', $id)
						->where('dashboard', '!=', '0')
						->orderBy('step', 'asc')
						->get()->toarray();
		
		//pr($stepsdatas); die;
		
		
		$fmsdatas=array();
		if($fms['fms_type']=='sample_order'){
					if($from==''){
						
						$date_st = date('Y-m-01');
						$date_en = date('Y-m-d', strtotime($date_st.' +1 month'));
						
						$date_start = new \DateTime($date_st);
						$date_end = new \DateTime($date_en);
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
										->whereBetween('created_at', array($date_start, $date_end))
										->orderBy($orderStepId, 'desc')
										->paginate(100)->toarray();
												
						$fmsdatas = $fmsdatas_pg['data'];
						//pr($fmsdatas_pg); die;
					}else{
						
						$date_st = $from;
						$date_en = date('Y-m-d', strtotime($date_st.' +1 month'));
						
						$date_start = new \DateTime($date_st);
						$date_end 	= new \DateTime($date_en);
						 
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
												->whereBetween('created_at', array($date_start, $date_end))
												->orderBy($orderStepId, 'desc')
												->paginate(100)->toarray();
												
						$fmsdatas = $fmsdatas_pg['data'];
						//pr($fmsdatas_pg); die;
					}
					
					
			
		}elseif($fms['fms_type']=='custom_order'){
					
					if($from==''){
						
						$date_start = date('Y-m-01');
						$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
												->whereBetween($orderDateTaskId, array($date_start, $date_end))
												->orderBy($orderStepId, 'desc')
												->paginate(100)->toarray();
												
						$fmsdatas = $fmsdatas_pg['data'];	
					}else{
						
						$date_start = $from;
						$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));
						
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
												->whereBetween($orderDateTaskId, array($date_start, $date_end))
												->orderBy($orderStepId, 'desc')
												->paginate(100)->toarray();
						$fmsdatas = $fmsdatas_pg['data'];
						
					}
					/* 22 Aug 2019 */
			
			
			
		}elseif($fms['fms_type']=='gr_order'){
					
					if($from==''){
						
						$date_start = date('Y-m-01');
						$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));						
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
												->whereBetween($orderDateTaskId, array($date_start, $date_end))
												//->orderBy($orderStepId, 'desc')
												->paginate(100)->toarray();
												
						$fmsdatas = $fmsdatas_pg['data'];	
					}else{
						
						$date_start = $from;
						$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));
						
						$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
												->whereBetween($orderDateTaskId, array($date_start, $date_end))
												->orderBy($orderStepId, 'desc')
												->paginate(100)->toarray();
						$fmsdatas = $fmsdatas_pg['data'];
					}
					/* 22 Aug 2019 */
			
			
			
		}else{
			if($from=='' || $from==0){
				
				$date_start = date('Y-m-01');
				$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));				
				
				$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
										->whereBetween($orderDateTaskId, array($date_start, $date_end))
										->orderBy($orderStepId, 'desc')
										->paginate(2000)->toarray();
				$fmsdatas = $fmsdatas_pg['data'];
										
			}else{
				
				$date_start = $from;
				$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));				
				
				$fmsdatas_pg = Fmsdata::where('fms_id', '=', $id)
									->whereBetween($orderDateTaskId, array($date_start, $date_end))
									->orderBy($orderStepId, 'desc')
									->paginate(2000)->toarray();
			
				$fmsdatas = $fmsdatas_pg['data'];
					
			}
		}
		
			
		$fabricatorHtml='';
		
		//pr($fmsdatas_pg); die;
		if($fms['fms_type']=='sample_order'){
			
			return view('fms.fmsdata_sample', compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('fabricatorHtml', $fabricatorHtml)->with('order_range', $from);
			
		}elseif($fms['fms_type']=='custom_order'){
			return view('fms.fmsdata_custom', compact('taskdatas', 'fms','stepsdatas', 'fmsdatas','fmsdatas_pg'))->with('no', 1)->with('fabricatorHtml', $fabricatorHtml)->with('order_range', $from);
		}elseif($fms['fms_type']=='gr_order'){
			return view('fms.fmsdata_gr_order', compact('taskdatas', 'fms','stepsdatas', 'fmsdatas','fmsdatas_pg'))->with('no', 1)->with('fabricatorHtml', $fabricatorHtml)->with('order_range', $from);
		}else{
			return view('fms.fmsdata_dashboard', compact('taskdatas', 'fms','stepsdatas', 'fmsdatas'))->with('no', 1)->with('fabricatorHtml', $fabricatorHtml)->with('order_range', $from);
		}
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		//dd($request);
		//dd($request->task);
		$data = array();
		$data['fms_id']=$request->fms_id;
		foreach($request->task as $tkey=>$tval)
		{
			$data[$tkey]=$tval;
		}
		
		foreach($request->step as $skey=>$sval)
		{
			$data[$skey]=['planed'=>$sval, 'actual'=>""];
		}
		
		$inserted = $this->fmsdata::create($data);
		if($inserted)
		{
			return redirect()->route('fmslist')->with('success', 'Fmsdata stored successfully.');
		}else{
			return redirect()->route('fmslist')->with('success', 'Fmsdata stored failed.');
		}
        //echo "<pre>"; print_r($data); echo "</pre>==="; die;
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
	
	function insertfmsdata($id)
	{
		//echo $id; die;
		$taskdatas = Fmstask::where('fms_id', '=', $id)->get();
		$fmsdatas = Fms::where('_id', '=', $id)->get()->toarray();
		$fmsdatas=$fmsdatas[0];
		//echo "<pre>"; print_r($fmsdatas); print_r($fmsdatas[0]['fms_name']); die;
		$stepsdatas = Fmsstep::where('fms_id', '=', $id)->get();
        return view('fms.insertfmsdata', compact('taskdatas', 'fmsdatas','stepsdatas'));
	}
	
	public function storefmsdata()
	{
		//$result = $member->save($request->all());
		//dd(DB::getQueryLog());
		/* echo "<pre>"; print_r($_POST); echo "</pre>===";
		$data = array(); 
		$data = array_merge($_POST['task'],$_POST['step']);
		echo "<pre>"; print_r($data); die; */
	}
	
	public function ajax_savefmsdata(Request $request)
	{
		$r_num = $request->row_num;
		$data = array();
		$data['fms_id']=$request->row[$r_num]['fms_id'];
		
		foreach($request->row[$r_num]['task'] as $tkey=>$tval)
		{
			$data[$tkey]=$tval;
		}
		
		foreach($request->row[$r_num]['step'] as $skey=>$sval)
		{
			$data[$skey]=$sval;
		}
		
		 /* echo "<pre>";
		print_r($data); die; */ 
		$inserted = $this->fmsdata::create($data);
		if($inserted)
		{
			echo "success id===".$inserted;
			die;
		}else{
			echo "failed";
			die;
		}
	}
	
	public function getHrbyId($mainArrs, $whenArr)
	{
		
		foreach($mainArrs as $mainArr)
		{
			$t1=0; $t2=0;
			if($mainArr['_id']==$whenArr['after_step_id'])
			{
				if(!empty($mainArr['after_task_time']))
				{
					$t1=  $mainArr['after_task_time']; 
					$t2 = $whenArr['time_hr'];
					
					return ($t1+$t2);
					exit;
				}else{
					$t1=  $mainArr['fms_when']['time_hr']; 
					$t2 = $whenArr['time_hr'];
					
					return $t1+$t2;
					exit;
				}
				
			}
		}
	}
		
	public function ajax_url_get_date()
	{
		/* echo "<pre>";
		print_r($_GET); die;  */  
		
		if(!empty($_GET) && $_GET['fms_id']!='')
		{		
			$dateVal 	= $_GET['thisVal']; // date 
			$rowNum 	= $_GET['rowNum']; // row_num
			
			//var_dump($dateVal);			
			
			if (strtotime($dateVal)>strtotime(0) && !empty($_GET['task_hr']))
			{
				/* echo 'it is a date';
				var_dump($dateVal); */
				
				$task_hr 	= $_GET['task_hr'];	
				$task_hr = ceil($task_hr/8);
				$date=date_create($dateVal);
				date_add($date,date_interval_create_from_date_string($task_hr." days"));
				echo $link_step_date = date_format($date,"d-m-Y H:i");
				
			}else{
				echo "no-date";
			}
			exit;			
		}else{
				echo "no-date";
		}
		exit;
		
	}
	
	
	/* Update Pattern/Standared */
	function ajax_updatePattern(){
		$dataId = $_GET['dataId'];
		$taskId = $_GET['taskId'];
		$currVal = $_GET['currVal'];
		
		$fms_id= $_GET['fms_id'];
		$dataArr = getFmsdataDetailByDataId($dataId);
		/* pr($_GET);
		die; */ 
			$updateArr = array($taskId => $currVal);
			
			$upId = DB::table('fmsdatas')
					->where('_id', $dataId)
					->update($updateArr);
			if($upId)
			{
				$updateResArr = array(
										'msg'=>'success',
									);
					
				echo json_encode($updateResArr);
			}else{
				$updateResArr = array(
										'msg'=>'failed',
									);
				echo json_encode($updateResArr);
			}
		exit;
	}
	/* Update Pattern/Standared END*/
	
	/* Update Client */
	function ajax_updateClientOrder(){
		
		$dataId = $_GET['dataId'];
		$taskId = $_GET['taskId'];
		$currVal = $_GET['currVal'];
		
		$fms_id= $_GET['fms_id'];
		$dataArr = getFmsdataDetailByDataId($dataId);
		/* pr($_GET);
		die; */ 
			$updateArr = array($taskId => $currVal);
			
			$upId = DB::table('fmsdatas')
					->where('_id', $dataId)
					->update($updateArr);
			if($upId)
			{
				$updateResArr = array(
										'msg'=>'success',
									);
					
				echo json_encode($updateResArr);
			}else{
				$updateResArr = array(
										'msg'=>'failed',
									);
				echo json_encode($updateResArr);
			}
		exit;
	}
	
	/* Update Client END*/
	
	/* Update fabricator start*/
	public function ajax_updateFabricator(Request $request)
	{
		$data_id = $request->data_id;
		$data_o_id = $request->data_o_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		
		$fabricator = $request->fab_name;
		$fab_id = $request->fab_id;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($fabricator); */
		
		$dateArr = array(
							'fb_id'=>$fab_id,
							'fabricator'=>$fabricator,
							$step_id=>array(
											'fb_id'=>$fab_id,
											'fabricator'=>$fabricator
											)
						);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			if($data_o_id!=''){
				updateAntyaPmProductionStatusPacked_api($data_o_id);
			}

			$resArr = array(
								'msg'=>'Fabricator updated',
								'step_id'=>$step_id,
								'fabricator'=>$fabricator,
							);
							
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	/* Update fabricator END*/
	
	/* Update tailor */
	public function ajax_updateTailor(Request $request)
	{
		$data_id = $request->data_id;
		$data_o_id = $request->data_o_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		
		$fabricator = $request->fab_name;
		$fab_id = $request->fab_id;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($fabricator); die('--999--'); */
		
		$dateArr = array(
							$step_id=>array(
											'tailor_id'=>$fab_id,
											'tailor'=>$fabricator
											)
						);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'Tailor updated',
								'step_id'=>$step_id,
								'tailor'=>$fabricator,
								'data_o_id'=>$data_o_id,
							);
							
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	/* Update tailor END*/
	
	/* Update ajax_updateFabricator_new */
	public function ajax_updateTailor2(Request $request)
	{
		$data_id = $request->data_id;
		$data_o_id = $request->data_o_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		
		$fabricator = $request->fab_name;
		$fab_id = $request->fab_id;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($fabricator); die('--999--'); */
		
		$dateArr = array(
							$step_id=>array(
											'fb_id'=>$fab_id,
											'fabricator'=>$fabricator
											)
						);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'Fabricator updated',
								'step_id'=>$step_id,
								'fabricator'=>$fabricator,
							);
							
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	/* Update ajax_updateFabricator_new END*/
	
	
	
	function ajax_updateOrderEmbroidery(){
		
		$dataId = $_GET['dataId'];
		$stepId = $_GET['stepId'];
		$currVal = $_GET['currVal'];
		
		$fms_id= $_GET['fms_id'];
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);			
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid'];
		
		$stepData = getStepDataByStepId($stepId);
		$stageTextArr = $stepData['fms_stage'];
		
		$nextStepDetails = getNextStepDetailsByCurrentStepId($stepId, $fms_id);
		
		$dataArr = getFmsdataDetailByDataId($dataId);
		/* pr($dataArr); 
		pr($_GET); 
		pr($nextStepDetails);
		die; */
		if(!empty($nextStepDetails)){
			/* $nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			$nextStepActualVal = $dataArr[$nextStep_id]['actual']; */
			
			$nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			///
			if(array_key_exists('actual', $dataArr[$nextStep_id])){
			$nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			$nextStepActualVal = $dataArr[$nextStep_id]['actual'];
			}else{
				$nextStepActualVal='';
			}
			///
			
		}else{
			$nextStepActualVal='';
		}
		
		
		if($stepData['fms_when']['fms_when_type']==7){
			
			//$dataArr[$stepId]['order_embroidery_id'] = $currVal;
			$embroidery_arr = $dataArr[$stepId];
			$embroidery_arr['dropdown_id'] = $currVal;
			
			$fms_whenArr = $stepData['fms_when'];
			
			if($nextStepActualVal==''){
				$updateArr = array(
								$stepId => $embroidery_arr,
								$stage_task_id =>$stageTextArr
								);
				$updateResArr = array(
								'stage_text_color'=>$stageTextArr['stage_text_color'],
								'stage_text'=>$stageTextArr['stage_text'],
								'msg'=>'success',
								);
			}else{
				$updateArr = array(
								$stepId => $embroidery_arr
								);
				$updateResArr = array(
								'stage_text_color'=>'',
								'stage_text'=>'',
								'msg'=>'success',
								);
			}
			
			
			$upId = DB::table('fmsdatas')
					->where('_id', $dataId)
					->update($updateArr); 
			$resArr = array();
			if($upId)
			{
				//echo "success";
				$resArr = $updateResArr;
				echo json_encode($resArr);
			}else{
				$resArr = $updateResArr;
				$resArr['msg']='failed';
				echo json_encode($resArr);
			}
		}else{
			$resArr = $updateResArr;
			$resArr['msg']='failed';
			echo json_encode($resArr);
		}		
		exit;
		
		
	}
	
	function ajax_updateFabricCmnt(){

		/* ************** */
		$dataId = $_GET['dataId'];
		$stepId = $_GET['stepId'];
		$currVal = $_GET['currVal'];
		
		$fms_id= $_GET['fms_id'];
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);			
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid'];
		
		$stepData = getStepDataByStepId($stepId);
		$stageTextArr = $stepData['fms_stage'];
		
		$nextStepDetails = getNextStepDetailsByCurrentStepId($stepId, $fms_id);
		
		$dataArr = getFmsdataDetailByDataId($dataId);
		/* pr($dataArr); 
		pr($_GET); 
		pr($nextStepDetails);
		die; */
		if(!empty($nextStepDetails)){
			/* $nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			$nextStepActualVal = $dataArr[$nextStep_id]['actual']; */
			
			$nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			///
			if(array_key_exists('actual', $dataArr[$nextStep_id])){
			$nextStep_oid = (array) $nextStepDetails['_id'];
			$nextStep_id = $nextStep_oid['oid'];
			$nextStepActualVal = $dataArr[$nextStep_id]['actual'];
			}else{
				$nextStepActualVal='';
			}
			///
			
		}else{
			$nextStepActualVal='';
		}
		/* ************** */
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		if($stepData['fms_when']['fms_when_type']==6){
			$stepData['fms_when']['fabric_comments'] = $currVal; 
			$fms_whenArr = $stepData['fms_when'];
			
			if($nextStepActualVal==''){
				$updateArr = array(
								$stepId => $fms_whenArr,
								$stage_task_id =>$stageTextArr
								);
				$updateResArr = array(
								'stage_text_color'=>$stageTextArr['stage_text_color'],
								'stage_text'=>$stageTextArr['stage_text'],
								'msg'=>'success',
								);
			}else{
				$updateArr = array(
								$stepId => $fms_whenArr
								);
				$updateResArr = array(
								'stage_text_color'=>'',
								'stage_text'=>'',
								'msg'=>'success',
								);
			}
			
			
			$upId = DB::table('fmsdatas')
					->where('_id', $dataId)
					->update($updateArr); 
			$resArr = array();
			if($upId)
			{
				//echo "success";
				$resArr = $updateResArr;
				echo json_encode($resArr);
			}else{
				$resArr = $updateResArr;
				$resArr['msg']='failed';
				echo json_encode($resArr);
			}
		}else{
			$resArr = $updateResArr;
			$resArr['msg']='failed';
			echo json_encode($resArr);
		}		
		exit;
	}
	
	
	public function ajax_updateActualDateSample()
	{
		
		$fms_id= $_GET['fms_id'];
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);			
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['data_id'];
		$step_id = $_GET['step_id'];
		$row_num_step = $_GET['row_num_step'];
		$planed_date = $_GET['planed_date'];
		
		
		
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		if(isset($_GET['pm_check'])){
			$pm_check =  $_GET['pm_check'];
		}else{
			$pm_check =  '';
		}
		
		if(isset($_GET['process'])){
			$process =  $_GET['process'];
		}else{
			$process =  '';
		}
		
		$store_room_id=0;
		if(isset($_GET['store_room_id'])){
			
			if($_GET['store_room_id']=='sample'){
				$store_room_id =0;
			}else if($_GET['store_room_id']=='main'){
				$fms_detail = getFmsDetailsById($fms_id);
				$store_room_id = $fms_detail['store_room_id'];
			}else{
				$store_room_id =  $_GET['store_room_id'];
			}
			
		}else{
			$fms_detail = getFmsDetailsById($fms_id);
			$store_room_id = $fms_detail['store_room_id'];
		}
		
		//pr($_GET); echo $store_room_id.'=='; die;
		
		if($planed_date!='')
		{
			$actual_date1 = date('d-m-Y');
			$actual_date = date('m/d/Y', strtotime($actual_date1));
			
			// check diffrence
			$planed_date_check = date('Y/m/d H:i:s', strtotime($planed_date));
			//$actual_date_check = date('Y/m/d H:i:s', strtotime($actual_date));
			$actual_date_check = date('Y/m/d H:i:s', time());
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#757575',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}
			
			
			$dateArr = array(
								'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
								'actual'=>date('Y-m-d H:i:s'),
							); 
							
		}else if($data['fms_when_type']==5 && $data['fms_when']['input_type']=='none'){
			$dateArr = array(
								'none_date'=>date('Y-m-d H:i:s'),
								'input_type'=>$data['fms_when']['input_type']
							);
			
			$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi(date('Y-m-d H:i:s')),
									'date_main'=>date('Y-m-d H:i:s', time())
								);								
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		
		$upId = DB::table('fmsdatas')
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			if($pm_check!='' && $process!=''){
				updateAntyaPmProductionStatusSample_api($fms_id, $pm_check, $store_room_id,$process);
				//$process
			}
			
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = 'class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			
			$resArr = array(
								'resHtml'=>$resHtml,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'stage_text'=>'<span '.$comp_icon_cls.' style="color:'.$stageTextArr['stage_text_color'].'">'.$stage_text.'</span>'
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;		
	}
	
	public function ajax_updateActualDate()
	{
		//pr($_GET); die;
		$fms_id	= $_GET['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		//pr($stage_task_id_arr); die;
		
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['data_id'];
		$step_id = $_GET['step_id'];
		$row_num_step = $_GET['row_num_step'];
		$planed_date = $_GET['planed_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		$crntUid = Auth::user()->id;
		//pr($data); die('==ddd==');
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		//pr($all_step_data); die('==ddd==');
		
		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		
		$process = "";
		if(isset($_GET['process'])){
			$process =  $_GET['process'];
		}		
		$patch = 0;
		$planned_steps_ids = array();
		if($planed_date!='')
		{
			//check diffrence
			$planed_date_check = date('Y-m-d H:i:s', strtotime($planed_date));
			$actual_date_check = date('Y-m-d H:i:s', time());
			
			//echo "Plane date ==".$planed_date_check.'====--actual dt'.$actual_date_check; die('====');
			
			if(array_key_exists('actual_depend',$data)){
					foreach($all_step_data as $key=>$step){						
						if(array_key_exists('depend_step_on_act',$step)){
							
							$depend_steps_arr = $step['depend_step_on_act'];							
							/* check the loop step depend on current step  */
							if(array_key_exists($step_id,$depend_steps_arr)){
								/* if find the step inside this then update planed time of this step */
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								array_push($planned_steps_ids,$curr_step);
								
								$timeinHr = $depend_steps_arr[$step_id];
								
								if($step['fms_when_type']==17){
									$plDt = date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time()));
									$plDt = checkSunday($plDt);
									$plannedArr = array(
														'inv_date'=>$plDt,
														'inv_no'=>'',
														'inv_amount'=>''
													);
								}else{				
									$plDt = date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time()));
									$plDt = checkSunday($plDt);
									$plannedArr = array(
														'planed'=>$plDt,
														'actual'=>''
													);
								}
													
								DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr
																						]);
						
							}
						}						
					}
			}
			
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}		

						
			$finish_day = '';			
			if(array_key_exists('finish_day', $data)){
					/* echo date('Y-m-d H:i:s', time());  echo '===';
					echo strtotime($planed_date);  */
					/* echo '==='; $actul_pl_diff = time()-strtotime($planed_date); echo $actul_pl_diff; */
					
					$actul_pl_diff = (time() - strtotime($planed_date)) / (60 * 60 * 24);					
					$mis_score='';
					if( $actul_pl_diff >0 ){  /// task delay
						$mis_score1 = ($data['finish_day']/($data['finish_day']-$actul_pl_diff));
						$mis_score = number_format(($mis_score1)*100,2);
					}else{ /// task on time or before
						$mis_score=0;
					}
					
					$dateArr = array(
									'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
									'actual'=>date('Y-m-d H:i:s')
								);
					
					// insert MIS score					
					$fms_mis_tbl = $fms_detail['mis_table'];
					$mis_data = DB::table($fms_mis_tbl)->select()->where('d','=',$id)->where('s','=',$step_id)->get()->toArray();					
					if(!empty($mis_data)){
						DB::table($fms_mis_tbl)
						->where('d','=',$id)
						->where('s','=',$step_id)
						->update(array($crntUid=> (float) $mis_score,'d'=>$id,'s'=>$step_id));
					}else{
						DB::table($fms_mis_tbl)
						->insert(array($crntUid=> (float) $mis_score,'d'=>$id,'s'=>$step_id));
					}					
					//pr($mis_data); die;
					
			}else{
					$dateArr = array(
										'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
										'actual'=>date('Y-m-d H:i:s'),
									);
			}
				//pr($dateArr); die;			
			///////////////////////			
							
							
		}else if($data['fms_when_type']==5 && $data['fms_when']['input_type']=='none'){
			$dateArr = array(
								'none_date'=>date('Y-m-d H:i:s'),
								'input_type'=>$data['fms_when']['input_type']
							);
			
			$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi(date('Y-m-d H:i:s')),
									'date_main'=>date('Y-m-d H:i:s', time())
								);								
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		
		
		/* pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		$dateArr['user_id'] = $crntUid;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = ' class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			$arrange_lr_copy='';
			if($step_id=='5e9946b00f0e75131c0037f6' || $step_id=='5e9946b00f0e75131c0037f7'){
				$arrange_lr_copy = date('d.m.y', strtotime(date('Y-m-d H:i:s')."+1 days"));
			}
			
			$resArr = array(
								'resHtml'=>$resHtml,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'arrange_lr_copy'=>$arrange_lr_copy,
								'status'=>1,
								'stage_text'=>'<span'.$comp_icon_cls.'>'.$stage_text.'</span>'
							);
			//pr($upId);  pr($resArr); die('===99==');
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
		
	}
	
	/* copy of ajax_updateActualDate */
	public function ajax_updateActualDateSampleCard()
	{
		//pr($_GET); die;
		$fms_id	= $_GET['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		//pr($stage_task_id_arr); die;
		
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['data_id'];
		$step_id = $_GET['step_id'];
		$row_num_step = $_GET['row_num_step'];
		$planed_date = $_GET['planed_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		$crntUid = Auth::user()->id;
		//pr($data); die('==ddd==');
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		//pr($all_step_data); die('==ddd==');
		
		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$process = "";
		if(isset($_GET['process'])){
			$process =  $_GET['process'];
		}		
		$patch = 0;
		$planned_steps_ids = array();
		if($planed_date!='')
		{
			//check diffrence
			$planed_date_check = date('Y-m-d H:i:s', strtotime($planed_date));
			$actual_date_check = date('Y-m-d H:i:s', time());
			
			//echo "Plane date ==".$planed_date_check.'====--actual dt'.$actual_date_check; die('====');
			
			if(array_key_exists('actual_depend',$data) && $process=='if_fabric_is_not'){
					$plannedArr = array(
											'planed'=>date('Y-m-d H:i',strtotime('+10 days',time())),
											'actual'=>'',
											'dropdown'=>'',
											'comment_dropdown'=>''
										);
					DB::table($fms_data_tbl)->where('_id', $id)->update(['5f36806e0f0e751744007a46' =>$plannedArr]);
			}
			
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}			
			$dateArr = array(
								'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
								'actual'=>date('Y-m-d H:i:s'),
							); 
							
		}else if($data['fms_when_type']==5 && $data['fms_when']['input_type']=='none'){
			$dateArr = array(
								'none_date'=>date('Y-m-d H:i:s'),
								'input_type'=>$data['fms_when']['input_type']
							);
			
			$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi(date('Y-m-d H:i:s')),
									'date_main'=>date('Y-m-d H:i:s', time())
								);								
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		
		
		/* pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		$dateArr['user_id'] = $crntUid;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = ' class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			$arrange_lr_copy='';
			if($step_id=='5e9946b00f0e75131c0037f6' || $step_id=='5e9946b00f0e75131c0037f7'){
				$arrange_lr_copy = date('d.m.y', strtotime(date('Y-m-d H:i:s')."+1 days"));
			}
			
			$resArr = array(
								'resHtml'=>$resHtml,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'arrange_lr_copy'=>$arrange_lr_copy,
								'status'=>1,
								'stage_text'=>'<span'.$comp_icon_cls.'>'.$stage_text.'</span>'
							);
			//pr($upId);  pr($resArr); die('===99==');
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
		
	}
	
	public function ajax_save_dropdown_data_with_date()
	{
		//pr($_POST); die('----from controller----');
				
		$fms_id	= $_POST['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_POST['data_id'];
		$step_id = $_POST['step_id'];
		$row_num_step = $_POST['row_num_step'];
		$planed_date = $_POST['planed_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		$crntUid = Auth::user()->id;
		
		//pr($data); die('==step-data==');	
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		//pr($step_id); die('==ddd==');	

		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$depend_steps_arr = '';
		$planned_steps_ids = array();
		if($planed_date!='' && $data['fms_when_type']==12)
		{
			//check diffrence
			$planed_date_check = date('Y-m-d H:i:s', strtotime($planed_date));
			$actual_date_check = date('Y-m-d H:i:s', time());
			$dropdown= $_POST['dropdown'];
			$comment_dropdown= trim($_POST['comment_dropdown']);
			
			if($dropdown=='Rejected' || $dropdown=='rejected'){
				
				if(array_key_exists('actual_depend',$data)){
					
					/* loop throw the all steps and check which step planned time depends on its actual time */
					foreach($all_step_data as $key=>$step){
						
						/* on rejected */
						if(array_key_exists('depend_steps',$step)){
							
							$depend_steps_arr = $step['depend_steps'];							
							/* check the loop step depend on current step  */
							if(in_array($step_id,$depend_steps_arr)){
								/* if find the step inside this then update planed time of this step */
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								array_push($planned_steps_ids,$curr_step);
								
								
								if(array_key_exists('process', $step) && $step['process']=='resolve_query'){
									
									$plDt = date('Y-m-d H:i:s',strtotime('+2 days',time()));
									$plDt = checkSunday($plDt);
									$plannedArr = array(
													'planed'=>$plDt,
													'actual'=>'',
													'dropdown'=>'',
													'comment_dropdown'=>'',
													
												);
								}else{
									$plDt = date('Y-m-d H:i:s');
									$plDt = checkSunday($plDt);
									$plannedArr = array(
													'planed'=>$plDt,
													'actual'=>''
												);
								}
								
												
								DB::table($fms_data_tbl)->where('_id', $id)->update([
																						$curr_step =>$plannedArr
																					]);
						
							}
						}
						
					}					
					
				}				
			}else if($dropdown=='Approved' || $dropdown=='approved'){
					//echo "hiii"; die;
					if(array_key_exists('actual_depend',$data)){
						
						foreach($all_step_data as $key=>$step){
							/* on Approved */
							if(array_key_exists('depend_steps_appr',$step)){
								
								$depend_steps_arr = $step['depend_steps_appr'];
								
								/* check the loop step depend on current step  */
								if(in_array($step_id,$depend_steps_arr)){
									/* if find the step inside this then update planed time of this step */
									$oid = (array) $step['_id'];
									$curr_step =  $oid['oid'];
									array_push($planned_steps_ids,$curr_step);
									$plDt = date('Y-m-d H:i',strtotime('+3 days',time()));
									$plDt = checkSunday($plDt);
									$plannedArr = array(
														'planed'=>$plDt,
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>'',
													);
									DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr
																						]);
							
								}
							}
							
						}
					}
			}
			
			//echo "Plane date ==".$planed_date_check.'====--actual dt'.$actual_date_check; //die('==tttt==');
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}

			$finish_day = '';			
			if(array_key_exists('finish_day', $data)){
					/* echo date('Y-m-d H:i:s', time());  echo '===';
					echo strtotime($planed_date);  */
					/* echo '==='; $actul_pl_diff = time()-strtotime($planed_date); echo $actul_pl_diff; */
					
					$actul_pl_diff = (time() - strtotime($planed_date)) / (60 * 60 * 24);
					$mis_score='';
					if( $actul_pl_diff >0 ){  /// task delay
						$mis_score1 = ($data['finish_day']/($data['finish_day']-$actul_pl_diff));
						$mis_score = number_format(($mis_score1)*100,2);
					}else{ /// task on time or before
						$mis_score=0;
					}
					
					$dateArr = array(
									'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
									'actual'=>date('Y-m-d H:i:s'),
									'dropdown'=>$dropdown,
									'comment_dropdown'=>$comment_dropdown
								);
						
					// insert MIS score					
					$fms_mis_tbl = $fms_detail['mis_table'];
					$mis_data = DB::table($fms_mis_tbl)->select()->where('d','=',$id)->where('s','=',$step_id)->get()->toArray();					
					if(!empty($mis_data)){
						DB::table($fms_mis_tbl)
						->where('d','=',$id)
						->where('s','=',$step_id)
						->update(array($crntUid=> (float) $mis_score,'d'=>$id,'s'=>$step_id));
					}else{
						DB::table($fms_mis_tbl)
						->insert(array($crntUid=> (float) $mis_score,'d'=>$id,'s'=>$step_id));
					}					
					//pr($mis_data); die;
													
			}else{
					$dateArr = array(
									'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
									'actual'=>date('Y-m-d H:i:s'),
									'dropdown'=>$dropdown,
									'comment_dropdown'=>$comment_dropdown
								);
			}
			
			//pr($dateArr); die;
			//die('22222222');
							
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		/*  pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		// $crntUid
		$dateArr['user_id']= $crntUid;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = ' class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			$pl_date='';
			$dropdown_list = '';
			if($step_id=='5e8ef2c80f0e752284006618'){
				if($dropdown=='Approved' || $dropdown=='approved'){
					$pl_date = date('Y-m-d H:i',strtotime('+3 days',time()));
					$dropdown_list = 'Approved,Cancelled';
				}elseif($dropdown=='Rejected' || $dropdown=='rejected'){
					$pl_date = date('Y-m-d H:i',strtotime('+2 days',time()));
					$dropdown_list = 'Approved,Hold';
				}
			}
			
			$process= '';
			if($step_id=='5e8ef2c80f0e75228400661b'){
				if($dropdown=='Approved' || $dropdown=='approved'){
					$pl_date = date('Y-m-d H:i',strtotime('+1 days',time()));					
					$stockdata = getFmsdataDetailByTbaleNameAndDataId($fms_data_tbl,$id);
					$stock_status = $stockdata['5e8ef2c80f0e75228400661a']['dropdownlist'];
					if($stock_status=='Not Available'){
						$process= 'pi_approval';
					}else{
						$process= '';
					}
					
				}
			}
			
			$resArr = array(
								'data_id'=>$id,
								'resHtml'=>$resHtml,
								'planned_steps_ids'=>$planned_steps_ids,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'status'=>1,
								'dropdown'=>$_POST['dropdown'],
								'dropdown_list'=>$dropdown_list,
								'date'=>changeDateToDmyHi($actual_date_check),
								'pl_date'=>changeDateToDmyHi($pl_date),
								'pl_date_ymd'=>$pl_date,
								'process'=>$process,
								'stage_text'=>'<span'.$comp_icon_cls.'>'.$stage_text.'</span>'
							);
			//pr($upId);  pr($resArr); die('===99==');
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;		
	}

	public function ajax_save_dropdown_data_with_date_pisample()
	{
		//pr($_POST); die('----from controller----');
				
		$fms_id	= $_POST['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_POST['data_id'];
		$step_id = $_POST['step_id'];
		$row_num_step = $_POST['row_num_step'];
		$planed_date = $_POST['planed_date'];
		$pi_date = $_POST['pi_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		//pr($step_id); die('==ddd==');	

		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		$crntUid = Auth::user()->id;
		$depend_steps_arr = '';
		$planned_steps_ids = array();
		$actual_date_check = date('Y-m-d H:i:s', time());
		if($planed_date!='' && $data['fms_when_type']==12)
		{
			//check diffrence
			$planed_date_check = date('Y-m-d H:i:s', strtotime($planed_date));
			
			$dropdown= $_POST['dropdown'];
			$comment_dropdown= trim($_POST['comment_dropdown']);
			
			if($dropdown=='Yes' || $dropdown=='yes'){
				//die('yesssss');
				if(array_key_exists('actual_depend',$data)){
					
					/* loop throw the all steps and check which step planned time depends on its actual time */
					foreach($all_step_data as $key=>$step){
						
						
						/* on rejected */
						if(array_key_exists('depend_steps',$step)){
							
							$depend_steps_arr = $step['depend_steps'];							
							/* check the loop step depend on current step  */
							if(in_array($step_id,$depend_steps_arr)){
								/* if find the step inside this then update planed time of this step */
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								array_push($planned_steps_ids,$curr_step);
								
								
								if(array_key_exists('process', $step) && $step['process']=='get_approval'){
									$plannedArr = array(
													'planed'=>date('Y-m-d H:i:s',strtotime('+2 days',time())),
													'actual'=>'',
													'dropdown'=>'',
													'comment_dropdown'=>'',
													
												);
								}else{
									$plannedArr = array(
													'planed'=>date('Y-m-d H:i:s'),
													'actual'=>''
												);
								}
								
												
								DB::table($fms_data_tbl)->where('_id', $id)->update([
																						$curr_step =>$plannedArr
																					]);
						
							}
						}
						
					}					
					
				}				
			}else if($dropdown=='No' || $dropdown=='no'){
					//echo "--noooo"; die;
					if(array_key_exists('actual_depend',$data)){
						
						foreach($all_step_data as $key=>$step){
							/* on Approved */
							if(array_key_exists('depend_steps_appr',$step)){
								
								$depend_steps_arr = $step['depend_steps_appr'];
								
								/* check the loop step depend on current step  */
								if(in_array($step_id,$depend_steps_arr)){
									/* if find the step inside this then update planed time of this step */
									$oid = (array) $step['_id'];
									$curr_step =  $oid['oid'];
									array_push($planned_steps_ids,$curr_step);
									
									$plannedArr = array(
														'planed'=>date('Y-m-d H:i',strtotime('+2 days',time())),
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>'',
													);
									DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr
																						]);
							
								}
							}
							
						}
					}
			}else if($dropdown=='Alternate' || $dropdown=='alternate'){
					//echo "--noooo"; die;
					if(array_key_exists('actual_depend',$data)){
						
						foreach($all_step_data as $key=>$step){
							/* on Approved */
							if(array_key_exists('depend_steps_appr',$step)){
								
								$depend_steps_arr = $step['depend_steps_appr'];
								
								/* check the loop step depend on current step  */
								if(in_array($step_id,$depend_steps_arr)){
									/* if find the step inside this then update planed time of this step */
									$oid = (array) $step['_id'];
									$curr_step =  $oid['oid'];
									array_push($planned_steps_ids,$curr_step);
									
									$plannedArr = array(
														'planed'=>date('Y-m-d H:i',strtotime('+2 days',time())),
														'actual'=>'',
														'dropdown'=>'',
														'comment_dropdown'=>'',
													);
									DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr
																						]);
							
								}
							}
							
						}
					}
			}
			
			//echo "Plane date ==".$planed_date_check.'====--actual dt'.$actual_date_check; //die('==tttt==');
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}			
			$dateArr = array(
								'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
								'actual'=>date('Y-m-d H:i:s'),
								'dropdown'=>$dropdown,
								'comment_dropdown'=>$comment_dropdown
							);
			//pr($dateArr); die;
							
		}elseif($planed_date!='' && $data['fms_when_type']==11){
			$dropdown= $_POST['dropdown'];
			$comment_dropdown= trim($_POST['comment_dropdown']);
			
			$dateArr = array(
								'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
								'actual'=>date('Y-m-d H:i:s'),
								'dropdown'=>$dropdown,
								'comment_dropdown'=>$comment_dropdown
							);
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>changeDateToDmyHi(date('Y-m-d H:i:s', time())),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		/* pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		$dateArr['user_id']=$crntUid;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = ' class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			
			
			$process= '';
			$pl_date='';
			$dropdown_list = '';
			if($step_id=='5eb25b870f0e750c5c0020d2'){
				if($dropdown=='No' || $dropdown=='no'){
					$pl_date = date('Y-m-d H:i:s', strtotime($pi_date."+3 day"));
					$process= 'sampling_ready';
				}else{
					$process= '';
				}
			}elseif($step_id=='5eb25b870f0e750c5c0020d3'){
				if($dropdown=='No' || $dropdown=='no'){
					$pl_date = date('Y-m-d H:i:s', strtotime($actual_date_check."+2 day"));
					$process= 'inform_merchant';
				}else{
					$pl_date = date('Y-m-d H:i:s', strtotime($actual_date_check."+1 day"));
					$process= 'inform_merchant_yes';
				}
			}
			
			$resArr = array(
								'data_id'=>$id,
								'resHtml'=>$resHtml,
								'planned_steps_ids'=>$planned_steps_ids,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'status'=>1,
								'dropdown'=>$_POST['dropdown'],
								'dropdown_list'=>$dropdown_list,
								'date'=>changeDateToDmyHi($actual_date_check),
								'pl_date'=>changeDateToDmyHi($pl_date),
								'pl_date_ymd'=>$pl_date,
								'process'=>$process,
								'stage_text'=>'<span'.$comp_icon_cls.'>'.$stage_text.'</span>'
							);
							
			//pr($upId);  pr($resArr); die('===99==');
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
		
	}
	
	public function ajax_save_dropdown_data_with_date_sample_card()
	{
		//pr($_POST); die('----from controller----');
		$fms_id	= $_POST['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_POST['data_id'];
		$step_id = $_POST['step_id'];
		$row_num_step = $_POST['row_num_step'];
		$planed_date = $_POST['planed_date'];
		$pi_date = $_POST['pi_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		//pr($step_id); die('==ddd==');	
		$crntUid = Auth::user()->id;
		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$depend_steps_arr = '';
		$planned_steps_ids = array();
		$actual_date_check = date('Y-m-d H:i:s', time());
		if($planed_date!='' && $data['fms_when_type']==12)
		{
			//check diffrence
			$planed_date_check = date('Y-m-d H:i:s', strtotime($planed_date));
			
			$dropdown= $_POST['dropdown'];
			$comment_dropdown= trim($_POST['comment_dropdown']);
			
			/* Step 1 submit */			
			if($step_id =='5f36806e0f0e751744007a44' && $dropdown=='Not Available'){
					$plannedArr = array(
										'planed'=>date('Y-m-d H:i',strtotime('+3 days',time())),
										'actual'=>''
									);
					DB::table($fms_data_tbl)->where('_id', $id)->update(['5f36806e0f0e751744007a45' =>$plannedArr]);
			}
			
			if($step_id =='5f36806e0f0e751744007a46' && $dropdown=='Received'){
					$plannedArr = array(
										'planed'=>date('Y-m-d H:i',strtotime('+1 days',time())),
										'actual'=>'',
										'dropdown'=>'',
										'comment_dropdown'=>'',
									);									
									
					DB::table($fms_data_tbl)->where('_id', $id)->update(['5f36806e0f0e751744007a47' =>$plannedArr]);
			}
			
			//echo "Plane date ==".$planed_date_check.'====--actual dt'.$actual_date_check; //die('==tttt==');
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}else{
				$resHtml= array(
									'bgcolor'=>BG_RED, //Red: ef7070
									'textcolor'=>'#000',
									'date'=>changeDateToDmyHi($actual_date_check),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
			}			
			$dateArr = array(
								'planed'=>date('Y-m-d H:i:s', strtotime($planed_date)),
								'actual'=>date('Y-m-d H:i:s'),
								'dropdown'=>$dropdown,
								'comment_dropdown'=>$comment_dropdown
							);
			//pr($dateArr); die('kkkk');
							
		}else{
			$dateArr = array(
								'planed'=>'none',
								'actual'=>date('Y-m-d H:i:s'),
							);
			
			$resHtml= array(
									'bgcolor'=>'#fff',
									'date'=>date('d-m-Y'),
									'date_main'=>date('Y-m-d H:i:s', time())
								);
		}
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());
		
		/* pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		$dateArr['user_id']=$crntUid;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			$comp = $stageTextArr['stage_text'];
			if($comp=='Complete'){
				$comp_icon_cls = ' class="comp_icon"';
				 $stage_text= '';
			}else{
				$comp_icon_cls ='';
				$stage_text= $stageTextArr['stage_text'];
			}
			
			
			
			$process= '';
			$pl_date='';
			$dropdown_list = '';
			if($step_id=='5f36806e0f0e751744007a44'){
				if($dropdown=='Not Available'){
					$pl_date = date('Y-m-d H:i',strtotime('+3 days',time()));
					$process= 'send_sample';
				}else{
					$process= '';
				}
			}
			
			$resArr = array(
								'data_id'=>$id,
								'resHtml'=>$resHtml,
								'planned_steps_ids'=>$planned_steps_ids,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'status'=>1,
								'dropdown'=>$_POST['dropdown'],
								'dropdown_list'=>$dropdown_list,
								'date'=>changeDateToDmyHi($actual_date_check),
								'pl_date'=>changeDateToDmyHi($pl_date),
								'pl_date_ymd'=>$pl_date,
								'process'=>$process,
								'stage_text'=>'<span'.$comp_icon_cls.'>'.$stage_text.'</span>'
							);
							
			//pr($upId);  pr($resArr); die('===99==');
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
		
	}
	
	public function ajax_update_dropdown(){
		//pr($_GET); die('===');
		
		$fms_id	= $_GET['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['dataId'];
		$step_id = $_GET['stepId'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());

		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		/*  pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		$crntUid = Auth::user()->id;
		/* update main pi table on ORDER STATUS change */
		$pi_data='';
		if($step_id=='5e9a9ecb0f0e750494006a35'){
			/* get PI id by data id  */
			$pi_data = DB::table('pi_fms_bulk_data')->select('_id', 'pi_id')->where('_id',$id)->get()->first();
			$p_id = $pi_data['pi_id'];
			/* Update PI id by p_id  */
			$upId = DB::table('tbl_purchase_invoice')
							->where('_id', $p_id)
							->update(array('pi_order_status'=>$_GET['currVal']));
			/* then update that PI according to dropdown */
		}
		
		$dateArr = array(
							'dropdownlist'=>$_GET['currVal'],
							'last_update_on'=>date('Y-m-d H:i:s', time()),
							'user_id'=>$crntUid
						);
		
		if(array_key_exists('actual_depend',$data)){
					foreach($all_step_data as $key=>$step){						
						if(array_key_exists('depend_step_on_act',$step)){
							
							$depend_steps_arr = $step['depend_step_on_act'];							
							/* check the loop step depend on current step  */
							if(array_key_exists($step_id,$depend_steps_arr)){
								/* if find the step inside this then update planed time of this step */
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								
								$timeinHr = $depend_steps_arr[$step_id];
								
								if($step['fms_when_type']==17){
									$plannedArr = array(
														'inv_date'=>date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time())),
														'inv_no'=>'',
														'inv_amount'=>''
													);
								}else{
									$plannedArr = array(
														'planed'=>date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time())),
														'actual'=>''
													);
								}
								
								DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr,
																							$step_id => $dateArr
																						]);
						
							}
						}						
					}
		}else{
			$upId = DB::table($fms_data_tbl)->where('_id', $id)
										->update([
													$step_id => $dateArr
												]);
		}
		
		if($upId){
			echo "success";
		}else{
			echo "false";
		}
		die();
	}
	
	public function ajax_update_dropdown_po(){
		//pr($_GET); die('===');
		
		$fms_id	= $_GET['fms_id'];		
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['dataId'];
		$step_id = $_GET['stepId'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		/* get all steps data by fms_id */
		$all_step_data = getAllStepDataByFmsId($fms_id);
		
		$stageTextArr = $data['fms_stage'];
		$stageTextArr['last_update'] = date('Y-m-d H:i:s', time());

		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		/*  pr($stageTextArr); 
		echo '--row-data-id=='.$id.'===step-id=='.$step_id.'=====task id=='.$stage_task_id.'---';
		pr($dateArr);
		die('===='); */
		
		/* update main pi table on ORDER STATUS change */
		$pi_data='';
		if($step_id=='5e9a9ecb0f0e750494006a35'){
			/* get PI id by data id  */
			$pi_data = DB::table('pi_fms_bulk_data')->select('_id', 'pi_id')->where('_id',$id)->get()->first();
			$p_id = $pi_data['pi_id'];
			/* Update PI id by p_id  */
			$upId = DB::table('tbl_purchase_invoice')
							->where('_id', $p_id)
							->update(array('pi_order_status'=>$_GET['currVal']));
			/* then update that PI according to dropdown */
		}
		
		$dateArr = array(
							'dropdownlist'=>$_GET['currVal'],
							'last_update_on'=>date('Y-m-d H:i:s', time())
						);
		
		if(array_key_exists('actual_depend',$data)){
					foreach($all_step_data as $key=>$step){						
						if(array_key_exists('depend_step_on_act',$step)){
							
							$depend_steps_arr = $step['depend_step_on_act'];							
							/* check the loop step depend on current step  */
							if(array_key_exists($step_id,$depend_steps_arr)){
								/* if find the step inside this then update planed time of this step */
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								
								$timeinHr = $depend_steps_arr[$step_id];
								
								if($step['fms_when_type']==17){
									$plannedArr = array(
														'inv_date'=>date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time())),
														'inv_no'=>'',
														'inv_amount'=>''
													);
								}else{
									$plannedArr = array(
														'planed'=>date('Y-m-d H:i',strtotime('+'.$timeinHr.' hours',time())),
														'actual'=>''
													);
								}
								
								DB::table($fms_data_tbl)->where('_id', $id)->update([
																							$curr_step =>$plannedArr,
																							$step_id => $dateArr
																						]);
						
							}
						}						
					}
		}else{
			$upId = DB::table($fms_data_tbl)->where('_id', $id)
										->update([
													$step_id => $dateArr
												]);
		}
		
		if($upId){
			echo "success";
		}else{
			echo "false";
		}
		die();
	}
	
	public function updatecustom($fms_id){
		echo $fms_id; 
		
		$datas = DB::table('fmsdatas')
				->where('fms_id', $fms_id)
				->get()->toarray();
				
		//pr($datas); die;
		
		/* foreach($datas as $key=>$val){
			
			
			$stageArr = $val['5d2d7cf3d860f31cb450aca6'];
			if(!empty($stageArr)){
				if($stageArr['stage_text']=='PM Checked'){
				echo $stageArr['stage_text'].'<br>';
				
				
				$upId = DB::table('fmsdatas')
				->where('_id', $val['_id'])
				->where('fms_id', $fms_id)
				->update([
							"5d2d7cf3d860f31cb450aca6" => array(
																	"stage_text"=>"Complete",
																	"stage_text_color"=>"#009933",
																)
						]);
				if($upId){
					echo "Done <br>";
					
				}
				
				}else{
					echo 'Not--<br>';
				}
			}
			
			 
		} */
		die;
		
		
	}
	
	public function ajax_updateActualDate_pm(){
		
		//pr($_GET); die;
		$fms_id= $_GET['fms_id'];
		$stage_task_id_arr = getStageTaskIdByFmsId($fms_id);			
		$stage_task_oid = (array) $stage_task_id_arr['_id']; 
		$stage_task_id = $stage_task_oid['oid']; 
		//pr($stage_task_id); var_dump($stage_task_id); die;
		
		$id = $_GET['data_id'];
		$step_id = $_GET['step_id'];
		$row_num_step = $_GET['row_num_step'];
		$planed_date = $_GET['planed_date'];
		$data = array();
		$data = getStepDataByStepId($step_id);
		
		// for update pm status
		$pm_check =  $_GET['pm_check'];
		
		if(isset($_GET['store_room_id'])){
			$store_room_id =  $_GET['store_room_id'];
		}else{
			$fms_detail = getFmsDetailsById($fms_id);
			$store_room_id = $fms_detail['store_room_id'];
		}
			
			// check diffrence
			// $actual_date_check_main = date('Y/m/d H:i:s', strtotime('+1 hour'.$planed_date_check));
			$planed_date_check = date('Y/m/d H:i:s', strtotime('+1 hour'.$planed_date));
			
			$actual_date_check = date('Y/m/d H:i:s', time());
			
			
			$resHtml='';
			if($planed_date_check>$actual_date_check)
			{
				
				$resHtml= array(
									'bgcolor'=>BG_GREEN, // Green: 99da72
									'textcolor'=>'#fff',
									'date'=>changeDateToDmyHi($actual_date_check)
								);
			}else if($planed_date_check==$actual_date_check){
				$resHtml= array(
									'bgcolor'=>'#fff',
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
			
			$dateArr = array(
								'none_date'=>date('Y-m-d H:i:s'),
								'input_type'=>"none"
							);
							
		
		$stageTextArr = $data['fms_stage'];
		//echo "kk999=="; var_dump($stage_task_id); echo "---=="; pr($stageTextArr); die;
		// update fms_data collection
		
		/* <span style="color:#79d1c9">QC1 Done</span> */
		
		$upId = DB::table('fmsdatas')
				->where('_id', $id)
				->update([
							$step_id => $dateArr,
							$stage_task_id =>$stageTextArr
						]); 
				
		if($upId)
		{
			if($pm_check!=''){
				updateAntyaPmProductionStatus_api($fms_id, $pm_check, $store_room_id, $process='');
			}
			
			$resArr = array(
								'resHtml'=>$resHtml,
								'step_id'=>$step_id,
								'row_num_step'=>$row_num_step,
								'stage_text'=>'<span style="color:'.$stageTextArr['stage_text_color'].'">'.$stageTextArr['stage_text'].'</span>'
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_deleteFmsData()
	{
		//print_r($_GET);
		
		if(!empty($_GET))
		{
			$fmsDataId = $_GET['fmsDataId'];
			$idDeleted = DB::table('fmsdatas')->where('_id', '=', $fmsDataId)->delete();
			if($idDeleted)
			{
				echo "delete"; 
				exit;
			}else{
				echo "failed"; 
				exit;
			}
			
		}
	}
	
	
	public function getFabricatorDataById($fbId, $fms_id)
	{
		//echo $fbId."===============".$fms_id; die;
		$fabdatas 	= Fmsdata::where('fms_id', '=', $fms_id)
								->where('fb_id', '=', $fbId)
								->orderBy('created_at', 'asc')->get()->toarray();
		return view('fms.fabricator', compact('fabdatas'));
		/* echo "<pre>";
		print_r($fabdatas);
		die; */ 
	}
	
	
	public function getEmployeereport($fbId='', $fms_id='')
	{
		//echo $fbId."===============".$fms_id; 
		/* $fabdatas 	= Fmsdata::where('fms_id', '=', $fms_id)
								->where('fb_id', '=', $fbId)
								->orderBy('created_at', 'asc')->get()->toarray(); */
		
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
	
	
	public function ajax_getFabricatorData(Request $request)
	{
		$fms_id = $request->fms_id;
		$fb_name = $request->fb_name;
		$fb_id = $request->fb_id;
		
		$fabdatas = Fmsdata::where('fms_id', '=', $fms_id)
								->where('fb_id', '=', $fb_id)
								->orderBy('created_at', 'asc')->get()->toarray();
		
		//echo "<pre>"; print_r($fabdatas); die;
		if(!empty($fabdatas)){
			$fbfinalArr = get_fabricator_data_details($fabdatas, $fms_id);
			//echo "<pre>"; print_r($fbfinalArr); die;
			return json_encode($fbfinalArr);
		}else{
			$data = array('');
			return json_encode($data);
		}	
		
	}
	
	// for test to store order data
	public function storeorderdata(){
		
		//pr($_POST); die;		
		$data_insert = DB::table('order_data')->insert($_POST);
		if($data_insert){
			return "data inserted";
		}else{
			return "try again";
		}
	}
	
	
	/* Store SAMPLE STOCK ORDER */
	public function storesampleorderdataapi(Request $request)
    {
		$fms_id = '5d0c9aafd04904203e19dc62'; // for-live-sample order
		
		try{
		/* task data */
		$tasksIds = get_task_id_by_fms_id($fms_id);
		$taskArr = [];
		$taskArrMain = []; 
		$i=0;
		foreach($tasksIds as $tkey=>$tval){
			$taskArr[]= (array)$tval['_id'];
			$taskArrMain[]= $taskArr[$i]['oid'];
			$i++;
		}
		$data = array();

		$ordernum = explode('_',$request->input('o_unique_no'))[0];
		
		$item_id = $request->input('o_item');
		$o_quantity = $request->input('order_qty');
		
		$pm_assign = $request->input('pm_assign');
		
		
		$o_date = $request->input('o_date');
		$o_date = date('Y-m-d 17:00', strtotime($o_date));
		
		$o_deliverydate = $request->input('o_deliverydate');
		$o_deliverydate = date('Y-m-d 17:00', strtotime($o_deliverydate));
		
		$taskOrderArr = array(
								$o_date, 
								$ordernum, 
								$item_id,
								$o_deliverydate,
								'',
								''
							);
		
		$data['fms_id'] = $fms_id;
		$i=0;
		foreach($taskArrMain as $taskArr)
		{
			$data[$taskArr] = $taskOrderArr[$i];
			$i++;
		}
		
		/* task data end */
		
		/* step data */
		$stepkArr = [];
		$stepsIds = get_step_id_by_fms_id($fms_id);
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
			//$fms_when = $s_plane_dt['fms_when'];
			$planedDt = '';
			
			/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
			if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){					
				$data[$step] = array(
									'none_date'=>'',
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}else if($s_plane_dt['fms_when_type']==2 && $s_plane_dt['process']=='move_to_sho'){
				$data[$step] = array(
										'planed'=>'',
										'actual'=>''
									);
			}else if($s_plane_dt['fms_when_type']==2 && $s_plane_dt['fms_when']['input_type']=='custom_date'){
				$data[$step] = array(
									'delivery_date'=>date('Y-m-d H:i',strtotime($o_deliverydate)),
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}else if($s_plane_dt['fms_when_type']==1 && $s_plane_dt['process']=='st_com'){
				$data[$step] = array(
										'planed'=>date('Y-m-d 17:00',strtotime($o_deliverydate)),
										'actual'=>''
									);
			}else if($s_plane_dt['fms_when_type']==7){
				$data[$step] = array(
									'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
									'built_options_id'=>$s_plane_dt['fms_when']['input_type']
									);
			}else{
				$data[$step] = array(
									//'planed'=>date('Y-m-d'),
									'planed'=>$planedDt,
									'actual'=>''
								);
			}
								
			$data['fabricator']="";		
			$data['fb_id']="";		
			$data['pm_assign']=$pm_assign;		
								
			$i++;
		}
		/* step data end*/
		
		
		$inserted = $this->fmsdata::create($data);
		if($inserted)
		{
			return true;
		}
		
		}catch(Exception $ex){
			DB::rollback();
		}
		
    }
	/* End Store SAMPLE STOCK ORDER */
	
	/* Store GR data into FMS */
	public function storegrorderdataapi(Request $request)
    {
		// order_type
		$order_type = $request->input('order_type');
		$fms_id = '5d67b56bd04904752b64fc72'; // live
		
		
		try{
		/* task data */
		$tasksIds = get_task_id_by_fms_id($fms_id);
		$taskArr = [];
		$taskArrMain = []; 
		$i=0;
		foreach($tasksIds as $tkey=>$tval){
			$taskArr[]= (array)$tval['_id'];
			$taskArrMain[]= $taskArr[$i]['oid'];
			$i++;
		}
		$data = array();

		$o_date = date('Y-m-d H:i:s');
		
		
		$o_deliverydate = $request->input('o_deliverydate');
		
		//$ordernum = explode('_',$request->input('o_unique_no'))[0];
		$order_num = $request->input('order_num');
		
		$item_id = $request->input('o_item');
		$o_quantity = $request->input('order_qty');
		
		
		$o_reference = $request->input('o_reference');
		$o_deliverydate = date('Y-m-d H:i',strtotime('+5 days',strtotime($o_date)));			
		$taskOrderArr = array(
							$o_date,
							$order_num,
							$o_reference,
							$item_id,
							$o_deliverydate,
							''
						);
		
		
		$data['fms_id'] = $fms_id;
		$i=0;
		foreach($taskArrMain as $taskArr)
		{
			$data[$taskArr] = $taskOrderArr[$i];
			$i++;
		}
		
		/* task data end */
		
		/* step data */
		$stepkArr = [];
		$stepsIds = get_step_id_by_fms_id($fms_id);
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
			if (array_key_exists("task_id_link",$fms_when))
			{
				$Hr 	= $fms_when['after_task_time'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
			}
			
			/* ******** for step linked with step ******* */
			if (array_key_exists("after_step_id",$fms_when))
			{
				// get previous step plened_date
				$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
				
				$Hr 	= $fms_when['time_hr'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
			}
			
			/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
			if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){					
				$data[$step] = array(
									'none_date'=>'',
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}elseif($s_plane_dt['fms_when_type']==6){
				/* $data[$step] = array(
									'custom_options'=>$s_plane_dt['fms_when']['fms_when_type'],
									'custom_options_id'=>$s_plane_dt['fms_when']['input_type']
									); */
									
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
									//'planed'=>date('Y-m-d'),
									'planed'=>$planedDt,
									'actual'=>''
								);
			}
			
								
			$data['fabricator']="";		
			$data['fb_id']="";		
								
			$i++;
		}
		/* step data end*/
		
		//pr($data); die;
		$inserted = $this->fmsdata::create($data);
		if($inserted)
		{
			return true;
		}
		
		}catch(Exception $ex){
			DB::rollback();
		}
		
    }
	/*END Store GR data into FMS */
	
	public function storeorderdataapi_old(Request $request)
    {
		
		$fms_id = '';
		
		// stock_order == stock order 
		$order_type = $request->input('order_type'); 
		
		$pm_assign = $request->input('pm_assign'); 
		/* 22== first three orders,,,, ==53 stock order   */
		if($order_type=='stock_order'){
			if($pm_assign==22){
				
				$fms_id = '5d0e143dd049046360277682'; // first 3 orders live --Narayana
			}else if($pm_assign==63){
				
				$fms_id = '5d172c0bd049040158088692'; // first 3 orders live --Pachim vihar 
			}else{
				
				$fms_id = '5d0cd734d0490449dd7eb122'; // live
			}
			
		}else if($order_type=='custom_order'){
			$fms_id = '5d2c0a0ad0490410d22e4412'; // live fms
			
		}
		
		try{
		/* task data */
		$tasksIds = get_task_id_by_fms_id($fms_id);
		$taskArr = [];
		$taskArrMain = []; 
		$i=0;
		foreach($tasksIds as $tkey=>$tval){
			$taskArr[]= (array)$tval['_id'];
			$taskArrMain[]= $taskArr[$i]['oid'];
			$i++;
		}
		$data = array();

		//$o_date = date('Y-m-d H:i:s');
		$o_date_main = $request->input('o_date');
		$o_date = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_date_main)));
		
		$o_deliverydate = $request->input('o_deliverydate');
		
		$ordernum = explode('_',$request->input('o_unique_no'))[0];
		
		$item_id = $request->input('o_item');
		$o_quantity = $request->input('order_qty');
		
		
		
		if($order_type=='custom_order'){
			$o_reference = $request->input('o_reference');
			if($o_deliverydate!=''){
				$o_deliverydate = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_deliverydate)));
			}else{
				$o_deliverydate='';
			}
						
			$taskOrderArr = array(
								$o_date,
								$ordernum, 
								$item_id,
								$o_reference,
								$o_deliverydate,
								'',
								'', 
								''
							);
		}else{
			$taskOrderArr = array(
								$o_date,
								$ordernum, 
								$item_id,
								$o_quantity,
								'',
								''
							);
		}
		
		
		$data['fms_id'] = $fms_id;
		$i=0;
		foreach($taskArrMain as $taskArr)
		{
			$data[$taskArr] = $taskOrderArr[$i];
			$i++;
		}
		
		/* task data end */
		
		/* step data */
		$stepkArr = [];
		$stepsIds = get_step_id_by_fms_id($fms_id);
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
			if (array_key_exists("task_id_link",$fms_when))
			{
				$Hr 	= $fms_when['after_task_time'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
			}
			
			/* ******** for step linked with step ******* */
			if (array_key_exists("after_step_id",$fms_when))
			{
				// get previous step plened_date
				$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
				
				$Hr 	= $fms_when['time_hr'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
			}
			
			/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
			if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){					
				$data[$step] = array(
									'none_date'=>'',
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}elseif($s_plane_dt['fms_when_type']==6){
				/* $data[$step] = array(
									'custom_options'=>$s_plane_dt['fms_when']['fms_when_type'],
									'custom_options_id'=>$s_plane_dt['fms_when']['input_type']
									); */
									
				$data[$step] = array(
									'fms_when_type'=>$s_plane_dt['fms_when']['fms_when_type'],
									'input_type'=>$s_plane_dt['fms_when']['input_type']
									);
			}else if($s_plane_dt['fms_when_type']==7 && $s_plane_dt['fms_when']['input_type']!='tailor_name'){
				$data[$step] = array(
									'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
									'built_options_id'=>$s_plane_dt['fms_when']['input_type']
									);
			}else{
				$data[$step] = array(
									//'planed'=>date('Y-m-d'),
									'planed'=>$planedDt,
									'actual'=>''
								);
			}
			
								
			$data['fabricator']="";		
			$data['fb_id']="";		
								
			$i++;
		}
		/* step data end*/
		
		
		$inserted = $this->fmsdata::create($data);
		if($inserted)
		{
			return true;
		}
		
		}catch(Exception $ex){
			DB::rollback();
		}
		
    }
	
	public function storeorderdataapi(Request $request)
    {
		
		$fms_id = '';
		
		// stock_order == stock order 
		$order_type = $request->input('order_type'); 
		
		$pm_assign = $request->input('pm_assign'); 
		/* 22== first three orders,,,, ==53 stock order   */
		if($order_type=='stock_order'){
			if($pm_assign==22){
				
				$fms_id = '5d0e143dd049046360277682'; // first 3 orders live --Narayana
			}else if($pm_assign==63){
				
				$fms_id = '5d172c0bd049040158088692'; // first 3 orders live --Pachim vihar 
			}else{
				
				$fms_id = '5d0cd734d0490449dd7eb122'; // live
			}
			
		}else if($order_type=='custom_order'){
			$fms_id = '5d2c0a0ad0490410d22e4412'; // live fms
			
		}
		
		try{
			/* task data */
			$tasksIds = get_task_id_by_fms_id($fms_id);
			$taskArr = [];
			$taskArrMain = []; 
			$i=0;
			foreach($tasksIds as $tkey=>$tval){
				$taskArr[]= (array)$tval['_id'];
				$taskArrMain[]= $taskArr[$i]['oid'];
				$i++;
			}
			
			$data = array();

			//$o_date = date('Y-m-d H:i:s');
			$o_date_main = $request->input('o_date');
			$o_date = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_date_main)));
			
			$o_deliverydate = $request->input('o_deliverydate');
			
			$ordernum = explode('_',$request->input('o_unique_no'))[0];
			
			$item_id = $request->input('o_item');
			$o_quantity = $request->input('order_qty');
			
			$data['fms_id'] = $fms_id;
		
			if($order_type=='custom_order'){
				
				$o_reference = $request->input('o_reference');
				if($o_deliverydate!=''){
					$o_deliverydate = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_deliverydate)));
				}else{
					//$o_deliverydate='';
					$o_deliverydate = date('Y-m-d H:i',strtotime($o_date." +1 day"));
				}
							
				$taskOrderArr = array(
									$o_date,
									$ordernum, 
									$item_id,
									$o_reference,
									$o_deliverydate,
									'',
									'', 
									''
								);
				
				
					$i=0;
					foreach($taskArrMain as $taskArr)
					{
						$data[$taskArr] = $taskOrderArr[$i];
						$i++;
					}
			
					/* task data end */
					
					/* step data */
					$stepkArr = [];
					$stepsIds = get_step_id_by_fms_id($fms_id);
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
						if (array_key_exists("task_id_link",$fms_when))
						{
							$Hr 	= $fms_when['after_task_time'];
							$inc_dt = ceil($Hr/8);				
							$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
						}
						
						/* ******** for step linked with step ******* */
						if (array_key_exists("after_step_id",$fms_when))
						{
							// get previous step plened_date
							$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
							
							$Hr 	= $fms_when['time_hr'];
							$inc_dt = ceil($Hr/8);				
							$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
						}
						
						
						/* 22 Jan 2020 new condetion for #1 FABRIC IS READY, #4 EMBROIDERY IS READY  */
						
						//$o_date='2020-01-31';
						//$o_deliverydate= '2020-01-30';
						
						$dtDiff = dateDiffInDays($o_date, $o_deliverydate);
						$plTime='';
						if($dtDiff>=9){
							$avlDay = 7;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
						}else if($dtDiff==8){
							$avlDay = 5;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
						}else if($dtDiff==7){
							$avlDay = 4;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
						}else if($dtDiff==6){
							$avlDay = 3;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
						}else if($dtDiff==5){
							$avlDay = 3;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));							
						}else if($dtDiff==4){
							$avlDay = 2;
							$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
						}else if($dtDiff==0){
							$plTime = date('Y-m-d 17:00', strtotime($o_date));
						}else if($dtDiff<=3 && $dtDiff!=0){
							$avlDay = 1;
							$plTime = date('Y-m-d 17:00', strtotime($o_date."+ 1 days"));
						}
						
						// fb ready = 5d2c114fd049041cc660fa44
						// emb ready = 5d2c114fd049041cc660fa48
						
						if($step=='5d2c114fd049041cc660fa44' || $step=='5d2c114fd049041cc660fa48'){
							$planedDt = $plTime;
						}
						
						/* 22 Jan 2020 new condetion for #1 FABRIC IS READY, #4 EMBROIDERY IS READY  ===END */
						
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
						}else if($s_plane_dt['fms_when_type']==7 && $s_plane_dt['fms_when']['input_type']!='tailor_name'){
							$data[$step] = 	array(
													'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
													'built_options_id'=>$s_plane_dt['fms_when']['input_type']
												);
						}else{
							$data[$step] = 	array(
													'planed'=>$planedDt,
													'actual'=>''
												);
						}
						
											
						$data['fabricator']="";		
						$data['fb_id']="";		
											
						$i++;
					}
					/* step data end*/
				
			}else{
					$taskOrderArr = array(
											$o_date,
											$ordernum, 
											$item_id,
											$o_quantity,
											'',
											''
										);
								
				
					$i=0;
					foreach($taskArrMain as $taskArr)
					{
						$data[$taskArr] = $taskOrderArr[$i];
						$i++;
					}
			
					/* task data end */
					
					/* step data */
					$stepkArr = [];
					$stepsIds = get_step_id_by_fms_id($fms_id);
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
						if (array_key_exists("task_id_link",$fms_when))
						{
							$Hr 	= $fms_when['after_task_time'];
							$inc_dt = ceil($Hr/8);				
							$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
						}
						
						/* ******** for step linked with step ******* */
						if (array_key_exists("after_step_id",$fms_when))
						{
							// get previous step plened_date
							$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
							
							$Hr 	= $fms_when['time_hr'];
							$inc_dt = ceil($Hr/8);				
							$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
						}
						
						/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
						if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){					
							$data[$step] = array(
												'none_date'=>'',
												'input_type'=>$s_plane_dt['fms_when']['input_type']
												);
						}elseif($s_plane_dt['fms_when_type']==6){
							/* $data[$step] = array(
												'custom_options'=>$s_plane_dt['fms_when']['fms_when_type'],
												'custom_options_id'=>$s_plane_dt['fms_when']['input_type']
												); */
												
							$data[$step] = array(
												'fms_when_type'=>$s_plane_dt['fms_when']['fms_when_type'],
												'input_type'=>$s_plane_dt['fms_when']['input_type']
												);
						}else if($s_plane_dt['fms_when_type']==7 && $s_plane_dt['fms_when']['input_type']!='tailor_name'){
							$data[$step] = array(
												'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
												'built_options_id'=>$s_plane_dt['fms_when']['input_type']
												);
						}else{
							$data[$step] = array(
												//'planed'=>date('Y-m-d'),
												'planed'=>$planedDt,
												'actual'=>''
											);
						}
						
											
						$data['fabricator']="";		
						$data['fb_id']="";		
											
						$i++;
					}
					/* step data end*/
				
				
			}
			/* else end */
		
		
			$inserted = $this->fmsdata::create($data);
			if($inserted)
			{
				return true;
			}
		
		}catch(Exception $ex){
			DB::rollback();
		}
		
    }
	
	public function ajax_savefabricator_in_data_list(Request $request)
	{
		$data_id = $request->data_id;
		$data_o_id = $request->data_o_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		
		$fabricator = $request->fab_name;
		$fab_id = $request->fab_id;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($fabricator); */
		
		$dateArr = array(
							'fb_id'=>$fab_id,
							'fabricator'=>$fabricator,
							$step_id=>array(
											'fb_id'=>$fab_id,
											'fabricator'=>$fabricator
											)
						);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			if($data_o_id!=''){
				updateAntyaPmProductionStatusPacked_api($data_o_id);
			}

			$resArr = array(
								'msg'=>'Fabricator updated',
								'step_id'=>$step_id,
								'fabricator'=>$fabricator,
							);
							
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	
	
	public function ajax_savefabricator_in_data(Request $request)
	{
		$data_id = $request->data_id;
		$data_o_id = $request->data_o_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		
		$fabricator = $request->fab_name;
		$fab_id = $request->fab_id;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($fabricator); */
		
		$dateArr = array(
							'fb_id'=>$fab_id,
							'fabricator'=>$fabricator,
							$step_id=>array(
											'fb_id'=>$fab_id,
											'fabricator'=>$fabricator
											)
						);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			if($data_o_id!=''){
				updateAntyaPmProductionStatusPacked_api($data_o_id);
			}

			$resArr = array(
								'msg'=>'Fabricator updated',
								'step_id'=>$step_id,
								'fabricator'=>$fabricator,
							);
							
			
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	///
	public function ajax_saveItem_in_data(Request $request)
	{
		$data_id = $request->data_id;
		$task_id = $request->task_id;
		$fms_id = $request->fms_id;
		
		$item_list = $request->item_list;
		
		/* pr($data_id);
		pr($task_id);
		pr($fms_id);
		pr($item_list); die; */
		
		$dateArr = array($task_id=>$item_list);
		// update data
		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'Item updated successfully',
								'task_id'=>$task_id,
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	///
	
	public function ajax_savetask_data(Request $request)
	{
		
		
		$data_id = $request->data_id;
		$task_id = $request->task_id;
		$fms_id = $request->fms_id;
		$user_cmnt = $request->user_task_cmnt;
		
		$user = auth()->user();
		
		if(isset($_POST['task_update_type'])){
			$dateArr = array(
							$task_id=>$user_cmnt
						);
		}else{
			$dateArr = array(
							$task_id=>array(
											"comment_by"=>$user->id,
											"comment"=>$user_cmnt
											)
						);
		}
		
		

		$upId = DB::table('fmsdatas')
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'Value updated',
								'task_id'=>$task_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_save_task_status_in_data(Request $request)
	{
	    $data_id = $request->data_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		$user_cmnt = implode(',',$request->date_time);
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		$user = auth()->user();
        $dateArr = array(
        $step_id=>array(
        "task_by"=>$user->id,
        "task_date"=>$user_cmnt
        )
        );
        
        $upId = DB::table($fms_data_tbl)
				->where('_id', $data_id)
				->update($dateArr);
				
        if($upId)
        {
        $resArr = array(
        'msg'=>'Task Date and Time updated',
        'step_id'=>$step_id
        );
        echo json_encode($resArr);
        }else{
        echo "failed";
        }
        exit;
        
        //pr($dateArr); die;
	}
	
	public function ajax_savecomment_in_data(Request $request)
	{
		//pr($_POST); die;		
		$data_id = $request->data_id;
		$step_id = $request->step_id;
		$fms_id = $request->fms_id;
		$user_cmnt = $request->user_cmnt;
		
		$fms_detail = getFmsDetailsById($fms_id);
		//pr($fms_detail); die;
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$user = auth()->user();		
		$dateArr = array(
							$step_id=>array(
											"comment_by"=>$user->id,
											"comment"=>$user_cmnt
											)
						);
        pr($dateArr); die;
		$upId = DB::table($fms_data_tbl)
				->where('_id', $data_id)
				->update($dateArr); 
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'Comment updated',
								'step_id'=>$step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_updatedate_and_time(Request $request){
		$currentDateTime 	= date('Y-m-d H:i:s', strtotime($request->currentDateTime));
		$currentDateTime2 	= changeDateToDmyHi($currentDateTime);
		$actual_data_id 	= $request->actual_data_id;
		$actual_step_id 	= $request->actual_step_id;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row;
		
		//$s_plane_dt = getStepDataByStepId($actual_step_id);
		
		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];

		$data_details = getFmsdataDetailByTbaleNameAndDataId($fms_data_tbl,$actual_data_id);		

		$dateArr = array(
							$actual_step_id=>array(
											"planed"=>$data_details[$actual_step_id]['planed'],
											"actual"=>$currentDateTime
											)
						);
						
		//pr($dateArr); die;
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'current_date'=>$currentDateTime2,
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_updatedate_and_time_cal(Request $request){
		
		$currentDateTime 	= date('Y-m-d H:i:s', strtotime($request->currentDateTime_cal));
		$currentDateTime2 	= changeDateToDmyHi($currentDateTime);
		$actual_data_id 	= $request->actual_data_id_cal;
		$actual_step_id 	= $request->actual_step_id_cal;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_cal;
		
		$s_plane_dt = getStepDataByStepId($actual_step_id);
		//pr($s_plane_dt); die;
		/* get fms main table details by fms id */
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];		
		$crntUid = Auth::user()->id;
		$dateArr = array(
							"etd"=>$currentDateTime,
							$actual_step_id=>array(
														'calendar'=>$currentDateTime,
														'user_id'=>$crntUid
													)
						);
						
		//pr($dateArr); die;
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			
			/* update those step which is depend on new ETD */
			if(array_key_exists('action', $s_plane_dt)){
				if($s_plane_dt['action']=='new_etd'){
					/* here update step which depend on new ETD */
					$all_step_data = getAllStepDataByFmsId($fms_id);
					foreach($all_step_data as $key=>$step){
						
						if(array_key_exists('depend_new_etd',$step)){
							$depend_steps_arr = $step['depend_new_etd'];
							$depend_step_id = $depend_steps_arr['step_id'];
							$depend_time_hr = $depend_steps_arr['time_hr'];
							
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid']; 
								
								$plDt = date('Y-m-d H:i',strtotime('+'.$depend_time_hr.' hours', strtotime($currentDateTime)));
								$plDt = checkSunday($plDt);
								$plannedArr = array(
													'planed'=>$plDt,
													'actual'=>''
												);
								DB::table($fms_data_tbl)->where('_id', $actual_data_id)->update([
																						$curr_step =>$plannedArr
																					]);
						}
						
						if(array_key_exists('before_etd_time',$step['fms_when'])){
							
							$before_etd_time = $step['fms_when']['before_etd_time'];
							//$before_days = ceil($before_etd_time/8);
							
								$oid = (array) $step['_id'];
								$curr_step =  $oid['oid'];
								
								$plDt = date('Y-m-d H:i',strtotime('-'.$before_etd_time.' hours', strtotime($currentDateTime)));
								$plDt = checkSunday($plDt);
								$plannedArr = array(
									'planed'=>$plDt,
									'actual'=>''
								);
								//pr($plannedArr); die('==pp---===');
								DB::table($fms_data_tbl)->where('_id', $actual_data_id)->update([
																						$curr_step =>$plannedArr
																					]);
						}
						
						
						
					}
					
					
				}
			}
			
			//$take_delivery_date	= date('d.m.y', strtotime($currentDateTime.'+3 days'));
			
			
			$pld =  date('D', strtotime($currentDateTime.'+3 days'));
			if($pld=='Sun'){
				$take_delivery_date	= date('d.m.y', strtotime($currentDateTime.'+4 days'));
			}else{
				$take_delivery_date	= date('d.m.y', strtotime($currentDateTime.'+3 days'));
			}
			
			$resArr = array(
								'current_date'=>$currentDateTime2,
								'take_delivery_date'=>$take_delivery_date,
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_updatedate_and_time_cal_fup(Request $request){
		
		//r($_POST); die('dghsdhg');
		
		$none_date = $request->currentDateTime_cal_fup;
		$none_date 	= date('Y-m-d H:i:s', strtotime($none_date));
		$none_date2 	= changeDateToDmyHi($none_date);
		$actual_data_id 	= $request->actual_data_id_cal_fup;
		$actual_step_id 	= $request->actual_step_id_cal_fup;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_cal_fup;
		
		$s_plane_dt = getStepDataByStepId($actual_step_id);
		//pr($s_plane_dt); die;
		$crntUid = Auth::user()->id;
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];		

		$dateArr = array(
							$actual_step_id=>array(
														"none_date"=>$none_date,
														"input_type"=>'none',
														"user_id"=>$crntUid
												)
						);
						
		//pr($dateArr); die('----');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'current_date'=>date('d.m.y', strtotime($none_date)),
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_update_date_and_time_import_cal(Request $request){
		//pr($_POST); die();	
		$crntUid = Auth::user()->id;
		$none_date = $request->currentDateTime_cal_fup;
		$none_date 	= date('Y-m-d H:i:s', strtotime($none_date));
		
		$planned_date =  $request->planned_date;
		$planned_date 	= date('Y-m-d H:i:s', strtotime($planned_date));
		
		$none_date2 	= changeDateToDmyHi($none_date);
		$actual_data_id 	= $request->actual_data_id_cal_fup;
		$actual_step_id 	= $request->actual_step_id_cal_fup;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_cal_fup;
		
		$s_plane_dt = getStepDataByStepId($actual_step_id);
		//pr($s_plane_dt); die;
	
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];		
		
		// [process] => rev_delivery_date
		$process='';
		if(array_key_exists('process', $s_plane_dt) && $s_plane_dt['process']=='rev_delivery_date'){
			$process='rev_delivery_date';
			$dateArr = array(
							$actual_step_id=>array(
														"actual_rev"=>date('Y-m-d H:i:s'),
														"rev_rev"=>$none_date,
														"user_id"=>$crntUid,
												)
						);
		}else{
			$dateArr = array(
							$actual_step_id=>array(
														"planed"=>$planned_date,
														"actual"=>$none_date,
														"user_id"=>$crntUid,
												)
						);
		}
		
						
		//pr($dateArr); die('----');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'current_date'=>date('d.m.y', strtotime($none_date)),
								'today_dt'=>date('d.m.y'),
								'process'=>$process,
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	
	public function ajax_updatedate_bulk_qty(Request $request){
		//pr($_POST); die('===');
		$planned_bulk 	=$request->planned_bulk;
		$actual_bulk 	=$request->actual_bulk;
		$actual_data_id 	= $request->actual_data_id_bulk;
		$actual_step_id 	= $request->actual_step_id_bulk;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_bulk;
				
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		$crntUid = Auth::user()->id;
		
		$dateArr = array(
							$actual_step_id=>array(
													"pl_qty"=>$planned_bulk,
													"act_qty"=>$actual_bulk,
													"user_id"=>$crntUid
												)
						);
						
		//pr($dateArr); die('------new');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								'planned_bulk'=>$planned_bulk,
								'actual_bulk'=>$actual_bulk,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	public function ajax_update_pi_data(Request $request){
		//pr($_POST); die('===ajax_update_pi_data');
		
		$planned_pi 	=$request->planned_pi;
		$actual_pi 	= $request->actual_pi;
		
		$actual_pi = date('Y-m-d H:i:s');
		
		
		$pi_number 	= $request->pi_number;
		
		$actual_data_id 	= $request->actual_data_id_pi;
		$actual_step_id 	= $request->actual_step_id_pi;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_pi;
				
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		$crntUid = Auth::user()->id;
		$dateArr = array(
							$actual_step_id=>array(
													"planed"=>$planned_pi,
													"actual"=>$actual_pi,
													"pi_number"=>$pi_number,
													"user_id"=>$crntUid
												)
						);
						
		//pr($dateArr); die('------new=='.$actual_data_id);
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								'planned_pi'=>$planned_pi,
								'actual_pi'=>changeDateToDmyHi($actual_pi),
								'pi_number'=>$pi_number,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	
	public function ajax_updatedate_inv_details(Request $request){
		//pr($_POST); die('===');
		
		$inv_date 	=$request->inv_date;
		$inv_no 	=$request->inv_no;
		$inv_amount 	=$request->inv_amount;
		$actual_data_id 	= $request->actual_data_id_inv;
		$actual_step_id 	= $request->actual_step_id_inv;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_inv;
		$payment_term = $request->payment_term;
		$crntUid = Auth::user()->id;
		//echo $payment_term;
		if($inv_date!=''){
		
		$delivery_dt_step = '5e9a9ecb0f0e750494006a36';
		
		$do_collection_step = '5e9a9ecb0f0e750494006a37';
		$collection_pl='';
		$collection_pl_arr='';
		$p_terms = $payment_term;
		if($p_terms!=''){
			$p_terms_arr = explode('_',$p_terms);
			if(strpos($p_terms, 'pdc') !== false){
				$credit_period = $p_terms_arr[0];
				
				$inv_date_new = date('Y-m-d H:i',strtotime($inv_date." +".$credit_period." days"));
				$collection_pl = date('Y-m-d',strtotime($inv_date_new." -3 days"));	
				
				$collection_pl_arr = array(
											'planed'=>$collection_pl,
											'actual'=>'',
											);
			}else{
				$collection_pl_arr = array(
											'planed'=>'',
											'actual'=>'',
											);
			}
				
		}
		
		
		
				
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$dateArr = array(
							$actual_step_id=>array(
													"inv_date"=>date('Y-m-d H:i:s', strtotime($inv_date)),
													"inv_no"=>$inv_no,
													"inv_amount"=>$inv_amount,
													'user_id'=>$crntUid
												),
							$delivery_dt_step=>array(
													'planed'=>date('Y-m-d H:i',strtotime($inv_date." +1 days")),
													'actual'=>'',
												),
							$do_collection_step=>$collection_pl_arr,
							
												
						);
						
		//pr($dateArr); die('------new');
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								"inv_date"=>date('d.m.y', strtotime($inv_date)),
								"inv_no"=>$inv_no,
								"inv_amount"=>$inv_amount,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr); exit;
		}else{
			echo "failed"; exit;
		}
		}else{
			echo "failed"; exit;
		}
		exit;
	}
	
	public function ajax_updatedate_inv_details_po_old(Request $request){
		//pr($_POST); die('===');
		$inv_no 	=$request->inv_no;
		$actual_data_id 	= $request->actual_data_id_inv;
		$actual_step_id 	= $request->actual_step_id_inv;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_inv;

		if($inv_no!=''){		
				
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$dateArr = array(
							$actual_step_id=>array(
													"inv_date"=>date('Y-m-d H:i:s'),
													"inv_no"=>$inv_no
												)
							
												
						);
						
		//pr($dateArr); die('------new');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								"inv_no"=>$inv_no,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr); exit;
		}else{
			echo "failed"; exit;
		}
		}else{
			echo "failed"; exit;
		}
		exit;
	}
	
	public function ajax_updatedate_inv_details_po(Request $request){
		//pr($_POST); die('===');
		
		$inv_date 	=$request->inv_date;
		$inv_no 	=$request->inv_no;
		$inv_amount 	=$request->inv_amount;
		$actual_data_id 	= $request->actual_data_id_inv;
		$actual_step_id 	= $request->actual_step_id_inv;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_inv;
		
		if($inv_date!=''){
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];		
		$dateArr = array(
							$actual_step_id=>array(
													"inv_date"=>date('Y-m-d H:i:s', strtotime($inv_date)),
													"inv_no"=>$inv_no,
													"inv_amount"=>$inv_amount
												)
						);
						
		//pr($dateArr); die('------new');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								"inv_date"=>date('d.m.y', strtotime($inv_date)),
								"inv_no"=>$inv_no,
								"inv_amount"=>$inv_amount,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr); exit;
		}else{
			echo "failed"; exit;
		}
		}else{
			echo "failed"; exit;
		}
		exit;
	}
	
	
	public function ajax_updatedate_inv_details_sampling(Request $request){
		//pr($_POST); die('===');
		
		$inv_date 		=	$request->inv_date;
		$inv_no 		=	$request->inv_no;
		$inv_amount 	=	$request->inv_amount;
		$awb_no 		=	$request->awb_no;
		$courier_company 	=	$request->courier_company;
		$actual_data_id 	= $request->actual_data_id_inv;
		$actual_step_id 	= $request->actual_step_id_inv;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_inv;

		if($inv_date!=''){
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		$crntUid = Auth::user()->id;
		$dateArr = array(
							$actual_step_id=>array(
													"inv_date"=>date('Y-m-d H:i:s', strtotime($inv_date)),
													"inv_no"=>$inv_no,
													"inv_amount"=>$inv_amount,
													"awb_no"=>$awb_no,
													"courier_company"=>$courier_company,
													"user_id"=>$crntUid
												)		
						);
						
		//pr($dateArr); die('------new');
		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
			if($upId)
			{
				$resArr = array(
									'msg'=>'success',
									"inv_date"=>date('d.m.y', strtotime($inv_date)),
									"inv_no"=>$inv_no,
									"inv_amount"=>$inv_amount,
									"awb_no"=>$awb_no,
									"courier_company"=>$courier_company,
									'step_id'=>$actual_step_id
								);
				echo json_encode($resArr); exit;
			}else{
				echo "failed"; exit;
			}
		}else{
			echo "failed"; exit;
		}
		exit;
	}
	
	
	public function ajax_updatedate_inv_details_samplecard(Request $request){
		//pr($_POST); die('===');		
		$awb_no 			=	$request->awb_no;
		$courier_company 	=	$request->courier_company;
		$actual_data_id 	= $request->actual_data_id_inv;
		$actual_step_id 	= $request->actual_step_id_inv;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row_inv;
		//pr($_POST); die('===');
		
		$fms_detail = getFmsDetailsById($fms_id);
		$fms_data_tbl = $fms_detail['fms_table'];
		
		$dateArr = array(
							$actual_step_id=>array(
													"awb_no"=>$awb_no,
													"courier_company"=>$courier_company,
												)		
						);
						
		//pr($dateArr); die('------new');		
		$upId = DB::table($fms_data_tbl)
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'msg'=>'success',
								"awb_no"=>$awb_no,
								"courier_company"=>$courier_company,
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr); exit;
		}else{
			echo "failed"; exit;
		}
		
		exit;
	}
	
	
	public function ajax_set_date_and_time_sample(Request $request){
		$currentDateTime 	= date('Y-m-d H:i:s', strtotime($request->currentDateTime));
		$currentDateTime2 	= changeDateToDmyHi($currentDateTime);
		$actual_data_id 	= $request->actual_data_id;
		$actual_step_id 	= $request->actual_step_id;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row;
		
		//$s_plane_dt = getStepDataByStepId($actual_step_id);
		
		//$data_details = getFmsdataDetailByDataId($actual_data_id);
		
		$dateArr = array(
							$actual_step_id=>array(
											"planed"=>$currentDateTime,
											"actual"=>''
											)
						);
						
		//pr($dateArr); die;
		
		$upId = DB::table('fmsdatas')
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'current_date'=>$currentDateTime2,
								'date_in_ymdhis'=>date('Y-m-d H:i:s', strtotime($request->currentDateTime)),
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		}
		exit;
	}
	
	// ajax_update_order_date
	
	public function ajax_update_order_date(Request $request){
		
					
		//pr($_POST);
		/* 
			<pre>Array
			(
			[currentOrderDate] => 31-01-2020
			[actual_data_id_order] => 5e1eb144d04904243379cbd2
			[actual_task_id_order] => 5d0cd734d0490449dd7eb123
			[fms_id] => 5d0cd734d0490449dd7eb122
			[actual_id_with_row_order] => row12
			[actual_order_no] => 29964
			[o_item] => 2264
			[order_qty] => 5
			[order_type] => stock_order
			[pm_assign] => stock
			)
			</pre>
		*/
		 
		//die();
		
		if(!empty($_POST)){
			
				$fms_id =$request->input('fms_id');
				$data_id = $request->input('actual_data_id_order');
				// stock_order == stock order 
				$order_type = $request->input('order_type'); 
				
				//$pm_assign = $request->input('pm_assign'); 
				/* 22== first three orders,,,, ==53 stock order   */
				
				/* if($order_type=='stock_order'){
					if($pm_assign==22){
						
						$fms_id = '5d0e143dd049046360277682'; // first 3 orders live --Narayana
					}else if($pm_assign==63){
						
						$fms_id = '5d172c0bd049040158088692'; // first 3 orders live --Pachim vihar 
					}else{
						
						$fms_id = '5d0cd734d0490449dd7eb122'; // live
					}
					
				}else if($order_type=='custom_order'){
					$fms_id = '5d2c0a0ad0490410d22e4412'; // live fms
					
				}	 */
				
				//echo $fms_id; die('----from--controller----');
				
				//$fms_id = $request->input('fms_id'); 
				
				try{
					/* task data */
					$tasksIds = get_task_id_by_fms_id($fms_id);
					$taskArr = [];
					$taskArrMain = []; 
					$i=0;
					foreach($tasksIds as $tkey=>$tval){
						$taskArr[]= (array)$tval['_id'];
						$taskArrMain[]= $taskArr[$i]['oid'];
						$i++;
					}
					
					$data = array();

					//$o_date = date('Y-m-d H:i:s');
					$o_date_main = $request->input('currentOrderDate');
					$o_date = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_date_main)));
					
					//$o_deliverydate = $request->input('o_deliverydate');
					$o_deliverydate = '';
					
					//$ordernum = explode('_',$request->input('o_unique_no'))[0];
					$ordernum = $request->input('actual_order_no');
					
					$item_id = $request->input('o_item');
					$o_quantity = $request->input('order_qty');
					
					$data['fms_id'] = $fms_id;
				
					if($order_type=='custom_order'){
						
						$o_reference = $request->input('o_reference');
						if($o_deliverydate!=''){
							$o_deliverydate = date('Y-m-d H:i',strtotime('+17 hours',strtotime($o_deliverydate)));
						}else{
							//$o_deliverydate='';
							$o_deliverydate = date('Y-m-d H:i',strtotime($o_date." +1 day"));
						}
									
						$taskOrderArr = array(
											$o_date,
											$ordernum, 
											$item_id,
											$o_reference,
											$o_deliverydate,
											'',
											'', 
											''
										);
						
						
							$i=0;
							foreach($taskArrMain as $taskArr)
							{
								$data[$taskArr] = $taskOrderArr[$i];
								$i++;
							}
					
							/* task data end */
							
							/* step data */
							$stepkArr = [];
							$stepsIds = get_step_id_by_fms_id($fms_id);
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
								if (array_key_exists("task_id_link",$fms_when))
								{
									$Hr 	= $fms_when['after_task_time'];
									$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
								}
								
								/* ******** for step linked with step ******* */
								if (array_key_exists("after_step_id",$fms_when))
								{
									// get previous step plened_date
									$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
									
									$Hr 	= $fms_when['time_hr'];
									$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
								}
								
								
								/* 22 Jan 2020 new condetion for #1 FABRIC IS READY, #4 EMBROIDERY IS READY  */
								
								//$o_date='2020-01-31';
								//$o_deliverydate= '2020-01-30';
								
								$dtDiff = dateDiffInDays($o_date, $o_deliverydate);
								$plTime='';
								if($dtDiff>=9){
									$avlDay = 7;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
								}else if($dtDiff==8){
									$avlDay = 5;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
								}else if($dtDiff==7){
									$avlDay = 4;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
								}else if($dtDiff==6){
									$avlDay = 3;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
								}else if($dtDiff==5){
									$avlDay = 3;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));							
								}else if($dtDiff==4){
									$avlDay = 2;
									$plTime = date('Y-m-d 17:00', strtotime($o_deliverydate."- ".$avlDay." days"));
								}else if($dtDiff==0){
									$plTime = date('Y-m-d 17:00', strtotime($o_date));
								}else if($dtDiff<=3 && $dtDiff!=0){
									$avlDay = 1;
									$plTime = date('Y-m-d 17:00', strtotime($o_date."+ 1 days"));
								}
								
								// fb ready = 5d2c114fd049041cc660fa44
								// emb ready = 5d2c114fd049041cc660fa48
								
								if($step=='5d2c114fd049041cc660fa44' || $step=='5d2c114fd049041cc660fa48'){
									$planedDt = $plTime;
								}
								
								/* 22 Jan 2020 new condetion for #1 FABRIC IS READY, #4 EMBROIDERY IS READY  ===END */
								
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
								}else if($s_plane_dt['fms_when_type']==7 && $s_plane_dt['fms_when']['input_type']!='tailor_name'){
									$data[$step] = 	array(
															'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
															'built_options_id'=>$s_plane_dt['fms_when']['input_type']
														);
								}else{
									$data[$step] = 	array(
															'planed'=>$planedDt,
															'actual'=>''
														);
								}
								
													
								$data['fabricator']="";		
								$data['fb_id']="";		
													
								$i++;
							}
							/* step data end*/
						
					}else{
							$taskOrderArr = array(
													$o_date,
													$ordernum, 
													$item_id,
													$o_quantity,
													'',
													''
												);
										
						
							$i=0;
							foreach($taskArrMain as $taskArr)
							{
								$data[$taskArr] = $taskOrderArr[$i];
								$i++;
							}
					
							/* task data end */
							
							/* step data */
							$stepkArr = [];
							$stepsIds = get_step_id_by_fms_id($fms_id);
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
								if (array_key_exists("task_id_link",$fms_when))
								{
									$Hr 	= $fms_when['after_task_time'];
									$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
								}
								
								/* ******** for step linked with step ******* */
								if (array_key_exists("after_step_id",$fms_when))
								{
									// get previous step plened_date
									$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
									
									$Hr 	= $fms_when['time_hr'];
									$inc_dt = ceil($Hr/8);				
									$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
								}
								
								/* for FABRIC COMMENTS (6) and  ORDER EMBROIDERY (7)*/
								if($s_plane_dt['fms_when_type']==5 && $s_plane_dt['fms_when']['input_type']=='none'){					
									$data[$step] = array(
														'none_date'=>'',
														'input_type'=>$s_plane_dt['fms_when']['input_type']
														);
								}elseif($s_plane_dt['fms_when_type']==6){
									/* $data[$step] = array(
														'custom_options'=>$s_plane_dt['fms_when']['fms_when_type'],
														'custom_options_id'=>$s_plane_dt['fms_when']['input_type']
														); */
														
									$data[$step] = array(
														'fms_when_type'=>$s_plane_dt['fms_when']['fms_when_type'],
														'input_type'=>$s_plane_dt['fms_when']['input_type']
														);
								}else if($s_plane_dt['fms_when_type']==7 && $s_plane_dt['fms_when']['input_type']!='tailor_name'){
									$data[$step] = array(
														'built_options'=>$s_plane_dt['fms_when']['fms_when_type'],
														'built_options_id'=>$s_plane_dt['fms_when']['input_type']
														);
								}else{
									$data[$step] = array(
														//'planed'=>date('Y-m-d'),
														'planed'=>$planedDt,
														'actual'=>''
													);
								}
								
													
								$data['fabricator']="";		
								$data['fb_id']="";		
													
								$i++;
							}
							/* step data end*/
						
						
					}
					/* else end */				
					//pr($data); die('===last==');
					$new_o_dt = date('Y-m-d', strtotime($o_date));
					
					$updated = $this->fmsdata::where('_id', $data_id)
												->update($data);
												
					
					if($updated)
					{
						/* now update order date in PM */
						$res = updatePmOrderDateInPM_api($ordernum, $new_o_dt);		
						if($res){
							echo "1"; die();
						}else{
							echo "0"; die();
						}						
						
					}else{
						echo "0"; die();
					}
				
				}catch(Exception $ex){
					DB::rollback();
				}
			
		}		
		
		/* $currentDateTime 	= date('Y-m-d H:i:s', strtotime($request->currentDateTime));
		$currentDateTime2 	= changeDateToDmyHi($currentDateTime);
		$actual_data_id 	= $request->actual_data_id;
		$actual_step_id 	= $request->actual_step_id;
		$fms_id 			= $request->fms_id;
		$actual_id_with_row = $request->actual_id_with_row; */
		
		//$s_plane_dt = getStepDataByStepId($actual_step_id);
		
		
		
		/* $data_details = getFmsdataDetailByDataId($actual_data_id);
		
		$dateArr = array(
							$actual_step_id=>array(
											"planed"=>$data_details[$actual_step_id]['planed'],
											"actual"=>$currentDateTime
											)
						);		
		$upId = DB::table('fmsdatas')
				->where('_id', $actual_data_id)
				->update($dateArr);
				
		if($upId)
		{
			$resArr = array(
								'current_date'=>$currentDateTime2,
								'msg'=>'success',
								'step_id'=>$actual_step_id
							);
			echo json_encode($resArr);
		}else{
			echo "failed";
		} */
		exit;
	}
	
	
	/* ******* MIS Time ********* */
	
	function getTimeDelay_test($curentStepId){
			$s_plane_dt = getStepDataByStepId($curentStepId);
			$fms_when = $s_plane_dt['fms_when'];
			$planedDt = '';
			/* ******** for step linked with task ******* */
			if (array_key_exists("task_id_link",$fms_when))
			{
				$Hr 	= $fms_when['after_task_time'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($o_date . " + ".$inc_dt." day"));
			}
			
			/* ******** for step linked with step ******* */
			if (array_key_exists("after_step_id",$fms_when))
			{
				// get previous step plened_date
				$pr_step_planed_date = $data[$fms_when['after_step_id']]['planed'];
				
				$Hr 	= $fms_when['time_hr'];
				$inc_dt = ceil($Hr/8);				
				$planedDt = date('Y-m-d H:i:s', strtotime($pr_step_planed_date . " + ".$inc_dt." day"));
			}
			
				return $planedDt;	
				
	}
	
	/* START print fms data */
	public function printfms($id,$from=''){
		$fms_id = $id;
		$taskdatas = Fmstask::where('fms_id', '=', $id)->orderBy('task_step', 'asc')->get()->toarray();
		$orderStepId = '';
		$orderDateTaskId ='';
		foreach($taskdatas as $tk=>$tv){
			if($tv['custom_type']=='orders'){
				$orderStepId=$tv['_id'];
			}else if($tv['input_type']=='custom_fields' && $tv['custom_type']=='date'){
				$orderDateTaskId=$tv['_id'];
			}
		}
		
		if(empty($taskdatas)){ return redirect()->route('fmslist')->with('success', 'No data found.'); }
		
		$fms = Fms::where('_id', '=', $id)->get()->toarray();
		$fms = $fms[0];
		
		$stepsdatas = Fmsstep::where('fms_id', '=', $id)->orderBy('step', 'asc')->get()->toarray();
		$fmsdatas=array();
		if($fms['fms_type']=='sample_order'){
			$fmsdatas 	= Fmsdata::where('fms_id', '=', $id)->orderBy($orderStepId, 'desc')->get()->toarray();
		}else{
			if($from==''){
				/* $cMnth = date("m");
				$lastDay = date("t");
				$date_start = date('2019-'.$cMnth.'-01');
				$date_end = date('2019-'.$cMnth.'-'.$lastDay); */
				
				$date_start = date('Y-m-01');
				$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));
				
				$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
										->whereBetween($orderDateTaskId, array($date_start, $date_end))
										->orderBy($orderStepId, 'desc')
										->paginate(500)->toarray();
				$fmsdatas = $fmsdatas_pg['data'];
			}else{
				/* $cMnth = $from;
				$lastDay = date("t", strtotime('2019-'.$cMnth.'-23'));				
				$date_start = date('2019-'.$cMnth.'-01');
				$date_end = date('2019-'.$cMnth.'-'.$lastDay); */
				
				$date_start = $from;
				$date_end = date('Y-m-d', strtotime($date_start.' +1 month'));
				
				$fmsdatas_pg 	= Fmsdata::where('fms_id', '=', $id)
										->whereBetween($orderDateTaskId, array($date_start, $date_end))
										->orderBy($orderStepId, 'desc')
										->paginate(500)->toarray();
				$fmsdatas = $fmsdatas_pg['data'];
			}
		}
		
		return view('fms.print_fmsdata', compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from);
	}	
	/* END print fms data */
	
	public function searchdatafromfms(){
		//echo json_encode($_GET); exit;
		$searchTerm = $_GET['term']; 
		if(!empty($searchTerm)){
			$fms_data_table='';
			if($_GET['fms'] =='bulk'){
				$fms_data_table='pi_fms_bulk_data';
			}else if($_GET['fms'] =='sampling'){
				$fms_data_table='pi_fms_sample_data';
			}else if($_GET['fms'] =='samplecard'){
				$fms_data_table='samplecards_fms_data';
			}
			$mainData=array();
			$dataarr=array();
			$where = '';
			//invoice_no
			if($_GET['searchby'] =='invoice_no'){			
				$where = $_GET['searchby'];
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['invoice_no'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//customer_data
			else if($_GET['searchby'] =='customer_data'){
				$where = 'customer_data.c_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				foreach($data as $row){ 
					$value = $row['customer_data']['c_value'] ;
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//brand_data
			else if($_GET['searchby'] =='brand_data'){
				$where = 'brand_data.b_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				foreach($data as $row){ 
					$value = $row['brand_data']['b_value'] ;
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//item_data
			else if($_GET['searchby'] =='item_data'){			
				$where = 'item_data.i_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['item_data']['i_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//color
			else if($_GET['searchby'] =='color'){
				
				$where = 'color';				
				if(isset($_GET['items']) && $_GET['items']!=''){
					$item = str_replace("---","#",$_GET['items']);
					$items = explode(',', $item);
					//echo json_encode($items); exit;
					//pr($items); die('kk');
					$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->where($where,'like', '%'.$searchTerm.'%')
					->whereIn('item_data.i_value', $items)
					->get()
					->unique($where)
					->toArray();
				}else{
					$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				}
				
				
				
				foreach($data as $row){ 
					$value = $row['color'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//s_person_data
			else if($_GET['searchby'] =='s_person_data'){			
				$where = 's_person_data.s_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['s_person_data']['s_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//crm_data
			else if($_GET['searchby'] =='crm_data'){			
				$where = 'crm_data.c_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['crm_data']['c_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			
			//merchant_data
			else if($_GET['searchby'] =='merchant_data'){			
				$where = 'merchant_data.m_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['merchant_data']['m_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			
			//echo '<pre>';print_r($mainData);	die;
			echo json_encode($mainData);
			exit;
		}
		
	}
	
	public function searchdatafromfmspurchase(){
		//echo json_encode($_GET); exit;
		$searchTerm = $_GET['term']; 
		if(!empty($searchTerm)){
			$fms_data_table='';
			if($_GET['fms'] =='import'){
				$fms_data_table='po_fms_import_data';
			}elseif($_GET['fms'] =='local'){
				$fms_data_table='po_fms_local_data';
			}
			$mainData=array();
			$dataarr=array();
			$where = '';
			//invoice_no
			if($_GET['searchby'] =='invoice_no'){		
				$where = $_GET['searchby'];
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['invoice_no'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//supplier_data
			else if($_GET['searchby'] =='supplier_data'){			
				$where = 'supplier_data.s_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(10000)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				foreach($data as $row){ 
					$value = $row['supplier_data']['s_value'] ;
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//item_data
			else if($_GET['searchby'] =='item_data'){			
				$where = 'item_data.i_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['item_data']['i_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//color
			else if($_GET['searchby'] =='color'){
				
				$where = 'color';				
				if(isset($_GET['items']) && $_GET['items']!=''){
					$item = str_replace("---","#",$_GET['items']);
					$items = explode(',', $item);
					//echo json_encode($items); exit;
					//pr($items); die('kk');
					$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->where($where,'like', '%'.$searchTerm.'%')
					->whereIn('item_data.i_value', $items)
					->get()
					->unique($where)
					->toArray();
				}else{
					$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				}
				
				
				
				foreach($data as $row){ 
					$value = $row['color'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
				
			//echo '<pre>';print_r($mainData);	die;
			echo json_encode($mainData);
			exit;
		}
		
	}
	
	public function color_search_by_hiddent_item(){
		$searchTerm = $_GET['term']; 
		if(!empty($searchTerm)){
			$fms_data_table='';
			if($_GET['fms'] =='bulk'){
				$fms_data_table='pi_fms_bulk_data';
			}elseif($_GET['fms'] =='sampling'){
				$fms_data_table='pi_fms_sample_data';
			}
			$mainData=array();
			$dataarr=array();
			$where = '';
			//invoice_no
			if($_GET['searchby'] =='invoice_no'){			
				$where = $_GET['searchby'];
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['invoice_no'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//customer_data
			else if($_GET['searchby'] =='customer_data'){			
				$where = 'customer_data.c_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				foreach($data as $row){ 
					$value = $row['customer_data']['c_value'] ;
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//item_data
			else if($_GET['searchby'] =='item_data'){			
				$where = 'item_data.i_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['item_data']['i_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//color
			else if($_GET['searchby'] =='color'){			
				$where = 'color';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['color'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}
			//s_person_data
			else if($_GET['searchby'] =='s_person_data'){			
				$where = 's_person_data.s_value';
				
				$data = DB::table($fms_data_table)
					->select($_GET['searchby'])
					->limit(100)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				
				foreach($data as $row){ 
					$value = $row['s_person_data']['s_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
			}	
			//echo '<pre>';print_r($mainData);	die;
			echo json_encode($mainData);
			exit;
		}
		
	}
	
	public function testing(){
			die('--testing--');
				$mainData=array();
				$dataarr=array();
				$searchTerm = 'Ginza';
				$where = 'supplier_data.s_value';
				
				$data = DB::table('po_fms_local_data')
					->select('supplier_data')
					->limit(10000)
					->where($where,'like', '%'.$searchTerm.'%')
					->get()
					->unique($where)
					->toArray();
				foreach($data as $row){ 
					$value = $row['supplier_data']['s_value'];
					$dataarr['value'] = $value;		 
					array_push($mainData, $dataarr); 
				}
				
				
				echo '<pre>';print_r($mainData);	die;
	}
	
}
