@php
    $deleteProductSubCategoryPermission = user()->permission('manage_product_sub_category');
@endphp

<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('modules.productCategory.productSubCategory')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    @if (!isset($page) && $page != "estimate")
    <x-table class="table-bordered" headType="thead-light">
        <x-slot name="thead">
            <th>#</th>
            <th>@lang('modules.productCategory.category')</th>
            <th>@lang('modules.productCategory.subCategory')</th>
            <th class="text-right">@lang('app.action')</th>
        </x-slot>

        @forelse($subcategories as $key => $subcategory)
            <tr id="cat-{{ $subcategory->id }}">
                <td>{{ $key + 1 }}</td>
                <td>
                    <select class="form-control select-picker" name="category_id" id="category_id_modal"
                            data-live-search="true" data-row-id="{{ $subcategory->id }}">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                    @if($subcategory->category_id == $category->id) selected @endif >{{ mb_ucwords($category->category_name) }}</option>
                        @endforeach
                    </select>
                </td>

                <td data-row-id="{{ $subcategory->id }}"
                    contenteditable="true">{{ mb_ucwords($subcategory->category_name) }}</td>

                <td class="text-right">
                    @if ($deleteProductSubCategoryPermission == 'all' || ($deleteProductSubCategoryPermission == 'added' && $subcategory->added_by == user()->id))
                        <x-forms.button-secondary data-cat-id="{{ $subcategory->id }}" icon="trash"
                                                  class="delete-category">
                            @lang('app.delete')
                        </x-forms.button-secondary>
                    @endif
                </td>
            </tr>
        @empty
            <x-cards.no-record-found-list colspan="4"/>
        @endforelse
    </x-table>
    @endif

    <x-form id="createProjectCategory">
        <div class="row border-top-grey ">
            <div class="col-sm-12 col-md-6">
                <x-forms.select fieldId="category_id" :fieldLabel="__('modules.projectCategory.categoryName')"
                                fieldName="category_id" search="true" fieldRequired="true">
                    @forelse($categories as $category)
                        <option value="{{ $category->id }}">{{ mb_ucwords($category->category_name) }}</option>
                    @empty
                        <option value="">@lang('messages.noCategoryAdded')</option>
                    @endforelse
                </x-forms.select>
            </div>
            <div class="col-sm-12 col-md-6">
                <x-forms.text fieldId="category_name" :fieldLabel="__('modules.productCategory.productSubCategory')"
                              fieldName="category_name" fieldRequired="true" fieldPlaceholder="e.g. Potential Client">
                </x-forms.text>
            </div>

        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-category" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>

    init(MODAL_LG);

    $('.delete-category').click(function () {

        const id = $(this).data('cat-id');
        let url = "{{ route('productSubCategory.destroy', ':id') }}";
        url = url.replace(':id', id);

        const token = "{{ csrf_token() }}";

        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('messages.confirmDelete')",
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
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            $('#cat-' + id).fadeOut();
                        }
                    }
                });
            }
        });

    });

    var page = "{{ $page }}";
    var item_no = "{{ $item_no }}";
    $('#save-category').click(function () {
        var url = "{{ route('productSubCategory.store') }}";
        $.easyAjax({
            url: url,
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {

                if (response.status == 'success') {
                    if(item_no){
                        if(page == 'estimate'){
                            $('#product_category_id' + item_no).html('<option value="">Select Service Category</option>' + response.categoryOptions);
                            $('#product_category_id' + item_no).selectpicker('refresh');
                            $('#product_sub_category_id' + item_no).html('<option value="">Select Service Sub Category</option>'+ response.subCategoryOptions);
                            $('#product_sub_category_id' + item_no).selectpicker('refresh');
                        }else {
                            $('#product_category_id' + item_no).html('<option value="">Select Service Category</option>' + response.data);
                            $('#product_category_id' + item_no).selectpicker('refresh');
                            // $('#product_sub_category_id' + item_no).html('<option value="">Select Service Sub Category</option>'+ response.subCategoryData);
                            // $('#product_sub_category_id' + item_no).selectpicker('refresh');
                        }
                        $('#sub_category_id').html('<option value="">Select Service Sub Category</option>'+ response.subCategoryData);
                        $('#sub_category_id').selectpicker('refresh');
                    }else {
                        if(page == 'estimate'){
                            $('#product_category_id').html('<option value="">Select Service Category</option>' + response.categoryOptions);
                            $('#product_category_id').selectpicker('refresh');
                            $('#product_sub_category_id').html('<option value="">Select Service Sub Category</option>'+ response.subCategoryOptions);
                            $('#product_sub_category_id').selectpicker('refresh');
                        }else {
                            $('#product_category_id').html('<option value="">Select Service Category</option>' + response.data);
                            $('#product_category_id').selectpicker('refresh');
                            // $('#product_sub_category_id').html('<option value="">Select Service Sub Category</option>'+ response.subCategoryData);
                            // $('#product_sub_category_id').selectpicker('refresh');
                        }
                        $('#sub_category_id').html('<option value="">Select Service Sub Category</option>'+ response.subCategoryData);
                        $('#sub_category_id').selectpicker('refresh');

                    }
                    $(MODAL_LG).modal('hide');
                }
            }
        })
    });

    $('[contenteditable=true]').focus(function () {
        $(this).data("initialText", $(this).html());
    }).blur(function () {
        if ($(this).data("initialText") !== $(this).html()) {
            let id = $(this).data('row-id');
            let value = $(this).html();

            let url = "{{ route('productSubCategory.update', ':id') }}";
            url = url.replace(':id', id);

            const token = "{{ csrf_token() }}";

            $.easyAjax({
                url: url,
                container: '#row-' + id,
                type: "POST",
                data: {
                    'category_name': value,
                    '_token': token,
                    '_method': 'PUT'
                },
                blockUI: true,
                success: function (response) {
                    if (response.status == 'success') {

                        $('#product_category_id').html(response.categories);
                        $('#product_category_id').selectpicker('refresh');

                        $('#sub_category_id').html(response.sub_categories);
                        $('#sub_category_id').selectpicker('refresh');
                    }
                }
            })
        }
    });

    $(document).on('change', '#category_id_modal', function() {
        const id = $(this).data('row-id');
        const categoryId = $(this).val();

        if (id == undefined){
            return false;
        }

        let url = "{{ route('productSubCategory.update', ':id') }}";
        url = url.replace(':id', id);

        const token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "POST",
            data: {
                'category_id': categoryId,
                '_token': token,
                '_method': 'PUT'
            },
            blockUI: true,
            success: function (response) {
                if (response.status == 'success') {
                    $('#product_category_id').html(response.categories);
                    $('#product_category_id').selectpicker('refresh');

                    $('#sub_category_id').html(response.sub_categories);
                    $('#sub_category_id').selectpicker('refresh');
                }
            }
        })
    });

</script>


