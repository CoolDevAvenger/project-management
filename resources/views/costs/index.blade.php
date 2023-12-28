@extends('layouts.app')

@section('content')
@php
$addProductPermission = user()->permission('add_product');
@endphp
<div class="content-wrapper">
    <!-- SUB-CATEGORY START -->
    <div class="w-100 d-flex ">
        <x-service-card>

            <x-slot name="buttons">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        @if ($addProductPermission == 'all' || $addProductPermission == 'added')
                        <x-forms.button-primary icon="plus" id="add-cost"
                                    class="category-btn mb-2 actionBtn"> @lang('app.addCost')
                        </x-forms.button-primary>
                        @endif
                    </div>

                </div>
            </x-slot>

            @include($view)

        </x-service-card>

    </div>
    </div>
    <!-- SUB-CATEGORY END -->
@endsection


