<?php
namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getIndex()
    {
        return view('product.index');
    }

    public function getList()
    {
        Session::put('product_search', Input::has('ok') ? Input::get('search') : (Session::has('product_search') ? Session::get('product_search') : ''));
        Session::put('product_field', Input::has('field') ? Input::get('field') : (Session::has('product_field') ? Session::get('product_field') : 'id'));
        Session::put('product_sort', Input::has('sort') ? Input::get('sort') : (Session::has('product_sort') ? Session::get('product_sort') : 'asc'));
        $products = Product::where('id', 'like', '%' . Session::get('product_search') . '%')
            ->orderBy(Session::get('product_field'), Session::get('product_sort'))->paginate(8);
        return view('product.list', ['products' => $products]);
    }

    public function getUpdate($id)
    {
        return view('product.update', ['product' => Product::find($id)]);
    }

    public function postUpdate($id)
    {
        $product = Product::find($id);
        if ($product->name12 != Input::get('name12'))
            $rules += ['name12' => 'required|unique:products'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $product->name12 = Input::get('name12');
        $product->save();
        return ['url' => 'product/list'];
    }

    public function getCreate()
    {
        return view('product.create');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), [
            "name12" => "required|unique:products"
        ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $product = new Product();
        $product->name12 = Input::get('name12');
        $product->save();
        return ['url' => 'product/list'];
    }

    public function getDelete($id)
    {
        Product::destroy($id);
        return Redirect('product/list');
    }

}