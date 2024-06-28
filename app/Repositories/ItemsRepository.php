<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Notifications\AppNotification;
use App\Models\ClientJob;
use App\Models\AssignedJobs;
use App\Models\Items;
use App\Models\ItemTypes;
use App\Models\Units;
use App\Models\Brands;

class ItemsRepository
{
    protected $model;

    public function __construct(Items $items)
    {
        $this->model = $items;
    }

    // Finding all Items
    public function all($request)
    {
        $Items=Items::with('brand','unit','supplier','item_type','attachments')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
         if($Items){
             return response()->json([
                'data' => $Items
                ]);
             }
             else{
             return response()->json([
                'data' => 'No data'
                ]);
             }
    }

    // Finding single Item
    public function find($id)
    {
        $Items=Items::where('id',$id)->with('brand','unit','supplier','item_type','attachments')->orderBy('id', 'DESC')->first();
            if($Items){
                $Items->service_Agreements = $Items->service_agreements;
                return response()->json(['data' => $Items]);
             }
             else{
                return response()->json(['data' => 'No data']);
             }
    }
    // Creating Item
    public function create($request)
    {
            // try {
            $item = new Items();
            $item->item_name=$request->item_name;
            $item->batch_number = $request->batch_number;
            $item->brand_id = $request->brand_id;
            $item->mfg=$request->mfg;
            $item->ed=$request->exp;
            $item->item_type=$request->item_type;
            $item->unit=$request->unit;
            $item->active_ingredients=$request->active_ingredients;
            $item->others_ingredients=$request->others_ingredients;
            $item->moccae_approval=$request->moccae_approval;
            $item->moccae_strat_date=$request->moccae_strat_date;
            $item->moccae_exp=$request->moccae_exp;
            $item->per_item_price=$request->per_item_price;
            $item->per_item_qty=$request->per_item_qty;
            $item->total_qty=$request->total_qty;
            $item->vat=$request->vat;
            $item->service_ids=json_encode($request->service_ids);
            $item->supplier_id=$request->supplier_id;
            $item->price=$request->price;
            $item->descriptions=$request->descriptions;
            $item->save();
            multiple_files_saving($request->attachments,$item->id,'App\Models\Items','items_files','item_file');
            saveImage($request->item_picture,$item->id,'App\Models\Items','items','item_photo');
            single_stock_updating($request->supplier_id,$item->id,$request->total_qty,$request->price);
            $message="A item has been added by the name ".$item->item_name;
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(Items::find($item->id))->log('A item has been added by the name '.$item->item_name);
            return response()->json([
                'message' => 'Item has been added',
                'data' => $item
            ]);
        //     }catch (\Illuminate\Validation\ValidationException $e) {
        //     // Validation error occurred
        //     return response()->json([
        //         'error' => $e->validator->errors()->first(),
        //     ], 422);
        // } catch (\Exception $e) {
        //     // Other unexpected errors
        //     return response()->json([
        //         'error' => 'Something went wrong.',
        //     ], 500);
        // }
    }

    // Saving Brand 
    public function creating_brand($request)
    {
            try {
            $brand = new Brands();
            $brand->name=$request->name;
            $brand->save();
            $message="A brand has been added by the name ".$brand->name;
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(Brands::find($brand->id))->log('A brand has been added by the name '.$brand->item_name);
            return response()->json([
                'message' => 'Brand has been added',
                'data' => $brand
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
    // Finding all Items
    public function all_brands($request)
    {
        $brands=Brands::with('items')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
         if($brands){return response()->json(['data' => $brands]);}
         else{return response()->json(['data' => 'No data']);}
    }

    // Finding single Item
    public function find_brand($id)
    {
        $brand=Brands::where('id',$id)->with('items')->orderBy('id', 'DESC')->first();
            if($brand){return response()->json(['data' => $brand]);}
            else{return response()->json(['data' => 'No data']);}
    }

    // Saving Item_type
    public function creating_item_type($request)
    {
            try {
            $brand = new ItemTypes();
            $brand->name=$request->name;
            $brand->save();
            $message="A ItemTypes has been added by the name ".$brand->name;
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(ItemTypes::find($brand->id))->log('A ItemTypes has been added by the name '.$brand->item_name);
            return response()->json([
                'message' => 'ItemTypes has been added',
                'data' => $brand
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
    // Finding all Item_type
    public function all_item_types($request)
    {
        $brands=ItemTypes::with('items')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
         if($brands){return response()->json(['data' => $brands]);}
         else{return response()->json(['data' => 'No data']);}
    }

    // Finding single Item_type
    public function item_type($id)
    {
        $brand=ItemTypes::where('id',$id)->with('items')->orderBy('id', 'DESC')->first();
            if($brand){return response()->json(['data' => $brand]);}
            else{return response()->json(['data' => 'No data']);}
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }


    // Saving Item Unit
    public function store_item_unit($request)
    {
            try {
            $unit = new Units();
            $unit->name=$request->name;
            $unit->save();
            $message="A Unit has been added by the name ".$unit->name;
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(Units::find($unit->id))->log('A Unit has been added by the name '.$unit->item_name);
            return response()->json([
                'message' => 'Unit has been added',
                'data' => $unit
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
    // Finding all Item Units
    public function get_item_units($request)
    {
        $units=Units::with('items')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
         if($units){return response()->json(['data' => $units]);}
         else{return response()->json(['data' => 'No data']);}
    }

    // Finding single Item unit
    public function get_item_unit($id)
    {
        $unit=Units::where('id',$id)->with('items')->orderBy('id', 'DESC')->first();
            if($unit){return response()->json(['data' => $unit]);}
            else{return response()->json(['data' => 'No data']);}
    }

}
