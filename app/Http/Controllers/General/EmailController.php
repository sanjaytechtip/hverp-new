<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\General\EmailModel;
use DB;
use Session;
use Excel;
use PDF;
use MyApp\VendorProductsImport;
use App\AgentsExport\ListExport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmailController extends Controller
{
	function __construct(){
		$this->emailmodel = new EmailModel();
	}
		
    public function index() {
		checkPermission('view_emailtemplate');
		$emails = DB::table('emails')->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.email_management.emaillist')->with('emails', $emails);
    }
	
    public function create(Request $request) {
		checkPermission('add_emailtemplate');
		$mailer = DB::table('emails')->orderBy('_id', 'desc')->get();
		return view('general.email_management.add_email',['mailer' => $mailer]);
    }
	
	public function ajaxMailerData(Request $request, $id) {
		$template_detail = DB::table('emails')->where('key_name', $id)->first();
		 /* pr($template_detail); die;   */
		$html = '
				<input type="hidden" name="id" value='.$template_detail['_id'].' />
				<div class="form-group row  form-material">
					<label class="col-md-3 form-control-label">To : </label>
					<div class="col-md-9">
						<input type="text" placeholder="To" class="form-control" required="required" name="to" value="'.$template_detail['to'].'">
					</div>
				</div>
				<div class="form-group row  form-material">
					<label class="col-md-3 form-control-label">CC : </label>
					<div class="col-md-9">
						<input type="text" placeholder="CC" class="form-control" name="cc" value="'.$template_detail['cc'].'">
					</div>
				</div>
				<div class="form-group row  form-material">
					<label class="col-md-3 form-control-label">From : </label>
					<div class="col-md-9">
						<input type="text" placeholder="From" class="form-control" required="required" name="from" value="'.$template_detail['from'].'">
					</div>
				</div>
				<div class="form-group row  form-material">
					<label class="col-md-3 form-control-label">Subject : </label>
					<div class="col-md-9">
						<input type="text" placeholder="Subject" class="form-control" required="required" name="subject" value="'.$template_detail['subject'].'">
					</div>
				</div>
				<div class="form-group row  form-material">
					<label class="col-md-3 form-control-label">Email Text : </label>
					<div class="col-md-9">
						<textarea class="form-control required" id="email_text" name="email_text">'.$template_detail['email_text'].'</textarea>
					</div>
				</div>
				<p><a href="javascript:void(0)" data-toggle="modal" data-target="#list_'.$template_detail['_id'].'" title="Click to see list details">Click Here For Short Code List</a></p>
				
				<div class="modal fade modal-info show dropdown-popup" id="list_'.$template_detail['_id'] .'" aria-labelledby="exampleModalInfo" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Sort Code</h4>
						  </div>
						  <div class="modal-body">
							'.$template_detail['sort_code'].'
						  </div>
							<div class="text-right">
								<a href="'.url('admin/sortcode_edit/'.$template_detail['_id']).'" title="Edit" class="btn btn-pure btn-success icon  waves-effect waves-classic" ><i class="icon md-edit" aria-hidden="true"></i>Edit</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="text-right">
					<button type="submit" class="btn btn-primary waves-effect waves-classic">Update</button>
				</div>
				<script>$("textarea#product-desc").ckeditor();</script>';
			return $html;
			
	}
	
	public function emailupdate(Request $request){
		$mailer = EmailModel::find($request->id);
		$mailer->key_name = $request->email_content;
		$mailer->to = $request->to;
		$mailer->cc = $request->cc;
		$mailer->from = $request->from;
		$mailer->subject = $request->subject;
		$mailer->email_text = str_replace("\'","'",$request->email_text);
		$mailer->save();
		Session::flash('success', 'Email template updated successfully!');
		return redirect('/admin/sent_email')->withInput();
	}
	
	public function editSortCode($id){
		checkPermission('edit_emailtemplate');
		$editCode = DB::table('emails')->where('_id',$id)->first();
		return view('general.email_management.edit_sort_code')->with('editCode', $editCode);
	}
	
	public function sortcodeupdate(Request $request, $id){
		$this->validate($request, [
			'sort_code' => 'required',
		]);
		$update_sortcode = array(
			'sort_code'  => $request->sort_code,
			'updated_at' => date('Y-m-d H:i:s')
		);
		/* pr($update_sortcode); die; */
		DB::table('emails')->where('_id', $id)->update($update_sortcode);
		Session::flash('success', 'Sortcode updated successfully!');
		return redirect('admin/sent_email');
	}
	
	public function add(Request $request){
		return view('general.email_management.edit_email');
	}
	
    public function store(Request $request) {
		checkPermission('add_emailtemplate');
        /* $this->validate($request, [
			'name' 	 	 => 'required',
			'key_name' 	 => 'required',
			'from'		 => 'required',
			'subject' 	 => 'required',
			'email_text' => 'required',
		]); */
		/* pr($_POST); die; */
		$data = array(
			"name"  	  => $request->name,
			"key_name" 	  => $request->key_name,
			"from"	      => $request->from,
			"subject"	  => $request->subject,
			"email_text"  => $request->email_text,
			"sort_code"   => $request->sort_code,
			"created_at"  => date('Y-m-d H:i:s')
		);
		/* pr($data); die; */
		$insert = DB::table('emails')->insert(array($data));
		if($insert){			
			Session::flash('success', 'Emails Added Successfully');
			return redirect('admin/emaillist');
		} else{ 
			return view('general.email_management.add_email');
		}
    }
	
    public function update(Request $request, $id) {
		checkPermission('edit_emailtemplate');
        $this->validate($request, [
			'name'  => 'required',
			'email' => 'required',
			'phone' => 'required',
		]);
		$Update_Agent = array(
			"name"        => $request->name,
			"email"       => $request->email,
			"phone"	      => $request->phone,
			"updated_at"  => date('Y-m-d H:i:s')
		);
		DB::table('agents')->where('_id', $id)->update($Update_Agent);
		Session::flash('success', 'Agent updated successfully!');
		return redirect('admin/agentlist');
    }
	
    public function destroy($id) {
		checkPermission('delete_emailtemplate');
        if($id!='') {
			DB::table('emails')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Email deleted successfully!');
		return redirect('admin/emaillist');
    }
}