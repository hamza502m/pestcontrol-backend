<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotes;
use App\Repositories\JobRepository;
use App\Models\QuotesFrequency;
use App\Notifications\AppNotification;
use App\Models\QuotedServices;

class QuotesController extends Controller
{
    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepo = $jobRepository;
    }
	public function index()
    {
            $quotes = Quotes::with('quoted_services')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
            // $quotes->map(function ($quote) {
            //     $quote->service_agreements = $quote->service_agreements;
            //     return $quote;
            // });
             if($quotes){
                 	return response()->json(['data' => $quotes]);
                 }
             else{
                 	return response()->json(['data' => 'No data']);
                 }
    }


    public function single_qoute($id)
    {
            $quote = Quotes::with('quoted_services')->find($id);
            if($quote){
                $quote->service_Agreements = $quote->service_agreements;
             	return response()->json(['data' => $quote]);
             }
             else{
             	return response()->json(['data' => 'No data']);
             }
    }

    // Storing Value of Qoutes
    public function store(Request $request)
    {
            $quote = new Quotes();
            $quote->quote_title=$request->quote_title;
            $quote->user_id=$request->user_id;
            $quote->client_id=$request->client_id;
            $quote->contract_reference=$request->contract_reference;
            $quote->firm=$request->firm;
            $quote->job_type=$request->job_type;
            $quote->food_watch_account=$request->food_watch_account;
            $quote->date=$request->date;
            $quote->due_date=$request->due_date;
            $quote->subject=$request->subject;
            $quote->service_ids=json_encode($request->service_ids);
            $quote->tm_ids=json_encode($request->tm_ids);
            $quote->description=$request->description;
            $quote->duration=$request->duration;
            $quote->discount_type=$request->discount_type;
            $quote->discount=$request->discount;
            $quote->vat=$request->vat;                                            
            $quote->trn=$request->trn;                                            
            $quote->status=$request->status;
            $quote->follow_up_date=$request->follow_up_date;
            $quote->remarks=$request->remarks;
            $quote->invoice_type=$request->invoice_type;
            $quote->no_of_invoive=$request->no_of_invoive;
            $quote->grand_total=$request->grand_total;
            $quote->save();
            if($quote){
                quoted_services($quote->id,$request,Quotes::class);
                save_tags($request->tags,$quote->id,Quotes::class);
                // $this->frequency_creation($quote->id,$request);
            }
            $message="A quote has been added into system by".$quote->quote_title;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Quote Added',
                'data' => $quote
            ]);
    }
/*    public function frequency_creation($quote_id,$requests){
            $i=0;
            foreach ($requests->frequency as $request) {
            $quoted_service = new QuotesFrequency();
            $quoted_service->quote_id=$quote_id;
            $quoted_service->frequencey=$request['frequencey'];
            $quoted_service->service_id=$request['service_id'];
            $quoted_service->turn=$request['turn'];
            $quoted_service->date=$request['date'];
            $quoted_service->day=$request['day'];
            $quoted_service->save();
            $i++;
            }
    }*/
    public function qoute_approving($id,Request $request){
            $quote_approve = Quotes::find($id);
            $month=monthsDifference($quote_approve->date,$quote_approve->due_date);
            if($month<3 AND $quote_approve->invoice_type=='4'){
                return response()->json([
                'Error'=>'Please select another inovice type'
                ]);
            }elseif($month<6 AND $quote_approve->invoice_type=='5'){
                return response()->json([
                'Error'=>'Please select another inovice type'
                ]);
            }elseif($month<12 AND $quote_approve->invoice_type=='6'){
                return response()->json([
                'Error'=>'Please select another inovice type'
                ]);
            }
            $quote_approve->status=$request->status;
            $quote_approve->save();
            if($quote_approve){
                contract_creations($quote_approve->id,$quote_approve);
                $jobs=$this->jobRepo->job_creation_with_quote($quote_approve->id,$quote_approve);
            }
            if($quote_approve->invoice_type=='1'){
            invoice_installment_type($quote_approve->id,$quote_approve,$jobs);
            }elseif ($quote_approve->invoice_type=='2') {
            invoice_service_type($quote_approve->id,$quote_approve,$jobs);
            }else{
            invoice_months_type($quote_approve->id,$quote_approve,$jobs);
            }
            $message="A quote has been Approved ".$quote_approve->quote_title;
            // auth()->user()->notify(new PasswordNotification($message));
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Quote has been Approved',
                'data' => $quote_approve
            ]);
    }
    public function job_creation($id,$request){
    }
}
