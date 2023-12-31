<script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>

@push('styles')
<style>
    .font-black{
        color: #28313c !important;
    }
    .amount-table tr td {
        box-shadow: 0 1px 0 0 #fff;
        padding: 2px !important;
    }
    .amount-table .br-bottom {
        padding-bottom: 16px !important;
        border-bottom: 1px solid rgba(0,0,0,.1) !important;
    }
</style>
@endpush

<div class="row">

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('estimates.index') }}">
            <x-cards.widget :title="__('app.totalEstimate')" :value="$counts->totalestimates"
                icon="file-invoice">
            </x-cards.widget>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('estimates.index') }}">
            <x-cards.widget :title="__('app.totalEstimateAmount')" :value="is_null($counts->totalestimateAmount) ? 0 : currency_format($counts->totalestimateAmount, company()->currency_id)"
                icon="fa fa-coins">
            </x-cards.widget>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <x-cards.widget :title="__('modules.dashboard.totalProjects')" :value="$counts->totalProjects"
                icon="layer-group">
            </x-cards.widget>
        </a>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <x-cards.widget :title="__('app.totalProjectAmount')" :value="is_null($counts->totalProjectAmount) ? 0 : currency_format($counts->totalProjectAmount, company()->currency_id)"
                icon="fa fa-coins">
            </x-cards.widget>
        </a>
    </div>


    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <x-cards.data :title="__('app.estimateStatus')">
            <x-pie-chart id="task-chart" :labels="$statusWiseCountEstimate['labels']"
                :values="$statusWiseCountEstimate['values']" :colors="$statusWiseCountEstimate['colors']" height="250" width="300" />
        </x-cards.data>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <x-cards.data :title="__('app.estimateStatusAmount')">
            <x-pie-amount-chart id="task-chart2" :labels="$statusWiseAmountEstimate['labels']"
                :values="$statusWiseAmountEstimate['values']" :colors="$statusWiseAmountEstimate['colors']" height="250" width="300" />
        </x-cards.data>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <x-cards.data :title="__('app.projectStatus')">
            <x-pie-chart id="task-chart3" :labels="$statusWiseCountProject['labels']"
                :values="$statusWiseCountProject['values']" :colors="$statusWiseCountProject['colors']" height="250" width="300" />
        </x-cards.data>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <x-cards.data :title="__('app.projectStatusAmount')">
            <x-pie-amount-chart id="task-chart4" :labels="$statusWiseAmountProject['labels']"
                :values="$statusWiseAmountProject['values']" :colors="$statusWiseAmountProject['colors']" height="250" width="300" />
        </x-cards.data>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <x-cards.widget :title="__('app.totalProjectCost')" :value="is_null($counts->totalProjectCost) ? 0 : currency_format($counts->totalProjectCost, company()->currency_id)"
                icon="fa fa-coins">
            </x-cards.widget>
        </a>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <x-cards.widget :title="__('app.totalProjectMargin')" :value="is_null($counts->totalProjectMargin) ? 0 : currency_format($counts->totalProjectMargin, company()->currency_id)"
                icon="fa fa-coins">
            </x-cards.widget>
        </a>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a class="py-4" href="{{ route('projects.index') }}">
            <x-cards.widget :title="__('app.totalProjectHour')" :value="$counts->totalprojectHour"
                icon="fa fa-clock">
            </x-cards.widget>
        </a>
    </div>




    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectAmount')</h5>
                    {{-- <div class="d-flex">
                        <p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount'):</p>
                        <p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateExpense, company()->currency_id) }}</span>
                        </p>
                    </div>
                    <div class="d-flex">
                        <p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount'):</p>
                        <p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateService, company()->currency_id) }}</span>
                        </p>
                    </div> --}}
                    <table class="table amount-table table-borderless">
                    <tbody>
                        <tr>
                          <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount')</p></td>
                          <td>:</td>
                          <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateService, company()->currency_id) }}</span>
                          </p></td>
                        </tr>
                        <tr>
                          <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p></td>
                          <td class="br-bottom">:</td>
                          <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateExpense, company()->currency_id) }}</span>
                          </p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                            <td>:</td>
                            <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateExpense+$counts->totalEstimateService), company()->currency_id) }}</span>
                            </p></td>
                        </tr>
                      </tbody>
                    </table>
                    {{-- <hr class="mb-1">
                    <div class="d-flex">
                        <p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.total'):</p>
                        <p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateExpense+$counts->totalEstimateService), company()->currency_id) }}</span>
                        </p>
                    </div> --}}
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectCost')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceCost'):</p></td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectServiceCost, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseCost')</p></td>
                              <td class="br-bottom">:</td>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseCost, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceCost+$counts->totalEstimateProjectExpenseCost), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectMargin')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceMargin')</p></td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectServiceMargin, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseMargin')</p>
                              </td>
                              <td class="br-bottom">:</td>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseMargin, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceMargin+$counts->totalEstimateProjectExpenseMargin), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

