<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Leave;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Estimate;
use App\Models\Project;
use Carbon\CarbonInterval;
use App\Models\UserActivity;
use App\Models\ProjectTimeLog;
use App\Models\DashboardWidget;
use App\Models\ProjectActivity;
use App\Models\ProjectCategory;
use App\Models\TaskboardColumn;
use App\Models\ProjectSubCategory;
use Illuminate\Support\Facades\DB;
use App\Models\EstimateStatusSetting;
use App\Models\ProjectStatusSetting;

/**
 *
 */
trait OverviewDashboard
{

    /**
     *
     * @return void
     */
    public function overviewDashboard()
    {
        abort_403($this->viewOverviewDashboard !== 'all');

        $this->startDate  = (request('startDate') != '') ? Carbon::createFromFormat($this->company->date_format, request('startDate')) : now($this->company->timezone)->startOfMonth();
        $this->endDate = (request('endDate') != '') ? Carbon::createFromFormat($this->company->date_format, request('endDate')) : now($this->company->timezone);
        $startDate = $this->startDate->toDateString();
        $endDate = $this->endDate->toDateString();

        $taskBoardColumn = TaskboardColumn::all();

        $this->projectCategories = ProjectCategory::all();
        $this->porjectsubcategories = ProjectSubCategory::all();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $yearQuery='';
        $estimateYearQuery='';
        $year = request('projectYear');
        if ($year != ""){
            $yearQuery .= " AND YEAR(projects.created_at) = '$year'";
            $estimateYearQuery .= " AND YEAR(estimates.created_at) = '$year'";
        }

        $month = request('projectMonth');
        if ($month != ""){
            $yearQuery .= " AND MONTH(projects.created_at) = '$month'";
            $estimateYearQuery = " AND MONTH(estimates.created_at) = '$month'";
        }

        $projectcategoryId = request('projectCategoryId');
        if ($projectcategoryId != ""){
            $yearQuery .= " AND projects.category_id = '$projectcategoryId'";
            $estimateYearQuery .= " AND estimates.project_category_id = '$projectcategoryId'";
        }

        $projectSubCategoryId = request('projectSubCategoryId');
        if ($projectSubCategoryId != ""){
            $yearQuery .= " AND projects.sub_category_id = '$projectSubCategoryId'";
            $estimateYearQuery .= " AND estimates.project_sub_category_id = '$projectSubCategoryId'";
        }

        $this->statusWiseCountEstimate = $this->estimateStatusCountChartData($year, $month, $projectcategoryId, $projectSubCategoryId);
        $this->statusWiseAmountEstimate = $this->estimateStatusAmountChartData($year, $month, $projectcategoryId, $projectSubCategoryId);
        $this->statusWiseCountProject = $this->projectStatusCountChartData($year, $month, $projectcategoryId, $projectSubCategoryId);
        $this->statusWiseAmountProject = $this->projectStatusAmountChartData($year, $month, $projectcategoryId, $projectSubCategoryId);

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "client" AND users.company_id = '. company()->id .') as totalClients'),
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" and users.status = "active" AND users.company_id = '. company()->id .') as totalEmployees'),

                DB::raw('(select count(projects.id) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalProjects'),

                DB::raw('(select sum(projects.project_budget) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalProjectAmount'),

                DB::raw('(select count(estimates.id) from `estimates` WHERE estimates.company_id = '. company()->id . $estimateYearQuery .') as totalestimates'),
                DB::raw('(select sum(estimates.total) from `estimates` WHERE estimates.company_id = '. company()->id . $estimateYearQuery .') as totalestimateAmount'),
                DB::raw('(select sum(projects.total_estimate_cost) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalProjectCost'),
                DB::raw('(select sum(projects.total_estimate_margin) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalProjectMargin'),
                DB::raw('(select sum(projects.total_estimate_hour) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalprojectHour'),

                DB::raw('(select sum(projects.total_estimate_service) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalEstimateService'),
                DB::raw('(select sum(projects.total_estimate_expense) from `projects` WHERE projects.company_id = '. company()->id . $yearQuery .') as totalEstimateExpense'),

