@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="my-4">
                    @component('components.table',['class'=> 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Title') }}</th>
                                <th class="all">{{ __('Slug') }}</th>
                                <th class="min-desktop">{{ __('Meta Description') }}</th>
                                <th class="min-desktop">{{ __('Meta Keywords') }}</th>
                                <th class="all text-center">{{ __('Status') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$page)
                            <tr>
                                <td>{{ $page->title }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>{{ $page->meta_description }}</td>
                                <td>{{ array_to_string($page->meta_keywords, ',', false) }}</td>
                                <td class="text-center">
                                <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $page->is_published . '.color_class') }}">{{ publish_status($page->is_published) }}
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
                                            @if(has_permission('page.index'))
                                                <a href="{{ route('page.index', $page->slug) }}"
                                                   target="_blank"
                                                   class="dropdown-item"><i class="fa fa-eye"></i> {{ __('Show') }}</a>
                                            @endif
                                            @if(has_permission('pages.edit'))
                                                <a href="{{ route('pages.edit', $page->slug) }}"
                                                   class="dropdown-item"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                                            @endif
                                            @if(has_permission('pages.toggle-status'))
                                                <a href="{{ route('pages.toggle-status', $page->slug) }}"
                                                   class="dropdown-item confirmation"
                                                   data-form-id="put-{{ $page->slug }}"
                                                   data-form-method="put"
                                                   href="{{ route('pages.toggle-status', $page->slug) }}"
                                                   data-alert="{{$page->is_published? __('Do you want to unpublished this page?'): __('Do you want to published this page?')}}">
                                                    @if($page->is_published)
                                                        <i class="fa fa-refresh"></i>
                                                        {{ __('Unpublished') }}
                                                    @else
                                                        <i class="fa fa-check-square-o"></i>
                                                        {{ __('Published') }}
                                                    @endif
                                                </a>
                                            @endif
                                            @if(has_permission('pages.destroy'))
                                                <a href="{{ route('pages.destroy', $page->slug) }}"
                                                   class="dropdown-item confirmation"
                                                   data-form-id="delete-{{ $page->slug }}"
                                                   data-form-method="delete"
                                                   href="{{ route('pages.destroy', $page->slug) }}"
                                                   data-alert="{{__('Do you want to remove this page?')}}"><i class="fa fa-trash"></i> {{ __('Destroy') }}
                                                </a>
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
