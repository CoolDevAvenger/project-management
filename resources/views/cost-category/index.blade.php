@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">
        <x-service-card>

            <x-slot name="buttons">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="addCostCategory"
                                    class="category-btn mb-2 actionBtn"> @lang('modules.statusFields.addCategory')
                        </x-forms.button-primary>
                    </div>

                </div>
            </x-slot>

            @include($view)

        </x-service-card>

    </div>
    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')

@endpush
