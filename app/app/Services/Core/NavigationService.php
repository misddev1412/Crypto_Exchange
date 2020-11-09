<?php

namespace App\Services\Core;

use App\Http\Requests\Core\NavigationRequest;
use App\Models\Core\Navigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;

class NavigationService
{
    public $navigation;

    public function __construct(Navigation $navigation)
    {
        $this->navigation = $navigation;
    }

//--------------- frontend navigation functions ------------------------

    public function navigationSingle($navPlace, $template = 'default_nav')
    {
        $navConfig = config('navigation');
        $navData = Cache::get("navigation:$navPlace");
        if (is_null($navData)) {
            $navData = Navigation::where('slug', $navPlace)->first();
            if ($navData) {
                Cache::forever("navigation:$navPlace", $navData->items);
                $navData = $navData->items;
            }
        }
        if (empty($navData) || !is_array($navData)) {
            return '';
        }
        if (!isset($navConfig['navigation_template'][$template])) {
            $template = 'default_nav';
        }
        $navTemplate = $this->_template_builder($navConfig['navigation_template'][$template]);
        return $this->_navigationBuilder($navData, $navTemplate);
    }

    protected function _template_builder($navTemplate)
    {
        $all_features = [
            'navigation_item_beginning_wrapper_start' => null,
            'navigation_item_beginning_wrapper_end' => null,
            'navigation_item_text_wrapper_start' => null,
            'navigation_item_text_wrapper_end' => null,
            'navigation_item_ending_wrapper_start' => null,
            'navigation_item_ending_wrapper_end' => null,
            'navigation_item_icon_wrapper_start' => null,
            'navigation_item_icon_wrapper_end' => null,

            'navigation_sub_menu_wrapper_start' => null,
            'navigation_sub_menu_wrapper_end' => null,
            'navigation_item_wrapper_in_sub_menu_start' => null,
            'navigation_item_wrapper_in_sub_menu_end' => null,

            'navigation_item_icon_position' => null,
            'navigation_item_link_class' => null,
            'navigation_item_link_active_class' => null,
            'navigation_item_active_class_on_anchor_tag' => false,
            'navigation_item_no_link_text' => 'javascript:;',

            'mega_menu_wrapper_start' => null,
            'mega_menu_wrapper_end' => null,
            'mega_menu_section_wrapper_start' => null,
            'mega_menu_section_wrapper_end' => null,
        ];
        return array_merge($navTemplate, array_diff_key($all_features, $navTemplate));
    }

    protected function _navigationBuilder($navData, $navTemplate)
    {
        $allRoutes = Route::getRoutes()->getRoutesByMethod()['GET'];
        $allAvailableRoutes = [];
        foreach ($allRoutes as $routeName => $routeData) {
            $middleware = $routeData->middleware();
            if (!is_array($middleware)) {
                continue;
            }
            $parameters = $routeData->signatureParameters();
            $isMenuable = true;
            foreach ($parameters as $parameter) {
                if (!$parameter->isOptional())
                    $isMenuable = false;
                break;
            }
            if ($isMenuable &&
                (
                    (in_array('menuable', $middleware)) ||
                    (Auth::user() && in_array('permission', $middleware) && has_permission($routeData->getName())) ||
                    (!Auth::user() && in_array('guest.permission', $middleware)) ||
                    (in_array('verification.permission', $middleware) && Auth::user() && !Auth::user()->is_email_verified && settings('require_email_verification') == ACTIVE
                    )
                )
            ) {
                $allAvailableRoutes[] = $routeData->getName();
            }
        }

        $arrayColumn = array_column($navData, 'parent_id');
        $routeColumn = array_column($navData, 'route');
        foreach ($routeColumn as $key => $val) {
            if (!empty($val) && !in_array($val, $allAvailableRoutes)) {
                unset($arrayColumn[$key]);
            }
        }
        $output = $this->_tagBuilder($navTemplate['navigation_wrapper_start']);
        $output .= $this->_navigationInside($navData, $allAvailableRoutes, $navTemplate, $arrayColumn);
        $output .= $navTemplate['navigation_wrapper_end'];
        return $output;
    }

