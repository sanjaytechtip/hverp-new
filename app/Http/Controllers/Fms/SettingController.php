<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\DropdownList;

class SettingController extends Controller
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
	
	public function openmenu($slug)
	{
		return view('fms.setting');
	}
	
	public function createleftmenu()
	{
		return view('fms.createleftmenu');
	}
	
	public function storemenuname(Request $request)
	{
		$this->validate($request, array(
			'menu_name'=>'required|max:255'
		));
		
		$res = Setting::create([
			'menu_name'=>$request->menu_name,
			'menu_slug'=>str_replace(' ', '_', strtolower($request->menu_name))
		]);
		if($res)
		{
			return redirect()->route('createleftmenu')->with('success', 'Menu created successfully.');
		}else{
			return redirect()->route('createleftmenu')->with('success', 'Menu not created.');
		}
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response 
     */
	 
	 /* used to create dropdown list */
    public function create()
    {
        return view('fms.setting');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
			'dropdown_name'=>'required|max:255'
		));
		
		/* dd($request);
		
		$userInput  =	array(
							"user_id" => $request->user_id,
							"dropdown_name" => $request->dropdown_name,
							"input_type" => $request->input_type,
							"labels"=>$request->row,
						   ); */
		

		/* "row" => array(
		"label_name" => "Female"
		"label_id" => "female"
		) */
		
		/* foreach($request->row as $key=>$val)
		{
			$userInput[$key]=$val;
		}  */
		
		
		//echo "<pre>"; print_r($request->row); die;
		
		$res = DropdownList::create([
			"user_id" => $request->user_id,
			"dropdown_name" => $request->dropdown_name,
			"input_type" => $request->input_type,
			"labels"=>json_encode($request->row),
		]);
		if($res)
		{
			return redirect()->route('createdropdown')->with('success', 'Dropdown created successfully.');
		}else{
			return redirect()->route('createdropdown')->with('success', 'Dropdown not created.');
		}
    }
	
	
	public function dropdownlist()
    {
        $dropdownlists = DropdownList::all();		
		//dd($dropdownlist);
		return view('fms.dropdownlist', compact('dropdownlists'))->with('no',1);
		
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
