<?php

namespace App\Services;

use App\Models\Requests;
use Carbon\Carbon;
use Illuminate\Support\collection;

abstract class BaseService implements IService
{

    protected $model;
    protected $limit = 10;
    protected $column = 'id';
    protected $sort = 'asc';
    protected $mapping = [];

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    protected function getModelClass()
    {
        if(!method_exists($this,'model')){
            throw new ModelNotDefined();
        }
        return app()->make($this->model());
    }
    public function all(){
        return $this->model->get();
    }
    public function datatable()
    {
        $query = $this->model->orderBy('id', 'DESC')->get();
        return datatable($query)->make(true);
    }
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
    public function getWhere($column, $operator, $value)
    {
        return $this->model->where($column, $operator, $value)->get();
    }
    public function getWhereFirst($column, $operator, $value)
    {
        return $this->model->where($column, $operator, $value)->firstOrFail();
    }
    public function pagination($perPage)
    {
        return $this->model->paginate($perPage);
    }

    public function getAll($query)
    {
        return $query->get();
    }

    public function getById($id, $query)
    {
        return $query->find($id);
    }

    public function update($id, $query, $request)
    {
        $data = $query->find($id);

        if ($data) {
            $data->update($request);
        }
        return $data;
    }

    public function delete($id, $query)
    {
        return $query->where('id', $id)->delete();
    }

    public function create($query, $request)
    {
        return $query->create($request);
    }

    public function history($query)
    {
        return $query->get();
    }

    public function getSort($query, $data)
    {
        return $query->orderBy($data['column'] ,$data['sort']);
    }

    private function checkDefault($request)
    {
        $data = [
            'limit' => $request['limit'] ?? $this->limit,
            'column' => $request['column'] ?? $this->column,
            'sort' => $request['sort'] ?? $this->sort,
        ];
        return $data;
    }

    public function mapping($request, $mapping)
    {
        $this->acceptedfields = $mapping;
        $data = $this->checkDefault($request);
        foreach ($this->acceptedfields->keys() as $key) {
            if ($key == $data['column']) {
                $this->column = $this->acceptedfields->get($key);
                break;
            }
        }
        $data['column'] = $this->column;
        return $data;
    }

    public function createCode($query, $codeFirstCharacter, $newInsertId)
    {
        for ($i = 0; $i < 4 - strlen((string)($newInsertId)); $i++) {
            $codeFirstCharacter = $codeFirstCharacter . "0";
        }
        $codeFirstCharacter = $codeFirstCharacter . $newInsertId;
        $code = [
            'asset_code' => $codeFirstCharacter
        ];
        return $this->update($newInsertId, $query, $code);
    }
}
