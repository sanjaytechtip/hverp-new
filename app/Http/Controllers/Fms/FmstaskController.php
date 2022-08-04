<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fmstask;
use DB;

class FmstaskController extends Controller
{
	protected $fmstask;
	
	public function __construct()
	{
		$this->fmstask = new Fmstask();
	}
	
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
        return view('fms.createtask');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		$fms_id = $request->fms_id;
		foreach($request->row as $rkey=>$val)
		{
			//echo "<pre>"; print_r($val); 
			$this->fmstask::create([
			'fms_id'=>$fms_id,
			'task_step'=>$rkey,
			'task_name'=>$val['task_name']
			]); 
		}
		//die;
		DB::commit();
        return redirect()->route('createtask')->with('success', 'Task created successfully.');
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