    protected function _tagBuilder($startingWrapper, $dbClass = null, $activeClass = null)
    {
        if ($startingWrapper == null) {
            return '';
        }
        if ($activeClass != null) {
            $dbClass = $dbClass == null ? $activeClass : ($activeClass == null ? '' : $activeClass . ' ' . $dbClass);
        }
        if ($dbClass != null) {
            if (strripos($startingWrapper, 'class="')) {
                $startingWrapper = substr_replace($startingWrapper, 'class="' . $dbClass . ' ', strripos($startingWrapper, 'class="'), 7);
            } elseif (strripos($startingWrapper, "class='")) {
                $startingWrapper = substr_replace($startingWrapper, "class='" . $dbClass . ' ', strripos($startingWrapper, 'class="'), 7);
            } else {
                $startingWrapper = substr_replace($startingWrapper, ' class="' . $dbClass . '">', -1);
            }
        }
        return $startingWrapper;
    }

    protected function _navigationInside($dbData, $allAvailableRoutes, $navTemplate, $arrayColumn, $parentId = 0, $level = 1, $megaMenu = 0)
    {
        $result = '';
        if ($level == 2 && $megaMenu == 1 && !is_null($navTemplate['mega_menu_wrapper_start'])) {
            $result .= $this->_tagBuilder($navTemplate['mega_menu_wrapper_start']);
        } elseif ($level > 1) {
            if (!is_null($navTemplate['navigation_sub_menu_wrapper_start'])) {
                $result .= $this->_tagBuilder($navTemplate['navigation_sub_menu_wrapper_start']);
            } else {
                $result .= $this->_tagBuilder($navTemplate['navigation_wrapper_start']);
            }
        }
        $inside = '';
        foreach ($dbData as $rowKey => $rowValue) {
            $innerInside = '';
            if ($rowValue['route'] != '' && !in_array($rowValue['route'], $allAvailableRoutes)) {
                continue;
            }
            if ($rowValue['parent_id'] == $parentId) {
                unset($dbData[$rowKey]);
                $tempList = $this->_listItemStartBuilder($rowValue, $navTemplate, $level, $megaMenu);
                if (!in_array($rowValue['order'], $arrayColumn) && in_array(strtolower($tempList['path']), ['#', 'javascript:;'])) {
                    $innerInside .= '';
                } else {
                    $innerInside .= $tempList['list'];
                }

                if (in_array($rowValue['order'], $arrayColumn)) {
                    $active_mega_menu = $rowValue['mega_menu'] == 1 ? 1 : $megaMenu;
                    $tempResult = $this->_navigationInside($dbData, $allAvailableRoutes, $navTemplate, $arrayColumn, $rowValue['order'], ($level + 1), $active_mega_menu);

                    if (empty($tempResult)) {
                        $innerInside = '';
                    } else {
                        $innerInside .= $tempResult;
                    }
                }

                if (!empty($innerInside)) {
                    if ($level == 2 && $megaMenu == 1 && !is_null($navTemplate['mega_menu_section_wrapper_start'])) {
                        $innerInside .= $this->_tagBuilder($navTemplate['mega_menu_section_wrapper_end']);
                    } else {
                        if ($level > 1 && !is_null($navTemplate['navigation_item_wrapper_in_sub_menu_start'])) {
                            $innerInside .= $this->_tagBuilder($navTemplate['navigation_item_wrapper_in_sub_menu_end']);
                        } else {
                            $innerInside .= $this->_tagBuilder($navTemplate['navigation_item_wrapper_end']);
                        }
                    }
                }
                if (!empty($innerInside)) {
                    $inside .= $innerInside;
                }
            }
        }
        if (!empty($inside)) {
            $result .= $inside;
            if ($level == 2 && $megaMenu == 1 && !is_null($navTemplate['mega_menu_wrapper_start'])) {
                $result .= $this->_tagBuilder($navTemplate['mega_menu_wrapper_end']);
            } elseif ($level > 1) {
                if (!is_null($navTemplate['navigation_sub_menu_wrapper_start'])) {
                    $result .= $this->_tagBuilder($navTemplate['navigation_sub_menu_wrapper_end']);
                } else {
                    $result .= $navTemplate['navigation_wrapper_end'];
                }
            }
        } else {
            $result = '';
        }
        return $result;
    }

