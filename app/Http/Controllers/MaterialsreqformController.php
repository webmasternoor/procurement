<?php
namespace App\Http\Controllers;

use App\Materialsreqform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MaterialsreqformController extends Controller
{
    public function getIndex()
    {
        return view('materialsreqform.index');
    }

    public function getList()
    {
        Session::put('materialsreqform_search', Input::has('ok') ? Input::get('search') : (Session::has('materialsreqform_search') ? Session::get('materialsreqform_search') : ''));
        Session::put('materialsreqform_field', Input::has('field') ? Input::get('field') : (Session::has('materialsreqform_field') ? Session::get('materialsreqform_field') : 'id'));
        Session::put('materialsreqform_sort', Input::has('sort') ? Input::get('sort') : (Session::has('materialsreqform_sort') ? Session::get('materialsreqform_sort') : 'asc'));
        $materialsreqforms = Materialsreqform::where('id', 'like', '%' . Session::get('materialsreqform_search') . '%')
            ->orderBy(Session::get('materialsreqform_field'), Session::get('materialsreqform_sort'))->paginate(8);
        return view('materialsreqform.list', ['materialsreqforms' => $materialsreqforms]);
    }

    public function getUpdate($id)
    {
        return view('materialsreqform.update', ['materialsreqform' => Materialsreqform::find($id)]);
    }

    public function postUpdate($id)
    {
        $materialsreqform = Materialsreqform::find($id);
        if ($materialsreqform->materialsreqform != Input::get('materialsreqform'))
            $rules += ['materialsreqform' => 'required|unique:materialsreqforms'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $materialsreqform->materialsreqform = Input::get('materialsreqform');
        $materialsreqform->save();
        return ['url' => 'materialsreqform/list'];
    }

    public function getCreate()
    {
        return view('materialsreqform.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "materialsreqform" => "required|unique:materialsreqforms"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $materialsreqform = new Materialsreqform();
        $materialsreqform->materialsreqform = Input::get('materialsreqform');
        $materialsreqform->save();
        return ['url' => 'materialsreqform/list'];
    }

    public function getDelete($id)
    {
        Materialsreqform::destroy($id);
        return Redirect('materialsreqform/list');
    }

}