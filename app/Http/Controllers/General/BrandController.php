<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\BrandModel;
use DB;
use Session;
use Response;

class BrandController extends Controller
{
	function __construct(){
		$this->brandmodel = new BrandModel();
	}
	
    public function index() {
		checkPermission('view_brand');
		$brands = DB::table('brands')->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.brand_management.brandlist')->with('brands', $brands)->with('search_by', '');
    }

    public function create(Request $request) {
		checkPermission('add_brand');
		return view('general.brand_management.add_brand');
    }

    public function store(Request $request) {
		checkPermission('add_brand');
        $this->validate($request, [
			'brand_name'  => 'required',
			/* 'marchant' 	  => 'required', */
			'sales_agent' => 'required',
			'merchant' => 'required',
		]);
		$data = array(
			"brand_name"    => $request->brand_name,
			"sales_agent"   => $request->sales_agent,
			"merchant" => $request->merchant,
			"description"   => $request->description,
			"user_login_id"   => $request->user_login_id,
			"org_password"   => $request->org_password,
			"created_at"    => date('Y-m-d H:i:s')
		);
		/* pr($data); die; */
		$insert = DB::table('brands')->insert(array($data));
		if($insert){			
			Session::flash('success', 'Brand Added Successfully');
			return redirect('admin/brandlist');
		} else{ 
			return view('general.brand_management.add_brand');
		}
    }

    public function edit($id) {
		checkPermission('edit_brand');
        $editBrand = DB::table('brands')->where('_id',$id)->first();
		return view('general.brand_management.edit_brand')->with('editBrand', $editBrand);
    }

    public function update(Request $request, $id) {
		checkPermission('edit_brand');
        $this->validate($request, [
			'brand_name'  => 'required',
			'sales_agent' => 'required',
			'merchant' => 'required',
		]);
		$Update_Brand = array(
			"brand_name"  	=> $request->brand_name,
			"sales_agent"   => $request->sales_agent,
			"merchant" => $request->merchant,
			"description"   => $request->description,
			"user_login_id"   => $request->user_login_id,
			"org_password"   => $request->org_password,
			"updated_at" 	=> date('Y-m-d H:i:s')
		);
		/* pr($Update_Brand); die; */
		DB::table('brands')->where('_id', $id)->update($Update_Brand);
		Session::flash('success', 'Brand updated successfully!');
		return redirect('admin/brandlist');
    }

    public function destroy($id) {
		checkPermission('delete_brand');
        if($id!='') {
			DB::table('brands')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Brand deleted successfully!');
		return redirect('admin/brandlist');
    }
	
	public function view($id) {
        $viewArticle = DB::table('articles')->where('_id',$id)->first();
		return view('general.article.view_article')->with('viewArticle', $viewArticle);
    }
	
	public function BrandImport(Request $request) {
		if ($request->input('submit') != null ){
			$file = $request->file('file');
	 
			$filename  = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath  = $file->getRealPath();
			$fileSize  = $file->getSize();
			$mimeType  = $file->getMimeType();
		
			$valid_extension = array("csv");
			$maxFileSize = 2097152;
			if(in_array(strtolower($extension),$valid_extension)){
				if($fileSize <= $maxFileSize){
					$location = ('uploads/csv'); /* Upload file */
					$file->move($location, $filename); /* Import CSV to Database */
					$filepath = ($location."/".$filename);
					$file = fopen($filepath,"r");  /* Reading file */
					$importData_arr = array();
					$i = 1;
					$flag = true;
					while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
						$num = count($filedata );
						if($flag) { $flag = false; continue; }
						for ($c=0; $c < $num; $c++) {
							$importData_arr[$i][] = $filedata [$c];
						}
						$i++;
					}
					fclose($file);
					foreach($importData_arr as $importData){
						$insertData[] = array(
							"brand_name"    => $importData[0],
							"merchant_name" => getAllStaffById($importData[1]),
							"sales_agent"   => getAllStaffById($importData[2]),
							"description"   => $importData[3],
							"created_at" 	=> date('Y-m-d H:i:s'),
							"import"        => 1
						);
					}
					$insert = DB::table('brands')->insert($insertData);
					/* echo "<pre>"; print_r($insertData); die; */
					Session::flash('success','CSV Imported Successfully.');
				}else{
					Session::flash('success','File too large. File must be less than 2MB.');
				}
			}else{
				Session::flash('success','Invalid File Extension.');
			}
		}
		return redirect('admin/brandlist');
	}
	
	public function downloadFile(){
		$file= public_path(). "/uploads/brand.csv";
		$headers = array(
			'Content-Type: application/csv',
		);
		/* echo "<pre>"; print_r($headers); die; */
		return Response::download($file, 'brand.csv', $headers);
	}
	
	public function searchBrand(Request $request){
	 /* pr($_POST); die; */
		$search_by = $request->brand_name;
    	if($request->has('brand_name')){
    		$brands = DB::table('brands')->orderBy('created_at', 'DESC')->where('brand_name','LIKE', '%' . $request->brand_name. '%')->paginate(20);
    	}else{
    		$brands = DB::table('brands')->paginate(20);
			$brands['search_by'] = $request->brand_name;
    	}
        return view('general.brand_management.brandlist', compact('brands'))->with('search_by', $search_by);
	}
	
	public function exportBrand(){
		ini_set('max_execution_time', 3600);
		ini_set('memory_limit','64M');	
	
		$filename = "all_brand.csv";
		$fp = fopen('php://output', 'w');
		
		//define column name
		$header = array(
			'brand_name',
			'merchant_name',
			'sales_agent', 
			'description'
		);	

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('brands')->select('brand_name', 'merchant_name', 'sales_agent', 'description')->orderBy('created_at', 'DESC')->get()->toarray();

		$i=0;
		foreach($data as $row){ 
			$i++;
			
			$rowData = array(
				$row['brand_name'],
				getAgentMerchant($row['merchant_name']),
				getAgentMerchant($row['sales_agent']), 
				$row['description']
			);	
			/* if($i==2){
				pr($rowData); die;
			} */
			fputcsv($fp, $rowData);			
		}
		exit;
	}
}