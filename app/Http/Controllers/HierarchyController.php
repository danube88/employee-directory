<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use App\Worker;

class HierarchyController extends Controller
{
    //
    public function dataHierarchy()
    {
      // code...
      $workers = DB::table('workers as w')
                ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
                ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
                ->leftJoin('subordinations as sb', 'sb.head_id', '=', 'w.id')
                ->select([
                  "w.id",
                  DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                  "p.name_position",
                  "s.head_id",
                  DB::raw("COUNT(sb.subordinate_id) as count")
                ])
                ->where('s.head_id','=',null)
                ->groupBy(['w.id',
                          's.head_id',
                          'w.surname',
                          'w.name',
                          'w.patronymic',
                          'p.name_position'
                          ])
                ->get();
      foreach ($workers as $value) {
        $array[] = $value->id;
      }
      $workers_two = DB::table('workers as w')
                  ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
                  ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
                  ->leftJoin('subordinations as sb', 'sb.head_id', '=', 'w.id')
                  ->whereIn('s.head_id',$array)
                  ->select([
                        "w.id",
                        DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                        "p.name_position",
                        "s.head_id",
                        DB::raw("COUNT(sb.subordinate_id) as count")
                        ])
                  ->groupBy(['w.id',
                            's.head_id',
                            'w.surname',
                            'w.name',
                            'w.patronymic',
                            'p.name_position'
                            ])
                  ->get();
      $workers = array_merge($workers->toArray(), $workers_two->toArray());

      $workers = $this->treeData($workers,0);

      return Response::json($workers);
    }

    public function dataLoading($id)
    {
      $workers = DB::table('workers as w')
              ->leftJoin('positions as p', 'p.id', '=', 'w.position_id')
              ->leftJoin('subordinations as s', 's.subordinate_id', '=', 'w.id')
              ->leftJoin('subordinations as sb', 'sb.head_id', '=', 'w.id')
              ->where('s.head_id','=',$id)
              ->select([
                "w.id",
                DB::raw("CONCAT(w.surname,' ',w.name,' ',w.patronymic) as nameWorker"),
                //"w.reception_date",
                //"w.salary",
                //"p.id as id_p",
                //"p.level",
                "p.name_position",
                "s.head_id",
                DB::raw("COUNT(sb.subordinate_id) as count")])
              ->groupBy(['w.id',
                        's.head_id',
                        'w.surname',
                        'w.name',
                        'w.patronymic',
                        /*'w.reception_date',
                        'w.salary',
                        'p.id',
                        'p.level',*/
                        'p.name_position'])
              ->get();
      foreach ($workers as $worker) {
        if ($worker->count > 0) {
          $worker->children = [];
        }
      }
      return Response::json($workers);
    }

    protected function treeData($rows,$root=0)
    {
      $forest = array();
      foreach ($rows as $key => $row) {
        if(!isset($row->head_id) && $row->count > 0){
          unset($rows[$key]);
          $row->children = [$this->treeData($rows,$row->id)];
          $forest[$row->id] = $row;
        } else {
          if($row->head_id == $root && $row->count > 0){
            unset($rows[$key]);
            $row->children = [$this->treeData($rows,$row->id)];
            $forest[$row->id] = $row;
          } elseif($row->head_id == $root && $row->count == 0) {
            unset($rows[$key]);
            $forest[$row->id] = $row;
          }
        }
      }
      return $forest;
    }
}
