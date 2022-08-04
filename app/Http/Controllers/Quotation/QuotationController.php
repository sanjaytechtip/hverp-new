<?php

namespace App\Http\Controllers\Quotation;
use App\Models\Form;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use DB;
use Session;
use Route;
use Auth;
use PDF;
error_reporting(0);
class QuotationController extends Controller
{
    
    public function index(Request $request) {
        if(!$_POST){
            $quotationlist = DB::table('tbl_quotation')->orderBy('id','DESC')->paginate(30);
            }else{
                $query = DB::table('tbl_quotation');
                if($request->quotation_no!=''){
                $query->where('quotation_no', 'LIKE','%'.$request->quotation_no.'%');
                }
                if($request->quotation_priority!=''){
                $query->where('quotation_priority', 'LIKE','%'.$request->quotation_priority.'%');
                }
                if($request->quotation_date!=''){
                $query->where('quotation_date', 'LIKE','%'.date('Y-m-d',strtotime($request->quotation_date)).'%');
                }
                if($request->quotation_ref_no!=''){
                $query->where('quotation_ref_no', 'LIKE','%'.$request->quotation_ref_no.'%');
                }
                if($request->created_by!=''){
                $query->where('created_by',$request->created_by);
                }
                if($request->approved_by!=''){
                $query->where('approved_by',$request->approved_by);
                }
                if($request->customer_name!=''){
                $query->where('customer_name',$request->customer_name);  
                }
                if($request->quotation_status!=''){
                $query->where('quotation_status',$request->quotation_status);  
                }
                if($request->city!=''){
                $object = DB::table('customers')->select('_id')->where('h_city', 'LIKE','%'.$request->city.'%')->get()->toArray();
                $da = json_decode(json_encode($object), true);
                
                $arr = array();
                foreach($da as $da_d){
                array_push($arr,$da_d['id']);
                }
                $query->whereIn('customer_name',$arr);
                }
                $quotationlist =$query->orderBy('id', 'DESC')->paginate(30);
            }
             //pr($quotationlist);die; 
            return view('general.quotation.quotationlist',['quotationlist'=>$quotationlist,'request'=>$request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $object_itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist = json_decode(json_encode($object_itemlist), true);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        $SrNo_object = DB::table('tbl_config_item')->first();
        $SrNo = json_decode(json_encode($SrNo_object), true);
        //pr($itemlist['data']); die;
        if(empty($_POST)){
		return view('general.quotation.quotationcreate',['datalist'=>$itemlist,'other_charges'=>$other_charges,'SrNo'=>$SrNo['row'],'itemlist_count'=>$itemlist_count]);
        }else{
            //pr($request->all()); //die;
            
            if($request->quotation_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            
            $arr = array(
                            'quotation_type'                =>  $request->quotation_type,
                            'quotation_srno'                =>  $request->quotation_srno,
                            'quotation_no'                  =>  $request->quotation_no,
                            'quotation_priority'            =>  $request->quotation_priority,
                            'customer_name'                 =>  $request->customer_name,
                            'quotation_date'                =>  date('Y-m-d',strtotime($request->quotation_date)),
                            'quotation_due_date'            =>  date('Y-m-d',strtotime($request->quotation_due_date)),
                            'quotation_ref_date'            =>  date('Y-m-d',strtotime($request->quotation_ref_date)),
                            'quotation_remarks'             =>  $request->quotation_remarks,
                            'quotation_ref_no'              =>  $request->quotation_ref_no,
                            'quotation_ref_no_search'       =>  SearchDataMatch($request->quotation_ref_no),
                            'quotation_status'              =>  $request->quotation_status,
                            'quotation_approved'            =>  $request->quotation_approved,
                            'quotation_contact_name'        =>  $request->quotation_contact_name,
                            'quotation_department_name'     =>  $request->quotation_department_name,
                            'quotation_contact_phone'       =>  $request->quotation_contact_phone,
                            'quotation_contact_email'       =>  $request->quotation_contact_email,
                            'quotation_subtotal'            =>  $request->quotation_subtotal,
                            'quotation_saletax'             =>  $request->quotation_saletax,
                            'quotation_tax_amount'          =>  $request->quotation_tax_amount,
                            'quotation_grand_total'         =>  $request->quotation_grand_total,
                            'created_by'                    =>  Auth::user()->id,
                            'approved_by'                   =>  $approved_by
                            
                        );
            
            $last_id = DB::table('tbl_quotation')->insertGetId($arr);

            if(!empty($request->other_charges_val)){
                foreach($request->other_charges_val as $key=>$rows_charges)
                {
                    $data_charges = array(
                        'quotation_id'          => $last_id,
                        'other_charges_name'    => $request->other_charges_name[$key],
                        'other_charges_val'     => $rows_charges
                    );
                    DB::table('tbl_quotation_shipping_charge')->insertGetId($data_charges);
                }
            }

            
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'           =>$rows['cust_ref_no'],
                    'item_name'             =>$rows['item_name'],
                    'item_id'               =>$rows['item_id'],
                    'comment'               =>$rows['comment'],
                    'description'           =>$rows['description'],
                    'vendor_sku'            =>$rows['vendor_sku'],
                    'sap_code'              =>$rows['sap_code'],
                    'hsn_code'              =>$rows['hsn_code'],
                    'tax_rate'              =>$rows['tax_rate'],
                    'grade'                 =>$rows['grade'],
                    'brand'                 =>$rows['brand'],
                    'packing_name'          =>$rows['packing_name'],
                    'list_price'            =>$rows['list_price'],
                    'rate'                  =>$rows['rate'],
                    'stock'                 =>$rows['stock'],
                    'mrp'                   =>$rows['mrp'],
                    'discount_per'          =>$rows['discount_per'],
                    'discount'              =>$rows['discount'],
                    'quantity'              =>$rows['quantity'],
                    'net_rate'              =>$rows['net_rate'],
                    'tax_amount'            =>$rows['tax_amount'],
                    'amount'                =>$rows['amount'],
                    'customer_id'           =>$request->customer_name,
                    'quotation_id'          =>$last_id,
                    'sale_order_item_satus' => 0
                    );
            DB::table('tbl_quotation_item')->insertGetId($data);
            }			
            }
            DB::table('tbl_config_item')->update(array('row'=>$request->quotation_srno+1));
			
            return redirect()->route('quotation_list')->with('success', 'Quotation saved successfully.');
            
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
    public function edit(Request $request, $id)
    {
		
		$object_quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
		$object_quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        $object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        $object_other_charges_data = DB::table('tbl_quotation_shipping_charge')->where('quotation_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
        //pr($other_charges_data); die;
		//pr($quotation_item_details); die;
		if(empty($_POST)){
		return view('general.quotation.quotationedit',['datalist'=>$itemlist,'quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'itemlist_count'=>$itemlist_count,'other_charges_data'=>$other_charges_data]);
		}else{
		//pr($request->all()); die;   
        $object_quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
        $quotation_details['revised_id'] = $id;
        $quotation_details['revised_date'] = date('Y-m-d H:i:s');
        unset($quotation_details['id']);
        unset($quotation_details['SO_No']);
        unset($quotation_details['SO_Date']);
        $last_id = DB::table('tbl_quotation_revised')->insertGetId($quotation_details);


        $object_quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
        foreach($quotation_item_details as $details_item){
        unset($details_item['id']);
        unset($details_item['quotation_id']);
        $details_item['revised_id'] = $id;
        $details_item['quotation_id'] = $last_id;
        $details_item['revised_date'] = date('Y-m-d H:i:s');
        unset($details_item['sale_order_item_id']);
        DB::table('tbl_quotation_item_revised')->insert(array($details_item));
        }
        

        $object_other_charges = DB::table('tbl_quotation_shipping_charge')->where('quotation_id',$id)->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        if(!empty($other_charges))
        {
        foreach($other_charges as $charges){
        unset($charges['id']);
        $charges['revised_id'] = $id;
        $charges['quotation_id'] = $last_id;
        $charges['revised_date'] = date('Y-m-d H:i:s');
        DB::table('tbl_quotation_shipping_charge_revised')->insert(array($charges));
        }   
        }
        
       
       
        //pr($quotation_details); die;
            if($request->quotation_status=='Approved'){
                $approved_by = Auth::user()->id;
            }else{
                $approved_by = '';
            }
            $arr = array(
                            'quotation_type'                =>  $request->quotation_type,
                            'quotation_srno'                =>  $request->quotation_srno,
                            'quotation_no'                  =>  $request->quotation_no,
                            'quotation_priority'            =>  $request->quotation_priority,
                            'customer_name'                 =>  $request->customer_name,
                            'quotation_date'                =>  date('Y-m-d',strtotime($request->quotation_date)),
                            'quotation_due_date'            =>  date('Y-m-d',strtotime($request->quotation_due_date)),
                            'quotation_ref_date'            =>  date('Y-m-d',strtotime($request->quotation_ref_date)),
                            'quotation_remarks'             =>  $request->quotation_remarks,
                            'quotation_ref_no'              =>  $request->quotation_ref_no,
                            'quotation_ref_no_search'       =>  SearchDataMatch($request->quotation_ref_no),
                            'quotation_status'              =>  $request->quotation_status,
                            'quotation_approved'            =>  $request->quotation_approved,
                            'quotation_contact_name'        =>  $request->quotation_contact_name,
                            'quotation_department_name'     =>  $request->quotation_department_name,
                            'quotation_contact_phone'       =>  $request->quotation_contact_phone,
                            'quotation_contact_email'       =>  $request->quotation_contact_email,
                            'quotation_subtotal'            =>  $request->quotation_subtotal,
                            'quotation_saletax'             =>  $request->quotation_saletax,
                            'quotation_tax_amount'          =>  $request->quotation_tax_amount,
                            'quotation_grand_total'         =>  $request->quotation_grand_total,
                            'created_by'                    =>  Auth::user()->id,
                            'approved_by'                   =>  $approved_by
                            
                        );
            $last_id = DB::table('tbl_quotation')->where('id',$id)->update($arr);
            DB::table('tbl_quotation_item')->where('quotation_id',$id)->delete();
            if(!empty($request->row)){
                foreach($request->row as $rows)
                {
                $data = array(
                    'cust_ref_no'   =>$rows['cust_ref_no'],
                    'item_name'     =>$rows['item_name'],
                    'item_id'       =>$rows['item_id'],
                    'comment'       =>$rows['comment'],
                    'description'   =>$rows['description'],
                    'vendor_sku'    =>$rows['vendor_sku'],
                    'sap_code'      =>$rows['sap_code'],
                    'hsn_code'      =>$rows['hsn_code'],
                    'tax_rate'      =>$rows['tax_rate'],
                    'grade'         =>$rows['grade'],
                    'brand'         =>$rows['brand'],
                    'packing_name'  =>$rows['packing_name'],
                    'list_price'    =>$rows['list_price'],
                    'rate'          =>$rows['rate'],
                    'stock'         =>$rows['stock'],
                    'mrp'           =>$rows['mrp'],
                    'discount_per'  =>$rows['discount_per'],
                    'discount'      =>$rows['discount'],
                    'quantity'      =>$rows['quantity'],
                    'net_rate'      =>$rows['net_rate'],
                    'tax_amount'    =>$rows['tax_amount'],
                    'amount'        =>$rows['amount'],
                    'customer_id'   =>$request->customer_name,
                    'quotation_id'  =>$id
                    );
                    //pr($data); die;
                    DB::table('tbl_quotation_item')->insertGetId($data);
            }			
            }
			

            DB::table('tbl_quotation_shipping_charge')->where('quotation_id',$id)->delete();
            if(!empty($request->other_charges_val)){
                foreach($request->other_charges_val as $key=>$rows_charges)
                {
                    $data_charges = array(
                        'quotation_id'          => $id,
                        'other_charges_name'    => $request->other_charges_name[$key],
                        'other_charges_val'     => $rows_charges
                    );
                    DB::table('tbl_quotation_shipping_charge')->insertGetId($data_charges);
                }
            }

            return redirect()->route('quotation_list')->with('success', 'Quotation Updated successfully.');
            
        
		}
    }
	
	

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($id!='') {
			DB::table('tbl_quotation')->where('id', '=', $id)->delete();
			DB::table('tbl_quotation_item')->where('quotation_id', '=', $id)->delete();
            DB::table('tbl_quotation_shipping_charge')->where('quotation_id', '=', $id)->delete();
		}
		Session::flash('success', 'Quotation deleted successfully!');
		return redirect()->route('quotation_list')->with('danger', 'Quotation deleted successfully.');
       
    }

    public function item_search_data(Request $request)
    {
        //pr($request->all()); die;
       $html = '';
       if($request->rowCount==1)
       {
       if($request->data_count==0)
       {
       $i=1;
       }else{
       $i = ($request->rowCount+1);   
       }
       }else if($request->rowCount>1){
       $i = ($request->rowCount+1);   
       }
       else
       {
       $i=1;   
       }
       foreach($request->id as $Ids){
       $object_itemlist = DB::table('items')->where('id', $Ids)->first();
       $itemlist = json_decode(json_encode($object_itemlist), true);
	   $id = $itemlist['id'];
	   
	   $customer_item = '';
	   $get_item = '';
	   $customer_brand = '';
	   $get_discount = '';
	   $sap_code = '';
	   $discount = '';

       
	   if(!empty($request->customer_name) && !empty($Ids)){
            $colname = $request->id;
            $object = DB::table("tbl_sapcode")
            ->where('customer_id',$request->customer_name)
            ->whereIn('product_id',$colname)
            ->first();
            $data = json_decode(json_encode($object), true);
            $sap_code =  $data['sap_code'];
	   }else{
	       $sap_code =  '';
	   }

	   //START- netrate by customer and item
	   //netrate by customer and item
	   if(!empty($request->customer_name) && !empty($Ids)){
	       //echo 'netrate by customer and item<br>';
		   $customer_item = get_netrate_by_customer_item($request->customer_name,$Ids);
		   if(!empty($customer_item)){
			    $net_rate = $customer_item;  
		   }		   
	   }
	   
       //echo $customer_item; die;
	   
	   // netrate by item
	   if(empty($customer_item))
	   {
	       //echo 'netrate by item<br>';
		  $get_item  = get_netrate_by_item($Ids); 
		  //pr($get_item); die;
		  if(!empty($get_item)){
		     $net_rate = $get_item;  
		  }
	   }
	   //END- netrate by customer and item
	   
	   
	   //START- Discount by customer and brand

       //Discount by customer and brand
	   if(empty($customer_item) && empty($get_item)){
	   if(!empty($request->customer_name) && !empty($itemlist['brand'])){
		   //echo 'Discount by customer and brand<br>';
	       //echo $itemlist['brand']; die;
	       $customer_brand = get_discount_by_customer_brand($request->customer_name,$itemlist['brand']);   
	       //pr($customer_brand); die;
	       if(!empty($customer_brand)){
			    $discount = $customer_brand['discount']; 
                $discount_by = $customer_brand['discount_by']; 
                $net_rate = ($discount_by==1)?$itemlist['list_price']:$itemlist['mrp'];
                
		   }
	   }
	   }
	   //echo $customer_brand; die;
	   
	   // Discount by brand
	   if(empty($customer_item) && empty($get_item)){
	  if(empty($customer_brand))
	   {
		   //echo 'Discount by brand<br>';
		  $get_discount  = get_discount_by_brand($itemlist['brand']); 
		  //pr($get_item); die;
		  if(!empty($get_discount)){
		     $discount = $get_discount['discount']; 
             $discount_by = $get_discount['discount_by']; 
             $net_rate = ($discount_by==1)?$itemlist['list_price']:$itemlist['mrp']; 
             
		  }
	   }
	   
	   }
	   
	   if(empty($customer_item) && empty($get_item) && empty($discount)){
		   if(empty($get_item) && empty($discount))
		   {
			   //echo 'List Price<br>';
			   $net_rate = $itemlist['list_price'];
              
				
		   }
	   }
	   
	   if(!empty($discount))
	   {
	       $discount_data = $discount;
	   }else{
	       $discount_data = 0;
	   }
       if(!empty($net_rate))
	   {
	       $net_rate = $net_rate;
	   }else{
	       $net_rate = 0;
	   }

	   //echo $net_rate; die;
	   $total_disc = ($net_rate * $discount_data)/100;
      
       $html .='<tr id="data-row-'.$i.'">
                            <td id="'.$id.'" class="item-details task_left_fix" data-text="Sl">'.$i.'</td>
                            <td class="task_left_fix" data-text="Sl"><input type="text" name="row['.$i.'][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_'.$i.'" required /></td>
							<td class="task_left_fix" data-text="Item">	
							<input type="text" readonly name="row['.$i.'][item_name]" value="'.$itemlist['name'].'-'.$itemlist['brand'].'" class="autocomplete-dynamic form-control" id="itemrel_'.$i.'" required />
							<input type="hidden" name="row['.$i.'][item_id]" id="tmp_id_item_'.$i.'" cus="'.$i.'" rel="item" value="'.$id.'" />
                            </td>
                            <td data-text="Comment">						  
						    <input type="text" name="row['.$i.'][comment]" class="autocomplete-dynamic form-control" id="comment_rel_'.$i.'" />
                            </td>
                            <td data-text="Item Description">						  
						    <input type="text" name="row['.$i.'][description]" value="'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$itemlist['description']).'" class="autocomplete-dynamic form-control" id="description_rel_'.$i.'" />
                            </td>
                          <td data-text="Vendor SKU">						  
						<input type="text" readonly name="row['.$i.'][vendor_sku]" value="'.$itemlist['vendor_sku'].'" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'.$i.'" required />
                            </td>
                            <td data-text="SAP Code">						  
						<input type="text" readonly name="row['.$i.'][sap_code]" value="'.$sap_code.'" class="autocomplete-dynamic form-control" id="sap_code_rel_'.$i.'" />
                            </td>
                          <td data-text="HSN Code">
                              <input type="text" readonly name="row['.$i.'][hsn_code]" class="autocomplete-dynamic form-control" value="'.$itemlist['hsn_code'].'" id="hsn_code_rel_'.$i.'" required />
                              </td>
                          <td data-text="Tax">
                              <input type="text" name="row['.$i.'][tax_rate]" cus="'.$i.'" value="'.$itemlist['tax_rate'].'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'.$i.'" required />
                              </td>
                          <td data-text="Packing Name">
                              <input type="text" readonly name="row['.$i.'][packing_name]" value="'.$itemlist['packing_name'].'" class="autocomplete-dynamic form-control" id="packing_name_rel_'.$i.'" /> 
                            </td>
                          <td data-text="List Price">
                              <input type="text" readonly name="row['.$i.'][list_price]" value="'.$itemlist['list_price'].'" class="autocomplete-dynamic form-control" id="list_price_rel_'.$i.'" /> 
                            </td>
                            <td data-text="Rate">
                              <input type="text" name="row['.$i.'][rate]" cus="'.$i.'" value="'.$net_rate.'" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'.$i.'" />
                          </td>
                          <td data-text="Stock">
                              <input type="text" readonly name="row['.$i.'][stock]" value="'.$itemlist['stock'].'" class="autocomplete-dynamic form-control" id="stock_rel_'.$i.'" /> 
                              </td>
                          <td data-text="MRP">
                            <input type="text" readonly name="row['.$i.'][mrp]" cus="'.$i.'" value="'.$itemlist['mrp'].'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'.$i.'" /> 
                            </td>
                             <td data-text="Dis %">
                              <input type="number" min="0" name="row['.$i.'][discount_per]" cus="'.$i.'" value="'.$discount_data.'" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'.$i.'" />
                          </td>
                          <td data-text="Discount">
                              <input type="number" readonly name="row['.$i.'][discount]" value="'.$total_disc.'" class="autocomplete-dynamic form-control" id="discount_rel_'.$i.'" />
                          </td>
                          <td data-text="Quantity">
                              <input type="number" min="1" required name="row['.$i.'][quantity]" cus="'.$i.'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'.$i.'" />
                              </td>
                              <td data-text="Net Rate" id="data-row-net-rate-'.$i.'" class="netrate" style="font-weight:bold;" align="right"></td>
                              <td data-text="Tax Amount" id="data-row-tax-amount-'.$i.'" class="taxamount" style="font-weight:bold;" align="right"></td>
                          <td data-text="Amount" id="data-row-amount-'.$i.'" class="amount" style="font-weight:bold;" align="right"></td>
                          <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'.$i.'" name="row['.$i.'][net_rate]" />
                          <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'.$i.'" name="row['.$i.'][tax_amount]" />
                          <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'.$i.'" name="row['.$i.'][amount]" />
                          <td><a href="javascript:void(0);" onclick="return removeRow('.$i.');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                        </tr>';
        $i++;}
       
       return $html;
    }

    public function ajaxCustomerDetails($customer_id)
	{
	  
	  $object = DB::table('customers')->where('id',$customer_id)->first();
      $data = json_decode(json_encode($object), true);
      //pr($data); die;
      $object_dept = DB::table('tbl_department_name_data')->where('c_id',$customer_id)->get()->toArray();
      $dept = json_decode(json_encode($object_dept), true);

	  $name = array();
	  $d = array();
	  $category = $data['category'];
	  $object_categories = DB::table('customer_categories')->where('id',$category)->first();
      $customer_categories = json_decode(json_encode($object_categories), true);
      //pr($customer_categories); die;
	  if($customer_categories['delivery_day']!='')
	  {
	   $today = time();
	   $delivery_date = date('d-m-Y',strtotime('+'.$customer_categories['delivery_day'].' days', $today));
	  }else{
	   $delivery_date = date('d-m-Y');  
	  }
      if(!empty($dept)){
	  foreach($dept as $names){
	      $d['department'] = getDepartmentName($names['d_id']);
	      $d['name']=$names['name'];
	      $d['label']=$names['name'];
	      $d['mobile']=$names['mobile_no'];
	      $d['email']=$names['email'];
	      $d['delivery_date'] = $delivery_date;
	      array_push($name,$d);
	  }
    }else{
        $d['delivery_date'] = $delivery_date;
        array_push($name,$d);  
    }
      return json_encode($name);
	}

    public function quotation_pdf($id)
	{
	    $object_quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
		$object_quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
		$object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
		$object_company_details = DB::table('company_details')->first();
        $company_details = json_decode(json_encode($object_company_details), true);
        $object_other_charges_data = DB::table('tbl_quotation_shipping_charge')->where('quotation_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
		$view = \View::make('general.quotation.quotationpdf',['quotation_details' => $quotation_details, 'quotation_item_details' => $quotation_item_details, 'other_charges'=>$other_charges, 'company_details' => $company_details,'other_charges_data'=>$other_charges_data]);
		$html_content = $view->render(); 
 		PDF::SetTitle('Quotation');
		PDF::SetFont('helvetica', '', 8);
 		PDF::AddPage('L');
 		PDF::writeHTML($html_content, true, false, true, false, '');
 		$filename = $quotation_details['quotation_no'].'.pdf';
 		PDF::Output($filename,'D');
 		exit;
	}

    public function quotation_print($id)
	{
	    $object_quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
		$object_quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
		$object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
		$object_company_details = DB::table('company_details')->first();
        $company_details = json_decode(json_encode($object_company_details), true);
        $object_other_charges_data = DB::table('tbl_quotation_shipping_charge')->where('quotation_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
		//pr($quotation_details); die;
		return view('general.quotation.quotationprint',['quotation_details' => $quotation_details, 'quotation_item_details' => $quotation_item_details, 'other_charges'=>$other_charges, 'company_details' => $company_details,'other_charges_data'=>$other_charges_data]);
	}

    public function view($id)
	{
	    $object_quotation_details = DB::table('tbl_quotation')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
		$object_quotation_item_details = DB::table('tbl_quotation_item')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
		$object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        $object_company_details = DB::table('company_details')->first();
        $company_details = json_decode(json_encode($object_company_details), true);
        $object_other_charges_data = DB::table('tbl_quotation_shipping_charge')->where('quotation_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
		$object_quotation_details_revised = DB::table('tbl_quotation_revised')->select('id','revised_date')->where('revised_id',$id)->orderBy('revised_date','ASC')->get()->toArray();
        $quotation_details_revised = json_decode(json_encode($object_quotation_details_revised), true);
		//pr($quotation_details_revised); die;
		return view('general.quotation.quotationview',['quotation_details_revised'=>$quotation_details_revised,'quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data]);
		
	}

    public function quotation_revised($id,$revised_id)
	{
        $object_quotation_details = DB::table('tbl_quotation_revised')->where('id',$id)->get()->first();
        $quotation_details = json_decode(json_encode($object_quotation_details), true);
		$object_quotation_item_details = DB::table('tbl_quotation_item_revised')->where('quotation_id',$id)->get()->toArray();
        $quotation_item_details = json_decode(json_encode($object_quotation_item_details), true);
		$object_other_charges = DB::table('other_charges')->orderBy('other_charges', 'ASC')->get()->toArray();
        $other_charges = json_decode(json_encode($object_other_charges), true);
        $object_company_details = DB::table('company_details')->first();
        $company_details = json_decode(json_encode($object_company_details), true);
        $object_other_charges_data = DB::table('tbl_quotation_shipping_charge_revised')->where('quotation_id', $id)->get()->toArray();
        $other_charges_data = json_decode(json_encode($object_other_charges_data), true);
		$object_quotation_details_revised = DB::table('tbl_quotation_revised')->select('id','revised_date')->where('revised_id',$id)->orderBy('revised_date','ASC')->get()->toArray();
        $quotation_details_revised = json_decode(json_encode($object_quotation_details_revised), true); 
        return view('general.quotation.quotationrevised',['quotation_details'=>$quotation_details,'quotation_item_details'=>$quotation_item_details,'other_charges'=>$other_charges,'other_charges_data'=>$other_charges_data]);
	}

    public function ajaxCustomerDetails_delivery_date($customer_id)
	{
	  $object_data = DB::table('customers')->where('id',$customer_id)->first(); 
      $data = json_decode(json_encode($object_data), true);
	  $category = $data['category'];
	  $object_customer_categories = DB::table('customer_categories')->where('category',$category)->first();
      $customer_categories = json_decode(json_encode($object_customer_categories), true);
	  if($customer_categories['delivery_day']!='')
	  {
	   $today = time();
	   return $delivery_date = date('d-m-Y',strtotime('+'.$customer_categories['delivery_day'].' days', $today));
	  }else{
	   return $delivery_date = date('d-m-Y');  
	  }
	}

}
   