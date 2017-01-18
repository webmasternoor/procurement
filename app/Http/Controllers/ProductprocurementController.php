<?php
namespace App\Http\Controllers;

use App\Productprocurement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductprocurementController extends Controller
{
    public function getIndex()
    {
        return view('productprocurement.index');
    }

    public function getList()
    {
        Session::put('productprocurement_search', Input::has('ok') ? Input::get('search') : (Session::has('productprocurement_search') ? Session::get('productprocurement_search') : ''));
        Session::put('productprocurement_field', Input::has('field') ? Input::get('field') : (Session::has('productprocurement_field') ? Session::get('productprocurement_field') : 'id'));
        Session::put('productprocurement_sort', Input::has('sort') ? Input::get('sort') : (Session::has('productprocurement_sort') ? Session::get('productprocurement_sort') : 'asc'));
        $productprocurements = Productprocurement::where('id', 'like', '%' . Session::get('productprocurement_search') . '%')
            ->orderBy(Session::get('productprocurement_field'), Session::get('productprocurement_sort'))->paginate(8);
        return view('productprocurement.list', ['productprocurements' => $productprocurements]);
    }

    public function getUpdate($id)
    {
        return view('productprocurement.update', ['productprocurement' => Productprocurement::find($id)]);
    }

    public function postUpdate($id)
    {
        $productprocurement = Productprocurement::find($id);
        if ($productprocurement->name12 != Input::get('name12'))
            $rules += ['name12' => 'required|unique:productprocurements'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $productprocurement->name12 = Input::get('name12');
        $productprocurement->save();
        return ['url' => 'productprocurement/list'];
    }

    public function getCreate()
    {
        return view('productprocurement.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "name12" => "required|unique:productprocurements"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $productprocurement = new Productprocurement();
        $productprocurement->name12 = Input::get('name12');
        $productprocurement->save();
        return ['url' => 'productprocurement/list'];
    }

    public function getDelete($id)
    {
        Productprocurement::destroy($id);
        return Redirect('productprocurement/list');
    }

}