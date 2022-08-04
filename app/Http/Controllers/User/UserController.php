<?php

namespace App\Http\Controllers\User;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Route;
error_reporting(0);
class UserController extends Controller
{
    public function index() {
        //userPermission(Route::current()->getName());
// 		if(!userHasRight()){
// 			return redirect()->route('dashboard');
// 		}
		$users=User::orderBy('id', 'DESC')->get();
        return view('user.userlist',compact('users'));
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
		
		$this->validate($request, [
			'name' 	=> ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255','unique:users'],
			'password' => ['required', 'string', 'min:6']
        ]);
        //print_r($_POST); die;
		User::create([
						'name' => $request->name,
						'email' => $request->email,
						'password' => Hash::make($request->password),
						'org_password' => $request->password,
						'phone' => $request->phone,
					]);
		
		return redirect('admin/userlist')->with('success', 'User has been created successfully');
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

}
