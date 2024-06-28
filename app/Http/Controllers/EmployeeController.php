<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{   
    /**
     * @var EmployeeRepository
     */
    private $employeesRepository;

    public function __construct(EmployeeRepository $employeesRepository)
    {
        $this->employees = $employeesRepository;
    }
    //Get all employees 
    public function index(Request $request){
        return $this->employees->all($request);
    }
    //Get single employee by id 
    public function single_employee($id)
    {
        return $this->employees->find($id);
    }
    // saving data
    public function store(Request $request)
    {   
        return $this->employees->create($request);
    }
    // saving data
    public function assigning_stock(Request $request)
    {   
        return $this->employees->assigning_stock($request);
    }
}
