<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\DropdownModel;
use DB;
use Session;

class DropdownController extends Controller
{
	function __construct(){
		$this->dropdownmodel = new DropdownModel();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		checkPermission('view_option');
		$dropdownlists = DB::table('dropdown_items')->orderBy('created_at','-1')->paginate(20);
		 /* pr($dropdownlists);die; */
		return view('general.dropdown.dropdownlisting')->with('dropdownlists', $dropdownlists);
	 }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
		checkPermission('add_option');
		return view('general.dropdown.dropdowncreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		checkPermission('add_option');      
		$customMessages = [
		'dropdown_name.unique' => 'Dropdown Name should be unique.',
		];
		$this->validate($request, array(
		'dropdown_name'=>'required|unique:dropdown_items|max:255'
		),$customMessages);
		
		if(!empty($request->row)){
		$rows = array();
		foreach($request->row as $row){
		$rows[$row['label_id']] = $row['label_name']; 
		}
		}
		
		
		$data  = array(
		"user_id" 		=> $request->user_id,
		"key"     		=> strtolower(str_replace(" ","_",$request->dropdown_name)),
		"dropdown_name" => stripslashes($request->dropdown_name),
		"items"=>$rows,
		"created_at"	=> date('Y-m-d H:i:s')
		); 
		//pr($data);die;
		$insert = DB::table('dropdown_items')->insert(array($data));
		if($insert)
		{
		return redirect()->route('dropdownlisting')->with('success', 'Dropdown store successfully.');
		}else{
		return redirect()->route('dropdownlisting')->with('success', 'Dropdown not stored.');
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
	
	public function Search(Request $request){
		$search_by=$request->dropdown_name;
    	if($request->has('dropdown_name')){
			//pr($_POST);die;
    		$dropdownlists = DB::table('dropdown_items')->orderBy('created_at', 'DESC')->where('dropdown_name','LIKE', '%' . $request->dropdown_name. '%')->paginate(20);
    	}else{
    		$dropdownlists = DB::table('dropdown_items')->orderBy('created_at', 'DESC')->paginate(20);
			$dropdownlists['search_by']=$request->dropdown_name;
    	}
        return view('general.dropdown.dropdownlisting', compact('dropdownlists'))->with('search_by', $search_by);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		checkPermission('edit_option');
        $editdropdown = DB::table('dropdown_items')->where('_id',$id)->first();
		//pr($editdropdown['items']);die;
		return view('general.dropdown.dropdownedit')->with('editdropdown', $editdropdown);
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
		checkPermission('edit_option');
		 if($id!='') {
			DB::table('dropdown_items')->where('_id', '=', $id)->delete();
		}
        $this->validate($request, array(
											'dropdown_name'=>'required|max:255'
										));
		
		if(!empty($request->row)){
			$rows = array();
			foreach($request->row as $row){
				$rows[$row['label_id']] = $row['label_name']; 
			}
		}
		
		$data  =	array(
		"user_id" => $request->user_id,
		"dropdown_name" => $request->dropdown_name,
		"key"     		=> strtolower(str_replace(" ","_",$request->dropdown_name)),
		"items"=>$rows,
		"created_at"	=> date('Y-m-d H:i:s')
		); 
		
		$insert = DB::table('dropdown_items')->insert(array($data));
		if($insert)
		{
			return redirect()->route('dropdownlisting')->with('success', 'Dropdown update successfully.');
		}else{
			return redirect()->route('dropdownlisting')->with('success', 'Dropdown not updated.');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		checkPermission('delete_option');
         if($id!='') {
			DB::table('dropdown_items')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Dropdown deleted successfully!');
		return redirect()->route('dropdownlisting')->with('danger', 'Dropdown deleted successfully.');
    }
}
