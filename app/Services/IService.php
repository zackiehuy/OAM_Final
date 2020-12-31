<?php

namespace App\Services;

interface IService
{
    public function getAll($query);
    public function getById($id, $query);
    public function update($id, $query, $request);
    public function delete($id, $query);
    public function create($query, $request);

    public function all();
    public function datatable();
    public function find($id);
    public function getWhere($column, $operator, $value);
    public function getWhereFirst($column, $operator, $value);
    public function pagination($perPage);
}
