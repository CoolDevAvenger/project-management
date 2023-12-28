@php
$editProductPermission = user()->permission('edit_product');
$deleteProductPermission = user()->permission('delete_product');
@endphp
<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4">

    <div class="table-responsive">
        <x-table class="table-bordered" headType="thead-light">
            <x-slot name="thead">
                <th>#</th>
                <th>@lang('app.costs')</th>
                <th>@lang('modules.productCategory.category')</th>
                <th>@lang('app.price')</th>
                <th class="text-right">@lang('app.action')</th>
            </x-slot>

            @forelse($costs as $key => $cost)
                <tr id="cat-{{ $cost->id }}">
                    <td>{{ $key + 1 }}</td>
                    <td data-row-id="{{ $cost->id }}"
                        contenteditable="true" class="nameitable">{{ mb_ucwords($cost->name) }}
                    </td>
                    <td>
                        <select class="form-control select-picker category_id_modal" name="category_id"
                                data-live-search="true" data-row-id="{{ $cost->id }}">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if($cost->category_id == $category->id) selected @endif >{{ mb_ucwords($category->category_name) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-row-id="{{ $cost->id }}"
                        contenteditable="true" class="priceitable">{{ mb_ucwords($cost->price) }}
                    </td>

                    <td class="text-right">
                        @if ($deleteProductPermission == 'all' || ($deleteProductPermission == 'added' && $cost->added_by == user()->id))
                            <x-forms.button-secondary data-category-id="{{ $cost->id }}" icon="trash"
                                                      class="delete-cost">
                                @lang('app.delete')
                            </x-forms.button-secondary>
                        @endif
                    </td>
                </tr>
            @empty
                <x-cards.no-record-found-list colspan="4"/>
            @endforelse
        </x-table>
    </div>

</div>

<script>

    $('.nameitable').focus(function () {
    $(this).data("initialText", $(this).html());
    }).blur(function () {
        if ($(this).data("initialText") !== $(this).html()) {
            let id = $(this).data('row-id');
            let value = $(this).html();

            let url = "{{ route('costs.update', ':id') }}";
            url = url.replace(':id', id);

            const token = "{{ csrf_token() }}";

            $.easyAjax({
                url: url,
                container: '#row-' + id,
                type: "POST",
                data: {
                    'name': value,
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

    $('.priceitable').focus(function () {
    $(this).data("initialText", $(this).html());
    }).blur(function () {
        if ($(this).data("initialText") !== $(this).html()) {
            let id = $(this).data('row-id');
            let value = $(this).html();

            let url = "{{ route('costs.update', ':id') }}";
            url = url.replace(':id', id);

            const token = "{{ csrf_token() }}";

            $.easyAjax({
                url: url,
                container: '#row-' + id,
                type: "POST",
                data: {
                    'price': value,
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

        let url = "{{ route('costs.update', ':id') }}";
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

    $('#add-cost').click(function () {
        const url = "{{ route('costs.create') }}";
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });

    $('body').on('click', '.delete-cost', function () {
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
                var url = "{{ route('costs.destroy', ':id') }}";
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
