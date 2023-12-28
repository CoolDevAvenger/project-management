<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\cost\StoreCostRequest;
use App\Models\Cost;
use App\Models\CostCategory;
use Illuminate\Http\Request;

class CostController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.costs';
        $this->middleware(function ($request, $next) {
            in_array('client', user_roles()) ? abort_403(!(in_array('orders', $this->user->modules) && user()->permission('add_order') == 'all')) : abort_403(!in_array('products', $this->user->modules));

            return $next($request);
        });
    }

    /**
     * @param ProductsDataTable $dataTable
     * @return mixed|void
     */
    public function index()
    {
        $viewPermission = user()->permission('view_product');
        abort_403(!in_array($viewPermission, ['all', 'added']));

        $this->costs = Cost::all();
        $this->categories = CostCategory::all();
        $this->view = 'costs.ajax.cost';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('costs.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->page = $request->page ? $request->page : null;
        $this->item_no = $request->item_no ? $request->item_no : null;
        $this->categoryID = $request->catID;
        $this->categories = CostCategory::all();

        return view('costs.create-cost-modal', $this->data);
    }

    /**
     * @param StoreCostRequest $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreCostRequest $request)
    {
        $cost = new Cost();
        if (company()) {
            $cost->company_id = company()->id;
        }
        $cost->name = $request->name;
        $cost->category_id = $request->category_id;
        $cost->price = $request->price;
        $cost->save();

        $options = $this->costDropdown($cost->id);
        $categoryOptions = $this->categoryDropdown($cost->category_id);

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $options, 'categoryOptions' => $categoryOptions]);
    }


    public function costDropdown($selectId = null)
    {
        /* Product Dropdown */
        $costData = Cost::get();
        $costOptions = '<option value="">Select Cost</option>';

        foreach ($costData as $item) {
            $selected = '';
            if (!is_null($selectId) && $item->id == $selectId) {
                $selected = 'selected';
            }
            $costOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->name . ' </option>';
        }

        $segments = request()->segments();
        if($segments[1] == "cost-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $costOptions]);

        return $costOptions;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cost = Cost::findOrFail($id);
        $cost->category_id = $request->category_id ? $request->category_id : $cost->category_id;
        $cost->name = $request->name ? strip_tags($request->name) : $cost->name;
        $cost->price = $request->price ? strip_tags($request->price) : $cost->price;
        $cost->save();

        $categoryDropdown = $this->categoryDropdown($cost->category_id);

        return Reply::successWithData(__('messages.updateSuccess'), ['categories' => $categoryDropdown]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cost::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    public function categoryDropdown($selectId = null)
    {
        /* Category Dropdown */
        $categoryData = CostCategory::get();
        $categoryOptions = '<option value="">Select Cost Category</option>';

        foreach ($categoryData as $item) {
            $selected = '';

            if (!is_null($selectId) && $item->id == $selectId) {
                $selected = 'selected';
            }

            $categoryOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->category_name . ' </option>';
        }

        $segments = request()->segments();
        if($segments[1] == "cost-category-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $categoryOptions]);

        return $categoryOptions;
    }

    public function getCostByCategory($id)
    {
        $cost = ($id == 'null') ? Cost::get() : Cost::where('category_id', $id)->get(['id', 'name', 'price']);

        return Reply::dataOnly(['status' => 'success', 'data' => $cost]);
    }

    public function getCost($id)
    {
        $cost = ($id == 'null') ? Cost::get() : Cost::where('id', $id)->get(['id','name', 'price']);

        return Reply::dataOnly(['status' => 'success', 'data' => $cost]);
    }

}
