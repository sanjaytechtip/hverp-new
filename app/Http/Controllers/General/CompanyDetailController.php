<?php
namespace App\Http\Controllers\General;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Route;

class CompanyDetailController extends Controller {
    public function index(Request $request) {
        userPermission(Route::current()->getName());
		if($request->has('name')){
			$elements = DB::table('group_of_company')->where('company_name','like','%'.$request->name.'%')->orderBy('company_name', 'DESC')->paginate(25);
			$search_by = $request->name;
		}else{
			$elements = DB::table('group_of_company')->orderBy('company_name', 'DESC')->paginate(20);
			$search_by = '';
		}
		return view('company_management.companylist',['elements'=>$elements,'search_by'=>$search_by]);
    }
	
    public function create() {
        /* userPermission(Route::current()->getName()); */
		$company_detail = DB::table('company_details')->first();
		//echo "<pre>"; print_r($company_detail); die;
		return view('companydetail_management.add_company_detail',['company_detail'=>$company_detail]);
    }
	
	public function store(Request $request) {
		//pr($request);die;
        $this->validate($request, [
			'company_name' => ['required'],
			'pan_no'  	   => ['required']
		]);
		$data = array(
			"company_name" => $request->company_name,
			"pan_no"       => $request->pan_no,
			"gstin_no"     => $request->gstin_no,
			"address"      => $request->address,
			"bank_details" => $request->bank_details,
			"created_at"   => date('Y-m-d H:i:s')
		);
		/*  echo "<pre>"; print_r($data); die; */
		$insert = DB::table('company_details')->update($data);
		if($insert){			
			Session::flash('success', 'Company Details Added Successfully');
			return redirect('admin/addcompanydetails');
		} else{ 
			return view('companydetail_management.add_company_detail');
		}
    }
	
	public function show($id) {
        //
    }
	
    public function edit($id) {	
        userPermission(Route::current()->getName());
		$editElement = DB::table('group_of_company')->where('_id',$id)->first();
		return view('company_management.edit_company')->with('editElement', $editElement);
	}

	public function update(Request $request, $id) {
		$this->validate($request, [
			'group_name'   => ['required'],
			'company_name' => ['required'],
		]);
		$update = array(
			"group_name"   => $request->group_name,
			"company_name" => $request->company_name,
			"updated_at"   => date('Y-m-d H:i:s')
		);
		$insert = DB::table('group_of_company')->where('_id', $id)->update($update);
		Session::flash('success', 'Group of Company updated successfully!');
		return redirect('admin/companylist');
    }
	
    public function destroy($id) {
        userPermission(Route::current()->getName());
		if($id!='') {
			DB::table('group_of_company')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Group of Company deleted successfully!');
		return redirect('admin/companylist');
    }
	
	public function search(Request $request){
		$search_by = $request->name;
		
    	if($request->has('name')){
			$elements = DB::connection('mysql')->select("SELECT * FROM tbl_item_elements Where e_name LIKE '%". $request->name."%' ORDER BY e_name DESC");
    		/* $elements = DB::connection('mysql')->table('tbl_item_elements')->orderBy('e_name', 'DESC')->where('name','LIKE', '%' . $request->name. '%'); */
    	}else{
			$elements = DB::connection('mysql')->select('SELECT * FROM tbl_item_elements ORDER BY e_name DESC');
			$elements['search_by'] = $request->name;
    	}
        return view('element_management.elementlist', compact('elements'))->with('search_by', $search_by);
	}
}