<script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
<script src="{{ asset('vendor/jquery/gauge.js') }}"></script>

@php
$editProjectPermission = user()->permission('edit_projects');
$addPaymentPermission = user()->permission('add_payments');
$projectBudgetPermission = user()->permission('view_project_budget');
$memberIds = $project->members->pluck('user_id')->toArray();
@endphp

@push('styles')
<style>
    #estimate_amount, #estimate_amount2{
        accent-color: #C12525;
    }
    #actual_cost, #actual_cost2{
        accent-color: #743ee2;
    }
    #margin_simulation, #margin_simulation2{
        accent-color: #8f8e28;
    }

</style>
@endpush

<div class="d-lg-flex">
    <div class="w-100 py-0 py-lg-3 py-md-0 ">
        <div class="d-flex align-content-center flex-lg-row-reverse mb-4">
            @if (!$project->trashed())
                <div class="ml-lg-3 ml-md-0 ml-0 mr-3 mr-lg-0 mr-md-3">
                    @if ($editProjectPermission == 'all' || ($editProjectPermission == 'added' && $project->added_by == user()->id) || ($project->project_admin == user()->id))
                        <select class="form-control select-picker change-status height-35">
                            @foreach ($projectStatus as $status)
                                <option
                                data-content="<i class='fa fa-circle mr-1 f-15' style='color:{{$status->color}}'></i>{{ ucfirst($status->status_name) }}"
                                @if ($project->status == $status->status_name)
                                selected @endif
                                value="{{$status->status_name}}"> {{ $status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        @foreach ($projectStatus as $status)
                            @if ($project->status == $status->status_name)
                                <div class="bg-white p-2 border rounded">
                                    <i class='fa fa-circle mr-2' style="color:{{$status->color}}"></i>{{ $status->status_name }}
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="ml-lg-3 ml-md-0 ml-0 mr-3 mr-lg-0 mr-md-3">
                    <div class="dropdown">
                        <button
                            class="btn btn-lg bg-white border height-35 f-15 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('app.action') <i class="icon-options-vertical icons"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                            aria-labelledby="dropdownMenuLink" tabindex="0">

                            @if ($editProjectPermission == 'all'
                                || ($project->project_admin == user()->id)
                                || ($editProjectPermission == 'added' && user()->id == $project->added_by)
                                || ($editProjectPermission == 'owned' && user()->id == $project->client_id && in_array('client', user_roles()))
                                || ($editProjectPermission == 'owned' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
                                || ($editProjectPermission == 'both' && (user()->id == $project->client_id || user()->id == $project->added_by))
                                || ($editProjectPermission == 'both' && in_array(user()->id, $memberIds) && in_array('employee', user_roles())))
                                <a class="dropdown-item openRightModal"
                                    href="{{ route('projects.edit', $project->id) }}">@lang('app.edit')
                                    @lang('app.project')
                                </a>

                                <a class="dropdown-item"
                                    href="{{ route('front.gantt', $project->hash) }}" target="_blank">
                                    @lang('modules.projects.viewPublicGanttChart')
                                </a>

                                <a class="dropdown-item"
                                    href="{{ route('front.taskboard', $project->hash) }}" target="_blank">
                                    @lang('app.public') @lang('modules.tasks.taskBoard')
                                </a>
                                <hr class="my-1">
                            @endif

                            @php $projectPin = $project->pinned() @endphp

                            @if ($projectPin)
                                <a class="dropdown-item" href="javascript:;" id="pinnedItem"
                                    data-pinned="pinned">@lang('app.unpin')
                                    @lang('app.project')</a>
                            @else
                                <a class="dropdown-item" href="javascript:;" id="pinnedItem"
                                    data-pinned="unpinned">@lang('app.pin')
                                    @lang('app.project')</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($projectPin)
                    <div class="align-self-center">
                        <span class='badge badge-success'><i class='fa fa-thumbtack'></i> @lang('app.pinned')</span>
                    </div>
                @endif
            @elseif($editProjectPermission == 'all'
            || ($project->project_admin == user()->id)
            || ($editProjectPermission == 'added' && user()->id == $project->added_by)
            || ($editProjectPermission == 'owned' && user()->id == $project->client_id && in_array('client', user_roles()))
            || ($editProjectPermission == 'owned' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
            || ($editProjectPermission == 'both' && (user()->id == $project->client_id || user()->id == $project->added_by))
            || ($editProjectPermission == 'both' && in_array(user()->id, $memberIds) && in_array('employee', user_roles())))
                <div class="ml-3">
                    <x-forms.button-primary class="restore-project" icon="undo">@lang('app.unarchive')
                    </x-forms.button-primary>
                </div>
            @endif
        </div>
        <!-- PROJECT PROGRESS AND CLIENT START -->
        <div class="row">
            <!-- PROJECT PROGRESS START -->
            <div class="col-md-6 mb-4">
                <x-cards.data :title="__('modules.projects.projectProgress')"
                    otherClasses="d-flex d-xl-flex d-lg-block d-md-flex  justify-content-between align-items-center">

                    <x-gauge-chart id="progressGauge" width="100" :value="$project->completion_percent" />

                    <!-- PROGRESS START DATE START -->
                    <div class="p-start-date mb-xl-0 mb-lg-3">
                        <h5 class="text-lightest f-14 font-weight-normal">@lang('app.startDate')</h5>
                        <p class="f-15 mb-0">{{ $project->start_date->translatedFormat(company()->date_format) }}</p>
                    </div>
                    <!-- PROGRESS START DATE END -->
                    <!-- PROGRESS END DATE START -->
                    <div class="p-end-date">
                        <h5 class="text-lightest f-14 font-weight-normal">@lang('modules.projects.deadline')</h5>
                        <p class="f-15 mb-0">
                            {{ !is_null($project->deadline) ? $project->deadline->translatedFormat(company()->date_format) : '--' }}
                        </p>
                    </div>
                    <!-- PROGRESS END DATE END -->

                </x-cards.data>
            </div>
            <!-- PROJECT PROGRESS END -->
            <!-- CLIENT START -->
            <div class="col-md-6 mb-4">
                @if (!is_null($project->client))
                    <x-cards.data :title="__('app.client')"
                        otherClasses="d-block d-xl-flex d-lg-block d-md-flex  justify-content-between align-items-center">

                        <div class="p-client-detail">
                            <div class="card border-0 ">
                                <div class="card-horizontal">

                                    <div class="card-img m-0">
                                        <img class="" src=" {{ $project->client->image_url }}"
                                            alt="{{ $project->client->name }}">
                                    </div>
                                    <div class="card-body border-0 p-0 ml-4 ml-xl-4 ml-lg-3 ml-md-3">
                                        <h4 class="card-title f-15 font-weight-normal mb-0 text-capitalize">
                                            @if (!in_array('client', user_roles()))
                                               <a href="{{ route('clients.show', $project->client_id) }}" class="text-dark">
                                                    {{ $project->client->name }}
                                                </a>
                                            @else
                                                {{ $project->client->name }}
                                            @endif
                                        </h4>
                                        <p class="card-text f-14 text-lightest mb-0">
                                            {{ $project->client->clientDetails->company_name }}
                                        </p>
                                        @if ($project->client->country_id)
                                            <span
                                                class="card-text f-12 text-lightest text-capitalize d-flex align-items-center">
                                                <span
                                                    class='flag-icon flag-icon-{{ strtolower($project->client->country->iso) }} mr-2'></span>
                                                {{ $project->client->country->nicename }}
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        @if( (in_array('admin', user_roles()) && $messageSetting->allow_client_admin == 'yes') ||
                        (in_array('employee', user_roles()) && $messageSetting->allow_client_employee == 'yes') )
                        <div class="p-client-msg mt-4 mt-xl-0 mt-lg-4 mt-md-0">
                            <button type="button" class="btn-secondary rounded f-15" id="new-chat"
                                data-client-id="{{ $project->client->id }}"> <i class="fab fa-whatsapp mr-1"></i>
                                @lang('app.message')</button>
                        </div>
                @endif


                </x-cards.data>
            @else
                <x-cards.data
                    otherClasses="d-flex d-xl-flex d-lg-block d-md-flex  justify-content-between align-items-center">
                    <x-cards.no-record icon="user" :message="__('messages.noClientAddedToProject')" />
                </x-cards.data>
                @endif

            </div>
            <!-- CLIENT END -->
        </div>
        <!-- PROJECT PROGRESS AND CLIENT END -->

        <!-- TASK STATUS AND BUDGET START -->
        <div class="row mb-4">
            <!-- TASK STATUS START -->
            <div class="col-lg-6 col-md-12">
                <x-cards.data :title="__('app.menu.tasks')" padding="false">
                    <x-pie-chart id="task-chart" :labels="$taskChart['labels']" :values="$taskChart['values']"
                        :colors="$taskChart['colors']" height="220" width="250" />
                </x-cards.data>
            </div>
            <!-- TASK STATUS END -->
            <!-- BUDGET VS SPENT START -->
            <div class="col-lg-6 col-md-12">
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <h4 class="f-18 f-w-500 mb-4">@lang('app.statistics')</h4>
                    </div>
                    @if ($projectBudgetPermission == 'all')
                        <div class="col">
                            <x-cards.widget :title="__('modules.projects.projectBudget')"
                                :value="((!is_null($project->project_budget) && $project->currency) ? currency_format($project->project_budget, $project->currency->id) : '0')"
                                icon="coins" />
                        </div>
                    @endif

                    @if ($viewPaymentPermission == 'all')
                        <div class="col">
                            <x-cards.widget :title="__('app.earnings')"
                                :value="(!is_null($project->currency) ? currency_format($earnings, $project->currency->id) : currency_format($earnings))"
                                icon="coins" />
                        </div>
                    @endif
                </div>
                <div class="row">
                    @if ($viewProjectTimelogPermission == 'all')
                        <div class="col">
                            <x-cards.widget :title="__('modules.projects.hoursLogged')" :value="$hoursLogged"
                                icon="clock" />
                        </div>
                    @endif

                    @if ($viewExpensePermission == 'all')
                        <div class="col">
                            <x-cards.widget :title="__('modules.projects.expenses_total')"
                                :value="(!is_null($project->currency) ? currency_format($expenses, $project->currency->id) : currency_format($expenses))"
                                icon="coins" />
                        </div>
                    @endif
                </div>
            </div>
            <!-- BUDGET VS SPENT END -->
        </div>
        <!-- TASK STATUS AND BUDGET END -->

    @if ($project->status == 'in progress' && $project->completion_percent != 0)
    <!-- Forecast charts first -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-body">
                            <h4 class="f-18 f-w-500 mb-0">@lang('app.projectForecast')</h4>
                            <div id="forecast-chart"></div>

                            <div class="row d-flex justify-content-center">
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="estimate_amount" id="estimate_amount" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="estimate_amount">
                                            @lang('app.estimateAmount')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="actual_cost" id="actual_cost" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="actual_cost">
                                            @lang('app.actualCost')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="margin_simulation" id="margin_simulation" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="margin_simulation">
                                            @lang('app.marginSimulation')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <input type="hidden" id="projectId" value="{{$project->id}}" name="projectId">
                </div>
            </div>
        </div>
    </div>

    <!-- Forecast charts2 -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-body">
                            <h4 class="f-18 f-w-500 mb-0">@lang('app.projectForecastSecond')</h4>
                            <div id="forecast-chart2"></div>

                            <div class="row d-flex justify-content-center">
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="estimate_amount2" id="estimate_amount2" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="estimate_amount2">
                                            @lang('app.estimateAmount')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="actual_cost2" id="actual_cost2" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="actual_cost2">
                                            @lang('app.actualCost')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-mb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked name="margin_simulation2" id="margin_simulation2" autocomplete="off">
                                        <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 pt-1 text-wrap" for="margin_simulation2">
                                            @lang('app.marginSimulation')
                                        </label>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </div>
    @endif

        <!-- TASK STATUS AND BUDGET START -->
        <div class="row mb-4">
            <!-- BUDGET VS SPENT START -->
            <div class="col-md-12">
                <x-cards.data>
                    <div class="row {{ $projectBudgetPermission == 'all' ? 'row-cols-lg-2' : '' }}">
                        @if ($viewProjectTimelogPermission == 'all')
                            <div class="col">
                                <h4 class="f-18 f-w-500 mb-0">@lang('modules.projects.hoursLogged')</h4>
                                <x-stacked-chart id="task-chart2" :chartData="$hoursBudgetChart" height="250" />
                            </div>
                        @endif
                        @if ($projectBudgetPermission == 'all')
                            <div class="col">
                                <h4 class="f-18 f-w-500 mb-0">@lang('modules.projects.projectBudget')</h4>
                                <x-stacked-chart id="task-chart3" :chartData="$amountBudgetChart" height="250" />
                            </div>
                        @endif
                    </div>
                </x-cards.data>
            </div>
            <!-- BUDGET VS SPENT END -->
        </div>
        <!-- TASK STATUS AND BUDGET END -->

        <!-- PROJECT DETAILS START -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <x-cards.data :title="__('app.project') . ' ' . __('app.details')"
                    otherClasses="d-flex justify-content-between align-items-center">
                    @if (is_null($project->project_summary))
                        <x-cards.no-record icon="align-left" :message="__('messages.projectDetailsNotAdded')" />
                    @else
                        <div class="text-dark-grey mb-0 ql-editor p-0">{!! $project->project_summary !!}</div>
                    @endif
                </x-cards.data>
            </div>
        </div>
        <!-- PROJECT DETAILS END -->

        {{-- Custom fields data --}}
        @if (isset($fields) && count($fields) > 0)
            <div class="row mt-4">
                <!-- TASK STATUS START -->
                <div class="col-md-12">
                    <x-cards.data :title="__('modules.client.clientOtherDetails')">
                        <x-forms.custom-field-show :fields="$fields" :model="$project"></x-forms.custom-field-show>
                    </x-cards.data>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.change-status').change(function() {
            var status = $(this).val();
            var url = "{{ route('projects.update_status', $project->id) }}";
            var token = '{{ csrf_token() }}'

            $.easyAjax({
                url: url,
                type: "POST",
                container: '.content-wrapper',
                blockUI: true,
                data: {
                    status: status,
                    _token: token
                }
            });
        });

        $('body').on('click', '#pinnedItem', function() {
            var type = $('#pinnedItem').attr('data-pinned');
            var id = '{{ $project->id }}';
            var pinType = 'project';

            var dataPin = type.trim(type);
            if (dataPin == 'pinned') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmUnpin')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('projects.destroy_pin', ':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token,
                                'type': pinType
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    window.location.reload();
                                }
                            }
                        })
                    }
                });

            } else {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmPin')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('projects.store_pin') }}?type=" + pinType;

                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token,
                                'project_id': id
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            }
        });

        $('body').on('click', '.restore-project', function() {
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.unArchiveMessage')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmRevert')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('projects.archive_restore', $project->id) }}";

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '#new-chat', function() {
            let clientId = $(this).data('client-id');
            const url = "{{ route('messages.create') }}?clientId=" + clientId;
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

    });
