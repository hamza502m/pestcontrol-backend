<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Contract;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\PasswordNotification;
use App\Models\QuotedServices;
use App\Models\Invoices;
use App\Models\Items;
use App\Models\StockIn;
use App\Models\AssignedStock;
use App\Models\StockOut;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordSend;
use App\Models\Attachment;

    // Saving Tags
    function save_tags($tags, $model_id,$model) 
    {
        if(!empty($tags)){
            $tagz=explode(",",$tags);
            foreach($tagz as $singletag) {
                $tag = new Tag();
                $tag->tag_id = $model_id;
                $tag->tag_type = $model;
                $tag->tag = $singletag;
                $tag->status = 1;
                $tag->save();
            }
        }
    }

    // Getting User Id
    function get_user_details($user_id) {
    	return User::where('id',$user_id)->first();
    }

    // Registering User
    function registering_user($request)
    {
        try {
            $registerUserData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users'
            ]);
            $user = User::create([
                'name' => $registerUserData['name'],
                'email' => $registerUserData['email'],
                'role' => $request->role,
                'password' => Hash::make(generateRandomPassword(12)),
            ]);
            $user_role=Role::where('id',$user->role)->first()->name;
            $message="Dear user This is your Password ".generateRandomPassword(12);
            //$user->notify(new PasswordNotification($message));
            // Mail::to($registerUserData['email'])->send(new PasswordSend($message));
            activity()->causedBy(auth()->user())->log($request->name.' as '.$user_role);
            if($request->image){
                saveImage($request->image,$user->id,'App\Models\User','users','Employee Photo');
            }
            return [
                'status' => 'success',
                'message' => 'User Created',
                'data' => $user,
                'token' =>$user->createToken($user->name.'-AuthToken')->plainTextToken
            ];
        }catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'status'=> 'error',
                'message' => $e->validator->errors()->first(),
            ];
        } catch (\Exception $e) {
            // Other unexpected errors
            return [
                'status' => 'error',
                'message' => 'Failed to Add User. ' . $e->getMessage(),
            ];
        }
    }

    // Generating Random Password
    function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
        $password = '';
        $characterCount = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $characterCount)];
        }
        return $password;
    }
    // Saving Attachment
    function saveImage($image,$model_id,$model,$folder,$description){
                $name = $image->getClientOriginalName();
                $name = strtolower(str_replace(' ', '-', $name));
                $destinationPath = public_path() . '/upload/'.$folder.'/'; // upload path
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                $fileName = $folder.'_' . rand(0, 999) . '.webp'; // renameing image
                $image->move($destinationPath, $fileName);
                $attachment = new Attachment;
                $attachment->attachment_id = $model_id;
                $attachment->attachment_type = $model;
                $attachment->attachment = $fileName;
                $attachment->attachment_description = $description;
                $attachment->status = 1;
                $attachment->save();
    }
    // Saving Multiple Attachments
    function multiple_Images_saving($images,$model_id,$model,$folder,$description){
                foreach($images as $key => $image) {
                $name = $image->getClientOriginalName();
                $name = strtolower(str_replace(' ', '-', $name));
                $destinationPath = public_path() . '/upload/'.$folder.'/'; // upload path
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                $fileName = $folder.'_' . rand(0, 999) . '.webp'; // renameing image
                $image->move($destinationPath, $fileName);
                $attachment = new Attachment;
                $attachment->attachment_id = $model_id;
                $attachment->attachment_type = $model;
                $attachment->attachment = $fileName;
                $attachment->attachment_description = $description;
                $attachment->status = 1;
                $attachment->save();
                }
    }
    // Saving Multiple files
    function multiple_files_saving($attachments,$model_id,$model,$folder,$description){
       
                foreach($attachments as $key => $attachment) {
                $name = $attachment->getClientOriginalName();
                $name = strtolower(str_replace(' ', '-', $name));
                $destinationPath = public_path() . '/upload/'.$folder.'/'; // upload path
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                $fileName = $folder.'_' . $name; // renameing image
                $attachment->move($destinationPath, $fileName);
                $attachment = new Attachment;
                $attachment->attachment_id = $model_id;
                $attachment->attachment_type = $model;
                $attachment->attachment = $fileName;
                $attachment->attachment_description = $description;
                $attachment->status = 1;
                $attachment->save();
                }
    }
        // saving clientJob Details
    function quoted_services($model_id,$requests,$model){
            
            foreach ($requests->services as $request){
            $i=0;
            foreach ($request['dates'] as $date){
            $quoted_service = new QuotedServices();
            $quoted_service->quotedServices_id=$model_id;
            $quoted_service->service_id=$request['service_id'];
            $quoted_service->service_name=$request['service_name'];
            $quoted_service->no_of_months=$request['no_of_months'];
            $quoted_service->rate=$request['rate'];
            $quoted_service->job_type=$request['job_type'];
            $quoted_service->sub_total=$request['sub_total'];
            $quoted_service->date=$date['date'];
            $quoted_service->quotedServices_type=$model;
            $quoted_service->save();
            $i++;
            }
            }
    }

    // saving Contract Creations
    function contract_creations($quote=null,$request){
            $contract = new Contract();
            $contract->user_id=$request->user_id;
            $contract->quote_id=$quote!=null ? $quote : $request->quote_id;
            $contract->client_id=$request->client_id;
            $contract->contracted_by=$request->user_id;
            $contract->firm=$request->firm;
            $contract->contract_reference=$request->contract_reference;
            $contract->food_watch_account=$request->food_watch_account;
            $contract->job_type=$request->job_type;
            $contract->date=$request->date;
            $contract->due_date=$request->due_date;
            $contract->trn=$request->trn;
            $contract->contract_title=$request->quote_title;
            $contract->contract_subject=$request->subject;
            $contract->service_ids=json_encode($request->service_ids);
            $contract->tm_ids=json_encode($request->tm_ids);
            $contract->discount_type=$request->discount_type;
            $contract->discount=$request->discount;
            $contract->vat=$request->vat;
            $contract->grand_total=$request->grand_total;
            $contract->save();
            return $contract->id;
    }

    // Creating Invoice as no of installments
    function invoice_installment_type($quote_id,$quote_approve,$jobs){
        $no_of_installments = $quote_approve->no_of_invoive;
        $due_amount_per_invoice = $quote_approve->grand_total / $no_of_installments;
        $month_gap=$quote_approve->duration / $no_of_installments;
        $due_date = DateTime::createFromFormat('d-m-Y', $quote_approve->date);
        for ($i = 0; $i < $no_of_installments; $i++){
            $invoice = new Invoices([
                'client_id' => $quote_approve->client_id,
                'contract_id' => $quote_approve->contract->id,
                'quote_id' => $quote_id,
                'user_id' => $quote_approve->user_id,
                'customer_name' => $quote_approve->client->full_name,
                'customer_number' => $quote_approve->client->phone_number,
                'reference' => $quote_approve->contract_reference,
                'subject' => $quote_approve->quote_title,
                'invoiced_by' => Auth::user()->id,
                'due_on' => $due_date->format('d-m-Y'), // Set the due date for this invoice
                'due_amount' => $due_amount_per_invoice,
                'discount_type' => $quote_approve->discount_type,
                'discount' => $quote_approve->discount,
                'vat' => $quote_approve->vat,
                'grand_total' => $quote_approve->grand_total,
                'status' => 1,
            ]);

            $invoice->save();
            $due_date->modify('+'.(int)$month_gap.' month');
        }
    }
    // Creating Invoice as Service type
    function invoice_service_type($quote_id,$quote_approve,$jobs){
        $i = 0;
        $total_job=count($jobs);
        foreach ($jobs as $request) {
            $invoice = new Invoices();
            $invoice->client_id=$quote_approve->client_id;
            $invoice->contract_id=$quote_approve->contract->id;
            $invoice->quote_id=$quote_id;
            $invoice->user_id=$quote_approve->user_id;
            $invoice->customer_name=$quote_approve->client->full_name;
            $invoice->customer_number=$quote_approve->client->phone_number;
            $invoice->reference=$quote_approve->contract_reference;
            $invoice->subject=$quote_approve->quote_title;
            $invoice->invoiced_by=Auth::user()->id;
            $invoice->due_on=$request->date;
            $invoice->due_amount=$quote_approve->grand_total/$total_job;
            $invoice->discount_type=$quote_approve->discount_type;
            $invoice->discount=$quote_approve->discount;
            $invoice->vat=$quote_approve->vat;
            $invoice->grand_total=$quote_approve->grand_total;
            $invoice->status=1;
            $invoice->save();
            $i++;
            }
    }
    // Creating Invoice on Monthly basis
    function invoice_months_type($quote_id,$quote_approve,$jobs){
        $no_of_installments = $quote_approve->no_of_invoive;
        $no_of_invoive=0;
        if($quote_approve->invoice_type=='4'){
            $no_of_invoive=3;
        }elseif ($quote_approve->invoice_type=='5'){
            $no_of_invoive=6;
        }elseif ($quote_approve->invoice_type=='6'){
            $no_of_invoive=12;
        }
        $due_amount_per_invoice = $quote_approve->grand_total / $no_of_invoive;
        $month_gap=$quote_approve->duration / $no_of_invoive;
        $due_date = DateTime::createFromFormat('d-m-Y', $quote_approve->date);
        for ($i = 0; $i < $no_of_invoive; $i++){
            $invoice = new Invoices([
                'client_id' => $quote_approve->client_id,
                'contract_id' => $quote_approve->contract->id,
                'quote_id' => $quote_id,
                'user_id' => $quote_approve->user_id,
                'customer_name' => $quote_approve->client->full_name,
                'customer_number' => $quote_approve->client->phone_number,
                'reference' => $quote_approve->contract_reference,
                'subject' => $quote_approve->quote_title,
                'invoiced_by' => Auth::user()->id,
                'due_on' => $due_date->format('d-m-Y'), // Set the due date for this invoice
                'due_amount' => $due_amount_per_invoice,
                'discount_type' => $quote_approve->discount_type,
                'discount' => $quote_approve->discount,
                'vat' => $quote_approve->vat,
                'grand_total' => $quote_approve->grand_total,
                'status' => 1,
            ]);
            $invoice->save();
            $due_date->modify('+'.(int)$month_gap.' month');
        }
    }

    function monthsDifference($date1, $date2) {
        $datetime1 = DateTime::createFromFormat('d-m-Y', $date1);
        $datetime2 = DateTime::createFromFormat('d-m-Y', $date2);
        $interval = $datetime1->diff($datetime2);
        $monthsDifference = ($interval->y * 12) + $interval->m;
        return $monthsDifference;
    }
    // Single Saving Stock
    function single_stock_updating($supplier_id,$item_id,$qty,$price){
            $stock = new StockIn();
            $stock->supplier_id=$supplier_id;
            $stock->item_id=$item_id;
            $stock->qty=$qty;
            $stock->item_price=$price;
            $stock->total_price=$qty*$price;
            $stock->save();
    }
    // Multiple Saving Stock
    function multiple_stock_updating($requests){
            $i=0;
            foreach ($requests->items as $date){
            $item=Items::find($date['item_id']);
            $stock=$item->total_qty+$date['qty'];
            Items::where('id',$date['item_id'])->update(['total_qty' => $stock,'price' => $date['price']]);
            $stock = new StockIn();
            $stock->supplier_id=$requests->supplier_id;
            $stock->item_name=$item->item_name;
            $stock->p_order_id=$item->p_order_id;
            $stock->item_id=$date['item_id'];
            $stock->qty=$date['qty'];
            $stock->item_price=$date['price'];
            $stock->total_price=$date['total_price'];
            $stock->save();
            $i++;
            }
    }
    // Assigning Stock 
    function assigning_stock_to_employee($requests){$i = 0;
        foreach ($requests->items as $date) {
            $item = Items::find($date['item_id']);
            $stock = $item->total_qty - $date['qty'];
            Items::where('id', $date['item_id'])->update(['total_qty' => $stock]);
            $assignedStock = AssignedStock::where('item_id', $date['item_id'])->where('user_id', $requests->user_id)->first();
            if ($assignedStock) {
                $assignedStock->qty += $date['qty'];
                $assignedStock->save();
            } else {
                $assignedStock = new AssignedStock();
                $assignedStock->user_id = $requests->user_id;
                $assignedStock->item_name = $item->item_name;
                $assignedStock->item_id = $date['item_id'];
                $assignedStock->qty = $date['qty'];
                $assignedStock->save();
            }
            $stockOut = new StockOut();
            $stockOut->user_id = $requests->user_id;
            $stockOut->item_name = $item->item_name;
            $stockOut->item_id = $date['item_id'];
            $stockOut->qty = $date['qty'];
            $stockOut->save();
            $i++;
        }
        return response()->json(['message' => 'Stock has been assigned'], 200);

    }