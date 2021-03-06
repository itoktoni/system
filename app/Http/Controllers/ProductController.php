<?php

namespace App\Http\Controllers;

use Auth;
use Curl;
use DataTables;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public $table;
    public $key;
    public $field;
    public $model;
    public $template;
    public $rules;
    public $datatable;
    public $searching;
    public $render;

    public function __construct()
    {
        $this->model    = new \App\Product();
        $this->table    = $this->model->getTable();
        $this->key      = $this->model->getKeyName();
        $this->field    = $this->model->getFillable();
        $this->template = $this->getTemplate();
        $this->render   = 'page.' . $this->template . '.';
    }

    public function index()
    {
        return redirect()->route($this->getModule() . 'list');
    }

    private function share($data = null, $key = null)
    {
        $view = [
            'status'   => Helper::shareStatus($this->model->status),
            'category'   => Helper::createOption('category-api', true)->pluck('name','id'),
            'tag'   => Helper::createOption('tag-api', true)->pluck('name','slug'),
        ];

        $merge = array_merge($view, $data);
        return $merge;
    }

    public function create()
    {      
        if (request()->isMethod('POST')) {
            $this->validate(request(), $this->model->rules);
            $request = request()->all();
            $cek     = $this->model->simpan($request);
            if ($cek) {
                return redirect()->back();
            }
        }

        return view($this->render . __function__)->with($this->share([
            'template' => $this->template,
        ]));
    }

    function list() {
        if (request()->isMethod('POST')) {
            $getData   = $this->model->baca();
            $datatable = Datatables::of($this->filter($getData));
            $datatable->editColumn('checkbox', function ($select) {
                return Helper::createCheckbox($select->{$this->key});
            });
            $datatable->addColumn('action', function ($select) {
                $action = [
                    'update' => ['primary', 'edit'],
                    'list'   => ['success', 'show'],
                ];

                if (!session()->exists('button')) {
                    session()->put('button', count($action));
                }
                $data = Helper::createAction([
                    'key'    => $select->{$this->key},
                    'route'  => $this->getModule(),
                    'action' => $action,
                ]);
                return $data;
            });
            $datatable->editColumn('price', function($select){
                return empty($select->price) ? 0 : number_format($select->price);
            });
            $datatable->editColumn('active', function($select){
                return Helper::createStatus([
                    'value' => $select->active,
                    'status' => $this->model->status,
                ]);
            });

            $datatable->rawColumns([
                'active', 'checkbox','action'
            ]);

            if (!empty(request()->get('search'))) {
                $datatable->filter(function ($query) {
                    $code         = request()->get('code');
                    $search       = request()->get('search');
                    $aggregate    = request()->get('aggregate');
                    $search_field = empty($code) ? $this->model->searching : $code;
                    $aggregation  = empty($aggregate) ? 'like' : $aggregate;
                    $input        = empty($aggregate) ? "%$search%" : "$search";
                    $query->where($search_field, $aggregation, $input);
                });
            }

            return $datatable->make(true);
        }
        if (request()->has('code')) {
            $id   = request()->get('code');
            $data = $this->model->baca($id);

            return view($this->render . 'show')->with([
                'fields'   => Helper::listData($this->model->datatable),
                'data'     => $this->validasi($data),
                'key'      => $this->key,
                'template' => $this->template,
            ]);
        }

        return view($this->render . __function__)->with([
            'fields'   => Helper::ListData($this->model->datatable),
            'template' => $this->template,
        ]);
    }

    public function update()
    {
        $id = request()->get('code');
        if (request()->isMethod('POST')) {
            $request = request()->all();
            $this->model->ubah($id, $request);
            return redirect()->route($this->getModule() . '_list');
        }

        $data    = $this->validasi($this->model->baca($id));
        $data->tags = json_decode($data->tags);
        return view($this->render . __function__)->with($this->share([
            'template' => $this->template,
            'model'    => $data,
            'key'      => $this->key,
        ]));

    }

    public function delete()
    {
        $input = request()->all();
        $this->model->hapus($input);

        return redirect()->back();
    }

}