{{-- completed project --}}
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectAmountCompleted')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount')</p></td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom">:</td>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountCompleted, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs+$counts->totalEstimateProjectExpenseAmountCompleted), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectActualAmountCompleted')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount')</p></td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmount), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom">:</td>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalPeojectActualExpenseAmount, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmount+$counts->totalPeojectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalCompletedProjectRealCost')</h5>
                <table class="table amount-table table-borderless">
                    <tbody>
                        <tr>
                          <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalProjectAmountCompleted')</p></td>
                          <td>:</td>
                          <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs+$counts->totalEstimateProjectExpenseAmountCompleted), company()->currency_id) }}</span>
                          </p></td>
                        </tr>
                        <tr>
                          <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalProjectActualAmountCompleted')</p>
                          </td>
                          <td class="br-bottom">:</td>
                          <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmount+$counts->totalPeojectActualExpenseAmount), company()->currency_id) }}</span>
                          </p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.RealCost')</p></td>
                            <td>:</td>
                            <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format((($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs+$counts->totalEstimateProjectExpenseAmountCompleted)-($counts->totalProjectSrviceActualAmount+$counts->totalPeojectActualExpenseAmount)), company()->currency_id) }}</span>
                            </p></td>
                        </tr>
                      </tbody>
                </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalCompletedProjectActualHour')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.CompletedProjectEstimateHours')</p></td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ ($counts->totalEstimatedHrs) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.CompletedProjectRegisteredHours')</p>
                              </td>
                              <td class="br-bottom">:</td>
                              <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ ($counts->totalActualHrs) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.difference')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ (($counts->totalEstimatedHrs)-($counts->totalActualHrs)) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa-clock text-lightest f-18"></i>
            </div>
        </a>
    </div>