</script>

{{-- forecast chart --}}
@if ($project->status == 'in progress')


<script>

    const runForecastChart = () => {
        var projectId = $('#projectId').val();
        var amountcheck = $('#estimate_amount').is(':checked') ? 1 : 0;
        var costCheck = $('#actual_cost').is(':checked') ? 1 : 0;
        var marginCheck = $('#margin_simulation').is(':checked') ? 1 : 0;

        var url = "{{ route('projects.forecastChart') }}";

        $.ajax({
            url: url,
            type: "GET",
            blockUI: true,
            data: {
                id: projectId,
                amountcheck: amountcheck,
                costCheck: costCheck,
                marginCheck: marginCheck
            },
            success: function (response) {
                if (response.status === "success") {
                    var forecastChartLebels=response.data.chartLebels;
                    var forecastChartDataSet=response.data.chartDataset;
                    var forecastChartColors=response.data.chartColors;

                    var forecastData = {
                        labels: forecastChartLebels,
                        datasets: forecastChartDataSet
                    }

                    var foreCastChart =new frappe.Chart("#forecast-chart", {
                        data: forecastData,
                        type: 'line',
                        height: 250,
                        lineOptions: {
                                dotSize: 4,
                                hideLine: 0,
                                hideDots: 0,
                                heatline: 1,
                                regionFill: 1,
                            },
                            valuesOverPoints: 1,
                            // axisOptions: {
                            //     yAxisMode: 'tick',
                            //     xAxisMode: 'tick',
                            //     xIsSeries: 0
                            // },
                        colors: forecastChartColors
                    });
                }
            }
        });
    }
    runForecastChart();
