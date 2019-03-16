<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use App\Worker;

class ListController extends Controller
{
    //
    public function dataList()
    {
      $workers = DB::table('workers as w')
                ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
                ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
                ->leftJoin('workers as wb', 'wb.id', '=', 's.head_id')
                ->select([
                  "w.id",
                  "w.table_number",
                  DB::raw("DATE_FORMAT(w.birthday,'%m.%d.%Y') as birthday"),
                  DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                  DB::raw("DATE_FORMAT(w.reception_date,'%m.%d.%Y') as reception_date"),
                  "w.salary",
                  "p.id as id_p",
                  "p.level",
                  "p.name_position",
                  "s.head_id",
                  DB::raw("CONCAT(IFNULL(wb.surname,' '),' ',IFNULL(wb.name,' '),' ',IFNULL(wb.patronymic,' ')) as nameHead")
                ])
                ->get()
                ->toArray();

      return Response::json($workers);
    }
}
