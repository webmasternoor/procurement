<?php
namespace App\Http\Controllers;

use App\Statuspro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StatusproController extends Controller
{
    public function getIndex()
    {
        return view('statuspro.index');
    }

    public function getList()
    {
        Session::put('statuspro_search', Input::has('ok') ? Input::get('search') : (Session::has('statuspro_search') ? Session::get('statuspro_search') : ''));
        Session::put('statuspro_field', Input::has('field') ? Input::get('field') : (Session::has('statuspro_field') ? Session::get('statuspro_field') : 'id'));
        Session::put('statuspro_sort', Input::has('sort') ? Input::get('sort') : (Session::has('statuspro_sort') ? Session::get('statuspro_sort') : 'asc'));
        $statuspros = Statuspro::where('id', 'like', '%' . Session::get('statuspro_search') . '%')
            ->orderBy(Session::get('statuspro_field'), Session::get('statuspro_sort'))->paginate(8);
        return view('statuspro.list', ['statuspros' => $statuspros]);
    }

    public function getUpdate($id)
    {
        return view('statuspro.update', ['statuspro' => Statuspro::find($id)]);
    }

    public function postUpdate($id)
    {
        $statuspro = Statuspro::find($id);
        if ($statuspro->name12 != Input::get('name12'))
            $rules += ['name12' => 'required|unique:statuspros'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $statuspro->name12 = Input::get('name12');
        $statuspro->save();
        return ['url' => 'statuspro/list'];
    }

    public function getCreate()
    {
        return view('statuspro.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "name12" => "required|unique:statuspros"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $statuspro = new Statuspro();
        $statuspro->name12 = Input::get('name12');
        $statuspro->save();
        return ['url' => 'statuspro/list'];
    }

    public function getDelete($id)
    {
        Statuspro::destroy($id);
        return Redirect('statuspro/list');
    }

}