</script>

<script>
    $('#estimate_amount, #actual_cost, #margin_simulation').on('click', function(e) {
        e.stopPropagation()
        chartFilter()
    });

    const chartFilter = () => {
        var projectId = $('#projectId').val();
        var amountcheck = $('#estimate_amount').is(':checked') ? 1 : 0;
        var costCheck = $('#actual_cost').is(':checked') ? 1 : 0;
        var marginCheck = $('#margin_simulation').is(':checked') ? 1 : 0;

        var url = "{{ route('projects.forecastChart') }}";

        $.ajax({
            url: url,
            type: "GET",
            blockUI: true,
            data: {
                id: projectId,
                amountcheck: amountcheck,
                costCheck: costCheck,
                marginCheck: marginCheck
            },
            success: function (response) {
                if (response.status === "success") {
                    var forecastChartLebels=response.data.chartLebels;
                    var forecastChartDataSet=response.data.chartDataset;
                    var forecastChartColors=response.data.chartColors;

                    var forecastData = {
                        labels: forecastChartLebels,
                        datasets: forecastChartDataSet
                    }

                    var foreCastChart =new frappe.Chart("#forecast-chart", {
                        data: forecastData,
                        type: 'line',
                        height: 250,
                        lineOptions: {
                                dotSize: 4,
                                hideLine: 0,
                                hideDots: 0,
                                heatline: 1,
                                regionFill: 1,
                            },
                            valuesOverPoints: 1,
                            // axisOptions: {
                            //     yAxisMode: 'tick',
                            //     xAxisMode: 'tick',
                            //     xIsSeries: 0
                            // },
                        colors: forecastChartColors
                    });
                }
            }
        });
    }
