<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;
use DB;
use App\Position;
use App\Worker;
use App\Subordination;
use Illuminate\Validation\Rule;
use Image;

class EmployeeController extends Controller
{
    //
    public function index(Request $request)
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
        if(($worker->photo != null)&&(file_exists(public_path()."/img/photo/mini/".$worker->photo))){
          $worker->photo = "../img/photo/mini/".$worker->photo."?".rand();
        } else {
          $worker->photo = '../img/example_mini.jpg';
        }
      }

      return Response::json(array('data' => $workers->toArray(),'total' => $total));
    }

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
          'position_id' => 'required|numeric',
          'salary' => 'required|numeric',
          'reception_date' => 'required|date|before_or_equal:'.date("Y-m-d").'',
          'photo' => 'file|image|max:1024|mimes:jpeg,jpg,bmp,png',
          'photoDelete' => 'numeric'
      ];
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) {
        return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
      } else {
        if($input['table_number'] == '000000'){
          return Response::json(array('errors' => array('table_number' => ['Table number must not be 000000!'])));
        }
        //$position = Position::where('id', '=', $input['position'])->first()->id;
        $worker = Worker::create([
          'surname' => $input['surname'],
          'name' => $input['name'],
          'patronymic' => $input['patronymic'],
          'table_number' => $input['table_number'],
          'birthday' => $input['birthday'],
          'position_id' => $input['position_id'],
          'salary' => $input['salary'],
          'reception_date' => $input['reception_date']
        ]);

        if ($request->hasFile('photo') && $input['photoDelete'] == 0) {
          $file = $request->file('photo');
          $input['photo'] = $worker->id.'.'.$file->getClientOriginalExtension();

          Image::make($file)->resize(200, 300)->save(public_path().'/img/photo/'.$input['photo'],100);

          $img = Image::make(public_path().'/img/photo/'.$input['photo'])->resize(70, 105);
          $img->save(public_path().'/img/photo/mini/'.$input['photo'],100);

          Worker::find($worker->id)->update([
            'photo' => $input['photo']
          ]);
        } else {
          Worker::find($worker->id)->update([
            'photo' => NULL
          ]);
        }

        if ($input['head'] != 0) {
          Subordination::create([
            'head_id' => $input['head'],
            'subordinate_id' => $worker->id
          ]);
        }
        return Response::json(['data'=>'Карточка сотрудника №'.$input['table_number'].' создана в БД']);
      }
    }

    public function show($id)
    {
      $worker = Worker::with('position')->findOrFail($id);
      $head = Subordination::where('subordinate_id','=',$id)->select('head_id')->first();
      $heads = DB::table('workers')
                ->leftJoin('positions', 'positions.id', '=', 'workers.position_id')
                ->where('positions.level','<',$worker->position->level)
                ->where('workers.id','!=',$id)
                ->select([
                    'workers.id',
                    'workers.table_number',
                    DB::raw("CONCAT(workers.surname,' ',workers.name,' ',workers.patronymic) as nameWorker"),
                    'positions.name_position'
                  ])
                ->orderBy('workers.table_number', 'asc')
                ->get()->toArray();
      if(($worker->photo != null)&&(file_exists(public_path()."/img/photo/".$worker->photo))){
        $worker->photo = "../../../img/photo/".$worker->photo."?".rand();
      } else {
        $worker->photo = "../../../img/example.jpg";
      };
      return Response::json(array('data' => $worker,'head' => $head,'heads' => $heads));
    }

    public function update(Request $request, $id)
    {
      $input = $request->except('_token');
      //return Response::json(array('data' => $input));
      $rules = [
          'head' => 'numeric',
          'table_number' => ['required','min:6','max:6',Rule::unique('workers')->ignore($id),],
          'surname' => 'required|min:2|max:128',
          'name' => 'required|min:2|max:128',
          'patronymic' => 'required|min:2|max:128',
          'birthday' => 'required|date|before_or_equal:'.date("Y-m-d").'',
          'position_id' => 'required|numeric',
          'salary' => 'required|numeric',
          'reception_date' => 'required|date|before_or_equal:'.date("Y-m-d").'',
          'photo' => 'file|image|max:1024|mimes:jpeg,jpg,bmp,png',
          'photoDelete' => 'numeric'
      ];
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) {
        return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
      } else {
        //$position = Position::where('id', '=', $input['position'])->first()->id;
        $worker = Worker::where('id','=',$id)->update([
          'surname' => $input['surname'],
          'name' => $input['name'],
          'patronymic' => $input['patronymic'],
          'table_number' => $input['table_number'],
          'birthday' => $input['birthday'],
          'position_id' => $input['position_id'],
          'salary' => $input['salary'],
          'reception_date' => $input['reception_date']
        ]);

        if ($request->hasFile('photo')) {
          $file = $request->file('photo');
          $input['photo'] = $id.'.'.$file->getClientOriginalExtension();

          Image::make($file)->resize(200, 300)->save(public_path().'/img/photo/'.$input['photo'],100);

          $img = Image::make(public_path().'/img/photo/'.$input['photo'])->resize(70, 105);
          $img->save(public_path().'/img/photo/mini/'.$input['photo'],100);

          Worker::where('id','=',$id)->update([
            'photo' => $input['photo']
          ]);
        }

        if ($input['head'] != 0) {
          $subordination = Subordination::updateOrCreate(
            ['subordinate_id' => $id],
            ['head_id' => $input['head']]
          );
        } else {
          Subordination::where('subordinate_id','=',$id)->delete();
        }

        $worker = Worker::where('id','=',$id)->first();
        if ($input['photoDelete'] != 0 && $worker->photo != null) {
          if(file_exists(public_path().'/img/photo/mini/'.$worker->photo)){
            unlink(public_path().'/img/photo/mini/'.$worker->photo);
          }
          if(file_exists(public_path().'/img/photo/'.$worker->photo)){
            unlink(public_path().'/img/photo/'.$worker->photo);
          }
          Worker::where('id','=',$id)->update([
            'photo' => NULL
          ]);
        }

        return Response::json(['data'=>'Карточка сотрудника №'.$input['table_number'].' изменена']);
      }
    }

    public function destroy($id)
    {
        //
        $worker = Worker::where('id','=',$id)->first();
        $head = Subordination::where('head_id','=',$worker->id)->count();
        if($head > 0){
          return Response::json(array('errors' => ['data'=>'Данный сотрудник имеет подчиненных, и не может быть удален']));
        } else {
          Subordination::where('subordinate_id','=',$worker->id)->delete();
          Worker::find($worker->id)->delete();
          return Response::json(['data'=>'Карточка '.$worker->table_number.' сотрудника удалена']);
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
          'level' => 'required|numeric',
          'table_number' => 'numeric'
      ];
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) {
        return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
      } else {
        $guide = DB::table('workers')
            ->leftJoin('positions', 'positions.id', '=', 'workers.position_id')
            ->where('positions.level','<',$input['level']);
      if($input['table_number'] != ''){
        $guide->where('workers.table_number','!=',$input['table_number']);
      }
      $guide = $guide->select([
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
