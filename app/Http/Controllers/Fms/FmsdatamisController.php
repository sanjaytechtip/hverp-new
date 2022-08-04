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
class FmsdatamisController extends Controller
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
		$taskdatas = Fmstask::where('fms_id', '=', $fms_id)->orderBy('order_no', 'asc')->where('in_fms', '!=', '0')->get()->toarray();
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
		
		$stepsdatas = Fmsstep::where('fms_id', '=', $id)->where('mis', '=', '1')->orderBy('order_no', 'asc')->get()->toarray();
		//pr($stepsdatas); die;
	
		$fmsdatas=array();
		if($fms['fms_type']=='pi_fms_bulk'){
			
			/* ///////////========== Update PI and PI row data into FMS data collection start =========///////////// */			
			$up = 0; // set 1 if want to update
			if($up==1){
				$fms_table_data = DB::table($fms_table)->select('id','pi_id')->get()->toArray();
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
						
						$fms_data['buyer_data'] = $buyer_data;
						$fms_data['customer_data'] = $customer_data;
						$fms_data['s_person_data'] = $s_person_data;
						$fms_data['brand_data'] = $brand_data;
						
						//$fms_data['pi_status'] = $piMain['status'];
						
						$fms_data['item_data'] = array(
												'i_id'=>$piRows['item'],
												'i_value'=>trim(getItemName($piRows['item']))
											);
							
						$fms_data['crm_data'] = $crm_data;
						$fms_data['merchant_data'] = $merchant_data;
						
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
			
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$orWhere = array();
							$whereIn = array();
							$where = array(
									array(
									'fms_id', '=', $fms_id
									)
								);
							
							// step#2
							if(!empty($_GET['5e8ef2c80f0e752284006618'])){
								if($_GET['5e8ef2c80f0e752284006618']=='Blank'){
									$where[]=array(
									'5e8ef2c80f0e752284006618.actual','=',''
									);
								}
								else if($_GET['5e8ef2c80f0e752284006618']=='Filled'){
									$where[]=array(
									'5e8ef2c80f0e752284006618.actual','!=',''
									);
								}
								
							}
							// step#3
							if(!empty($_GET['5e8ef2c80f0e752284006619'])){
								if($_GET['5e8ef2c80f0e752284006619']=='Blank'){
									$where[]=array(
									'5e8ef2c80f0e752284006619.actual','=',''
									);
									
									$where[]=array(
										'5e8ef2c80f0e752284006618.dropdown','=','Rejected'
									);
								}
								else if($_GET['5e8ef2c80f0e752284006619']=='Filled'){
									$where[]=array(
									'5e8ef2c80f0e752284006619.actual','!=',''
									);
								}
							}
							
							//step#4
							if(!empty($_GET['5e8ef2c80f0e75228400661a'])){
								if($_GET['5e8ef2c80f0e75228400661a']=='Blank'){
									$where[]=array(
										'5e8ef2c80f0e75228400661a.dropdownlist','=',''
									);
								}else{
									$where[]=array(
									'5e8ef2c80f0e75228400661a.dropdownlist','=',$_GET['5e8ef2c80f0e75228400661a']
									);
								}
								
							}
							
							// step#5
							if(!empty($_GET['5e8ef2c80f0e75228400661b'])){
								if($_GET['5e8ef2c80f0e75228400661b']=='Blank'){
									$where[]=array(
									'5e8ef2c80f0e75228400661b.actual','=',''
									);
								}
								else if($_GET['5e8ef2c80f0e75228400661b']=='Filled'){
									$where[]=array(
									'5e8ef2c80f0e75228400661b.actual','!=',''
									);
								}
							}
							
							// step#6
							if(!empty($_GET['5e96e00c0f0e751ff8004712'])){
								if($_GET['5e96e00c0f0e751ff8004712']=='Blank'){
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
									$where[]=array(
									'5e96e00c0f0e751ff8004712.actual','!=',''
									);
								}
							}
							
							// step#7							
							if(!empty($_GET['5e96e00c0f0e751ff8004713'])){
								if($_GET['5e96e00c0f0e751ff8004713']=='Blank'){
									$where[]=array(
										'5e96e00c0f0e751ff8004713.notes','=',''
									);
									
								}else if($_GET['5e96e00c0f0e751ff8004713']=='Filled'){
									
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
									$where[]=array(
										'5e9801ff0f0e750980005e47.planed','!=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e47.actual','=',''
									);
								}
								else if($_GET['5e9801ff0f0e750980005e47']=='Filled'){
									$where[]=array(
									'5e9801ff0f0e750980005e47.actual','!=',''
									);
								}
							}
							
							// step#10
							if(!empty($_GET['5e9801ff0f0e750980005e48'])){
								if($_GET['5e9801ff0f0e750980005e48']=='Blank'){
									$where[]=array(
										'5e9801ff0f0e750980005e48.actual','=',''
									);
									
									$where[]=array(
										'lab_dip','=','Required'
									);
									
								}
								else if($_GET['5e9801ff0f0e750980005e48']=='Filled'){
									$where[]=array(
									'5e9801ff0f0e750980005e48.actual','!=',''
									);
								}
							}
							
							// step#11
							if(!empty($_GET['5e9801ff0f0e750980005e49'])){
								if($_GET['5e9801ff0f0e750980005e49']=='Blank'){
									$where[]=array(
										'5e9801ff0f0e750980005e49.actual','=',''
									);
									
									$where[]=array(
										'fob_sample','=','Buyer'
									);
								}
								else if($_GET['5e9801ff0f0e750980005e49']=='Filled'){
									$where[]=array(
									'5e9801ff0f0e750980005e49.actual','!=',''
									);
								}
							}
							
							// step#12
							if(!empty($_GET['5e9801ff0f0e750980005e4a'])){
								if($_GET['5e9801ff0f0e750980005e4a']=='Blank'){
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
									$where[]=array(
									'5e9801ff0f0e750980005e4a.actual','!=',''
									);
								}
							}
							
							// step#13
							if(!empty($_GET['5e9801ff0f0e750980005e4b'])){
								if($_GET['5e9801ff0f0e750980005e4b']=='Blank'){
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
									$where[]=array(
									'5e9801ff0f0e750980005e4b.actual','!=',''
									);
								}
							}
							
							// step#14
							if(!empty($_GET['5e9801ff0f0e750980005e4c'])){
								if($_GET['5e9801ff0f0e750980005e4c']=='Blank'){
									
									$where[]=array(
										'5e9801ff0f0e750980005e4c.actual','=',''
									);
									
								}
								else if($_GET['5e9801ff0f0e750980005e4c']=='Filled'){
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
								$where[]=array(
											'payment_term','like','%pdc%'
									);
									
								$whereIn['5e9801ff0f0e750980005e4d.dropdownlist']=$_GET['5e9801ff0f0e750980005e4d'];
							}
							
							
							// step#16
							if(!empty($_GET['5e9946af0f0e75131c0037f5'])){
								if($_GET['5e9946af0f0e75131c0037f5']=='Blank'){
									$where[]=array(
										'5e9946af0f0e75131c0037f5.act_qty','=',''
									);
									
								}
								else if($_GET['5e9946af0f0e75131c0037f5']=='Filled'){
									$where[]=array(
										'5e9946af0f0e75131c0037f5.act_qty','!=',''
									);
								}
							}
							
							// step#17
							if(!empty($_GET['5e9946b00f0e75131c0037f6'])){
								if($_GET['5e9946b00f0e75131c0037f6']=='Blank'){
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','=',''
									);
									
									$where[]=array(
									'5e9801ff0f0e750980005e4c.actual','!=',''
									);
								}
								else if($_GET['5e9946b00f0e75131c0037f6']=='Filled'){
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','!=',''
									);
								}
							}
							
							// step#18
							if(!empty($_GET['5e9946b00f0e75131c0037f7'])){
								if($_GET['5e9946b00f0e75131c0037f7']=='Blank'){
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','=',''
									);
									
									$where[]=array(
									'5e9946b00f0e75131c0037f6.actual','!=',''
									);
								}
								else if($_GET['5e9946b00f0e75131c0037f7']=='Filled'){
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','!=',''
									);
								}
							}
							
							// step#19
							if(!empty($_GET['5e9946b00f0e75131c0037f8'])){
								if($_GET['5e9946b00f0e75131c0037f8']=='Blank'){
									$where[]=array(
										'5e9946b00f0e75131c0037f8.none_date','=',''
									);
									
									$where[]=array(
										'5e9801ff0f0e750980005e4d.dropdownlist','=','COD'
									);
								}else if($_GET['5e9946b00f0e75131c0037f8']=='Filled'){
									$where[]=array(
										'5e9946b00f0e75131c0037f8.none_date','!=',''
									);
								}
								
							}
							
							// step#20							
							if(!empty($_GET['5e9a9ecb0f0e750494006a33'])){
								if($_GET['5e9a9ecb0f0e750494006a33']=='Blank'){
									$where[]=array(
										'5e9a9ecb0f0e750494006a33.inv_date','=',''
									);
									
								}else if($_GET['5e9a9ecb0f0e750494006a33']=='Filled'){
									$where[]=array(
										'5e9a9ecb0f0e750494006a33.inv_date','!=',''
									);
								}
								
							}
							
							// step#21
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a34'])){
								if($_GET['5e9a9ecb0f0e750494006a34']=='Blank'){
									$where[]=array(
									'5e9a9ecb0f0e750494006a34.actual','=',''
									);
									
									$where[]=array(
									'5e9946b00f0e75131c0037f7.actual','!=',''
									);
								}
								else if($_GET['5e9a9ecb0f0e750494006a34']=='Filled'){
									$where[]=array(
									'5e9a9ecb0f0e750494006a34.actual','!=',''
									);
								}
							}
							
							// step#22
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a35'])){
								if(in_array('Blank', $_GET['5e9a9ecb0f0e750494006a35'])){
									array_push($_GET['5e9a9ecb0f0e750494006a35'], "");
								}
								$whereIn['5e9a9ecb0f0e750494006a35.dropdownlist']=$_GET['5e9a9ecb0f0e750494006a35'];
							}
							
							// step#23
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a36'])){
								if($_GET['5e9a9ecb0f0e750494006a36']=='Blank'){
									$where[]=array(
									'5e9a9ecb0f0e750494006a36.actual','=',''
									);
									
									$where[]=array(
									'5e9a9ecb0f0e750494006a33.inv_date','!=',''
									);
								}
								else if($_GET['5e9a9ecb0f0e750494006a36']=='Filled'){
									$where[]=array(
									'5e9a9ecb0f0e750494006a36.actual','!=',''
									);
								}
							}
							
							// step#24 
							
							if(!empty($_GET['5e9a9ecb0f0e750494006a37'])){
								if($_GET['5e9a9ecb0f0e750494006a37']=='Blank'){
									
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
									$where[]=array(
									'5e9a9ecb0f0e750494006a37.actual','!=',''
									);
								}
							}
							
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
							
							$limit=50;
							if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
								$limit=500;
							}
								
							//echo '<pre>';print_r($where);	 5e9a9ecb0f0e750494006a35
							// if step#5 blank
							if(!empty($_GET['5e8ef2c80f0e75228400661b']) && $_GET['5e8ef2c80f0e75228400661b']=='Blank'){
								$fmsdatas_pg = DB::table($fms_table)
												->where($where)
												
												->where(function($q){
												   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
													 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
												})
												->paginate($limit)->toarray();
							
								$all_data 	 = DB::table($fms_table)
												->where($where)
												
												->where(function($q){
													   $q->where('5e8ef2c80f0e752284006618.dropdown', 'Approved')
														 ->orWhere('5e8ef2c80f0e752284006619.dropdown', 'Approved');
													})
												->paginate($limit);
							}elseif(!empty($_GET['5e9801ff0f0e750980005e4c']) && $_GET['5e9801ff0f0e750980005e4c']=='Blank'){
								/// if step#14 blank 
								//pr($where); pr($whereIn);  die;
								
								$fmsdatas_pg = DB::table($fms_table);
								$fmsdatas_pg =	$fmsdatas_pg->where($where);
												
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
								//echo "==";pr($whereIn); pr($where); die('kk');
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

			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.pi_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.pi_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
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
								
						$fms_data['crm_data'] = $crm_data;
						$fms_data['merchant_data'] = $merchant_data;
						
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
							
							$order_type = $piMain['order_type'];
							$fms_data =array();							
							// row data 
							$fms_data['etd']=$piRows['etd'];
							$roid = (array) $piRows['_id'];
							$row_id = $roid['oid'];
							$fms_data['item_row']=$row_id;
							
							/* new code start  */
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
							
							// row data 
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
							
							/* New code END */
							///pr($fms_data); die; 
							DB::table($fms_table)->where('_id', $data_id)->where('po_id', $pi_id)->update($fms_data);
							// update data END
					
					echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
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
			/* //////========== Update PO and PO row data into FMS data collection start =========///// */
				$up = 0; // set 1 if want to update
				if($up==1){
					$fms_table_data = DB::table($fms_table)->select('id','po_id')->get()->toArray();
					//$fms_table_data = DB::table($fms_table)->select('id','po_id')->where('po_id', '5f533e8bd04904275b71af22')->get()->toArray();
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
							// row data 
							$fms_data['etd']=$piRows['etd'];
							$roid = (array) $piRows['_id'];
							$row_id = $roid['oid'];
							$fms_data['item_row']=$row_id;
							
							/* new code start  */
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
							
							// row data 
							
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
							/* New code END */
							///pr($fms_data); die;
							DB::table($fms_table)->where('_id', $data_id)->where('po_id', $pi_id)->update($fms_data);
							// update data END
					
					echo $cn.'==data_id'.$data_id.'=====row_id'.$row_id.'<br/>';
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
			
			
			
		}
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
	
	public function misscore(Request $request)
	{
		//pr($request->all()); die('hiii'); 
		$post = $request->all();
		if(!empty($post)){
			$user_id = $post['user'];
			$user_kpi_fms = getAllKpiFMSByUseId($user_id);
		}else{
			$user_kpi_fms = array();
		}
		
		//return view('fms.mis', compact('fms_data', 'old_pending'))->with('post', $post);
		return view('fms.mis', compact('user_kpi_fms'))->with('post', $post);
	}
	
	public function misscore_old(Request $request)
	{
		//pr($request->all()); die('hiii'); 
		$post = $request->all();
		//pr($post); die;
		//$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		$fms_id = '';
		$user_id = '';
		$mis_period = '';
		$mis_from = '';
		$mis_to = '';
		$old_pending=array();
		if(!empty($post)){
			//$fms_id = $post['fms'];
			$user_id = $post['user'];
			$mis_period = $post['mis_period'];
			$mis_from = $post['mis_from'];
			$mis_to = $post['mis_to'];
			/* new code start */
			
			//pr($request->all()); die('hi999ii'); 
			//
			$fms_ids = getAllKpiFMSByUseId($user_id);

			$fms_data  = array();
			foreach($fms_ids as $fmsid){
				$fms_id =$fmsid['fms'];
				//echo $fms_id.'<br/>';
			
			$kpi_datas = getallKpiByFmsAndUseId($fms_id, $user_id);
			foreach($kpi_datas as $kpi_arr){
					//pr($kpi_arr); die;
					$o_id = (array) $kpi_arr['_id'];
					$kp_id =  $o_id['oid'];
					$step_arr =  $kpi_arr['step'];
					//pr($step_arr);  die;
					//$kp_id =  $n_id['oid'];
					//pr($kp_id);
					/* $kpi_data = getStepIdByKpiId($kp_id);
					if(!empty($kpi_data) && array_key_exists('step', $kpi_data)){
						$step_id = $kpi_data['step'][0];
						$step_data = getStepDataByStepId($step_id);
					} */
					
					$step_id = $step_arr[0];
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
					$fms = $fms[0];
					$fms_table= $fms['fms_table'];
					//echo $fms_table; die;
					if($mis_period=='till_date'){
						$start_day = date('Y-m-d');
					}
					
					$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day)));
					//pr($dateArrayPending);	die('1');
					/* for old pending */
					$pending_data 	= 	DB::table($fms_table)
										->select('invoice_no',$step_id)
										->where('fms_id',$fms_id)
										->where('merchant_data.m_id', '=',$user_id)
										->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
										->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
										->where($step_id.'.actual', '=','')
										->whereBetween($step_id.'.planed', $dateArrayPending)
										->get()->toArray();
					
					
					if(!empty($pending_data)){
						$old_pending[$kp_id]=count($pending_data);
					}
					
					//pr($pending_data);	die('1');	
					//pr($pending_data); echo $old_pending; die;
					/* exclude PI if step#5 and step#22 Cancelled */
					$fms_data[$kp_id] 	= 	DB::table($fms_table)
											->select('fms_id','invoice_no',$step_id)
											->where('fms_id',$fms_id)
											->where('merchant_data.m_id', '=',$user_id)
											->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
											->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
											->whereBetween($step_id.'.planed', $dateArray)
											->get()->toArray();
					
					
			}   // end KPI data
			} // end fms_id loop
			//$total_task = count($fms_data);
			/* pr($old_pending);	
			pr($fms_data);	die; */
			//die('hiii22');
			/* new code end */			
			
			//pr($kpi_data); die('==out-side==');
					//pr($fms_data);	die;
				//return view('fms.mis', compact('fmslists', 'fms_data', 'old_pending'))->with('post', $post);
				return view('fms.mis', compact('fms_data', 'old_pending'))->with('post', $post);
		}else{
				$fms_data 	= 	array();
				//return view('fms.mis', compact('fmslists', 'fms_data', 'old_pending'))->with('post', $post);
				return view('fms.mis', compact('fms_data', 'old_pending'))->with('post', $post);
		}
		
	}
	
	public function miskpi()
    {
		$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		//pr($mis_sum_bulk); echo $mis_sum_bulk; die;
       // return view('fms.miskpi', compact('fmslists'))->with('no', 1)->with('mis_sum_bulk', $mis_sum_bulk)->with('crntUid', $uid);
        return view('fms.miskpi', compact('fmslists'));
    }
	
	
	public function get_users_fms_access($fms_id)
    {
		$userlist = $userlist = DB::table('users')->select('_id','name')->whereIn('access_fms_id', [$fms_id])->whereNotIn('user_role', ['1','2'])->get()->toArray();
		
		$userHtml='<option value="">--Select--</option>';
		foreach($userlist as $user){
			$userHtml.='<option value="'.$user['_id'].'">'.$user['name'].'</option>';
		}
		echo $userHtml; die();
    }

	public function get_users_fms_steps($fms_id, $user_id)
    {
		$allsteps = DB::table('fmssteps')->select('_id','step','user_right')->where('fms_id', $fms_id)->get()->toArray();
		$userStep = array();
		
		/* cmsuser = CRM, Merchant and Sales_person user */
		/* $cmsuser = isCMSUser($user_id);
		if($cmsuser==1){
						foreach($allsteps as $step){
							$step_id = (array) $step['_id'];
							$sid = $step_id['oid'];
							$userStep[$sid]=$step['step'];
						}
		}else{
				foreach($allsteps as $step){
					if(array_key_exists('user_right', $step) && !empty($step['user_right'])){
							$user_right = $step['user_right'];
							foreach($user_right as $ukey=>$uVal){
								if($ukey==$user_id){
									if(in_array('2', $uVal) || in_array('3', $uVal)){
										$step_id = (array) $step['_id'];
										$sid = $step_id['oid'];
										$userStep[$sid]=$step['step'];
									}
								}
							}
					}
				}
		} */
		
		foreach($allsteps as $step){
			if(array_key_exists('user_right', $step) && !empty($step['user_right'])){
					$user_right = $step['user_right'];
					foreach($user_right as $ukey=>$uVal){
						if($ukey==$user_id){
							if(in_array('2', $uVal) || in_array('3', $uVal)){
								$step_id = (array) $step['_id'];
								$sid = $step_id['oid'];
								$userStep[$sid]=$step['step'];
							}
						}
					}
			}
		}
		
		
		//pr($userStep);
		$stepHtml='';
		foreach($userStep as $skey=>$sval){
			$stepHtml.='<input type="checkbox" name="step[]" value="'.$skey.'"> Step#'.$sval.' <br/>';
		}
		echo $stepHtml; die();
    }
	
	public function kpistore(Request $request)
    {
		$post = $request->all();
		$kpi_name = $post['kpi_name'];
		$fms = $post['fms'];
		$data = array(
						'kpi_name'=>$post['kpi_name'],
						'fms'=>$post['fms'],
						'user'=>$post['user'],
						'step'=>$post['step'],
						'created_at'=>date('Y-m-d')
					);
		$id = DB::table('fms_kpi')->insert($data);
		if($id>0){
			return redirect()->route('kpilist')->with('success', 'KPI added successfully.');
		}else{
			return redirect()->route('kpilist')->with('error', 'KPI not added.');
		}
    }
	
	public function kpilist()
    {
		$allkpi = DB::table('fms_kpi')->get()->toArray();
		return view('fms.miskpi_list', compact('allkpi'));
    }
	
	public function editkpi($kpi_id)
    {
		$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		$kpidata = DB::table('fms_kpi')->where('_id', $kpi_id)->get()->toArray();
		//pr($allkpi); die;
		return view('fms.editkpi', compact('kpidata','fmslists'));
    }
	
	public function viewkpi($kpi_id)
    {
		$fmslists = DB::table('fmss')->orderBy('order_no')->get()->toArray();
		$kpidata = DB::table('fms_kpi')->where('_id', $kpi_id)->get()->toArray();
		//pr($allkpi); die;
		return view('fms.viewkpi', compact('kpidata','fmslists'));
    }
	
	public function kpiupdate(Request $request, $kpi_id)
    {
		$post = $request->all();
		$kpi_name = $post['kpi_name'];
		$fms = $post['fms'];
		$data = array(
						'kpi_name'=>$post['kpi_name'],
						'fms'=>$post['fms'],
						'user'=>$post['user'],
						'step'=>$post['step'],
						'updated_at'=>date('Y-m-d')
					);
			//pr($data); die;
		$id = DB::table('fms_kpi')->where('_id', $kpi_id)->update($data);
		if($id>0){
			return redirect()->route('kpilist')->with('success', 'KPI Updated successfully.');
		}else{
			return redirect()->route('kpilist')->with('error', 'KPI not Updated.');
		}
    }
	
	
	public function get_kpi_list($fms_id, $user_id)
    {
		$allkpis = DB::table('fms_kpi')->where('fms', $fms_id)->where('user', $user_id)->get()->toArray();
		//$kpiArray = array();
		$kpi_html='';
		foreach($allkpis as $kpi){
				$kpi_id = (array) $kpi['_id'];
				$kpi_id = $kpi_id['oid'];
				//$kpiArray[$kpi_id]=$kpi['kpi_name'];
				$kpi_html.='<option value="'.$kpi_id.'">'.$kpi['kpi_name'].'</option>';
		}
		echo $kpi_html;
		die;
    }
	
	
	public function misdetails(Request $request, $id, $from='', $orderNum='')
    {
		//pr($_GET); die;
		$search_by ='';
		$search_text = '';
		$item_drop = '';
		
		$all_order_qty='';
		$post = $request->all();
		
		$fms_id = $id;
		$taskdatas = Fmstask::where('fms_id', '=', $fms_id)->orderBy('order_no', 'asc')->where('in_fms', '!=', '0')->get()->toarray();
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
		
		$stepsdatas = Fmsstep::where('fms_id', '=', $id)->where('mis', '=', '1')->orderBy('order_no', 'asc')->get()->toarray();
		//pr($stepsdatas); die;
		
				/// new code start
				$user_id = $_GET['user_id'];								
				$step_id = $_GET['step_id'];
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
				
				/// new code end
	
		$fmsdatas=array();
		if($fms['fms_type']=='pi_fms_bulk'){
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$orWhere = array();
							$whereIn = array();
							$where = array(
											array(
													'fms_id', '=', $fms_id
												)
										);
							
							$limit=50;
							if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
								$limit=500;
							}
							
							// step#23
							$fup_from = '';
							$fup_to = '';
							
							// Pi Date
							$pi_from ='';
							$pi_to ='';
							/* MIS for step#23  */
							// merchant_data							
							if(!empty($_GET['merchant_data'])){
								$where[]=array(
										'merchant_data.m_value','=',trim($_GET['merchant_data'])
									);
							}
							
							
							if(array_key_exists('s_type', $_GET)){
								
								/* check search type */
								$mis_period = $_GET['s_type'];
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
									$mis_from = date('Y-m-d', strtotime( $_GET['mis_from']));
									$mis_to = date('Y-m-d', strtotime( $_GET['mis_to']));
									$start_day = date("Y-m-d", strtotime($mis_from));
									$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('2222');
								}elseif($mis_period=='till_date'){
									
									$start_day = date("2020-05-30");
									$toDay = date('Y-m-d');
									$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
									$dateArray = array($start_day, $end_day);
									
								}
								
								if($mis_period=='till_date'){
									$start_day = date('Y-m-d');
								}
								
								$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day)));
					
								//pr($dateArrayPending); die;
								//echo $step_id; die('step 16'); 
								/* here this we do for step#16 and checking step#17 */
								$qty_variation='';
								if(array_key_exists('qty_variation', $_GET)  && $_GET['qty_variation']==1){
									$qty_variation=1;
								}
								
								if(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1 && $normal_user==1){
									$fmsdatas_pg =	DB::table($fms_table)
														->where('fms_id',$fms_id)
														->where($step_id.'.actual', '=','')
														->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
														->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
														->whereBetween($step_id.'.planed', $dateArrayPending)
														->paginate($limit)->toArray();
															
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($step_id.'.actual', '=','')
													->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
													->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
									
									
								}elseif(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1){
									$fmsdatas_pg 	= DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereUserQuery, '=',$user_id)
													->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
													->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit)->toArray();
											
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereUserQuery, '=',$user_id)
													->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
													->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
									
								}elseif($step_id=="5e9946b00f0e75131c0037f6" && $qty_variation==1){
									//echo $user_id.'==';
									//echo $step_id; die('step 16');									
									$fmsdatas_pg 	= 		DB::table($fms_table)
															->where('fms_id',$fms_id)
															->where(function($q) use ($user_id){
																   $q->where('5e9946af0f0e75131c0037f5.user_id',"=",$user_id)
																	->orWhere('5e9946b00f0e75131c0037f6.user_id',"=",$user_id);
																})
															->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
															->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
															->whereBetween('5e9946b00f0e75131c0037f6.planed', $dateArray)
															->paginate($limit)->toArray();
															
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where(function($q) use ($user_id){
																   $q->where('5e9946af0f0e75131c0037f5.user_id',"=",$user_id)
																	->orWhere('5e9946b00f0e75131c0037f6.user_id',"=",$user_id);
																})
															->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
															->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
															->whereBetween('5e9946b00f0e75131c0037f6.planed', $dateArray)
													->paginate($limit);
															
								}elseif($normal_user==1){
									
									$fmsdatas_pg =	DB::table($fms_table)
														->where('fms_id',$fms_id)
														->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
														->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
														->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
														->whereBetween($step_id.'.planed', $dateArray)
														->paginate($limit)->toArray();
															
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where(function($q) use ($step_id,$user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
															->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
															->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled') 
															->whereBetween($step_id.'.planed', $dateArray)
													->paginate($limit);
													
								}else{
									//echo $whereUserQuery; pr($dateArray); die;
									$fmsdatas_pg 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereUserQuery, '=',$user_id)
													->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
													->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
													->whereBetween($step_id.'.planed', $dateArray)
													->paginate($limit)->toArray();
											
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereUserQuery, '=',$user_id)
													->where('5e8ef2c80f0e75228400661b.dropdown', '!=','Cancelled')
													->where('5e9a9ecb0f0e750494006a35.dropdownlist', '!=','Cancelled')
													->whereBetween($step_id.'.planed', $dateArray)
													->paginate($limit);
								}
							}
							
							/* ****** End Task DATA ***** */
											
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
			if(isset($_GET) && !empty($_GET) &&  array_key_exists('export', $_GET) && $_GET['export']=='excel'){
				return view('fms.pi_fmsdata_excel',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}else{
				return view('fms.pi_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
			}
		}elseif($fms['fms_type']=='pi_fms_sample'){
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$orWhere = array();
							$whereIn = array();
							$where = array(
											array(
													'fms_id', '=', $fms_id
												)
										);
							
							$limit=50;
							if(isset($_GET) && !empty($_GET) && array_key_exists('export', $_GET)  && $_GET['export']=='excel'){
								$limit=500;
							}
							
							// step#23
							$fup_from ='';
							$fup_to ='';
							
							// Pi Date
							$pi_from ='';
							$pi_to ='';
							/* MIS for step#23  */						
							
							if(array_key_exists('s_type', $_GET)){
								
								/* check search type */
								$mis_period = $_GET['s_type'];
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
									$mis_from = date('Y-m-d', strtotime( $_GET['mis_from']));
									$mis_to = date('Y-m-d', strtotime( $_GET['mis_to']));
									$start_day = date("Y-m-d", strtotime($mis_from));
									$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('2222');
								}elseif($mis_period=='till_date'){
									
									$start_day = date("2020-05-30");
									$toDay = date('Y-m-d');
									$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('333333');
								}
															
								$user_id = $_GET['user_id'];								
								$step_id = $_GET['step_id'];								
									
								//pr($_GET);die('=4444=='); 
								//$task_not_done='';
								$task_done_late='';
								$delay_in_doing_task='';
								
								if($mis_period=='till_date'){
									$start_day = date('Y-m-d');
								}
								
								$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day)));
								
								$whereArr = array();
								if($step_id=='5eb25b870f0e750c5c0020d3'){
									$whereArr[]=array(
												'5eb25b870f0e750c5c0020d2.dropdown','=','No'
											);
								}
								
								if(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1 && $normal_user==1){
									$fmsdatas_pg =	DB::table($fms_table)
														->where('fms_id',$fms_id)
														->where($whereArr)
														->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
														->where($step_id.'.actual', '=','')
														->whereBetween($step_id.'.planed', $dateArrayPending)
														->paginate($limit)->toArray();
														
														
										
															
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereArr)
													->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
									
									
								}elseif(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1){
									$fmsdatas_pg 	= DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereArr)
													->where($whereUserQuery, '=',$user_id)
													->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit)->toArray();
											
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where($whereArr)
													->where($whereUserQuery, '=',$user_id)
													->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
									
								}elseif($normal_user==1){
													$fmsdatas_pg 	= 	DB::table($fms_table)
																->where('fms_id',$fms_id)
																->where($whereArr)
																->where(function($q) use ($step_id, $user_id){
																			   $q->where($step_id.'.user_id',"=",$user_id)
																				->orWhere($step_id.'.actual',"=","");
																			})
																->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
																->whereBetween($step_id.'.planed', $dateArray)
																->paginate($limit)->toArray();
														
													$all_data 	= 	DB::table($fms_table)
															->where('fms_id',$fms_id)
															->where($whereArr)
															->where(function($q) use ($step_id, $user_id){
																			   $q->where($step_id.'.user_id',"=",$user_id)
																				->orWhere($step_id.'.actual',"=","");
																			})
															->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
															->whereBetween($step_id.'.planed', $dateArray)
															->paginate($limit);
								}else{
									$fmsdatas_pg 	= 	DB::table($fms_table)
												->where('fms_id',$fms_id)
												->where($whereUserQuery, '=',$user_id)
												->where($whereArr)
												->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
												->whereBetween($step_id.'.planed', $dateArray)
												->paginate($limit)->toArray();
										
									$all_data 	= 	DB::table($fms_table)
											->where('fms_id',$fms_id)
											->where($whereUserQuery, '=',$user_id)
											->where($whereArr)
											->where('5eb2ac560f0e750c5c0020eb.dropdownlist', '!=','Cancelled')
											->whereBetween($step_id.'.planed', $dateArray)
											->paginate($limit);
								}
								
								
							
							}
							
							/* ****** End Task DATA ***** */
											
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
					//pr($stepsdatas); die;
					return view('fms.pi_sampling_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
		}elseif($fms['fms_type']=='po_fms_import'){
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$limit=50;
							// step#23
							$fup_from ='';
							$fup_to ='';
							// Pi Date
							$pi_from ='';
							$pi_to ='';
							/* MIS for step#23  */						

							if(array_key_exists('s_type', $_GET)){
								/* check search type */
								$mis_period = $_GET['s_type'];
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
									$mis_from = date('Y-m-d', strtotime( $_GET['mis_from']));
									$mis_to = date('Y-m-d', strtotime( $_GET['mis_to']));
									$start_day = date("Y-m-d", strtotime($mis_from));
									$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('2222');
								}elseif($mis_period=='till_date'){
									
									$start_day = date("2020-05-30");
									$toDay = date('Y-m-d');
									$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('333333');
								}
								
								if($mis_period=='till_date'){
									$start_day = date('Y-m-d');
								}
								
								$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day))); 
								
								$user_id = $_GET['user_id'];								
								$step_id = $_GET['step_id'];								
									
								//pr($_GET);die('=4444=='); 
								//$task_not_done='';
								$task_done_late='';
								$delay_in_doing_task='';
								
								if(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1){
									$fmsdatas_pg 	= DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit)->toArray();
											
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
									
								}else{
									$fmsdatas_pg 	= 	DB::table($fms_table)
												->where('fms_id',$fms_id)
												->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
												->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
												->whereBetween($step_id.'.planed', $dateArray)
												->paginate($limit)->toArray();
									
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where(function($q) use ($step_id, $user_id){
																		   $q->where($step_id.'.user_id',"=",$user_id)
																			->orWhere($step_id.'.actual',"=","");
																		})
															->where('5ee7671f0f0e750a80007414.dropdownlist', '!=','Cancelled')
													->whereBetween($step_id.'.planed', $dateArray)
													->paginate($limit);
								}
								
								
								
							//pr($fmsdatas_pg); die;
							}
							
							/* ****** End Task DATA ***** */
											
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
					//pr($stepsdatas); die;
					return view('fms.po_import_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
		}elseif($fms['fms_type']=='po_fms_local'){
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$limit=50;
							// step#23
							$fup_from ='';
							$fup_to ='';
							// Pi Date
							$pi_from ='';
							$pi_to ='';
							/* MIS for step#23  */						

							if(array_key_exists('s_type', $_GET)){
								/* check search type */
								$mis_period = $_GET['s_type'];
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
									$mis_from = date('Y-m-d', strtotime( $_GET['mis_from']));
									$mis_to = date('Y-m-d', strtotime( $_GET['mis_to']));
									$start_day = date("Y-m-d", strtotime($mis_from));
									$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('2222');
								}elseif($mis_period=='till_date'){
									
									$start_day = date("2020-05-30");
									$toDay = date('Y-m-d');
									$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('333333');
								}
															
								$user_id = $_GET['user_id'];								
								$step_id = $_GET['step_id'];								
									
								//pr($_GET);die('=4444=='); 
								//$task_not_done='';
								$task_done_late='';
								$delay_in_doing_task='';
								if($mis_period=='till_date'){
									$start_day = date('Y-m-d');
								}
								$dateArrayPending = array('2020-05-30', date("Y-m-d",strtotime('+1 day'.$start_day))); 
								
								if(array_key_exists('old_pending', $_GET)  && $_GET['old_pending']==1){
									//pr($dateArrayPending); die;
									$fmsdatas_pg 	= 	DB::table($fms_table)
												->where('fms_id',$fms_id)
												->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
												->where($step_id.'.actual', '=','')
												->whereBetween($step_id.'.planed', $dateArrayPending)
												->paginate($limit)->toArray();
									
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
													->where($step_id.'.actual', '=','')
													->whereBetween($step_id.'.planed', $dateArrayPending)
													->paginate($limit);
								}else{
									$fmsdatas_pg 	= 	DB::table($fms_table)
												->where('fms_id',$fms_id)
												->where(function($q) use ($step_id, $user_id){
															   $q->where($step_id.'.user_id',"=",$user_id)
																->orWhere($step_id.'.actual',"=","");
															})
												->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
												->whereBetween($step_id.'.planed', $dateArray)
												->paginate($limit)->toArray();
									
									$all_data 	= 	DB::table($fms_table)
													->where('fms_id',$fms_id)
													->where(function($q) use ($step_id, $user_id){
																		   $q->where($step_id.'.user_id',"=",$user_id)
																			->orWhere($step_id.'.actual',"=","");
																		})
															->where('5f06e4142e977d1dd0e4c709.dropdownlist', '!=','Cancelled')
													->whereBetween($step_id.'.planed', $dateArray)
													->paginate($limit);
								}
								
								
								
							//pr($fmsdatas_pg); die;
							}
							
							/* ****** End Task DATA ***** */
											
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
					//pr($stepsdatas); die;
					return view('fms.po_local_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
		}elseif($fms['fms_type']=='sample_cards'){
				if(isset($_GET) && !empty($_GET) && array_key_exists('search', $_GET)){
							//pr($_GET);die('===');
							$limit=50;
							
							// step#23
							$fup_from ='';
							$fup_to ='';
							
							// Pi Date
							$pi_from ='';
							$pi_to ='';
							/* MIS for step#23  */						
							
							if(array_key_exists('s_type', $_GET)){
								
								/* check search type */
								$mis_period = $_GET['s_type'];
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
									$mis_from = date('Y-m-d', strtotime( $_GET['mis_from']));
									$mis_to = date('Y-m-d', strtotime( $_GET['mis_to']));
									$start_day = date("Y-m-d", strtotime($mis_from));
									$end_day = date("Y-m-d",strtotime('+1 day'.$mis_to));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('2222');
								}elseif($mis_period=='till_date'){
									
									$start_day = date("2020-05-30");
									$toDay = date('Y-m-d');
									$end_day = date("Y-m-d",strtotime('+1 day'.$toDay));				
									$dateArray = array($start_day, $end_day);
									//pr($dateArray);	die('333333');
								}
															
								$user_id = $_GET['user_id'];								
								$step_id = $_GET['step_id'];								
									
								//pr($_GET);die('=4444=='); 
								//$task_not_done='';
								$task_done_late='';
								$delay_in_doing_task='';
								
								//echo $whereUserQuery; die;
								if($normal_user==1){
													$fmsdatas_pg 	= 	DB::table($fms_table)
																->where('fms_id',$fms_id)
																->where(function($q) use ($step_id, $user_id){
																			   $q->where($step_id.'.user_id',"=",$user_id)
																				->orWhere($step_id.'.actual',"=","");
																			})
																->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
																->whereBetween($step_id.'.planed', $dateArray)
																->paginate($limit)->toArray();
														
													$all_data 	= 	DB::table($fms_table)
															->where('fms_id',$fms_id)
															->where(function($q) use ($step_id, $user_id){
																			   $q->where($step_id.'.user_id',"=",$user_id)
																				->orWhere($step_id.'.actual',"=","");
																			})
															->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
															->whereBetween($step_id.'.planed', $dateArray)
															->paginate($limit);
								}else{
									$fmsdatas_pg 	= 	DB::table($fms_table)
												->where('fms_id',$fms_id)
												->where($whereUserQuery, '=',$user_id)
												->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
												->whereBetween($step_id.'.planed', $dateArray)
												->paginate($limit)->toArray();
										
									$all_data 	= 	DB::table($fms_table)
											->where('fms_id',$fms_id)
											->where($whereUserQuery, '=',$user_id)
											->where('5f36806e0f0e751744007a49.dropdownlist', '!=','Cancelled')
											->whereBetween($step_id.'.planed', $dateArray)
											->paginate($limit);
								}
								
								
							
							}
							
							/* ****** End Task DATA ***** */
											
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
					//pr($stepsdatas); die;
					return view('fms.samplecard_fmsdata_mis',['all_data' => $all_data], compact('taskdatas', 'fms','stepsdatas', 'fmsdatas', 'fmsdatas_pg'))->with('no', 1)->with('order_range', $from)->with('orderNum',$orderNum)->with('item_id', $item_id)->with('search_by', $search_by)->with('search_text', $search_text)->with('item_drop', $item_drop);
		}
    }
	
	
	public function ajax_update_kpi_target(Request $request){
		$post = $request->all();
		
		/* first check if kpi within data exist */
		$start_day = '';				
		$end_day = '';
		$kpi_id='';
		if($post['mis_period']=='last_week'){
			
			//$previous_week = strtotime("+1 day");
			$current_week = strtotime("+1 day");
			$start_week = strtotime("last monday midnight",$current_week);
			$end_week = strtotime("next sunday",$start_week);
			
			$start_day = date("Y-m-d", $start_week);				
			$end_day = date("Y-m-d", $end_week);
			
			/* increase 1 day due to in query b/w date */				
			//$dateArray = array($start_day, $end_day);				
			//pr($dateArray);	die('1');				
		}
		
		$data = array(
						'fms_id'=>$post['fms_id'],
						'user_id'=>$post['user_id'],
						'mis_period'=>$post['mis_period'],
						'mis_from'=>$start_day,
						'mis_to'=>$end_day,
						'kpi_id'=>$post['kpi_id'],
						'task_not_done'=>$post['task_not_done'],
						'task_done_late'=>$post['task_done_late'],
						'delay_in_doing_task'=>$post['delay_in_doing_task'],
						'created_at'=>date('Y-m-d')
					);
		
		$kpi_data = DB::table('kpi_target')
					->where('kpi_id', '=',$post['kpi_id'])
					->where('mis_from', '=',$start_day)
					->where('mis_to', '=',$end_day)
					->where('user_id', '=',$post['user_id'])
					->first();
		
		if(!empty($kpi_data)){
			unset($data['created_at']);
			$data['updated_at'] = date('Y-m-d');
			$id =   DB::table('kpi_target')
					->where('kpi_id', '=',$post['kpi_id'])
					->where('mis_from', '=',$start_day)
					->where('mis_to', '=',$end_day)
					->where('user_id', '=',$post['user_id'])
					->update($data);
			if($id>0){
				echo "2";
			}else{
				echo "3";
			}
		}else{
			$id = DB::table('kpi_target')->insert($data);
		
			if($id>0){
				echo "1";
			}else{
				echo "0";
			}
		}
		 //pr($post); die;
		 exit();
	}
	

}
