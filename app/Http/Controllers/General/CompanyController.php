<?php
namespace App\Http\Controllers\General;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Route;

class CompanyController extends Controller {
    public function index(Request $request) {
		
        //userPermission(Route::current()->getName());
		if($request->has('name')){
			
			//DB::enableQueryLog();
			
			$elements = DB::table('group_of_company');
			$elements->where('group_name','like','%'.$request->name.'%');			
			
			if($request->has('company_name')){
				$elements->where('g_company_name','LIKE', '%'.$request->company_name.'%');
			} 
			$elements = $elements->orderBy('group_name', 'DESC')->paginate(50);
			$search_by = $request->name;
			
			/* $query = DB::getQueryLog();
			$query = end($query);
			dd($query); */	
			
		}else{
			$elements = DB::table('group_of_company')->orderBy('group_name', 'DESC')->paginate(50);
			$search_by = '';
		}
		
		
		//$elements = json_decode(json_encode($elements), true);

		 /* pr($elements);die;  */
		return view('company_management.companylist',['elements'=>$elements,'search_by'=>$search_by]);
    }
	
    public function create() {
        userPermission(Route::current()->getName());
		return view('company_management.add_company');
    }
	
	public function store(Request $request) {		//echo '<pre>';print_r($_POST);echo '</pre>';die;
		$g_company_name = implode(',',$request->company_name);
        $this->validate($request, [
			'group_name'   => ['required'],
			'company_name' => ['required']
		]);
		// 
		
		$data = array(
			"group_name"   => $request->group_name,
			"g_company_name" => $g_company_name,
			"created_at"   => date('Y-m-d H:i:s')
		);
		/* echo "<pre>"; print_r($data); die; */
		$insert = DB::table('group_of_company')->insert($data);
		if($insert){			
			Session::flash('success', 'Group of Company Added Successfully');
			return redirect('admin/companylist');
		} else{ 
			return view('company_management.add_company');
		}
    }
	
	public function show($id) {
        //
    }
	
    public function edit($id) {	
        userPermission(Route::current()->getName());
		$editElement = DB::table('group_of_company')->where('id',$id)->first();
		$editElement = json_decode(json_encode($editElement), true);
		return view('company_management.edit_company')->with('editElement', $editElement);
	}

	public function update(Request $request, $id) {
		$this->validate($request, [
			'group_name'   => ['required'],
			'company_name' => ['required'],
		]);
		$g_company_name = implode(',',$request->company_name);
		$update = array(
			"group_name"   => $request->group_name,
			"g_company_name" => $g_company_name,
			"updated_at"   => date('Y-m-d H:i:s')
		);
		$insert = DB::table('group_of_company')->where('id', $id)->update($update);
		Session::flash('success', 'Group of Company updated successfully!');
		return redirect('admin/companylist');
    }
	
    public function destroy($id) {
        userPermission(Route::current()->getName());
		if($id!='') {
			DB::table('group_of_company')->where('id', '=', $id)->delete();
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