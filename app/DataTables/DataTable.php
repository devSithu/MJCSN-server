<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\DataTables;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Http\Exceptions\HttpResponseException;
use Yajra\DataTables\Html\Column;

/**
 * Description of DataTable
 *
 * @author truong.nguyen
 */
class DataTable implements ValidatesWhenResolved
{
    protected $dtId;
    protected $dtRenderData = false;
    protected $jsAutoRender = true;
    protected $ajaxReady = false;
    ///////////////////////////////////    Datatable options     //////////////////////////////////
    protected $scrollX = true;
    protected $scrollY = false;
    protected $stateSave = false;
    protected $pageLength = 10;
    protected $lengthMenu = [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]];
    protected $order = [];
    protected $processing = true;
    protected $deferRender = true;
    protected $serverSide = false;
    protected $ajax = null;
    protected $searchDelay = 500;
    /////////////////////////////////////////////////////////////////////////////////
    protected $END_LINE = '';

    /**
     * Name of the dataTable variable.
     *
     * @var string
     */
    protected $dataTableVariable = 'dataTable';

    /**
     * Html builder.
     *
     * @var \Yajra\DataTables\Html\Builder
     */
    protected $htmlBuilder;

    /**
     * Custom attributes set on the class.
     *
     * @var array
     */
    protected $attributes = [];
    protected $request;
    protected $view;

//    drawCallback: function () {
    //        $("#table_count").html("検索結果(" + (table ? table.page.info().recordsTotal : this.api().rows().count()) + "件)");
    //        update_check_all_button();
    //    },
    //    rowCallback: function (row, data, index) {
    //        $(row).addClass('qb-visitor-line');
    //    },
    //    oColReorder: {
    //        iFixedColumns: 2
    //    },

    public function __construct()
    {
        $this->END_LINE = config('app.debug') ? PHP_EOL : '';
        foreach ($this->columns() as $key => $value) {
            if (is_array($value)) {
                $column = new Column($value);
            } else {
                $column = new Column([
                    'title' => is_string($key) ? $key : $this->builder()->getQualifiedTitle($value),
                    'data' => $value,
                ]);
            }
            $this->builder()->add($column);
        }
    }

    protected function id()
    {
        if (!$this->dtId) {
            $this->dtId = "_dtId" . rand(0, 1000);
        }
        return $this->dtId;
    }

    public function dom()
    {
        return "<"
            . "fl"
            . "<t>"
            . "ip>";
    }

    public function render($data = [])
    {
        return $this->renderHtml() . $this->END_LINE . $this->renderJs($data);
    }

    public function renderHtml()
    {
        return $this->builder()->table(['id' => $this->id()]);
    }

    public function renderJs($data)
    {
        $dt_options = [
            'dom' => $this->dom(),
            'deferRender' => $this->deferRender,
            'scrollX' => $this->scrollX,
            'scrollY' => $this->scrollY,
            'pageLength' => $this->pageLength,
            'lengthMenu' => $this->lengthMenu,
            'stateSave' => $this->stateSave,
            'order' => $this->order,
            'processing' => $this->processing,
            'serverSide' => $this->serverSide,
            'data' => $data,
        ];
        if (!in_array($this->ajax, [null, false], true)) {
            $dt_options['serverSide'] = true;
            $dt_options['searchDelay'] = $this->searchDelay;
            $dt_options['ajax'] = $this->ajax;
        }
        $js_options = [
            'autoRender' => $this->jsAutoRender,
        ];
        if ($this->dtRenderData) {

            foreach ($data as $key => $item) {

            }
        } else {
            $cols = $this->builder()->getColumns()->map(function (Column $column) {
                $column = $column->toArray();
                unset($column['attributes']);

                return $column;
            });
            $dt_options['columns'] = $cols;
        }
        $js = csp_scope([$this->id() => ['options' => $dt_options, 'js' => $js_options]], '_dt');
        $js .= csp_script('/js/datatable-trigger.js') . $this->END_LINE;

        return $js;
    }

    protected function getDataMap()
    {

    }

    public function query()
    {
        return $this->findModel()->newQuery();
    }

    public function columns()
    {
        return $this->findModel()->getFillable();
    }

    private function findModel()
    {
        $class = "App\\Models\\" . class_basename($this);
        return new $class;
    }

    /**
     * Get DataTables Request instance.
     *
     * @return \Yajra\DataTables\Utilities\Request
     */
    public function request()
    {
        return $this->request ?: $this->request = resolve('datatables.request');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseAjax()
    {
        /** @var \Yajra\DataTables\DataTableAbstract $dataTable */
        $dataTable = $this->dataTable($this->query());

        return $dataTable->toJson();
    }

    /**
     * Get DataTables Html Builder instance.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function builder()
    {
        return $this->htmlBuilder ?: $this->htmlBuilder = (app()->bound('datatables.html') ? resolve('datatables.html') : app()->make(\Yajra\DataTables\Html\Builder::class));
    }

    /**
     * Set a custom class attribute.
     *
     * @param mixed      $key
     * @param mixed|null $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->attributes = array_merge($this->attributes, $key);
        } else {
            $this->attributes[$key] = $value;
        }
        if ($this->view) {
            $this->view->with($key, $value);
        }

        return $this;
    }

    public function view($view, $data = [], $mergeData = [])
    {
        $this->view = view($view, $data, $mergeData)->with($this->dataTableVariable, $this)->with($this->attributes);
        return $this;
    }

    public function __toString()
    {
        if ($this->ajax === null && $this->ajaxReady) {
            $this->ajax = "";
        }
        if ($this->view) {
            return app()->call([$this->view, '__toString']);
        }
        return $this->render();
    }

    public function toResponse()
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return $this->responseAjax();
        }
        if ($this->ajax === null) {
            $this->ajax = "";
        }
        return $this;
    }

    public function validate()
    {
        if ($this->request()->ajax() && $this->request()->wantsJson() && $this->ajaxReady) {
            throw new HttpResponseException($this->responseAjax());
        }
    }

    protected function searchKeyWords()
    {
        $str = $this->request()->keyword();
//        $str = strtolower(mb_convert_kana($str, 'askh', 'UTF-8'));
        return preg_split('/[\s]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    }

}