    protected function _listItemStartBuilder($data, $navTemplate, $level, $megaMenu)
    {
        $beginningPart = '';
        $endingPart = '';
        $megamenu_ending = '';
        $linkBuilder = $this->_linkBuilder($data, $navTemplate);
        $activeClass = $linkBuilder['active_class'];
        $linkBeginning = $linkBuilder['link_beginning'];
        $linkEnding = $linkBuilder['link_ending'];
// full-left/full-right/top-left/top-right/bottom-left/bottom-right/text-left/text-right
        if ($data['beginning_text'] != null) {
            $beginningPart .= $navTemplate['navigation_item_beginning_wrapper_start'];
            if ($navTemplate['navigation_item_icon_position'] == 'top-left' &&
                !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
                $data['icon'] != null
            ) {
                $beginningPart .= $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'];
            }
            $beginningPart .= __($data['beginning_text']);
            $beginningPart .= $navTemplate['navigation_item_beginning_wrapper_end'];
            if ($navTemplate['navigation_item_icon_position'] == 'top-right' &&
                !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
                $data['icon'] != null
            ) {
                $beginningPart .= $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'];
            }
        }
        if ($data['ending_text'] != null) {
            $endingPart .= $navTemplate['navigation_item_ending_wrapper_start'];
            if ($navTemplate['navigation_item_icon_position'] == 'bottom-left' &&
                !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
                $data['icon'] != null
            ) {
                $endingPart .= $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'];
            }
            $endingPart .= __($data['ending_text']);
            $endingPart .= $navTemplate['navigation_item_ending_wrapper_end'];
            if ($navTemplate['navigation_item_icon_position'] == 'bottom-right' &&
                !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
                $data['icon'] != null
            ) {
                $endingPart .= $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'];
            }
        }
        if ($level == 2 && $megaMenu == 1 && !is_null($navTemplate['mega_menu_section_wrapper_start'])) {
            $mainTag = $this->_tagBuilder($navTemplate['mega_menu_section_wrapper_start'], $data['class'], $activeClass) . '<div class="megamenu-header">';
            $megamenu_ending = '</div>';
        } else {
            if ($level > 1 && !is_null($navTemplate['navigation_item_wrapper_in_sub_menu_start'])) {
                $mainTag = $this->_tagBuilder($navTemplate['navigation_item_wrapper_in_sub_menu_start'], $data['class'], $activeClass);
            } else {
                $mainTag = $this->_tagBuilder($navTemplate['navigation_item_wrapper_start'], $data['class'], $activeClass);
            }
        }
        if (
            $navTemplate['navigation_item_icon_position'] == 'text-right' &&
            !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
            $data['icon'] != null
        ) {
            $output = $mainTag . $linkBeginning . $beginningPart . $navTemplate['navigation_item_text_wrapper_start'] . __($data['name']) . $navTemplate['navigation_item_text_wrapper_end'] . $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'] . $endingPart . $linkEnding . $megamenu_ending;
        } elseif (
            $navTemplate['navigation_item_icon_position'] == 'text-left' &&
            !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
            $data['icon'] != null
        ) {
            $output = $mainTag . $linkBeginning . $beginningPart . $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'] . $navTemplate['navigation_item_text_wrapper_start'] . __($data['name']) . $navTemplate['navigation_item_text_wrapper_end'] . $endingPart . $linkEnding . $megamenu_ending;
        } elseif (
            $navTemplate['navigation_item_icon_position'] == 'full-right' &&
            !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
            $data['icon'] != null
        ) {
            $output = $mainTag . $linkBeginning . $beginningPart . $navTemplate['navigation_item_text_wrapper_start'] . __($data['name']) . $navTemplate['navigation_item_text_wrapper_end'] . $endingPart . $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'] . $linkEnding . $megamenu_ending;
        } elseif (
            $navTemplate['navigation_item_icon_position'] == 'full-left' &&
            !is_null($navTemplate['navigation_item_icon_wrapper_start']) &&
            $data['icon'] != null
        ) {
            $output = $mainTag . $linkBeginning . $this->_tagBuilder($navTemplate['navigation_item_icon_wrapper_start'], $data['icon']) . $navTemplate['navigation_item_icon_wrapper_end'] . $beginningPart . $navTemplate['navigation_item_text_wrapper_start'] . __($data['name']) . $navTemplate['navigation_item_text_wrapper_end'] . $endingPart . $linkEnding . $megamenu_ending;
        } else {
            $output = $mainTag . $linkBeginning . $beginningPart . __($data['name']) . $endingPart . $linkEnding . $megamenu_ending;
        }
        return [
            'list' => $output,
            'path' => $linkBuilder['path']
        ];
    }

// For single nav use

