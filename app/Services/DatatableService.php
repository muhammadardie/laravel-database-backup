<?php

namespace App\Services;

use Yajra\Datatables\Datatables;
use DB;

class DatatableService
{
    /**
    * Create default Yajra Datatable
    * @param $model model object
    * @param $request array
    * @param $route route definition for action button routing
    * @return Datatable of collection
    */
    public static function create($model, $request, $route=NULL)
    {
        if($request->ajax()){
            $tableName   = $model->getTable();
            $tableKey    = $model->getKeyName();
            $actionId    = method_exists($model, 'datatableActionId') ? 
                            $model->datatableActionId() : '';
            $rawColumns  = method_exists($model, 'rawColumns') ? $model->rawColumns() : [];          
            $sql_no_urut = DatatableService::getRowNum($tableName.'.'. ($actionId != '' ? $actionId : $tableKey), $request);
            $collection  = $model
                                ->select(array_merge([DB::raw($sql_no_urut)], $model->datatableColumns()))
                                ->datatableCond();

            $datatable =  Datatables::of($collection)
                            ->addColumn('action', function ($collection) use($model, $route,$tableKey, $actionId, $tableName){
                                $btn_action = '';
                                $route = $route === null ? $tableName : $route;

                                if (in_array("show", $model->datatableButtons())) {
                                    $btn_action .= '<a data-href="'. route($route.'.show', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn cur-p btn-outline-primary btn-show-datatable" title="Detail Data">
                                                    <span class="fa fa-search"></span></a>&nbsp;&nbsp;';
                                }

                                if (in_array("edit", $model->datatableButtons())) {
                                    $btn_action .= '<a data-href="'. route($route.'.show', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn cur-p btn-outline-primary btn-edit-datatable" title="Ubah Data">
                                                    <span class="fa fa-edit"></span></a>&nbsp;&nbsp;';
                                }

                                if (in_array("destroy", $model->datatableButtons())) {
                                    $btn_action .= '<a data-href="'. route($route.'.show', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn cur-p btn-outline-primary btn-delete-datatable" title="Hapus Data">
                                                    <span class="fa fa-trash"></span></a>';
                                }

                                return $btn_action;
                                
                            })
                            ->addcolumn('created_at', function($collection){
                                return \Helper::tglIndo($collection->created_at); 
                            })
                            ->addcolumn('updated_at', function($collection){
                                return \Helper::tglIndo($collection->updated_at); 
                            });

            if(method_exists($model, 'datatableExcTimestamp')){
                if (in_array("created_at", $model->datatableExcTimestamp())) {
                    $datatable->removeColumn('created_at');
                }
                
                if (in_array("updated_at", $model->datatableExcTimestamp())) {
                    $datatable->removeColumn('updated_at');
                }    
            }

            if(method_exists($model, 'makeColumns')){
                $model->makeColumns($datatable);        
            }

            return $datatable->rawColumns(array_merge(['action'], $rawColumns)) // to html
                             ->make(true);
        }
    }

    /**
    * Get row number Yajra Datatable
    * @author moko
    * @param $primary_key string
    * @param $request array
    * @return string sql
    */
    public static function getRowNum($primary_key, $request){
        // get column index frontend
        $order_column = $request->get('order')[0]['column'];

        // nomor urut
        $sql_no_urut = "row_number() OVER (ORDER BY $primary_key DESC) AS rownum"; // row_number() = postgresql function
        if($order_column != 0){

            // ----------------------------
            // Yajra Datatable Index
            $field_name = $request->get('columns')[$order_column]['data']; // field_name

            if(isset($request->get('columns')[$order_column]['name'])){
                $field_name =  $request->get('columns')[$order_column]['name']; // table.field_name
            }

            $ordering   = $request->get('order')[0]['dir']; // asc|desc
            // ----------------------------
            
            $sql_no_urut= "row_number() OVER (ORDER BY $field_name $ordering) AS rownum";
        }

        return $sql_no_urut;
    }
}