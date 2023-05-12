<?php

namespace App\Reports;

abstract class Report
{
    protected $table_name;
    protected $columns = [];
    protected $queryBuilder;
    protected $records;

    protected $filters = [];

    abstract protected function prepareTable();
    abstract protected function prepareQueryBuilder();
    abstract protected function formatData();

    public function getPaginatedData()
    {
        $this->records = $this->queryBuilder->paginate(10)->withQueryString();
        $this->formatData();
        return $this->records;
    }

    public function getAllData()
    {
        $this->records = $this->queryBuilder->get();
        $this->formatData();
        return $this->records;
    }

    protected function addColumn($column)
    {
        $this->columns[] = $column;
    }

    protected function addFilter($name, $type)
    {
        $this->filters[] = [
            'name' => $name,
            'type' => $type,
        ];
    }

    protected function applyFilters()
    {
        $filters = request()->get('filters');

        if (!is_array($filters)) {
            $filters = json_decode($filters, true);
        }

        //dd($filters);
        if (isset($filters)) {
            foreach ($filters as $key => $filter) {

                if ($filter['relation'] == 'and') {
                    $this->andFiltration($key, $filter);
                } else if ($filter['relation'] == 'or') {
                    $this->orFiltration($key, $filter);
                }

            }
        }

    }

    protected function applyOrders()
    {
        //dd(request('order'));
        $column = request()->get('order');

        if (!$column) {
            $column = 'orders_total';
        }

        $this->queryBuilder->orderBy($column, 'DESC');
    }

    public function render()
    {
        $this->prepareTable();
        $this->prepareQueryBuilder();
        return $this;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    protected function andFiltration($key, $filter)
    {
        switch ($filter['op']) {
            case 'between':{
                    $this->queryBuilder->whereBetween($key, [$filter['fromVal'], $filter['toVal']]);
                    break;
                }
            case 'like':{
                    $this->queryBuilder->where($key, 'like', '%' . $filter['value'] . '%');
                    break;
                }
            default:{
                    $this->queryBuilder->where($key, $filter['op'], $filter['value']);
                    break;
                }
        }
    }

    protected function orFiltration($key, $filter)
    {
        switch ($filter['op']) {
            case 'between':{
                    $this->queryBuilder->orWhereBetween($key, [$filter['fromVal'], $filter['toVal']]);
                    break;
                }
            case 'like':{
                    $this->queryBuilder->orWhere($key, 'LIKE', '%' . $filter['value'] . '%');
                    break;
                }
            default:{
                    $this->queryBuilder->orWhere($key, $filter['op'], $filter['value']);
                    break;
                }
        }
    }
}
