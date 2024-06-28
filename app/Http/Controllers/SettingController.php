<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceAgreement;
use App\Models\TreatmentMethod;
use App\Models\State;
use App\Models\Cities;
use App\Models\Countries;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function service_agreement(Request $request)
    {
            $service = new ServiceAgreement();
            $service->name=$request->name;
            $service->work_scope=$request->work_scope;
            $service->save();
            $message="A Service has been added by this name ".$request->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(ServiceAgreement::find($service->id))->log('Service has been added by '.$request->name);
            return response()->json([
                'message' => 'Service has been Added',
                'data' => $service
            ]);
    }
    // Get all Services
    public function services(Request $request)
    {
	         $services=ServiceAgreement::orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
	            if($services){
	               return response()->json(['data' => $services]);
	            }
	            else{
	               return response()->json(['data' => 'No data']);
	            }
    }
    // Get service by id
    public function service($id)
    {
	         $service=ServiceAgreement::where('id',$id)->first();
	            if($service){
	                return response()->json(['data' => $service]);
	            }
	            else{
	                return response()->json(['data' => 'No data']);
	            }
    }

    public function add_treatment_method(Request $request)
    {
            $treatment_method = new TreatmentMethod();
            $treatment_method->name=$request->name;
            $treatment_method->work_scope=$request->work_scope;
            $treatment_method->save();
            $message="A treatment method has been added by this name ".$request->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(ServiceAgreement::find($treatment_method->id))->log('Service has been added by '.$request->name);
            return response()->json([
                'message' => 'Treatment method has been Added',
                'data' => $treatment_method
            ]);
    }
    // Get all Treatment Method
    public function treatment_methods(Request $request)
    {
	         $treatment_methods=TreatmentMethod::orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
	            if($treatment_methods){
	               return response()->json(['data' => $treatment_methods]);
	            }
	            else{
	               return response()->json(['data' => 'No data']);
	            }
    }
    // Get Treatment Method by id
    public function treatment_method($id)
    {
	         $treatment_method=TreatmentMethod::where('id',$id)->first();
	            if($treatment_method){
	                return response()->json(['data' => $treatment_method]);
	            }
	            else{
	                return response()->json(['data' => 'No data']);
	            }
    }
    // Countries
    public function save_countries(Request $request)
    {   

      try{
            $validate = $this->validate($request, [
                'name' => 'required|unique:countries,name'
            ]);
            $country = new Countries();
            $country->name=$request->name;
            $country->currency_sign=$request->currency_sign;
            $country->country_code=$request->country_code;
            $country->save();
            $message="A country has been added by this name ".$request->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Country has been Added',
                'data' => $country
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error' => $e->validator->errors()->first(),], 422);
        }
    }
    public function get_countries(Request $request)
    {            
        $countries=Countries::with('states','cities')->get();
        if($countries){return response()->json(['data' => $countries]);}
        else{return response()->json(['data' => 'No data']);}
    }
    public function get_country($id)
    {            
        $countries=Countries::where('id',$id)->first();
        if($countries){return response()->json(['data' => $countries]);}
        else{return response()->json(['data' => 'No data']);}
    }
    // States
    public function save_state(Request $request)
    {   

      try{
            $validate = $this->validate($request, [
                'name' => 'required|unique:states,name'
            ]);
            $state = new State();
            $state->name=$request->name;
            $state->country_id=$request->country_id;
            $state->save();
            $message="A state has been added by this name ".$request->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'State has been Added',
                'data' => $state
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error' => $e->validator->errors()->first(),], 422);
        }
    }
    public function get_states(Request $request)
    {            
        $countries=State::all();
        if($countries){return response()->json(['data' => $countries]);}
        else{return response()->json(['data' => 'No data']);}
    }
    public function get_state($id)
    {            
        $countries=State::where('id',$id)->first();
        if($countries){return response()->json(['data' => $countries]);}
        else{return response()->json(['data' => 'No data']);}
    }

    // Cities
     public function save_city(Request $request)
    {  
        try{
            $validate = $this->validate($request, [
                'name' => 'required|unique:cities,name'
            ]);
            $city = new Cities();
            $city->name=$request->name;
            $city->country_id=$request->country_id;
            $city->state_id=$request->state_id;
            $city->save();
            $message="A city has been added by this name ".$request->name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'City has been Added',
                'data' => $city
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error' => $e->validator->errors()->first(),], 422);
        }
    }

    public function get_cities(Request $request)
    {            
        $cities=Cities::all();
        if($cities){return response()->json(['data' => $cities]);}
        else{return response()->json(['data' => 'No data']);}
    }
    public function get_city($id)
    {            
        $cities=Cities::where('id',$id)->first();
        if($cities){return response()->json(['data' => $cities]);}
        else{return response()->json(['data' => 'No data']);}
    }
}