                DB::raw('(select sum(tasks.estimate_cost) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectServiceCost'),
                DB::raw('(select sum(tasks.estimate_expense_cost) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectExpenseCost'),

                DB::raw('(select sum(tasks.estimate_margin) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectServiceMargin'),
                DB::raw('(select sum(tasks.estimate_expense_margin) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectExpenseMargin'),

                // Completed project's total amount
                DB::raw('(select SUM(tasks.estimate_amount) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.unit_name != "Hrs" AND projects.status="finished" AND tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectServiceAmountCompletedNotHrs'),
                DB::raw('(SELECT SUM((estimate_price * actual_hours) + (((estimate_price * actual_hours)*estimate_margin_percent)/100))
                AS extra_amount FROM (SELECT tsk.estimate_price AS estimate_price,
                tsk.estimate_margin_percent AS estimate_margin_percent,
                (SELECT SUM(ptl.total_hours) from `project_time_logs` ptl WHERE ptl.task_id=tsk.id)
                AS actual_hours, tsk.unit_name AS u_name FROM
                `tasks` tsk inner join projects on projects.id=tsk.project_id WHERE tsk.unit_name="Hrs" AND projects.status="finished" AND tsk.company_id = '. company()->id . $yearQuery .') X) as totalEstimateProjectServiceAmountCompletedHrs'),
                DB::raw('(select sum(tasks.estimate_expense_amount) from `tasks` inner join projects on projects.id=tasks.project_id WHERE projects.status="finished" AND tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectExpenseAmountCompleted'),

                // Completed project's actual total amount
                DB::raw('(select SUM(tpl.earnings) from `project_time_logs` tpl inner join tasks on tasks.id=tpl.task_id inner join projects on projects.id=tasks.project_id  WHERE projects.status="finished" AND tasks.company_id = '. company()->id . $yearQuery .') as totalProjectSrviceActualAmount'),
                DB::raw('(select sum(expenses.actual_price) from `expenses` inner join projects on projects.id=expenses.project_id WHERE projects.status="finished" AND expenses.company_id = '. company()->id . $yearQuery .') as totalPeojectActualExpenseAmount'),

                // Completed project's estimate and actual total hours
                DB::raw('(select IFNULL(SUM(tasks.estimate_hrs), 0) from `tasks` inner join projects on projects.id=tasks.project_id WHERE projects.status="finished" AND tasks.company_id = '. company()->id . $yearQuery .') as totalEstimatedHrs'),
                DB::raw('(select IFNULL(SUM(ptl.total_hours), 0) from `project_time_logs` as ptl inner join tasks on tasks.id=ptl.task_id inner join projects on projects.id=tasks.project_id WHERE projects.status="finished" AND projects.company_id = '. company()->id . $yearQuery .') as totalActualHrs'),


                // conpleted project divided betewwn price/hrs, hrs and expenses
                DB::raw('(select SUM(tpl.earnings) from `project_time_logs` tpl inner join tasks on tasks.id=tpl.task_id inner join projects on projects.id=tasks.project_id  WHERE projects.status="finished" AND tasks.unit_name != "Hrs" AND tasks.company_id = '. company()->id . $yearQuery .') as totalProjectSrviceActualAmountNotHrs'),
                DB::raw('(select SUM(tpl.earnings) from `project_time_logs` tpl inner join tasks on tasks.id=tpl.task_id inner join projects on projects.id=tasks.project_id  WHERE projects.status="finished" AND tasks.unit_name = "Hrs" AND tasks.company_id = '. company()->id . $yearQuery .') as totalProjectSrviceActualAmountHrs'),
                DB::raw('(select sum(expenses.actual_price) from `expenses` inner join projects on projects.id=expenses.project_id WHERE projects.status="finished" AND expenses.company_id = '. company()->id . $yearQuery .') as totalPeojectActualExpenseAmount'),


