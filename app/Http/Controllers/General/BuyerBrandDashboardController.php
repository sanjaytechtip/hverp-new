<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use App\General\ArticleModel;
use App\RegisterForm;
use DB;
use Session;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class BuyerBrandDashboardController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
	{
		
	}
	
	public function user_dashboard()
    {
        return view('userdashboard.user_dashboard')->with('no', 1);
    }
	
	public function sendenqemail(Request $request){
		$post = $request->all();
		$userLoginData = Session::get('userLogin');
		$login_type = $userLoginData['login_type'];
		$user_login_email = $userLoginData['user_login_id'];
		
		$ccArr = array();
		if($login_type=='buyer_login'){
			$sales_id = $userLoginData['agent'];
			$merchant_id = $userLoginData['merchant_name'];
		
			$sales_data = getUserDetailById($sales_id);		
			$sales_email = $sales_data['email'];
			
			if($sales_email!=''){
				array_push($ccArr,$sales_email);
			}
			
			$merchant_data = getUserDetailById($merchant_id);
			$merchant_email = $merchant_data['email'];
			if($merchant_email!=''){
				array_push($ccArr,$merchant_email);
			}
		}elseif($login_type=='brand_login'){
			$company_name = $userLoginData['brand_name'];
		}
		

		$cc = $ccArr;
		//$cc = array();
		
		$company_name = $userLoginData['company_name'];
		
		$to = 'sc@positex.in';
		//$to = 'shoyeb.at.techvoi@gmail.com';
		$from = $user_login_email; 
		$fromName = 'Positex Pvt. Ltd'; 
		 
		$subject = 'Enquiry: '.$company_name;
		
		$data["from"] = $from;
		$data["from_name"] = $company_name;
		$data["email"] = $to;
		
        $data["subject"] = $subject;
        $data["body"] = $post['message'];
		
		if(!empty($cc)){
			$data["cc"] = $cc;
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
					->cc($data["cc"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
		}else{
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
		}
		echo '1';
		die;
	}
	
	
	public function completepst(Request $request){
		$post = $request->all();
		if($post['pstid']!=''){
			$upId = DB::table('pst_buyer_brand')
						->where('_id', $post['pstid'])
						->update(
								array(
								'status'=>'1',
								'pst_closed_at'=>date('Y-m-d')
								)
							); 
			if($upId){
				echo '1';
				die;
			}else{
				echo '0';
				die;
			}
		}else{
			echo '0';
			die;
		}
	}
	
	public function sendpstemail(Request $request){
		$post = $request->all();
		
		$userLoginData = Session::get('userLogin');
		$login_type = $userLoginData['login_type'];
		$user_login_email = $userLoginData['user_login_id'];
		$user_id = $userLoginData['user_id'];
		
		$sales_id = '';
		$merchant_id = '';
			
		$ccArr = array();
		if($login_type=='buyer_login'){
			$sales_id = $userLoginData['agent'];
			$merchant_id = $userLoginData['merchant_name'];
		
			$sales_data = getUserDetailById($sales_id);		
			$sales_email = $sales_data['email'];
			
			if($sales_email!=''){
				array_push($ccArr,$sales_email);
			}
			
			$merchant_data = getUserDetailById($merchant_id);
			$merchant_email = $merchant_data['email'];
			if($merchant_email!=''){
				array_push($ccArr,$merchant_email);
			}
			
			$company_name = $userLoginData['company_name'];
			
		}elseif($login_type=='brand_login'){
			$company_name = $userLoginData['brand_name'];
			
			$sales_id = $userLoginData['sales_agent'];
			$merchant_id = $userLoginData['merchant'];
			
		}
		

		//$cc = $ccArr;
		$cc = array();
		
		$to = 'orders@positex.in';
		//$to = 'shoyeb.at.techvoi@gmail.com';
		$from = $user_login_email; 
		$fromName = 'Positex Pvt. Ltd'; 
		 
		$subject = 'PST: '.$company_name;
		
		$data["from"] = $from;
		$data["from_name"] = $company_name;
		$data["email"] = $to;
		
        $data["subject"] = $subject;
        $data["body"] = $post['message'];
		
		$send=0;
		if(!empty($cc)){
			$data["cc"] = $cc;
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
					->cc($data["cc"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			$send=1;
		}else{
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			$send=1;
		}
		
		if($send==1){
			$pst_row=DB::table('pst_buyer_brand')->select('pst_no')->orderBy('_id', 'DESC')->first();
			if(empty($pst_row)){
				$pst_no = '1001';
			}else{
				$pst_no = (int) $pst_row['pst_no'];
				$pst_no = ($pst_no+1);
			}
			/* send auto reply email */
			
			$data = array();
			$msg = '<p>Dear Sir/Madam,</p><p>Thanks for raising the PST for your concern, vide PST#'.$pst_no.' Date '.date('d M  Y').'.</p><p>We shall look into your problem/ feedback and provide reply within 24 hours.</p><p><br></p><p></p><p><br></p><p>Regards<br>Dilip<br>Positex Pvt Ltd.</p>';
			
			
			$to =$user_login_email;
			//$to = 'md8shoyeb@gmail.com';
			
			$from = 'orders@positex.in'; 
			$fromName = 'Positex Pvt. Ltd'; 

			$subject = 'Positex PST#'.$pst_no;

			$data["from"] = $from;
			$data["from_name"] = $fromName;
			$data["email"] = $to;

			$data["subject"] = $subject;
			$data["body"] = $msg;
		
			
			Mail::send([], $data, function($message)use($data) {
            $message->from($data["from"], $data["from_name"])
					->to($data["email"])
                    ->subject($data["subject"])
                   ->setBody($data["body"], 'text/html');
			});
			
			
			$data = array();
			$data['pst_no'] = strval($pst_no);
			$data['problem_faced'] = $post['message'];
			$data['positex_remarks'] = '';
			$data['status'] = '0';
			$data['pst_closed_at'] = '';
			$data['login_type'] = $login_type;
			$data['user_login_email'] = $user_login_email;
			$data['user_id'] = $user_id;
			
			$data['agent'] = $sales_id;
			$data['merchant_name'] = $merchant_id;
			
			$data['created_at'] = date('Y-m-d H:i:s');
			$inserted = DB::table('pst_buyer_brand')->insertGetId($data);
			if($inserted){
				echo '1';
			}else{
				echo '0';
			}
			
		}else{
			echo '0';
		}	
		
		die;
	}
	
	public function buyerbrandmis(){
		return view('userdashboard.buyerbrandmis');
	}
	
}