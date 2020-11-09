@extends('layouts.master',['activeSideNav' => active_side_nav()])

@section('title', $title)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                <div class="my-4">
                    @component('components.table',['class' => 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Name') }}</th>
                                <th class="min-phone-l text-center">{{ __('Short Code') }}</th>
                                <th class="min-phone-l text-center">{{ __('Icon') }}</th>
                                <th class="min-phone-l text-center">{{ __('Default') }}</th>
                                <th class="min-phone-l text-center">{{ __('Status') }}</th>
                                <th class="all no-sort text-right">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $language)
                            <tr>
                                <td>{{$language->name}}</td>
                                <td class="text-center">{{$language->short_code}}</td>
                                <td class="text-center">
                                    @if($language->icon)
                                        <img width="40" height="25" src="{{ get_language_icon($language->icon) }}"
                                             alt="Icon">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($language->short_code == settings('lang'))
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ active_status($language->is_active) }}</td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                            <a class="dropdown-item"
                                               href="{{ route('languages.edit',$language->id) }}"><i
                                                    class="fa fa-pencil"></i> {{ __('Edit') }}</a>
                                            <a class="dropdown-item confirmation" data-alert="{{__('Are you sure?')}}"
                                               data-form-id="urm-{{$language->id}}" data-form-method='delete'
                                               href="{{ route('languages.destroy',$language->id) }}"><i
                                                    class="fa fa-trash-o"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endcomponent
                </div>
                {{ $dataTable['pagination'] }}
            </div>
        </div>
    </div>


@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection


