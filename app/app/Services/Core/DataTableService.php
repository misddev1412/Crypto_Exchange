<?php

namespace App\Services\Core;

use App\Exports\DataTableExport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class DataTableService
{
    /**
     * @var Request
     */
    public $request;
    protected $paginationInfo;
    protected $searchFields = [];
    protected $orderFields = [];
    protected $filterFields = [];
    protected $headings = [];
    protected $appends = [];
    protected $displayDateFilter = true;
    protected $displayDownloadButton = false;

    public function __construct(Request $request)
    {
        $this->paginationInfo = [
            'pageName' => PAGINATION_PAGE_NAME,
            'itemPerPage' => PAGINATION_ITEM_PER_PAGE,
            'eachSide' => PAGINATION_EACH_SIDE,
        ];
        $this->request = $request;
    }


    public function create($queryBuilder)
    {
        $frm = !is_array($this->request->get($this->paginationInfo['pageName'] . '-frm')) ? $this->request->get($this->paginationInfo['pageName'] . '-frm') : null;
        $to = !is_array($this->request->get($this->paginationInfo['pageName'] . '-to')) ? $this->request->get($this->paginationInfo['pageName'] . '-to') : null;

        if ($frm) {
            $queryBuilder->whereDate($queryBuilder->getModel()->getTable() . '.created_at', '>=', $frm);
        }

        if ($to) {
            $queryBuilder->whereDate($queryBuilder->getModel()->getTable() . '.created_at', '<=', $to);
        }

        $this->filter($queryBuilder);

        $this->search($queryBuilder);

        $this->orderBy($queryBuilder);

        if ($this->request->ajax()) {
            $extension = $this->request->get('type', 'csv');
            $name = random_string(10) . '.' . datatable_downloadable_type($extension)['extension'];
            $file = Excel::raw(new DataTableExport($queryBuilder, $this->headings), constant('\Maatwebsite\Excel\Excel::' . strtoupper($extension)));
            response()->json(['file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($file), 'name' => $name])->send();
            die();
        }
        $paginationObject = $queryBuilder->paginate($this->paginationInfo['itemPerPage'], ['*'], $this->paginationInfo['pageName'])->appends($this->appends);
        return $this->makeView($paginationObject);
    }

    private function filter(&$queryBuilder)
    {
        $filterRequest = $this->request->get($this->paginationInfo['pageName'] . '-fltr');

        if (!empty($this->filterFields)) {
            if (is_array($filterRequest)) {
                $filterRequest = array_intersect_key($filterRequest, $this->filterFields);
                $this->appends[$this->paginationInfo['pageName'] . '-fltr'] = $filterRequest;

                foreach ($filterRequest as $key => $value) {
                    if (is_array($this->filterFields[$key][2])) {
                        if (isset($this->filterFields[$key][3])) {
                            $queryBuilder->whereHas($this->filterFields[$key][3], function ($query) use ($key, $value) {
                                $query->whereIn($this->filterFields[$key][0], $value);
                            });
                        } else {
                            $queryBuilder->whereIn($this->filterFields[$key][0], $value);
                        }
                    } else if ($this->filterFields[$key][2] === 'preset') {
                        if (isset($this->filterFields[$key][3]) && !is_null($this->filterFields[$key][3])) {
                            $queryBuilder->whereHas($this->filterFields[$key][3], function ($query) use ($key, $value) {
                                $query->where(function ($subQuery) use ($key, $value) {
                                    foreach ($value as $presetKey => $presetValue) {
                                        if (isset($this->filterFields[$key][4][$presetKey])) {
                                            $subQuery->orWhere($this->filterFields[$key][0], $this->filterFields[$key][4][$presetKey][1], $this->filterFields[$key][4][$presetKey][2]);
                                        }
                                    }
                                });
                            });
                        } else {
                            foreach ($value as $presetKey => $presetValue) {
                                if (isset($this->filterFields[$key][4][$presetKey])) {
                                    $queryBuilder->where($this->filterFields[$key][0], $this->filterFields[$key][4][$presetKey][1], $this->filterFields[$key][4][$presetKey][2]);
                                }
                            }
                        }

                    } else if ($this->filterFields[$key][2] === 'range') {
                        if (isset($this->filterFields[$key][3])) {
                            $queryBuilder->whereHas($this->filterFields[$key][3], function ($query) use ($key, $value) {
                                if (isset($value[0]) && is_numeric($value[0])) {
                                    $query->where($this->filterFields[$key][0], '>=', $value[0]);
                                }
                                if (isset($value[1]) && is_numeric($value[1])) {
                                    $query->where($this->filterFields[$key][0], '<=', $value[1]);
                                }
                            });
                        } else {
                            if (isset($value[0]) && is_numeric($value[0])) {
                                $queryBuilder->where($this->filterFields[$key][0], '>=', $value[0]);
                            }
                            if (isset($value[1]) && is_numeric($value[1])) {
                                $queryBuilder->where($this->filterFields[$key][0], '<=', $value[1]);
                            }
                        }
                    } else {
                        unset($filterRequest[$key]);
                    }
                }
            }
        }
    }

    private function search(&$queryBuilder)
    {
        $searchOperator = !is_array($this->request->get($this->paginationInfo['pageName'] . '-comp')) ? $this->request->get($this->paginationInfo['pageName'] . '-comp') : null;
        $searchField = !is_array($this->request->get($this->paginationInfo['pageName'] . '-ssf')) ? $this->request->get($this->paginationInfo['pageName'] . '-ssf') : null;
        $searchTerm = !is_array($this->request->get($this->paginationInfo['pageName'] . '-srch')) ? $this->request->get($this->paginationInfo['pageName'] . '-srch') : null;

        if (!empty($searchOperator) && !empty($searchTerm)) {
            $comparison = ['e' => '=', 'lk' => 'like', 'ne' => '!='];
            $operator = array_key_exists($searchOperator, $comparison) ? $comparison[$searchOperator] : $comparison = 'LIKE';

            $searchFields = [];
            foreach ($this->searchFields as $key => $searchRow) {
                if (strlen($searchField) > 0 && $key == $searchField) {
                    unset($searchFields);
                    if (isset($searchRow[2])) {
                        $searchFields['relations'][$searchRow[2]][] = $searchRow[0];
                    } else {
                        $searchFields['original'][] = $searchRow[0];
                    }
                    break;
                }

                if (isset($searchRow[2])) {
                    $searchFields['relations'][$searchRow[2]][] = $searchRow[0];
                } else {
                    $searchFields['original'][] = $searchRow[0];
                }
            }

            $this->appends[$this->paginationInfo['pageName'] . '-comp'] = $searchOperator;
            $this->appends[$this->paginationInfo['pageName'] . '-ssf'] = $searchField;
            $this->appends[$this->paginationInfo['pageName'] . '-srch'] = $searchTerm;

            if (strtolower($operator) === 'like') {
                $queryBuilder->likeSearch($searchFields, $searchTerm);
            } else {
                $queryBuilder->strictSearch($searchFields, $searchTerm, $operator);
            }
        }
    }

    private function orderBy(&$queryBuilder)
    {
        $orderColumn = !is_array($this->request->get($this->paginationInfo['pageName'] . '-sort')) ? $this->request->get($this->paginationInfo['pageName'] . '-sort') : null;
        $orderValue = !is_array($this->request->get($this->paginationInfo['pageName'] . '-ord')) ? $this->request->get($this->paginationInfo['pageName'] . '-ord') : null;

        $orderableColumn = null;
        $orderableRelation = null;
        foreach ($this->orderFields as $key => $orderRow) {
            if (strlen($orderColumn) > 0 && $key == $orderColumn) {
                unset($orderFields);
                if (isset($orderRow[2])) {
                    $orderableColumn = $orderRow[0];
                    $orderableRelation = $orderRow[2];
                } else {
                    $orderableColumn = $orderRow[0];
                }
                break;
            }
        }

        $orderableValue = (strtolower($orderValue) === 'a') ? 'asc' : ((strtolower($orderValue) === 'd') ? 'desc' : null);
        if (!empty($orderableColumn) && !empty($orderableValue)) {
            $queryBuilder->getQuery()->orders = null;

            if ($orderableRelation) {
                $relation = $queryBuilder->getRelation($orderableRelation);
                $columnAs = "{$orderableColumn}_ord";
                $queryBuilder->orderBy(
                    $relation->getModel()::select("{$orderableColumn} as {$columnAs}")
                        ->whereColumn($this->getRelationshipColumn($relation), $relation->getParent()->getTable() . '.' . $this->getRelationshipParentColumn($relation))
                        ->limit(1),
                    $orderableValue
                );
            } else {
                $queryBuilder->orderBy($orderableColumn, $orderableValue);
            }

            $this->appends[$this->paginationInfo['pageName'] . '-ord'] = $orderValue;
            $this->appends[$this->paginationInfo['pageName'] . '-sort'] = $orderColumn;
        }

    }

    private function getRelationshipColumn(Relation $relation)
    {
        if ($relation instanceof HasOne) {
            return $relation->getForeignKeyName();
        } elseif ($relation instanceof BelongsTo) {
            return $relation->getOwnerKeyName();
        } else {
            return $relation->getModel()->getKeyName();
        }
    }

    private function getRelationshipParentColumn($relation)
    {
        if ($relation instanceof HasOne) {
            return $relation->getLocalKeyName();
        } elseif ($relation instanceof BelongsTo) {
            return $relation->getForeignKeyName();
        } else {
            return $relation->getParent()->getKeyName();
        }
    }

    public function makeView($paginationObject)
    {
        $route = url()->current();
        $data['items'] = $paginationObject;
        // prepare filters
        $data['filters'] = new HtmlString(view('core.renderable_template.filters', [
            'pageName' => $this->paginationInfo['pageName'],
            'route' => $route,
            'orderFields' => $this->orderFields,
            'searchFields' => $this->searchFields,
            'displayDateFilter' => $this->displayDateFilter,
            'displayFilterButton' => empty($this->filterFields) ? false : true,
            'displayDownloadButton' => $this->displayDownloadButton
        ])->render());

        $data['advanceFilters'] = new HtmlString(view('core.renderable_template.advance_filters', [
            'pageName' => $this->paginationInfo['pageName'],
            'route' => $route,
            'filterFields' => $this->filterFields
        ])->render());

        // prepare pagination
        $data['pagination'] = new HtmlString(view('core.renderable_template.pagination',
            [
                'itemPerPage' => $this->paginationInfo['itemPerPage'],
                'eachSide' => $this->paginationInfo['eachSide'],
                'paginationObject' => $paginationObject,
                'pageName' => $this->paginationInfo['pageName'],
                'route' => $route
            ])->render());

        return $data;
    }

    /**
     * @param array $searchFields
     * @return DataTableService
     */
    public function setSearchFields(array $searchFields)
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    /**
     * @param array $orderFields
     * @return DataTableService
     */
    public function setOrderFields(array $orderFields)
    {
        $this->orderFields = $orderFields;
        return $this;
    }

    /**
     * @param array $filterFields
     * @return DataTableService
     */
    public function setFilterFields(array $filterFields)
    {
        $this->filterFields = $filterFields;
        return $this;
    }

    /**
     * @param $paginationInfo
     * @return DataTableService
     */
    public function setPaginationInfo($paginationInfo)
    {
        foreach ($paginationInfo as $item => $value) {
            $this->paginationInfo[$item] = $value;
        }
        return $this;
    }

    /**
     * @return DataTableService
     */
    public function withoutDateFilter()
    {
        $this->displayDateFilter = false;
        return $this;
    }

    /**
     * @param $headings
     * @return DataTableService
     */
    public function downloadable(array $headings = [])
    {
        foreach ($headings as $heading) {
            $this->headings['columns'][] = $heading[0];
            $this->headings['headers'][] = $heading[1];
            if (isset($heading[2])) {
                $this->headings['relations'][$heading[2]][] = $heading[0];
            } else {
                $this->headings['original'][] = $heading[0];
            }
        }
        $this->displayDownloadButton = true;
        return $this;
    }
}
