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

      $total = Worker::all()->count();
      $workers = DB::table('workers as w')
                ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
                ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
                ->leftJoin('workers as ws', 'ws.id', '=', 's.head_id')
                ->select([
                  "w.id",
                  "w.table_number",
                  DB::raw("DATE_FORMAT(w.birthday,'%m.%d.%Y') as birthday"),
                  DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                  DB::raw("DATE_FORMAT(w.reception_date,'%m.%d.%Y') as reception_date"),
                  "w.salary",
                  "p.name_position",
                  "s.head_id"
                ])
                ->offset(($request->page - 1) * $param->per_page)
                ->limit($param->per_page)
                ->orderBy($sortName, $sortOrder)
                ->get()
                ->toArray();

      return Response::json(array('data' => $workers ,'total' => $total));
    }
}
