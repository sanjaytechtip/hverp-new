<?php
namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\General\ArticleModel;
use DB;
use Session;
use Intervention\Image\Facades\Image as Image;

class ArticleController extends Controller
{
	function __construct(){
		$this->articlemodel = new ArticleModel();
	}
	
    public function index() {
		checkPermission('view_purchasecode');
		$articles = DB::table('articles')->orderBy('created_at', 'DESC')->paginate(20);
		return view('general.article.articlelist')->with('articles', $articles)->with('search', '')->with('search_by', '');
    }

    public function create(Request $request) {
		checkPermission('add_purchasecode');
		return view('general.article.add_article');
    }

    public function store(Request $request) {
		include(app_path() . '/googlesheet/index.php');
		checkPermission('add_purchasecode');
        /*  $this->validate($request, [
			'article_no'   => 'required',
			'description'  => 'required',
			'factory_code' => 'required',
			'gsm' 		   => 'required',
			'width' 	   => 'required',
			'unit' 		   => 'required',
			'category' 	   => 'required',
		]);
		*/
		$this->validate($request, [
			'article_no'   => 'required'
		]);		
		
		if($request->fpt_report_img !=''){
			$image_fpt = $request->file('fpt_report_img');
			$imageName_fpt = $image_fpt->getClientOriginalName();
			$destinationPath = ('uploads/fpt_report_images');
			$image_fpt->move($destinationPath, $imageName_fpt);
		}else{
			$imageName_fpt = '';
		}
		
		if($request->garment_ref_img !=''){
			$image = $request->file('garment_ref_img');
			$imageName = $image->getClientOriginalName();
			/* $destinationPath = public_path('uploads/garment_ref_pic_images');
			// for live dev demo */ 
			$destinationPath = ('uploads/garment_ref_pic_images'); // For theme Forest
			$image->move($destinationPath, $imageName);
		}else{
			$imageName = '';
		}
		
		if($request->sample_card_type_img !=''){
			$image1 = $request->file('sample_card_type_img');
			$imageName1 = $image1->getClientOriginalName();
			/* $destinationPath = public_path('uploads/sample_card_images'); */
			$destinationPath = ('uploads/sample_card_images');
			$image1->move($destinationPath, $imageName1);
		}else{
			$imageName1 = '';
		}
		
		
		$application =''; $colour=''; $category=''; $article_no='';
		if(!empty($request->application)){
			$application = implode(',', $request->application);
		}
		
		if($request->colour!=''){
			
			$clrArrMain = explode(',',$request->colour);
			
			$clrArr = array();
			foreach($clrArrMain as $cl){
				$clr_name = trim($cl);
				$clr_name2 = str_replace(" ","-",$clr_name);
				array_push($clrArr, $clr_name2);	
			} 
			$colour = implode(',', $clrArr);
		}
		
		if(!empty($request->category)){
			$category = implode(',', $request->category);
		}
		
		//echo $colour;
		//pr($_POST); die();
		if($request->article_no!=''){
			$article = trim($request->article_no);
			$article_no = str_replace(" ","-",$article);
		}
		
		$data = array(
			"article_no"  	  => $article_no,
			"description" 	  => $request->description,
			"hsn_code"	  	  => $request->hsn_code,
			"factory_code"	  => $request->factory_code,
			"composition"	  => $request->composition,
			"fabric_finish"	  => $request->fabric_finish,
			"count_construct" => $request->count_construct,
			"gsm"		  	  => $request->gsm,
			"width"		  	  => $request->width,
			"unit"		  	  => $request->unit,
			"category"		  => $category,
			"invoice_desc"	  => $request->invoice_desc,
			"function_label"  => $request->function_label,
			"function_tag"	  => $request->function_tag,
			"date_of_item"	  => $request->date_of_item,
			"fpt_report"	  => $request->fpt_report,
			"fpt_report_img"  => $imageName_fpt,
			"garment_ref_img" => $imageName, // for img
			"garment_ref_pic" => $request->garment_ref_pic, // for url 
			"sample_card_img" => $imageName1,
			"sample_card"	  => $request->sample_card_type,
			"ref_article"	  => $request->ref_article,
			"application"	  => $application,
			"compliance"	  => $request->compliance,
			"location"		  => $request->location,
			"sample_size"	  => $request->sample_size,
			"test_method"	  => $request->test_method,
			"remark"		  => $request->remark,
			"colour"		  => $colour,
			"sales_stage"	  => $request->sales_stage,	
			"weave"	  		  => $request->weave,	
			"max_price"		  => $request->max_price,
			"min_price" 	  => $request->min_price,
			"date_of_pricing" => $request->date_of_pricing !=''? date('Y-m-d', strtotime($request->date_of_pricing)):'',
			"item_induction_date" => $request->item_induction_date !=''? date('Y-m-d', strtotime($request->item_induction_date)):'',
			"moq"				=>$request->moq,
			"lead_time" 		=> $request->lead_time,
			"mill_non_mill"		=> $request->mill_non_mill,
			"created_at" 	    => date('Y-m-d H:i:s')
		);
		//pr($data); die; 
		$insert = DB::table('articles')->insert(array($data));
		
		$current_date = date('Y-m-d H:i:s');
		dataspredsheet([[
			isEmptyCheck($current_date), 
			isEmptyCheck($request->article_no), 
			isEmptyCheck($request->description), 
			isEmptyCheck(getHSNDetails($request->hsn_code)), 
			isEmptyCheck($request->factory_code), 
			isEmptyCheck($request->composition), 
			isEmptyCheck($request->fabric_finish), 
			isEmptyCheck($request->count_construct),
			isEmptyCheck($request->gsm),
			isEmptyCheck($request->width),
			isEmptyCheck($request->unit),
			isEmptyCheck($category),
			isEmptyCheck($request->invoice_desc),
			isEmptyCheck($request->function_label),
			isEmptyCheck($request->function_tag),
			isEmptyCheck($request->date_of_item),
			isEmptyCheck($request->fpt_report),
			isEmptyCheck($request->garment_ref_pic),
			isEmptyCheck($request->sample_card_type),
			isEmptyCheck($request->ref_article),
			isEmptyCheck($application),
			isEmptyCheck($request->compliance),
			isEmptyCheck($request->location),
			isEmptyCheck($request->sample_size),
			isEmptyCheck($request->test_method),
			isEmptyCheck($request->remark),
			isEmptyCheck($colour)
		]],"1b9Hp3zKLHtr2zSTXcspXolVy8WuFDux-uXuldxdYMPI","A1!A:AA");
		if($insert){			
			Session::flash('success', 'Articles Added Successfully');
			return redirect('admin/articlelist');
		} else{ 
			return view('general.article.add_article');
		}
    }

