<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Project\StoreProjectSubCategory;
use App\Models\ProjectCategory;
use App\Models\ProjectSubCategory;
use Illuminate\Http\Request;

class ProjectSubCategoryController extends AccountBaseController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->categoryID = $request->catID;
        $this->subcategories = ProjectSubCategory::all();
        $this->categories = ProjectCategory::all();
        return view('projects.sub-category.create', $this->data);
    }

    /**
     * @param StoreProjectSubCategory $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreProjectSubCategory $request)
    {
        $category = new ProjectSubCategory();
        $category->category_id = $request->category_id;
        $category->company_id = $request->category_id;
        $category->category_name = $request->category_name;
        $category->save();

        $categoryData = ProjectCategory::get();
        $subCategoryData = ProjectSubCategory::get();
        $category = '';
        $subCategory = '';

        foreach ($subCategoryData as $item) {
            $subCategory .= '<option value='.$item->id.'>'.ucwords($item->category_name).'</option>';
        }

        foreach ($categoryData as $data) {
            $category .= '<option value='.$data->id.'>'.ucwords($data->category_name).'</option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $category, 'subCategoryData' => $subCategory]);
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
        $category = ProjectSubCategory::findOrFail($id);
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
        ProjectSubCategory::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    public function categoryDropdown($selectId = null)
    {
        /* Category Dropdown */
        $categoryData = ProjectCategory::get();
        $categoryOptions = '<option value="">--</option>';

        foreach ($categoryData as $item) {
            $selected = '';

            if (!is_null($selectId) && $item->id == $selectId) {
                $selected = 'selected';
            }

            $categoryOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->category_name . ' </option>';
        }

        return $categoryOptions;
    }

    public function subCategoryDropdown($selectId)
    {
        /* Sub-Category Dropdown */
        $subCategoryData = ProjectSubCategory::get();
        $subCategoryOptions = '<option value="">--</option>';

        foreach ($subCategoryData as $item) {
            $selected = '';

            if ($item->id == $selectId) {
                $selected = 'selected';
            }

            $subCategoryOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->category_name . ' </option>';
        }

        return $subCategoryOptions;
    }

    public function getSubCategories($id)
    {
        $sub_categories = ($id == 'null') ? ProjectSubCategory::get() : ProjectSubCategory::where('category_id', $id)->get();

        return Reply::dataOnly(['status' => 'success', 'data' => $sub_categories]);
    }

}
