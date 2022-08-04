<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SiteSetting;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

	public function weeklyoff(Request $request){
			
		$siteSettings=SiteSetting::where('user_id', '=', getCompanyId())->firstOrFail()->toarray();
		//echo'<pre>--';print_r($siteSettings);die;
		//$weekoff = $siteSettings['weekoff'];
		$weekoff = $siteSettings;
		//dd($weekoff);
		return view('fms.weeklyoff',compact('weekoff'));
		
	}
	
	public function weeklyoffupdate(Request $request){
		
			//dd($request->get('weekdays'));
			
			//print_r($_POST);die;
			
			$siteSettings=SiteSetting::where('user_id', '=', getCompanyId())->firstOrFail();			
			$siteSettings->weekoff = $request->get('weekdays');
			$siteSettings->office_time = $request->get('office_time');
			$siteSettings->tea_break1 = $request->get('tea_break1');
			$siteSettings->tea_break2 = $request->get('tea_break2');
			$siteSettings->lunch = $request->get('lunch'); 
			$siteSettings->save();
			return redirect(route('weeklyoff'))->with('success', 'Setting has been successfully updated!');
		
	}
	
	
    
}
