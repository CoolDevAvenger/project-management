<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helper\Files;
use App\Helper\Reply;
use App\Models\Cost;
use Illuminate\Http\Request;
use App\Models\CostCategory;
use App\Models\StructureCost;
use App\Models\StructureCostItme;
use App\Http\Requests\cost\StoreStructureCostRequest;
use App\DataTables\StructureCostDataTable;

class StructureCostController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.structureCosts';
        $this->pageIcon = 'ti-file';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('estimates', $this->user->modules));
            return $next($request);
        });
    }

    public function index(StructureCostDataTable $dataTable)
    {
        abort_403(!in_array(user()->permission('view_estimates'), ['all', 'added', 'owned', 'both']));

        return $dataTable->render('structure-cost.index', $this->data);

    }

    public function create()
    {
        $this->addPermission = user()->permission('add_estimates');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->pageTitle = __('app.createStructureCost');
        $this->costs = Cost::all();
        $this->costCategory = CostCategory::all();

        if (request()->ajax()) {
            $html = view('structure-cost.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'structure-cost.ajax.create';
        return view('structure-cost.create', $this->data);

    }

    /**
     * Show Help Modal.
     *
     * @return \Illuminate\Http\Response
     */
    public function userHelp(Request $request)
    {
        return view('structure-cost.help-modal');
    }

    public function store(StoreStructureCostRequest $request)
    {
        // dd($request);
        $cost_id = $request->cost_id;
        $items = $request->item_name;
        $cost_price = $request->amount;
        $cost_category_id = $request->cost_category_id;

        if (trim($items[0]) == '' || trim($cost_price[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($cost_price as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        foreach ($cost_category_id as $category_id) {
            if (is_null($category_id)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $structureCost = new StructureCost();
        if (company()) {
            $structureCost->company_id = company()->id;
        }
        $structureCost->added_by = user()->id;
        // $project->updated_by = user()->id;
        $structureCost->structure_cost_no = "STR-00". rand(100,999) . StructureCost::count();
        $structureCost->year = $request->year;
        $structureCost->total_structure_cost = round($request->total, 2);
        $structureCost->hour_number = $request->hour_number;
        $structureCost->estimate_employee_cost = $request->estimate_employee_cost;
        $structureCost->estimate_hour_percent = $request->estimate_hour_percent;
        $structureCost->total_hourly_structure_cost =round($request->total_hourly_structure_cost, 2);
        $structureCost->save();

        foreach ($items as $key => $item) {
            $costItem = new StructureCostItme();
            $costItem->structure_cost_id = $structureCost->id;
            $costItem->cost_id = $cost_id[$key];
            $costItem->cost_name = $item;
            $costItem->cost_price = $cost_price[$key];
            $costItem->cost_category_id = $cost_category_id[$key];
            $costItem->save();
        }
        $this->logSearchEntry($structureCost->id, $structureCost->structure_cost_no, 'structureCost.show', 'structureCost');

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('structureCost.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['structureCostId' => $structureCost->id, 'redirectUrl' => $redirectUrl]);
    }


    public function edit($id)
    {
        $this->structureCost = StructureCost::findOrFail($id);

        $this->editPermission = user()->permission('edit_estimates');

        abort_403(!(
            $this->editPermission == 'all'
            || ($this->editPermission == 'added' && $this->estimate->added_by == user()->id)
            || ($this->editPermission == 'owned' && $this->estimate->client_id == user()->id)
            || ($this->editPermission == 'both' && ($this->estimate->client_id == user()->id || $this->estimate->added_by == user()->id))
        ));

        $this->costs = Cost::all();
        $this->costCategory = CostCategory::all();

        $this->pageTitle = $this->structureCost->structure_cost_no;

        if (request()->ajax()) {
            $html = view('structure-cost.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'structure-cost.ajax.edit';
        return view('structure-cost.create', $this->data);
    }

    public function update(StoreStructureCostRequest $request, $id)
    {
        // dd($request);
        $cost_id = $request->cost_id;
        $cost_ids = $request->cost_ids;
        $items = $request->item_name;
        $cost_price = $request->amount;
        $cost_category_id = $request->cost_category_id;

        if (trim($items[0]) == '' || trim($cost_price[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($cost_price as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }
        foreach ($cost_category_id as $category_id) {
            if (is_null($category_id)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $structureCost = StructureCost::findOrFail($id);
        if (company()) {
            $structureCost->company_id = company()->id;
        }
        // $structureCost->added_by = user()->id;
        $structureCost->updated_by = user()->id;
        // $structureCost->structure_cost_no = "STR-00". rand(100,999) . StructureCost::count();
        $structureCost->year = $request->year;
        $structureCost->total_structure_cost = round($request->total, 2);
        $structureCost->hour_number = $request->hour_number;
        $structureCost->estimate_employee_cost = $request->estimate_employee_cost;
        $structureCost->estimate_hour_percent = $request->estimate_hour_percent;
        $structureCost->total_hourly_structure_cost =round($request->total_hourly_structure_cost, 2);
        $structureCost->save();

        if (!empty($request->item_name) && is_array($request->item_name)) {
            // Step1 - Delete all invoice items which are not avaialable
            if (!empty($cost_ids)) {
                StructureCostItme::whereNotIn('id', $cost_ids)->where('structure_cost_id', $structureCost->id)->delete();
            }
            foreach ($items as $key => $item) {
                $cost_item_id = isset($cost_ids[$key]) ? $cost_ids[$key] : 0;

                // $costItem = StructureCostItme::findOrFail($cost_item_id);
                // if(is_null($costItem)) {
                //     $costItem = new StructureCostItme();
                // }
                try {
                    $costItem = StructureCostItme::findOrFail($cost_item_id);
                } catch(Exception $e) {
                    $costItem = new StructureCostItme();
                }
                $costItem->structure_cost_id = $structureCost->id;
                $costItem->cost_id = $cost_id[$key];
                $costItem->cost_name = $item;
                $costItem->cost_price = $cost_price[$key];
                $costItem->cost_category_id = $cost_category_id[$key];
                $costItem->save();
            }
        }
        $this->logSearchEntry($structureCost->id, $structureCost->structure_cost_no, 'structureCost.show', 'structureCost');

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('structureCost.index');
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['structureCostId' => $structureCost->id, 'redirectUrl' => $redirectUrl]);
    }

    public function destroy($id)
    {
        $structureCost = StructureCost::findOrFail($id);

        $this->deletePermission = user()->permission('delete_estimates');

        abort_403(!(
            $this->deletePermission == 'all'
            || ($this->deletePermission == 'added' && $estimate->added_by == user()->id)
            || ($this->deletePermission == 'owned' && $estimate->client_id == user()->id)
            || ($this->deletePermission == 'both' && ($estimate->client_id == user()->id || $structureCost->added_by == user()->id))
        ));

        StructureCost::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));

    }

}
