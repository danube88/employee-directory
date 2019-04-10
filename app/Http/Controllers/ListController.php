<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Response;
use DB;
use App\Worker;

class ListController extends Controller
{
    //
    public function dataList(Request $request)
    {
      // code...
      $param = json_decode($request->queryParams);
      if(!empty($param->sort)){
        $sortName = $param->sort[0]->name;
        $sortOrder = $param->sort[0]->order;
      } else {
        $sortName = 'table_number';
        $sortOrder = 'asc';
      }

      $workers = DB::table('workers as w')
                ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
                ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
                ->leftJoin('workers as ws', 'ws.id', '=', 's.head_id')
                ->select([
                  "w.id",
                  "w.photo",
                  "w.table_number",
                  DB::raw("DATE_FORMAT(w.birthday,'%m.%d.%Y') as birthday"),
                  DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                  DB::raw("DATE_FORMAT(w.reception_date,'%m.%d.%Y') as reception_date"),
                  "w.salary",
                  "p.name_position",
                  "s.head_id",
                  DB::raw("CONCAT(ws.surname,' ',ws.name,' ',ws.patronymic) as nameHead"),
                ]);
      if ($param->global_search != "") {
        $search = "%".$param->global_search."%";
        $workers->where("w.table_number","like",$search)
                ->orWhere("w.birthday","like",$search)
                ->orWhere(DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic)"),"like",$search)
                ->orWhere("w.salary","like",$search)
                ->orWhere("w.reception_date","like",$search)
                ->orWhere("p.name_position","like",$search)
                ->orWhere(DB::raw("CONCAT(ws.surname,' ',ws.name,' ',ws.patronymic)"),"like",$search);
      }
      $total = $workers->count();
      $workers->offset(($request->page - 1) * $param->per_page)
              ->limit($param->per_page)
              ->orderBy($sortName, $sortOrder);
      $workers = $workers->get();

      foreach ($workers as $worker) {
        if(($worker->photo != null) && (file_exists(public_path()."/img/photo/mini/".$worker->photo))){
          $worker->photo = "../img/photo/mini/".$worker->photo."?".rand();
        } else {
          $worker->photo = '../img/example_mini.jpg';
        }
      }

      return Response::json(array('data' => $workers->toArray(),'total' => $total));
    }
}