                // for in progress projects
                // in-progress project's total amount
                DB::raw('(select SUM((tasks.estimate_amount * projects.completion_percent)/100) from `tasks` inner join projects on projects.id=tasks.project_id WHERE tasks.unit_name != "Hrs" AND projects.status="in progress" AND tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectServiceAmountInProgressNotHrs'),
                DB::raw('(SELECT SUM((estimate_price * actual_hours) + (((estimate_price * actual_hours)*estimate_margin_percent)/100))
                AS extra_amount FROM (SELECT tsk.estimate_price AS estimate_price,
                tsk.estimate_margin_percent AS estimate_margin_percent,
                (SELECT SUM(ptl.total_hours) from `project_time_logs` ptl WHERE ptl.task_id=tsk.id)
                AS actual_hours, tsk.unit_name AS u_name FROM
                `tasks` tsk inner join projects on projects.id=tsk.project_id WHERE tsk.unit_name="Hrs" AND projects.status="in progress" AND tsk.company_id = '. company()->id . $yearQuery .') X) as totalEstimateProjectServiceAmountInProgressHrs'),
                DB::raw('(select sum(expenses.actual_price) from `expenses` inner join projects on projects.id=expenses.project_id WHERE projects.status="in progress" AND expenses.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectExpenseAmountInProgress'),
                // DB::raw('(select sum(tasks.estimate_expense_amount) from `tasks` inner join projects on projects.id=tasks.project_id WHERE projects.status="in progress" AND tasks.company_id = '. company()->id . $yearQuery .') as totalEstimateProjectExpenseAmountInProgress'),

                // in-progress  project's actual total amount
                DB::raw('(select SUM(tpl.earnings) from `project_time_logs` tpl inner join tasks on tasks.id=tpl.task_id inner join projects on projects.id=tasks.project_id  WHERE projects.status="in progress" AND tasks.unit_name != "Hrs" AND tasks.company_id = '. company()->id . $yearQuery .') as totalInProgressProjectSrviceActualAmountNotHrs'),
                DB::raw('(select SUM(tpl.earnings) from `project_time_logs` tpl inner join tasks on tasks.id=tpl.task_id inner join projects on projects.id=tasks.project_id  WHERE projects.status="in progress" AND tasks.unit_name = "Hrs" AND tasks.company_id = '. company()->id . $yearQuery .') as totalInProgressProjectSrviceActualAmountHrs'),
                DB::raw('(select sum(expenses.actual_price) from `expenses` inner join projects on projects.id=expenses.project_id WHERE projects.status="in progress" AND expenses.company_id = '. company()->id . $yearQuery .') as totalInProgressProjectActualExpenseAmount'),

                // In Progress project's estimate and actual total hours
                DB::raw('(select IFNULL(SUM(tasks.estimate_hrs), 0) from `tasks` inner join projects on projects.id=tasks.project_id WHERE projects.status="in progress" AND tasks.company_id = '. company()->id . $yearQuery .') as totalInProgressEstimatedHrs'),
                DB::raw('(select IFNULL(SUM(ptl.total_hours), 0) from `project_time_logs` as ptl inner join tasks on tasks.id=ptl.task_id inner join projects on projects.id=tasks.project_id WHERE projects.status="in progress" AND projects.company_id = '. company()->id . $yearQuery .') as totalInProgressActualHrs'),


