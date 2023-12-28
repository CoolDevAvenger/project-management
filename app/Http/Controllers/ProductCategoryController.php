<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductCategory;
use App\Models\BaseModel;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends AccountBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.serviceCategories';
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

        $this->serviceCategory = ProductCategory::all();
        $this->view = 'service-category.ajax.category';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('service-category.index', $this->data);
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
        $this->categories = ProductCategory::all();
        return view('products.category.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProductCategory()
    {
        $this->categories = ProductCategory::all();
        return view('service-category.create-service-category-modal', $this->data);
    }

    /**
     * @param StoreProductCategory $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreProductCategory $request)
    {
        $category = new ProductCategory();
        $category->category_name = $request->category_name;
        $category->save();

        $categories = ProductCategory::get();
        //$options = BaseModel::options($categories, $category, 'category_name');
        $options = $this->categoryDropdown($category->id);

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $options]);
    }

    public function categoryDropdown($selectId = null)
    {
        /* Category Dropdown */
        $categoryData = ProductCategory::get();
        $categoryOptions = '<option value="">Select Service Category</option>';

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
        $this->serviceCategory = ProductCategory::findOrfail($id);

        return view('service-category.edit-category', $this->data);
    }

    /**
     * @param StoreProductCategory $request
     * @param int $id
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(StoreProductCategory $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->category_name = strip_tags($request->category_name);
        $category->save();

        $categories = ProductCategory::get();
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
        ProductCategory::destroy($id);
        $categoryData = ProductCategory::all();
        return Reply::successWithData(__('messages.deleteSuccess'), ['data' => $categoryData]);
    }

    public function getCategories($id)
    {
        $product_categories = ($id == 'null') ? ProductCategory::get() : ProductCategory::where('id', $id)->get();

        return Reply::dataOnly(['status' => 'success', 'data' => $product_categories]);
    }

}
