<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductSubCategory;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.serviceSubcategories';
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

        $this->subcategories = ProductSubCategory::all();
        $this->categories = ProductCategory::all();
        $this->view = 'service-sub-category.ajax.sub-category';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('service-sub-category.index', $this->data);
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
        $this->subcategories = ProductSubCategory::all();
        $this->categories = ProductCategory::all();
        return view('products.sub-category.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSubCategory(Request $request)
    {
        $this->categoryID = $request->catID;
        $this->subcategories = ProductSubCategory::all();
        $this->categories = ProductCategory::all();
        return view('service-sub-category.create-service-sub-category', $this->data);
    }

    /**
     * @param StoreProductSubCategory $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreProductSubCategory $request)
    {
        $category = new ProductSubCategory();
        $category->category_id = $request->category_id;
        $category->category_name = $request->category_name;
        $category->save();

        $categoryOptions = $this->categoryDropdown($category->category_id);
        $subCategoryOptions = $this->subCategoryDropdown($category->id);

        $categoryData = ProductCategory::get();
        $subCategoryData = ProductSubCategory::get();
        $category = '';
        $subCategory = '';

        foreach ($subCategoryData as $item) {
            $subCategory .= '<option value='.$item->id.'>'.ucwords($item->category_name).'</option>';
        }

        foreach ($categoryData as $data) {
            $category .= '<option value='.$data->id.'>'.ucwords($data->category_name).'</option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $category, 'subCategoryData' => $subCategory, 'subCategoryOptions' => $subCategoryOptions, 'categoryOptions' => $categoryOptions]);
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
        $category = ProductSubCategory::findOrFail($id);
        $category->category_id = $request->category_id ? $request->category_id : $category->category_id;
        $category->category_name = $request->category_name ? strip_tags($request->category_name) : $category->category_name;
        $category->save();

        $subCategoryOptions = $this->categoryDropdown($category->category_id);
        $categoryOptions = $this->subCategoryDropdown($category->id);

        return Reply::successWithData(__('messages.updateSuccess'), ['sub_categories' => $subCategoryOptions, 'categories' => $categoryOptions]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductSubCategory::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
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

        $segments = request()->segments();
        if($segments[1] == "product-category-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $categoryOptions]);

        return $categoryOptions;
    }

    public function subCategoryDropdown($selectId=null)
    {
        /* Sub-Category Dropdown */
        $subCategoryData = ProductSubCategory::get();
        $subCategoryOptions = '<option value="">Select Service Sub Category</option>';

        foreach ($subCategoryData as $item) {
            $selected = '';

            if ($item->id == $selectId) {
                $selected = 'selected';
            }

            $subCategoryOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->category_name . ' </option>';
        }

        $segments = request()->segments();
        if($segments[1] == "product-sub-category-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $subCategoryOptions]);

        return $subCategoryOptions;
    }

    public function getSubCategories($id)
    {
        $sub_categories = ($id == 'null') ? ProductSubCategory::get() : ProductSubCategory::where('category_id', $id)->get();

        return Reply::dataOnly(['status' => 'success', 'data' => $sub_categories]);
    }

    public function getSelectedServiceSubCategories($id)
    {
        $sub_categories = ($id == 'null') ? ProductSubCategory::get() : ProductSubCategory::where('id', $id)->get();

        return Reply::dataOnly(['status' => 'success', 'data' => $sub_categories]);
    }

}