                DB::raw('(select count(invoices.id) from `invoices` where (status = "unpaid" or status = "partial") AND invoices.company_id = '. company()->id .') as totalUnpaidInvoices'),
                DB::raw('(select sum(project_time_logs.total_minutes) from `project_time_logs` where approved = "1" AND project_time_logs.company_id = '. company()->id .') as totalHoursLogged'),
                DB::raw('(select sum(project_time_log_breaks.total_minutes) from `project_time_log_breaks` WHERE project_time_log_breaks.company_id = '. company()->id .') as totalBreakMinutes'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $completedTaskColumn->id . ' and is_private = "0" AND tasks.company_id = '. company()->id .') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id != ' . $completedTaskColumn->id . ' and is_private = "0" AND tasks.company_id = '. company()->id .') as totalPendingTasks'),
                DB::raw('(select count(distinct(attendances.user_id)) from `attendances` inner join users as atd_user on atd_user.id=attendances.user_id inner join role_user on role_user.user_id=atd_user.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" and attendances.clock_in_time >= "'.today(company()->timezone)->setTimezone('UTC')->toDateTimeString().'" and atd_user.status = "active" AND attendances.company_id = '. company()->id .') as totalTodayAttendance'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="open") and deleted_at IS NULL AND tickets.company_id = '. company()->id .') as totalOpenTickets'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="resolved" or status="closed") and deleted_at IS NULL AND tickets.company_id = '. company()->id .') as totalResolvedTickets')
            )
            ->first();

        $minutes = $this->counts->totalHoursLogged - $this->counts->totalBreakMinutes;
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $timeLog = $hours . ' ' . __('app.hrs');

        if ($remainingMinutes > 0) {
            $timeLog .= ' ' . $remainingMinutes . ' ' . __('app.mins');
        }

        $this->counts->totalHoursLogged = $timeLog;
        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-dashboard')->get();

        $this->activeWidgets = $this->widgets->filter(function ($value, $key) {
            return $value->status == '1';
        })->pluck('widget_name')->toArray();

        $this->earningChartData = $this->earningChart($startDate, $endDate);
        $this->timlogChartData = $this->timelogChart($startDate, $endDate);
        $this->rnPrEsAmForecastDate = $this->rnPrEsAmForecasChart($this->counts->totalEstimateProjectServiceAmountInProgressNotHrs, $this->counts->totalEstimateProjectServiceAmountInProgressHrs, $this->counts->totalEstimateProjectExpenseAmountInProgress);

        $this->leaves = Leave::with('user', 'type')
            ->where('status', 'pending')
            ->whereBetween('leave_date', [$startDate, $endDate])
            ->get();

        $this->newTickets = Ticket::with('requester')->where('status', 'open')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->orderBy('updated_at', 'desc')->get();

        $this->pendingTasks = Task::with('project', 'users', 'boardColumn')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where('tasks.is_private', 0)
            ->orderBy('due_date', 'desc')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->limit(15)
            ->get();


        $currentDate = now()->timezone($this->company->timezone)->toDateTimeString();

        $this->pendingLeadFollowUps = Lead::with('followup', 'leadAgent', 'leadAgent.user', 'leadAgent.user.employeeDetail', 'leadAgent.user.employeeDetail.designation')
            ->selectRaw('leads.id,leads.company_name, leads.client_name, leads.agent_id, ( select lead_follow_up.next_follow_up_date from lead_follow_up where lead_follow_up.lead_id = leads.id and DATE(lead_follow_up.next_follow_up_date) < "'.$currentDate.'" ORDER BY lead_follow_up.created_at DESC Limit 1) as follow_up_date_past,
            ( select lead_follow.next_follow_up_date from lead_follow_up as lead_follow where lead_follow.lead_id = leads.id and DATE(lead_follow.next_follow_up_date) > "'.$currentDate.'" ORDER BY lead_follow.created_at DESC Limit 1) as follow_up_date_next'
        )
            ->where('leads.next_follow_up', 'yes')
            ->groupBy('leads.id')
            ->get();

        $this->pendingLeadFollowUps = $this->pendingLeadFollowUps->filter(function ($value, $key) {
            return $value->follow_up_date_past != null && $value->follow_up_date_next == null && $value->followup->status != 'completed';
        });

        $this->projectActivities = ProjectActivity::with('project')
            ->join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->where('projects.company_id', company()->id)
            ->whereNull('projects.deleted_at')
            ->select('project_activity.*')
            ->limit(15)
            ->whereBetween('project_activity.created_at', [$startDate, $endDate])
            ->orderBy('project_activity.id', 'desc')
            ->groupBy('project_activity.id')
            ->get();

        $this->userActivities = UserActivity::with('user')->limit(15)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')->get();

        $this->view = 'dashboard.ajax.overview';
    }

    public function estimateStatusCountChartData($year, $month, $projectcategoryId, $projectSubCategoryId)
    {
        $labels = EstimateStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['labels'] = EstimateStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['colors'] = EstimateStatusSetting::all()->where('status', 'active')->pluck('color');
        $data['values'] = [];

        foreach ($labels as $label) {
            $estimateQuery = Estimate::where('status', $label);

            // when($year, function ($q) use ($year) {
            //     return $q->where(DB::raw('YEAR(`created_at`)'), $year);
            // })
            // ->when($month, function ($q) use ($month) {
            //     return $q->where(DB::raw('MONTH(`created_at`)'), $month);
            // })
            // ->when($projectcategoryId, function ($q) use ($projectcategoryId) {
            //     return $q->where(DB::raw('project_category_id'), $projectcategoryId);
            // })
            // ->when($projectSubCategoryId, function ($q) use ($projectSubCategoryId) {
            //     return $q->where(DB::raw('project_sub_category_id'), $projectSubCategoryId);
            // })

            if($year != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('YEAR(`created_at`)'), $year);
            }
            if($month != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('MONTH(`created_at`)'), $month);
            }
            if($projectcategoryId != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('project_category_id'), $projectcategoryId);
            }
            if($projectSubCategoryId != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('project_sub_category_id'), $projectSubCategoryId);
            }

            $data['values'][] = $estimateQuery->count();
        }

        return $data;
    }

    public function estimateStatusAmountChartData($year, $month, $projectcategoryId, $projectSubCategoryId)
    {
        $labels = EstimateStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['labels'] = EstimateStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['colors'] = EstimateStatusSetting::all()->where('status', 'active')->pluck('color');
        $data['values'] = [];

        foreach ($labels as $label) {
            $estimateQuery = Estimate::where('status', $label);

            // when($year, function ($q) use ($year) {
            //     return $q->where(DB::raw('YEAR(`created_at`)'), $year);
            // })
            // ->when($month, function ($q) use ($month) {
            //     return $q->where(DB::raw('MONTH(`created_at`)'), $month);
            // })
            // ->when($projectcategoryId, function ($q) use ($projectcategoryId) {
            //     return $q->where(DB::raw('project_category_id'), $projectcategoryId);
            // })
            // ->when($projectSubCategoryId, function ($q) use ($projectSubCategoryId) {
            //     return $q->where(DB::raw('project_sub_category_id'), $projectSubCategoryId);
            // })

            if($year != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('YEAR(`created_at`)'), $year);
            }
            if($month != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('MONTH(`created_at`)'), $month);
            }
            if($projectcategoryId != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('project_category_id'), $projectcategoryId);
            }
            if($projectSubCategoryId != '') {
                $estimateQuery = $estimateQuery->where(DB::raw('project_sub_category_id'), $projectSubCategoryId);
            }

            $data['values'][] = $estimateQuery->sum('total');
        }

        return $data;
    }

    public function projectStatusCountChartData($year, $month, $projectcategoryId, $projectSubCategoryId)
    {
        $labels = ProjectStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['labels'] = ProjectStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['colors'] = ProjectStatusSetting::all()->where('status', 'active')->pluck('color');
        $data['values'] = [];

        foreach ($labels as $label) {
            $projectQuery = Project::where('status', $label);

            // when($year, function ($q) use ($year) {
            //     return $q->where(DB::raw('YEAR(`created_at`)'), $year);
            // })
            // ->when($month, function ($q) use ($month) {
            //     return $q->where(DB::raw('MONTH(`created_at`)'), $month);
            // })
            // ->when($projectcategoryId, function ($q) use ($projectcategoryId) {
            //     return $q->where(DB::raw('category_id'), $projectcategoryId);
            // })
            // ->when($projectSubCategoryId, function ($q) use ($projectSubCategoryId) {
            //     return $q->where(DB::raw('sub_category_id'), $projectSubCategoryId);
            // })

            if($year != '') {
                $projectQuery = $projectQuery->where(DB::raw('YEAR(`created_at`)'), $year);
            }
            if($month != '') {
                $projectQuery = $projectQuery->where(DB::raw('MONTH(`created_at`)'), $month);
            }
            if($projectcategoryId != '') {
                $projectQuery = $projectQuery->where(DB::raw('category_id'), $projectcategoryId);
            }
            if($projectSubCategoryId != '') {
                $projectQuery = $projectQuery->where(DB::raw('sub_category_id'), $projectSubCategoryId);
            }

            $data['values'][]= $projectQuery->count();
        }

        return $data;
    }

    public function projectStatusAmountChartData($year, $month, $projectcategoryId, $projectSubCategoryId)
    {
        $labels = ProjectStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['labels'] = ProjectStatusSetting::all()->where('status', 'active')->pluck('status_name');
        $data['colors'] = ProjectStatusSetting::all()->where('status', 'active')->pluck('color');
        $data['values'] = [];

        foreach ($labels as $label) {
            $projectQuery = Project::where('status', $label);

            // when($year, function ($q) use ($year) {
            //     return $q->where(DB::raw('YEAR(`created_at`)'), $year);
            // })
            // ->when($month, function ($q) use ($month) {
            //     return $q->where(DB::raw('MONTH(`created_at`)'), $month);
            // })
            // ->when($projectcategoryId, function ($q) use ($projectcategoryId) {
            //     return $q->where(DB::raw('category_id'), $projectcategoryId);
            // })
            // ->when($projectSubCategoryId, function ($q) use ($projectSubCategoryId) {
            //     return $q->where(DB::raw('sub_category_id'), $projectSubCategoryId);
            // })

            if($year != '') {
                $projectQuery = $projectQuery->where(DB::raw('YEAR(`created_at`)'), $year);
            }
            if($month != '') {
                $projectQuery = $projectQuery->where(DB::raw('MONTH(`created_at`)'), $month);
            }
            if($projectcategoryId != '') {
                $projectQuery = $projectQuery->where(DB::raw('category_id'), $projectcategoryId);
            }
            if($projectSubCategoryId != '') {
                $projectQuery = $projectQuery->where(DB::raw('sub_category_id'), $projectSubCategoryId);
            }

            $data['values'][] = $projectQuery->sum('project_budget');
        }

        return $data;
    }


    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function earningChart($startDate, $endDate)
    {
        $payments = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')->where('payments.status', 'complete');

        $payments = $payments->whereBetween('payments.paid_on', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);

        $payments = $payments->orderBy('paid_on', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(paid_on,"%d-%M-%y") as date'),
                DB::raw('YEAR(paid_on) year, MONTH(paid_on) month'),
                DB::raw('amount as total'),
                'currencies.id as currency_id',
                'currencies.exchange_rate'
            ]);

        $incomes = [];

        foreach ($payments as $invoice) {
            if (!isset($incomes[$invoice->date])) {
                $incomes[$invoice->date] = 0;
            }

            if ($invoice->currency_id != $this->company->currency_id && $invoice->exchange_rate != 0) {
                $incomes[$invoice->date] += floor($invoice->total / $invoice->exchange_rate);

            } else {
                $incomes[$invoice->date] += round($invoice->total, 2);
            }
        }

        $dates = array_keys($incomes);
        $graphData = [];

        foreach ($dates as $date) {
            $graphData[] = [
                'date' => $date,
                'total' => isset($incomes[$date]) ? round($incomes[$date], 2) : 0,
            ];
        }

        usort($graphData, function ($a, $b) {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });

        // return $graphData;
        $graphData = collect($graphData);

        $data['labels'] = $graphData->pluck('date');
        $data['values'] = $graphData->pluck('total')->toArray();
        $data['colors'] = [$this->appTheme->header_color];
        $data['name'] = __('app.earnings');

        return $data;
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function timelogChart($startDate, $endDate)
    {
        $timelogs = ProjectTimeLog::whereBetween('start_time', [$startDate, $endDate]);
        $timelogs = $timelogs->where('project_time_logs.approved', 1);
        $timelogs = $timelogs->groupBy('date')
            ->orderBy('start_time', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(start_time,\'%d-%M-%y\') as date'),
                DB::raw('FLOOR(sum(total_minutes/60)) as total_hours')
            ]);
        $data['labels'] = $timelogs->pluck('date');
        $data['values'] = $timelogs->pluck('total_hours')->toArray();
        $data['colors'] = [$this->appTheme->header_color];
        $data['name'] = __('modules.dashboard.totalHoursLogged');
        return $data;
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function rnPrEsAmForecasChart($startDate, $endDate, $test)
    {

    }

}