    public function edit($id) {
		checkPermission('edit_purchasecode');
        $editArticle = DB::table('articles')->where('_id',$id)->first();
		return view('general.article.edit_article')->with('editArticle', $editArticle);
    }

    public function update(Request $request, $id) {
		checkPermission('edit_purchasecode');
        /* $this->validate($request, [
			'article_no'   => 'required',
			'description'  => 'required',
			'factory_code' => 'required',
			'gsm' 		   => 'required',
			'width' 	   => 'required',
			'unit' 		   => 'required',
			'category' 	   => 'required',
		]); */
		
		$this->validate($request, [
			'article_no'   => 'required'
		]);
		
		
		if($request->fpt_report_img !=''){
			$image_fpt = $request->file('fpt_report_img');
			$imageName_fpt = $image_fpt->getClientOriginalName();
			$destinationPath = ('uploads/fpt_report_images');
			$image_fpt->move($destinationPath, $imageName_fpt);
		}else{
			$imageName_fpt = '';
		}
		
		if($request->garment_ref_img !=''){
			$image = $request->file('garment_ref_img');
			$imageName = $image->getClientOriginalName();
			/* $destinationPath = public_path('uploads/garment_ref_pic_images'); */
			$destinationPath = ('uploads/garment_ref_pic_images');
			$image->move($destinationPath, $imageName);
		}else{
			$imageName = $request->garment_ref_img_hidden;
		}
		
		if($request->sample_card_img !=''){
			$image1 = $request->file('sample_card_img');
			$imageName1 = $image1->getClientOriginalName();
			/* $destinationPath = public_path('uploads/sample_card_images'); */
			$destinationPath = ('uploads/sample_card_images');
			$image1->move($destinationPath, $imageName1);
		}else{
			$imageName1 = $request->sample_card_img_hidden;
		}
		
		
		$application =''; $colour=''; $category=''; $article_no='';
		if(!empty($request->application)){
			$application = implode(',', $request->application);
		}else{
			$application ='';
		}
		
		/* if(!empty($request->colour)){
			$colour = implode(',', $request->colour);
		}else{
			$colour ='';
		} */
		
		if($request->colour!=''){
			
			$clrArrMain = explode(',',$request->colour);
			
			$clrArr = array();
			foreach($clrArrMain as $cl){
				$clr_name = trim($cl);
				$clr_name2 = str_replace(" ","-",$clr_name);
				array_push($clrArr, $clr_name2);	
			} 
			$colour = implode(',', $clrArr);
		}
		
		if(!empty($request->category)){
			$category = implode(',', $request->category);
		}
		
		
		if($request->article_no!=''){
			$article = trim($request->article_no);
			$article_no = str_replace(" ","-",$article);
		}
		
		$Update_Article = array(
			"article_no"  	  => $article_no,
			"description" 	  => $request->description,
			"hsn_code"	  	  => $request->hsn_code,
			"factory_code"	  => $request->factory_code,
			"composition"	  => $request->composition,
			"fabric_finish"	  => $request->fabric_finish,
			"count_construct" => $request->count_construct,
			"gsm"		  	  => $request->gsm,
			"width"		  	  => $request->width,
			"unit"		  	  => $request->unit,
			"category"		  => $category,
			"invoice_desc"	  => $request->invoice_desc,
			"function_label"  => $request->function_label,
			"function_tag"	  => $request->function_tag,
			"date_of_item"	  => $request->date_of_item,
			"fpt_report"	  => $request->fpt_report,
			"fpt_report_img" => $imageName_fpt,
			"garment_ref_img" => $imageName, // for img
			"garment_ref_pic" => $request->garment_ref_pic, // for url
			"sample_card_img" => $imageName1,
			"sample_card"	  => $request->sample_card,
			"ref_article"	  => $request->ref_article,
			"application"	  => $application,
			"compliance"	  => $request->compliance,
			"location"		  => $request->location,
			"sample_size"	  => $request->sample_size,
			"test_method"	  => $request->test_method,
			"remark"		  => $request->remark,
			"colour"		  => $colour,
			"sales_stage"	  => $request->sales_stage,
			"weave"	  		  => $request->weave,
			"max_price"		  => $request->max_price,
			"min_price" 	  => $request->min_price,
			"date_of_pricing" => $request->date_of_pricing !=''? date('Y-m-d', strtotime($request->date_of_pricing)):'',
			"item_induction_date" => $request->item_induction_date !=''? date('Y-m-d', strtotime($request->item_induction_date)):'',
			"moq"				=>$request->moq,
			"lead_time" 		=> $request->lead_time,
			"mill_non_mill"		=> $request->mill_non_mill,
			"updated_at" 	  => date('Y-m-d H:i:s')
		);
		/* pr($Update_Article); die; */
		DB::table('articles')->where('_id', $id)->update($Update_Article);
		Session::flash('success', 'Article updated successfully!');
		return redirect('admin/articlelist');
    }

