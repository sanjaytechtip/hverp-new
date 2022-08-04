<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\HsnModel;
use Config;
use DB;
use Session;

class HsnController extends Controller
{
	function __construct(){
		$this->hsnmodel = new HsnModel();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		checkPermission('view_hsn');
		$hsncode = $this->hsnmodel->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.hsn.hsnlist')->with('hsncode', $hsncode)->with('search_by', '');
    }
	 
	 public function Search(Request $request)
    {
		  $search_by=$request->hsn_code;
    	if($request->has('hsn_code')){
			//pr($_POST);die;
    		$hsncode = $this->hsnmodel->orderBy('created_at', 'DESC')->where('hsn_code','LIKE', '%' . $request->hsn_code. '%')->paginate(20);
    	}else{
    		$hsncode = $this->hsnmodel->orderBy('created_at', 'DESC')->paginate(20);
			$hsncode['search_by']=$request->hsn_code;
    	}
        return view('general.hsn.hsnlist', compact('hsncode'))->with('search_by', $search_by);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		checkPermission('add_hsn');
		return view('general.hsn.add_hsn');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		checkPermission('add_hsn');
        $this->validate($request, [
			'hsn_code' => 'required',
			'gstn' => 'required',
			/* 'desc' => 'required', */
		]);
		$data = array(
			"hsn_code"		=> $request->hsn_code,
			"gstn"			=> $request->gstn,
			"desc"			=> $request->desc,
			"remark"		=> $request->remark,
			"created_at"	=> date('Y-m-d H:i:s')
		);
		/* pr($data); die; */
		$insert = $this->hsnmodel->addHsnData($data);
		if($insert){			
			Session::flash('success', 'Hsn Added Successfully');
			return redirect('admin/hsnlist');
		} else{ 
			return view('general.hsn.add_hsn');
		}
    }
    public function show($id) {
     
    }

    public function edit($id) {
		checkPermission('edit_hsn');
        $editHsn = $this->hsnmodel->editHsn($id);
		return view('general.hsn.edit_hsn')->with('editHsn', $editHsn);
    }

    public function update(Request $request, $id) {
		checkPermission('edit_hsn');
        $this->validate($request, [
			'hsn_code' => 'required',
			'gstn'     => 'required',
			'desc'     => 'required',
		]);
		$Update_Hsn_Data = array(
			"hsn_code"	  => $request->hsn_code,
			"gstn"		  => $request->gstn,
			"desc"		  => $request->desc,
			"remark"	  => $request->remark,
			"updated_at"  => date('Y-m-d H:i:s')
		);
		DB::table('hsncode')->where('_id', $id)->update($Update_Hsn_Data);
		Session::flash('success', 'Hsn updated successfully!');
		return redirect('admin/hsnlist');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
		checkPermission('delete_hsn');
        if($id!='') {
			DB::table('hsncode')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Hsn Code deleted successfully!');
		return redirect('admin/hsnlist');
    }
	
	public function export_hsn(){
		$filename = "hsn_code.csv";
		$fp = fopen('php://output', 'w');
		$header = array('hsn_code','gstn');	

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('hsncode')->select('hsn_code','gstn')->get()->toarray();
		foreach($data as $row){
			$rowData = array($row['hsn_code'],$row['gstn']);
			fputcsv($fp, $rowData);
		}
		exit;
	}	
}
