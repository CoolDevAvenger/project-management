@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- SUB-CATEGORY START -->
    <div class="w-100 d-flex ">
        <x-service-card>

            <x-slot name="buttons">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="add-sub-category"
                                    class="category-btn mb-2 actionBtn"> @lang('modules.statusFields.addSubCategory')
                        </x-forms.button-primary>
                    </div>

                </div>
            </x-slot>

            @include($view)

        </x-service-card>

    </div>
    </div>
    <!-- SUB-CATEGORY END -->
@endsection


