<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\CompanyTypeModel;
use DB;
use Session;

class CompanyTypeController extends Controller
{
	function __construct(){
		$this->companyTypeModel = new CompanyTypeModel();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		
		$companyTypelists = DB::table('companyType_items')->orderBy('created_at','-1')->get();
		return view('general.companyType.companyTypelisting')->with('companyTypelists', $companyTypelists);
	 }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
		return view('general.companyType.companyTypecreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // pr($_POST);die;
		$this->validate($request, array(
		'company_name'=>'required'
		));
		
		if(!empty($request->row)){
		$rows = array();
		foreach($request->row as $row){
		$rows[$row['company_type_id']] = $row['company_type']; 
		}
		}
		
		
		$data  = array(
		"user_id" 		=> $request->user_id,
		"company_name" => $request->company_name,
		"items"=>$rows,
		"created_at"	=> date('Y-m-d H:i:s')
		); 
		//pr($data);die;
		$insert = DB::table('companyType_items')->insert(array($data));
		if($insert)
		{
		return redirect()->route('companyTypelisting')->with('success', 'CompanyType store successfully.');
		}else{
		return redirect()->route('companyTypelisting')->with('success', 'CompanyType not stored.');
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
    public function edit($id)
    {
        $editcompanyType = DB::table('companyType_items')->where('_id',$id)->first();
		//pr($editdropdown['items']);die;
		return view('general.companyType.companyTypeedit')->with('editcompanyType', $editcompanyType);
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


		if($id!='') {
			DB::table('companyType_items')->where('_id', '=', $id)->delete();
		}
      
	    $this->validate($request, array(
		'company_name'=>'required'
		));	
		
		if(!empty($request->row)){
		$rows = array();
		foreach($request->row as $row){
		$rows[$row['company_type_id']] = $row['company_type']; 
		}
		}
		
		$data  = array(
		"user_id" 		=> $request->user_id,
		"company_name" => $request->company_name,
		"items"=>$rows,
		"created_at"	=> date('Y-m-d H:i:s')
		);  
		
		$insert = DB::table('companyType_items')->insert(array($data));
		if($insert)
		{
			return redirect()->route('companyTypelisting')->with('success', 'CompanyType update successfully.');
		}else{
			return redirect()->route('companyTypelisting')->with('success', 'CompanyType not updated.');
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
         if($id!='') {
			DB::table('companyType_items')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'CompanyType deleted successfully!');
		return redirect()->route('companyTypelisting')->with('danger', 'CompanyType deleted successfully.');
    }
}
