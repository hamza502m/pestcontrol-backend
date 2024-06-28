<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Tag;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\AppNotification;
use App\Jobs\ClientRegisterjob;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   

        $client=Client::with('jobs','tags','user','user.addressess')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
            if($client){
                return response()->json(['data' => $client]);
            }
            else{
                return response()->json(['data' => 'No data']);
            }
    }
    public function singleClint($id)
    {   

        $client=Client::where('id',$id)->with('jobs','tags','user','user.addressess')->first();
            if($client){
                return response()->json(['data' => $client]);
            }
            else{
                return response()->json(['data' => 'No data']);
            }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    try {
            $clientDetail = $request->validate([
                'email' => 'required|string|email|unique:clients'
            ]);
            $user=registering_user($request);
            if(isset($user['error'])){
             return response()->json(['error' => $user['error']], 422);
            }
            $clientDetail = new Client();
            $clientDetail->user_id=$user['data']->id;
            $clientDetail->full_name=$request->name;
            $clientDetail->email=$request->email;
            $clientDetail->firm_name=$request->firm_name;
            $clientDetail->phone_number=$request->phone_number;
            $clientDetail->mobile_number=$request->mobile_number;
            $clientDetail->industry_name=$request->industry_name;
            $clientDetail->reference=$request->reference;
            $clientDetail->address=$request->address;
            $clientDetail->city=$request->city;
            $clientDetail->latitude=$request->latitude;
            $clientDetail->longitude=$request->longitude;
            $clientDetail->save();
            if($clientDetail){
                $this->tags($request->tags, $clientDetail->id);
            }  
            dispatch(new ClientRegisterjob($clientDetail));
            auth()->user()->notify(new AppNotification($clientDetail));
            activity()->performedOn(Client::find($clientDetail->id))->log('Client added by name of '.$clientDetail->full_name);
        // $user=User::where('id',$request->user_id)->where('clientDetail')->first();
        return response()->json([
                'message' => 'Client Created',
                'data' => $clientDetail
            ]);
            }catch (\Illuminate\Validation\ValidationException $e) {
            // Validation error occurred
            return response()->json([
                'error' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json([
                'error' => 'Something went wrong.',
            ], 500);
        }
    }
    // For Saving Tags
    // Code by Umair 
    // 15/05/2024
    public function tags($tags, $job_id) {
        if(!empty($tags)){
        $tagz=explode(",",$tags);
        foreach($tagz as $singletag) {
            $tag = new Tag();
            $tag->tag_id = $job_id;
            $tag->tag_type = Client::class;
            $tag->tag = $singletag;
            $tag->status = 1;
            $tag->save();
            }
        }
    }
    public function client_addressess(Request $request){
            $i=0;
            foreach($request->addresses as $address) {   
            $client_address = new Addresses();
            $client_address->user_id=$address['user_id'];
            $client_address->address=$address['address'];
            $client_address->city=$address['city'];
            $client_address->lat=$address['lat'];
            $client_address->lang=$address['lang'];
            $client_address->save();
            $i++;
            }
            return response()->json([
                'message' => 'Addresses Have been added'
            ]);
    }
    public function markasRead($id)
    {
     if($id){
        auth()->user()->notifications->where('id',$id)->markAsRead();
        return response()->json([
            'message'=>'read'
        ]);
     }
    }
}
