<?php

namespace App\Exports;

use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataTableExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $queryBuilder;
    public $heading;

    public function __construct($queryBuilder, $heading = [])
    {
        $this->queryBuilder = $queryBuilder;
        $this->heading = $heading;
    }

    public function query()
    {
        return $this->queryBuilder;
    }

    public function headings(): array
    {
        return $this->heading['headers'];
    }

    /**
     * @inheritDoc
     */
    public function map($model): array
    {
        $columns = Arr::only($model->getOriginal(), $this->heading['original']);
        if (isset($this->heading['relations'])) {
            foreach ($model->getRelations() as $relationName => $relationModel) {
                $relationColumns = Arr::only($relationModel->getOriginal(), $this->heading['relations'][$relationName]);
                $columns = array_merge($columns, $relationColumns);
            }
        }
        return array_merge(array_flip($this->heading['columns']), $columns);
    }
}
