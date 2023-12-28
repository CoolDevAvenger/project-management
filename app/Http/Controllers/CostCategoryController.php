<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\BaseModel;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use App\Http\Requests\Cost\StoreCostCategory;

class CostCategoryController extends AccountBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.costCategories';
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

        // $this->cost = Cost::all();
        $this->costCategory = CostCategory::all();
        $this->view = 'cost-category.ajax.category';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('cost-category.index', $this->data);
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
        $this->categories = CostCategory::all();
        return view('cost-category.create-cost-category-modal', $this->data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function createCostCategory()
    // {
    //     $this->categories = CostCategory::all();
    //     return view('service-category.create-service-category-modal', $this->data);
    // }

    /**
     * @param StoreCostCategory $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreCostCategory $request)
    {
        $category = new CostCategory();
        if (company()) {
            $category->company_id = company()->id;
        }
        $category->category_name = $request->category_name;
        $category->save();

        $categories = CostCategory::get();
        //$options = BaseModel::options($categories, $category, 'category_name');
        $options = $this->categoryDropdown($category->id);

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $options]);
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

        return $categoryOptions;
    }


    public function edit($id)
    {
        $this->costCategory = CostCategory::findOrfail($id);

        return view('cost-category.edit-category', $this->data);
    }

    /**
     * @param StoreCostCategory $request
     * @param int $id
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(StoreCostCategory $request, $id)
    {
        $category = CostCategory::findOrFail($id);
        $category->category_name = strip_tags($request->category_name);
        $category->save();

        $categories = CostCategory::get();
        $options = BaseModel::options($categories, null, 'category_name');

        return Reply::successWithData(__('messages.updateSuccess'), ['data' => $options]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CostCategory::destroy($id);
        $categoryData = CostCategory::all();
        return Reply::successWithData(__('messages.deleteSuccess'), ['data' => $categoryData]);
    }

    public function getCategories($id)
    {
        $product_categories = ($id == 'null') ? CostCategory::get() : CostCategory::where('id', $id)->get();

        return Reply::dataOnly(['status' => 'success', 'data' => $product_categories]);
    }

}