{{-- finished projects divided between price/hrs, hrs and expense --}}
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectAmountCompleted')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                                </td>
                                <td class="br-bottom">:</td>
                                <td class="br-bottom"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountCompleted, company()->currency_id) }}</span>
                                </p></td>
                              </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs+$counts->totalEstimateProjectExpenseAmountCompleted), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectActualAmountCompleted')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmountNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmountHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                                </td>
                                <td class="br-bottom mb-3">:</td>
                                <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalPeojectActualExpenseAmount, company()->currency_id) }}</span>
                                </p></td>
                              </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalProjectSrviceActualAmountNotHrs+$counts->totalProjectSrviceActualAmountHrs+$counts->totalPeojectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=5">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalCompletedProjectRealCost')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs-$counts->totalProjectSrviceActualAmountNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedHrs-$counts->totalProjectSrviceActualAmountHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                                </td>
                                <td class="br-bottom mb-3">:</td>
                                <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountCompleted-$counts->totalPeojectActualExpenseAmount, company()->currency_id) }}</span>
                                </p></td>
                              </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountCompletedNotHrs+$counts->totalEstimateProjectServiceAmountCompletedHrs+$counts->totalEstimateProjectExpenseAmountCompleted)-($counts->totalProjectSrviceActualAmountNotHrs+$counts->totalProjectSrviceActualAmountHrs+$counts->totalPeojectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>


    {{-- in Progress project --}}
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectAmountInProgress')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountInProgress, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs+$counts->totalEstimateProjectExpenseAmountInProgress), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectActualAmountInProgress')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalServiceAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalInProgressProjectActualExpenseAmount, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs+$counts->totalInProgressProjectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

        <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectRealCostInProgress')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalProjectAmountCompleted')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs+$counts->totalEstimateProjectExpenseAmountInProgress), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalProjectActualAmountCompleted')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs+$counts->totalInProgressProjectActualExpenseAmount), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.RealCost')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs+$counts->totalEstimateProjectExpenseAmountInProgress)-($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs+$counts->totalInProgressProjectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalRunningProjectActualHour')</h5>

                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.RunningProjectEstimateHours')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ ($counts->totalInProgressEstimatedHrs) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.RunningProjectRegisteredHours')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ ($counts->totalInProgressActualHrs) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.difference')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ (($counts->totalInProgressEstimatedHrs)-($counts->totalInProgressActualHrs)) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>

                </div>
                <i class="fa fa-clock text-lightest f-18"></i>
            </div>
        </a>
    </div>

    {{-- in progress projects divided between price/hrs, hrs and expense --}}
    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectAmountInProgress')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressHrs), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountInProgress, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs+$counts->totalEstimateProjectExpenseAmountInProgress), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectActualAmountInProgress')</h5>
                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountHrs), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalInProgressProjectActualExpenseAmount, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs+$counts->totalInProgressProjectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <a href="{{ route('projects.index') }}?statusId=1">
            <div class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center">
                <div class="d-block text-capitalize">
                    <h5 class="f-18 f-w-500 mb-20 font-black">@lang('app.totalProjectRealCostInProgress')</h5>

                    <table class="table amount-table table-borderless">
                        <tbody>
                            <tr>
                              <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalPricePerHrsAmount')</p>
                            </td>
                              <td>:</td>
                              <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs-$counts->totalInProgressProjectSrviceActualAmountNotHrs), company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalHrsAmount')</p>
                              </td>
                                <td>:</td>
                                <td><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressHrs-$counts->totalInProgressProjectSrviceActualAmountHrs), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                            <tr>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold font-black d-grid mr-1">@lang('app.totalExpenseAmount')</p>
                              </td>
                              <td class="br-bottom mb-3">:</td>
                              <td class="br-bottom mb-3"><p class="mb-0 f-15 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format($counts->totalEstimateProjectExpenseAmountInProgress-$counts->totalInProgressProjectActualExpenseAmount, company()->currency_id) }}</span>
                              </p></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0 f-18 font-weight-bold font-black d-grid mr-1">@lang('app.total')</p></td>
                                <td>:</td>
                                <td><p class="mb-0 f-18 font-weight-bold text-blue text-primary d-grid"><span id="">{{ currency_format(($counts->totalEstimateProjectServiceAmountInProgressNotHrs+$counts->totalEstimateProjectServiceAmountInProgressHrs+$counts->totalEstimateProjectExpenseAmountInProgress)-($counts->totalInProgressProjectSrviceActualAmountNotHrs+$counts->totalInProgressProjectSrviceActualAmountHrs+$counts->totalInProgressProjectActualExpenseAmount), company()->currency_id) }}</span>
                                </p></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
                <i class="fa fa fa-coins text-lightest f-18"></i>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
    </div>

    @if (in_array('clients', $modules) && in_array('total_clients', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('clients.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalClients')" :value="$counts->totalClients"
                    icon="users">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('employees', $modules) && in_array('total_employees', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('employees.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalEmployees')" :value="$counts->totalEmployees"
                    icon="user">
                </x-cards.widget>
            </a>
        </div>
    @endif

    {{-- @if (in_array('projects', $modules) && in_array('total_projects', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('projects.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalProjects')" :value="$counts->totalProjects"
                    icon="layer-group">
                </x-cards.widget>
            </a>
        </div>
    @endif --}}

    @if (in_array('invoices', $modules) && in_array('total_unpaid_invoices', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('invoices.index') . '?status=pending' }}">
                <x-cards.widget :title="__('modules.dashboard.totalUnpaidInvoices')"
                    :value="$counts->totalUnpaidInvoices" icon="file-invoice">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('timelogs', $modules) && in_array('total_hours_logged', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('time-log-report.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalHoursLogged')" :value="$counts->totalHoursLogged"
                    icon="clock">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('tasks', $modules) && in_array('total_pending_tasks', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('tasks.index') }}?status=pending_task&type=public">
                <x-cards.widget :title="__('modules.dashboard.totalPendingTasks')" :value="$counts->totalPendingTasks"
                    icon="tasks" :info="__('modules.dashboard.privateTaskInfo')">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('attendance', $modules) && in_array('total_today_attendance', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('attendances.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalTodayAttendance')"
                    :value="$counts->totalTodayAttendance.'/'.$counts->totalEmployees" icon="calendar-check">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('tickets', $modules) && in_array('total_unresolved_tickets', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('tickets.index') . '?status=open' }}">
                <x-cards.widget :title="__('modules.tickets.totalUnresolvedTickets')"
                    :value="floor($counts->totalOpenTickets)" icon="ticket-alt">
                </x-cards.widget>
            </a>
        </div>
    @endif

</div>

<div class="row">
    @if (in_array('payments', $modules) && in_array('recent_earnings', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('app.income').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'">
                <x-bar-chart id="task-chart1" :chartData="$earningChartData" height="250"></x-bar-chart>
            </x-cards.data>
        </div>
    @endif


    {{-- Time log line chart --}}
    {{-- @if (in_array('timelogs', $modules) && in_array('timelogs', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('app.menu.timeLogs').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'">
                <x-line-chart id="task-chart2" :chartData="$timlogChartData" height="250"></x-line-chart>
            </x-cards.data>
        </div>
    @endif --}}

    @if (in_array('leaves', $modules) && in_array('settings_leaves', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.leaves.pendingLeaves').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($leaves as $item)
                        <tr>
                            <td class="pl-20">
                                <x-employee :user="$item->user" />
                            </td>
                            <td class="text-darkest-grey">{{ $item->leave_date->translatedFormat(company()->date_format) }}</td>
                            <td class="f-14">
                                <x-status :style="'color:'.$item->type->color" :value="$item->type->type_name"></x-status>
                            </td>
                            <td align="right" class="pr-20">
                                <div class="task_view">
                                    <a href="{{ route('leaves.show', [$item->id]) }}"
                                        class="taskView openRightModal">@lang('app.view')</a>
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
                                            type="link" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuLink" tabindex="0">
                                            <a class="dropdown-item leave-action-approved" data-leave-id='{{ $item->id }}'
                                                data-leave-action="approved" href="javascript:;">
                                                <i class="fa fa-check mr-2"></i>
                                                {{ __('app.approve') }}
                                            </a>
                                            <a data-leave-id='{{ $item->id }}' data-leave-action="rejected"
                                                class="dropdown-item leave-action-reject" href="javascript:;">
                                                <i class="fa fa-times mr-2"></i>
                                                {{ __('app.reject') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('tickets', $modules) && in_array('new_tickets', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.openTickets').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($newTickets as $item)
                        <tr>
                            <td class="pl-20">
                                <div class="avatar-img rounded">
                                    <img src="{{ $item->requester->image_url }}"
                                        alt="{{ $item->requester->name }}" title="{{ $item->requester->name }}">
                                </div>
                            </td>
                            <td><a href="{{ route('tickets.show', $item->id) }}"
                                    class="text-darkest-grey">{{ ucfirst($item->subject) }}</a>
                                <br />
                                <span class="f-10 text-lightest mt-1">{{ $item->requester->name }}</span>
                            </td>
                            <td class="text-darkest-grey" width="15%">{{ $item->updated_at->translatedFormat(company()->date_format) }}</td>
                            <td class="f-14 pr-20 text-right" width="20%">
                                @php
                                    if ($item->priority == 'low') {
                                        $priority = 'dark-green';
                                    } elseif ($item->priority == 'medium') {
                                        $priority = 'blue';
                                    } elseif ($item->priority == 'high') {
                                        $priority = 'yellow';
                                    } elseif ($item->priority == 'urgent') {
                                        $priority = 'red';
                                    }
                                @endphp
                                <x-status :color="$priority" :value="__('app.' . $item->priority)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="ticket-alt" :message="__('messages.noRecordFound')" />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('tasks', $modules) && in_array('overdue_tasks', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.totalPendingTasks').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($pendingTasks as $task)
                        <tr>
                            <td class="pl-20">
                                <h5 class="f-13 text-darkest-grey"><a href="{{ route('tasks.show', [$task->id]) }}"
                                        class="openRightModal">{{ $task->heading }}</a></h5>
                                <div class="text-muted">{{ $task->project->project_name ?? '' }}</div>
                            </td>
                            <td>
                                @foreach ($task->users as $item)
                                    <div class="taskEmployeeImg rounded-circle mr-1">
                                        <a href="{{ route('employees.show', $item->id) }}">
                                            <img data-toggle="tooltip"
                                                data-original-title="{{ mb_ucwords($item->name) }}"
                                                src="{{ $item->image_url }}">
                                        </a>
                                    </div>
                                @endforeach
                            </td>
                            <td width="15%">@if(!is_null($task->due_date)) {{ $task->due_date->translatedFormat(company()->date_format) }} @else -- @endif</td>
                            <td class="f-14 pr-20 text-right" width="20%">
                                <x-status :style="'color:'.$task->boardColumn->label_color"
                                    :value="($task->boardColumn->slug == 'completed' || $task->boardColumn->slug == 'incomplete' ? __('app.' . $task->boardColumn->slug) : $task->boardColumn->column_name)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="shadow-none">
                                <x-cards.no-record icon="tasks" :message="__('messages.noRecordFound')" />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('leads', $modules) && in_array('pending_follow_up', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.pendingFollowUp').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($pendingLeadFollowUps as $item)
                        <tr>
                            <td class="pl-20">
                                <h5 class="f-13 text-darkest-grey"><a
                                        href="{{ route('leads.show', [$item->id]) }}">{{ $item->client_name }}</a>
                                </h5>
                                <div class="text-muted">{{ $item->company_name }}</div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->follow_up_date_past)->timezone(company()->timezone)->translatedFormat(company()->date_format) }}
                            </td>
                            <td class="pr-20 text-right">
                                @if ($item->agent_id)
                                    <x-employee :user="$item->leadAgent->user" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="shadow-none">
                                <x-cards.no-record icon="users" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('projects', $modules) && in_array('project_activity_timeline', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.projectActivityTimeline').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false"
                otherClasses="h-200 p-activity-detail cal-info">
                @forelse($projectActivities as $key=>$activity)
                    <div class="card border-0 b-shadow-4 p-20 rounded-0">
                        <div class="card-horizontal">
                            <div class="card-header m-0 p-0 bg-white rounded">
                                <x-date-badge :month="$activity->created_at->timezone(company()->timezone)->translatedFormat('M')"
                                    :date="$activity->created_at->timezone(company()->timezone)->translatedFormat('d')">
                                </x-date-badge>
                            </div>
                            <div class="card-body border-0 p-0 ml-3">
                                <h4 class="card-title f-14 font-weight-normal text-capitalize mb-0">
                                    {!! __($activity->activity) !!}
                                </h4>
                                <p class="card-text f-12 text-dark-grey">
                                    <a href="{{ route('projects.show', $activity->project_id) }}"
                                        class="text-lightest font-weight-normal text-capitalize f-12">{{ mb_ucwords($activity->project->project_name) }}
                                    </a>
                                    <br>
                                    {{ $activity->created_at->timezone(company()->timezone)->translatedFormat(company()->time_format) }}
                                </p>
                            </div>
                        </div>
                    </div><!-- card end -->
                @empty
                    <div class="card border-0 p-20 rounded-0">
                        <x-cards.no-record icon="tasks" :message="__('messages.noRecordFound')" />
                    </div><!-- card end -->
                @endforelse
            </x-cards.data>
        </div>
    @endif

    @if (in_array('employees', $modules) && in_array('user_activity_timeline', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.userActivityTimeline').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false"
                otherClasses="h-200 p-activity-detail cal-info">
                @forelse($userActivities as $key=>$activity)
                    <div class="card border-0 b-shadow-4 p-20 rounded-0">
                        <div class="card-horizontal">
                            <div class="card-header m-0 p-0 bg-white rounded">
                                <x-date-badge :month="$activity->created_at->timezone(company()->timezone)->translatedFormat('M')"
                                    :date="$activity->created_at->timezone(company()->timezone)->translatedFormat('d')">
                                </x-date-badge>
                            </div>
                            <div class="card-body border-0 p-0 ml-3">
                                <h4 class="card-title f-14 font-weight-normal text-capitalize mb-0">
                                    {!! __($activity->activity) !!}
                                </h4>
                                <p class="card-text f-12 text-dark-grey">
                                    <a href="{{ route('employees.show', $activity->user_id) }}"
                                        class="text-lightest font-weight-normal text-capitalize f-12">{{ mb_ucwords($activity->user->name) }}
                                    </a>
                                    <br>
                                    {{ $activity->created_at->timezone(company()->timezone)->translatedFormat(company()->time_format) }}
                                </p>
                            </div>
                        </div>
                    </div><!-- card end -->
                @empty
                    <div class="card border-0 p-20 rounded-0">
                        <x-cards.no-record icon="users" :message="__('messages.noRecordFound')" />
                    </div><!-- card end -->
                @endforelse
            </x-cards.data>
        </div>
    @endif
</div>

<script>
    $('body').on('click', '.leave-action-approved', function() {
        let action = $(this).data('leave-action');
        let leaveId = $(this).data('leave-id');
        let searchQuery = "?leave_action=" + action + "&leave_id=" + leaveId;
        let url = "{{ route('leaves.show_approved_modal') }}" + searchQuery;

        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });


    $('#save-dashboard-widget').click(function() {
        $.easyAjax({
            url: "{{ route('dashboard.widget', 'admin-dashboard') }}",
            container: '#dashboardWidgetForm',
            blockUI: true,
            type: "POST",
            redirect: true,
            data: $('#dashboardWidgetForm').serialize(),
            success: function() {
                window.location.reload();
            }
        })
    });
</script>
