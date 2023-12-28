<!-- DESKTOP DESCRIPTION TABLE START -->
<div class="d-flex px-4 py-3 c-inv-desc item-row">

    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block ">
        <table width="100%">
            <tbody>
                <tr class="text-dark-grey font-weight-bold f-14">
                    <td width="{{ $invoiceSetting->hsn_sac_code_show ? '10%' : '20%' }}"
                        class="border-0 inv-desc-mbl btlr">@lang('app.productName')</td>
                    @if ($invoiceSetting->hsn_sac_code_show)
                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                        <td width="10%" class="border-0" align="right">@lang('app.hsnSac')</td>
                    @endif
                    <td width="20%" class="border-0">
                        @lang('modules.productCategory.productCategory')
                    </td>
                    <td width="20%" class="border-0">
                        @lang('modules.productCategory.productSubCategory')
                    </td>
                    <td width="10%" class="border-0" align="right">{{ isset($items->unit) ? $items->unit->unit_type : 'Qty\hrs' }}</td>
                    <td width="10%" class="border-0" align="right">@lang('modules.invoices.unitPrice')</td>
                    <td width="10%" class="border-0" align="right">@lang('modules.invoices.discount')%
                    </td>
                    <td width="14px" class="border-0" align="right">@lang('modules.invoices.tax')
                    </td>
                    <td width="14%" class="border-0 bblr-mbl" align="right">
                        @lang('modules.invoices.amount')
                    </td>
                </tr>
                <tr>
                    <td class="border-bottom-0 btrr-mbl btlr">
                        <input type="text" readonly class="form-control f-14 border-0 w-100 item_name" name="item_name[]"
                            placeholder="@lang('app.productName')" value="{{ $items->name }}">
                    </td>
                    <td class="border-bottom-0 btrr-mbl btlr">
                        <input type="text" readonly class="f-14 border-0 w-100 item_name form-control"
                            name="service_category[]" value="{{ $items->category->category_name }}" placeholder="@lang('modules.productCategory.productCategory')">
                    </td>
                    <td class="border-bottom-0 btrr-mbl btlr">
                        <input type="text" readonly class="f-14 border-0 w-100 item_name form-control"
                            name="service_sub_category[]" value="{{ $items->subCategory->category_name }}" placeholder="@lang('modules.productCategory.productSubCategory')">
                    </td>
                    <td class="border-bottom-0 d-block d-lg-none d-md-none">
                        <textarea class="form-control f-14 border-0 w-100 mobile-description" name="item_summary[]"
                            placeholder="@lang('placeholders.invoices.description')">{{ strip_tags($items->description) }}</textarea>
                    </td>
                    @if ($invoiceSetting->hsn_sac_code_show)
                        <td class="border-bottom-0">
                            <input type="text" min="1"
                                class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                data-item-id="{{ $items->id }}" value="{{ $items->hsn_sac_code }}"
                                name="hsn_sac_code[]">
                        </td>
                    @endif
                    <td class="border-bottom-0">
                        <input type="number" min="1"
                            class="form-control f-14 border-0 w-100 text-right quantity"
                            data-item-id="{{ $items->id }}" value="1" name="quantity[]">
                    </td>
                    <td class="border-bottom-0">
                        <input type="number" min="1"
                            class="f-14 border-0 w-100 text-right cost_per_item form-control"
                            data-item-id="{{ $items->id }}" placeholder="{{ $items->price }}"
                            value="{{ $items->price }}" name="cost_per_item[]">
                    </td>
                    <td class="border-bottom-0">
                        <input type="number" min="0"
                        name="service_discount_value[]"
                        class="f-14 border-0 w-100 text-right cost_per_item form-control"
                        placeholder="0">
                    </td>
                    <td class="border-bottom-0">
                        <div class="select-others height-35 rounded border-0">
                            <select id="multiselect" name="taxes[0][]" multiple="multiple"
                                class="select-picker type customSequence border-0" data-size="3">
                                @foreach ($taxes as $tax)
                                    <option data-rate="{{ $tax->rate_percent }}"
                                        @if (isset($items->taxes) && array_search($tax->id, json_decode($items->taxes)) !== false) selected @endif value="{{ $tax->id }}">
                                        {{ strtoupper($tax->tax_name) }}:
                                        {{ $tax->rate_percent }}%</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td rowspan="3" align="right" valign="top" class="bg-amt-grey btrr-bbrr">
                        <span class="amount-html" data-item-id="{{ $items->id }}">0.00</span>
                        <input type="hidden" class="amount" name="amount[]" data-item-id="{{ $items->id }}"
                            value="0">
                    </td>
                    <td rowspan="2" align="right" valign="middle" style="padding:8px; border: 0;">
                        <a href="javascript:;"
                        class="remove-item"><i
                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                    </td>
                </tr>
                <tr class="d-none d-md-table-row d-lg-table-row">
                    <td colspan="{{ $invoiceSetting->hsn_sac_code_show ? '7' : '6' }}" class="dash-border-top bblr">
                        <textarea class="form-control f-14 border-0 w-100 desktop-description" name="item_summary[]"
                            placeholder="@lang('placeholders.invoices.description')">{{ strip_tags($items->description) }}</textarea>
                    </td>

                    <td class="border-left-0">
                        <input type="file" class="dropify" id="dropify" name="invoice_item_image[]"
                            data-allowed-file-extensions="png jpg jpeg" data-messages-default="test" data-height="70"
                            data-default-file="{{ $items->image_url }}" />
                        <input type="hidden" name="invoice_item_image_url[]" value="{{ $items->image_url }}">
                    </td>
                </tr>
                <tr class="dash-border-top bblr">
                    <td colspan="8">
                        <a href="javascript:void(0);" style="font-size:14px !important;"
                        class="add_expense_btn btn btn-outline-secondary border-grey" data-toggle="tooltip"
                        data-original-title="{{ __('app.addExpense')}}">@lang('app.addExpense')</a>
                    </td>
                </tr>
                <tr class="add_expense_row dash-border-top bblr">
                    <td colspan="2" width="10%">
                        <div class="select-others height-35 rounded border-0">
                            <select class="form-control select-picker" required name="expense_id[]" id="expense_id"
                                data-live-search="true">
                                <option value="">@lang('app.selectExpense')</option>
                                @foreach ($expenses as $expense)
                                    <option value="{{ $expense->id }}">{{ mb_ucwords($expense->item_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td colspan="2" width="10%">
                        <div class="select-others height-35 rounded border-0">
                            <select class="form-control select-picker" name="expense_category_id[]" id="expense_category_id"
                                data-live-search="true">
                                <option value="">@lang('app.selectExpenseCategory')</option>
                                @foreach ($expenseCategories as $category)
                                    <option value="{{ $category->id }}">{{ mb_ucwords($category->category_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="number" min="1"
                            class="f-14 border-0 w-100 text-right expense_cost form-control"
                            placeholder="0.00" name="expense_cost[]">
                    </td>
                    <td>
                        <input type="number" min="0"
                        name="expense_discount_value[]"
                        class="f-14 border-0 w-100 text-right expense_discount_value form-control"
                        placeholder="0">
                    </td>
                    <td>
                    </td>
                    <td align="right" valign="middle" style="padding:8px; border: 0;">
                        <a href="javascript:;"
                        class="remove-expense remove_expense_btn"><i
                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- <a href="javascript:;" class="d-flex align-items-center justify-content-center ml-3 remove-item"><i
                class="fa fa-times-circle f-20 text-lightest"></i></a> --}}
    </div>

    <script>
    $(document).ready(function() {

        $('.add_expense_row').hide();

        $(document).on('click', '.add_expense_btn', function() {
            $(this).closest('tr').hide();
            $(this).closest('tr').next('tr.add_expense_row').show();
        });

        $(document).on('click', '.remove_expense_btn', function() {
            $(this).closest('tr.add_expense_row').hide();
            $(this).attr('name', 'expense_id[]');
            $(this).attr('name', 'expense_category_id[]');
            calculateTotal();
            $(this).closest('tr').prev('tr').show();
        });

        $('#expense_id').change(function (e) {
            let categoryId = $(this).val();
            let url = "{{ route('get_expense_categories', ':id') }}";

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

                        $('#expense_category_id').html(options);
                        $('#expense_category_id').selectpicker('refresh');
                    }
                }
            })
        });
    });

        $(function() {

            $(document).find('.dropify').dropify({
                messages: dropifyMessages
            });

            var quantity = $('#sortable').find('.quantity[data-item-id="{{ $items->id }}"]').val();
            var perItemCost = $('#sortable').find('.cost_per_item[data-item-id="{{ $items->id }}"]').val();
            var amount = (quantity * perItemCost);
            $('#sortable').find('.amount[data-item-id="{{ $items->id }}"]').val(amount);
            $('#sortable').find('.amount-html[data-item-id="{{ $items->id }}"]').html(amount);

            calculateTotal();
        });
    </script>

</div>
<!-- DESKTOP DESCRIPTION TABLE END -->
