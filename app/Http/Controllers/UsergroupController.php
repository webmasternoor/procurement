<?php
namespace App\Http\Controllers;

use App\Usergroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsergroupController extends Controller
{
    public function getIndex()
    {
        return view('usergroup.index');
    }

    public function getList()
    {
        Session::put('usergroup_search', Input::has('ok') ? Input::get('search') : (Session::has('usergroup_search') ? Session::get('usergroup_search') : ''));
        Session::put('usergroup_field', Input::has('field') ? Input::get('field') : (Session::has('usergroup_field') ? Session::get('usergroup_field') : 'id'));
        Session::put('usergroup_sort', Input::has('sort') ? Input::get('sort') : (Session::has('usergroup_sort') ? Session::get('usergroup_sort') : 'asc'));
        $usergroups = Usergroup::where('id', 'like', '%' . Session::get('usergroup_search') . '%')
            ->orderBy(Session::get('usergroup_field'), Session::get('usergroup_sort'))->paginate(8);
        return view('usergroup.list', ['usergroups' => $usergroups]);
    }

    public function getUpdate($id)
    {
        return view('usergroup.update', ['usergroup' => Usergroup::find($id)]);
    }

    public function postUpdate($id)
    {
        $usergroup = Usergroup::find($id);
        if ($usergroup->name12 != Input::get('name12'))
            $rules += ['name12' => 'required|unique:usergroups'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $usergroup->user_id = Input::get('user_id');
        $usergroup->name12 = Input::get('name12');
        $usergroup->save();
        return ['url' => 'usergroup/list'];
    }

    public function getCreate()
    {
        return view('usergroup.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "name12" => "required|unique:usergroups"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $usergroup = new Usergroup();
        $usergroup->user_id = Input::get('user_id');
        $usergroup->name12 = Input::get('name12');
        $usergroup->save();
        return ['url' => 'usergroup/list'];
    }

    public function getDelete($id)
    {
        Usergroup::destroy($id);
        return Redirect('usergroup/list');
    }

}