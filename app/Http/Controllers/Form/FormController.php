<?php

namespace App\Http\Controllers\Form;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use DB;
use Session;
use Route;
error_reporting(0);
@ini_set('memory_limit','12048M');
class FormController extends Controller
{
    public function createform(Request $request) {
		return view('form_management.createform');
    }
    public function index() {
        //userPermission(Route::current()->getName());
// 		if(!userHasRight()){
// 			return redirect()->route('dashboard');
// 		}
		
        $forms = Form::orderBy('id', 'DESC')->get();
		return view('form_management.formlist')->with('forms', $forms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		return view('user.user_register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		
		//pr($request->all()); die;
		
        $data = array(
        'form_name' 		    => $request->form_name,
        'module_name'	        => $request->module_name,
        'table_name'            => $request->table_name,
        'is_Import'             => $request->import,
        'is_export'             => $request->export_data
        );
		$insert_id = Form::insertGetId($data);

        if(!empty($request->row)){
            $i=1;
            $field_name_array = array();
			foreach($request->row as $key=>$row){
				$form_data = array(
                    'f_id'                  => $insert_id,
                    'parent_id'             => 0,
                    'field_type'            => $row['field_type'],
                    'field_type_value'      => $row['field_type_value'],
                    'label_name'            => $row['label_name'],
                    'field_name'            => $row['field_name'],
                    'is_database_field'     => $row['is_database_field'],
                    'field_length'          => $row['field_length'],
                    'is_nullable'           => $row['is_nullable'],
                    'is_array'              => $row['is_array'],
                    'is_unique'             => $row['is_unique'],
                    'is_readonly'           => $row['is_readonly'],
                    'min_length'            => $row['min_length'],
                    'max_length'            => $row['character_limit'],
                    'layout'                => $row['layout'],
                    'html_id'               => $row['field_id'],
                    'html_class'            => $row['field_class'],
                    'placeholder'           => $row['placeholder_text'],
                    'field_instructions'    => $row['field_instructions'],
                    'is_required'           => $row['is_required'],
                    'required_msg'          => $row['required_message'],
                    'restrictions'          => $row['restrictions'],
                    'show_in_list'          => $row['show_list'],
                    'is_disable'            => $row['is_disable'],
                    'conditional'           => $row['conditional'],
                    'field_order'           => $i
                );

                
                $p_insert_id = DB::table('tbl_form_data')->insertGetId($form_data);

                //Repeater Field
                if(!empty($row['repeat'])){
                    $k=1;
                    foreach($row['repeat'] as $key=>$repeat){
                        $form_data_re = array(
                            'f_id'                  => $insert_id,
                            'parent_id'             => $p_insert_id,
                            'field_type'            => $repeat['field_type'],
                            'field_type_value'      => $repeat['field_type_value'],
                            'label_name'            => $repeat['label_name'],
                            'field_name'            => $repeat['field_name'],
                            'is_database_field'     => $repeat['is_database_field'],
                            'field_length'          => $repeat['field_length'],
                            'is_nullable'           => $repeat['is_nullable'],
                            'is_array'              => $repeat['is_array'],
                            'is_unique'             => $repeat['is_unique'],
                            'is_readonly'           => $repeat['is_readonly'],
                            'min_length'            => $repeat['min_length'],
                            'max_length'            => $repeat['character_limit'],
                            'layout'                => $repeat['layout'],
                            'html_id'               => $repeat['field_id'],
                            'html_class'            => $repeat['field_class'],
                            'placeholder'           => $repeat['placeholder_text'],
                            'field_instructions'    => $repeat['field_instructions'],
                            'is_required'           => $repeat['is_required'],
                            'required_msg'          => $repeat['required_message'],
                            'restrictions'          => $repeat['restrictions'],
                            'show_in_list'          => $repeat['show_list'],
                            'is_disable'            => $repeat['is_disable'],
                            'conditional'           => $repeat['conditional'],
                            'field_order'           => $k
                        );
                     $k++;
                     $last_sub_parent_id = DB::table('tbl_form_data')->insertGetId($form_data_re);
                     if(!empty($repeat['repeat_new'])){
                        $j=1;
                        foreach($repeat['repeat_new'] as $key=>$repeat_new){
                            $form_data_re_new = array(
                                'f_id'                  => $insert_id,
                                'parent_id'             => $p_insert_id,
                                'sub_parent_id'         => $last_sub_parent_id,
                                'field_type'            => $repeat_new['field_type'],
                                'field_type_value'      => $repeat_new['field_type_value'],
                                'label_name'            => $repeat_new['label_name'],
                                'field_name'            => $repeat_new['field_name'],
                                'is_database_field'     => $repeat_new['is_database_field'],
                                'field_length'          => $repeat_new['field_length'],
                                'is_nullable'           => $repeat_new['is_nullable'],
                                'is_array'              => $repeat_new['is_array'],
                                'is_unique'             => $repeat_new['is_unique'],
                                'is_readonly'           => $repeat_new['is_readonly'],
                                'min_length'            => $repeat_new['min_length'],
                                'max_length'            => $repeat_new['character_limit'],
                                'layout'                => $repeat_new['layout'],
                                'html_id'               => $repeat_new['field_id'],
                                'html_class'            => $repeat_new['field_class'],
                                'placeholder'           => $repeat_new['placeholder_text'],
                                'field_instructions'    => $repeat_new['field_instructions'],
                                'is_required'           => $repeat_new['is_required'],
                                'required_msg'          => $repeat_new['required_message'],
                                'restrictions'          => $repeat_new['restrictions'],
                                'show_in_list'          => $repeat_new['show_list'],
                                'is_disable'            => $repeat_new['is_disable'],
                                'conditional'           => $repeat_new['conditional'],
                                'field_order'           => $k
                            );
                         $j++;
                         DB::table('tbl_form_data')->insertGetId($form_data_re_new);
    
                        if($repeat_new['is_database_field']==1){
                            $field_name_array[$repeat_new['field_name']] = getDynamicTable($repeat_new['field_type'],$repeat_new['field_name'],$repeat_new['field_length'],$repeat_new['is_nullable']); 
                        }
                        }
                       
                    }

                    if($repeat['is_database_field']==1){
                        $field_name_array[$repeat['field_name']] = getDynamicTable($repeat['field_type'],$repeat['field_name'],$repeat['field_length'],$repeat['is_nullable']); 
                    }
                    }
                   
                }
                //
                if($row['is_database_field']==1){
                    $field_name_array[$row['field_name']] = getDynamicTable($row['field_type'],$row['field_name'],$row['field_length'],$row['is_nullable']); 
                }
                    $i++;
			}
             //pr($field_name_array); die;
            Schema::create($request->table_name, function($table) use ($field_name_array)
            {
            $table->engine = 'InnoDB'; 
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci'; 
            $table->increments('id');
            foreach($field_name_array as $key=>$array_name)
            {
            $field_name = $array_name['field_name'];
            $type       = $array_name['type'];
            $length     = $array_name['length'];
            $is_nullable= $array_name['is_nullable'];
            if($is_nullable==1){
            $table->$type($field_name,$length)->nullable();
            }else{
            $table->$type($field_name,$length);    
            }
            }
            //$table->timestamps();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            });

		}
        //die;
        return redirect()->route('formlist')->with('success', 'Form added successfully.');
        
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
		$user = User::find($id);
        return view('user.user_edit', compact('user', 'id'));
    }
	
	public function update(Request $request, $id) {
	    //pr($request->all());die;
		/* dd($_POST); dd($_FILES); die; */
		$this->validate($request, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255'],
        ]);
		
		$user= User::find($id);
        $user->name = $request->get('name');       
        $user->email = $request->get('email'); 
        $user->phone = $request->get('phone');  
	
		//echo '<pre>';print_r($user); die;
        $user->save();
        
		return redirect()->route('useredit', $id)->with('success', 'User has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user= User::find($id);
        $user->delete();
        return redirect('admin/userlist')->with('success','User has been deleted sucessfully!');
    }


    public function listdata(Request $request, $id) {
        $tbl_column = '';
        $i_name = '';
        $forms = Form::where('id', $id)->get()->toArray();
        //pr($forms); die;
        $table_name = $forms[0]['table_name'];
        if(!empty($_POST)){
            if($forms[0]['id']==35){
                $query = DB::table($table_name);
                if($request->vendor_sku!=''){
                $query->where('vendor_sku', 'LIKE', checkChar($request->vendor_sku));  
                $vendor_sku =$request->vendor_sku; 
                }
                if($request->name!=''){
                $query->where('name', 'LIKE', checkChar($request->name));  
                $name =$request->name; 
                }

                if($request->synonyms!=''){
                $query->where('synonyms', 'LIKE', '%'.$request->synonyms.'%');  
                $synonyms =$request->synonyms; 
                }

                if($request->grade!=''){
                $query->where('grade', 'LIKE', checkChar($request->grade));  
                $grade =$request->grade; 
                }
                if($request->brand!=''){
                $query->where('brand', 'LIKE', checkChar($request->brand));  
                $brand =$request->brand; 
                }
                if($request->packing_name!=''){
                $query->where('packing_name', 'LIKE', checkChar($request->packing_name));  
                $packing_name =$request->packing_name; 
                }
                if($request->hsn_code!=''){
                $query->where('hsn_code', 'LIKE', checkChar($request->hsn_code));  
                $hsn_code =$request->hsn_code; 
                }
                if($request->is_verified!=''){
                $query->where('is_verified', 'LIKE', checkChar($request->is_verified));  
                $is_verified =$request->is_verified; 
                }
                $data_list =$query->orderBy('id', 'DESC')->paginate(100);
            }else{
        $data_list = DB::table($table_name)->where($request->tbl_column, 'LIKE', '%'.$request->i_name.'%')->orderBy('id', 'DESC')->paginate(100);
            }
        //$data_list = json_decode(json_encode($object), true);
        $tbl_column = $request->tbl_column;
        $i_name = $request->i_name; 
        }else{
        $data_list = DB::table($table_name)->orderBy('id', 'DESC')->paginate(100);
        //$data_list = json_decode(json_encode($object), true);
        
        //pr($data_list); die;
        $vendor_sku ='';
        $name ='';
        $grade = '';
        $brand = '';
        $packing_name ='';
        $hsn_code = '';
        $is_verified = '';
        $synonyms = '';
    }
        $module_data = getModuleList($forms[0]['id']);
         
		if(!empty($module_data)){
			$finalArr= array();
			foreach($module_data as $row){
				if(isset($row['show_list']) &&  $row['show_list']==1){
					$finalArr[$row['field_name']] = $row['label_name'];					
				}
			}
		}
        //pr($module_data);
        return view('form_management.viewformlist',['datalist'=>$data_list, 'finalArr'=>$finalArr,'forms'=>$forms,'tbl_column'=>$tbl_column,'i_name'=>$i_name,'vendor_sku'=>$vendor_sku,'name'=>$name,'grade'=>$grade,'brand'=>$brand,'packing_name'=>$packing_name,'hsn_code'=>$hsn_code,'is_verified'=>$is_verified,'synonyms'=>$synonyms]);
    }


    public function adddata($id) {
        $object = Form::where('id',$id)->first();
        $adddata = json_decode(json_encode($object), true);
        //pr($adddata); die;
        return view('form_management.adddata',['adddata'=>$adddata]);
    }

    public function formupdate(Request $request, $form_id)
    {  
        //pr($request->all()); die;
        $forms = DB::table('tbl_form')->where('id', $form_id)->first();
        //pr($forms); die;
        $image = array();
        if(!empty($request->file())) {
            foreach($request->file() as $key=>$files)
            {
            $fileName = time().'_'.$request->$key->getClientOriginalName();
            request()->$key->move(public_path('uploads/images'), $fileName);
            $image[$key]=$fileName;
            }
            
        }
        
        $data = array_merge($_POST,$image);
        //pr($data); die;
        postInsertData($data,$_POST['table_name']);
        return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data added successfully.');
        
    }

    public function editdata(Request $request, $table, $id, $form_id)
    {
     $object = DB::table($table)->where('id',$id)->first(); 
     $user_edit_details = json_decode(json_encode($object), true);
     //pr($user_edit_details); die;
     $forms = Form::where('id', $form_id)->get()->toArray();
     //pr($forms); die;
     return view('form_management.vieweditdatalist',['user_edit_details'=>$user_edit_details, 'forms'=>$forms,'table'=>$table,'id'=>$id,'form_id'=>$form_id]);
    }

    public function edit_formupdate(Request $request, $id, $form_id)
    {
        //pr($request->all());die;
        $object = DB::table('tbl_form')->where('id', $form_id)->get();
        $forms = json_decode(json_encode($object), true);
        $image = array();
        if(!empty($request->file())) {
            foreach($request->file() as $key=>$files)
            {
            $fileName = time().'_'.$request->$key->getClientOriginalName();
            request()->$key->move(public_path('uploads/images'), $fileName);
            $image[$key]=$fileName;
            }
            
        }
        
        $data = array_merge($_POST,$image);
        if($form_id=='617fe586d049045cf838a502')
        {
        brandUpdate($data,$id);
        }
        if($form_id=='617bdabdd0490401e7794262')
        {
        gradeUpdate($data,$id);
        }
        if($form_id=='617bbfc3d049044bde0079b2')
        {
        HSNcodeUpdate($data,$id);
        }
       
        postUpdateData($data,$forms[0]['table_name'],$id);
        return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data updated successfully.');
    }
    
    public function deletedata(Request $request, $table, $id, $form_id)
    {
    if($id!='') 
    {
	DB::table($table)->where('id', '=', $id)->delete();
	}
    return redirect()->to(url('admin/listdata/'.$form_id))->with('success', 'Data deleted successfully.');   
    }

    function uniqueValueCheck(Request $req)
	     {
	       //echo strtoupper($req->input_val); die;
	       $object = DB::table($req->table_name)->where($req->field_name,$req->input_val)->orWhere($req->field_name,strtolower($req->input_val))->orWhere($req->field_name,strtoupper($req->input_val))->orWhere($req->field_name,ucwords($req->input_val))->get()->first();
	       //pr($data); die;
           $data = json_decode(json_encode($object), true);
	       $data_name = strtolower($data[$req->field_name]);
	       if($data_name==strtolower($req->input_val)){
	           echo 1;
	       }else{
	           echo 0;
	       }
	     }

         function uniqueValueCheckEdit(Request $req)
	     {
	       $object = DB::table($req->table_name)->where('id',$req->id)->get()->first();
           $data_original = json_decode(json_encode($object), true);
	       $object = DB::table($req->table_name)->where($req->field_name,$req->input_val)->orWhere($req->field_name,strtolower($req->input_val))->orWhere($req->field_name,strtoupper($req->input_val))->orWhere($req->field_name,ucwords($req->input_val))->get()->first();
	       //pr($data); die;
           $data = json_decode(json_encode($object), true);
	       $data_name = strtolower($data[$req->field_name]);
	       if($data_name==strtolower($req->input_val) && $data['id']!=$req->id){
	           echo $data_original[$req->field_name];
	       }else{
	           echo 0;
	       }
	     }

         public function formedit($id) {
            $object = DB::table('tbl_form')->where('id', $id)->get();
            $forms_data = json_decode(json_encode($object), true);
            //pr($forms_data); die;
            $tbldataCount = DB::table('tbl_form')->where('id', $id)->count();
            $object_form_data = DB::table('tbl_form_data')->where('f_id', $forms_data[0]['id'])->get();
            $forms = json_decode(json_encode($object_form_data), true);
            //pr($forms); die;
            return view('form_management.vieweditform',['forms'=>$forms,'forms_data'=>$forms_data,'tbldataCount'=>$tbldataCount]);
        }

        public function emailchecker(Request $request)
        {
             //echo 1;
             //pr($request->all()); die;
            if($request->email!='')
            {
             $object = DB::table($request->table_name)->select('email')->where('email', $request->email)->first(); 
             $data = json_decode(json_encode($object), true);
             $email = $data['email'];
             if($email==''){
                 echo 'true';
             }else{
                 echo 'false';
             }
            }
        }
    public function hsn_task_rate(Request $request)
    {
        $object = DB::table('hsn_taxes')->where('hsn_code',$request->hsn_code_val)->get()->first();
        $data = json_decode(json_encode($object), true);
        return $data['tax'];
    }


    public function import_data_master_items(Request $req)
	{
	    
	   if(isset($_POST['importSubmit'])){
	       $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
	       if(!empty($_FILES['result_file']['name']) && in_array($_FILES['result_file']['type'], $csvMimes)){
	           if(is_uploaded_file($_FILES['result_file']['tmp_name'])){
	                     //header('Content-Type: text/html; charset=UTF-8');
	                     $csv = array_map("str_getcsv", file($_FILES['result_file']['tmp_name'],FILE_SKIP_EMPTY_LINES));
	                     //pr($csv); die;
	                     $file = fopen($_FILES['result_file']['name'], "r");
                         $keys = array_shift($csv);
                         $data = $this->import_insert_data_items($csv,$req->table_name,$keys);
                         //pr($data); die;
                         return redirect()->to(url('admin/listdata/'.$req->id))->with('success', $data['msg']);
	                 }
	              }
	           }
	     }




         public function import_insert_data_items($data,$table_name,$keys)
         {
           //pr($data); die;
           $arr = array();
           $msg = array();
           $update_array = array();
           $insert_array = array();
           $d = array();
           foreach($data as $key=>$datas){
           $datas_array = array_combine($keys,$datas);
           array_push($arr,$datas_array);
           }   
           //pr($arr);die;
           
           if(!empty($arr)){
               //pr($arr); die;
           foreach($arr as $ar){
               //echo 1; die;
               checkGrade(checkNullDatas(utf8_encode($ar['grade'])));
               checkBrand(checkNullDatas(utf8_encode($ar['brand'])));
               checkHSNCodeTax(checkNullDatas($ar['hsn_code']),$ar['tax_rate']);
               $vendor_sku = utf8_encode($ar['vendor_sku']);
               $datacheck = DB::table('items')->where('vendor_sku', '=', $vendor_sku)->get()->count();
               if($datacheck==1){
                 array_push($update_array,$vendor_sku);
               }else{
                 array_push($insert_array,$vendor_sku);
                 $data = array('active_deactive'=>$ar['active_deactive'],'vendor_sku'=>mbConvertEncoding($ar['vendor_sku']),'name'=>mbConvertEncoding($ar['name']),'grade'=>checkNullDatas(mbConvertEncoding($ar['grade'])),'brand'=>checkNullDatas(mbConvertEncoding($ar['brand'])),'packing_name'=>checkNullDatas(mbConvertEncoding($ar['packing_name'])),'hsn_code'=>checkNullDatas(mbConvertEncoding($ar['hsn_code'])),'mrp'=>checkNullDatas($ar['mrp']),'stock'=>checkNullDatas($ar['stock']),'is_verified'=>$ar['is_verified'],'tax_rate'=>checkNullDatas($ar['tax_rate']),'description'=>checkNullDatas($ar['description']),'list_price'=>checkNullDatas($ar['list_price']),'minimum_order_pack'=>checkNullDatas($ar['minimum_order_pack']),'net_rate'=>checkNullDatas($ar['net_rate']),'pack_size'=>checkNullDatas($ar['pack_size']),'shelf_life'=>checkNullDatas($ar['shelf_life']),'specific_gravity'=>checkNullDatas($ar['specific_gravity']),'storage_conditions'=>checkNullDatas($ar['storage_conditions']),'sub_type'=>checkNullDatas($ar['sub_type']),'synonyms'=>checkNullDatas($ar['synonyms']),'type'=>checkNullDatas($ar['type']),'unit'=>checkNullDatas($ar['unit']),'unit_price'=>checkNullDatas($ar['unit_price']));
                 DB::table($table_name)->insert(array($data));  
               }
               
           }  
           }
           
           if($table_name!='items'){
           $msg['msg'] = 'Data imported successfully.';   
           }else{
           if(count($update_array)>0){
           $msg_update_array = count($update_array).' Records ('.implode(', ',$update_array).') already exist.';
           }else{
           $msg_update_array = '';    
           }
           $msg['msg'] = count($insert_array).' Records imported successfully.'. $msg_update_array;
           }
           
           return $msg;
         }

         public function export_data_master_item(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['_id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    //$header = array_keys(end($arr));
	    $header = array('active_deactive','vendor_sku','name','grade','brand','packing_name','hsn_code','mrp','stock','tax_rate','description','list_price','minimum_order_pack','net_rate',
	    'pack_size','shelf_life','specific_gravity','storage_conditions','sub_type','synonyms','type','unit','unit_price','is_verified');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $ar = array();
        foreach($array as $data){
            $d1['active_deactive']= $data['active_deactive'];
            $d1['vendor_sku']= $data['vendor_sku'];
            $d1['name']= $data['name'];
            $d1['grade']= $data['grade'];
            $d1['brand']= $data['brand'];
            $d1['packing_name']= $data['packing_name'];
            $d1['hsn_code']= $data['hsn_code'];
            $d1['mrp']= $data['mrp'];
            $d1['stock']= $data['stock'];
            $d1['tax_rate']= $data['tax_rate'];
            $d1['description']= $data['description'];
            $d1['list_price']= $data['list_price'];
            $d1['minimum_order_pack']= $data['minimum_order_pack'];
            $d1['net_rate']= $data['net_rate'];
            $d1['pack_size']= $data['pack_size'];
            $d1['shelf_life']= $data['shelf_life'];
            $d1['specific_gravity']= $data['specific_gravity'];
            $d1['storage_conditions']= $data['storage_conditions'];
            $d1['sub_type']= $data['sub_type'];
            $d1['synonyms']= $data['synonyms'];
            $d1['type']= $data['type'];
            $d1['unit']= $data['unit'];
            $d1['unit_price']= $data['unit_price'];
            $d1['is_verified']= $data['is_verified'];
            fputcsv($fp, $d1);
        }
        
	}

    function ajax_item_details(Request $req)
	     {
	       $data = DB::table('items')->where('id',$req->id)->get()->first();
           $data_original = json_decode(json_encode($data), true);
	       $html = '';
	       $html .= '<tr>';
	       $html .= '<th>Status</th>';
	       if($data_original['active_deactive']==1){
	       $html .= '<td>Active</td>';
	       }else{
	       $html .= '<td>Deactive</td>';    
	       }
	       $html .= '<th>Synonyms</th>';
	       $html .= '<td>'.$data_original['synonyms'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Tax Rate</th>';
	       $html .= '<td>'.$data_original['tax_rate'].'</td>';
	       $html .= '<th>Description</th>';
	       $html .= '<td>'.$data_original['description'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Item Type</th>';
	       $html .= '<td>'.$data_original['type'].'</td>';
	       $html .= '<th>Item Sub type</th>';
	       $html .= '<td>'.$data_original['sub_type'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>COA Applicable?</th>';
	       $html .= '<td>'.$data_original['coa_applicable'].'</td>';
	       $html .= '<th>Unit</th>';
	       $html .= '<td>'.$data_original['unit'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Pack Size</th>';
	       $html .= '<td>'.$data_original['pack_size'].'</td>';
	       $html .= '<th>Unit Price</th>';
	       $html .= '<td>'.$data_original['unit_price'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Specific Gravity <br/>(optional in case of chemicals)</th>';
	       $html .= '<td>'.$data_original['specific_gravity'].'</td>';
	       $html .= '<th>Minimum Order Pack</th>';
	       $html .= '<td>'.$data_original['minimum_order_pack'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Storage Conditions</th>';
	       $html .= '<td>'.$data_original['storage_conditions'].'</td>';
	       $html .= '<th>EUD <br/>(End User Declaration)</th>';
	       $html .= '<td>'.$data_original['eud'].'</td>';
	       $html .= '</tr>';
	       
	       $html .= '<tr>';
	       $html .= '<th>Shelf Life</th>';
	       $html .= '<td>'.$data_original['shelf_life'].'</td>';
	       $html .= '<th>Is Verified</th>';
	       $html .= '<td>'.$data_original['is_verified'].'</td>';
	       $html .= '</tr>';
	       
	       return $html;  
	     }

         public function export_data_master(Request $req, $id, $table_name, $limit)
	{
	  if($limit==0){
	  $data = DB::table($table_name)->orderBy('id','DESC')->get();
	  }else{
	  $data = DB::table($table_name)->orderBy('id','DESC')->limit(intval($limit))->get();   
	  }
	  $array = json_decode(json_encode($data), true);
	  //pr($array); die;
	  $filename = $table_name.".csv";
	  $fp = fopen('php://output', 'w');
	  $arr = array();
	  foreach($array as $key=>$d){
	  unset($d['_id'],$d['created_on']);
	  array_push($arr,$d);
	  }
	  
	    $header = array_keys(end($arr));
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $ar = array();
        foreach($arr as $k=>$d1){
            fputcsv($fp, $d1);
        }
        
	}

    public function export_data_master_update(Request $req)
	{
	 $filename = "Item_update.csv";  
	 $fp = fopen('php://output', 'w');
	 $header = array("vendor_sku","mrp","unit_price");
	 header('Content-type: application/csv');
     header('Content-Disposition: attachment; filename='.$filename);
     fputcsv($fp, $header);
     $arr = array(array('vendor_sku'=>'3345dffd','mrp'=>'20000','unit_price'=>'2000'));
     foreach($arr as $k=>$d1){
            fputcsv($fp, $d1);
        }
    fclose($fp);
	}

    public function import_data_master_update(Request $req)
	{
	   if(isset($_POST['importSubmit'])){
	       $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
	       if(!empty($_FILES['result_file']['name']) && in_array($_FILES['result_file']['type'], $csvMimes)){
	           if(is_uploaded_file($_FILES['result_file']['tmp_name'])){
	                     $csv = array_map("str_getcsv", file($_FILES['result_file']['tmp_name'],FILE_SKIP_EMPTY_LINES));
                         $keys = array_shift($csv);
                         $data = $this->import_insert_data_update($csv,$req->table_name,$keys);
                         //pr($data); die;
                         return redirect()->to(url('admin/listdata/'.$req->id))->with('success', $data['msg']);
	                 }
	              }
	           }
	     }

         public function import_insert_data_update($data,$table_name,$keys)
	{
	  $update_array = array();
	  $arr = array();
	  $msg = array();
	  foreach($data as $datas){
	  $datas_array = array_combine($keys,$datas);
	  $datas_array['created_on'] = date('Y-m-d h:i:s');
	  array_push($arr,$datas_array);
	  }
	  //pr($arr); die;
	  if($table_name!='items'){
	  DB::table($table_name)->insert($arr); 
	  }else{
	  if(!empty($arr)){
	  foreach($arr as $ar){
	      $datacheck = DB::table('items')->where('vendor_sku', '=', $ar['vendor_sku'])->get()->count();
	      if($datacheck==1){
	        array_push($update_array,$ar['vendor_sku']);
	        $data = array('vendor_sku'=>$ar['vendor_sku'],'mrp'=>checkNullDatas($ar['mrp']),'unit_price'=>checkNullDatas($ar['unit_price']),'updated_on'=> date('Y-m-d H:i:s'));
	        //pr($data); 
	        DB::table($table_name)->where('vendor_sku', '=', $ar['vendor_sku'])->update($data);  
	      }
	  }  
	  }
	  }
	  
	  
	  if(count($update_array)>0){
	  $msg_update_array = count($update_array).' Records Updated Successfully.';
	  }
	  $msg['msg'] = $msg_update_array;
	  return $msg;
	}

    function mrp_data_update(Request $req)
	     {
	         $data = array('mrp'=>$req->mrp_update);
	         DB::table('items')->where('id',$req->mrp_id)->update($data);
	         return $req->mrp_update;
	     }

         
    function list_price_data_update(Request $req)
    {
        $data = array('list_price'=>$req->list_price_update);
        DB::table('items')->where('id',$req->list_price_id)->update($data);
        return $req->list_price_update;
    }
		 
	public function userassign($id)
	{
	$users_assign = DB::table('roles')->whereNotNull('role_category','<>','')->orderBy('order','asc')->get();
	//pr($users_assign); die;
	$user_data = User::where('id',$id)->first();
	$added_modules = $user_data->userrole;
	//pr($added_modules); die;
	return view('user.user_assign',['users_assign'=>$users_assign,'user_id'=>$id,'added_modules'=>$added_modules]);
	}

    public function ajax_user_status_update(Request $request)
    {
      $update = array('status'=>$request->status);
      DB::table('users')->where('id',$request->id)->update($update);
      echo 1;
    }

    public function ajax_get_item_tax_rate(Request $request)
    {
     $id = $request->mrp_id;
     $object = DB::table('items')->select('hsn_code')->where('id',$id)->get()->first();
     $data = json_decode(json_encode($object), true);
     $hsn_code = $data['hsn_code'];
     if($hsn_code!='')
     {
        $object_hsn_tax = DB::table('hsn_taxes')->select('tax')->where('hsn_code',$hsn_code)->get()->first();
        $data_hsn_tax = json_decode(json_encode($object_hsn_tax), true);
        return $data_hsn_tax['tax'];  
     }
    }

}