@extends('layouts.app')

@push('datatable-styles')
    @include('sections.daterange_css')
@endpush

@push('styles')
    <style>
        .h-200 {
            height: 340px;
            overflow-y: auto;
        }

        .dashboard-settings {
            width: 600px;
        }

        @media (max-width: 768px) {
            .dashboard-settings {
                width: 300px;
            }
        }
    .dropdown-toggle {
        background-color: #fff;
        border-color: #fff !important;
        font-size: 14px;
        padding: .5rem;
    }

    </style>
@endpush

@section('filter-section')
    <!-- FILTER START -->
    <!-- DASHBOARD HEADER START -->
    <div class="d-flex filter-box project-header bg-white dashboard-header">

        <div class="mobile-close-overlay w-100 h-100" id="close-client-overlay"></div>
        <div class="project-menu d-lg-flex" id="mob-client-detail">

            <a class="d-none close-it" href="javascript:;" id="close-client-detail">
                <i class="fa fa-times"></i>
            </a>

            @if ($viewOverviewDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=overview'" :text="__('modules.projects.overview')"
                       class="overview" ajax="false"/>
            @endif

            @if (in_array('projects', user_modules()) && $viewProjectDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=project'" :text="__('app.project')" class="project"
                       ajax="false"/>
            @endif

            @if (in_array('clients', user_modules()) && $viewClientDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=client'" :text="__('app.client')" class="client"
                       ajax="false"/>
            @endif

            @if ($viewHRDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=hr'" :text="__('app.menu.hr')" class="hr" ajax="false"/>
            @endif

            @if (in_array('tickets', user_modules()) && $viewTicketDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=ticket'" :text="__('app.menu.ticket')" class="ticket"
                       ajax="false"/>
            @endif

            @if ($viewFinanceDashboard == 'all')
                <x-tab :href="route('dashboard.advanced').'?tab=finance'" :text="__('app.menu.finance')" class="finance"
                       ajax="false"/>
            @endif
            @if (request('tab') == 'overview' || request('tab') == '')
            <div class="d-flex align-items-center border-left-grey-sm-0 border-right-grey h-100 pl-4">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.year')</p>
                <div class="select-status">
                    <div class="select-status">
                        <select class="form-control select-picker" name="projectYear" id="projectYear" data-live-search="true" data-size="8">
                            <option  value="">@lang('app.all')</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center border-left-grey-sm-0 h-100 pl-4">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.month')</p>
                <div class="select-status">
                    <div class="select-status">
                        <select class="form-control select-picker" name="projectMonth" id="projectMonth" data-live-search="true" data-size="8">
                            <option  value="">@lang('app.all')</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center border-left-grey border-left-grey-sm-0 h-100 pl-4">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('modules.projects.projectCategory')</p>
                <div class="select-status">
                    <div class="select-status">
                        <select class="form-control select-picker" name="projectCategoryId" id="projectCategoryId" data-live-search="true" data-size="8">
                            <option  value="">@lang('app.all')</option>
                            @foreach ($projectCategories as $category)
                                <option value="{{$category->id}}">{{ ucfirst($category->category_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center border-left-grey border-left-grey-sm-0 border-right-grey h-100 pl-4">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('modules.projects.projectSubCategory')</p>
                <div class="select-status">
                    <div class="select-status">
                        <select class="form-control select-picker" name="projectSubCategoryId" id="projectSubCategoryId" data-live-search="true" data-size="8">
                            <option  value="">@lang('app.all')</option>
                            @foreach ($porjectsubcategories as $subCategory)
                                <option value="{{$subCategory->id}}">{{ ucfirst($subCategory->category_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif
        </div>


        <div class="ml-auto d-flex align-items-center justify-content-center ">
            <!-- DATE START -->
            <div
                class="{{ request('tab') == 'overview' || request('tab') == '' ? 'd-none' : 'd-flex' }} align-items-center border-left-grey border-left-grey-sm-0 h-100 pl-4">
                <i class="fa fa-calendar-alt mr-2 f-14 text-dark-grey"></i>
                <div class="select-status">
                    <input type="text"
                           class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                           id="datatableRange2" placeholder="@lang('placeholders.dateRange')">
                </div>
            </div>
            <!-- DATE END -->
            @if (isset($widgets) && in_array('admin', user_roles()))
                <div class="admin-dash-settings">
                    <x-form id="dashboardWidgetForm" method="POST">
                        <div class="dropdown keep-open">
                            <a class="d-flex align-items-center justify-content-center dropdown-toggle px-lg-4 border-left-grey text-dark"
                               type="link" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <i class="fa fa-cog" title="{{__('modules.dashboard.dashboardWidgetsSettings')}}" data-toggle="tooltip"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <ul class="dropdown-menu dropdown-menu-right dashboard-settings p-20"
                                aria-labelledby="dropdownMenuLink" tabindex="0">
                                <li class="border-bottom mb-3">
                                    <h4 class="heading-h3">@lang('modules.dashboard.dashboardWidgets')</h4>
                                </li>
                                @foreach ($widgets as $widget)
                                    @php
                                        $wname = \Illuminate\Support\Str::camel($widget->widget_name);
                                    @endphp
                                    <li class="mb-2 float-left w-50">
                                        <div class="checkbox checkbox-info ">
                                            <input id="{{ $widget->widget_name }}" name="{{ $widget->widget_name }}"
                                                   value="true" @if ($widget->status) checked @endif type="checkbox">
                                            <label for="{{ $widget->widget_name }}">@lang('modules.dashboard.' .
                                            $wname)</label>
                                        </div>
                                    </li>
                                @endforeach
                                @if (count($widgets) % 2 != 0)
                                    <li class="mb-2 float-left w-50 height-35"></li>
                                @endif
                                <li class="float-none w-100">
                                    <x-forms.button-primary id="save-dashboard-widget" icon="check">@lang('app.save')
                                    </x-forms.button-primary>
                                </li>
                            </ul>
                        </div>
                    </x-form>
                </div>
            @endif

        </div>

        <a class="mb-0 d-block d-lg-none text-dark-grey mr-2 border-left-grey border-bottom-0"
           onclick="openClientDetailSidebar()"><i class="fa fa-ellipsis-v"></i></a>

    </div>
    <!-- FILTER END -->
    <!-- DASHBOARD HEADER END -->

@endsection

@section('content')

    <!-- CONTENT WRAPPER START -->
    <div class="px-4 py-0 py-lg-3  border-top-0 admin-dashboard">
        @include($view)
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    <script src="{{ asset('vendor/jquery/daterangepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            var format = '{{ company()->moment_date_format }}';
            var startDate = "{{ $startDate->translatedFormat(company()->date_format) }}";
            var endDate = "{{ $endDate->translatedFormat(company()->date_format) }}";
            var start = moment(startDate, format);
            var end = moment(endDate, format);

            $('#datatableRange2').daterangepicker({
                locale: daterangeLocale,
                linkedCalendars: false,
                startDate: start,
                endDate: end,
                ranges: daterangeConfig,
                opens: 'left',
                parentEl: '.dashboard-header'
            }, cb);


            $('#datatableRange2').on('apply.daterangepicker', function (ev, picker) {
                showTable();
            });

        });
    </script>


    <script>
        $(".dashboard-header").on("click", ".ajax-tab", function (event) {
            event.preventDefault();

            $('.project-menu .p-sub-menu').removeClass('active');
            $(this).addClass('active');

            const dateRangePicker = $('#datatableRange2').data('daterangepicker');
            let startDate = $('#datatableRange').val();

            let endDate;

            if (startDate === '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: ".admin-dashboard",
                historyPush: true,
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('.admin-dashboard').html(response.html);
                        init('.admin-dashboard');
                    }
                }
            });
        });

        $('.keep-open .dropdown-menu').on({
            "click": function (e) {
                e.stopPropagation();
            }
        });

        $('#projectYear, #projectMonth, #projectCategoryId, #projectSubCategoryId').on('changed.bs.select', function(e) {
            e.stopPropagation()
            yearFilter()
        });

        function yearFilter() {
            let projectYear = $('#projectYear').val();
            let projectMonth = $('#projectMonth').val();
            let projectCategoryId = $('#projectCategoryId').val();
            let projectSubCategoryId = $('#projectSubCategoryId').val();
            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: ".admin-dashboard",
                data: {
                    projectYear: projectYear,
                    projectMonth: projectMonth,
                    projectCategoryId: projectCategoryId,
                    projectSubCategoryId: projectSubCategoryId,
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('.admin-dashboard').html(response.html);
                        init('.admin-dashboard');
                    }
                }
            });
        }

        function showTable() {
            const dateRangePicker = $('#datatableRange2').data('daterangepicker');
            let startDate = $('#datatableRange').val();

            let endDate;
            if (startDate === '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: ".admin-dashboard",
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('.admin-dashboard').html(response.html);
                        init('.admin-dashboard');
                    }
                }
            });
        }
    </script>
    <script>
        const activeTab = "{{ $activeTab }}";
        $('.project-menu .' + activeTab).addClass('active');
    </script>
@endpush
