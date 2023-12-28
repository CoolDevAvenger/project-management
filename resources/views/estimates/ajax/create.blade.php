@php
    $addProductPermission = user()->permission('add_product');
@endphp
@push('styles')
<style>
    .service_discount_value, .expense_price, .expense_margin_percent, .expense_discount_value, .service_margin_percent {
        padding: 0.5rem !important;
        border: 1px solid #e7e9eb !important;
        border-radius: 0.25rem !important;
    }

    .c-inv-desc table tr td {
        padding: 6px 6px !important;
    }
     .select-picker>.item_name {
        padding: 0rem!important;
    }

    .tax-select>.bootstrap-select>.dropdown-toggle {
        /* width: 100% !important; */
    }

    .text-red{
        color: #ed4040;
    }
    .success-btn{
        background-color: #48b95b !important;
        border: 1px solid #48b95b !important;
    }
    .success-btn:hover{
        background-color: #48b95b !important;
        border: 1px solid #48b95b !important;
        cursor: auto;
    }

    .form-control-tax {
        background-color: #fff;
        border: 1px solid #e8eef3;
        border-radius: .25rem;
        box-shadow: none;
        color: #28313c;
        font-weight: 400;
        height: auto;
        padding: 6px !important;
        position: relative;
        transition: all .3s ease;
        /* width: 40%; */
    }

    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: auto;
    }
    .bootstrap-select:not(.input-group-btn), .bootstrap-select[class*=col-] {
        display: inline;
    }

    .pr-category{width: 16% !important;}
    .pr-subcategory{width: 16% !important;}
    .pr-name{width: 16% !important;}
    .pr-unittype{width: 8% !important;}
    .pr-unitprice{width: 8% !important;}
    .pr-margin{width: 8% !important;}
    .pr-tax{width: 7% !important;}
    .pr-amount{width: 7% !important;}
    .pr-cost{width: 7% !important;}
    .pr-cost-margin{width: 7% !important;}
    .pr-removeitem{width: 1% !important;}

    @media (min-width: 1235px) and (max-width: 1359px){
        table {
            font-size: 12px !important;
        }
        .c-inv-desc table tr.f-14 {
            font-size: 13px !important;
        }
        .c-inv-desc table tr td input.f-14 {
            font-size: 12px !important;
        }
        .c-inv-desc table .bootstrap-select>.dropdown-toggle, .input-group .bootstrap-select.form-control .dropdown-toggle {
            font-size: 12px;
        }
    }

    @media (min-width: 1235px) and (max-width: 1359px){
        table {
            font-size: 12px !important;
        }
        .c-inv-desc table tr.f-14 {
            font-size: 13px !important;
        }
        .c-inv-desc table tr td input.f-14 {
            font-size: 12px !important;
        }
        .c-inv-desc table .bootstrap-select>.dropdown-toggle, .input-group .bootstrap-select.form-control .dropdown-toggle {
            font-size: 12px;
        }

        .add_expense_row .f-14 {font-size: 12px !important; transition: all .3s ease;}
        .desktop-description {font-size: 12px !important;}
    }

    @media (min-width: 1087px) and (max-width: 1235px){
        table {
            font-size: 11px !important;
        }
        .c-inv-desc table tr.f-14 {
            font-size: 12px !important;
        }
        .c-inv-desc table tr td input.f-14 {
            font-size: 11px !important;
        }
        .c-inv-desc table .bootstrap-select>.dropdown-toggle, .input-group .bootstrap-select.form-control .dropdown-toggle {
            font-size: 11px;
        }

        .add_expense_row .f-14 {font-size: 11px !important; transition: all .3s ease;}
        .desktop-description {font-size: 11px !important;}
    }

    @media (max-width: 1087px){
        table {
            font-size: 9px !important;
        }
        .c-inv-desc table tr.f-14 {
            font-size: 10px !important;
        }
        .c-inv-desc table tr td input.f-14 {
            font-size: 9px !important;
        }
        .c-inv-desc table .bootstrap-select>.dropdown-toggle, .input-group .bootstrap-select.form-control .dropdown-toggle {
            font-size: 9px;
        }

        .add_expense_row .f-14 {font-size: 9px !important; transition: all .3s ease;}
        .desktop-description {font-size: 9px !important;}
    }

    .btn-outline-secondary{
        padding-left: 4px !important;
        padding-right: 4px !important;
    }

