
<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.createCost')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">

    <x-form id="createCost">
        <div class="row border-top-grey ">
            <div class="col-sm-12 col-md-4">
                <x-forms.text fieldId="cost_name" :fieldLabel="__('app.name')"
                              fieldName="name" fieldRequired="true" fieldPlaceholder="e.g. Decoration">
                </x-forms.text>
            </div>
            <div class="col-sm-12 col-md-4">
                <x-forms.select fieldId="category_id" :fieldLabel="__('app.category')"
                                fieldName="category_id" search="true" fieldRequired="true">
                    <option value="">@lang('app.selectCostCategory')</option>
                    @forelse($categories as $category)
                        <option value="{{ $category->id }}">{{ mb_ucwords($category->category_name) }}</option>
                    @empty
                    @endforelse
                </x-forms.select>
            </div>
            <div class="col-sm-12 col-md-4">
                <x-forms.text fieldId="cost_price" :fieldLabel="__('app.price')"
                              fieldName="price" fieldRequired="true" fieldPlaceholder="e.g. 1000">
                </x-forms.text>
            </div>

        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-cost" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    init(MODAL_LG);
    var page = "{{ $page }}";
    var item_no = "{{ $item_no }}";
    $('#save-cost').click(function () {
        var url = "{{ route('costs.store') }}";
        $.easyAjax({
            url: url,
            container: '#createCost',
            type: "POST",
            data: $('#createCost').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    if(page == 'cost'){
                        console.log("cost dropdown", response);
                        if(item_no){
                            $('#add-cost' + item_no).html(response.data);
                            $('#add-cost' + item_no).trigger('change');
                            $('#add-cost' + item_no).selectpicker('refresh');
                            $('#cost_category_id' + item_no).html(response.categoryOptions);
                            $('#cost_category_id' + item_no).selectpicker('refresh');
                        }else {
                            $('#add-cost').html(response.data);
                            $('#add-cost').trigger('change');
                            $('#add-cost').selectpicker('refresh');
                            $('#cost_category_id').html(response.categoryOptions);
                            $('#cost_category_id').selectpicker('refresh');
                        }
                        $(MODAL_LG).modal('hide');
                    } else {
                        if ($(MODAL_LG).hasClass('show')) {
                            $(MODAL_LG).modal('hide');
                            window.location.reload();
                        } else {
                            window.location.href = response.redirectUrl;
                        }
                    }
                }
            }
        })
    });

</script>


