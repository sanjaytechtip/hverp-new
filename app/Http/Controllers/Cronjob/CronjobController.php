<?php
namespace App\Http\Controllers\Cronjob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;

error_reporting(0);

class CronjobController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function cronjob_everyday()
    {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',6)
        ->get(); 
        $results = json_decode(json_encode($object), true);
        if(!empty($results))
        {
            foreach($results as $res)
            {
                $initial_target_date = strtotime($res['saleorder_date']);
	            $now = time();
	            $difference = $now - $initial_target_date;
	            $diff_hours = floor($difference / (60*60) );

                if($diff_hours==0)
	            {
                $this->createTask($res); 
                }
                else if($diff_hours%24==0)
                {
                $this->createTask($res);
                }
            }
            
        }
        DB::table('tbl_config_sale_order')->update(array('row'=>$SrNo['row']+1));
    }



    public function create_weekly_task()
    {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',5)
        ->where('tbl_sale_order.day_of_week','!=','')
        ->get(); 

        $results = json_decode(json_encode($object), true);
        if(!empty($results))
        {
        foreach($results as $res)
        {

        $initial_target_date    = strtotime($res['saleorder_date']);
        $now                    = time();
        $difference             = $now - $initial_target_date;
        $differencefordays      = $now - ($initial_target_date + 24*60*60);
        $diff_hours             = floor($difference / (60*60) );
	    $diff_days              = floor($differencefordays / (24*60*60) );


        if($diff_hours>24 && $diff_hours<48)
	    {
        $this->createTask($res);
        }
        else if($diff_days%7==0 && $diff_days>=7)
        {
        $this->createTask($res);
        }

       }
      }
        
    }



   public function create_monthly_task() 
   {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',3)
        ->where('tbl_sale_order.date_of_month','!=','')
        ->get(); 
        $results = json_decode(json_encode($object), true);
        if(!empty($results))
        {
        foreach($results as $res)
           {
            if($res['date_of_month']!=0)
	            {
                $NextMonthCreateTask = date('Y').'-'.date('m').'-'.$res['date_of_month'];
                $DateCheck = date('Y-m-d');
                if($DateCheck == $NextMonthCreateTask)
                    {
                    $this->createTask($res);
                    }
                }
            }
        }
   }


   public function create_forthnightly_task()
   {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',4)
        ->where('tbl_sale_order.date_of_forthnigthly','!=','')
        ->get();
        $results = json_decode(json_encode($object), true);
        if(!empty($results))
        {
            foreach($results as $res)
            {
                if($res['date_of_fortnightly']!=0 && $res['next_create_date']!='')
	           {
                $NextMonthCreateTask = $res['next_create_date'];
                $DateCheck = date('Y-m-d');
                if($DateCheck == $NextMonthCreateTask)
                    {
                        $next_create_date  = date('Y-m-d',strtotime('+14 days', strtotime($NextMonthCreateTask)));
                        DB::table('tbl_sale_order')->where('id',$res['id'])->where('is_recurring',1)->where('recurring_type',4)->update(array('next_create_date'=>$NextMonthCreateTask));
                        $this->createTask($res);
                    }
               }
            }
        }
   }


   public function create_quaterly_task() 
   {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',2)
        ->where('tbl_sale_order.date_of_quaterly','!=','')
        ->where('tbl_sale_order.month_of_quaterly','!=','')
        ->get();
        $results = json_decode(json_encode($object), true);
        if(!empty($results))
        {
            $nextMonth = date('m');
            foreach($results as $res)
            {
                $original_initial_target_date = $res['saleorder_date'];
                $checkDateLoop = $this->checkQuaterly($res['month_of_quaterly']);  
                if(array_search($nextMonth,$checkDateLoop)!='')
	             {
                    $c_month = $checkDateLoop[array_search($nextMonth,$checkDateLoop)];
                    $new_date = date('Y').'-'.$c_month.'-'.$res['date_of_quaterly'];
                    $DateCheck = date('Y-m-d');
                    if($DateCheck == $new_date)
                    {
                        $this->createTask($res);
                    }
                 }

            }
        }
   }


   public function create_yearly_task()
   {
        $object= DB::table('tbl_sale_order')
        ->where('tbl_sale_order.is_recurring', 1)
        ->where('tbl_sale_order.recurring_type',1)
        ->where('tbl_sale_order.date_of_yearly','!=','')
        ->where('tbl_sale_order.month_of_yearly','!=','')
        ->get();
        $results = json_decode(json_encode($object), true);

        if(!empty($results))
        {
            foreach($results as $res)
            {
                $NextYearCreateTask = date('Y').'-'.$res['month_of_yearly'].'-'.$res['date_of_yearly'];
                $DateCheck = date('Y-m-d');
                if($DateCheck == $NextYearCreateTask)
                {
                    $this->createTask($res); 
                }
            }
        }

   }

   public function checkQuaterly($month_of_quaterly)
	{
	$month_of_quaterly = explode('/',$month_of_quaterly);
	$quaterly_one = array(1,4,7,10);
	$quaterly_two = array(2,5,8,11);
	$quaterly_three = array(3,6,9,12);
	
	if ($month_of_quaterly == $quaterly_one) 
	return $quaterly_one;
	else if ($month_of_quaterly == $quaterly_two) 
	return $quaterly_two;
	else if ($month_of_quaterly == $quaterly_three) 
	return $quaterly_three;
	}


    public function createTask($res)
    {
        $object_SrNo = DB::table('tbl_config_sale_order')->first();
        $SrNo  = json_decode(json_encode($object_SrNo), true);
        $arr = array(
            'saleorder_type'                =>  $res['saleorder_type'],
            'saleorder_srno'                =>  $SrNo['row'],
            'saleorder_no'                  =>  $res['saleorder_type'].$SrNo['row'],
            'saleorder_priority'            =>  $res['saleorder_priority'],
            'customer_name'                 =>  $res['customer_name'],
            'saleorder_date'                =>  date('Y-m-d'),
            'saleorder_due_date'            =>  date('Y-m-d'),
            'saleorder_ref_date'            =>  date('Y-m-d'),
            'saleorder_remarks'             =>  $res['saleorder_remarks'],
            'saleorder_ref_no'              =>  '',
            'saleorder_ref_no_search'       =>  '',
            'saleorder_status'              =>  $res['saleorder_status'],
            'saleorder_approved'            =>  '',
            'saleorder_contact_department'  =>  $res['saleorder_contact_department'],
            'saleorder_contact_name'        =>  $res['saleorder_contact_name'],
            'saleorder_contact_phone'       =>  $res['saleorder_contact_phone'],
            'saleorder_contact_email'       =>  $res['saleorder_contact_email'],
            'saleorder_subtotal'            =>  $res['saleorder_subtotal'],
            'saleorder_saletax'             =>  $res['saleorder_saletax'],
            'saleorder_tax_amount'          =>  $res['saleorder_tax_amount'],
            'saleorder_grand_total'         =>  $res['saleorder_grand_total'],
            'created_by'                    =>  $res['created_by'],
            'approved_by'                   =>  $res['approved_by'],
            'type'                          =>  $res['type'],
            'single_order'                  =>  '',
            'adv_amount'                    =>  $res['adv_amount'],
            'is_recurring'  				=>  0,
            'recurring_type'				=>  0,
            'date_of_yearly'				=>  '',
            'month_of_yearly'				=>  '',
            'date_of_quaterly'				=>  '',
            'month_of_quaterly'				=>  '',
            'date_of_month'					=>  '',
            'date_of_forthnigthly'			=>  '',
            'day_of_week'					=>  '',
            'is_scheduled'  				=>  ''
            
        ); 
        
        $last_id = DB::table('tbl_sale_order')->insertGetId($arr);
        $other_charges_val_object = DB::table('tbl_so_shipping_charge')->where('sale_order_id',$res['id'])->get();
        $other_charges_val  = json_decode(json_encode($other_charges_val_object), true);
        if(!empty($other_charges_val))
        {
            foreach($other_charges_val as $key=>$rows_charges)
            {
                $data_charges = array(
                    'sale_order_id'          => $last_id,
                    'other_charges_name'     => $rows_charges['other_charges_name'],
                    'other_charges_val'      => $rows_charges['other_charges_val'],
                );
                DB::table('tbl_so_shipping_charge')->insertGetId($data_charges);
            }
        }
        
        $sale_order_id_object = DB::table('tbl_sale_order_item')->where('sale_order_id', $res['id'])->get(); 
        $sale_order_id = json_decode(json_encode($sale_order_id_object), true);
        if(!empty($sale_order_id))
        {
            foreach($sale_order_id as $sale_order_item)
            {
                $data = array(
                    'cust_ref_no'       =>$sale_order_item['cust_ref_no'],
                    'item_name'         =>$sale_order_item['item_name'],
                    'item_id'           =>$sale_order_item['item_id'],
                    'vendor_sku'        =>$sale_order_item['vendor_sku'],
                    'sap_code'          =>$sale_order_item['sap_code'],
                    'hsn_code'          =>$sale_order_item['hsn_code'],
                    'tax_rate'          =>$sale_order_item['tax_rate'],
                    'grade'             =>$sale_order_item['grade'],
                    'brand'             =>$sale_order_item['brand'],
                    'packing_name'      =>$sale_order_item['packing_name'],
                    'list_price'        =>$sale_order_item['list_price'],
                    'rate'              =>$sale_order_item['rate'],
                    'stock'             =>$sale_order_item['stock'],
                    'mrp'               =>$sale_order_item['mrp'],
                    'discount_per'      =>$sale_order_item['discount_per'],
                    'discount'          =>$sale_order_item['discount'],
                    'quantity'          =>$sale_order_item['quantity'],
                    'net_rate'          =>$sale_order_item['net_rate'],
                    'tax_amount'        =>$sale_order_item['tax_amount'],
                    'amount'            =>$sale_order_item['amount'],
                    'customer_id'       =>$res['customer_name'],
                    'sale_order_id'     =>$last_id,
                    'quotation_item_id' =>$sale_order_item['quotation_item_id'],
                    'scheduled_date'	=>$sale_order_item['scheduled_date'],
                    'schedule_type'		=>$sale_order_item['schedule_type']
                    );
            }
        }

        $last_item_id = DB::table('tbl_sale_order_item')->insertGetId($data);
    }
	
}