@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="my-4">
                    @component('components.table',['class'=> 'lf-data-table lf-toggle-text-color'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Name') }}</th>
                                <th class="all">{{ __('Slug') }}</th>
                                <th class="text-center">{{ __('Active Status') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$postCategory)
                            <tr>
                                <td>{{ $postCategory->name }}</td>
                                <td>{{ $postCategory->slug }}</td>
                                <td class="text-center">
                                    <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $postCategory->is_active . '.color_class') }}">{{ active_status($postCategory->is_active) }}
                                    </span>
                                </td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             role="menu">
                                            @if(has_permission('post-categories.edit'))
                                                <a href="{{ route('post-categories.edit', $postCategory->slug) }}"
                                                   class="dropdown-item"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                                            @endif
                                            @if(has_permission('post-categories.toggle-status'))
                                                <a data-form-id="update-{{ $postCategory->slug }}"
                                                   data-form-method="PUT"
                                                   href="{{ route('post-categories.toggle-status', $postCategory->slug) }}"
                                                   class="dropdown-item confirmation"
                                                   data-alert="{{__('Do you want to change this post category\'s status?')}}"><i
                                                        class="fa fa-check-square-o"></i> {{ __('Change Status') }}</a>
                                            @endif
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
