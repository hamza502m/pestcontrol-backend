<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPurchase;
use App\Models\Suppliers;
use App\Notifications\AppNotification;

class SuppliersController extends Controller
{
    
    public function get_suppliers(Request $request)
    {
        $suppliers=Suppliers::with('country','state','city','order_purchased','order_purchased.items')->get();
        if($suppliers){return response()->json([
            'data' => $suppliers,
        ]);}
        else{return response()->json(['data' => 'No data']);}
    }
    public function get_supplier($id)
    {            
        $supplier=Suppliers::with('country','state','city','order_purchased','order_purchased.items')->where('id',$id)->first();
        if($supplier){return response()->json(['data' => $supplier]);}
        else{return response()->json(['data' => 'No data']);}
    }
    public function store(Request $request)
    {
            $supplier = new Suppliers();
            $supplier->supplier_name=$request->supplier_name;
            $supplier->company_name=$request->company_name;
            $supplier->email=$request->email;
            $supplier->number=$request->number;
            $supplier->vat=$request->vat;
            $supplier->tin_no=$request->tin_no;
            $supplier->supplier_type=$request->supplier_type;
            $supplier->item_notes=$request->item_notes;
            $supplier->address=$request->address;
            $supplier->country=$request->country;
            $supplier->state=$request->state;
            $supplier->hsn=$request->hsn;
            $supplier->city=$request->city;
            $supplier->zip=$request->zip;
            $supplier->save();
            $message="A supplier has been added into system by the name of ".$supplier->supplier_name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Supplier Added',
                'data' => $supplier
            ]);
    }
    public function OrderPurchased(Request $request)
    {
            $purchas_order = new OrderPurchase();
            $purchas_order->supplier_id=$request->supplier_id;
            $purchas_order->payment_method=$request->payment_method;
            $purchas_order->city=$request->city;
            $purchas_order->zip=$request->zip;
            $purchas_order->order_date=$request->order_date;
            $purchas_order->delivery_date=$request->delivery_date;
            $purchas_order->private_notes=$request->private_notes;
            $purchas_order->save();
            multiple_files_saving($request->attachments,$purchas_order->id,'App\Models\OrderPurchase','order_purchase','order_purchase');
            multiple_stock_updating($request);
            $message="A Order has been Purchased from ".$purchas_order->supplier_name;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Order has been Purchased',
                'data' => $purchas_order
            ]);
    }
}
