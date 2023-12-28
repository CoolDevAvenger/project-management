<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesDataTable;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Expenses\StoreExpense;
use App\Http\Requests\Expenses\UpdateExpense;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ExpensesCategory;
use App\Models\ExpensesCategoryRole;
use App\Models\Project;
use App\Models\RoleUser;
use App\Models\User;
use App\Scopes\ActiveScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewExpenseEvent;

class ExpenseController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.expenses';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('expenses', $this->user->modules));
            return $next($request);
        });
    }

    public function index(ExpensesDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_expenses');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        if (!request()->ajax()) {
            $this->employees = User::allEmployees(null, true);
            $this->projects = Project::allProjects();
            $this->categories = ExpenseCategoryController::getCategoryByCurrentRole();
        }

        return $dataTable->render('expenses.index', $this->data);

    }

    public function changeStatus(Request $request)
    {
        abort_403(user()->permission('approve_expenses') != 'all');

        $expenseId = $request->expenseId;
        $status = $request->status;
        $expense = Expense::findOrFail($expenseId);
        $expense->status = $status;
        $expense->save();
        return Reply::success(__('messages.updateSuccess'));
    }

    public function show($id)
    {
        $this->expense = Expense::with(['user', 'project', 'category', 'transactions' => function($q){
            $q->orderBy('id', 'desc')->limit(1);
        }, 'transactions.bankAccount'])->findOrFail($id)->withCustomFields();

        $this->viewPermission = user()->permission('view_expenses');
        $viewProjectPermission = user()->permission('view_project_expenses');
        $this->editExpensePermission = user()->permission('edit_expenses');
        $this->deleteExpensePermission = user()->permission('delete_expenses');

        abort_403(!($this->viewPermission == 'all'
        || ($this->viewPermission == 'added' && $this->expense->added_by == user()->id)
        || ($viewProjectPermission == 'owned' || $this->expense->user_id == user()->id)));

        if (!empty($this->expense->getCustomFieldGroupsWithFields())) {
            $this->fields = $this->expense->getCustomFieldGroupsWithFields()->fields;
        }

        $this->pageTitle = $this->expense->item_name;

        if (request()->ajax()) {
            $html = view('expenses.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'expenses.ajax.show';
        return view('expenses.show', $this->data);

    }

    public function create(Request $request)
    {
        $this->addPermission = user()->permission('add_expenses');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->currencies = Currency::all();
        $this->categories = ExpenseCategoryController::getCategoryByCurrentRole();
        $this->linkExpensePermission = user()->permission('link_expense_bank_account');
        $this->viewBankAccountPermission = user()->permission('view_bankaccount');

        $bankAccounts = BankAccount::where('status', 1)->where('currency_id', company()->currency_id);

        if($this->viewBankAccountPermission == 'added'){
            $bankAccounts = $bankAccounts->where('added_by', user()->id);
        }

        $bankAccounts = $bankAccounts->get();
        $this->bankDetails = $bankAccounts;
        $this->companyCurrency = Currency::where('id', company()->currency_id)->first();

        // Get only current login employee projects
        if ($this->addPermission == 'added') {
            $this->projects = Project::where('added_by', user()->id)->orWhereHas('projectMembers', function ($query) {
                $query->where('user_id', user()->id);
            })->get();

        } else {
            $this->projects = Project::all();
        }

        $this->page = $request->page ? $request->page : null;
        $this->item_no = $request->item_no ? $request->item_no : null;

        $this->pageTitle = __('modules.expenses.addExpense');
        $this->projectId = request('project_id') ? request('project_id') : null;

        if (!is_null($this->projectId)) {
            $employees = Project::with('projectMembers')->where('id', $this->projectId)->first();
            $this->projectName = $employees->project_name;
            $this->employees = $employees->projectMembers;

        } else {
            $this->employees = User::allEmployees(null, true);
        }

        $expense = new Expense();

        if (!empty($expense->getCustomFieldGroupsWithFields())) {
            $this->fields = $expense->getCustomFieldGroupsWithFields()->fields;
        }

        if (request()->ajax()) {
            $html = view('expenses.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'expenses.ajax.create';
        return view('expenses.show', $this->data);

    }

    public function store(StoreExpense $request)
    {
        $userRole = session('user_roles');
        $expense = new Expense();
        $expense->item_name = $request->item_name;
        $expense->purchase_date = Carbon::createFromFormat($this->company->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from = $request->purchase_from;
        $expense->price = round($request->price, 2);
        $expense->currency_id = $request->currency_id;
        $expense->category_id = $request->category_id;
        $expense->user_id = user()->id;
        $expense->default_currency_id = company()->currency_id;
        $expense->exchange_rate = $request->exchange_rate;
        $expense->description = trim_editor($request->description);

        // if ($userRole[0] == 'admin') {
        //     $expense->status = 'approved';
        //     $expense->approver_id = user()->id;
        // }

        // if ($request->has('status')) {
        //     $expense->status = $request->status;
        //     $expense->approver_id = user()->id;
        // }

        $expense->approver_id = null;
        $expense->status = 'pending';

        if ($request->has('project_id') && $request->project_id != '0') {
            $expense->project_id = $request->project_id;
        }

        if ($request->hasFile('bill')) {
            $filename = Files::uploadLocalOrS3($request->bill, Expense::FILE_PATH);
            $expense->bill = $filename;
        }

        $expense->bank_account_id = $request->bank_account_id;

        $expense->save();

        if (!isRunningInConsoleOrSeeding()) {

            if ($expense->user_id == user()->id) {
                event(new NewExpenseEvent($expense, 'member'));

            }
            else {
                event(new NewExpenseEvent($expense, 'member'));
                event(new NewExpenseEvent($expense, 'admin'));
            }
        }

        $options = $this->expenseDropdown($expense->id);
        $categoryOptions = $this->categoryDropdown($expense->category_id);

        // To add custom fields data
        if ($request->custom_fields_data) {
            $expense->updateCustomFieldData($request->custom_fields_data);
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('expenses.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl, 'options' => $options, 'categoryOptions' => $categoryOptions]);
    }

    public function expenseDropdown($selectId = null)
    {
        /* Expense Dropdown */
        $expenseData = Expense::where('project_id', null)->get();
        $expenseOptions = '<option value="">Select Expense</option>';

        foreach ($expenseData as $item) {
            $selected = '';
            if (!is_null($selectId) && $item->id == $selectId) {
                $selected = 'selected';
            }
            $expenseOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->item_name . ' </option>';
        }

        $segments = request()->segments();
        if($segments[1] == "expense-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $expenseOptions]);

        return $expenseOptions;
    }

    public function categoryDropdown($selectId = null)
    {
        /* Category Dropdown */
        $categoryData = ExpensesCategory::get();
        $categoryOptions = '<option value="">Select Expense Category</option>';

        foreach ($categoryData as $item) {
            $selected = '';

            if (!is_null($selectId) && $item->id == $selectId) {
                $selected = 'selected';
            }

            $categoryOptions .= '<option ' . $selected . ' value="' . $item->id . '"> ' . $item->category_name . ' </option>';
        }

        $segments = request()->segments();
        if($segments[1] == "expense-category-dropdown")
            return Reply::dataOnly(['status' => 'success', 'data' => $categoryOptions]);

        return $categoryOptions;
    }

    public function edit($id)
    {
        $this->expense = Expense::findOrFail($id)->withCustomFields();
        $this->editPermission = user()->permission('edit_expenses');

        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->expense->added_by == user()->id)));

        // $this->currencies = Currency::all();
        $this->currencies = Currency::where('id', company()->currency_id)->get();
        $this->categories = ExpenseCategoryController::getCategoryByCurrentRole();
        $this->employees = User::allEmployees();
        $this->pageTitle = __('modules.expenses.updateExpense');
        $this->linkExpensePermission = user()->permission('link_expense_bank_account');
        $this->viewBankAccountPermission = user()->permission('view_bankaccount');

        $bankAccounts = BankAccount::where('status', 1)->where('currency_id', $this->expense->currency_id);

        if($this->viewBankAccountPermission == 'added'){
            $bankAccounts = $bankAccounts->where('added_by', user()->id);
        }

        $bankAccounts = $bankAccounts->get();
        $this->bankDetails = $bankAccounts;


        $userId = $this->expense->user_id;

        // if (!is_null($userId)) {
        //     $this->projects = Project::with('members')->whereHas('members', function ($q) use ($userId) {
        //         $q->where('user_id', $userId);
        //     })->get();
        // }
        // else {
        //     $this->projects = Project::get();
        // }
        $this->projects = Project::get();

        $this->companyCurrency = Currency::where('id', company()->currency_id)->first();

        $expense = new Expense();

        if (!empty($expense->getCustomFieldGroupsWithFields())) {
            $this->fields = $expense->getCustomFieldGroupsWithFields()->fields;
        }

        if (request()->ajax()) {
            $html = view('expenses.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'expenses.ajax.edit';
        return view('expenses.show', $this->data);

    }

    public function update(UpdateExpense $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->item_name = $request->item_name;
        $expense->purchase_date = Carbon::createFromFormat($this->company->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from = $request->purchase_from;
        $expense->price = round($request->price, 2);
        $expense->actual_price = round($request->actual_price, 2);
        $expense->currency_id = $request->currency_id;
        $expense->user_id = $request->user_id;
        $expense->category_id = $request->category_id;
        $expense->default_currency_id = company()->currency_id;
        $expense->exchange_rate = $request->exchange_rate;
        $expense->description = trim_editor($request->description);

        if ($request->project_id) {
            $expense->project_id = ($request->project_id > 0) ? $request->project_id : null;
        }

        if ($request->bill_delete == 'yes') {
            Files::deleteFile($expense->bill, Expense::FILE_PATH);
            $expense->bill = null;
        }

        if ($request->hasFile('bill')) {
            Files::deleteFile($expense->bill, Expense::FILE_PATH);

            $filename = Files::uploadLocalOrS3($request->bill, Expense::FILE_PATH);
            $expense->bill = $filename;
        }
        if ($request->status) {
            $expense->status = $request->status;
        }
        $expense->bank_account_id = $request->bank_account_id;
        $expense->save();

        // To add custom fields data
        if ($request->custom_fields_data) {
            $expense->updateCustomFieldData($request->custom_fields_data);
        }

        if ($request->taskProjectId != '') {
            return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('expenses.index').'?projectId='.$request->taskProjectId]);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('expenses.index')]);

    }

    public function destroy($id)
    {
        $this->expense = Expense::findOrFail($id);
        $this->deletePermission = user()->permission('delete_expenses');
        abort_403(!($this->deletePermission == 'all' || ($this->deletePermission == 'added' && $this->expense->added_by == user()->id)));

        Expense::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
        case 'delete':
            $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
        case 'change-status':
            $this->changeBulkStatus($request);
                return Reply::success(__('messages.updateSuccess'));
        default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_employees') != 'all');

        Expense::withoutGlobalScope(ActiveScope::class)->whereIn('id', explode(',', $request->row_ids))->delete();
    }

    protected function changeBulkStatus($request)
    {
        abort_403(user()->permission('edit_employees') != 'all');

        Expense::withoutGlobalScope(ActiveScope::class)->whereIn('id', explode(',', $request->row_ids))->update(['status' => $request->status]);
    }

    protected function getEmployeeProjects(Request $request)
    {
        // Get employee category
        if (!is_null($request->userId)) {
            $categories = ExpensesCategory::with('roles')->whereHas('roles', function($q) use ($request) {
                $user = User::findOrFail($request->userId);

                $roleId = (count($user->role) > 1) ? $user->role[1]->role_id : $user->role[0]->role_id;
                $q->where('role_id', $roleId);
            })->get();
        }
        else {
            $categories = ExpensesCategory::get();
        }

        if($categories) {
            foreach ($categories as $category) {
                $selected = $category->id == $request->categoryId ? 'selected' : '';
                $categories .= '<option value="' . $category->id . '"'.$selected.'>' . $category->category_name . '</option>';
            }
        }

        // Get employee project
        if (!is_null($request->userId)) {
            $projects = Project::with('members')->whereHas('members', function ($q) use ($request) {
                $q->where('user_id', $request->userId);
            })->get();
        }
        else if(user()->permission('add_expenses') == 'all' && is_null($request->userId))
        {
            $projects = [];
        }
        else {
            $projects = Project::get();
        }

        $data = null;

        if ($projects) {
            foreach ($projects as $project) {
                $data .= '<option data-currency-id="'. $project->currency_id .'" value="' . $project->id . '">' . $project->project_name . '</option>';
            }
        }


        return Reply::dataOnly(['status' => 'success', 'data' => $data, 'category' => $categories]);
    }

    protected function getCategoryEmployee(Request $request)
    {
        $expenseCategory = ExpensesCategoryRole::where('expenses_category_id', $request->categoryId)->get();
        $roleId = [];
        $managers = [];
        $employees = [];

        foreach($expenseCategory as $category) {
            array_push($roleId, $category->role_id);
        }

        if (count($roleId ) == 1 && $roleId != null) {
            $users = User::whereHas(
                'role', function($q)  use ($roleId) {
                    $q->whereIn('role_id', $roleId);
                }
            )->get();

            foreach ($users as $user) {
                ($user->hasRole('Manager')) ? array_push($managers, $user) : array_push($employees, $user);
            }
        }
        else {
            $employees = User::allEmployees(null, true);
        }

        $data = null;

        if ($employees) {
            foreach ($employees as $employee) {

                $data .= '<option ';

                $selected = $employee->id == $request->userId ? 'selected' : '';
                $itsYou = $employee->id == user()->id ? "<span class='ml-2 badge badge-secondary pr-1'>". __('app.itsYou') .'</span>' : '';

                $data .= 'data-content="<div class=\'d-inline-block mr-1\'><img class=\'taskEmployeeImg rounded-circle\' src=\'' . $employee->image_url . '\' ></div> '.ucfirst($employee->name).$itsYou.'"
                value="' . $employee->id . '"'.$selected.'>'.ucfirst($employee->name).'</option>';

            }
        }
        else {
            foreach ($managers as $manager) {
                $data .= '<option ';

                $selected = $manager->id == $request->userId ? 'selected' : '';
                $itsYou = $manager->id == user()->id ? "<span class='ml-2 badge badge-secondary pr-1'>" . __('app.itsYou') . '</span>' : '';
                $data .= 'data-content="<div class=\'d-inline-block mr-1\'><img class=\'taskEmployeeImg rounded-circle\' src=\'' . $manager->image_url . '\' ></div> '.ucfirst($manager->name).'"
                value="' . $manager->id . '"'.$selected.'>'.ucfirst($manager->name).$itsYou.'</option>';
            }
        }

        return Reply::dataOnly(['status' => 'success', 'employees' => $data]);
    }

    public function getExpenseByCategories($id)
    {

        // $estimate_id = request()->estimate_id != '' ? request()->estimate_id : null;

        $expense = ($id == 'null') ? Expense::where('project_id', null)
                                            ->where('estimate_id', null)
                                            ->get(['id','item_name', 'price'])
                                            :
                                             Expense::where('category_id', $id)->where('project_id', null)
                                             ->where('estimate_id', null)
                                            ->get(['id','item_name', 'price']);

        return Reply::dataOnly(['status' => 'success', 'data' => $expense]);
    }

    public function getExpense($id)
    {
        $expense = ($id == 'null') ? Expense::get() : Expense::where('id', $id)->get(['id','item_name', 'price']);

        return Reply::dataOnly(['status' => 'success', 'data' => $expense]);
    }

}
