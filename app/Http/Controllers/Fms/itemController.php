<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use DB;
class itemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//return ''; die;
		$datas = Item::all();
		//dd($datas); die;
		return view('item.item', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.item_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'i_name' => ['required', 'string', 'max:255'],
			'i_code' => ['required', 'string', 'max:255'],
			'i_category' => ['required'],
			'i_elements' => ['required', 'string', 'max:255'],
			'i_quantity_produced' => ['required', 'numeric'],
			'active_status' => ['required'],
        ]);  
		
			
		$Item = new Item();
		$Item->i_name = $request->get('i_name');
		$Item->i_code = $request->get('i_code');
		$Item->i_category = $request->get('i_category');
		$Item->i_elements = implode(',', $request->get('i_elements'));
		$Item->i_quantity_produced = $request->get('i_quantity_produced');
		$Item->active_status = $request->get('active_status');
		
		$inserted = $Item->save();
		
		if($inserted)
		{
			return redirect()->route('itemlist')->with('success', 'Item added successfully.');
		}else{
			return redirect()->route('itemlist')->with('success', 'Failed! try again.');
		}
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
        $item = Item::find($id);
		//echo "<pre>"; print_r( $item); die;
        return view('item.item_edit', compact('item', 'id'));
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
		$this->validate($request, [
			'i_name' => ['required', 'string', 'max:255'],
			'i_code' => ['required', 'string', 'max:255'],
			'i_category' => ['required'],
			'i_elements' => ['required', 'max:255'],
			'i_quantity_produced' => ['required', 'numeric'],
			'active_status' => ['required'],
        ]); 
		//$i_elements = implode(',', $request->get('item_elements'));
		
		//echo "<pre>"; print_r($_POST);
		//echo $i_elements;
		//die;		
		$dateArr = array(
						'i_name' => $request->get('i_name'),
						'i_code' => $request->get('i_code'),
						'i_category' => $request->get('i_category'),
						'i_elements' => implode(',', $request->get('i_elements')),
						'i_quantity_produced' => $request->get('i_quantity_produced'),
						'active_status' => $request->get('active_status')
						);
		$upId = DB::table('items')
				->where('_id', $id)
				->update($dateArr); 
        if($upId)
		{
			return redirect()->route('itemlist')->with('success', 'Item updated successfully.');
		}else{
			return redirect()->route('itemlist')->with('success', 'Failed! try again.');
		}
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delId = DB::table('items')
				->where('_id', $id)
				->delete();
				
        if($delId)
		{
			return redirect()->route('itemlist')->with('success', 'Item delete successfully.');
		}else{
			return redirect()->route('itemlist')->with('success', 'Failed! try again.');
		}
    }
}
