<?php
namespace App\Http\Controllers\SapCode;
use App\Models\Form;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use DB;
use Session;
use Route;
error_reporting(0);
class SapCodeFormController extends Controller
{
    
    public function index(Request $request) {
        if(!$_POST){
            $data = DB::table('tbl_sapcode')->orderBy('id', 'DESC')->paginate(50);
            $customer_name ='';
            $product_name = '';
            }else{
                //pr($request->all()); die;
                $query = DB::table('tbl_sapcode');
                if($request->customer_name!=''){
                $query->where('customer_id',$request->customer_name);  
                $customer_name =$request->customer_name; 
                }
                if($request->sap_code!=''){
                $query->where('sap_code', 'LIKE','%'.$request->sap_code.'%');  
                $product_name =$request->sap_code; 
                }
                $data =$query->orderBy('id', 'DESC')->paginate(50);
                
            }
            //pr($data); die;
            return view('general.sapcodeform.sapcodelist',['datas'=>$data,'product_name'=>$product_name,'customer_name'=>$customer_name]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        //$itemlist = json_decode(json_encode($object_itemlist), true);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
        //pr($itemlist);die;
        return view('general.sapcodeform.sapcodeformcreate',['datalist'=>$itemlist,'itemlist_count'=>$itemlist_count]);
        //pr($itemlist);die;
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
    public function edit(Request $request, $id)
    {
		
    }
	
	

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
       
    }

    public function add_sapcode_form(Request $request)
    {

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
        "sap_code"          =>      $request->sap_code
        );
        //pr($insertData); die;
        $insert = DB::table('tbl_sapcode')->insert(array($insertData));
        return redirect('admin/sapcodelist')->with('success', 'SAP Code added Successfully.');
    }

    public function edit_sapcode(Request $req,$id)
    {
        $itemlist = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->paginate(10);
        //$itemlist = json_decode(json_encode($object_itemlist), true);
        $itemlist_count = DB::table('items')->where('active_deactive', '1')->orderBy('id', 'DESC')->count();
     $object_data = DB::table('tbl_sapcode')->where('id', $id)->first();
     $data = json_decode(json_encode($object_data), true);
     //pr($data); die;
     return view('general.sapcodeform.sapcodeformedit',['datalist'=>$itemlist,'data'=>$data,'itemlist_count'=>$itemlist_count]);
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
                "sap_code"          =>      $request->sap_code
                );
                
                DB::table('tbl_sapcode')->where('id',$id)->update($updateData);
                return redirect('admin/sapcodelist')->with('success', 'SAP Code update Successfully.');
}

public function delete_sapcode(Request $req, $id)
{
 //userPermission(Route::current()->getName());
 DB::table('tbl_sapcode')->where('id',$id)->delete();
 return redirect('admin/sapcodelist')->with('success', 'Sap Code deleted Successfully.');   
}

}
   