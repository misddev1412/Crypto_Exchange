<?php


namespace App\Override\Eloquent;

use App\Override\Eloquent\LaraframeQueryBuilder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class LaraframeModel extends Model
{
    public static function bulkUpdate(array $attributes)
    {
        if (empty($attributes)) {
            return false;
        }

        $table = (new static())->getTable();

        $sql = "UPDATE {$table} SET ";
        $updatableFieldSeparator = '';
        $rowCount = count($attributes);

        $updatableFields = (new static())->getUpdatableFields($attributes);

        if (empty($updatableFields)) {
            return false;
        }

        foreach ($updatableFields as $updatableField) {
            //for each updatable field
            $sql .= $updatableFieldSeparator;
            $sql .= "{$updatableField} = (CASE";
            for ($i = 0; $i < $rowCount; $i++) {
                if (!isset($attributes[$i]['fields'][$updatableField])) {
                    continue;
                }
                $sql .= " WHEN ";
                $conditionalSyntax = '';

                // for each condition
                foreach ($attributes[$i]['conditions'] as $conditionalKey => $conditionalValue) {
                    if (is_array($conditionalValue)) {
                        $sql .= $conditionalSyntax . "{$conditionalKey} {$conditionalValue[0]} {$conditionalValue[1]}";
                    } else {
                        $sql .= $conditionalSyntax . "{$conditionalKey}='{$conditionalValue}'";
                    }
                    $conditionalSyntax = ' AND ';
                }

                $updatableFieldValue = $attributes[$i]['fields'][$updatableField];

                if (is_array($updatableFieldValue)) {
                    if ($updatableFieldValue[0] == 'increment') {
                        $sql .= " THEN {$updatableField} + {$updatableFieldValue[1]}";
                    } elseif ($updatableFieldValue[0] == 'decrement') {
                        $sql .= " THEN {$updatableField} - {$updatableFieldValue[1]}";
                    } else {
                        $sql .= " THEN {$updatableFieldValue[1]}";
                    }
                } else {
                    $sql .= " THEN '{$updatableFieldValue}'";
                }
            }
            $sql .= " ELSE {$updatableField} END) ";
            $updatableFieldSeparator = ', ';
        }

        $conditionalClause = "WHERE ";
        $conditionalFieldSeparator = '(';

        foreach ($attributes as $value) {
            $innerSeparator = '';
            $conditionalClause .= $conditionalFieldSeparator;
            foreach ($value['conditions'] as $conditionalKey => $conditionalValue) {
                if (is_array($conditionalValue)) {
                    $conditionalClause .= $innerSeparator . "{$conditionalKey} {$conditionalValue[0]} {$conditionalValue[1]}";
                } else {
                    $conditionalClause .= $innerSeparator . "{$conditionalKey}='{$conditionalValue}'";
                }
                $innerSeparator = ' AND ';
            }
            $conditionalClause .= ')';
            $conditionalFieldSeparator = ' OR (';
        }
        $sql .= $conditionalClause;

        return DB::update($sql);
    }

    private function getUpdatableFields($values)
    {
        $fields = [];
        $conditions = [];
        foreach ($values as $value) {
            if (count(array_intersect_key($value['fields'], $value['conditions'])) >= 2) {
                return false;
            }
            $fields = array_merge($fields, $value['fields']);
            $conditions = array_merge($conditions, $value['conditions']);
        }
        $fields = array_keys($fields);
        $conditions = array_keys($conditions);
        $commonFields = array_intersect($fields, $conditions);
        if (count($commonFields) >= 1) {
            $fields = array_merge(array_diff($fields, $commonFields), $commonFields);
        }
        return $fields;
    }

    public function toggleStatus(string $attribute = 'is_active')
    {
        $this->{$attribute} = !$this->{$attribute};
        return $this->update();
    }

    public function newEloquentBuilder($query){
        return new QueryBuilder($query);
    }
}
