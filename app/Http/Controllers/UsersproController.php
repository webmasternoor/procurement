<?php
namespace App\Http\Controllers;

use App\Userspro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersproController extends Controller
{
    public function getIndex()
    {
        return view('userspro.index');
    }

    public function getList()
    {
        Session::put('userspro_search', Input::has('ok') ? Input::get('search') : (Session::has('userspro_search') ? Session::get('userspro_search') : ''));
        Session::put('userspro_field', Input::has('field') ? Input::get('field') : (Session::has('userspro_field') ? Session::get('userspro_field') : 'id'));
        Session::put('userspro_sort', Input::has('sort') ? Input::get('sort') : (Session::has('userspro_sort') ? Session::get('userspro_sort') : 'asc'));
        $userspros = Userspro::where('id', 'like', '%' . Session::get('userspro_search') . '%')
            ->orderBy(Session::get('userspro_field'), Session::get('userspro_sort'))->paginate(8);
        return view('userspro.list', ['userspros' => $userspros]);
    }

    public function getUpdate($id)
    {
        return view('userspro.update', ['userspro' => Userspro::find($id)]);
    }

    public function postUpdate($id)
    {
        $userspro = Userspro::find($id);
        if ($userspro->name12 != Input::get('name12'))
            $rules += ['name12' => 'required|unique:userspros'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $userspro->id = Input::get('id');
        $userspro->project_id = Input::get('project_id');
        $userspro->user_type = Input::get('user_type');
        $userspro->name12= Input::get('name12');
        $userspro->save();
        return ['url' => 'userspro/list'];
    }

    public function getCreate()
    {
        return view('userspro.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "name12" => "required|unique:userspros"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $userspro = new Userspro();
        $userspro->id = Input::get('id');
        $userspro->project_id = Input::get('project_id');
        $userspro->user_type = Input::get('user_type');
        $userspro->name12= Input::get('name12');
        $userspro->save();
        return ['url' => 'userspro/list'];
    }

    public function getDelete($id)
    {
        Userspro::destroy($id);
        return Redirect('userspro/list');
    }

}