</style>
@endpush
<!-- CREATE INVOICE START -->
<div class="bg-white rounded b-shadow-4 create-inv">
    <!-- HEADING START -->
    <div class="px-lg-4 px-md-4 px-3 py-3">
        <h4 class="mb-0 f-21 font-weight-normal text-capitalize">@lang('app.estimate') @lang('app.details')</h4>
    </div>
    <!-- HEADING END -->
    <hr class="m-0 border-top-grey">
    <!-- FORM START -->
    <x-form class="c-inv-form" id="saveInvoiceForm">
        <!-- INVOICE NUMBER, DATE, DUE DATE, FREQUENCY START -->
        <div class="row px-lg-4 px-md-4 px-3 py-3">
            <!-- INVOICE NUMBER START -->
            <div class="col-md-6 col-lg-4">
                <div class="form-group mb-4">
                    <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('modules.estimates.estimatesNumber')</label>
                    <div class="input-group">
                        <div class="input-group-prepend  height-35 ">
                            <span class="input-group-text border-grey f-15 bg-additional-grey px-3 text-dark"
                                id="basic-addon1">{{ $invoiceSetting->estimate_prefix }}{{ $invoiceSetting->estimate_number_separator }}{{ $zero }}</span>
                        </div>
                        <input type="text" name="estimate_number" id="estimate_number"
                            class="form-control height-35 f-15"
                            value="@if (is_null($lastEstimate)) 1 @else{{ $lastEstimate }} @endif"
                            placeholder="0019" aria-label="0019" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <!-- INVOICE NUMBER END -->
            <!-- INVOICE DATE START -->
            <div class="col-md-6 col-lg-4">
                <div class="form-group mb-4">
                    <x-forms.label fieldId="due_date" :fieldLabel="__('modules.estimates.validTill')" fieldRequired="true">
                    </x-forms.label>
                    <div class="input-group">
                        <input type="text" id="valid_till" name="valid_till"
                            class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                            placeholder="@lang('placeholders.date')"
                            value="{{ Carbon\Carbon::today()->addDays(30)->format(company()->date_format) }}">
                    </div>
                </div>
            </div>
            <!-- INVOICE DATE END -->

            <!-- FREQUENCY START -->
            <div class="col-md-6 col-lg-4">
                <div class="form-group c-inv-select mb-4">
                    <x-forms.label fieldId="currency_id" :fieldLabel="__('modules.invoices.currency')">
                    </x-forms.label>

                    <div class="select-others rounded">
                        <select class="form-control select-picker" name="currency_id" id="currency_id">
                            @foreach ($currencies as $currency)
                                <option
                                    @if (isset($estimate)) @if ($currency->id == $estimate->currency_id) selected @endif
                                @else @if ($currency->id == company()->currency_id) selected @endif
                                    @if ($estimateTemplate && $currency->id == $estimateTemplate->currency_id) selected @endif @endif
                                    value="{{ $currency->id }}">
                                    {{ $currency->currency_code . ' (' . $currency->currency_symbol . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!-- FREQUENCY END -->

            <!-- CLIENT START -->
            <input type="hidden" name="template_id" value="{{ $estimateTemplate->id ?? '' }}">
            @if (isset($estimate))
                <input type="hidden" name="estimate_id" value="{{ $estimate->id ?? '' }}">
            @endif
            <div class="col-md-6 col-lg-4">
                @if (isset($client) && !is_null($client))
                    <div class="form-group mb-lg-0 mb-md-0 mb-4">
                        <x-forms.label fieldId="due_date" :fieldLabel="__('app.client')">
                        </x-forms.label>
                        <div class="input-group">
                            <input type="hidden" name="client_id" id="client_id" value="{{ $client->id }}">
                            <input type="text" value="{{ $client->name }}"
                                class="form-control height-35 f-15 readonly-background" readonly>
                        </div>
                    </div>
                @else
                    <x-client-selection-dropdown :clients="$clients" :selected="isset($estimate)
                        ? $estimate->client_id
                        : (request()->has('default_client')
                            ? request()->has('default_client')
                            : null)" />
                @endif
            </div>
            <!-- CLIENT END -->

            <div class="col-md-6 col-lg-4 d-none">
                <div class="form-group c-inv-select mb-4">
                    <x-forms.label fieldId="calculate_tax" :fieldLabel="__('modules.invoices.calculateTax')">
                    </x-forms.label>
                    <div class="select-others height-35 rounded">
                        <select class="form-control select-picker" data-live-search="true" data-size="8"
                            name="calculate_tax" id="calculate_tax">
                            <option value="after_discount" selected>@lang('modules.invoices.afterDiscount')</option>
                            <option value="before_discount">@lang('modules.invoices.beforeDiscount')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-none">
                <div class="form-group c-inv-select">
                    <x-forms.label  fieldId="" :fieldLabel="__('modules.unitType.unitType')">
                    </x-forms.label>
                    <div class="select-others height-35 rounded">
                        <select class="form-control select-picker" name="unit_type_id" id="unit_type_id">
                            @foreach ($unit_types as $unit_type)
                                <option @if ($unit_type->default == 1) selected @endif value="{{ $unit_type->id }}">
                                    {{ ucwords($unit_type->unit_type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group c-inv-select">
                    <x-forms.label fieldId=""
                                :fieldLabel="__('modules.projects.projectCategory')">
                    </x-forms.label>
                    <div class="rounded">
                        <select class="form-control select-picker" name="project_category_id"
                                id="project_category_id" data-live-search="true">
                            <option value="">--</option>
                            @foreach ($projectCategories as $category)
                                <option @if (isset($estimate)) @if($estimate->project_category_id == $category->id) selected @endif @endif value="{{ $category->id }}">
                                    {{ mb_ucwords($category->category_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group c-inv-select">
                    <x-forms.label fieldId=""
                                :fieldLabel="__('modules.projects.projectSubCategory')">
                    </x-forms.label>
                    <div class="rounded">
                        <select class="form-control select-picker" name="project_sub_category_id" id="project_sub_category_id" data-live-search="true">
                                <option value="">@lang('messages.noProjectSubCategoryAdded')</option>
                                @foreach ($projectSubCategories as $subCategory)
                                    <option @if (isset($estimate)) @if($estimate->project_sub_category_id == $subCategory->id) selected @endif @endif value="{{ $subCategory->id }}">
                                        {{ mb_ucwords($subCategory->category_name) }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-12 my-3">
                <div class="form-group">
                    <x-forms.label fieldId="description" :fieldLabel="__('app.description')">
                    </x-forms.label>
                    <div id="description">{!! isset($estimate) ? $estimate->description : ($estimateTemplate ? $estimateTemplate->description : '') !!}</div>
                    <textarea name="description" id="description-text" class="d-none"></textarea>
                </div>
            </div>

        </div>
        <!-- INVOICE NUMBER, DATE, DUE DATE, FREQUENCY END -->

        <x-forms.custom-field :fields="$fields"></x-forms.custom-field>

        <hr class="border-top-grey mb-4">
        <div id="sortable">
            @if (isset($estimate))
            <div class="pl-2 py-0 c-inv-desc">
                <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                    <table width="100%">
                        <tbody>
                            <tr class="text-dark-grey font-weight-bold f-14">
                                <td class="border-0 pr-category">
                                    @lang('modules.productCategory.productCategory')
                                </td>
                                <td class="border-0 pr-subcategory">
                                    @lang('modules.productCategory.productSubCategory')
                                </td>
                                <td class="border-0 inv-desc-mbl btlr pr-name">@lang('app.productName')</td>
                                {{-- @if ($invoiceSetting->hsn_sac_code_show)
                                    <td width="8%" class="border-0" >@lang('app.hsnSac')</td>
                                    <td width="8%" class="border-0" >@lang('app.hsnSac')</td>
                                @endif --}}
                                <td class="border-0 pr-unittype">@lang('modules.invoices.unitType')
                                </td>
                                <td class="border-0 pr-unitprice">
                                    @lang('modules.invoices.unitPrice')
                                </td>
                                <td class="border-0 pr-margin" >@lang('modules.invoices.margin')%
                                </td>
                                <td align="left" class="border-0 pr-tax">VAT
                                </td>
                                <td align="right" class="border-0 bblr-mbl pr-amount">
                                    @lang('modules.invoices.amount')
                                </td>
                                <td class="border-0 bblr-mbl pr-cost" align="right" valign="middle" style="padding:8px; border: 0;">
                                    @lang('modules.invoices.cost')
                                </td>
                                <td class="border-0 bblr-mbl pr-cost-margin" align="right" valign="middle" style="padding:8px; border: 0;">
                                    @lang('modules.invoices.margin')
                                </td>
                                <td class="pr-removeitem" align="right" valign="middle" style="padding:8px; border: 0;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @foreach ($estimate->items as $key => $estimateitem)
                <!-- DESKTOP DESCRIPTION TABLE START -->
                <div class="d-flex pl-2 pb-2 c-inv-desc item-row">

                    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                        <table width="100%">
                            <tbody>
                                {{-- <tr class="text-dark-grey font-weight-bold f-14">
                                    <td width="20%" class="border-0">
                                        @lang('modules.productCategory.productCategory')
                                    </td>
                                    <td width="20%" class="border-0">
                                        @lang('modules.productCategory.productSubCategory')
                                    </td>
                                    <td width="{{ $invoiceSetting->hsn_sac_code_show ? '10%' : '20%' }}"
                                        class="border-0 inv-desc-mbl btlr">@lang('app.productName')</td>
                                    @if ($invoiceSetting->hsn_sac_code_show)
                                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                                    @endif
                                    <td width="10%" class="border-0" align="right" id="type">
                                    </td>
                                    <td width="10%" class="border-0" align="right">
                                        @lang('modules.invoices.unitPrice')
                                    </td>
                                    <td width="10%" class="border-0" align="right">@lang('modules.invoices.discount')%
                                    </td>
                                    <td width="14px" class="border-0" align="right">@lang('modules.invoices.tax')
                                    </td>
                                    <td width="14%" class="border-0 bblr-mbl" align="right">
                                        @lang('modules.invoices.amount')
                                    </td>
                                </tr> --}}
                                <tr>
                                    {{-- <td class="text-dark-grey font-weight-bold f-18 border-0" rowspan="3" align="right" valign="middle">#</td> --}}
                                    <td class="border-bottom-0 btrr-mbl btlr pr-category">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker product_category_id" name="product_category_id[]"
                                                        data-live-search="true">
                                                    <option value="">{{ __('app.select') . ' ' . __('modules.productCategory.productCategory') }}</option>
                                                    @foreach ($serviceCategories as $category)
                                                        <option data-content="{{ $category->category_name }}" value="{{ $category->id }}" @if($estimateitem->product_category_id == $category->id) selected @endif>
                                                            {{ mb_ucwords($category->category_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="product_category_name" value="{{$estimateitem->product_category_name}}"
                                                name="product_category_name[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addServiceCategory" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('modules.productCategory.productCategory') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 btrr-mbl btlr pr-subcategory">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker product_sub_category_id" name="product_sub_category_id[]" data-live-search="true">
                                                        <option value="">{{ __('app.select') . ' ' . __('modules.productCategory.productSubCategory') }}</option>
                                                        @foreach ($serviceSubCategories as $subCategory)
                                                            <option data-content="{{ $subCategory->category_name }}" value="{{ $subCategory->id }}" @if($estimateitem->product_sub_category_id == $subCategory->id) selected @endif>
                                                                {{ mb_ucwords($subCategory->category_name) }}</option>
                                                        @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="product_sub_category_name" value="{{$estimateitem->product_sub_category_name}}"
                                                name="product_sub_category_name[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addServiceSubCategory" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('modules.productCategory.productSubCategory') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 btrr-mbl btlr pr-name">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                            <select class="form-control select-picker item_id add-products" data-live-search="true" data-size="8" name="item_id[]">
                                                <option value="">{{ __('app.select') . ' ' . __('app.product') }}</option>
                                                @foreach ($products as $item)
                                                    <option data-content="{{ $item->name }}" value="{{ $item->id }}"  @if($estimateitem->item_id == $item->id) selected @endif>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden"
                                            class="item_name" value="{{$estimateitem->item_name}}"
                                            name="item_name[]">
                                            {{-- <input type="hidden" name="item_id[]" value="{{ $estimateitem->id }}"> --}}
                                        </div>
                                        <div class="input-group-append">
                                            <a href="{{ route('products.create') }}?page=estimate" data-redirect-url="{{ url()->full() }}"
                                                class="btn btn-outline-secondary border-grey openRightModal" data-toggle="tooltip"
                                                data-original-title="{{ __('app.add') . ' ' . __('modules.dashboard.newproduct') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                        </div>
                                    </div>
                                    </td>
                                    <td class="border-bottom-0 d-block d-lg-none d-md-none">
                                        <textarea class="form-control f-14 border-0 w-100 mobile-description" name="item_summary[]"
                                            placeholder="@lang('placeholders.invoices.description')"></textarea>
                                    </td>
                                    {{-- @if ($invoiceSetting->hsn_sac_code_show)
                                        <td width="{{ $invoiceSetting->hsn_sac_code_show ? '10%' : '20%' }}" class="border-bottom-0">
                                            <input type="text" min="1"
                                                class="f-14 border-0 w-100 text-right hsn_sac_code form-control"
                                                value="" name="hsn_sac_code[]">
                                        </td>
                                    @endif --}}
                                    <td align="middle" class="border-bottom-0 pr-unittype">
                                        {{-- <div class="d-flex align-item-center"> --}}
                                        <span class="product_unit_name_html">{{$estimateitem->unit_name}}</span>
                                        <input type="number" min="1"
                                            class="form-control f-14 border-0 w-100 text-right quantity unit_type_quantity @if($estimateitem->unit_name == "Fixed Price" || $estimateitem->unit_name == "Hrs") d-none @endif"
                                            value="{{$estimateitem->quantity}}" name="quantity[]">
                                            <input type="hidden"
                                            class="product_id" value="{{$estimateitem->item_id}}"
                                            name="product_id[]">
                                            <input type="hidden"
                                            class="product_unit_id" value="{{$estimateitem->unit_id}}"
                                            name="product_unit_id[]">
                                            <input type="hidden"
                                            class="product_unit_name" value="{{$estimateitem->unit_name}}"
                                            name="product_unit_name[]">
                                        {{-- </div> --}}
                                    </td>
                                    <td class="border-bottom-0 pr-unitprice">
                                        <input type="number" min="1"
                                            class="f-14 border-0 w-100 text-right cost_per_item form-control"
                                            placeholder="0.00" name="cost_per_item[]" value="{{$estimateitem->item_price}}">
                                    </td>
                                    <td  class="border-bottom-0 pr-margin">
                                        <input type="number" min="0"
                                        class="f-14 border-0 w-100 text-right service_margin_percent form-control" name="service_margin_percent[]" value="{{$estimateitem->service_margin_percent}}"
                                        placeholder="0">
                                    </td>
                                    <td rowspan="2" align="middle" valign="top" class="pr-tax">
                                        <div class="select-others height-35 rounded border-0 tax-select">
                                            <select id="multiselect{{ $key }}" name="taxes[{{ $key }}][]"
                                                multiple="multiple" class="select-picker type customSequence border-0"
                                                data-size="3">
                                                @foreach ($taxes as $tax)
                                                    <option data-rate="{{ $tax->rate_percent }}"
                                                        @if (isset($item->taxes) && array_search($tax->id, json_decode($item->taxes)) !== false) selected @endif value="{{ $tax->id }}">
                                                        {{ $tax->rate_percent }}%</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    {{-- <td valign="top" align="left" class="c-inv-sub-padding">
                                        @foreach ($taxes as $tax)
                                            <div class="form-control f-14">{{ strtoupper($tax->tax_name) }}:{{ $tax->rate_percent }}%</div>
                                        <input data-rate="{{ $tax->rate_percent }}" type="hidden" value="{{ $tax->id }}" min="0" name="taxes[]" class="type" placeholder="0">
                                        @endforeach
                                    </td> --}}

                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-amount">
                                        <span class="amount-html">
                                            {{$estimateitem->unit_name == "Hrs" ? "" : $estimateitem->amount}}
                                        </span>
                                        <input type="hidden" class="amount" name="amount[]" value="{{$estimateitem->amount}}">
                                    </td>
                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost">
                                        <span class="cost-html">{{$estimateitem->unit_name == "Hrs" ? "" : $estimateitem->cost}}</span>
                                        <input type="hidden" class="cost" name="cost[]" value="{{$estimateitem->cost}}">
                                    </td>
                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost-margin">
                                        <span class="margin-html">{{$estimateitem->unit_name == "Hrs" ? "" : $estimateitem->margin}}</span>
                                        <input type="hidden" class="margin" name="margin[]" value="{{$estimateitem->margin}}">
                                    </td>
                                    <td rowspan="2" align="right" valign="middle" style="padding:8px; border: 0;">
                                        <a href="javascript:;"
                                        class="remove-item"><i
                                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                                    </td>
                                </tr>
                                <tr class="d-none d-md-table-row d-lg-table-row">
                                    <td colspan="{{ $invoiceSetting->hsn_sac_code_show ? '7' : '6' }}"
                                        class="dash-border-top bblr">
                                        <textarea class="f-14 border-0 w-100 desktop-description form-control" name="item_summary[]"
                                            placeholder="@lang('placeholders.invoices.description')"></textarea>
                                    </td>
                                </tr>
                                <tr class="dash-border-top @if($estimateitem->expense_item_id !='')d-none @endif bblr">
                                    <td colspan="10">
                                        <a href="javascript:void(0);" style="font-size:14px !important;"
                                        class="add_expense_btn f-15 f-w-500" data-toggle="tooltip"
                                        data-original-title="{{ __('app.addExpense')}}"><i class="icons icon-plus font-weight-bold mr-1"></i>@lang('app.addExpense')</a>
                                    </td>
                                </tr>
                                <tr class="add_expense_row @if($estimateitem->expense_item_id =='')d-none @endif dash-border-top bblr">
                                    <td align="middle" class="text-dark-grey font-weight-bold f-14">{{ __('app.expense')}}
                                         {{-- <span style='font-size:26px; font-weight:1 !important;'>&#8680;</span> --}}
                                        </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker expense_category_id" name="expense_category_id[]"
                                                    data-live-search="true">
                                                    <option value="">@lang('app.selectExpenseCategory')</option>
                                                    @foreach ($expenseCategories as $category)
                                                        <option value="{{ $category->id }}" @if($estimateitem->expense_category_id == $category->id) selected @endif>{{ mb_ucwords($category->category_name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" class="user_id" id="user_id{{$key}}" value="{{ user()->id }}">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addExpenseCategory" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('app.expenseCategory') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker add_expense" required name="expense_id[]" id="add_expense"
                                                data-live-search="true">
                                                <option value="">@lang('app.selectExpense')</option>
                                                @foreach ($expenses as $expense)
                                                    <option value="{{ $expense->id }}" @if($estimateitem->expense_item_id == $expense->id) selected @endif>{{ mb_ucwords($expense->item_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-red f-14 duplicate-error d-none" id="duplicate-error">Duplicate Expense</div>
                                            <input type="hidden"
                                            class="expense_item_id" value="{{$estimateitem->expense_item_id}}"
                                            name="expense_item_id[]">
                                            <input type="hidden"
                                            class="expense_item_name" value="{{$estimateitem->expense_item_name}}"
                                            name="expense_item_name[]">
                                        </div>
                                            <div class="input-group-append">
                                                <a href="{{ route('expenses.create') }}?page=estimate"          data-redirect-url="{{ url()->full() }}"
                                                class="btn btn-outline-secondary border-grey openRightModal" data-toggle="tooltip"
                                                data-original-title="{{ __('app.addNewExpense') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" min="1"
                                            class="f-14 border-0 w-100 text-right expense_price form-control"
                                            placeholder="0.00" name="expense_price[]" value="{{$estimateitem->expense_price}}">
                                    </td>

                                    <td>
                                        <input type="number" min="1"
                                            class="f-14 border-0 w-100 text-right expense_margin_percent form-control"
                                            placeholder="0" name="expense_margin_percent[]" value="{{$estimateitem->expense_margin_percent}}">
                                    </td>

                                    <td valign="top" align="left" class="c-inv-sub-padding">
                                        @foreach ($taxes as $tax)
                                            <div class="form-control f-14">{{ $tax->rate_percent }}%</div>
                                        <input data-expense-tax-rate="{{ $tax->rate_percent }}" type="hidden" value="{{$estimateitem->expnese_tax}}" min="0" name="expnese_tax[]" class="expnese_tax" placeholder="0">
                                        @endforeach
                                    </td>

                                    <td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">
                                        <span class="expense-amount-html">{{$estimateitem->expense_amount}}</span>
                                        <input type="hidden"
                                            class="expense-amount" value="{{$estimateitem->expense_amount}}"
                                            name="expense_amount[]">
                                        <input type="hidden"
                                            class="expense-tax-amount" value="{{$estimateitem->expense_tax_amount}}"
                                            name="expense_tax_amount[]">
                                    </td>
                                    <td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">
                                        <span class="expense-cost-html">{{$estimateitem->expense_cost}}</span>
                                        <input type="hidden"
                                        name="expense_cost[]" value="{{$estimateitem->expense_cost}}"
                                        class="expense-cost"
                                        >
                                    </td>
                                    <td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">
                                        <span class="expense-margin-html">{{$estimateitem->expense_margin}}</span>
                                        <input type="hidden"
                                        name="expense_margin[]" value="{{$estimateitem->expense_margin}}"
                                        class="expense-margin"
                                        >
                                    </td>
                                    <td align="right" valign="middle" style="padding:8px; border: 0;">
                                        <a href="javascript:;"
                                        class="remove-expense remove_expense_btn"><i
                                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- <a href="javascript:;"
                            class="d-flex align-items-center justify-content-center ml-3 remove-item"><i
                                class="fa fa-times-circle f-20 text-lightest"></i></a> --}}
                    </div>
                </div>
                <!-- DESKTOP DESCRIPTION TABLE END -->
            @endforeach

            @elseif (isset($estimateTemplateItem) && !is_null($estimateTemplateItem))

                @foreach ($estimateTemplateItem as $item)
                    <!-- DESKTOP DESCRIPTION TABLE START -->
                    <div class="d-flex px-4 py-3 c-inv-desc item-row">
                        <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                            <table width="100%">
                                <tbody>
                                    <tr class="text-dark-grey font-weight-bold f-14">
                                        <td width="40%" class="border-0 inv-desc-mbl btlr">@lang('app.description')</td>
                                        @if ($invoiceSetting->hsn_sac_code_show)
                                            <td width="10%" class="border-0" align="right">@lang('app.hsnSac')
                                            </td>
                                        @endif
                                        <td width="10%" class="border-0" align="right" id="type">

                                        </td>
                                        <td width="10%" class="border-0" align="right">
                                            @lang('modules.invoices.unitPrice')
                                        </td>
                                        <td width="13%" class="border-0" align="right">@lang('modules.invoices.tax')
                                        </td>
                                        <td width="17%" class="border-0 bblr-mbl" align="right">
                                            @lang('modules.invoices.amount')</td>
                                    </tr>
                                    <tr>
                                        <td class="border-bottom-0 btrr-mbl btlr">
                                            <input type="text" class="f-14 border-0 w-100 item_name form-control"
                                                name="item_name[]" placeholder="@lang('modules.expenses.itemName')"
                                                value="{{ $item->item_name }}">
                                        </td>
                                        @if ($invoiceSetting->hsn_sac_code_show)
                                            <td class="border-bottom-0">
                                                <input type="text"
                                                    class="f-14 border-0 w-100 text-right hsn_sac_code form-control"
                                                    value="{{ $item->hsn_sac_code }}" name="hsn_sac_code[]">
                                            </td>
                                        @endif
                                        <td class="border-bottom-0">
                                            <input type="number" min="1"
                                                class="f-14 border-0 w-100 text-right quantity form-control"
                                                name="quantity[]" value="{{ $item->quantity }}">
                                        </td>
                                        <td class="border-bottom-0">
                                            <input type="number" min="1"
                                                class="f-14 border-0 w-100 text-right cost_per_item form-control"
                                                placeholder="0.00" name="cost_per_item[]"
                                                value="{{ $item->unit_price }}">
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="select-others height-35 rounded border-0">
                                                <select id="multiselect" name="taxes[0][]" multiple="multiple"
                                                    class="select-picker type customSequence border-0" data-size="3">
                                                    @foreach ($taxes as $tax)
                                                        <option data-rate="{{ $tax->rate_percent }}"
                                                            @if (isset($item->taxes) && array_search($tax->id, json_decode($item->taxes)) !== false) selected @endif
                                                            value="{{ $tax->id }}">
                                                            {{ strtoupper($tax->tax_name) }}
                                                            {{ $tax->rate_percent }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td rowspan="2" align="right" valign="top"
                                            class="bg-amt-grey btrr-bbrr">
                                            <span
                                                class="amount-html">{{ number_format((float) $item->amount, 2, '.', '') }}</span>
                                            <input type="hidden" class="amount" name="amount[]"
                                                value="{{ $item->amount }}">
                                        </td>
                                    </tr>
                                    <tr class="d-none d-md-table-row d-lg-table-row">
                                        <td colspan="{{ $invoiceSetting->hsn_sac_code_show ? '4' : '3' }}"
                                            class="dash-border-top bblr">
                                            <textarea class="f-14 border-0 w-100 desktop-description form-control" name="item_summary[]"
                                                placeholder="@lang('placeholders.invoices.description')">{{ $item->item_summary }}</textarea>
                                        </td>
                                        <td class="border-left-0">
                                            <input type="file" class="dropify" name="invoice_item_image[]"
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-messages-default="test" data-height="70"
                                                data-id="{{ $item->id }}" id="{{ $item->id }}"
                                                data-default-file="{{ $item->estimateTemplateItemImage ? $item->estimateTemplateItemImage->file_url : '' }}"
                                                @if ($item->estimateTemplateItemImage && $item->estimateTemplateItemImage->external_link) data-show-remove="false" @endif />
                                            <input type="hidden" name="invoice_item_image_url[]"
                                                value="{{ $item->proposalTemplateItemImage ? $item->proposalTemplateItemImage->external_link : '' }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="javascript:;"
                                class="d-flex align-items-center justify-content-center ml-3 remove-item"><i
                                    class="fa fa-times-circle f-20 text-lightest"></i></a>
                        </div>
                    </div>
                    <!-- DESKTOP DESCRIPTION TABLE END -->
                @endforeach
            @else
                <!-- DESKTOP DESCRIPTION TABLE START -->
                <div class="pl-2 py-0 c-inv-desc">

                    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                        <table width="100%">
                            <tbody>
                                <tr class="text-dark-grey font-weight-bold f-14">
                                    <td class="border-0 pr-category">
                                        @lang('modules.productCategory.productCategory')
                                    </td>
                                    <td class="border-0 pr-subcategory">
                                        @lang('modules.productCategory.productSubCategory')
                                    </td>
                                    <td class="border-0 inv-desc-mbl btlr pr-name">@lang('app.productName')</td>
                                    {{-- @if ($invoiceSetting->hsn_sac_code_show)
                                        <td width="8%" class="border-0" >@lang('app.hsnSac')</td>
                                        <td width="8%" class="border-0" >@lang('app.hsnSac')</td>
                                    @endif --}}
                                    <td class="border-0 pr-unittype">@lang('modules.invoices.unitType')
                                    </td>
                                    <td class="border-0 pr-unitprice">
                                        @lang('modules.invoices.unitPrice')
                                    </td>
                                    <td class="border-0 pr-margin" >@lang('modules.invoices.margin')%
                                    </td>
                                    <td align="middle" class="border-0 pr-tax" >VAT
                                    </td>
                                    <td align="right" class="border-0 bblr-mbl pr-amount">
                                        @lang('modules.invoices.amount')
                                    </td>
                                    <td class="border-0 bblr-mbl pr-cost" align="right" valign="middle" style="padding:8px; border: 0;">
                                        @lang('modules.invoices.cost')
                                    </td>
                                    <td class="border-0 bblr-mbl pr-cost-margin" align="right" valign="middle" style="padding:8px; border: 0;">
                                        @lang('modules.invoices.margin')
                                    </td>
                                    <td class="pr-removeitem" align="right" valign="middle" style="padding:8px; border: 0;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex pl-2 pb-2 c-inv-desc item-row">
                    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                        <table width="100%">
                            <tbody>
                                {{-- <tr class="text-dark-grey font-weight-bold f-14">
                                    <td width="20%" class="border-0">
                                        @lang('modules.productCategory.productCategory')
                                    </td>
                                    <td width="20%" class="border-0">
                                        @lang('modules.productCategory.productSubCategory')
                                    </td>
                                    <td width="{{ $invoiceSetting->hsn_sac_code_show ? '10%' : '20%' }}"
                                        class="border-0 inv-desc-mbl btlr">@lang('app.productName')</td>
                                    @if ($invoiceSetting->hsn_sac_code_show)
                                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                                    @endif
                                    <td width="10%" class="border-0" align="right" id="type">
                                    </td>
                                    <td width="10%" class="border-0" align="right">
                                        @lang('modules.invoices.unitPrice')
                                    </td>
                                    <td width="10%" class="border-0" align="right">@lang('modules.invoices.discount')%
                                    </td>
                                    <td width="14px" class="border-0" align="right">@lang('modules.invoices.tax')
                                    </td>
                                    <td width="14%" class="border-0 bblr-mbl" align="right">
                                        @lang('modules.invoices.amount')
                                    </td>
                                </tr> --}}
                                <tr>
                                    {{-- <td class="text-dark-grey font-weight-bold f-18 border-0" rowspan="3" align="right" valign="middle">#</td> --}}
                                    <td class="border-bottom-0 btrr-mbl btlr pr-category">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker product_category_id" name="product_category_id[]"
                                                        id="product_category_id" data-live-search="true">
                                                    <option value="">{{ __('app.select') . ' ' . __('modules.productCategory.productCategory') }}</option>
                                                    @foreach ($serviceCategories as $category)
                                                        <option data-content="{{ $category->category_name }}" value="{{ $category->id }}">
                                                            {{ mb_ucwords($category->category_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="product_category_name"
                                                name="product_category_name[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addServiceCategory" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('modules.productCategory.productCategory') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 btrr-mbl btlr pr-subcategory">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker product_sub_category_id" name="product_sub_category_id[]" id="product_sub_category_id" data-live-search="true">
                                                        <option value="">{{ __('app.select') . ' ' . __('modules.productCategory.productSubCategory') }}</option>
                                                        @foreach ($serviceSubCategories as $subCategory)
                                                            <option data-content="{{ $subCategory->category_name }}" value="{{ $subCategory->id }}">
                                                                {{ mb_ucwords($subCategory->category_name) }}</option>
                                                        @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="product_sub_category_name"
                                                name="product_sub_category_name[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;" id="addServiceSubCategory"
                                                    class="btn btn-outline-secondary border-grey" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('modules.productCategory.productSubCategory') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 btrr-mbl btlr pr-name">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                            <select class="form-control select-picker item_id add-products" data-live-search="true" data-size="8" id="add-products" name="item_id[]">
                                                <option value="">{{ __('app.select') . ' ' . __('app.product') }}</option>
                                                @foreach ($products as $item)
                                                    <option data-content="{{ $item->name }}" value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden"
                                            class="item_name"
                                            name="item_name[]">
                                        </div>
                                        <div class="input-group-append">
                                            <a href="{{ route('products.create') }}?page=estimate" data-redirect-url="{{ url()->full() }}"
                                                class="btn btn-outline-secondary border-grey openRightModal" data-toggle="tooltip"
                                                data-original-title="{{ __('app.add') . ' ' . __('modules.dashboard.newproduct') }}"><i
                                                    class="icons icon-plus font-weight-bold"></i></a>
                                        </div>
                                    </div>
                                    </td>
                                    <td class="border-bottom-0 d-block d-lg-none d-md-none">
                                        <textarea class="form-control f-14 border-0 w-100 mobile-description" name="item_summary[]"
                                            placeholder="@lang('placeholders.invoices.description')"></textarea>
                                    </td>
                                    @if ($invoiceSetting->hsn_sac_code_show)
                                        <td width="{{ $invoiceSetting->hsn_sac_code_show ? '10%' : '20%' }}" class="border-bottom-0">
                                            <input type="text" min="1"
                                                class="f-14 border-0 w-100 text-right hsn_sac_code form-control"
                                                value="" name="hsn_sac_code[]">
                                        </td>
                                    @endif
                                    <td align="middle" class="border-bottom-0 pr-unittype">
                                        {{-- <div class="d-flex align-item-center"> --}}
                                        <span class="product_unit_name_html"></span>
                                        <input type="number" min="1"
                                            class="form-control f-14 border-0 w-100 text-right quantity unit_type_quantity"
                                            value="1" name="quantity[]">

                                            <input type="hidden"
                                            class="product_id"
                                            name="product_id[]">
                                            <input type="hidden"
                                            class="product_unit_id"
                                            name="product_unit_id[]">
                                            <input type="hidden"
                                            class="product_unit_name"
                                            name="product_unit_name[]">
                                        {{-- </div> --}}
                                    </td>
                                    <td class="border-bottom-0 pr-unitprice">
                                        <input type="number" min="1"
                                            class="f-14 border-0 w-100 text-right cost_per_item form-control"
                                            placeholder="0.00" name="cost_per_item[]">
                                    </td>
                                    <td class="border-bottom-0 pr-margin">
                                        <input type="number" min="0"
                                        class="f-14 border-0 w-100 text-right service_margin_percent form-control" name="service_margin_percent[]"
                                        placeholder="0">
                                    </td>
                                    <td class="pr-tax" rowspan="2" valign="top">
                                        <div class="select-others height-35 rounded border-0 tax-select">
                                            <select id="multiselect" name="taxes[0][]"
                                                class="select-picker type customSequence border-0" data-size="3">
                                                @foreach ($taxes as $tax)
                                                    <option data-rate="{{ $tax->rate_percent }}"
                                                        value="{{ $tax->id }}" selected>
                                                        {{ $tax->rate_percent }}%</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    {{-- <td valign="top" align="left" class="c-inv-sub-padding">
                                        @foreach ($taxes as $tax)
                                            <div class="form-control f-14">{{ strtoupper($tax->tax_name) }}:{{ $tax->rate_percent }}%</div>
                                        <input data-rate="{{ $tax->rate_percent }}" type="hidden" value="{{ $tax->id }}" min="0" name="taxes[]" class="type" placeholder="0">
                                        @endforeach
                                    </td> --}}

                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-amount">
                                        <span class="amount-html">0.00</span>
                                        <input type="hidden" class="amount" name="amount[]" value="0">
                                    </td>
                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost">
                                        <span class="cost-html">0.00</span>
                                        <input type="hidden" class="cost" name="cost[]" value="0">
                                    </td>
                                    <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost-margin">
                                        <span class="margin-html">0.00</span>
                                        <input type="hidden" class="margin" name="margin[]" value="0">
                                    </td>
                                    <td class="pr-removeitem" rowspan="2" align="right" valign="middle" style="padding:8px; border: 0;">
                                        <a href="javascript:;"
                                        class="remove-item"><i
                                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                                    </td>
                                </tr>
                                <tr class="d-none d-md-table-row d-lg-table-row">
                                    <td colspan="{{ $invoiceSetting->hsn_sac_code_show ? '7' : '6' }}"
                                        class="dash-border-top bblr">
                                        <textarea class="border-0 w-100 desktop-description form-control" name="item_summary[]"
                                            placeholder="@lang('placeholders.invoices.description')"></textarea>
                                    </td>
                                </tr>
{{--                                <tr class="add_expense_row dash-border-top bblr">--}}
{{--                                    <td align="middle" class="text-dark-grey font-weight-bold f-14">{{ __('app.expense')}}--}}
{{--                                        --}}{{-- <span style='font-size:26px; font-weight:1 !important;'>&#8680;</span> --}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <div class="dropdown bootstrap-select form-control select-picker">--}}
{{--                                                <select class="form-control select-picker expense_category_id" name="expense_category_id0[]" id="expense_category_id"--}}
{{--                                                        data-live-search="true">--}}
{{--                                                    <option value="">@lang('app.selectExpenseCategory')</option>--}}
{{--                                                    @foreach ($expenseCategories as $category)--}}
{{--                                                        <option value="{{ $category->id }}">{{ mb_ucwords($category->category_name) }}--}}
{{--                                                        </option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                                <input type="hidden" class="user_id" id="user_id" value="{{ user()->id }}">--}}
{{--                                            </div>--}}
{{--                                            <div class="input-group-append">--}}
{{--                                                <a href="javascript:;" id="addExpenseCategory"--}}
{{--                                                   class="btn btn-outline-secondary border-grey" data-toggle="tooltip"--}}
{{--                                                   data-original-title="{{ __('app.add') . ' ' . __('app.expenseCategory') }}"><i--}}
{{--                                                        class="icons icon-plus font-weight-bold"></i>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                    <td colspan="2">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <div class="dropdown bootstrap-select form-control select-picker">--}}
{{--                                                <select class="form-control select-picker add_expense" required name="expense_id0[]" id="add_expense"--}}
{{--                                                        data-live-search="true">--}}
{{--                                                    <option value="">@lang('app.selectExpense')</option>--}}
{{--                                                    @foreach ($expenses as $expense)--}}
{{--                                                        <option value="{{ $expense->id }}">{{ mb_ucwords($expense->item_name) }}--}}
{{--                                                        </option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                                <div class="text-red f-14 duplicate-error d-none" id="duplicate-error">Duplicate Expense</div>--}}
{{--                                                <input type="hidden"--}}
{{--                                                       class="expense_item_id"--}}
{{--                                                       name="expense_item_id0[]">--}}
{{--                                                <input type="hidden"--}}
{{--                                                       class="expense_item_name"--}}
{{--                                                       name="expense_item_name0[]">--}}
{{--                                            </div>--}}
{{--                                            <div class="input-group-append">--}}
{{--                                                <a href="{{ route('expenses.create') }}?page=estimate" data-redirect-url="{{ url()->full() }}"--}}
{{--                                                   class="btn btn-outline-secondary border-grey openRightModal" data-toggle="tooltip"--}}
{{--                                                   data-original-title="{{ __('app.addNewExpense') }}"><i--}}
{{--                                                        class="icons icon-plus font-weight-bold"></i></a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" min="1"--}}
{{--                                               class="f-14 border-0 w-100 text-right expense_price form-control"--}}
{{--                                               placeholder="0.00" name="expense_price0[]" value="">--}}
{{--                                    </td>--}}

{{--                                    <td>--}}
{{--                                        <input type="number" min="1"--}}
{{--                                               class="f-14 border-0 w-100 text-right expense_margin_percent form-control"--}}
{{--                                               placeholder="0" name="expense_margin_percent0[]"  value="">--}}
{{--                                    </td>--}}

{{--                                    <td valign="top" align="left" class="c-inv-sub-padding">--}}
{{--                                        @foreach ($taxes as $tax)--}}
{{--                                            <div class="form-control f-14">{{ $tax->rate_percent }}%</div>--}}
{{--                                            <input data-expense-tax-rate="{{ $tax->rate_percent }}" type="hidden" value="{{ $tax->id }}" min="0" name="expnese_tax0[]" class="expnese_tax" placeholder="0">--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}

{{--                                    <td align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-amount">--}}
{{--                                        <span class="expense-amount-html">0.00</span>--}}
{{--                                        <input type="hidden"--}}
{{--                                               class="expense-amount"--}}
{{--                                               name="expense_amount0[]">--}}
{{--                                        <input type="hidden"--}}
{{--                                               class="expense-tax-amount"--}}
{{--                                               name="expense_tax_amount0[]">--}}
{{--                                    </td>--}}
{{--                                    <td align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost">--}}
{{--                                        <span class="expense-cost-html">0.00</span>--}}
{{--                                        <input type="hidden"--}}
{{--                                               name="expense_cost0[]"--}}
{{--                                               class="expense-cost"--}}
{{--                                        >--}}
{{--                                    </td>--}}
{{--                                    <td align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost-margin">--}}
{{--                                        <span class="expense-margin-html">0.00</span>--}}
{{--                                        <input type="hidden"--}}
{{--                                               name="expense_margin0[]"--}}
{{--                                               class="expense-margin"--}}
{{--                                        >--}}
{{--                                    </td>--}}
{{--                                    <td align="right" valign="middle" style="padding:8px; border: 0;">--}}
{{--                                        <a href="javascript:;"--}}
{{--                                           class="remove-expense remove_expense_btn"><i--}}
{{--                                                class="fa fa-times-circle f-20 text-lightest"></i></a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

                                <tr class="dash-border-top bblr">
                                    <td colspan="10">
                                        <a href="javascript:void(0);" style="font-size:14px !important;"
                                           class="add_expense_btn f-15 f-w-500" data-toggle="tooltip"
                                           data-original-title="{{ __('app.addExpense')}}"><i class="icons icon-plus font-weight-bold mr-1"></i>@lang('app.addExpense')</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- DESKTOP DESCRIPTION TABLE END -->
            @endif

        </div>
        <!--  ADD ITEM START-->
        <div class="row px-lg-4 px-md-4 px-3 pb-3 pt-0 mb-3  mt-2 hide-buttons">
            <div class="col-md-12">
                <a class="f-15 f-w-500" href="javascript:;" id="add-item"><i
                        class="icons icon-plus font-weight-bold mr-1"></i>@lang('modules.invoices.addItem')</a>
            </div>
        </div>
        <!--  ADD ITEM END-->

        <hr class="m-0 border-top-grey">

        <!-- TOTAL, DISCOUNT START -->
        <div class="d-flex px-lg-4 px-md-4 px-3 pb-3 c-inv-total">
            <table width="100%" class="text-right f-14 text-capitalize">
                <tbody>
                    <tr>
                        <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                        <td width="50%" class="p-0 border-0 c-inv-total-right">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="border-top-0 text-dark-grey">
                                            @lang('modules.invoices.subTotal')</td>
                                        <td width="30%" class="border-top-0 sub-total">0.00</td>
                                        <input type="hidden" class="sub-total-field" name="sub_total"
                                            value="0">
                                    </tr>
                                    <tr class="d-none">
                                        <td width="20%" class="text-dark-grey">@lang('modules.invoices.margin')
                                        </td>
                                        <td width="40%" style="padding: 5px;">
                                            <table width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="70%" class="c-inv-sub-padding total_margin_percent_html">0.00
                                                            <input type="hidden" min="0"
                                                                name="discount_value"
                                                                class="discount_value"
                                                                placeholder="0">
                                                                </td>
                                                        <td width="30%" align="left" class="c-inv-sub-padding">
                                                            <div
                                                                class="select-others select-tax rounded border-0">
                                                                <select class="form-control select-picker"
                                                                    id="discount_type" name="discount_type">
                                                                    <option selected value="percent">%</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <input type="hidden" min="0"
                                            name="total_margin_amount"
                                            class="total_margin_amount"
                                            placeholder="0">
                                            {{-- <span id="discount_amount">0.00</span> --}}
                                            <span id="total_margin_amount_html">0.00</span>
                                            <input type="hidden" min="0"
                                            name="total_margin_percent"
                                            class="total_margin_percent"
                                            placeholder="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        {{-- <td>@lang('modules.invoices.tax')</td> --}}
                                        <td>VAT</td>
                                        <td colspan="2" class="p-0 border-0">
                                            <table width="100%" id="invoice-taxes">
                                                <tr>
                                                    <td><span class="tax-percent">0.00</span></td>
                                                </tr>
                                            </table>
                                            <input type="hidden" min="0"
                                                name="total_tax_amount"
                                                class="total_tax_amount"
                                                placeholder="0">
                                        </td>

                                    </tr>
                                    <tr class="bg-amt-grey f-16 f-w-500">
                                        <td colspan="2">@lang('modules.invoices.total')</td>
                                        <td><span class="total">0.00</span></td>
                                        <input type="hidden" class="total-field" name="total" value="0">
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- TOTAL, DISCOUNT END -->

        @if (in_array('admin', user_roles()))
        <hr class="m-0 border-top-grey pt-4">

        <div class="px-4 mt-5 mb-4 py-0 c-inv-desc">
            <div class=" w-100 d-lg-flex d-md-flex d-block">
                <table width="100%" style="border: 5px solid #000;">
                    <tbody>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="10%" class="" >
                                @lang('app.totalValueService')
                            </td>
                            <td width="10%" class="">
                                @lang('app.totalHours')
                            </td>
                            <td width="10%" class="border-0">
                                @lang('app.totalEstimate')
                            </td>
                            <td width="10%" style="border-left: 5px solid #000 !important;" class="" > @lang('app.totalEspense')</td>
                            <td width="10%" class="" > @lang('app.totalCost')</td>

                            <td width="10%" class="">
                                @lang('app.totalMargin')
                            </td>

                            <td width="10%" class="">
                                @lang('app.totalMargin')%
                            </td>
                        </tr>
                        <tr>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-service-html">0.00</span>
                                <input type="hidden" min="0"
                                name="total_service"
                                class="total_service"
                                placeholder="0">
                            </td>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-hours-html">0.00</span>
                                <input type="hidden" min="0"
                                name="total_hours"
                                class="total_hours"
                                placeholder="0">
                            </td>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-estimate-html">0.00</span>
                            </td>
                            <td width="10%" style="border-left: 5px solid #000;" valign="middle" class=" ">
                                <span class="total-expense-html">0.00</span>
                                <input type="hidden" min="0"
                                name="total_expense"
                                class="total_expense"
                                placeholder="0">
                            </td>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-cost-html">0.00</span>
                                <input type="hidden" min="0"
                                name="total_cost"
                                class="total_cost"
                                placeholder="0">
                            </td>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-margin-html">0.00</span>
                            </td>
                            <td width="10%" valign="middle" class=" ">
                                <span class="total-margin-percent-html">0.00</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <hr class="m-0 border-top-grey pt-4">

        <div class="row px-lg-4 px-md-4 px-3 py-3">
            <!-- INVOICE START DATE START -->
            <div class="col-md-6 col-lg-6">
                <div class="form-group mb-4">
                    <x-forms.label fieldId="due_date" :fieldLabel="__('placeholders.startDate')" fieldRequired="true">
                    </x-forms.label>
                    <div class="input-group">
                        <input type="text" id="start_date" required name="start_date"
                            class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                            placeholder="@lang('placeholders.startDate')"
                            value="{{ Carbon\Carbon::today()->addDays(30)->format(company()->date_format) }}">
                    </div>
                </div>
            </div>
            <!-- INVOICE START DATE END -->

            <!-- INVOICE END DATE START -->
            <div class="col-md-6 col-lg-6">
                <div class="form-group mb-4">
                    <x-forms.label fieldId="due_date" :fieldLabel="__('placeholders.endDate')" fieldRequired="true">
                    </x-forms.label>
                    <div class="input-group">
                        <input type="text" id="end_date" required name="end_date"
                            class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                            placeholder="@lang('placeholders.endDate')"
                            value="{{ Carbon\Carbon::today()->addDays(30)->format(company()->date_format) }}">
                    </div>
                </div>
            </div>
            <!-- INVOICE END DATE END -->

            <div class="col-md-6 col-lg-6 d-none">
                <div class="form-group c-inv-select">
                    <x-forms.label  fieldId="" :fieldLabel="__('modules.payments.paymentMethod')">
                    </x-forms.label>
                    <div class="select-others height-35 rounded">
                        <select class="form-control select-picker" required name="payment_method" id="payment_method">
                            <option value="">@lang('modules.payments.selectPaymentMethod')</option>
                            <option value="Stripe">Stripe</option>
                            <option value="Offline Payment">Offline Payment</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="form-group c-inv-select">
                    <x-forms.label fieldId=""
                                :fieldLabel="__('modules.payments.paymentType')">
                    </x-forms.label>
                    <div class="rounded">
                        <select class="form-control select-picker" required name="payment_type"
                                id="payment_type" data-live-search="true">
                                <option value="">@lang('modules.payments.selectPaymentType')</option>
                                <option value="Debit cards">Debit cards</option>
                                <option value="Credit cards">Credit cards</option>
                                <option value="Checks">Checks</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTE AND TERMS AND CONDITIONS START -->
        <div class="d-flex flex-wrap px-lg-4 px-md-4 px-3 py-3">
            <div class="col-md-6 col-sm-12 c-inv-note-terms p-0 mb-lg-0 mb-md-0 mb-3">
                <x-forms.label fieldId="" class="text-capitalize" :fieldLabel="__('modules.invoices.note')">
                </x-forms.label>
                <textarea class="form-control" name="note" id="note" rows="4" placeholder="@lang('placeholders.invoices.note')"></textarea>
            </div>
            <div class="col-md-6 col-sm-12 p-0 c-inv-note-terms">
               <a href="#" style="cursor: pointer" data-toggle="modal" data-target="#exampleModalLong">
                   {{__('modules.invoiceSettings.invoiceTerms')}}
                   </a>
            </div>
        </div>
        <!-- NOTE AND TERMS AND CONDITIONS END -->

        <!-- CANCEL SAVE SEND START -->
        <div class="border-top-grey justify-content-start px-4 py-3 c-inv-btns hide-buttons">
            <div class="d-flex">
                <x-forms.button-primary data-type="save" class="save-form mr-3" icon="check">@lang('app.save')
                </x-forms.button-primary>
            </div>
                {{-- <div class="inv-action dropup mr-3">
                    <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        @lang('app.save')
                        <span><i class="fa fa-chevron-down f-15 text-white"></i></span>
                    </button>
                    <!-- DROPDOWN - INFORMATION -->
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuBtn" tabindex="0">
                        <li>
                            <a class="dropdown-item f-14 text-dark save-form" href="javascript:;" data-type="save">
                                <i class="fa fa-save f-w-500 mr-2 f-11"></i> @lang('app.save')
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item f-14 text-dark save-form" href="javascript:void(0);"
                                data-type="send">
                                <i class="fa fa-paper-plane f-w-500  mr-2 f-12"></i> @lang('app.saveSend')
                            </a>
                        </li>
                    </ul>
                </div> --}}
                <x-forms.button-secondary data-type="draft" class="save-form mr-3">@lang('app.saveDraft')
                </x-forms.button-secondary>


            <x-forms.button-cancel :link="route('estimates.index')" class="border-0">@lang('app.cancel')
            </x-forms.button-cancel>

            <div class="col-md-4">
                <x-forms.button-secondary data-type="print" class="print-estimate" icon="print">@lang('app.print')
                </x-forms.button-secondary>
            </div>


        </div>
        <!-- CANCEL SAVE SEND END -->

    </x-form>
    <!-- FORM END -->
</div>
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><b><span style="font-size: 14px;"><span style="font-size: 14px;">T</span><span style="font-size: 14px;">ERMS OF USE FOR WORKSUITE.BIZ</span></span></b></div><div><br></div><div>The use of any product, service or feature (the "Materials") available through the internet web sites accessible at Worksuite.com (the "Web Site") by any user of the Web Site ("You" or "Your" hereafter) shall be governed by the following terms of use:</div><div>This Web Site is provided by Worksuite, a partnership awaiting registration with Government of India, and shall be used for informational purposes only. By using the Web Site or downloading Materials from the Web Site, You hereby agree to abide by the terms and conditions set forth in this Terms of Use. In the event of You not agreeing to these terms and conditions, You are requested by Worksuite not to use the Web Site or download Materials from the Web Site. This Web Site, including all Materials present (excluding any applicable third party materials), is the property of Worksuite and is copyrighted and protected by worldwide copyright laws and treaty provisions. You hereby agree to comply with all copyright laws worldwide in Your use of this Web Site and to prevent any unauthorized copying of the Materials. Worksuite does not grant any express or implied rights under any patents, trademarks, copyrights or trade secret information.</div><div>Worksuite has business relationships with many customers, suppliers, governments, and others. For convenience and simplicity, words like joint venture, partnership, and partner are used to indicate business relationships involving common activities and interests, and those words may not indicate precise legal relationships.</div><div><br></div><div><b><span style="font-size: 14px;">LIMITED LICENSE:</span></b></div><div><br></div><div>Subject to the terms and conditions set forth in these Terms of Use, Worksuite grants You a non-exclusive, non-transferable, limited right to access, use and display this Web Site and the Materials thereon. You agree not to interrupt or attempt to interrupt the operation of the Web Site in any manner. Unless otherwise specified, the Web Site is for Your personal and non-commercial use. You shall not modify, copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, transfer, or sell any information, software, products or services obtained from this Web Site.</div><div><br></div><div><b><span style="font-size: 14px;">THIRD PARTY CONTENT</span></b></div><div>The Web Site makes information of third parties available, including articles, analyst reports, news reports, and company information, including any regulatory authority, content licensed under Content Licensed under Creative Commons Attribution License, and other data from external sources (the "Third Party Content"). You acknowledge and agree that the Third Party Content is not created or endorsed by Worksuite. The provision of Third Party Content is for general informational purposes only and does not constitute a recommendation or solicitation to purchase or sell any securities or shares or to make any other type of investment or investment decision. In addition, the Third Party Content is not intended to provide tax, legal or investment advice. You acknowledge that the Third Party Content provided to You is obtained from sources believed to be reliable, but that no guarantees are made by Worksuite or the providers of the Third Party Content as to its accuracy, completeness, timeliness. You agree not to hold Worksuite, any business offering products or services through the Web Site or any provider of Third Party Content liable for any investment decision or other transaction You may make based on Your reliance on or use of such data, or any liability that may arise due to delays or interruptions in the delivery of the Third Party Content for any reason</div><div>By using any Third Party Content, You may leave this Web Site and be directed to an external website, or to a website maintained by an entity other than Worksuite. If You decide to visit any such site, You do so at Your own risk and it is Your responsibility to take all protective measures to guard against viruses or any other destructive elements. Worksuite makes no warranty or representation regarding and does not endorse, any linked web sites or the information appearing thereon or any of the products or services described thereon. Links do not imply that Worksuite or this Web Site sponsors, endorses, is affiliated or associated with, or is legally authorized to use any trademark, trade name, logo or copyright symbol displayed in or accessible through the links, or that any linked site is authorized to use any trademark, trade name, logo or copyright symbol of Worksuite or any of its affiliates or subsidiaries. You hereby expressly acknowledge and agree that the linked sites are not under the control of Worksuite and Worksuite is not responsible for the contents of any linked site or any link contained in a linked site, or any changes or updates to such sites. Worksuite is not responsible for webcasting or any other form of transmission received from any linked site. Worksuite is providing these links to You only as a convenience, and the inclusion of any link shall not be construed to imply endorsement by Worksuite in any manner of the website.</div><div><br></div><div><br></div><div><b><span style="font-size: 14px;">NO WARRANTIES</span></b></div><div>THIS WEB SITE, THE INFORMATION AND MATERIALS ON THE SITE, AND ANY SOFTWARE MADE AVAILABLE ON THE WEB SITE, ARE PROVIDED "AS IS" WITHOUT ANY REPRESENTATION OR WARRANTY, EXPRESS OR IMPLIED, OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, NON INFRINGEMENT, OR FITNESS FOR ANY PARTICULAR PURPOSE. THERE IS NO WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, REGARDING THIRD PARTY CONTENT. INSPITE OF FROIDEN BEST ENDEAVOURS, THERE IS NO WARRANTY ON BEHALF OF FROIDEN THAT THIS WEB SITE WILL BE FREE OF ANY COMPUTER VIRUSES. SOME JURISDICTIONS DO NOT ALLOW FOR THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.</div><div>LIMITATION OF DAMAGES:</div><div>IN NO EVENT SHALL FROIDEN OR ANY OF ITS SUBSIDIARIES OR AFFILIATES BE LIABLE TO ANY ENTITY FOR ANY DIRECT, INDIRECT, SPECIAL, CONSEQUENTIAL OR OTHER DAMAGES (INCLUDING, WITHOUT LIMITATION, ANY LOST PROFITS, BUSINESS INTERRUPTION, LOSS OF INFORMATION OR PROGRAMS OR OTHER DATA ON YOUR INFORMATION HANDLING SYSTEM) THAT ARE RELATED TO THE USE OF, OR THE INABILITY TO USE, THE CONTENT, MATERIALS, AND FUNCTIONS OF THIS WEB SITE OR ANY LINKED WEB SITE, EVEN IF FROIDEN IS EXPRESSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</div><div><br></div><div><b><span style="font-size: 14px;">DISCLAIMER:</span></b></div><div>THE WEB SITE MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL AND CLERICAL ERRORS. FROIDEN EXPRESSLY DISCLAIMS ANY OBLIGATION(S) TO UPDATE THIS WEBSITE OR ANY OF THE MATERIALS ON THIS WEBSITE. FROIDEN DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE WEB SITE. YOU ACKNOWLEDGE THAT ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION SHALL BE AT YOUR SOLE RISK. FROIDEN RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE WEB SITE. FROIDEN MAY MAKE ANY OTHER CHANGES TO THE WEB SITE, THE MATERIALS AND THE PRODUCTS, PROGRAMS, SERVICES OR PRICES (IF ANY) DESCRIBED IN THE WEB SITE AT ANY TIME WITHOUT NOTICE. THIS WEB SITE IS FOR INFORMATIONAL PURPOSES ONLY AND SHOULD NOT BE CONSTRUED AS TECHNICAL ADVICE OF ANY MANNER.</div><div>UNLAWFUL AND/OR PROHIBITED USE OF THE WEB SITE</div><div>As a condition of Your use of the Web Site, You shall not use the Web Site for any purpose(s) that is unlawful or prohibited by the Terms of Use. You shall not use the Web Site in any manner that could damage, disable, overburden, or impair any Worksuite server, or the network(s) connected to any Worksuite server, or interfere with any other party's use and enjoyment of any services associated with the Web Site. You shall not attempt to gain unauthorized access to any section of the Web Site, other accounts, computer systems or networks connected to any Worksuite server or to any of the services associated with the Web Site, through hacking, password mining or any other means. You shall not obtain or attempt to obtain any Materials or information through any means not intentionally made available through the Web Site.</div><div><br></div><div><b><span style="font-size: 14px;">INDEMNITY:</span></b></div><div>You agree to indemnify and hold harmless Worksuite, its subsidiaries and affiliates from any claim, cost, expense, judgment or other loss relating to Your use of this Web Site in any manner, including without limitation of the foregoing, any action You take which is in violation of the terms and conditions of these Terms of Use and against any applicable law.</div><div><br></div><div><b><span style="font-size: 14px;">CHANGES:</span></b></div><div>Worksuite reserves the rights, at its sole discretion, to change, modify, add or remove any portion of these Terms of Use in whole or in part, at any time. Changes in these Terms of Use will be effective when notice of such change is posted. Your continued use of the Web Site after any changes to these Terms of Use are posted will be considered acceptance of those changes. Worksuite may terminate, change, suspend or discontinue any aspect of the Web Site, including the availability of any feature(s) of the Web Site, at any time. Worksuite may also impose limits on certain features and services or restrict Your access to certain sections or all of the Web Site without notice or liability. You hereby acknowledge and agree that Worksuite may terminate the authorization, rights, and license given above at any point of time at its own sole discretion, and upon such termination; You shall immediately destroy all Materials.</div><div><br></div><div><br></div><div><b><span style="font-size: 14px;">INTERNATIONAL USERS AND CHOICE OF LAW:</span></b></div><div>This Site is controlled, operated, and administered by Worksuite from within India. Worksuite makes no representation that Materials on this Web Site are appropriate or available for use at any other location(s) outside India. Any access to this Web Site from territories where their contents are illegal is prohibited. You may not use the Web Site or export the Materials in violation of any applicable export laws and regulations. If You access this Web Site from a location outside India, You are responsible for compliance with all local laws.</div><div>These Terms of Use shall be governed by the laws of India,Terms of Use for worksuite.biz</div><div>The use of any product, service or feature (the "Materials") available through the internet web sites accessible at Worksuite.com (the "Web Site") by any user of the Web Site ("You" or "Your" hereafter) shall be governed by the following terms of use:</div><div>This Web Site is provided by Worksuite, a partnership awaiting registration with Government of India, and shall be used for informational purposes only. By using the Web Site or downloading Materials from the Web Site, You hereby agree to abide by the terms and conditions set forth in this Terms of Use. In the event of You not agreeing to these terms and conditions, You are requested by Worksuite not to use the Web Site or download Materials from the Web Site. This Web Site, including all Materials present (excluding any applicable third party materials), is the property of Worksuite and is copyrighted and protected by worldwide copyright laws and treaty provisions. You hereby agree to comply with all copyright laws worldwide in Your use of this Web Site and to prevent any unauthorized copying of the Materials. Worksuite does not grant any express or implied rights under any patents, trademarks, copyrights or trade secret information.</div><div>Worksuite has business relationships with many customers, suppliers, governments, and others. For convenience and simplicity, words like joint venture, partnership, and partner are used to indicate business relationships involving common activities and interests, and those words may not indicate precise legal relationships.</div><div><br></div><div><b><span style="font-size: 14px;">LIMITED LICENSE:</span></b></div><div>Subject to the terms and conditions set forth in these Terms of Use, Worksuite grants You a non-exclusive, non-transferable, limited right to access, use and display this Web Site and the Materials thereon. You agree not to interrupt or attempt to interrupt the operation of the Web Site in any manner. Unless otherwise specified, the Web Site is for Your personal and non-commercial use. You shall not modify, copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, transfer, or sell any information, software, products or services obtained from this Web Site.</div><div><br></div><div><b><span style="font-size: 14px;">THIRD-PARTY CONTENT</span></b></div><div>The Web Site makes information of third parties available, including articles, analyst reports, news reports, and company information, including any regulatory authority, content licensed under Content Licensed under Creative Commons Attribution License, and other data from external sources (the "Third Party Content"). You acknowledge and agree that the Third Party Content is not created or endorsed by Worksuite. The provision of Third Party Content is for general informational purposes only and does not constitute a recommendation or solicitation to purchase or sell any securities or shares or to make any other type of investment or investment decision. In addition, the Third Party Content is not intended to provide tax, legal or investment advice. You acknowledge that the Third Party Content provided to You is obtained from sources believed to be reliable, but that no guarantees are made by Worksuite or the providers of the Third Party Content as to its accuracy, completeness, timeliness. You agree not to hold Worksuite, any business offering products or services through the Web Site or any provider of Third Party Content liable for any investment decision or other transaction You may make based on Your reliance on or use of such data, or any liability that may arise due to delays or interruptions in the delivery of the Third Party Content for any reason</div><div>By using any Third Party Content, You may leave this Web Site and be directed to an external website, or to a website maintained by an entity other than Worksuite. If You decide to visit any such site, You do so at Your own risk and it is Your responsibility to take all protective measures to guard against viruses or any other destructive elements. Worksuite makes no warranty or representation regarding, and does not endorse, any linked web sites or the information appearing thereon or any of the products or services described thereon. Links do not imply that Worksuite or this Web Site sponsors, endorses, is affiliated or associated with, or is legally authorized to use any trademark, trade name, logo or copyright symbol displayed in or accessible through the links, or that any linked site is authorized to use any trademark, trade name, logo or copyright symbol of Worksuite or any of its affiliates or subsidiaries. You hereby expressly acknowledge and agree that the linked sites are not under the control of Worksuite and Worksuite is not responsible for the contents of any linked site or any link contained in a linked site, or any changes or updates to such sites. Worksuite is not responsible for webcasting or any other form of transmission received from any linked site. Worksuite is providing these links to You only as a convenience, and the inclusion of any link shall not be construed to imply endorsement by Worksuite in any manner of the website.</div><div><br></div><div><b><span style="font-size: 14px;">NO WARRANTIES</span></b></div><div>THIS WEB SITE, THE INFORMATION AND MATERIALS ON THE SITE, AND ANY SOFTWARE MADE AVAILABLE ON THE WEB SITE, ARE PROVIDED "AS IS" WITHOUT ANY REPRESENTATION OR WARRANTY, EXPRESS OR IMPLIED, OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, NON INFRINGEMENT, OR FITNESS FOR ANY PARTICULAR PURPOSE. THERE IS NO WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, REGARDING THIRD PARTY CONTENT. INSPITE OF FROIDEN BEST ENDEAVOURS, THERE IS NO WARRANTY ON BEHALF OF FROIDEN THAT THIS WEB SITE WILL BE FREE OF ANY COMPUTER VIRUSES. SOME JURISDICTIONS DO NOT ALLOW FOR THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.</div><div>LIMITATION OF DAMAGES:</div><div>IN NO EVENT SHALL FROIDEN OR ANY OF ITS SUBSIDIARIES OR AFFILIATES BE LIABLE TO ANY ENTITY FOR ANY DIRECT, INDIRECT, SPECIAL, CONSEQUENTIAL OR OTHER DAMAGES (INCLUDING, WITHOUT LIMITATION, ANY LOST PROFITS, BUSINESS INTERRUPTION, LOSS OF INFORMATION OR PROGRAMS OR OTHER DATA ON YOUR INFORMATION HANDLING SYSTEM) THAT ARE RELATED TO THE USE OF, OR THE INABILITY TO USE, THE CONTENT, MATERIALS, AND FUNCTIONS OF THIS WEB SITE OR ANY LINKED WEB SITE, EVEN IF FROIDEN IS EXPRESSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</div><div><br></div><div><b><span style="font-size: 14px;">DISCLAIMER:</span></b></div><div><span style="font-size: 12px;">THE WEB SITE MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL AND CLERICAL ERRORS. FROIDEN EXPRESSLY DISCLAIMS ANY OBLIGATION(S) TO UPDATE THIS WEBSITE OR ANY OF THE MATERIALS ON THIS WEBSITE. FROIDEN DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE WEB SITE. YOU ACKNOWLEDGE THAT ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION SHALL BE AT YOUR SOLE RISK. FROIDEN RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE WEB SITE. FROIDEN MAY MAKE ANY OTHER CHANGES TO THE WEB SITE, THE MATERIALS AND THE PRODUCTS, PROGRAMS, SERVICES OR PRICES (IF ANY) DESCRIBED IN THE WEB SITE AT ANY TIME WITHOUT NOTICE. THIS WEB SITE IS FOR INFORMATIONAL PURPOSES ONLY AND SHOULD NOT BE CONSTRUED AS TECHNICAL ADVICE OF ANY MANNER.</span></div><div><span style="font-size: 12px;">UNLAWFUL AND/OR PROHIBITED USE OF THE WEB SITE</span></div><div>As a condition of Your use of the Web Site, You shall not use the Web Site for any purpose(s) that is unlawful or prohibited by the Terms of Use. You shall not use the Web Site in any manner that could damage, disable, overburden, or impair any Worksuite server, or the network(s) connected to any Worksuite server, or interfere with any other party's use and enjoyment of any services associated with the Web Site. You shall not attempt to gain unauthorized access to any section of the Web Site, other accounts, computer systems or networks connected to any Worksuite server or to any of the services associated with the Web Site, through hacking, password mining or any other means. You shall not obtain or attempt to obtain any materials or information through any means not intentionally made available through the Web Site.</div><div><br></div><div><b><span style="font-size: 14px;">INDEMNITY:</span></b></div><div>You agree to indemnify and hold harmless Worksuite, its subsidiaries and affiliates from any claim, cost, expense, judgment or other loss relating to Your use of this Web Site in any manner, including without limitation of the foregoing, any action You take which is in violation of the terms and conditions of these Terms of Use and against any applicable law.</div><div><br></div><div><b><span style="font-size: 14px;">CHANGES:</span></b></div><div>Worksuite reserves the rights, at its sole discretion, to change, modify, add or remove any portion of these Terms of Use in whole or in part, at any time. Changes in these Terms of Use will be effective when notice of such change is posted. Your continued use of the Web Site after any changes to these Terms of Use are posted will be considered acceptance of those changes. Worksuite may terminate, change, suspend or discontinue any aspect of the Web Site, including the availability of any feature(s) of the Web Site, at any time. Worksuite may also impose limits on certain features and services or restrict Your access to certain sections or all of the Web Site without notice or liability. You hereby acknowledge and agree that Worksuite may terminate the authorization, rights, and license given above at any point of time at its own sole discretion, and upon such termination; You shall immediately destroy all Materials.</div><div><br></div><div><b><span style="font-size: 14px;">INTERNATIONAL USERS AND CHOICE OF LAW:</span></b></div><div>This Site is controlled, operated, and administered by Worksuite from within India. Worksuite makes no representation that Materials on this Web Site are appropriate or available for use at any other location(s) outside India. Any access to this Web Site from territories where their contents are illegal is prohibited. You may not use the Web Site or export the Materials in violation of any applicable export laws and regulations. If You access this Web Site from a location outside India, You are responsible for compliance with all local laws.</div><div>These Terms of Use shall be governed by the laws of India, without giving effect to its conflict of laws provisions. You agree that the appropriate court(s) in Bangalore, India, will have the exclusive jurisdiction to resolve all disputes arising under these Terms of Use and You hereby consent to personal jurisdiction in such forum.</div><div>These Terms of Use constitute the entire agreement between Worksuite and You with respect to Your use of the Web Site. Any claim You may have with respect to Your use of the Web Site must be commenced within one (1) year of the cause of action. If any provision(s) of this Terms of Use is held by a court of competent jurisdiction to be contrary to law then such provision(s) shall be severed from this Terms of Use and the other remaining provisions of this Terms of Use shall remain in full force and effect. without giving effect to its conflict of laws provisions. You agree that the appropriate court(s) in Bangalore, India, will have the exclusive jurisdiction to resolve all disputes arising under these Terms of Use and You hereby consent to personal jurisdiction in such forum.</div><div>These Terms of Use constitute the entire agreement between Worksuite and You with respect to Your use of the Web Site. Any claim You may have with respect to Your use of the Web Site must be commenced within one (1) year of the cause of action. If any provision(s) of this Terms of Use is held by a court of competent jurisdiction to be contrary to law then such provision(s) shall be severed from this Terms of Use and the other remaining provisions of this Terms of Use shall remain in full force and effect.</div><p></p>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- CREATE INVOICE END -->
@push('scripts')
<script>
    $(document).ready(function() {

        $('.addServiceCategory').click(function () {
            var url = "{{ route('productCategory.create') }}?page=estimate";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#addServiceSubCategory').click(function () {
            const url = "{{ route('productSubCategory.create') }}?page=estimate";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#addExpenseCategory').click(function() {
            let userId = $('.user_id').val();
            const url = "{{ route('expenseCategory.create') }}?page=estimate&user_id="+userId;
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        changesProduct($('#unit_type_id').val());
        var term = '{!! $unit_types[0]->unit_type !!}';
        $('#unit_type_id').change(function(e) {
            let unitTypeId = $(this).val();
            changesProduct(unitTypeId);
        });

        function showButtons(){
            $('header').removeClass('d-none')
            $('.hide-buttons').removeClass('d-none')
            $('.ql-snow').removeClass('d-none')
        }

        $('.print-estimate').click(function () {
            $('header').addClass('d-none');
            $('.hide-buttons').addClass('d-none');
            $('.ql-snow').addClass('d-none');
            window.print();
            setTimeout(showButtons(), 2000);
        });
        var i_multi_expenses = $(document).find('.add_expense_row').length;
        $(document).on('click', '.add_expense_btn', function() {
            i_multi_expenses++;
            var parent_i = $(document).find('.item_name').length;
            parent_i = parent_i - 1;
            console.log(parent_i);
            var i = i_multi_expenses;
            refreshProductCategory(i);
            refreshProductSubCategory(i);
            refreshProduct(i);
            refreshExpenseCategory(i);
            refreshExpense(i);
            console.log(i);
                var htmlToAppend =  '<tr class="add_expense_row dash-border-top bblr">'+
                    '<td align="middle" class="text-dark-grey font-weight-bold f-14">{{ __('app.expense')}}' +
                    '</td>' +
                    '<td>'+
                    '<div class="input-group">'+
                    '<div class="dropdown bootstrap-select form-control select-picker">' +
                    '<select class="form-control select-picker expense_category_id" required name="expense_category_id'+parent_i+'[]"'+ 'id="expense_category_id' + i + '" data-live-search="true">'+
                    '<option value="">@lang('app.selectExpenseCategory')</option>'
                    @foreach ($expenseCategories as $category)
                    +'<option value="{{ $category->id }}">'+
                    '{{ mb_ucwords($category->category_name) }}</option>'
                    @endforeach
                    +'</select>'+
                    '<input class="user_id" type="hidden" id="user_id' + i + '" value="{{ user()->id }}">'+
                    '</div>'+
                    '<div class="input-group-append">'+
                    '<a href="javascript:;" id="addExpenseCategory' + i + '"'+
                    'class="btn btn-outline-secondary border-grey" data-toggle="tooltip"'+
                    'data-original-title="'+
                    '{{ __('app.add')}} {{ __("app.expenseCategory") }}"'+
                    '><i class="icons icon-plus font-weight-bold"></i></a>'+
                    '</div>'+
                    '</div>'+
                    '</td>'+
                    '<td colspan="2" width="10%">'+
                    '<div class="input-group">'+
                    '<div class="dropdown bootstrap-select form-control select-picker">'+
                    '<select class="form-control select-picker add_expense" required name="expense_id'+parent_i+'[]" id="add_expense' + i + '" data-live-search="true">'+
                    '<option value="">@lang('app.selectExpense')</option>'
                    @foreach ($expenses as $expense)
                    +'<option value="{{ $expense->id }}">'+
                    '{{ mb_ucwords($expense->item_name) }}</option>'
                    @endforeach
                    +'</select>'+
                    '<div class="text-red f-14 duplicate-error d-none" id="duplicate-error' + i + '">Duplicate Expense</div>' +
                    '<input type="hidden" class="expense_item_id" name="expense_item_id'+parent_i+'[]">' +
                    '<input type="hidden" class="expense_item_name" name="expense_item_name'+parent_i+'[]">' +
                    '</div>'+
                    '<div class="input-group-append">'+
                    '<a href="{{ route('expenses.create') }}?item_no=' + i + '&page=estimate"' +
                    'data-redirect-url="{{ url()->full() }}" class="btn btn-outline-secondary border-grey openRightModal"' +
                    'data-toggle="tooltip" data-original-title="' +
                    '{{ __('app.addNewExpense')}}"'+
                    '><i class="icons icon-plus font-weight-bold"></i></a>' +
                    '</div>'+
                    '</div>'+
                    '</td>'+
                    '<td>'+
                    '<input type="number" min="1"'+
                    'class="f-14 border-0 w-100 text-right expense_price form-control"'+
                    'placeholder="0.00" name="expense_price'+parent_i+'[]">'+
                    '</td>'+
                    '<td>'+
                    '<input type="number" min="1"'+
                    'class="f-14 border-0 w-100 text-right expense_margin_percent form-control"'+
                    'placeholder="0" name="expense_margin_percent'+parent_i+'[]">'+
                    '</td>'+

                    '<td valign="top" align="left" class="c-inv-sub-padding">'
                    @foreach ($taxes as $tax)
                    +'<div class="form-control f-14">{{ $tax->rate_percent }}%</div>'+
                    '<input data-expense-tax-rate="{{$tax->rate_percent }}" type="hidden" value="{{ $tax->id }}"'+'min="0" name="expnese_tax'+parent_i+'[]" class="expnese_tax" placeholder="0">'
                    @endforeach
                    +'</td>'+
                    '<td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">'+
                    '<span class="expense-amount-html">0.00</span>'+
                    '<input type="hidden" class="expense-amount" name="expense_amount'+parent_i+'[]">'+
                    '<input type="hidden" class="expense-tax-amount" name="expense_tax_amount'+parent_i+'[]">'+
                    '</td>'+
                    '<td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">'+
                    '<span class="expense-cost-html">0.00</span>'+
                    '<input type="hidden" name="expense_cost'+parent_i+'[]" class="expense-cost">'+
                    '</td>'+
                    '<td width="5%" align="right" valign="top" class="bg-amt-grey btrr-bbrr">'+
                    '<span class="expense-margin-html">0.00</span>'+
                    '<input type="hidden" name="expense_margin'+parent_i+'[]" class="expense-margin">'+
                    '</td>'+
                    '<td align="right" valign="middle" style="padding:8px; border: 0;">'+
                    '<a href="javascript:;" class="remove-expense remove_expense_btn">'+
                    '<i class="fa fa-times-circle f-20 text-lightest"></i></a>'+
                    '</td>'+
                    '</tr>';
                // Clone the existing "add_expense_row" and remove the "d-none" class
                $(this).closest('tr').prev('tr').after(htmlToAppend);

            console.log('i_multi_expense=>',i_multi_expenses);
            $('#multiselect' + i).selectpicker();
            $('#expense_category_id' + i).selectpicker();
            $('#add_expense' + i).selectpicker();

            $('#dropify' + i).dropify({
                messages: dropifyMessages
            });

            $('#addExpenseCategory' + i).click(function() {
                let userId = $('.user_id').val();
                const url = "{{ route('expenseCategory.create') }}?item_no=" + i + "&page=estimate&user_id="+userId;
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });
            // var data = $(this).closest('tr').next('tr').children('td:nth-child(5)').find('select.customSequence');
            // $(this).closest('tr').next('tr').find('select.customSequence').addClass('type');
            calculateEstimageTotal();
            $('#expense_category_id' + i).change(function (e) {
                e.preventDefault();
                let categoryId = $(this).val();
                let url = "{{ route('get_expense_by_categories', ':id') }}";
                url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

                var self = this;
                $.easyAjax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        if (response.status == 'success') {
                            var options = [];
                            var rData;
                            rData = response.data;
                            // console.log("inside expense data", rData);
                            $.each(rData, function (index, value) {
                                var selectData;
                                selectData = '<option value="' + value.id + '">' + value
                                    .item_name + '</option>';
                                options.push(selectData);
                            });

                            $(self).closest('tr').find('select.add_expense').html('<option value="0" selected>@lang('app.selectExpense')</option>' + options);
                            $(self).closest('tr').find('select.add_expense').selectpicker('refresh');
                        }
                    }
                })
            });

            // Change Expense type
            $('#add_expense' + i).on('changed.bs.select', function(e) {
                e.stopImmediatePropagation();
                var id = $(this).val();
                let expenseUrl = "{{ route('get_expense', ':id') }}";
                expenseUrl = (id) ? expenseUrl.replace(':id', id) : expenseUrl.replace(':id', null);

                var self = this;
                $.easyAjax({
                    url: expenseUrl,
                    type: "GET",
                    success: function (response) {
                        if (response.status == 'success') {
                            var expensePrice;
                            rData = response.data;
                            // console.log('expense data', rData);
                            expenseId =rData[0].id;
                            expenseName =rData[0].item_name;
                            expensePrice =rData[0].price;
                            $(self).closest('tr').find('.expense_item_id').val(expenseId);
                            $(self).closest('tr').find('.expense_item_name').val(expenseName);

                            //Create array of input values
                            var arr = $("#saveInvoiceForm .expense_item_id").map(function() {
                                if ($(this).val() != '') return $(this).val()
                            }).get();
                            console.log('expense id array', arr);
                            //Create array of duplicates if there are any
                            var unique = arr.filter(function(item, pos) {
                                return arr.indexOf(item) != pos;
                            });
                            console.log('unique data', unique);
                            //show/hide error msg
                            // (unique.length != 0) ? $('#duplicate-error' + i).toggleClass('d-none') : $('.duplicate-error').addClass('d-none');

                            // var itemTax = $(self).closest(".item-row").find("input.expnese_tax").data("expense-tax-rate");
                            // console.log('expense tax',itemTax);
                            // var taxAmount = expensePrice * (itemTax / 100);

                            var marginValue = $(self).closest('.tr').find('.expense_margin_percent').val();
                            if (isNaN(marginValue)) {
                                marginValue = 0;
                            }
                            var marginedAmount = 0;
                            if (marginValue != "") {
                                marginAmount = (parseFloat(expensePrice) / 100) * parseFloat(marginValue);
                                $(self).closest('tr').find('.expense-margin').val(decimalupto2(marginAmount));
                                // $(self).closest('.item-row').find('.expense-margin-html').html(decimalupto2(marginAmount));
                                $(self).closest('tr').find('.expense-margin-html').html(number_format(marginAmount, 2, ',', '.'));
                                marginedAmount = parseFloat(expensePrice) + parseFloat(marginAmount);
                                console.log(marginedAmount);
                            }
                            var itemTax = $(self).closest("tr").find("input.expnese_tax").data("expense-tax-rate");
                            // console.log('expense tax rate',itemTax);
                            var taxAmount = parseFloat(marginedAmount) * (parseFloat(itemTax) / 100);
                            // console.log('expense tax',taxAmount);

                            $(self).closest('tr').find('.expense_price').val(decimalupto2(expensePrice));
                            $(self).closest('tr').find('.expense-tax-amount').val(decimalupto2(taxAmount));
                            if (isNaN(marginedAmount)) {
                                marginedAmount = 0;
                            }
                            if(marginedAmount > 0){
                                $(self).closest('tr').find('.expense-amount').val(decimalupto2(marginedAmount));
                                // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(marginedAmount));
                                $(self).closest('tr').find('.expense-amount-html').html(number_format(marginedAmount, 2, ',', '.'));
                            } else {
                                $(self).closest('tr').find('.expense-amount').val(decimalupto2(expensePrice));
                                // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(expensePrice));
                                $(self).closest('tr').find('.expense-amount-html').html(number_format(expensePrice, 2, ',', '.'));
                            }

                            $(self).closest('tr').find('.expense-cost').val(decimalupto2(expensePrice));
                            // $(self).closest('.item-row').find('.expense-cost-html').html(decimalupto2(expensePrice));
                            $(self).closest('tr').find('.expense-cost-html').html(number_format(expensePrice, 2, ',', '.'));

                            calculateEstimageTotal();
                        }
                    }
                })
            });
        });

        $(document).on('click', '.remove_expense_btn', function() {
            $(this).closest('tr').remove();
            $(this).closest('tr').find('.add_expense').val('').selectpicker("refresh");
            $(this).closest('tr').find('.expense_category_id').val('').selectpicker("refresh");

            $(this).closest('tr').find('.expense_item_id').val(null);
            $(this).closest('tr').find('.expense_item_name').val(null);

            $(this).closest('tr').find('.expense_price').val(null);
            $(this).closest('tr').find('.expense_margin_percent').val(null);
            $(this).closest('tr').find('.expense-tax-amount').val(null);
            $(this).closest('tr').find('.expense-amount').val(null);
            $(this).closest('tr').find('.expense-amount-html').html('€0.00');
            $(this).closest('tr').find('.expense-cost').val(null);
            $(this).closest('tr').find('.expense-cost-html').html('€0.00');
            $(this).closest('tr').find('.expense-margin').val(null);
            $(this).closest('tr').find('.expense-margin-html').html('€0.00');
            // $(this).closest('tr').find('select.customSequence:first').removeClass('type');

            calculateEstimageTotal();
            $(this).closest('tr').prev('tr').show();
        });

        $('.itemImage').on('change', function(e) {
            console.log($('#' + e.target.id).data('item-id'));
            $('#imageId_' + $('#' + e.target.id).data('item-id')).val('');

        })

        $(".itemOldImage").next(".dropify-clear").trigger("click");
        var file = $('.dropify').dropify({
            messages: dropifyMessages
        });

        file.on("dropify.afterClear", function(event, element) {
            var elementID = element.element.id;
            var elementName = element.element.name;
            var elementIndex = element.element.dataset.index;
            if (elementName.indexOf("[]") > -1) {
                elementName = elementName.replace("[]", "");
            }
            if ($("#" + elementID + "_delete").length == 0) {
                $("#" + elementID).after(
                    '<input type="hidden" name="' +
                    elementName +
                    '_delete[' + elementIndex + ']" id="' +
                    elementID +
                    '_delete" value="yes">'
                );
            }
        });


        function changesProduct(id) {
            var url = "{{ route('get_clients_data', ':id') }}",
                url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        // $.each(rData, function(index, value) {
                        //     var selectData = '';
                        //     selectData = '<option value="' + value.id + '">' + value.name +
                        //         '</option>';
                        //     options.push(selectData);
                        // });
                        // $('#add-products').html(
                        //     '<option value="" class="form-control" >{{ __('app.select') . ' ' . __('app.product') }}</option>' +
                        //     options);
                        // $('#add-products').selectpicker('refresh');
                        var term = response.type.unit_type;
                         $('#type').html(term);
                    }
                }
            });
        }

        const hsn_status = "{{ $invoiceSetting->hsn_sac_code_show }}";
        const defaultClient = "{{ request('default_client') }}";

        quillImageLoad('#description');

        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        const dp1 = datepicker('#valid_till', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', today()->addDays(30)) }}"),
            ...datepickerConfig
        });

        const dp2 = datepicker('#start_date', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', today()) }}"),
            ...datepickerConfig
        });

        const dp3 = datepicker('#end_date', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', today()->addDays(30)) }}"),
            ...datepickerConfig
        });

        const resetAddProductButton = () => {
            //$("#add-products").val('').selectpicker("refresh");
        };


        // add product dropdown
        // $('#add-products').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        //     e.stopImmediatePropagation()
        //     var id = $(this).val();
        //     if (previousValue != id && id != '') {
        //         addProduct(id);
        //         resetAddProductButton();
        //     }
        // });

        // Change service type
        // $('#add-products').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        //     e.stopImmediatePropagation()
        //     var id = $(this).val();
        //     if (previousValue != id && id != '') {
        //         let producturl = "{{ route('get_product', ':id') }}";
        //         producturl = (id) ? producturl.replace(':id', id) : producturl.replace(':id', null);

        //         $.easyAjax({
        //             url: producturl,
        //             type: "GET",
        //             success: function (response) {
        //                 if (response.status == 'success') {
        //                     var productCategoryId;
        //                     var productSubCategoryId;
        //                     rData = response.data;
        //                     productCategoryId =rData[0].category_id;
        //                     productSubCategoryId =rData[0].sub_category_id;
        //                     // let categoryUrl = "{{ route('get_product_categories', ':id') }}";
        //                     // categoryUrl = (productCategoryId) ? categoryUrl.replace(':id', productCategoryId) : categoryUrl.replace(':id', null);
        //                     // $.easyAjax({
        //                     //     url: categoryUrl,
        //                     //     type: "GET",
        //                     //     success: function (response) {
        //                     //         if (response.status == 'success') {
        //                     //             var options = [];
        //                     //             var rData;
        //                     //             rData = response.data;
        //                     //             $.each(rData, function (index, value) {
        //                     //                 var selectData;
        //                     //                 selectData = '<option value="' + value.id + '">' + value
        //                     //                     .category_name + '</option>';
        //                     //                 options.push(selectData);
        //                     //             });
        //                     //             $('#product_category_id').html(options);
        //                     //             $('#product_category_id').selectpicker('refresh');
        //                     //         }
        //                     //     }
        //                     // })
        //                     // let subCategoryUrl = "{{ route('get_selected_product_sub_categories', ':id') }}";
        //                     // subCategoryUrl = (productSubCategoryId) ? subCategoryUrl.replace(':id', productSubCategoryId) : subCategoryUrl.replace(':id', null);
        //                     // $.easyAjax({
        //                     //     url: subCategoryUrl,
        //                     //     type: "GET",
        //                     //     success: function (response) {
        //                     //         if (response.status == 'success') {
        //                     //             var options = [];
        //                     //             var rData;
        //                     //             rData = response.data;
        //                     //             $.each(rData, function (index, value) {
        //                     //                 var selectData;
        //                     //                 selectData = '<option value="' + value.id + '">' + value
        //                     //                     .category_name + '</option>';
        //                     //                 options.push(selectData);
        //                     //             });
        //                     //             $('#product_sub_category_id').html(options);
        //                     //             $('#product_sub_category_id').selectpicker('refresh');
        //                     //         }
        //                     //     }
        //                     // })
        //                 }
        //                 addProduct(id);
        //                 // resetAddProductButton();
        //             }
        //         })
        //     }
        // });

        function addProduct(id) {
            var currencyId = $('#currency_id').val();

            $.easyAjax({
                url: "{{ route('invoices.add_item') }}",
                type: "GET",
                data: {
                    id: id,
                    currencyId: currencyId
                },
                blockUI: true,
                success: function(response) {
                    if ($('input[name="item_name[]"]').val() == '') {
                        $("#sortable .item-row").remove();
                    }
                    $(response.view).hide().appendTo("#sortable").fadeIn(500);
                    calculateEstimageTotal();

                    var noOfRows = $(document).find('#sortable .item-row').length;
                    var i = $(document).find('.item_name').length - 1;
                    var itemRow = $(document).find('#sortable .item-row:nth-child(' + noOfRows +
                        ') select.type');
                    itemRow.attr('id', 'multiselect' + i);
                    itemRow.attr('name', 'taxes[' + i + '][]');
                    $(document).find('#multiselect' + i).selectpicker();

                    $(document).find('#dropify' + i).dropify({
                        messages: dropifyMessages
                    });
                }
            });
        }

        function refreshProductCategory(i) {
            $.easyAjax({
                url: "{{ route('product_category_dropdown') }}",
                type: "GET",
                success: function(response) {
                    // console.log("product cat dropdown", response.data);
                    $('#product_category_id' + i).html(response.data);
                    $('#product_category_id' + i).selectpicker('refresh');
                }
            });
        }
        function refreshProductSubCategory(i) {
            $.easyAjax({
                url: "{{ route('product_sub_category_dropdown') }}",
                type: "GET",
                success: function(response) {
                    $('#product_sub_category_id' + i).html(response.data);
                    $('#product_sub_category_id' + i).selectpicker('refresh');
                }
            });
        }
        function refreshProduct(i) {
            $.easyAjax({
                url: "{{ route('product_dropdown') }}",
                type: "GET",
                success: function(response) {
                    //console.log("product dropdown", response.data);
                    $('#add-products' + i).html(response.data);
                    $('#add-products' + i).selectpicker('refresh');
                }
            });
        }

        function refreshExpenseCategory(i) {
            $.easyAjax({
                url: "{{ route('expense_category_dropdown') }}",
                type: "GET",
                success: function(response) {
                    // console.log("expense cat dropdown", response.data);
                    $('#expense_category_id' + i).html(response.data);
                    $('#expense_category_id' + i).selectpicker('refresh');
                }
            });
        }

        function refreshExpense(i) {
            $.easyAjax({
                url: "{{ route('expense_dropdown') }}",
                type: "GET",
                success: function(response) {
                    //console.log("expense dropdown", response.data);
                    $('#add_expense' + i).html(response.data);
                    $('#add_expense' + i).selectpicker('refresh');
                }
            });
        }

        $(document).on('click', '#add-item', function() {
            var i = $(document).find('.item_name').length;
            refreshProductCategory(i);
            refreshProductSubCategory(i);
            refreshProduct(i);
            refreshExpenseCategory(i);
            refreshExpense(i);

            var item = ' <div class="d-flex pl-2 py-2 c-inv-desc item-row">' +
                '<div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">' +
                '<table width="100%">' +
                '<tbody>' +
                '<tr>' +
                '<td class="border-bottom-0 btrr-mbl btlr pr-category">' +
                    '<div class="input-group">' +
                        '<div class="dropdown bootstrap-select form-control select-picker">' +
                            '<select class="form-control select-picker product_category_id"'+ 'name="product_category_id[]" id="product_category_id' + i + '" data-live-search="true">' +
                                '<option value="">'+
                                    '{{ __('app.select')}} {{ __("modules.productCategory.productCategory") }}'+
                                '</option>'
                                @foreach ($serviceCategories as $category)
                                    + '<option data-content="{{ $category->category_name }}" value="{{ $category->id }}">' +
                                        '{{mb_ucwords($category->category_name)}}</option>'
                                @endforeach
                            +'</select>'+
                            '<input type="hidden" class="product_category_name" name="product_category_name[]">'+
                        '</div>'+
                        '<div class="input-group-append">' +
                            '<a href="javascript:;" id="addServiceCategory' + i + '"'+
                                'class="btn btn-outline-secondary border-grey" data-toggle="tooltip"'+
                                'data-original-title="@lang('app.add')'+' '+'@lang('modules.productCategory.productCategory')"><i class="icons icon-plus font-weight-bold"></i></a>'+
                        '</div>'+
                    '</div>'+
                '</td>'+

                '<td class="border-bottom-0 btrr-mbl btlr pr-subcategory">' +
                    '<div class="input-group">' +
                        '<div class="dropdown bootstrap-select form-control select-picker">' +
                            '<select class="form-control select-picker product_sub_category_id"'+ 'name="product_sub_category_id[]" id="product_sub_category_id' + i + '" data-live-search="true">' +
                                '<option value="">'+
                                    '{{ __('app.select')}} {{ __("modules.productCategory.productSubCategory") }}'+
                                '</option>'
                                @foreach ($serviceSubCategories as $subCategory)
                                    + '<option data-content="{{$subCategory->category_name}}" value="{{ $subCategory->id }}">' +
                                        '{{mb_ucwords($subCategory->category_name)}}</option>'
                                @endforeach
                            +'</select>'+
                        '<input type="hidden" class="product_sub_category_name" name="product_sub_category_name[]">'+
                        '</div>'+
                        '<div class="input-group-append">' +
                            '<a href="javascript:;" id="addServiceSubCategory' + i + '"'+
                                'class="btn btn-outline-secondary border-grey" data-toggle="tooltip"'+
                                'data-original-title="@lang('app.add')'+' '+'@lang('modules.productCategory.productSubCategory')"><i class="icons icon-plus font-weight-bold"></i></a>'+
                        '</div>'+
                    '</div>'+
                '</td>'+

                '<td class="border-bottom-0 btrr-mbl btlr pr-name">' +
                    '<div class="input-group">' +
                        '<div class="dropdown bootstrap-select form-control select-picker">' +
                            '<select class="form-control select-picker item_id add-products"'+ 'name="item_id[]" id="add-products' + i + '" data-live-search="true">' +
                                '<option value="">'+
                                    '{{ __('app.select')}} {{ __("app.product") }}'+
                                '</option>'
                                @foreach ($products as $item)
                                    + '<option data-content="{{ $item->name }}" value="{{ $item->id }}">' +
                                        '{{mb_ucwords($item->name)}}</option>'
                                @endforeach
                            +'</select>'+
                            '<input type="hidden" class="item_name" name="item_name[]">'+
                        '</div>'+
                        '<div class="input-group-append">' +
                            '<a href="{{ route('products.create') }}?item_no=' + i + '&page=estimate"'+
                                ' data-redirect-url="{{ url()->full() }}" class="btn btn-outline-secondary border-grey openRightModal" data-toggle="tooltip"'+
                                'data-original-title="@lang('app.add')'+' '+'@lang('modules.dashboard.newproduct')"><i class="icons icon-plus font-weight-bold"></i></a>'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                '<td class="border-bottom-0 d-block d-lg-none d-md-none">' +
                '<textarea class="f-14 border-0 w-100 mobile-description form-control" name="item_summary[]" placeholder="@lang('placeholders.invoices.description')"></textarea>' +
                '</td>'
                ;
            // if (hsn_status == 1) {
            //     item += '<td class="border-bottom-0">' +
            //         '<input type="text" min="1" class="f-14 border-0 w-100 text-right hsn_sac_code form-control" value="" name="hsn_sac_code[]">' +
            //         '</td>';
            // }
            item += '<td align="middle" class="border-bottom-0 pr-unittype">' +
            '<span class="product_unit_name_html"></span>'+
            '<input type="number" min="1" class="form-control f-14 border-0 w-100 text-right quantity unit_type_quantity" value="1" name="quantity[]">' +
            '<input type="hidden" class="product_id" name="product_id[]">' +
            '<input type="hidden" class="product_unit_id" name="product_unit_id[]">' +
            '<input type="hidden" class="product_unit_name" name="product_unit_name[]">' +
            '</td>' +
            '<td class="border-bottom-0 pr-unitprice">' +
            '<input type="number" min="1" class="form-control f-14 border-0 w-100 text-right cost_per_item" placeholder="0.00" value="0" name="cost_per_item[]">' +
            '</td>' +
            '<td class="border-bottom-0 pr-margin">'+
            '<input type="number" min="0"'+
            'class="f-14 border-0 w-100 text-right service_margin_percent form-control"'+ 'name="service_margin_percent[]" placeholder="0">'+
            '</td>'+

            '<td class="pr-tax" rowspan="2" valign="top">' +
            '<div class="select-others height-35 rounded border-0 tax-select">' +
            '<select id="multiselect' + i + '" name="taxes[' + i +
            '][]" class="select-picker type customSequence" data-size="3">'
            @foreach ($taxes as $tax)
                +'<option data-rate="{{ $tax->rate_percent }}" selected value="{{ $tax->id }}">' +
                '{{ $tax->rate_percent }}%</option>'
            @endforeach +
            '</select>' +
            '</div>' +
            '</td>' +
            '<td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-amount">'+
                '<span class="amount-html">0.00</span>'+
                '<input type="hidden" class="amount" name="amount[]" value="0">'+
            '</td>'+
            '<td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost">'+
                '<span class="cost-html">0.00</span>'+
                '<input type="hidden" class="cost" name="cost[]" value="0">'+
            '</td>'+
            '<td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr pr-cost-margin">'+
                '<span class="margin-html">0.00</span>'+
                '<input type="hidden" class="margin" name="margin[]" value="0">'+
            '</td>'+
            '<td class="pr-removeitem" rowspan="2" width="6px" align="right" valign="middle" style="padding:8px; border: 0;">'+
                '<a href="javascript:;"class="remove-item">'+
                    '<i class="fa fa-times-circle f-20 text-lightest"></i></a>'+
            '</td>'+
            '</tr>' +

            '<tr class="d-none d-md-table-row d-lg-table-row">' +
            '<td colspan="{{ $invoiceSetting->hsn_sac_code_show ? 7 : 6 }}" class="dash-border-top bblr">' +
            '<textarea class="border-0 w-100 desktop-description form-control" name="item_summary[]" placeholder="@lang('placeholders.invoices.description')"></textarea>' +
            '</td>' +
            '</td>' +
            '</tr>' +
            '<tr class="dash-border-top bblr">' +
            '<td colspan="10">' +
            '<a href="javascript:void(0);" style="font-size:14px !important;"' +
            'class="add_expense_btn f-15 f-w-500" data-toggle="tooltip"' +
            'data-original-title="{{ __('app.addExpense')}}"><i class="icons icon-plus font-weight-bold mr-1"></i>@lang('app.addExpense')</a>' +
            '</td>'+
            '</tr>'+
            '</tbody>' +
            '</table>' +
            '</div>' +
            '</div>';
            $(item).hide().appendTo("#sortable").fadeIn(500);
            $('#multiselect' + i).selectpicker();
            $('#product_category_id' + i).selectpicker();
            $('#product_sub_category_id' + i).selectpicker();
            $('#add-products' + i).selectpicker();
            $('#expense_category_id' + i).selectpicker();
            $('#add_expense' + i).selectpicker();

            $('#dropify' + i).dropify({
                messages: dropifyMessages
            });

        $('#addServiceCategory' + i).click(function () {
            var url = "{{ route('productCategory.create') }}?item_no=" + i + "&page=estimate";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#addServiceSubCategory' + i).click(function () {
            const url = "{{ route('productSubCategory.create') }}?item_no=" + i + "&page=estimate";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#addExpenseCategory' + i).click(function() {
            let userId = $('.user_id').val();
            const url = "{{ route('expenseCategory.create') }}?item_no=" + i + "&page=estimate&user_id="+userId;
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });


            $('#product_category_id' + i).change(function (e) {
            let categoryId = $(this).val();
            let catname = $(this).closest('.item-row').find('.product_category_id option:selected').text();
            $(this).closest('.item-row').find('.product_category_name').val(catname.trim());

            console.log("product category name", catname);

            let url = "{{ route('get_product_sub_categories', ':id') }}";

            url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option data-content="'+ value
                                .category_name +'" value="' + value.id + '">' + value
                                .category_name + '</option>';
                            options.push(selectData);
                        });

                        $(self).closest('.item-row').find('select.product_sub_category_id').html('<option value="">Select Service Sub Category</option>' + options);
                        $(self).closest('.item-row').find('select.product_sub_category_id').selectpicker('refresh');
                    }
                }
            })
        });

        $('#product_sub_category_id' + i).change(function (e) {
            let subCatId = $(this).val();
            let subCatname = $(this).closest('.item-row').find('.product_sub_category_id option:selected').text();
            $(this).closest('.item-row').find('.product_sub_category_name').val(subCatname.trim());
            console.log("product sub cat name",subCatname);
            let url = "{{ route('get_product_by_sub_category', ':id') }}";

            url = (subCatId) ? url.replace(':id', subCatId) : url.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option data-content="'+ value
                                .name +'" value="' + value.id + '">' + value
                                .name + '</option>';
                            options.push(selectData);
                        });

                        $(self).closest('.item-row').find('select.add-products').html('<option value="">Select Product</option>' + options);
                        $(self).closest('.item-row').find('select.add-products').selectpicker('refresh');
                    }
                }
            })
        });

        // Change service type
         $('#add-products' + i).on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        e.stopImmediatePropagation()
        var id = $(this).val();
        if (previousValue != id && id != '') {
            let producturl = "{{ route('get_product', ':id') }}";
            producturl = (id) ? producturl.replace(':id', id) : producturl.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: producturl,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var productId;
                        var productPrice;
                        rData = response.data;
                        // console.log("product data",rData);
                        productId =rData[0].id;
                        productName =rData[0].name;
                        $(self).closest('.item-row').find('.item_name').val(productName);
                        productPrice =rData[0].price;
                        var productUnitId =rData[0].unit_id;
                        var productUnitName =rData[0].unit_name;
                        if(productUnitName =="Fixed Price" || productUnitName =="Hrs"){
                            $(self).closest('.item-row').find('input.unit_type_quantity').hide();
                        }else{
                            $(self).closest('.item-row').find('input.unit_type_quantity').show();
                        }
                        console.log("Unit id",productUnitId);
                        console.log("Unit name",productUnitName);
                        // var itemTax=[];
                        // $(self).closest(".item-row").find("select.type:first option:selected")
                        // .each(function (index) {
                        //     itemTax[index] = $(this).data("rate");
                        // });
                        // console.log(itemTax[0]);
                        $(self).closest('.item-row').find('.cost_per_item').val(decimalupto2(productPrice));
                            var quantity = $(self).closest('.item-row').find('.quantity').val();
                            var perItemCost = $(self).closest('.item-row').find('.cost_per_item').val();
                            var amount = (quantity * perItemCost);
                            // var taxAmount = itemTax[0] * (amount / 100);
                            // console.log(taxAmount);
                            // var amountWithTax = amount + taxAmount;
                            var marginValue = $(self).closest('.item-row').find('.service_margin_percent').val();
                            if(productUnitName =="Hrs"){
                                $(self).closest('.item-row').find('.service_margin_percent').val(0);
                                marginValue = $(self).closest('.item-row').find('.service_margin_percent').val();
                            }
                            var marginedAmount = 0;
                            if (marginValue != "") {
                                marginAmount = (parseFloat(amount) / 100) * parseFloat(marginValue);
                                if(productUnitName =="Hrs"){
                                    marginAmount=0;
                                }
                                $(self).closest('.item-row').find('.margin').val(decimalupto2(marginAmount));

                                if(productUnitName =="Hrs"){
                                    $(self).closest('.item-row').find('.margin-html').html('');
                                }else{
                                    // $(self).closest('.item-row').find('.margin-html').html(decimalupto2(marginAmount));
                                    $(self).closest('.item-row').find('.margin-html').html(number_format(marginAmount, 2, ',', '.'));
                                }
                                marginedAmount = parseFloat(amount + marginAmount);
                            }
                            $(self).closest('.item-row').find('.product_unit_name_html').html(productUnitName);
                            $(self).closest('.item-row').find('.product_id').val(productId);
                            $(self).closest('.item-row').find('.product_unit_id').val(productUnitId);
                            $(self).closest('.item-row').find('.product_unit_name').val(productUnitName);
                            if(productUnitName =="Hrs"){
                                amount=0;
                            }if(marginedAmount>0){
                                $(self).closest('.item-row').find('.amount').val(decimalupto2(marginedAmount));
                                $(self).closest('.item-row').find('.cost').val(decimalupto2(marginedAmount));
                                // $(self).closest('.item-row').find('.amount-html').html(decimalupto2(marginedAmount));
                                $(self).closest('.item-row').find('.amount-html').html(number_format(marginedAmount, 2, ',', '.'));

                                // $(self).closest('.item-row').find('.cost-html').html(decimalupto2(marginedAmount));
                                $(self).closest('.item-row').find('.cost-html').html(number_format(marginedAmount, 2, ',', '.'));
                            }else
                            {
                                $(self).closest('.item-row').find('.amount').val(decimalupto2(amount));
                                $(self).closest('.item-row').find('.cost').val(decimalupto2(amount));

                                // $(self).closest('.item-row').find('.amount-html').html(decimalupto2(amount));
                                $(self).closest('.item-row').find('.amount-html').html(number_format(amount, 2, ',', '.'));

                                // $(self).closest('.item-row').find('.cost-html').html(decimalupto2(amount));
                                $(self).closest('.item-row').find('.cost-html').html(number_format(amount, 2, ',', '.'));
                            }
                            if(productUnitName =="Hrs"){
                                $(self).closest('.item-row').find('.amount-html').html('');
                                $(self).closest('.item-row').find('.cost-html').html('');
                            }
                            calculateEstimageTotal();
                        }
                    }
                })
            }
        });
        console.log(i_multi_expenses);
        $('#expense_category_id' + i).change(function (e) {
            e.preventDefault();
            let categoryId = $(this).val();
            let url = "{{ route('get_expense_by_categories', ':id') }}";
           alert('okay_id');
            url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        // console.log("inside expense data", rData);
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option value="' + value.id + '">' + value
                                .item_name + '</option>';
                            options.push(selectData);
                        });

                        $(self).closest('.item-row').find('select.add_expense').html('<option value="0" selected>@lang('app.selectExpense')</option>' + options);
                        $(self).closest('.item-row').find('select.add_expense').selectpicker('refresh');
                    }
                }
            })
        });

        // Change Expense type
        $('#add_expense' + i).on('changed.bs.select', function(e) {
            e.stopImmediatePropagation();
            var id = $(this).val();
            let expenseUrl = "{{ route('get_expense', ':id') }}";
            expenseUrl = (id) ? expenseUrl.replace(':id', id) : expenseUrl.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: expenseUrl,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var expensePrice;
                        rData = response.data;
                        // console.log('expense data', rData);
                        expenseId =rData[0].id;
                        expenseName =rData[0].item_name;
                        expensePrice =rData[0].price;
                        $(self).closest('tr').find('.expense_item_id').val(expenseId);
                        $(self).closest('tr').find('.expense_item_name').val(expenseName);

                                    //Create array of input values
                                    var arr = $("#saveInvoiceForm .expense_item_id").map(function() {
                                        if ($(this).val() != '') return $(this).val()
                                    }).get();
                                    console.log('expense id array', arr);
                                    //Create array of duplicates if there are any
                                    var unique = arr.filter(function(item, pos) {
                                        return arr.indexOf(item) != pos;
                                    });
                                    console.log('unique data', unique);
                                    //show/hide error msg
                                    // (unique.length != 0) ? $('#duplicate-error' + i).toggleClass('d-none') : $('.duplicate-error').addClass('d-none');

                        // var itemTax = $(self).closest(".item-row").find("input.expnese_tax").data("expense-tax-rate");
                        // console.log('expense tax',itemTax);
                        // var taxAmount = expensePrice * (itemTax / 100);

                        var marginValue = $(self).closest('.item-row').find('.expense_margin_percent').val();
                        if (isNaN(marginValue)) {
                            marginValue = 0;
                        }
                        var marginedAmount = 0;
                        if (marginValue != "") {
                            marginAmount = (parseFloat(expensePrice) / 100) * parseFloat(marginValue);
                            $(self).closest('.item-row').find('.expense-margin').val(decimalupto2(marginAmount));
                            // $(self).closest('.item-row').find('.expense-margin-html').html(decimalupto2(marginAmount));
                            $(self).closest('.item-row').find('.expense-margin-html').html(number_format(marginAmount, 2, ',', '.'));
                            marginedAmount = parseFloat(expensePrice) + parseFloat(marginAmount);
                            console.log(marginedAmount);
                        }
                        var itemTax = $(self).closest(".item-row").find("input.expnese_tax").data("expense-tax-rate");
                        // console.log('expense tax rate',itemTax);
                        var taxAmount = parseFloat(marginedAmount) * (parseFloat(itemTax) / 100);
                        // console.log('expense tax',taxAmount);

                        $(self).closest('.item-row').find('.expense_price').val(decimalupto2(expensePrice));
                        $(self).closest('.item-row').find('.expense-tax-amount').val(decimalupto2(taxAmount));
                        if (isNaN(marginedAmount)) {
                            marginedAmount = 0;
                        }
                        if(marginedAmount > 0){
                            $(self).closest('.item-row').find('.expense-amount').val(decimalupto2(marginedAmount));
                            // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(marginedAmount));
                            $(self).closest('.item-row').find('.expense-amount-html').html(number_format(marginedAmount, 2, ',', '.'));
                        } else {
                            $(self).closest('.item-row').find('.expense-amount').val(decimalupto2(expensePrice));
                            // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(expensePrice));
                            $(self).closest('.item-row').find('.expense-amount-html').html(number_format(expensePrice, 2, ',', '.'));
                        }

                        $(self).closest('.item-row').find('.expense-cost').val(decimalupto2(expensePrice));
                        // $(self).closest('.item-row').find('.expense-cost-html').html(decimalupto2(expensePrice));
                        $(self).closest('.item-row').find('.expense-cost-html').html(number_format(expensePrice, 2, ',', '.'));

                        calculateEstimageTotal();
                    }
                }
            })
        });


        });

        $('#project_category_id').change(function (e) {
            let categoryId = $(this).val();
            let url = "{{ route('get_project_sub_categories', ':id') }}";

            url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option value="' + value.id + '">' + value
                                .category_name + '</option>';
                            options.push(selectData);
                        });

                        $('#project_sub_category_id').html('<option value="0">Select Project Sub Category</option>' + options);
                        $('#project_sub_category_id').selectpicker('refresh');
                    }
                }
            })
        });

        $('.product_category_id').change(function (e) {
            let categoryId = $(this).val();
            let catname = $(this).closest('.item-row').find('.product_category_id option:selected').data("content");
            console.log("product cat name",catname);
            $(this).closest('.item-row').find('.product_category_name').val(catname);
            let url = "{{ route('get_product_sub_categories', ':id') }}";

            url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option data-content="'+ value
                                .category_name +'" value="' + value.id + '">' + value
                                .category_name + '</option>';
                            options.push(selectData);
                        });

                        $(self).closest('.item-row').find('select.product_sub_category_id').html('<option value="">Select Service Sub Category</option>' + options);
                        $(self).closest('.item-row').find('select.product_sub_category_id').selectpicker('refresh');
                    }
                }
            })
        });

        $('.product_sub_category_id').change(function (e) {
            let subCatId = $(this).val();
            let subCatname = $(this).closest('.item-row').find('.product_sub_category_id option:selected').data("content");
            console.log("product sub cat name", subCatname);
            $(this).closest('.item-row').find('.product_sub_category_name').val(subCatname);
            let url = "{{ route('get_product_by_sub_category', ':id') }}";

            url = (subCatId) ? url.replace(':id', subCatId) : url.replace(':id', null);

            var self = this;
            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData;
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData;
                            selectData = '<option data-content="'+ value
                                .name +'" value="' + value.id + '">' + value
                                .name + '</option>';
                            options.push(selectData);
                        });

                        $(self).closest('.item-row').find('select.add-products').html('<option value="">Select Product</option>' + options);
                        $(self).closest('.item-row').find('select.add-products').selectpicker('refresh');
                    }
                }
            })
        });


        // Change service type
        $('.add-products').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation()
            var id = $(this).val();
            if (previousValue != id && id != ''){
                let producturl = "{{ route('get_product', ':id') }}";
                producturl = (id) ? producturl.replace(':id', id) : producturl.replace(':id', null);

                var self = this;
                $.easyAjax({
                    url: producturl,
                    type: "GET",
                    success: function (response) {
                        if (response.status == 'success') {
                            var productId;
                            var productPrice;
                            rData = response.data;
                            // console.log("product data",rData);
                            productId =rData[0].id;
                            productName =rData[0].name;
                            $(self).closest('.item-row').find('.item_name').val(productName);
                            productPrice =rData[0].price;
                            var productUnitId =rData[0].unit_id;
                            var productUnitName =rData[0].unit_name;
                            if(productUnitName =="Fixed Price" || productUnitName =="Hrs"){
                                $(self).closest('.item-row').find('input.unit_type_quantity').hide();
                            }else{
                                $(self).closest('.item-row').find('input.unit_type_quantity').show();
                            }
                            console.log("Unit id",productUnitId);
                            console.log("Unit name",productUnitName);
                            // var itemTax=[];
                            // $(self).closest(".item-row").find("select.type:first option:selected")
                            // .each(function (index) {
                            //     itemTax[index] = $(this).data("rate");
                            // });
                            // console.log(itemTax[0]);
                            $(self).closest('.item-row').find('.cost_per_item').val(decimalupto2(productPrice));
                            var quantity = $(self).closest('.item-row').find('.quantity').val();
                            var perItemCost = $(self).closest('.item-row').find('.cost_per_item').val();
                            var amount = (quantity * perItemCost);
                            // var taxAmount = itemTax[0] * (amount / 100);
                            // console.log(taxAmount);
                            // var amountWithTax = amount + taxAmount;
                            var marginValue = $(self).closest('.item-row').find('.service_margin_percent').val();
                            if(productUnitName =="Hrs"){
                                $(self).closest('.item-row').find('.service_margin_percent').val(0);
                                marginValue = $(self).closest('.item-row').find('.service_margin_percent').val();
                            }
                            var marginedAmount = 0;
                            if (marginValue != "") {
                                marginAmount = (parseFloat(amount) / 100) * parseFloat(marginValue);
                                if(productUnitName =="Hrs"){
                                    marginAmount=0;
                                }
                                $(self).closest('.item-row').find('.margin').val(decimalupto2(marginAmount));

                                if(productUnitName =="Hrs"){
                                    $(self).closest('.item-row').find('.margin-html').html('');
                                }else{
                                    // $(self).closest('.item-row').find('.margin-html').html(decimalupto2(marginAmount));
                                    $(self).closest('.item-row').find('.margin-html').html(number_format(marginAmount, 2, ',', '.'));                                }
                                marginedAmount = parseFloat(amount + marginAmount);
                            }
                            $(self).closest('.item-row').find('.product_unit_name_html').html(productUnitName);
                            $(self).closest('.item-row').find('.product_id').val(productId);
                            $(self).closest('.item-row').find('.product_unit_id').val(productUnitId);
                            $(self).closest('.item-row').find('.product_unit_name').val(productUnitName);
                            if(productUnitName =="Hrs"){
                                amount=0;
                            }if(marginedAmount>0){
                                $(self).closest('.item-row').find('.amount').val(decimalupto2(marginedAmount));
                                $(self).closest('.item-row').find('.cost').val(decimalupto2(marginedAmount));

                                // $(self).closest('.item-row').find('.amount-html').html(decimalupto2(marginedAmount));
                                // $(self).closest('.item-row').find('.cost-html').html(decimalupto2(marginedAmount));
                                $(self).closest('.item-row').find('.amount-html').html(number_format(marginedAmount, 2, ',', '.'));
                                $(self).closest('.item-row').find('.cost-html').html(number_format(marginedAmount, 2, ',', '.'));
                            }else
                            {
                                $(self).closest('.item-row').find('.amount').val(decimalupto2(amount));
                                $(self).closest('.item-row').find('.cost').val(decimalupto2(amount));
                                // $(self).closest('.item-row').find('.amount-html').html(decimalupto2(amount));
                                // $(self).closest('.item-row').find('.cost-html').html(decimalupto2(amount));

                                $(self).closest('.item-row').find('.amount-html').html(number_format(amount, 2, ',', '.'));
                                $(self).closest('.item-row').find('.cost-html').html(number_format(amount, 2, ',', '.'));
                            }
                            if(productUnitName =="Hrs"){
                                $(self).closest('.item-row').find('.amount-html').html('');
                                $(self).closest('.item-row').find('.cost-html').html('');
                            }
                            calculateEstimageTotal();
                        }
                    }
                })
            }
        });

        {{--$('.expense_category_id').change(function (e) {--}}
        {{--    let categoryId = $(this).val();--}}
        {{--    let url = "{{ route('get_expense_by_categories', ':id') }}";--}}
        {{--    alert('okay not');--}}
        {{--    url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);--}}

        {{--    var self = this;--}}
        {{--    $.easyAjax({--}}
        {{--        url: url,--}}
        {{--        type: "GET",--}}
        {{--        success: function (response) {--}}
        {{--            if (response.status == 'success') {--}}
        {{--                var options = [];--}}
        {{--                var rData;--}}
        {{--                rData = response.data;--}}
        {{--                $.each(rData, function (index, value) {--}}
        {{--                    var selectData;--}}
        {{--                    selectData = '<option value="' + value.id + '">' + value--}}
        {{--                        .item_name + '</option>';--}}
        {{--                    options.push(selectData);--}}
        {{--                });--}}

        {{--                $(self).closest('.item-row').find('select.add_expense').html('<option value="0" selected>@lang('app.selectExpense')</option>' + options);--}}
        {{--                $(self).closest('.item-row').find('select.add_expense').selectpicker('refresh');--}}
        {{--            }--}}
        {{--        }--}}
        {{--    })--}}
        {{--});--}}

        $(document).ready(function() {
            $(document).on('change', '.expense_category_id', function() {
                var selectedValue = $(this).val();
                console.log('Selected value: ' + selectedValue);
                // Your event handling logic here
            });
        });
        // Change Expense type
        $('.add_expense').on('changed.bs.select', function(e) {
            e.stopImmediatePropagation()
            var id = $(this).val();
            let expenseUrl = "{{ route('get_expense', ':id') }}";
            expenseUrl = (id) ? expenseUrl.replace(':id', id) : expenseUrl.replace(':id', null);
            var self = this;
            $.easyAjax({
                url: expenseUrl,
                type: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        var expensePrice;
                        rData = response.data;
                        // console.log('expense data', rData);
                        expenseId =rData[0].id;
                        expenseName =rData[0].item_name;
                        expensePrice =rData[0].price;
                        $(self).closest('.item-row').find('.expense_item_id').val(expenseId);
                        $(self).closest('.item-row').find('.expense_item_name').val(expenseName);
                                    //Create array of input values
                                    var arr = $("#saveInvoiceForm .expense_item_id").map(function() {
                                        if ($(this).val() != '') return $(this).val()
                                    }).get();
                                    console.log('expense id array', arr);
                                    //Create array of duplicates if there are any
                                    var unique = arr.filter(function(item, pos) {
                                        return arr.indexOf(item) != pos;
                                    });
                                    console.log('unique data', unique);
                                    //show/hide error msg
                                    // (unique.length != 0) ? $('#duplicate-error').toggleClass('d-none') : $('.duplicate-error').addClass('d-none');
                        // var itemTax = $(self).closest(".item-row").find("input.expnese_tax").data("expense-tax-rate");
                        // console.log('expense tax',itemTax);
                        // var taxAmount = expensePrice * (itemTax / 100);

                        var marginValue = $(self).closest('.item-row').find('.expense_margin_percent').val();
                        if (isNaN(marginValue)) {
                            marginValue = 0;
                        }
                        var marginedAmount = 0;
                        if (marginValue != "") {
                            marginAmount = (parseFloat(expensePrice) / 100) * parseFloat(marginValue);
                            $(self).closest('.item-row').find('.expense-margin').val(decimalupto2(marginAmount));
                            // $(self).closest('.item-row').find('.expense-margin-html').html(decimalupto2(marginAmount));
                            $(self).closest('.item-row').find('.expense-margin-html').html(number_format(marginAmount, 2, ',', '.'));

                            marginedAmount = parseFloat(expensePrice) + parseFloat(marginAmount);
                            console.log(marginedAmount);
                        }
                        var itemTax = $(self).closest(".item-row").find("input.expnese_tax").data("expense-tax-rate");
                        // console.log('expense tax rate',itemTax);
                        var taxAmount = parseFloat(marginedAmount) * (parseFloat(itemTax) / 100);
                        // console.log('expense tax',taxAmount);

                        $(self).closest('.item-row').find('.expense_price').val(decimalupto2(expensePrice));
                        $(self).closest('.item-row').find('.expense-tax-amount').val(decimalupto2(taxAmount));
                        if (isNaN(marginedAmount)) {
                            marginedAmount = 0;
                        }
                        if(marginedAmount > 0){
                            $(self).closest('.item-row').find('.expense-amount').val(decimalupto2(marginedAmount));
                            // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(marginedAmount));
                            $(self).closest('.item-row').find('.expense-amount-html').html(number_format(marginedAmount, 2, ',', '.'));
                        } else {
                            $(self).closest('.item-row').find('.expense-amount').val(decimalupto2(expensePrice));
                            // $(self).closest('.item-row').find('.expense-amount-html').html(decimalupto2(expensePrice));
                            $(self).closest('.item-row').find('.expense-amount-html').html(number_format(expensePrice, 2, ',', '.'));
                        }

                        $(self).closest('.item-row').find('.expense-cost').val(decimalupto2(expensePrice));
                        // $(self).closest('.item-row').find('.expense-cost-html').html(decimalupto2(expensePrice));
                        $(self).closest('.item-row').find('.expense-cost-html').html(number_format(expensePrice, 2, ',', '.'));

                        calculateEstimageTotal();
                    }
                }
            })
        });
        $('#saveInvoiceForm').on('input', '.expense_margin_percent', function() {
            // var marginValue = $(this).closest('.item-row').find('.expense_margin_percent').val();
            // var expensePrice = $(this).closest('.item-row').find('.expense_price').val();
            var marginValue = $(this).val();
            var $expensePriceInput = $(this).closest('td').prev('td').find('.expense_price');

            // Now you can access the value of the expense_price input
            var expensePrice = $expensePriceInput.val();
            console.log(marginValue,expensePrice);
            if (isNaN(marginValue)) {
                marginValue = 0;
                $(this).closest('.item-row').find('.margin').val(0);
                $(this).closest('.item-row').find('.margin-html').html('€0.00');
            }

            var marginAmount = 0;
            var marginedAmount = 0;
            if (marginValue != "" && marginValue > 0) {
                marginAmount = (parseFloat(expensePrice) / 100) * parseFloat(marginValue);
                marginedAmount = parseFloat(expensePrice) + parseFloat(marginAmount);
                console.log(marginedAmount);
            }
            if (isNaN(marginAmount) || marginAmount == 0) {
                $(this).closest('tr').find('.expense-margin').val(0);
                $(this).closest('tr').find('.expense-margin-html').html('€0.00');
            } else {
                $(this).closest('tr').find('.expense-margin').val(decimalupto2(marginAmount));
                // $(this).closest('.item-row').find('.expense-margin-html').html(decimalupto2(marginAmount));
                $(this).closest('tr').find('.expense-margin-html').html(number_format(marginAmount, 2, ',', '.'));
            }
            var itemTax = $(this).closest('tr').find("input.expnese_tax").data("expense-tax-rate");
            // console.log('expense tax rate',itemTax);
            var taxAmount = parseFloat(marginedAmount) * (parseFloat(itemTax) / 100);
            // console.log('expense tax',taxAmount);
            $(this).closest('tr').find('.expense-tax-amount').val(decimalupto2(taxAmount));
            if(marginedAmount > 0) {
                $(this).closest('tr').find('.expense-amount').val(decimalupto2(marginedAmount));
                // $(this).closest('.item-row').find('.expense-amount-html').html(decimalupto2(marginedAmount));
                $(this).closest('tr').find('.expense-amount-html').html(number_format(marginedAmount, 2, ',', '.'));
            }else {
                $(this).closest('tr').find('.expense-amount').val(decimalupto2(expensePrice));
                // $(this).closest('.item-row').find('.expense-amount-html').html(decimalupto2(expensePrice));
                $(this).closest('tr').find('.expense-amount-html').html(number_format(expensePrice, 2, ',', '.'));
            }


            calculateEstimageTotal();
        });

        $('#saveInvoiceForm').on('input', '.service_margin_percent', function() {
            var productUnitName = $(this).closest('.item-row').find('.product_unit_name').val();
            var marginValue = $(this).closest('.item-row').find('.service_margin_percent').val();
            if (isNaN(marginValue)) {
                marginValue = 0;
                $(this).closest('.item-row').find('.margin').val(0);
                $(this).closest('.item-row').find('.margin-html').html('€0.00');
            }
            if(productUnitName =="Hrs"){
                amount = 0;
            }
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            if(productUnitName =="Hrs"){
                perItemCost = 0;
                marginValue = 0;
            }
            var amountValue = (quantity * perItemCost);
            $(this).closest('.item-row').find('.cost').val(decimalupto2(amountValue));
            if(productUnitName =="Hrs"){
                $(this).closest('.item-row').find('.cost-html').html('');
            }else{
                // $(this).closest('.item-row').find('.cost-html').html(decimalupto2(amountValue));
                $(this).closest('.item-row').find('.cost-html').html(number_format(amountValue, 2, ',', '.'));
            }
            var marginAmount = 0;
            var marginedAmount = 0;
            if (marginValue != "" && marginValue > 0) {
                marginAmount = (parseFloat(amountValue) / 100) * parseFloat(marginValue);
                marginedAmount = parseFloat(amountValue) + parseFloat(marginAmount);
            }
            if (isNaN(marginAmount) || marginAmount == 0) {
                $(this).closest('.item-row').find('.margin').val(0);
                $(this).closest('.item-row').find('.margin-html').html('€0.00');
            } else {
                $(this).closest('.item-row').find('.margin').val(decimalupto2(marginAmount));
                // $(this).closest('.item-row').find('.margin-html').html(decimalupto2(marginAmount));
                $(this).closest('.item-row').find('.margin-html').html(number_format(marginAmount, 2, ',', '.'));
            }
            if(marginedAmount > 0) {
                $(this).closest('.item-row').find('.amount').val(decimalupto2(marginedAmount));
                // $(this).closest('.item-row').find('.amount-html').html(decimalupto2(marginedAmount));
                $(this).closest('.item-row').find('.amount-html').html(number_format(marginedAmount, 2, ',', '.'));
            }else {
                $(this).closest('.item-row').find('.amount').val(decimalupto2(amountValue));
                // $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amountValue));
                $(this).closest('.item-row').find('.amount-html').html(number_format(amountValue, 2, ',', '.'));
            }
            if(productUnitName =="Hrs") {
                $(this).closest('.item-row').find('.margin-html').html('');
                $(this).closest('.item-row').find('.amount-html').html('');
            }
            calculateEstimageTotal();
        });

        $('#saveInvoiceForm').on('click', '.remove-item', function() {
            $(this).closest('.item-row').fadeOut(300, function() {
                $(this).remove();
                $('select.customSequence').each(function(index) {
                    $(this).attr('name', 'taxes[' + index + '][]');
                    $(this).attr('id', 'multiselect' + index + '');
                });
                calculateEstimageTotal();
            });
        });

        $('.save-form').click(function() {
            let note = document.getElementById('description').children[0].innerHTML;
            document.getElementById('description-text').value = note;

            var type = $(this).data('type');

            if (KTUtil.isMobileDevice()) {
                $('.desktop-description').remove();
            } else {
                $('.mobile-description').remove();
            }

            calculateEstimageTotal();

            var discount = $('.discount_amount').html();
            var total = $('.sub-total-field').val();

            if (parseFloat(discount) > parseFloat(total)) {
                Swal.fire({
                    icon: 'error',
                    text: "{{ __('messages.discountExceed') }}",

                    customClass: {
                        confirmButton: 'btn btn-primary',
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                });

                return false;
            }

            $.easyAjax({
                url: "{{ route('estimates.store') }}?type=" + type,
                container: '#saveInvoiceForm',
                type: "POST",
                blockUI: true,
                file: true,
                data: $('#saveInvoiceForm').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            })
        });

        $('#saveInvoiceForm').on('click', '.remove-item', function() {
            $(this).closest('.item-row').fadeOut(300, function() {
                $(this).remove();
                $('select.customSequence').each(function(index) {
                    $(this).attr('name', 'taxes[' + index + '][]');
                    $(this).attr('id', 'multiselect' + index + '');
                });
                calculateEstimageTotal();
            });
        });

        $('#saveInvoiceForm').on('keyup', '.quantity,.cost_per_item,.item_name, .discount_value', function() {
            var marginValue = $(this).closest('.item-row').find('.service_margin_percent').val();
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amountValue = (quantity * perItemCost);
            $(this).closest('.item-row').find('.cost').val(decimalupto2(amountValue));
            // $(this).closest('.item-row').find('.cost-html').html(decimalupto2(amountValue));
            $(this).closest('.item-row').find('.cost-html').html(number_format(amountValue, 2, ',', '.'));
            var marginedAmount = 0;
            if (marginValue != "") {
                marginAmount = (parseFloat(amountValue) / 100) * parseFloat(marginValue);
                $(this).closest('.item-row').find('.margin').val(decimalupto2(marginAmount));
                // $(this).closest('.item-row').find('.margin-html').html(decimalupto2(marginAmount));
                $(this).closest('.item-row').find('.margin-html').html(number_format(marginAmount, 2, ',', '.'));
                marginedAmount = parseFloat(amountValue + marginAmount);
            }
            $(this).closest('.item-row').find('.amount').val(decimalupto2(marginedAmount));
            // $(this).closest('.item-row').find('.amount-html').html(decimalupto2(marginedAmount));
            $(this).closest('.item-row').find('.amount-html').html(number_format(marginedAmount, 2, ',', '.'));

            calculateEstimageTotal();
        });

        $('#saveInvoiceForm').on('change', '.type, #discount_type, #calculate_tax', function() {
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amount = (quantity * perItemCost);

            $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
            // $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));
            $(this).closest('.item-row').find('.amount-html').html(number_format(amount, 2, ',', '.'));

            calculateEstimageTotal();
        });

        $('#saveInvoiceForm').on('input', '.quantity', function() {
            var marginValue = $(this).closest('.item-row').find('.service_margin_percent').val();
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amountValue = (quantity * perItemCost);
            $(this).closest('.item-row').find('.cost').val(decimalupto2(amountValue));
            // $(this).closest('.item-row').find('.cost-html').html(decimalupto2(amountValue));
            $(this).closest('.item-row').find('.cost-html').html(number_format(amountValue, 2, ',', '.'));
            var marginedAmount = 0;
            if (marginValue != "") {
                marginAmount = (parseFloat(amountValue) / 100) * parseFloat(marginValue);
                $(this).closest('.item-row').find('.margin').val(decimalupto2(marginAmount));
                // $(this).closest('.item-row').find('.margin-html').html(decimalupto2(marginAmount));
                $(this).closest('.item-row').find('.margin-html').html(number_format(marginAmount, 2, ',', '.'));
                marginedAmount = parseFloat(amountValue + marginAmount);
            }
            $(this).closest('.item-row').find('.amount').val(decimalupto2(marginedAmount));
            // $(this).closest('.item-row').find('.amount-html').html(decimalupto2(marginedAmount));
            $(this).closest('.item-row').find('.amount-html').html(number_format(marginedAmount, 2, ',', '.'));

            calculateEstimageTotal();
        });

    function calculateEstimageTotal() {
        var subtotal = 0;
        var totalMargin = 0;
        var totalHours = 0;
        var expenseSubtotal = 0;
        var totalExpenseMargin = 0;
        var discount = 0;
        var tax = "";
        var taxList = new Object();
        var taxTotal = 0;
        var expenseTaxTotal = 0;
        var discountAmount = 0;
        var discountValue = $(".discount_value").val();
        var calculateTax = $("#calculate_tax").val();
        var adjustmentAmount = $("#adjustment_amount").val();

        $(".quantity").each(function (index, element) {
            var discountedAmount = 0;
            var amount = parseFloat(
                $(this).closest(".item-row").find(".amount").val()
            );
            if (isNaN(amount)) {
                amount = 0;
            }
            var margin = parseFloat(
                $(this).closest(".item-row").find(".margin").val()
            );
            if (isNaN(margin)) {
                margin = 0;
            }
            totalMargin = (parseFloat(totalMargin) + parseFloat(margin)).toFixed(2);
            subtotal = (parseFloat(subtotal) + parseFloat(amount)).toFixed(2);
            // $(".total-service-html").html(subtotal);
            $(".total-service-html").html(number_format(parseFloat(subtotal), 2, ',', '.'));
            $(".total_service").val(subtotal);


            var hours = parseFloat(
                $(this).closest(".item-row").find(".quantity").val()
            );
            if (isNaN(hours)) {
                hours = 0;
            }
            var productUnitName = $(this).closest('.item-row').find('.product_unit_name').val();
            if(productUnitName =="Fixed Price" || productUnitName =="Hrs"){
                hours = 0;
            }
            totalHours = (parseFloat(totalHours) + parseFloat(hours)).toFixed(2);
            // $(".total-hours-html").html(totalHours);
            $(".total-hours-html").html(number_format(totalHours, 2, ',', '.', false));
            $(".total_hours").val(totalHours);
        });

        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            subtotal = parseFloat(subtotal);

            $(this)
                .closest(".item-row")
                .find("select.type option:selected")
                .each(function (index) {
                    itemTax[index] = $(this).data("rate");
                    itemTaxName[index] = $(this).text();
                });
            var itemTaxId = $(this).closest(".item-row").find("select.type").val();

            var amount = parseFloat(
                $(this).closest(".item-row").find(".amount").val()
            );
            if (isNaN(amount)) {
                amount = 0;
            }
            if (itemTaxId != "") {
                for (var i = 0; i <= itemTaxName.length; i++) {
                    if (typeof taxList[itemTaxName[i]] === "undefined") {

                            var taxValue = amount * (parseFloat(itemTax[i]) / 100);
                            if (!isNaN(taxValue)) {
                                taxList[itemTaxName[i]] = parseFloat(taxValue);
                            }
                    } else {
                        var taxValue =
                            parseFloat(taxList[itemTaxName[i]]) +
                            amount * (parseFloat(itemTax[i]) / 100);

                        if (!isNaN(taxValue)) {
                            taxList[itemTaxName[i]] = parseFloat(taxValue);
                        }
                    }
                }
            }
        });


        $(".expense-amount").each(function (index, element) {
            console.log($(this).val());
            var expenseAmount = parseFloat($(this).closest('tr').find(".expense-amount").val());
            if (isNaN(expenseAmount)) {
                expenseAmount = 0;
            }
            var expenseMargin = parseFloat($(this).closest('tr').find(".expense-margin").val());
            if (isNaN(expenseMargin)) {
                expenseMargin = 0;
            }
            totalExpenseMargin = (parseFloat(totalExpenseMargin) + parseFloat(expenseMargin)).toFixed(2);
            expenseSubtotal = (parseFloat(expenseSubtotal) + parseFloat(expenseAmount)).toFixed(2);
            // $(".total-expense-html").html(expenseSubtotal);
            $(".total-expense-html").html(number_format(expenseSubtotal, 2, ',', '.'));
            $(".total_expense").val(expenseSubtotal);

            var expenseTax = parseFloat($(this).closest(".item-row").find(".expense-tax-amount").val());
            if (isNaN(expenseTax)) {
                expenseTax = 0;
            }
            expenseTaxTotal = (parseFloat(expenseTaxTotal) + parseFloat(expenseTax)).toFixed(2);
        });

        if (isNaN(totalExpenseMargin)) {
            totalExpenseMargin = 0;
        }

        var expServSubTotal = parseFloat(subtotal) + parseFloat(expenseSubtotal);
        var expServTotalMargin = parseFloat(totalMargin)+ parseFloat(totalExpenseMargin) ;

        if(expenseSubtotal == 0){
            var totalCost = 0;
            totalCost = parseFloat(subtotal) - parseFloat(totalMargin);
            // $(".total-cost-html").html(totalCost);
            $(".total-cost-html").html(number_format(totalCost, 2, ',', '.'));
            $(".total_cost").val(totalCost);
            var totalMarginPercent = (parseFloat(totalMargin) * 100) / parseFloat(totalCost);
        }else {
            var totalCost = 0;
            totalCost = parseFloat(expServSubTotal) - parseFloat(expServTotalMargin);
            // $(".total-cost-html").html(totalCost);
            $(".total-cost-html").html(number_format(totalCost, 2, ',', '.'));
            $(".total_cost").val(totalCost);
            var totalMarginPercent = (parseFloat(expServTotalMargin) * 100) / parseFloat(totalCost);
        }

        if (isNaN(totalMarginPercent)) {
            totalMarginPercent = 0;
        }

        $.each(taxList, function (key, value) {
            if (!isNaN(value)) {
                tax ='<tr><td class="text-dark-grey" style="width: 34%;">' +
                    key +
                    '</td><td><span class="tax-percent">0.00'
                    "</span></td></tr>";
                taxTotal = taxTotal + decimalupto2(value);
            }
        });

        if (isNaN(expenseTaxTotal)) {
            expenseTaxTotal = 0;
        }
        var expServTaxTotal = parseFloat(taxTotal) + parseFloat(expenseTaxTotal);

        if (isNaN(expServSubTotal)) {
            expServSubTotal = 0;
        }
        if (isNaN(expServTotalMargin)) {
            expServTotalMargin = 0;
        }

        // var subTotalWithoutMargin = parseFloat(expServSubTotal) - parseFloat(expServTotalMargin);
        // if (isNaN(subTotalWithoutMargin)) {
        //     subTotalWithoutMargin = 0;
        // }


        // $(".sub-total").html(decimalupto2(expServSubTotal).toFixed(2));
        $(".sub-total").html(number_format(expServSubTotal, 2, ',', '.'));
        $(".sub-total-field").val(decimalupto2(decimalupto2(expServSubTotal).toFixed(2)));
        // $(".total-estimate-html").html(decimalupto2(expServSubTotal).toFixed(2));
        $(".total-estimate-html").html(number_format(expServSubTotal, 2, ',', '.'));

        $("#total_margin_amount_html").html(decimalupto2(expServTotalMargin).toFixed(2));
        $(".total_margin_amount").val(decimalupto2(expServTotalMargin));
        $(".total_margin_percent_html").html(decimalupto2(totalMarginPercent).toFixed(2));
        $(".total_margin_percent").val(decimalupto2(totalMarginPercent));
        // $(".total-margin-value").val(decimalupto2(totalMargin).toFixed(2));

        // $(".total-margin-html").html(decimalupto2(expServTotalMargin).toFixed(2));
        $(".total-margin-html").html(number_format(expServTotalMargin, 2, ',', '.'));
        // $(".total-margin-percent-html").html(decimalupto2(totalMarginPercent).toFixed(2));
        $(".total-margin-percent-html").html(number_format(totalMarginPercent, 2, ',', '.'));

        if (tax != "") {
            $("#invoice-taxes").html(tax);
            // $(".tax-percent").html(decimalupto2(expServTaxTotal));
            $(".tax-percent").html(number_format(expServTaxTotal, 2, ',', '.'));
            $(".total_tax_amount").val(decimalupto2(expServTaxTotal));

        } else {
            $("#invoice-taxes").html(
                '<tr><td colspan="2"><span class="tax-percent">0.00</span></td></tr>'
            );
        }

        if (adjustmentAmount && adjustmentAmount != 0 && adjustmentAmount != '') {
            expServSubTotal = expServSubTotal + parseFloat(adjustmentAmount);
        }

        var total = decimalupto2(expServSubTotal + expServTaxTotal);

        // $(".total").html(total.toFixed(2));
        $(".total").html(number_format(total, 2, ',', '.'));
        $(".total-field").val(total.toFixed(2));

    }


    number_format = function (number, decimals, dec_point, thousands_sep, symbol=true) {
        number = parseFloat(number);
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        if(!symbol)
            return x1 + x2;

        return "€"+x1 + x2;

    }

    calculateEstimageTotal();
    init(RIGHT_MODAL);
    });

    function ucWord(str) {
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
    }

    function checkboxChange(parentClass, id) {
    var checkedData = '';
    $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
        checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
    });
    $('#' + id).val(checkedData);
    }
    </script>
@endpush
