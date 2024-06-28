<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Notifications\AppNotification;
use App\Models\Services;
use App\Models\ServicItems;
use App\Models\ServiceReports;
use App\Models\ServiceUsedChemicals;
use App\Models\ServicAreas;

class ServiceRepository
{
    protected $model;

    public function all($request)
    {
        $services = Services::with('item_services','item_services.items')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
        if($services){return response()->json(['data' => $services]);}
        else{return response()->json(['data' => 'No data']);}
    }

    public function find($id)
    {
            $service = Services::with('item_services','item_services.items')->find($id);
            if($service){return response()->json(['data' => $service]);}
            else{return response()->json(['data' => 'No data']);}
    }

    public function create($request)
    {

        $service = new Services();
        $service->service_name=$request->service_name;
        $service->unit_price=$request->unit_price;
        $service->service_code=$request->service_code;
        $service->status=$request->status;
        $service->scope_of_work=$request->scope_of_work;
        $service->terms_and_conditions=$request->terms_and_conditions;
        $service->save();
        $this->save_service_item($request,$service->id);
        $message="A Service has been added into system by ".$request->service_name;
        auth()->user()->notify(new AppNotification($message));
        return response()->json([
            'message' => 'Service Added',
            'data' => $service
        ]);
    }

    public function save_service_item($request,$service_id)
    {   
        foreach ($request->items as $key => $item) {
        $service = new ServicItems();
        $service->service_id=$service_id;
        $service->item_id=$item['item_id'];
        $service->item_name=$item['item_name'];
        $service->high=$item['high'];
        $service->medium=$item['medium'];
        $service->low=$item['low'];
        $service->save();
        }
    }


    public function service_report($request)
    {
        $service = new ServiceReports();
        $service->client_name=$request->client_name;
        $service->contact_number=$request->contact_number;
        $service->date=$request->date;
        $service->type_of_visit=$request->type_of_visit;
        $service->service_ids=json_encode($request->service_ids);
        $service->tm_ids=json_encode($request->tm_ids);
        $service->status=$request->status;
        $service->save();
        $this->servic_areas($request,$service->id);
        $this->service_used_chemicals($request,$service->id);
        $message="A Service Report has been created into system for ".$request->client_name;
        auth()->user()->notify(new AppNotification($message));
        return response()->json([
            'message' => 'Service Report Created',
            'data' => $service
        ]);
    }

    public function servic_areas($request,$service_id)
    {   
        foreach ($request->servic_areas as $key => $item) {
        $service = new ServicAreas();
        $service->report_id=$service_id;
        $service->inspected_area=$item['inspected_area'];
        $service->infestation_level=$item['infestation_level'];
        $service->manifested_area=$item['manifested_area'];
        $service->report_follow_up=$item['report_follow_up'];
        $service->save();
        }
    }

    public function service_used_chemicals($request,$service_id)
    {   
        foreach ($request->service_used_chemicals as $key => $item) {
        $service = new ServiceUsedChemicals();
        $service->report_id=$service_id;
        $service->item_id=$item['item_id'];
        $service->item_name=$item['item_name'];
        $service->qty=$item['qty'];
        $service->type=$item['type'];
        $service->price=$item['price'] ? $item['price'] : 0;
        $service->infestation_level=$item['infestation_level'];
        $service->job_id=$item['job_id'];
        $service->service_id=$item['service_id'];
        $service->save();
        }
    }
    public function all_service_report($request)
    {
        $services = ServiceReports::with('services_areas','service_used_chemicals')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
        $services->map(function ($quote) {
                $quote->service_agreements = $quote->service_agreements;
                $quote->treatment_method = $quote->treatment_method;
                return $quote;
            });
        if($services){return response()->json(['data' => $services]);}
        else{return response()->json(['data' => 'No data']);}
    }

    public function single_service_report($id)
    {
        $service = ServiceReports::with('services_areas','service_used_chemicals')->find($id);
            if($service){
                $service->service_Agreements = $service->service_agreements;
                $service->treatment_method = $service->treatment_method;
                return response()->json(['data' => $service]);
            }
            else{return response()->json(['data' => 'No data']);}
    }

}
