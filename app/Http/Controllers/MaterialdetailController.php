<?php
namespace App\Http\Controllers;

use App\Materialdetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MaterialdetailController extends Controller
{
    public function getIndex()
    {
        return view('materialdetail.index');
    }

    public function getList()
    {
        Session::put('materialdetail_search', Input::has('ok') ? Input::get('search') : (Session::has('materialdetail_search') ? Session::get('materialdetail_search') : ''));
        Session::put('materialdetail_field', Input::has('field') ? Input::get('field') : (Session::has('materialdetail_field') ? Session::get('materialdetail_field') : 'id'));
        Session::put('materialdetail_sort', Input::has('sort') ? Input::get('sort') : (Session::has('materialdetail_sort') ? Session::get('materialdetail_sort') : 'asc'));
        $materialdetails = Materialdetail::where('id', 'like', '%' . Session::get('materialdetail_search') . '%')
            ->orderBy(Session::get('materialdetail_field'), Session::get('materialdetail_sort'))->paginate(8);
        return view('materialdetail.list', ['materialdetails' => $materialdetails]);
    }

    public function getUpdate($id)
    {
        return view('materialdetail.update', ['materialdetail' => Materialdetail::find($id)]);
    }

    public function postUpdate($id)
    {
        $materialdetail = Materialdetail::find($id);
        if ($materialdetail->materialdetail != Input::get('materialdetail'))
            $rules += ['materialdetail' => 'required|unique:materialdetails'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $materialdetail->materialdetail = Input::get('materialdetail');
        $materialdetail->save();
        return ['url' => 'materialdetail/list'];
    }

    public function getCreate()
    {
        return view('materialdetail.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "materialdetail" => "required|unique:materialdetails"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $materialdetail = new Materialdetail();
        $materialdetail->materialdetail = Input::get('materialdetail');
        $materialdetail->materialdetail1 = Input::get('materialdetail1');
        $materialdetail->save();
        return ['url' => 'materialdetail/list'];
    }

    public function getDelete($id)
    {
        Materialdetail::destroy($id);
        return Redirect('materialdetail/list');
    }

}