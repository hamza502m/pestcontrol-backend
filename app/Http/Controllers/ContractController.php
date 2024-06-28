<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Http\Controllers\Controller;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::with('client','user')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
            // $contracts->map(function ($contract) {
            //     $contract->service_agreements = $contract->service_agreements;
            //     $contract->treatment_methods = $contract->treatment_methods;
            //     return $contract;
            // });
             if($contracts){
                    return response()->json(['data' => $contracts]);
                 }
             else{
                    return response()->json(['data' => 'No data']);
                 }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function single_contract($id)
    {
            $contract =Contract::with('quote','tags','client','user')->find($id);
            if($contract){
                $contract->service_agreements = $contract->service_agreements;
                $contract->treatment_methods = $contract->treatment_methods;
                return response()->json(['data' => $contract]);
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
            // $contract=contract_creations($request);
            // if($contract){
            //     quoted_services($contract,$request);
            //     save_tags($request->tags,$contract,Contract::class);
            // }
            // $message="A Contract has been added into system";
            // // auth()->user()->notify(new PasswordNotification($message));
            // auth()->user()->notify(new AppNotification($message));
            // return response()->json([
            //     'message' => 'Contract Added',
            //     'data' => $contract
            // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
