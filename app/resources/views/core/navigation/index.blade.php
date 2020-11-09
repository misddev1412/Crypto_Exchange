@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('style')
    <link rel="stylesheet"
          href="{{mix('css/menu-builder.css')}}">
@endsection
@section('content')
    <div class="container my-5 lf-backend-navigation-wrapper">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-outline card-info lf-toggle-border-color lf-toggle-bg-card mb-4 shadow-sm">
                    <div class="card-header bg-primary">
                        <h5 class="text-white">{{ __('Select Nav') }}</h5>
                    </div>
                    <div class="card-body">
                        <nav class="nav nav-pills flex-column">
                            @foreach($navigationPlaces as $navigationPlace)
                                <a class="nav-link {{ $slug == $navigationPlace ? 'active bg-info' : '' }}"
                                   href="{{route('menu-manager.index',$navigationPlace)}}">{{ucfirst(str_replace('-',' ',$navigationPlace))}}</a>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <div class="card lf-toggle-border-color lf-toggle-bg-card card-outline card-info mb-4 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary">
                        <h5 class="text-white">{{ __('Add Routes') }}</h5>
                    </div>
                    <div class="card-header lf-toggle-border-color">
                        <input id="search-route"
                               type="text"
                               class="form-control lf-toggle-border-color lf-toggle-bg-input"
                               placeholder="search">
                    </div>
                    <div class="card-body px-4 py-2">
                        <?php $count = 1; ?>
                        <div class="ml-n1">
                            <div id="all-routes"
                                 class="lf-h-150px overflow-auto content-box ml-n2 mr-n4"
                                 data-name="Unnamed">
                                @foreach($allRoutes as $routeName => $routeData)
                                    @if(is_null($routeData->getName()))
                                        @continue
                                    @endif
                                    @php
                                        $middleware = $routeData->middleware();
                                        $parameters = $routeData->signatureParameters();
                                        $isMenuable = true
                                    @endphp
                                    @if(is_array($middleware) && count(array_intersect($middleware,['permission','guest.permission','verification.permission','menuable']))>0)
                                        @foreach($parameters as $parameter)
                                            @if(!$parameter->isOptional())
                                                @php($isMenuable = false)
                                                @break
                                            @endif
                                        @endforeach
                                    @else
                                        @php($isMenuable = false)
                                    @endif
                                    @if($isMenuable)
                                        <?php
                                        $route = explode('/{', $routeName)[0];
                                        if ($route == '/' || $route == '' || strlen($route) == 2) {
                                            $route = 'home';
                                        } else {
                                            if (strpos($route, '/') == 2) {
                                                $route = substr($route, 3);
                                            }
                                            $route = strtolower(str_replace('/', ' - ', str_replace('-', ' ', $route)));
                                        }
                                        ?>
                                        <div class="checkbox lf-checkbox">
                                            <input id="checkbox-route-{{$count}}"
                                                   type="checkbox"
                                                   class="flat-red route-check-box"
                                                   value="{{$routeData->getName()}}">
                                            <label for="checkbox-route-{{$count}}"
                                                   class="mb-0">{{$route}}</label>
                                        </div>
                                        <?php $count++; ?>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer lf-toggle-border-color">
                        <button class="btn btn-block btn-sm btn-info"
                                id="add-route">{{ __('Add Route') }}</button>
                    </div>
                </div>

                <div class="card lf-toggle-border-color lf-toggle-bg-card card-outline card-info shadow-sm">
                    <div class="card-header bg-primary">
                        <h5 class="text-white">{{ __('Add LINK') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text"
                                   id="link-data"
                                   placeholder="Enter url"
                                   class="form-control lf-toggle-border-color lf-toggle-bg-input">
                        </div>
                        <div class="form-group mb-0">
                            <input type="text"
                                   data-name="Unnamed"
                                   id="link-name"
                                   placeholder="Enter Menu Item Name"
                                   class="form-control lf-toggle-border-color lf-toggle-bg-input">
                        </div>
                    </div>
                    <div class="card-footer lf-toggle-border-color">
                        <button class="btn btn-block btn-sm btn-info"
                                id="add-link">{{ __('Add A custom Link') }}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card lf-toggle-border-color lf-toggle-bg-card card-outline card-info">
                    <div class="card-header bg-primary">
                        <h5 class="text-white">{{ __('Menu Items') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('menu-manager.save', $slug)}}"
                              id="menu-form">
                            <div class="w-100 overflow-hidden">
                                {{ $menu }}
                            </div>
                            {{--                            <button id="form-submit-button" type="submit" class="lf-d-none">{{ __('Save Menu') }}</button>--}}
                        </form>
                    </div>
                    <div class="card-footer lf-toggle-border-color">
                        <button class="btn btn-sm btn-info menu-submit">{{ __('Save Menu') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('plugins/jQueryUI/jquery-ui.min.js')}}"></script>
    <script src="{{asset('plugins/menu_manager/jquery.mjs.nestedSortable.js')}}"></script>
    <script src="{{asset('plugins/menu_manager/adminmenuhandler.js')}}"></script>
@endsection
