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

class RoleController extends Controller {
	
    public function index() {
        /* userPermission(Route::current()->getName()); */
		/* if(!userHasRight()){
			return redirect()->route('dashboard');
		} */
		$rolelists = DB::table('roles')->orderBy('order','asc')->paginate(20);
		 /* pr($rolelists);die; */
		return view('role_management.rolelist')->with('rolelists', $rolelists);
    }
	
    public function create() {
        /* userPermission(Route::current()->getName()); */
        $role_cat = DB::table('role_category')->get()->toArray();
		return view('role_management.add_role',['role_cat'=>$role_cat]);
    }
	
	public function store(Request $request) {
	    //pr($request->all()); die;
        $this->validate($request, array(
			'module_name'=>'required|max:255'
		));
		
		if(!empty($request->row)){
			$rows = array();
			foreach($request->row as $row){
				$rows[$row['sub_module_slug']] = $row['sub_module_name']; 
			}
		}
	
		$data  = array(
			"user_id" 	  => $request->user_id,
			"module_name" => $request->module_name,
			"role_category"  => $request->role_category,
			"order" 	  => $request->order,
			"items"		  => $rows,
			"created_at"  => date('Y-m-d H:i:s')
		);
		/* pr($data);die; */
		$insert = DB::table('roles')->insert(array($data));
		if($insert) {
			return redirect()->route('rolelist')->with('success', 'Roles store successfully.');
		}else{
			return redirect()->route('rolelist')->with('success', 'Roles not stored.');
		}
    }
	
	public function show($id) {
        //
    }
	
    public function edit($id) {	
        //userPermission(Route::current()->getName());
		$editrole = DB::table('roles')->where('_id',$id)->first();
		$role_cat = DB::table('role_category')->get()->toArray();
		//pr($editrole); die;
		return view('role_management.edit_role',['editrole'=>$editrole,'role_cat'=>$role_cat]);
	}

	public function update(Request $request, $id) {
	    //pr($request->all()); die;
		if($id!='') {
			//DB::table('roles')->where('_id', '=', $id)->delete();
		}
		$this->validate($request, array(
			'module_name'=>'required|max:255'
		));
		
		if(!empty($request->row)){
			$rows = array();
			foreach($request->row as $row){
				$rows[$row['sub_module_slug']] = $row['sub_module_name']; 
			}
		}
		$data  = array(
			"user_id" 	  => $request->user_id,
			"module_name" => $request->module_name,
			"role_category"  => $request->role_category,
			"order" 	  => $request->order,
			"items"		  => $rows,
			"created_at"  => date('Y-m-d H:i:s')
		);
		//pr($data);die;
		$insert = DB::table('roles')->where('_id',$id)->update(($data));
		if($insert) {
			return redirect()->route('rolelist')->with('success', 'Roles updated successfully.');
		}else{
			return redirect()->route('rolelist')->with('success', 'Roles not updated.');
		}
    }
	
    public function destroy($id) {
        userPermission(Route::current()->getName());
		if($id!='') {
			DB::table('roles')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Role deleted successfully!');
		return redirect()->route('rolelist')->with('danger', 'Role deleted successfully.');
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