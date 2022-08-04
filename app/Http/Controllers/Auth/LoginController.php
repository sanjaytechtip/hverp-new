<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;

use Session;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
	protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	/* public function logout(Request $request)
	{
		return view('auth.login');
	} */ 
	
	/* public function testing() {
	  die('tttttt');
	}
	
	public function logout() {
	  Auth::logout();
	  return redirect('/login');
	} */
	
	public function logout()
    {
		Session::flush();
        
        Auth::logout();

        return redirect('login');
    }
	
	public function showLoginForm()
	{
		return view('auth.login');
	} 

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
  ]);
  $credentials = $request->only('email', 'password');
 
  if (Auth::attempt($credentials)) {
    if(Auth::check() && Auth::user()->status != 1){
      Auth::logout();
      return redirect('login')->with('success','Your account is blocked. Please contact with administrator.')->withInput();
      
    }

    if($request->remember===null){
    setcookie('login_email',$request->email,100);
    setcookie('login_pass',$request->password,100);
    }
    else
    {
    setcookie('login_email',$request->email,time()+60*60*24*100);
    setcookie('login_pass',$request->password,time()+60*60*24*100);
    }
    return redirect()->intended('admin');
  }
  return redirect('login')->with('success','These credentials do not match our records.')->withInput();
  }
 
}
