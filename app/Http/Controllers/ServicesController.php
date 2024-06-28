<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Http\Controllers\Controller;
use App\Notifications\AppNotification;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
     /**
     * @var ServiceRepository
     */
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->services = $serviceRepository;
    }
    public function index(Request $request)
    {
        return $this->services->all($request);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->services->create($request);
    }
    /**
     * Display the specified resource.
     */
    public function single_qoute($id)
    { 
        return $this->services->find($id);
    }

    /**
     * Service Report
     */
    public function service_report(Request $request)
    {
        return $this->services->service_report($request);
    }
    public function all_service_report(Request $request)
    {
        return $this->services->all_service_report($request);
    }
    public function single_service_report($id)
    {
        return $this->services->single_service_report($id);
    }
}
