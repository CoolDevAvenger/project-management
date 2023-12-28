<div class="modal-header">
    <h5 class="modal-title">@lang('app.costCategories')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<x-form id="createCostCategory" method="POST" class="ajax-form">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <x-forms.text fieldId="category_name" :fieldLabel="__('modules.projectCategory.categoryName')"
                    fieldName="category_name" fieldRequired="true" :fieldPlaceholder="__('placeholders.category')">
                </x-forms.text>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="save-service-category" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>


<script>
    var page = "{{ $page }}";
    var item_no = "{{ $item_no }}";
    console.log("item no. ", page);
    console.log("item no. ", item_no);

    $('#save-service-category').click(function () {
        $.easyAjax({
            container: '#createCostCategory',
            type: "POST",
            disableButton: true,
            blockUI: true,
            buttonSelector: "#save-service-category",
            url: "{{ route('costCategory.store') }}",
            data: $('#createCostCategory').serialize(),
            success: function (response) {
                if (response.status === 'success') {
                    if (page === 'cost') {
                        if(item_no){
                            $('#cost_category_id' + item_no).html(response.data);
                            $('#cost_category_id' + item_no).trigger('change');
                            $('#cost_category_id' + item_no).selectpicker('refresh');
                        }else {
                            $('#cost_category_id').html(response.data);
                            $('#cost_category_id').trigger('change');
                            $('#cost_category_id').selectpicker('refresh');
                        }
                        $(MODAL_LG).modal('hide');
                    } else {
                        $(MODAL_LG).modal('hide');
                        window.location.reload();
                    }
                }
            }
        })
    });

</script>
