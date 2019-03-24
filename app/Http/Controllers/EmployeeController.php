<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;
use DB;
use App\Position;
use App\Worker;
use App\Subordination;

class EmployeeController extends Controller
{
    //
    public function store(Request $request)
    {
      $input = $request->except('_token');

      $rules = [
          'head' => 'numeric',
          'table_number' => 'required|min:6|max:6|unique:workers',
          'surname' => 'required|min:2|max:128',
          'name' => 'required|min:2|max:128',
          'patronymic' => 'required|min:2|max:128',
          'birthday' => 'required|date|before_or_equal:'.date("Y-m-d").'',
          'position' => 'required|numeric',
          'salary' => 'required|numeric',
          'reception_date' => 'required|date|before_or_equal:'.date("Y-m-d").''
      ];
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) {
        return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
      } else {
        //$position = Position::where('id', '=', $input['position'])->first()->id;
        $worker = Worker::create([
          'surname' => $input['surname'],
          'name' => $input['name'],
          'patronymic' => $input['patronymic'],
          'table_number' => $input['table_number'],
          'birthday' => $input['birthday'],
          'position_id' => $input['position'],
          'salary' => $input['salary'],
          'reception_date' => $input['reception_date']
        ]);

        if ($input['head'] != 0) {
          Subordination::create([
            'head_id' => $input['head'],
            'subordinate_id' => $worker->id
          ]);
        }
        return Response::json(['data'=>'Карточка сотрудника №'.$input['table_number'].' создана в БД']);
      }
    }

    public function dataPositions()
    {
      $positions = Position::select('id','name_position','default_salary','level')->get();

      return Response::json($positions);
    }

    public function dataHeads(Request $request)
    {
      $input = $request->except('_token');

      $rules =
      [
          'level' => 'required|numeric'
      ];
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) {
        return Response::json(array('errors' => $validator->getMessageBag()->toArray(),'data' => $input));
      } else {
        $guide = DB::table('workers')
            ->leftJoin('positions', 'positions.id', '=', 'workers.position_id')
            ->where('positions.level','<',$input['level'])
            ->select([
              'workers.id',
              'workers.table_number',
              DB::raw("CONCAT(workers.surname,' ',workers.name,' ',workers.patronymic) as nameWorker"),
              'positions.name_position'
            ])
            ->orderBy('workers.table_number', 'asc')
            ->get();

        return Response::json($guide);
      }
    }
}