    public function destroy($id) {
		checkPermission('delete_purchasecode');
        if($id!='') {
			DB::table('articles')->where('_id', '=', $id)->delete();
		}
		Session::flash('success', 'Article deleted successfully!');
		return redirect('admin/articlelist');
    }
	
	public function view($id) {
        $viewArticle = DB::table('articles')->where('_id',$id)->first();
		return view('general.article.view_article')->with('viewArticle', $viewArticle);
    }
	
	public function ArticleImport(Request $request) {
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
					while (($filedata = fgetcsv($file, 1000000, ",")) !== FALSE) {
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
							"article_no"  	  => $importData[0],
							"description" 	  => $importData[1],
							"hsn_code" 		  => getHSNIdByCode($importData[2]),
							"factory_code"    => $importData[3],
							"composition" 	  => $importData[4],
							"fabric_finish"   => $importData[5],
							"count_construct" => $importData[6],
							"gsm" 			  => $importData[7],
							"width" 		  => $importData[8],
							"unit" 			  => $importData[9],
							"category" 		  => trim($importData[10]),
							"invoice_desc" 	  => $importData[11],
							"function_label"  => $importData[12],
							"function_tag" 	  => $importData[13],
							"date_of_item" 	  => $importData[14],
							"fpt_report" 	  => $importData[15],
							"garment_ref_pic_type" => 1,
							"garment_ref_pic" => $importData[16],
							"sample_card_type"=> 1,
							"sample_card" 	  => $importData[17],
							"ref_article" 	  => $importData[18],
							"application" 	  => $importData[19],
							"compliance" 	  => $importData[20],
							"location" 		  => $importData[21],
							"sample_size" 	  => $importData[22],
							"test_method" 	  => $importData[23],
							"remark" 	      => $importData[24],
							"colour" 		  => $importData[25],
							"created_at" 	  => date('Y-m-d H:i:s'),
							"import"          => 1
						);
					}
					$insert = DB::table('articles')->insert($insertData);
					/* echo "<pre>"; print_r(array($insertData)); die; */
					Session::flash('success','CSV Imported Successfully.');
				}else{
					Session::flash('success','File too large. File must be less than 2MB.');
				}
			}else{
				Session::flash('success','Invalid File Extension.');
			}
		}
		return redirect('admin/articlelist');
	}
	
	public function searchArticle(Request $request){
		/* pr($_POST);die; */
		$search=$request->article;
		$search_by=trim($request->hsn_code);
		if($request->has('hsn_code')){
			$articles = DB::table('articles')->where($request->article,'LIKE', '%' .$request->hsn_code. '%')->paginate(200); 
    	}else{
    		$articles = DB::table('articles')->paginate(200);
			$articles['search']=$request->article;
			$articles['search_by']=$request->hsn_code;
    	}
		return view('general.article.articlelist', compact('articles'))->with('search', $search)->with('search_by', $search_by);
        /* return view('general.article.articlelist', compact('articles')); */
	}
	
	public function export_article(){
		ini_set('max_execution_time', 3600);
		ini_set('memory_limit','64M');
		
		$data_hsn = DB::table('hsncode')->select('hsn_code','gstn')->get()->toarray();		
		$hsnRow=array();
		$i=0;
		foreach($data_hsn as $res){ $i++;
			//pr($res); die();
			$oid = (array) $res['_id'];
			$id = $oid['oid'];
			$hsnRow[$id]= array(
				'hsn_code' => $res['hsn_code'],
				'gstn' => $res['gstn']
			);
		} 
		//pr($hsnRow); die();	
	
		$filename = "all_article.csv";
		$fp = fopen('php://output', 'w');
		
		//define column name
		$header = array('article_no','description', 'hsn_code', 'factory_code', 'composition', 'fabric_finish', 'count_construct', 'gsm', 'width', 'unit', 'category', 'invoice_desc', 'function_label', 'function_tag', 'date_of_item', 'fpt_report', 'garment_ref_pic', 'sample_card', 'ref_article', 'application', 'compliance', 'location', 'sample_size', 'test_method', 'remark', 'colour');	

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		
		$data = DB::table('articles')->select('article_no', 'description', 'hsn_code', 'factory_code', 'composition', 'fabric_finish', 'count_construct', 'gsm', 'width', 'unit', 'category', 'invoice_desc', 'function_label', 'function_tag', 'date_of_item', 'fpt_report', 'garment_ref_pic', 'sample_card', 'ref_article', 'application', 'compliance', 'location', 'sample_size', 'test_method', 'remark', 'colour')->orderBy('created_at', 'DESC')->get()->toarray();

		$i=0;
		foreach($data as $row){ 
			$i++;
			$hsn_code = '';
			if($row['hsn_code']!='' && array_key_exists($row['hsn_code'],  $hsnRow)){
				$hsn_code = $hsnRow[$row['hsn_code']]['hsn_code'];
			}else{
				$hsn_code = '';
			}
			
			$rowData = array(
				$row['article_no'],
				$row['description'],
				$hsn_code, 
				$row['factory_code'], 
				$row['composition'], 
				$row['fabric_finish'], 
				$row['count_construct'], 
				$row['gsm'], 
				$row['width'],
				$row['unit'],
				$row['category'],
				$row['invoice_desc'],
				$row['function_label'],
				$row['function_tag'],
				$row['date_of_item'],
				$row['fpt_report'],
				$row['garment_ref_pic'],
				$row['sample_card'],
				$row['ref_article'],
				$row['application'],
				$row['compliance'],
				$row['location'],
				$row['sample_size'],
				$row['test_method'],
				$row['remark'],
				$row['colour']
			);	
			/* if($i==2){
				pr($rowData); die;
			} */
			fputcsv($fp, $rowData);			
		}
		exit;
	}
	
}