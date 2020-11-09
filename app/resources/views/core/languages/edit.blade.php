@extends('layouts.master',['activeSideNav' => active_side_nav()])

@section('title', $title)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        @component('components.form_box')
                            @slot('title', __('Edit Language'))
                            @slot('indexUrl', route('languages.index'))
                            {{ Form::model($language, ['route' => ['languages.update', $language->id], 'class' => 'cvalidate', 'id' => 'languageForm', 'files' => true, 'method'=> 'put']) }}
                            @include('core.languages._form', ['buttonText' => __('Update')])
                            {{ Form::close() }}
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
@endsection

@section('script')
    @include('core.languages._script')
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#languageForm').cValidate({
                rules : {
                    'name' : 'required|max:255',
                    'short_code' : 'required|min:2|max:2',
                    'icon' : 'image|max:100',
                    'is_active' : 'required',
                }
            });
        });
    </script>
@endsection
