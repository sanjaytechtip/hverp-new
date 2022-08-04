<?php
namespace App\Http\Controllers\General;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Image;
use Video;
use Route;

class RoleDesignationController extends Controller {
	
    public function index() {
        userPermission(Route::current()->getName());
		/* if(!userHasRight()){
			return redirect()->route('dashboard');
		} */
				
		$rolelists = DB::table('tbl_role_designation')->orderBy('role_value','ASC')->paginate(20);
		return view('role_designation_management.role-designation-list')->with('rolelists', $rolelists);
    }
	public function getMaxDesignationVal(){
		$data_max = DB::table('tbl_role_designation')
						->select('role_value')
						->orderBy('role_value','DESC')
						->get()->toArray();
		if(!empty($data_max)){
			$maxVal = array();
			foreach($data_max as $row){
				$maxVal[] = $row['role_value']; 
			}
			rsort($maxVal);
			return $maxVal[0];
		}
	}
	
    public function create() {
        userPermission(Route::current()->getName());
        $users_assign = DB::table('roles')->orderBy('module_name','ASC')->orderBy('order','ASC')->get();
		$maxVal = $this->getMaxDesignationVal();
		return view('role_designation_management.add_role_designation',['users_assign'=>$users_assign,'maxVal'=>$maxVal]);
    }
	
	public function store(Request $request) {
        $this->validate($request, array(
			'role_name'=>'required|max:255',
			'role_value'=>'required|max:255'
		));
		
		$data  = array(
			"role_name" => $request->role_name,
			"role_value"  => $request->role_value,
			"userrole" 	  => $request->userrole,
			"created_at"  => date('Y-m-d H:i:s')
		);
		//pr($data);die;
		$insert = DB::table('tbl_role_designation')->insert(array($data));
		if($insert) {
			return redirect('admin/role-designation-list')->with('success', 'Designation store successfully.');
		}else{
			return redirect('admin/role-designation-list')->with('success', 'Designation not stored.');
		}
    }
	
	public function show($id) {
        //
    }
	
    public function edit($id) {	
        userPermission(Route::current()->getName());
		$editrole = DB::table('tbl_role_designation')->where('_id',$id)->first();
		$users_assign = DB::table('roles')->orderBy('module_name','ASC')->orderBy('order','ASC')->get();
		return view('role_designation_management.role-designation-edit',['users_assign'=>$users_assign,'editrole'=>$editrole]);
	}

	public function update(Request $request, $id) {

	    $this->validate($request, array(
			'role_name'=>'required|max:255',
			'role_value'=>'required|max:255'
		));
		
	
		$data  = array(
			"role_name" => $request->role_name,
			"role_value"  => $request->role_value,
			"userrole" 	  => $request->userrole
		);
		$insert = DB::table('tbl_role_designation')->where('_id',$id)->update($data);
		if($insert) {
			return redirect('admin/role-designation-list')->with('success', 'Designation updated successfully.');
		}else{
			return redirect('admin/role-designation-list')->with('success', 'Designation not updated.');
		}
    }
	
    public function destroy($id) {
        userPermission(Route::current()->getName());
		if($id!='') {
			DB::table('tbl_role_designation')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Designation deleted successfully!');
		return redirect('admin/role-designation-list')->with('danger', 'Designation deleted successfully.');
    }
	
	public function search(Request $request){
		$where = '1=1';

		if($request->i_category != ''){
			$where .= " AND i_category = '". $request->i_category."'";	
		}
		if($request->i_style != ''){
			$where .= " AND i_style = $request->i_style ";	
		}
		
		$search    = $request->i_category;
		$search_by = $request->i_style;
		if($request->has('i_category') || $request->has('i_style') ){
			$item = DB::connection('mysql')->select("SELECT * FROM tbl_item Where $where ORDER BY i_id DESC");
		}
		else{
			$item = DB::connection('mysql')->select('SELECT * FROM tbl_item ORDER BY i_id DESC LIMIT 200');
			$item['search']    = $request->i_category;
			$item['search_by'] = $request->i_style;
		}
		return view('item_management.itemlist', compact('item'))->with('search', $search)->with('search_by', $search_by);
	}
}