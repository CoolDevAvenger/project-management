
<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4">

    <div class="table-responsive">
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
                        <select class="form-control select-picker category_id_modal" name="category_id"
                                data-live-search="true" data-row-id="{{ $subcategory->id }}">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if($subcategory->category_id == $category->id) selected @endif >{{ mb_ucwords($category->category_name) }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td data-row-id="{{ $subcategory->id }}"
                        contenteditable="true">{{ mb_ucwords($subcategory->category_name) }}
                    </td>

                    <td class="text-right">
                        <x-forms.button-secondary data-category-id="{{ $subcategory->id }}" icon="trash"
                                                    class="delete-sub-category">
                            @lang('app.delete')
                        </x-forms.button-secondary>
                    </td>
                </tr>
            @empty
                <x-cards.no-record-found-list colspan="4"/>
            @endforelse
        </x-table>
    </div>

</div>

<script>

    $('[contenteditable=true]').focus(function () {
    $(this).data("initialText", $(this).html());
    }).blur(function () {
        if ($(this).data("initialText") !== $(this).html()) {
            let id = $(this).data('row-id');
            let value = $(this).html();

            let url = "{{ route('projectSubCategory.update', ':id') }}";
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
                    }
                }
            })
        }
    });

    $(document).on('change', '.category_id_modal', function() {
        const id = $(this).data('row-id');
        const categoryId = $(this).val();

        if (id == undefined){
            return false;
        }

        let url = "{{ route('projectSubCategory.update', ':id') }}";
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
                }
            }
        })
    });

    $('#addProjectSubCategory').click(function () {
        const url = "{{ route('project-settings.createSubCategory') }}";
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });

    $('body').on('click', '.delete-sub-category', function () {
        var id = $(this).data('category-id');
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
                var url = "{{ route('projectSubCategory.destroy', ':id') }}";
                url = url.replace(':id', id);
                var token = "{{ csrf_token() }}";
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
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

</script>
