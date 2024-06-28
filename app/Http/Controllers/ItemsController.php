<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ItemsRepository;

class ItemsController extends Controller
{
     /**
     * @var ItemsRepository
     */
    private $itemsRepository;

    public function __construct(ItemsRepository $itemsRepository)
    {
        $this->Items = $itemsRepository;
    }
    public function store(Request $request)
    {
        return $this->Items->create($request);
    }
    public function get_items(Request $request)
    {
        return $this->Items->all($request);
    }
    public function get_item($id)
    {
        return $this->Items->find($id);
    }
    // Brands
    public function store_brand(Request $request)
    {
        return $this->Items->creating_brand($request);
    }
    public function get_brands(Request $request)
    {
        return $this->Items->all_brands($request);
    }
    public function get_brand($id)
    {
        return $this->Items->find_brand($id);
    }
    // Item Type
    public function store_item_type(Request $request)
    {
        return $this->Items->creating_item_type($request);
    }
    public function get_item_types(Request $request)
    {
        return $this->Items->all_item_types($request);
    }
    public function get_item_type($id)
    {
        return $this->Items->item_type($id);
    }
    // Item Unites
    public function store_item_unit(Request $request)
    {
        return $this->Items->store_item_unit($request);
    }
    public function get_item_units(Request $request)
    {
        return $this->Items->get_item_units($request);
    }
    public function get_item_unit($id)
    {
        return $this->Items->get_item_unit($id);
    }
}