</script>


{{-- second forecast chart --}}
<script>
    const runForecastChart2 = () => {
        var projectId = $('#projectId').val();
        var amountcheck2 = $('#estimate_amount2').is(':checked') ? 1 : 0;
        var costCheck2 = $('#actual_cost2').is(':checked') ? 1 : 0;
        var marginCheck2 = $('#margin_simulation2').is(':checked') ? 1 : 0;

        var url = "{{ route('projects.2ndForecastChart') }}";

        $.ajax({
            url: url,
            type: "GET",
            blockUI: true,
            data: {
                id: projectId,
                amountcheck: amountcheck2,
                costCheck: costCheck2,
                marginCheck: marginCheck2
            },
            success: function (response) {
                if (response.status === "success") {
                    var forecastChartLebels2=response.data.chartLebels;
                    var forecastChartDataSet2=response.data.chartDataset;
                    var forecastChartColors2=response.data.chartColors;

                    var forecastData2 = {
                        labels: forecastChartLebels2,
                        datasets: forecastChartDataSet2
                    }

                    var foreCastChart2 =new frappe.Chart("#forecast-chart2", {
                        data: forecastData2,
                        type: 'line',
                        height: 250,
                        lineOptions: {
                                dotSize: 4,
                                hideLine: 0,
                                hideDots: 0,
                                heatline: 1,
                                regionFill: 1,
                            },
                            valuesOverPoints: 1,
                            // axisOptions: {
                            //     yAxisMode: 'tick',
                            //     xAxisMode: 'tick',
                            //     xIsSeries: 0
                            // },
                        colors: forecastChartColors2
                    });
                }
            }
        });
    }
    runForecastChart2();
