<?php


namespace App\Override\Eloquent;


use Illuminate\Database\Eloquent\Builder;

class LaraframeQueryBuilder extends Builder
{

    public function active(string $attribute = 'is_active')
    {
        $this->where($attribute, ACTIVE);
        return $this;
    }

    public function likeSearch(array $searchableColumns, string $term = null)
    {
        if (empty($searchableColumns) || empty($term)) {
            return $this;
        }

        $this->where(function ($query) use ($searchableColumns, $term) {
            if (isset($searchableColumns['original'])) {
                foreach ($searchableColumns['original'] as $searchableColumn) {
                    foreach ($this->convertSearchTermToArray($term) as $termPart) {
                        $query->orWhere($searchableColumn, 'like', '%' . $termPart . '%');
                    }
                }
            }

            if (isset($searchableColumns['relations'])) {
                foreach ($searchableColumns['relations'] as $relation => $relationColumns) {
                    $query->orWhereHas($relation, function ($relationQuery) use ($relationColumns, $term) {
                        $relationQuery->where(function ($query) use ($relationColumns, $term) {
                            foreach ($relationColumns as $relationColumn) {
                                foreach ($this->convertSearchTermToArray($term) as $termPart) {
                                    $query->orWhere($relationColumn, 'like', '%' . $termPart . '%');
                                }
                            }
                        });
                    });
                }
            }
        });

        return $this;
    }

    private function convertSearchTermToArray($term)
    {
        $reservedSymbols = ['-', '+', '<', '>', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        return explode(' ', $term);
    }

    public function strictSearch(array $searchableColumns, string $term = null, $operator = '=')
    {
        if (empty($searchableColumns) || empty($term)) {
            return $this;
        }

        $this->where(function ($query) use ($searchableColumns, $term) {
            if (isset($searchableColumns['original'])) {
                foreach ($searchableColumns['original'] as $searchableColumn) {
                    foreach ($this->convertSearchTermToArray($term) as $termPart) {
                        $query->orWhere($searchableColumn, $termPart);
                    }
                }
            }

            if (isset($searchableColumns['relations'])) {
                foreach ($searchableColumns['relations'] as $relation => $relationColumns) {
                    $query->orWhereHas($relation, function ($relationQuery) use ($relationColumns, $term) {
                        $relationQuery->where(function ($query) use ($relationColumns, $term) {
                            foreach ($relationColumns as $relationColumn) {
                                foreach ($this->convertSearchTermToArray($term) as $termPart) {
                                    $query->orWhere($relationColumn, $termPart);
                                }
                            }
                        });
                    });
                }
            }
        });

        return $this;
    }


}
