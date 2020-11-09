<div class="bg-primary py-3 px-3">
    <form action="{{$route}}"
          class="lf-filter-form"
          method="get">
        <div class="d-md-flex">
            @if(isset($orderFields) && !empty($orderFields))
                <div class="lf-flex-1 ml-md-1">
                    <div class="form-group d-flex">
                        <div class="lf-select lf-flex-1">
                            <select class="form-control lf-filter-sort-by"
                                    name="{{$pageName}}-sort">
                                <option value="">{{ __('Sort by') }}</option>
                                @foreach($orderFields as $ofKey => $ofVal)
                                    <option
                                        value="{{$ofKey}}" {{return_get($pageName.'-sort',$ofKey)}}>{{$ofVal[1]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lf-select ml-1 lf-flex-1">
                            <select class="form-control lf-filter-order-by"
                                    name="{{$pageName}}-ord">
                                <option value="d"{{return_get($pageName.'-ord','d')}}>{{ __('Desc') }}</option>
                                <option value="a"{{return_get($pageName.'-ord','a')}}>{{ __('Asc') }}</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="btn btn-danger ml-1"><i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            @endif

            @if($displayDateFilter)
                <div class="lf-flex-1 ml-md-1">
                    <div class="form-group d-flex">
                        <div class="lf-flex-1">
                            <input type="text"
                                   class="form-control lf-filter-from-date datepicker"
                                   name="{{$pageName}}-frm"
                                   placeholder="From date"
                                   value="{{return_get($pageName.'-frm')}}">
                        </div>
                        <div class="ml-1 lf-flex-1">
                            <input type="text"
                                   class="form-control lf-filter-to-date datepicker"
                                   name="{{$pageName}}-to"
                                   placeholder="To date"
                                   value="{{return_get($pageName.'-to')}}">
                        </div>
                        <button type="submit"
                                class="btn btn-danger ml-1"><i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            @endif

            @if(isset($searchFields) && !empty($searchFields))
                <div class="lf-flex-2 ml-md-1">
                    <div class="d-flex lf-filter-search-group{{ $displayFilterButton ? ' lf-filter-search-mr' : '' }}">
                        <div class="lf-select lf-flex-3">
                            <select class="form-control"
                                    name="{{$pageName}}-ssf">
                                <option value="">{{ __('All Fields') }}</option>
                                @foreach($searchFields as $ssfKey => $ssfVal)
                                    <option
                                        value="{{$ssfKey}}" {{return_get($pageName.'-ssf',$ssfKey)}}>{{$ssfVal[1]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lf-select lf-flex-3 ml-1">
                            <select class="form-control lf-filter-comparison-operator select-compact"
                                    name="{{$pageName}}-comp">
                                <option
                                    value="lk"{{return_get($pageName.'-comp','lk')}}>{{__('Similar to')}}</option>
                                <option value="e"{{return_get($pageName.'-comp','e')}}>{{__('Exact to')}}</option>
                                <option
                                    value="l"{{return_get($pageName.'-comp','l')}}>{{__('Smaller than')}}</option>
                                <option
                                    value="le"{{return_get($pageName.'-comp','le')}}>{{__('Less or equal to')}}</option>
                                <option
                                    value="m"{{return_get($pageName.'-comp','m')}}>{{__('Bigger Than')}}</option>
                                <option
                                    value="me"{{return_get($pageName.'-comp','me')}}>{{__('Bigger or Equal to')}}</option>
                                <option
                                    value="ne"{{return_get($pageName.'-comp','ne')}}>{{__('Not Equal')}}</option>
                            </select>
                        </div>
                        <div class="lf-flex-5 ml-1">
                            <input type="text"
                                   class="form-control lf-filter-search mr-0"
                                   name="{{$pageName}}-srch"
                                   placeholder="search"
                                   value="{{return_get($pageName.'-srch')}}">
                        </div>
                        <button type="submit"
                                class="btn btn-danger ml-1"><i class="fa fa-search"></i></button>
                        @if($displayFilterButton)
                            <a href="javascript:"
                               class="lf-filter-toggler ml-1 btn btn-warning">
                                <i class="fa fa-filter"></i>
                            </a>
                        @endif

                        @if($displayDownloadButton)
                            <div class="lf-action ml-1">
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-sm btn-secondary dropdown-toggle"
                                            data-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa fa-download"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right"
                                         role="menu">
                                        @foreach(datatable_downloadable_type() as $item => $value)
                                            <a class="dropdown-item download"
                                               data-type="{{ $item }}"
                                               href="{{ url()->current() }}"><i
                                                    class="{{ $value['icon_class'] }}"></i> {{ $value['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($displayFilterButton || $displayDownloadButton)
                @if($displayFilterButton)
                    <a href="javascript:"
                       class="lf-filter-toggler ml-1 btn btn-warning">
                        <i class="fa fa-filter"></i>
                    </a>
                @endif

                @if($displayDownloadButton)
                    <div class="lf-action ml-1">
                        <div class="btn-group">
                            <button type="button"
                                    class="btn btn-sm btn-secondary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa fa-download"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right"
                                 role="menu">
                                @foreach(datatable_downloadable_type() as $item => $value)
                                    <a class="dropdown-item download"
                                       data-type="{{ $item }}"
                                       href="{{ url()->current() }}"><i
                                            class="{{ $value['icon_class'] }}"></i> {{ $value['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            @if(isset($displayFilterButton))
                @if(is_array(request()->input($pageName.'-fltr')))
                    @foreach(request()->input($pageName.'-fltr') as $key=>$value)
                        @if(is_array($value))
                            @foreach($value as $optionKey=>$optionValue)
                                @if(!empty($optionValue) && !is_array($optionValue))
                                    <input type="hidden"
                                           name="{{$pageName}}-fltr[{{$key}}][]"
                                           value="{{request()->input($pageName.'-fltr.'.$key.'.'.$optionKey)}}">
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endif
        </div>
    </form>
</div>
