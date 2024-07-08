<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Controllers\Controller;
use App\Notifications\AppNotification;
use App\Notifications\PasswordNotification;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $vendors = Vendor::with('user')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
             if($vendors){
                    return response()->json(['data' => $vendors]);
                 }
             else{
                    return response()->json(['data' => 'No data']);
                 }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function single_vendor($id)
    {
        $vendor = Vendor::with('user')->find($id);
        if($vendor){
                return response()->json(['data' => $vendor]);
             }
             else{
                return response()->json(['data' => 'No data']);
             }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $user=registering_user($request);
            if(isset($user['error'])){
             return response()->json(['error' => $user['error']], 422);
            }
            $vendor = new Vendor();
            $vendor->user_id=$user['data']->id;
            $vendor->firm_name=$request->firm_name;
            $vendor->mng_name=$request->mng_name;
            $vendor->mng_contact=$request->mng_contact;
            $vendor->mng_email=$request->mng_email;
            $vendor->acc_name=$request->acc_name;
            $vendor->acc_contact=$request->acc_contact;
            $vendor->acc_email=$request->acc_email;
            $vendor->percentage=$request->percentage;
            $vendor->save();
            $message="A Vendor has been added into system by ".$user['data']->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Vendor Added',
                'data' => $user['data']
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
