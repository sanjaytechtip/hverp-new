<?php
namespace App\Http\Controllers\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseInvoice;
use App\Item;
use DB;
use Session;
use PDF;
use Route;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailUser;

error_reporting(0);

use App\User;

class SapCodeFormController extends Controller
{
	function __construct(){
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

	
    
	 
    public function create() {
        //userPermission(Route::current()->getName());
        $object_itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        $itemlist = json_decode(json_encode($object_itemlist), true);
        //pr($itemlist); die;
		return view('general.sapcodeform.sapcodeformcreate',['datalist'=>$itemlist]);
    }
    
    public function edit_sapcode(Request $req,$id)
    {
     $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('_id', 'DESC')->limit(5)->get()->toArray(); 
     $data = DB::table('tbl_sapcode')->where('_id', $id)->first();
     //pr($data); die;
     return view('general.sapcodeform.sapcodeformedit',['datalist'=>$itemlist,'data'=>$data]);
    }
    
    public function sapcodelist(Request $req) {
        //userPermission(Route::current()->getName());
        if(!$_POST){
		$data = DB::table('tbl_sapcode')->orderBy('id', 'DESC')->paginate(50);
		$customer_name ='';
		$product_name = '';
        }else{
            //pr($req->all()); die;
            $query = DB::table('tbl_sapcode');
            if($req->customer_name!=''){
            $query->where('customer_id',$req->customer_name);  
            $customer_name =$req->customer_name; 
            }
            if($req->sap_code!=''){
            $query->where('sap_code', 'LIKE','%'.$req->sap_code.'%');  
            $product_name =$req->sap_code; 
            }
            $data =$query->orderBy('_id', 'DESC')->paginate(50);
        }
		//pr($data); die;
		return view('general.sapcodeform.sapcodelist',['datas'=>$data,'product_name'=>$product_name,'customer_name'=>$customer_name]);
    }
    
    public function sapcode_search(Request $request)
    {
       
            $query = DB::table('items')->where('user_role','!=','1');
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
            $query->where('is_verified',$request->is_verified);  
            $is_verified =$request->is_verified; 
            }
            $data_list =$query->orderBy('id', 'DESC')->get()->toArray();
            ?>
            <table class="table table-bordered t1" id="data-table">
              <thead>
                <th class="bg-info"><input id="ckbCheckAll" type="checkbox"></th>
                <th class="bg-info">Vendor SKU</th>
                <th class="bg-info">Group Name</th>
                <th class="bg-info">HSN Code</th>
                <th class="bg-info">Grade</th>
                <th class="bg-info">Brand</th>
                <th class="bg-info">Packing Name</th>
                <th class="bg-info">List Price</th>
                <th class="bg-info">MRP</th>
                <!--<th class="bg-info">Net Rate</th>-->
                <th class="bg-info">Stock</th>
                  </thead>
              <tbody id="results"> 
              <?php
            if(!empty($data_list))
            {
            foreach($data_list as $user)
            {
            ?>
            <tr>
                <td style="text-align:center;" class="white-space-normal"><input id="<?php echo $user[_id];?>" value="<?php echo $user['name'];?> (<?php echo $user['vendor_sku'];?>)" class="checkBoxClass" name="apply-radio" type="checkbox"></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['vendor_sku'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['name'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['hsn_code'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['grade'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['brand'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['packing_name'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['list_price'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['mrp'];?></td>
                <td style="text-align:center;" class="white-space-normal"><?php echo $user['stock'];?></td>
               </tr>
                
            <?php }}else{?>
            <tr>
                <td colspan="8">No record found.</td>
              </tr>
              </tbody>
            </table>
            <?php }
    }
    
    public function delete_sapcode(Request $req, $id)
    {
     //userPermission(Route::current()->getName());
     DB::table('tbl_sapcode')->where('id',$id)->delete();
     return redirect('admin/sapcodelist')->with('success', 'Sap Code deleted Successfully.');   
    }
    
    public function sapcode_data_update(Request $request, $id) {
            if($request->customer_name==''){
                        $customer_name = 0;
                    }else{
                        $customer_name = $request->customer_name;
                    }
                    $updateData = array(
                    "customer_id"       =>      $customer_name,
                    "customer_name"     =>      getCustomerNamefromId($customer_name),
                    "product_id"        =>      $request->product_id,
                    "product_name"      =>      $request->product,
                    "sap_code"          =>      $request->sap_code,
                    "updated_on"        =>      date('Y-m-d H:i:s')
                    );
                    
                    DB::table('tbl_sapcode')->where('_id',$id)->update($updateData);
                    return redirect('admin/sapcodelist')->with('success', 'SAP Code update Successfully.');
    }
    
    public function add_sapcode_form(Request $request)
    {
        //pr($request->all()); die;
                    if($request->customer_name==''){
                        $customer_name = 0;
                    }else{
                        $customer_name = $request->customer_name;
                    }
                    $insertData = array(
                    "customer_id"       =>      $customer_name,
                    "customer_name"     =>      getCustomerNamefromId($customer_name),
                    "product_id"        =>      $request->product_id,
                    "product_name"      =>      $request->product,
                    "sap_code"          =>      $request->sap_code,
                    "created_at"        =>      date('Y-m-d H:i:s'),
                    "updated_on"        =>      date('Y-m-d H:i:s')
                    );
                    $insert = DB::table('tbl_sapcode')->insert(array($insertData));
                    return redirect('admin/sapcodelist')->with('success', 'SAP Code added Successfully.');
    }
    
    
	


	
}