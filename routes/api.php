<?php

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\{UserAuthController,ClientController,PermissionController,EmployeeController,QuotesController,ContractController,JobController,ServicesController,VendorController,SuppliersController,SettingController,ItemsController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
  // Clients
  Route::post('add_client',[ClientController::class,'store']);
  Route::post('add_client_addressess',[ClientController::class,'client_addressess']);
  Route::get('get_clients',[ClientController::class,'index']);
  Route::get('single_client/{id}',[ClientController::class,'singleClint']);
  // Permissions
  Route::post('add-permission',[PermissionController::class,'store']);
  Route::post('update-permission/{id}',[PermissionController::class,'updatePermission']);
  Route::post('add-role',[PermissionController::class,'addRole']);
  Route::post('update-role/{id}',[PermissionController::class,'updateRole']);
  Route::post('updatePermissionbyuser/{id}',[PermissionController::class,'updatePermissionbyuser']);
  Route::get('allPermissionbyuser/{id}',[UserAuthController::class,'allPermissionbyuser']);
  Route::get('markasread/{id}',[ClientController::class,'markasread']);
  // Jobs
  Route::post('create-job',[JobController::class,'store']);
  Route::get('getjobs',[JobController::class,'index']);
  Route::get('singlejob/{id}',[JobController::class,'singleJob']);
  Route::post('assigning-job',[JobController::class,'assigning_job_to_team']);
  Route::get('getting_assign_jobs',[JobController::class,'getting_assign_jobs']);
  Route::post('reschedual-job',[JobController::class,'reschedual_job']);
  Route::post('job-status',[JobController::class,'job_status']);

  // Employee
  Route::post('add_employee',[EmployeeController::class,'store']);
  Route::post('assigning-stock',[EmployeeController::class,'assigning_stock']);
  Route::get('get_employees',[EmployeeController::class,'index']);
  Route::get('single_employee/{id}',[EmployeeController::class,'single_employee']);
  // Vendors
  Route::post('add_vendor',[VendorController::class,'store']);
  Route::get('get_vendors',[VendorController::class,'index']);
  Route::get('single_vendor/{id}',[VendorController::class,'single_vendor']);
  // Service of Agreements 
  Route::post('add-agreement',[SettingController::class,'service_agreement']);
  Route::get('get_services',[SettingController::class,'services']);
  Route::get('get_service/{id}',[SettingController::class,'service']);
  // Treatment Methods 
  Route::post('add-treatement_method',[SettingController::class,'add_treatment_method']);
  Route::get('get_treatement_methods',[SettingController::class,'treatment_methods']);
  Route::get('get_treatement_method/{id}',[SettingController::class,'treatment_method']);
  // Treatment Methods 
  Route::post('add-qoute',[QuotesController::class,'store']);
  Route::get('get_qoutes',[QuotesController::class,'index']);
  Route::get('get_qoute/{id}',[QuotesController::class,'single_qoute']);
  Route::post('qoute_approving/{id}',[QuotesController::class,'qoute_approving']);
  // Services Routes 
  Route::post('create-service',[ServicesController::class,'store']);
  Route::get('get_services',[ServicesController::class,'index']);
  Route::get('get_service/{id}',[ServicesController::class,'single_qoute']);

  // Contract
  Route::post('create-contract',[ContractController::class,'store']);
  Route::get('get_contracts',[ContractController::class,'index']);
  Route::get('get_contract/{id}',[ContractController::class,'single_contract']);

  // Countries
  Route::post('add-country',[SettingController::class,'save_countries']);
  Route::post('add-city',[SettingController::class,'save_city']);
  Route::get('get_countries',[SettingController::class,'get_countries']);
  Route::get('get_country/{id}',[SettingController::class,'get_country']);
  // States
  Route::post('add-state',[SettingController::class,'save_state']);
  Route::get('get_states',[SettingController::class,'get_states']);
  Route::get('get_state/{id}',[SettingController::class,'get_state']);
  // Items
  Route::post('add-item',[ItemsController::class,'store']);
  Route::get('get_items',[ItemsController::class,'get_items']);
  Route::get('get_item/{id}',[ItemsController::class,'get_item']);
  // Brands
  Route::post('add-brand',[ItemsController::class,'store_brand']);
  Route::get('get_brands',[ItemsController::class,'get_brands']);
  Route::get('get_brand/{id}',[ItemsController::class,'get_brand']);
  // Item types
  Route::post('add-item-type',[ItemsController::class,'store_item_type']);
  Route::get('all_item_type',[ItemsController::class,'get_item_types']);
  Route::get('get_tem_type/{id}',[ItemsController::class,'get_item_type']);
  // Item  Units
  Route::post('add-units',[ItemsController::class,'store_item_unit']);
  Route::get('all_units/{type?}',[ItemsController::class,'get_item_units']);
  Route::get('get_units/{id}',[ItemsController::class,'get_item_unit']);
  // Service Report 
  Route::post('create-service-report',[ServicesController::class,'service_report']);
  Route::get('get_service_reports',[ServicesController::class,'all_service_report']);
  Route::get('get_service_report/{id}',[ServicesController::class,'single_service_report']);
  // Suppliers
  Route::post('add-supplier',[SuppliersController::class,'store']);
  Route::get('get_suppliers',[SuppliersController::class,'get_suppliers']);
  Route::get('get_supplier/{id}',[SuppliersController::class,'get_supplier']);
  // order purchasing
  Route::post('add_order_purchase',[SuppliersController::class,'OrderPurchased']);
});