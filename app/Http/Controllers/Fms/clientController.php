<?php

namespace App\Http\Controllers\Fms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Client;
use DB;
class clientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return redirect()->route('clientlist');
    }
	
	public function clientlist()
    {
	    $datas = Client::all();
		return view('client.client', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('client.client_add');
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
			'r_nick_name' => ['required', 'string', 'max:255'],
			'r_full_name' => ['required', 'string', 'max:255'],
			'address' => ['required', 'string'],
			'r_person_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255'],
			'r_mobile_no' => ['required', 'numeric'],
			'client_password' => ['required', 'string', 'min:6'],
        ]);  
		
			
		$client = new Client();
		$client->r_nick_name = $request->get('r_nick_name');
		$client->r_full_name = $request->get('r_full_name');
		$client->address = $request->get('address');
		$client->r_person_name = $request->get('r_person_name');
		$client->email = $request->get('email');
		$client->r_mobile_no = $request->get('r_mobile_no');
		$client->client_password = Hash::make($request->get('client_password'));
		$client->org_password = $request->get('client_password');
		$client->extra_mobile_no = $request->get('extra_mobile_no');
		$client->GSTIN = $request->get('GSTIN');
		$client->universal_note = $request->get('universal_note');
		$client->discount = $request->get('discount');
		$client->payment_term = $request->get('payment_term');
		$client->client_place_type = $request->get('client_place_type');
		$client->r_receiving = $request->get('r_receiving');
		$client->r_sms_sent = $request->get('r_sms_sent');
		
		/* var_dump($client); 
		die; */
		$inserted = $client->save();
		
		if($inserted)
		{
			return redirect()->route('clientlist')->with('success', 'Client added successfully.');
		}else{
			return redirect()->route('clientlist')->with('success', 'Failed! try again.');
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
        $client = Client::find($id);
        return view('client.client_edit', compact('client', 'id'));
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
			'r_nick_name' => ['required', 'string', 'max:255'],
			'r_full_name' => ['required', 'string', 'max:255'],
			'address' => ['required', 'string'],
			'r_person_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255'],
			'r_mobile_no' => ['required', 'numeric'],
			'client_password' => ['required', 'string', 'min:6'],
        ]); 		
		
		$dateArr = array(
						'r_nick_name' => $request->get('r_nick_name'),
						'r_full_name' => $request->get('r_full_name'),
						'address' => $request->get('address'),
						'r_person_name' => $request->get('r_person_name'),
						'email' => $request->get('email'),
						'r_mobile_no' => $request->get('r_mobile_no'),
						'client_password' => Hash::make($request->get('client_password')),
						'org_password' => $request->get('client_password'),
						'extra_mobile_no' => $request->get('extra_mobile_no'),
						'GSTIN' => $request->get('GSTIN'),
						'universal_note' => $request->get('universal_note'),
						'discount' => $request->get('discount'),
						'payment_term' => $request->get('payment_term'),
						'client_place_type' => $request->get('client_place_type'),
						'r_receiving' => $request->get('r_receiving'),
						'r_sms_sent' => $request->get('r_sms_sent')
						);
		
		
		$upId = DB::table('clients')
				->where('_id', $id)
				->update($dateArr); 
        if($upId)
		{
			return redirect()->route('clientlist')->with('success', 'Client updated successfully.');
		}else{
			return redirect()->route('clientlist')->with('success', 'Failed! try again.');
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
		
		$upId = DB::table('clients')
				->where('_id', $id)
				->delete();
				
        if($upId)
		{
			return redirect()->route('clientlist')->with('success', 'Client delete successfully.');
		}else{
			return redirect()->route('clientlist')->with('success', 'Failed! try again.');
		}
       
    }
}
