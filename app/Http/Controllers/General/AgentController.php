<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\General\AgentModel;
use DB;
use Session;
use Excel;
use PDF;
use MyApp\VendorProductsImport;
use App\AgentsExport\ListExport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentController extends Controller
{
	function __construct(){
		$this->agentmodel = new AgentModel();
	}
	
    public function index() {
		$agents = DB::table('agents')->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.agent_management.agentlist')->with('agents', $agents);
    }	

    public function create(Request $request) {
		return view('general.agent_management.add_agent');
    }	

    public function store(Request $request) {
        $this->validate($request, [
			'name'  => 'required',
			'email' => 'required',
			'phone' => 'required',
		]);
		$data = array(
			"name"  	  => $request->name,
			"email" 	  => $request->email,
			"phone"	      => $request->phone,
			"created_at"  => date('Y-m-d H:i:s')
		);
		$insert = DB::table('agents')->insert(array($data));
		if($insert){			
			Session::flash('success', 'Agent Added Successfully');
			return redirect('admin/agentlist');
		} else{ 
			return view('general.agent_management.add_agent');
		}
    }
	

    public function edit($id) {
        $editAgent = DB::table('agents')->where('_id',$id)->first();
		return view('general.agent_management.edit_agent')->with('editAgent', $editAgent);
    }
	
    public function update(Request $request, $id) {
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
        if($id!='') {
			DB::table('agents')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Agent deleted successfully!');
		return redirect('admin/agentlist');
    }
	
	public function AgentImport(Request $request) {    
	    /* echo "Hellllo"; die; */
		if ($request->input('submit') != null ){
			$file = $request->file('file');
			// File Details 
			$filename  = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath  = $file->getRealPath();
			$fileSize  = $file->getSize();
			$mimeType  = $file->getMimeType();
			
			// Valid File Extensions
			$valid_extension = array("csv");
			// 2MB in Bytes
			$maxFileSize = 2097152; 
			// Check file extension
			if(in_array(strtolower($extension),$valid_extension)){
				// Check file size
				if($fileSize <= $maxFileSize){
					// File upload location
					$location = 'uploads'; // Upload file
					$file->move($location,$filename); // Import CSV to Database
					$filepath = public_path($location."/".$filename);
					$file = fopen($filepath,"r");  // Reading file
					$importData_arr = array();
					$i = 1;
					while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
						$num = count($filedata );
						for ($c=0; $c < $num; $c++) {
							$importData_arr[$i][] = $filedata [$c];
						}
						$i++;
					}
					fclose($file);
					// Insert to MySQL database
					foreach($importData_arr as $importData){
						/* echo "<pre>"; print_r($importData[3]); die; */
						$insertData = array(
							"name"  => $importData[0],
							"email" => $importData[1],
							"phone" => $importData[2],
							"created_at"  => date('Y-m-d H:i:s')
						);
						/* Page::insertData($insertData); */
					}
					$insert = DB::table('agents')->insert(array($insertData));
					/* echo "<pre>"; print_r($insertData); die; */
					Session::flash('message','Import Successful.');
				}else{
					Session::flash('message','File too large. File must be less than 2MB.');
				}
			}else{
				Session::flash('message','Invalid File Extension.');
			}
		}
		return redirect('admin/agentlist');
	}
	
	public function agentExport(){
		/* echo "Hello"; die; */
		return Excel::download(new ListExport, 'agent.xlsx');
	}
}