@extends('layouts.master', ['title'=>__('Logs'), 'activeSideNav' => active_side_nav()])
@section('title', __('Logs'))
@section('content')
    <div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-4">
                <div class="col-sm-4">
                    <select name="file" class="file form-control">
                        @foreach($files as $file)
                            <option
                                {{($current_file == $file) ? 'selected' : ''}}
                                value="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"> {{$file}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-8">
                    @if($current_file)
                        <div class="float-right">
                            <a class="btn btn-sm btn-primary" href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}"><span
                                    class="fa fa-download"></span>
                                {{ __('Download') }}</a>
                            <a class="btn btn-sm btn-danger" id="delete-log" data-alert="{{ __('Are you sure?') }}" class="confirmation"
                               href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}"><span
                                    class="fa fa-trash"></span>
                                {{ __('Delete Current File') }}</a>
                            @if(count($files) > 1)
                                <a class="btn btn-sm btn-danger" id="delete-all-log" data-alert="{{ __('Are you sure?') }}" class="confirmation"
                                   href="?delall=true"><span class="fa fa-trash"></span>
                                    {{ __('Delete All Files') }}</a>
                            @endif
                        </div>
                        <br>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-lg-12">
            <div class="box box-primary box-borderless">
                <div class="box-body">
                    @if ($logs === null)
                        <div>
                            {{ __('Log file > 50M, please download it.') }}
                        </div>
                    @else
                        @component('components.table',['class' => 'lf-data-table mb-4'])
                            @slot('thead')
                                <tr class="bg-primary text-white">
                                    <th class="all">{{ __('Level') }}</th>
                                    <th class="min-phone-l">{{ __('Date') }}</th>
                                    <th class="min-phone-l">{{ __('Content') }}</th>
                                    <th class="none">{{ __('Details') }}</th>
                                    <th class="none">{{ __('Stacktrace') }}</th>
                                </tr>
                            @endslot

                            @foreach($logs as $key => $log)
                                <tr data-display="stack{{{$key}}}">
                                    <td class="text-{{{$log['level_class']}}}"><i
                                            class="fa fa-{{{$log['level_img']}}}"
                                            aria-hidden="true"></i> {{$log['level']}}</td>
                                    <td class="date">{{{$log['date']}}}</td>
                                    <td class="text">
                                        <code class="d-block">
                                            {{{substr($log['text'],0,80)}}} ...
                                        </code>
                                    </td>
                                    <td>
                                        <code class="d-block">
                                            {{{$log['text']}}}
                                            @if (isset($log['in_file']))
                                                <br/>{{{$log['in_file']}}}
                                            @endif
                                        </code>
                                    </td>
                                    <td>
                                        @if ($log['stack'])
                                            <div class="stack d-block lf-pre-wrap" id="stack{{{$key}}}">{{{ trim($log['stack']) }}}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap4.min.css') }}">
@endsection

@section('script')
    @include('layouts.includes.list-js')
    <script src="{{asset('plugins/select2/js/select2.js')}}"></script>

    <script>
        "use strict";

        $(document).ready(function () {
            $('.file').select2({
                theme: 'bootstrap4'
            });

            $(document).on('change', '.file', function () {
                window.location = $(this).val();
            });

            $('.data-table').dataTable({
                'paging': true,
                'searching': true,
                'bInfo': false,
                "dom": 'rtp',
                "language": {
                    "aria": {
                        "sortAscending": ": {{ __('activate to sort column ascending') }}",
                        "sortDescending": ": {{ __('activate to sort column descending') }}"
                    },
                    "emptyTable": "{{ __('No data available in table') }}",
                    "info": "{{ __('Showing :start to :end of _TOTAL_ entries',['start'=>'_START_','end'=>'_END_']) }}",
                    "infoEmpty": "{{ __('No entries found') }}",
                    "infoFiltered": "{{ __('(filtered1 from :max total entries)',['max'=>'_MAX_']) }}",
                    "lengthMenu": "{{ __(':menu entries',['menu'=>'_MENU_']) }}",
                    "search": "{{ __('Search') }}:",
                    "zeroRecords": "{{ __('No matching records found') }}"
                },
                buttons: [],

                responsive: {
                    details: {}
                }
            });
        });
    </script>
@endsection