    protected function _linkBuilder($dbData, $navTemplate)
    {
        $path = $navTemplate['navigation_item_no_link_text'];
        if ($dbData['route'] != '') {
            $path = route($dbData['route']);
        } else {
            if (strpos($dbData['custom_link'], 'http://') === 0 || strpos($dbData['custom_link'], 'https://') === 0) {
                if (strpos($dbData['custom_link'], '.') >= 8) {
                    $path = $dbData['custom_link'];
                }
            } elseif (strpos($dbData['custom_link'], 'www.') === 0) {
                $path = 'http://' . $dbData['custom_link'];
            } elseif ($dbData['custom_link'] == 'javascript:;') {
                $path = $dbData['custom_link'];
            } else {
                $path = asset($dbData['custom_link']);
            }
        }
        $activeClass = $navTemplate['navigation_item_link_active_class'] == '' ? 'link-active' : $navTemplate['navigation_item_link_active_class'];
        $activeClass = !empty($dbData['route']) && $dbData['route'] == request()->route()->getName() ? $activeClass : (url()->current() == $path ? $activeClass : '');
        $class = '';

        if ($navTemplate['navigation_item_active_class_on_anchor_tag'] === true && $activeClass !== '') {
            $class = $activeClass;
        }
        if ($class == '' && !is_null($navTemplate['navigation_item_link_class'])) {
            $class = $navTemplate['navigation_item_link_class'];
        } elseif ($class != '' && !is_null($navTemplate['navigation_item_link_class'])) {
            $class = $class . ' ' . $navTemplate['navigation_item_link_class'];
        }

        $blank = $dbData['new_tab'] == 1 ? ' target="_blank"' : '';
        $linkBeginning = '<a href ="' . $path . '" class="' . $class . '"' . $blank . '>';
        $linkEnding = '</a>';
        if ($navTemplate['navigation_item_active_class_on_anchor_tag'] === true) {
            $activeClass = '';
        }
        return [
            'link_beginning' => $linkBeginning,
            'link_ending' => $linkEnding,
            'active_class' => $activeClass,
            'path' => $path
        ];
    }

//--------------- backend navigation functions ------------------------

    public function backendMenuBuilder($slug)
    {
        $data['navigationPlaces'] = config('navigation.registered_place');
        $data['slug'] = empty($slug) ? $data['navigationPlaces'][0] : $slug;
        $data['allRoutes'] = Route::getRoutes()->getRoutesByMethod()['GET'];
        $data['menuItems'] = Navigation::where('slug', $data['slug'])->first();
        $data['menu'] = '<ol class="mymenu">';
        if ($data['menuItems']) {
            $data['menu'] .= $this->backendInnerMenu($data['menuItems']->items);
        }
        $data['menu'] .= '</ol>';
        $data['menu'] = new HtmlString($data['menu']);
        return $data;
    }

    protected function backendInnerMenu($dbData, $parentId = 0, $result = NULL)
    {
        foreach ($dbData as $row) {
            $count = 0;
            if ($row['parent_id'] == $parentId) {
                $ol = FALSE;
                $parentOrder = $row['order'];
                $result .= view('core.renderable_template._backend_navigation', ['row' => $row])->render();
                foreach ($dbData as $rowInside) {
                    if ($rowInside['parent_id'] == $parentOrder && $count < 1) {
                        if ($ol == FALSE) {
                            $result .= '<ol>';
                            $ol = TRUE;
                        }
                        $count++;
                        $result .= $this->backendInnerMenu($dbData, $parentOrder);
                    }
                }
                if ($ol == TRUE) {
                    $result .= '</ol>';
                    $ol == FALSE;
                }
                $result .= '</li>';
            }
        }
        return $result;
    }

    public function backendMenuSave(NavigationRequest $request, $slug)
    {
        if (!in_array($slug, config('navigation.registered_place'))) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __('The navigation slug is invalid')
            ];
        }
        $menuItems = $request->menu_item;
        if (empty($menuItems) || !is_array($menuItems)) {
            $menuItems = [];
        }

        $reorderedMenuItems = array_column($menuItems, "order");
        array_multisort($reorderedMenuItems, SORT_ASC, $menuItems);

        $data = [
            'slug' => $slug,
            'items' => $menuItems
        ];

        $availableNavigation = Navigation::updateOrCreate(['slug' => $slug], $data);
        if ($availableNavigation) {
            Cache::forever("navigation:$slug", $data['items']);

            return [
                RESPONSE_STATUS_KEY => true,
                RESPONSE_MESSAGE_KEY => __('Menu has been saved successfully. Refresh the page to check the changes')
            ];
        }
        return [
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __('Menu can not be saved')
        ];
    }
}
