@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <nav class="tabs px-4 border-bottom-grey">
                        <div class="nav" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link f-15 active general"
                                href="{{ route('invoice-settings.index') }}?tab=general" role="tab"
                                aria-controls="nav-ticketAgents" aria-selected="true">@lang('app.menu.invoiceSettings')
                            </a>

                            <a class="nav-item nav-link f-15 units"
                                    href="{{ route('invoice-settings.index') }}?tab=units" role="tab"
                                    aria-controls="nav-ticketTypes" aria-selected="true">@lang('app.menu.units')
                            </a>

                            <a class="nav-item nav-link f-15 quickbooks"
                                href="{{ route('invoice-settings.index') }}?tab=quickbooks" role="tab"
                                aria-controls="nav-ticketTypes" aria-selected="true">@lang('app.menu.quickBookSettings')
                            </a>

                        </div>
                    </nav>
                </div>
            </x-slot>

            <x-slot name="buttons">
                <div class="row d-none">
                    <div class="col-md-12  mb-3">
                        <x-forms.button-primary id="addUnitType" icon="plus" class="addUnit btn btn-primary mb-2 actionBtn units-btn"
                            type="button" data-toggle="tooltip">@lang('app.addUnit')</x-forms.button-primary>

                        <x-alert type="info" icon="info-circle" class="actionBtn quickbooks-btn">
                            @lang('modules.invoiceSettings.syncInfo')
                        </x-alert>
                    </div>
                </div>
            </x-slot>

            @include($view)

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')
<script>

    $('.nav-item').removeClass('active');
    const activeTab = "{{ $activeTab }}";
    $('.' + activeTab).addClass('active');

    showBtn(activeTab);

    function showBtn(activeTab) {
        $('.actionBtn').addClass('d-none');
        $('.' + activeTab + '-btn').removeClass('d-none');
    }


    $("body").on("click", "#editSettings .nav a", function(event) {
        event.preventDefault();

        $('.nav-item').removeClass('active');
        $(this).addClass('active');

        const requestUrl = this.href;

        $.easyAjax({
            url: requestUrl,
            blockUI: true,
            container: "#nav-tabContent",
            historyPush: true,
            success: function(response) {
                if (response.status == "success") {
                    showBtn(response.activeTab);

                    $('#nav-tabContent').html(response.html);
                    init('#nav-tabContent');
                }
            }
        });
    });

    $('#addUnitType').click(function() {
        const url = "{{ route('unit-type.create') }}";
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });

</script>
@endpush