</script>

<script>
    $('#estimate_amount2, #actual_cost2, #margin_simulation2').on('click', function(e) {
        e.stopPropagation()
        chartFilter2()
    });

    const chartFilter2 = () => {
        var projectId = $('#projectId').val();
        var amountcheck2 = $('#estimate_amount2').is(':checked') ? 1 : 0;
        var costCheck2 = $('#actual_cost2').is(':checked') ? 1 : 0;
        var marginCheck2 = $('#margin_simulation2').is(':checked') ? 1 : 0;

        var url = "{{ route('projects.2ndForecastChart') }}";

        $.ajax({
            url: url,
            type: "GET",
            blockUI: true,
            data: {
                id: projectId,
                amountcheck: amountcheck2,
                costCheck: costCheck2,
                marginCheck: marginCheck2
            },
            success: function (response) {
                if (response.status === "success") {
                    var forecastChartLebels2=response.data.chartLebels;
                    var forecastChartDataSet2=response.data.chartDataset;
                    var forecastChartColors2=response.data.chartColors;

                    var forecastData2 = {
                        labels: forecastChartLebels2,
                        datasets: forecastChartDataSet2
                    }

                    var foreCastChart2 =new frappe.Chart("#forecast-chart2", {
                        data: forecastData2,
                        type: 'line',
                        height: 250,
                        lineOptions: {
                                dotSize: 4,
                                hideLine: 0,
                                hideDots: 0,
                                heatline: 1,
                                regionFill: 1,
                            },
                            valuesOverPoints: 1,
                            // axisOptions: {
                            //     yAxisMode: 'tick',
                            //     xAxisMode: 'tick',
                            //     xIsSeries: 0
                            // },
                        colors: forecastChartColors2
                    });
                }
            }
        });
    }
</script>

@endif


