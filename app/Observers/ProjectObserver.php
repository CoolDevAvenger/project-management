<?php

namespace App\Observers;

use App\Events\NewProjectEvent;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\ProjectMember;
use App\Models\UniversalSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectObserver
{

    public function saving(Project $project)
    {
        if (!isRunningInConsoleOrSeeding() && user()) {
            $project->last_updated_by = user()->id;
        }

        if (request()->has('added_by')) {
            $project->added_by = request('added_by');
        }
    }

    public function creating(Project $project)
    {
        $project->hash = md5(microtime());

        if (!isRunningInConsoleOrSeeding() && user()) {
            $project->added_by = user()->id;
        }

        if (company()) {
            $project->company_id = company()->id;
        }
    }

    public function created(Project $project)
    {
        if (!$project->public && !empty(request()->user_id)) {
            $project->projectMembers()->attach(request()->user_id);
        }

        if (!isRunningInConsoleOrSeeding()) {

            // Send notification to client
            if (!empty(request()->client_id)) {
                event(new NewProjectEvent($project));
            }
        }
    }

    public function updating(Project $project)
    {
        if (request()->public && !empty(request()->member_id)) {
            $project->projectMembers()->detach(request()->member_id);
        }
    }

    public function updated(Project $project)
    {
        if (request()->private && !empty(request()->user_id)) {
            $project->projectMembers()->attach(request()->user_id);
        }

        if (!isRunningInConsoleOrSeeding()) {

            // Send notification to client
            if (!empty(request()->client_id)) {
                event(new NewProjectEvent($project));
            }

            if ($project->isDirty('project_short_code')) {
                // phpcs:ignore
                DB::statement("UPDATE tasks SET task_short_code = CONCAT( '$project->project_short_code', '-', id ) WHERE project_id = '" . $project->id . "'; ");
            }

        }
    }

    public function deleting(Project $project)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $project->id)->where('module_type', 'project')->get();

        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }

        $tasks = $project->tasks()->get();

        $notifyData = ['App\Notifications\TaskCompleted', 'App\Notifications\SubTaskCompleted', 'App\Notifications\SubTaskCreated', 'App\Notifications\TaskComment', 'App\Notifications\TaskCompletedClient', 'App\Notifications\TaskCommentClient', 'App\Notifications\TaskNote', 'App\Notifications\TaskNoteClient', 'App\Notifications\TaskReminder', 'App\Notifications\TaskUpdated', 'App\Notifications\TaskUpdatedClient', 'App\Notifications\NewTask'];

        foreach ($tasks as $task) {
            Notification::whereIn('type', $notifyData)
                ->whereNull('read_at')
                ->where(function ($q) use ($task) {
                    $q->where('data', 'like', '{"id":' . $task->id . ',%');
                    $q->orWhere('data', 'like', '%,"task_id":' . $task->id . ',%');
                })->delete();
        }

        $notifyData = ['App\Notifications\NewProject', 'App\Notifications\NewProjectMember', 'App\Notifications\ProjectReminder', 'App\Notifications\NewRating'];

        if ($notifyData) {
            Notification::whereIn('type', $notifyData)
                ->whereNull('read_at')
                ->where(function ($q) use ($project) {
                    $q->where('data', 'like', '{"id":' . $project->id . ',%');
                    $q->orWhere('data', 'like', '%"project_id":' . $project->id . ',%');
                })->delete();
        }

        $estimate = Estimate::where('project_id', '=', $project->id)->first();
        if(!is_null($estimate)){
            $estimate->project_id = null;
            $estimate->status = 'waiting';
            $estimate->save();
            
        foreach ($estimate->items as $item){
            if(!is_null($item->expense_item_id)){
                $expense = Expense::findOrFail($item->expense_item_id);
                if(!is_null($expense)) {
                    $expense->project_id = null;
                    $expense->actual_price = null;
                    $expense->status = 'pending';
                    $expense->last_updated_by = user()->id;
                    $expense->save();
                }
            }
        }
    }

        // $expenses = $project->expenses()->get();
        // $expenseNotifyData = ['App\Notifications\NewExpenseAdmin', 'App\Notifications\NewExpenseMember', 'App\Notifications\NewExpenseStatus'];
        // foreach ($expenses as $expense) {
        //     if ($expenseNotifyData) {
        //     Notification::
        //     whereIn('type', $expenseNotifyData)
        //         ->whereNull('read_at')
        //         ->where('data', 'like', '{"id":' . $expense->id . ',%')
        //         ->delete();
        //     }
        //     if(!is_null($expense->bank_account_id) && $expense->status == 'approved'){

        //         $account = $expense->bank_account_id;
        //         $price = $expense->price;

        //         $bankAccount = BankAccount::find($account);

        //         if($bankAccount){
        //             $bankBalance = $bankAccount->bank_balance;
        //             $bankBalance += $price;

        //             $transaction = new BankTransaction();
        //             $transaction->expense_id = $expense->id;
        //             $transaction->type = 'Cr';
        //             $transaction->bank_account_id = $account;
        //             $transaction->amount = round($price, 2);
        //             $transaction->transaction_date = $expense->purchase_date;;
        //             $transaction->bank_balance = round($bankBalance, 2);
        //             $transaction->transaction_relation = 'expense';
        //             $transaction->transaction_related_to = $expense->item_name;
        //             $transaction->title = 'expense-deleted';
        //             $transaction->save();

        //             $bankAccount->bank_balance = round($bankBalance, 2);
        //             $bankAccount->save();
        //         }
        //     }
        // }
    }

    public function deleted(Project $project)
    {
        $project->tasks()->delete();
        $project->expenses()->delete();
    }

    public function restored(Project $project)
    {
        $project->tasks()->restore();
    }

}
