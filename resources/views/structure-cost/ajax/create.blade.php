@php
    $addProductPermission = user()->permission('add_product');
@endphp

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
    /* width: 77% !important; */
}

</style>

<!-- CREATE INVOICE START -->
<div class="bg-white rounded b-shadow-4 create-inv">
    <!-- HEADING START -->
    <div class="px-lg-4 px-md-4 px-3 py-3">
        <h4 class="mb-0 f-21 font-weight-normal text-capitalize">@lang('app.structureCost') @lang('app.details')</h4>
    </div>
    <!-- HEADING END -->
    <hr class="m-0 border-top-grey">
    <!-- FORM START -->
    <x-form class="c-inv-form" id="saveInvoiceForm">
        <div class="row px-lg-4 px-md-4 px-3 py-3">
            <!-- INVOICE DATE START -->
            <div class="col-md-6 col-lg-4">
                <div class="form-group mb-4">
                    <x-forms.label fieldId="due_date" :fieldLabel="__('app.year')" fieldRequired="true">
                    </x-forms.label>
                    <div class="input-group">
                        <input type="number" id="year" name="year"
                            class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                            placeholder="e.g. 2023">
                    </div>
                </div>
            </div>
            <!-- INVOICE DATE END -->
        </div>
        <hr class="m-0 border-top-grey">
        <div id="sortable">
                <!-- DESKTOP DESCRIPTION TABLE START -->
                <div class="pl-2 py-0 c-inv-desc">

                    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                        <table width="50%">
                            <tbody>
                                <tr class="text-dark-grey font-weight-bold f-14">
                                    <td width="32%"class="border-0">
                                        @lang('app.costCategory')
                                    </td>
                                    <td width="30%" class="border-0">
                                        @lang('app.cost')
                                    </td>
                                    <td width="20%" class="border-0">
                                        @lang('modules.invoices.amount')
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex pl-2 pb-1 c-inv-desc item-row">
                    <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                        <table width="50%">
                            <tbody>
                                <tr>
                                    <td width="40%" class="btrr-mbl btlr">
                                        <div class="input-group">
                                            <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker cost_category_id" name="cost_category_id[]"
                                                        id="cost_category_id" data-live-search="true">
                                                    <option value="">{{ __('app.select') . ' ' . __('app.costCategory') }}</option>
                                                    @foreach ($costCategory as $category)
                                                        <option data-content="{{ $category->category_name }}" value="{{ $category->id }}">
                                                            {{ mb_ucwords($category->category_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="cost_category_name"
                                                name="cost_category_name[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addCostCategory" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('app.costCategory') }}">@lang('app.add')</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="40%" class="btrr-mbl btlr">
                                        <div class="input-group">
                                                <div class="dropdown bootstrap-select form-control select-picker">
                                                <select class="form-control select-picker item_id add-cost" data-live-search="true" data-size="8" id="add-cost" name="item_id[]">
                                                    <option value="">{{ __('app.select') . ' ' . __('app.cost') }}</option>
                                                    @foreach ($costs as $item)
                                                        <option data-content="{{ $item->name }}" value="{{ $item->id }}">
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden"
                                                class="item_name"
                                                name="item_name[]">
                                                <input type="hidden"
                                                class="cost_id"
                                                name="cost_id[]">
                                            </div>
                                            <div class="input-group-append">
                                                <a href="javascript:;"
                                                    class="btn btn-outline-secondary border-grey addCost" data-toggle="tooltip"
                                                    data-original-title="{{ __('app.add') . ' ' . __('app.cost') }}">@lang('app.add')</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="20%" align="right" valign="top" class="btrr-bbrr">
                                        <span class="amount-html">0.00</span>
                                        <input type="hidden" class="amount" name="amount[]" value="0">
                                    </td>
                                    <td width="8px" align="right" valign="middle" style="padding:8px; border: 0;">
                                        <a href="javascript:;"
                                        class="remove-item"><i
                                            class="fa fa-times-circle f-20 text-lightest"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- DESKTOP DESCRIPTION TABLE END -->
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
            <table width="48%" class="text-right f-14 text-capitalize">
                <tbody>
                    <tr>
                        <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                        <td width="50%" class="p-0 border-0 c-inv-total-right">
                            <table width="100%">
                                <tbody>
                                    <tr class="f-20 f-w-500">
                                        <td colspan="2">@lang('modules.invoices.total')</td>
                                        <td class="bg-amt-grey"><span class="total">0.00</span></td>
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


        <div class="pl-2 py-0 c-inv-desc">
            <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                <table width="48%">
                    <tbody>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="70%"class="border-0">
                                <x-forms.button-primary data-toggle="modal" data-target="#help-modal" class="help-btn mb-2" title="{{ __('app.help') }}" icon="help">@lang('app.help')
                                </x-forms.button-primary>
                            </td>
                            <td width="30%" class="border-0">
                            </td>
                        </tr>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="70%"class="border-0">
                                @lang('app.HourNumber')
                            </td>
                            <td width="30%" class="border-0">
                                <input required type="number" min="0"
                                        class="f-14 w-100 text-right hour-number form-control" name="hour_number"
                                        placeholder="0">
                            </td>
                        </tr>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="70%"class="border-0">
                                @lang('app.estimateEmployeeCost')
                            </td>
                            <td width="30%" class="border-0">
                                <input required type="number" min="0"
                                        class="f-14 w-100 text-right estimate-employee-cost form-control" name="estimate_employee_cost"
                                        placeholder="0">
                            </td>
                        </tr>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="70%"class="border-0">
                                @lang('app.estimatePercentageHour')
                            </td>
                            <td width="30%" class="border-0">
                                <div class="d-flex">
                                    <input  type="number" min="0" id="estimate-hour-percent"
                                    class="f-14 w-100 text-right estimate-hour-percent form-control" name="estimate_hour_percent"
                                    placeholder="0">
                                    <span class="mt-2 pl-1">%</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="m-0 border-top-grey mt-4">
        <div class="pl-2 py-0 c-inv-desc">
            <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                <table width="48%" class="">
                    <tbody>
                        <tr class="text-dark-grey font-weight-bold f-20">
                            <td width="70%"class="border-0">
                                @lang('app.hourlyStructureCost')
                            </td>
                            <td align="right" width="30%"class="f-20 f-w-500 bg-amt-grey">
                                <span class="total-structure-cost-html">0.00</span>
                                <input type="hidden" class="total-structure-cost" name="total_hourly_structure_cost" value="0">
                                {{-- <input readonly type="number" min="0"
                                class="f-14 w-100 text-right total-structure-cost form-control" name="total_hourly_structure_cost"
                                placeholder="0"> --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NOTE AND TERMS AND CONDITIONS START -->
        {{-- <div class="d-flex flex-wrap px-lg-4 px-md-4 px-3 py-3">
            <div class="col-md-6 col-sm-12 c-inv-note-terms p-0 mb-lg-0 mb-md-0 mb-3">
                <x-forms.label fieldId="" class="text-capitalize" :fieldLabel="__('app.help')">
                </x-forms.label>
                <textarea class="form-control" name="note" id="note" rows="4" placeholder="e.g. @lang('app.help')"></textarea>
            </div>
        </div> --}}
        <!-- NOTE AND TERMS AND CONDITIONS END -->
        <!-- CANCEL SAVE SEND START -->
        <div class="border-top-grey justify-content-start px-4 py-3 c-inv-btns hide-buttons mt-4">
            <div class="d-flex">
                <x-forms.button-primary data-type="save" class="save-form mr-3" icon="check">@lang('app.save')
                </x-forms.button-primary>
            </div>
            <x-forms.button-cancel :link="route('structureCost.index')" class="border-0">@lang('app.cancel')
            </x-forms.button-cancel>
        </div>
        <!-- CANCEL SAVE SEND END -->
    </x-form>
    <!-- FORM END -->
</div>
<!-- CREATE INVOICE END -->

<!-- also the modal itself -->
<div id="help-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center align-items-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modelHeading">@lang('app.help')</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <h4>Informazioni generali relative alla pagina</h4>
                <p>Ogni azienda o attività professionali ha dei costi di struttura da tener conto al fine di calcolo di un preventivo corretto. Escluderli significa falsificare le marginalità reali. E’ necessario, quindi, conoscere almeno in modo sintetico i costi di struttura al fine di tenerne conto nei preventivi. Esistono diverse metodologie per individuare la quota da attribuire ad un singolo preventivo. Ma è necessario lasciare flessibilità a chi lo compila per evitare valutazioni sbagliate alle specifiche situazione. Per queste ragioni abbiamo creato questo modulo che permette di inserire in modo semplificato i costi principali di struttura in modo flessibile previsti per l’anno in corso e pochi parametri che tengono conto dell’affettivo uso delle risorse. Questi parametri vengono elaborati con una formula proprietaria che calcola un costo ora basato sul potenziale utilizzo delle risorse durante l’anno. In pratica la formula ti dice quanto incide per ora lavorata mediamente il costo totale di struttura. E’ un metodo utilizzato anche dalle grandi multinazionali e risulta estremamente efficace. </p>

                <p> Il risultato (Costo di struttura orario) è una informazione utile da tenere in considerazione durante l’impostazione dei costi orari dei servizi oppure nei preventivI. In questo modo è la singola impresa o per specifico preventivo si può decidere se tenerne conto. A tal proposito proponiamo due esempi per due ipotesi di utilizzo del valore: Costo di struttura orario.</p>
                <p>- Utilizzo del costo di struttura orario nelle impostazione dei servizi. Decido di includere in tutti i preventivi il costo di struttura. Quindi quando creo un servizio che ha u costo ad esempio di 50,00, sapendo che il costo orario di struttura è di 10,00, inserisco 60,00. I questo modo tutti i preventivi includo quel costo previsto</p>
                <p>- Utilizzo del costo di struttura orario sol per singoli preventivi. In questo caso quando creo il servizio inserirò il valore reale del costo, quindi come dall’esempio precedente 50,00
                IL SERVIZIO CLIENTE E’ SEMPRE DISPONBILE A CHIARIRE EVENTUALI DUBBI SU QUESTO METDO DI CALCOLO E MODALITA’ DI APPLICAZIONE. QUINI, IN CASO DI NON ESITARE A SOTTOPORE PER EMIL I VOSTRI QUESTITI.</p>

                <h4>Informazioni sulle voci</h4>
                <p><strong> - Costi di struttura: </strong>  nella prima area della pagina è possibile aggiungere tutti i costi previsto per l’anno che si ritengono da considerare di struttura, quindi ad esempio gli affitti, le utenze, gli ammortamenti, abbonamenti, cancelleria, etc. Quindi tutto quello che non è attribuibile a specifici lavori. E’ stato strutturato in modo estremamente semplificato, usando solo la categoria e il costo, consentendo la compilazione in modo semplificato e indicativo (non sono necessario valori di stima perfetti. Si consiglia di tener conto dei costi dell’anno precedente, visto che trattasi di stime). Il metodo di calcolo tiene consto del personale non dedicato ai progetti, quindi si può escludere dal voci di costo ad esempio personale che non lavora direttamente ai progetti, anche se queste ipotesi sono limitate nella piccola impresa o studio professionale. In seguito sarà spiegato questo.</p>
                <p><strong> - Stima numero ore totali pagate al personale: </strong>  In questo campo è necessario inserire una stima del totale delle ore pagate al personale (è possibile includer anche Freelancer in questo modo il costo di struttura è spalmato su tutte e forse attive e sul totale delle ore lavorate dei progetti)<p>

                <p><strong> - Stima costo del personale: </strong> In questo campo è necessario inserire una stima del totale del costo delle ore pagate al personale (è possibile includer anche Freelancer in questo modo il costo di struttura è spalmato su tutte e forse attive e sul totale delle ore lavorate dei progetti)</p>


                <p><strong> - Stima % ore assegnate ai progetti:</strong>  In questo campo è necessario inserire una stima delle ore lavorate riguardo i progetti che si prevede di realizzare nell’anno. Questo parametro è importante perché ha l’obiettivo  <strong>di tener conto del tempo non dedicato ai progetti specifici</strong>.  Attraverso questo parametro la siamo in grado di attribuire al costo di struttura il costo di quel personale che per alcuni periodi non ha progetti o svolge attività non di progetto, cioè non legato a specifiche commesse. Quindi ad esempio un addetto amministrativo che non si occupa di commesse escludendolo da questa percentuale va a far parte dei costi di struttura. Abbiamo volutamente inserire un parametro semplificato perché è estremamente difficile avere dati previsionali dettagliati previsionali. L’esperienza che trattasi di un metodo molto efficace, pratico ed immediato.</p>


                <p><strong> - Costo di struttura orario:</strong> E’ un formula proprietaria che è in grado di <strong>determinare una stima del costo orari di struttura da attribuire alle ore lavorate nei progetti</strong>. In pratica mi dice quanti euro devo aggiunge al costo materiale delle risorse impiegate nelle commesse per singola ora lavorata. Come già spiegato il valore va aggiunto, qualora si ritiene opportuno per dimensione, tipologia di lavoro e cliente se aggiungerlo o no. Si può aggiungere alo specifico preventivo o nelle impostazioni del costo del servizio, comunque modificabile nel singolo preventivo. Abbiamo volutamente utilizzato uno schema che attraverso l’inserimento di pochi parametri che richiede pochi dati come il costo del personale e le ore pagate e parametri riconducibili all’esperienza, permette di avere un dato estremamente utile da tenere inconsiderazione e con flessibilità.</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel rounded mr-3" data-dismiss="modal">@lang('app.close')</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.addCostCategory').click(function () {
            var url = "{{ route('costCategory.create') }}?page=cost";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('.addCost').click(function () {
            var url = "{{ route('costs.create') }}?page=cost";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        function refreshCostCategory(i) {
            $.easyAjax({
                url: "{{ route('cost_category_dropdown') }}",
                type: "GET",
                success: function(response) {
                    // console.log("cost cat dropdown", response.data);
                    $('#cost_category_id' + i).html(response.data);
                    $('#cost_category_id' + i).selectpicker('refresh');
                }
            });
        }

        function refreshCost(i) {
            $.easyAjax({
                url: "{{ route('cost_dropdown') }}",
                type: "GET",
                success: function(response) {
                    // console.log("cost dropdown", response.data);
                    $('#add-cost' + i).html(response.data);
                    $('#add-cost' + i).selectpicker('refresh');
                }
            });
        }

        $(document).on('click', '#add-item', function() {
            var i = $(document).find('.item_name').length;
            refreshCostCategory(i);
            refreshCost(i);
            var item = ' <div class="d-flex pl-2 py-2 c-inv-desc item-row">' +
                '<div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">' +
                '<table width="50%">' +
                '<tbody>' +
                '<tr>' +
                '<td width="40%" class="btrr-mbl btlr">' +
                    '<div class="input-group">' +
                        '<div class="dropdown bootstrap-select form-control select-picker">' +
                            '<select class="form-control select-picker cost_category_id"'+ 'name="cost_category_id[]" id="cost_category_id' + i + '" data-live-search="true">' +
                                '<option value="">'+
                                    '{{ __('app.select')}} {{ __("app.costCategory") }}'+
                                '</option>'
                                @foreach ($costCategory as $category)
                                    + '<option data-content="{{ $category->category_name }}" value="{{ $category->id }}">' +
                                        '{{ mb_ucwords($category->category_name) }}</option>'
                                @endforeach
                            +'</select>'+
                            '<input type="hidden" class="cost_category_name" name="cost_category_name[]">'+
                        '</div>'+
                        '<div class="input-group-append">' +
                            '<a href="javascript:;" id="addCostCategory' + i + '"'+
                                'class="btn btn-outline-secondary border-grey" data-toggle="tooltip"'+
                                'data-original-title="@lang('app.add')'+' '+'@lang('app.costCategory')">@lang('app.add')</a>'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                '<td width="40%" class="btrr-mbl btlr">' +
                    '<div class="input-group">' +
                        '<div class="dropdown bootstrap-select form-control select-picker">' +
                            '<select class="form-control select-picker item_id add-cost"'+ 'name="item_id[]" id="add-cost' + i + '">' +
                                '<option value="">'+
                                    '{{ __('app.select')}} {{ __("app.cost") }}'+
                                '</option>'
                                @foreach ($costs as $item)
                                    + '<option data-content="{{ $item->name }}" value="{{ $item->id }}">' +
                                        '{{ mb_ucwords($item->name) }}</option>'
                                @endforeach
                            +'</select>'+
                            '<input type="hidden" class="cost_id" name="cost_id[]">'+
                            '<input type="hidden" class="item_name" name="item_name[]">'+
                        '</div>'+
                        '<div class="input-group-append">' +
                            '<a href="javascript:;" id="addCost' + i + '"'+
                                'class="btn btn-outline-secondary border-grey" data-toggle="tooltip"'+
                                'data-original-title="@lang('app.add')'+' '+'@lang('app.cost')">@lang('app.add')</a>'+
                        '</div>'+
                    '</div>'+
                '</td>';
            item += '<td width="20%" align="right" valign="top" class="btrr-bbrr">'+
                '<span class="amount-html">0.00</span>'+
                '<input type="hidden" class="amount" name="amount[]" value="0">'+
            '</td>'+
            '<td width="8px" align="right" valign="middle" style="padding:8px; border: 0;">'+
                '<a href="javascript:;"class="remove-item">'+
                    '<i class="fa fa-times-circle f-20 text-lightest"></i></a>'+
            '</td>'+
            '</tr>' +
            '</tr>' +
            '</tbody>' +
            '</table>' +
            '</div>' +
            '</div>';
            $(item).hide().appendTo("#sortable").fadeIn(500);
            $('#multiselect' + i).selectpicker();
            $('#cost_category_id' + i).selectpicker();
            $('#add-cost' + i).selectpicker();



        $('#addCostCategory' + i).click(function () {
            var url = "{{ route('productCategory.create') }}?item_no=" + i + "&page=cost";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#addCost'+i).click(function () {
            var url = "{{ route('costs.create') }}?item_no=" + i + "&page=cost";
            console.log(url);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });


        $('#cost_category_id' + i).change(function (e) {
            let cadid = $(this).val();
            let catName = $(this).closest('.item-row').find('.cost_category_id option:selected').data("content");
            console.log("cost cat name", catName);
            $(this).closest('.item-row').find('.cost_category_name').val(catName);
            let url = "{{ route('get_cost_by_category', ':id') }}";

            url = (cadid) ? url.replace(':id', cadid) : url.replace(':id', null);
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

                        $(self).closest('.item-row').find('select.add-cost').html('<option value="">@lang('app.selectCost')</option>' + options);
                        $(self).closest('.item-row').find('select.add-cost').selectpicker('refresh');
                    }
                }
            })
        });

        // Change service type
         $('#add-cost' + i).on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation()
            var id = $(this).val();
            if (previousValue != id && id != '') {
                let costurl = "{{ route('get_cost', ':id') }}";
                costurl = (id) ? costurl.replace(':id', id) : costurl.replace(':id', null);

                var self = this;
                $.easyAjax({
                    url: costurl,
                    type: "GET",
                    success: function (response) {
                        if (response.status == 'success') {
                            var costId;
                            var csotname;
                            var productPrice;
                            rData = response.data;
                            console.log("cost data",rData);
                            costId =rData[0].id;
                            csotname =rData[0].name;
                            productPrice =rData[0].price;
                            $(self).closest('.item-row').find('.item_name').val(csotname);
                            $(self).closest('.item-row').find('.cost_id').val(costId);
                            $(self).closest('.item-row').find('.amount').val(decimalupto2(productPrice));
                            $(self).closest('.item-row').find('.amount-html').html(decimalupto2(productPrice));

                            calculateCostTotal();
                            calculateStructureCost();

                        }
                    }
                })
            }
        });
    });

        $('.cost_category_id').change(function (e) {
            let cadid = $(this).val();
            let catName = $(this).closest('.item-row').find('.cost_category_id option:selected').data("content");
            console.log("cost cat name", catName);
            $(this).closest('.item-row').find('.cost_category_name').val(catName);
            let url = "{{ route('get_cost_by_category', ':id') }}";

            url = (cadid) ? url.replace(':id', cadid) : url.replace(':id', null);

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

                        $(self).closest('.item-row').find('select.add-cost').html('<option value="">@lang('app.selectCost')</option>' + options);
                        $(self).closest('.item-row').find('select.add-cost').selectpicker('refresh');
                    }
                }
            })
        });

        // Change service type
        $('.add-cost').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation()
            var id = $(this).val();
            if (previousValue != id && id != '') {
                let costurl = "{{ route('get_cost', ':id') }}";
                costurl = (id) ? costurl.replace(':id', id) : costurl.replace(':id', null);

                var self = this;
                $.easyAjax({
                    url: costurl,
                    type: "GET",
                    success: function (response) {
                        if (response.status == 'success') {
                            var costId;
                            var csotname;
                            var productPrice;
                            rData = response.data;
                            console.log("cost data",rData);
                            costId =rData[0].id;
                            csotname =rData[0].name;
                            productPrice =rData[0].price;
                            $(self).closest('.item-row').find('.item_name').val(csotname);
                            $(self).closest('.item-row').find('.cost_id').val(costId);
                            $(self).closest('.item-row').find('.amount').val(decimalupto2(productPrice));
                            $(self).closest('.item-row').find('.amount-html').html(decimalupto2(productPrice));

                            calculateCostTotal();
                            calculateStructureCost();
                        }
                    }
                })
            }
        });

        $('#saveInvoiceForm').on('click', '.remove-item', function() {
            $(this).closest('.item-row').fadeOut(300, function() {
                $(this).remove();
                $('select.customSequence').each(function(index) {
                    $(this).attr('name', 'taxes[' + index + '][]');
                    $(this).attr('id', 'multiselect' + index + '');
                });
                calculateCostTotal();
                calculateStructureCost();
            });
        });

        $('.save-form').click(function() {
            calculateCostTotal();
            calculateStructureCost();
            $.easyAjax({
                url: "{{ route('structureCost.store') }}",
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
                calculateCostTotal();
                calculateStructureCost();
            });
        });

        $('#saveInvoiceForm').on('keyup', '.hour-number, .estimate-hour-percent, .estimate-employee-cost', function() {
            calculateCostTotal();
            calculateStructureCost();
        });

        // $('#estimate-hour-percent').blur(function(e) {
        //     var value = $.trim($(this).val());
        //     if(value != '') {
        //         $(this).val(value +'%');
        //     }
        // });
        // $('#estimate-hour-percent').focus(function(e) {
        //     var value = $.trim($(this).val());
        //     if(value != '') {
        //         $(this).val(value +'%');
        //     }
        // });

        // $('#estimate-hour-percent').focus(function() {
        //     var value = $.trim($(this).val());
        //     if(value != '') {
        //         $(this).val().replace('%','');
        //     }
        // }).blur(function() {
        //     var value = $.trim($(this).val());
        //     if(value != '') {
        //         $(this).val(value +'%');
        //     }
        //     calculateCostTotal();
        //     calculateStructureCost();
        // });


    function calculateCostTotal() {
        var total = 0;
        $(".item_name").each(function (index, element) {
            var amount = parseFloat(
                $(this).closest(".item-row").find(".amount").val()
            );
            if (isNaN(amount)) {
                amount = 0;
            }
            total = (parseFloat(total) + parseFloat(amount)).toFixed(2);
        });

        if (isNaN(total)) {
            total = 0;
        }
        $(".total").html(total);
        $(".total-field").val(total);

    }

    function calculateStructureCost() {

            var total = 0;
            var a = 0;
            var b = 0;
            var c = 0;
            var d = 0;

            a = parseFloat(
                $('.total-field').val()
            );

            b = parseFloat(
                $('.hour-number').val()
            );
            c = parseFloat(
                $('.estimate-employee-cost').val()
            );

            d = parseFloat(
                $('.estimate-hour-percent').val()
            );

            // var total_hr = $('.estimate-hour-percent').val().replace('%','');
            // d = 1;

            if (isNaN(a)) {
                a = 0;
            }
            if (isNaN(b)) {
                b = 0;
            }
            if (isNaN(c)) {
                c = 0;
            }
            if (isNaN(d)) {
                d = 0;
            }
            console.log('a',a);
            console.log('b',b);
            console.log('c',c);
            console.log('d',d);

            if (isNaN(total)) {
                total = 0;
            }

            var r_one = (parseFloat(c) * parseFloat(d)) / 100;
            console.log('r_one', r_one);
            var r_two = (parseFloat(b) * parseFloat(d)) / 100;
            console.log('r_two', r_two);

            // total = parseFloat(a);
            if ((!isNaN(d) && d != 0)) {
                if (!isNaN(r_one) && r_one != 0) {
                    total = (parseFloat(a) + (parseFloat(c) - parseFloat(r_one))).toFixed(2);
                }

                if (!isNaN(r_two) && r_two != 0) {
                    total = (parseFloat(total) / parseFloat(r_two)).toFixed(2);
                }
            }
            console.log('total structure cost', total);

            if (isNaN(total)) {
                total = 0;
            }
            if (total=='Infinity') {
                total = 0;
            }
            $(".total-structure-cost-html").html(total);
            $(".total-structure-cost").val(total);
        }


    calculateCostTotal();
    calculateStructureCost();
    init(RIGHT_MODAL);
    });

    function ucWord(str) {
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
    }
    </script>
