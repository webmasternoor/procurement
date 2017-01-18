<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Post;
use App\User;
use Collective\Html\FormBuilder;
use Illuminate\Http\Request;
use DB;
use Yajra\Datatables\Facades\Datatables;

class BlogController extends Controller
{
    public function __construct()
    {
        view()->share('controller', 'BlogController.php');
        view()->share('title', $this->getTitle('blog'));
        view()->share('description', $this->getDescription('blog'));
    }

    public function getIndex()
    {
        return view('datatables.blog.index');
    }

    public function getBasic()
    {
        return view('datatables.blog.basic');
    }

    public function getBasicData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->editColumn('name', '{{ $name."-name" }}')
            ->make();
    }

    public function getMaster()
    {
        return view('datatables.blog.master');
    }

    public function getMasterData()
    {
        $users = User::select();

        return Datatables::of($users)
            ->addColumn('details_url', function ($user) {
                return url('blog/details-data/' . $user->id);
            })
            ->make(true);
    }

    public function getDetailsData($id)
    {
        $posts = User::find($id)->posts();

        return Datatables::of($posts)->make(true);
    }

    public function getBasicObject()
    {
        return view('datatables.blog.basic-object');
    }

    public function getBasicObjectData()
    {
        $users = User::query();

        return Datatables::of($users)->make(true);
    }

    public function getManualCount(Request $request)
    {
        if ($request->ajax()) {
            $users = User::query();

            return Datatables::of($users)
                ->setTotalRecords(100)
                ->make(true);
        }

        return view('datatables.blog.manual-count');
    }

    public function getBasicColumns()
    {
        return view('datatables.blog.basic-columns');
    }

    public function getBasicColumnsData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)->make();
    }

    public function getIoc()
    {
        return view('datatables.blog.ioc');
    }

    public function getIocData()
    {
        $users      = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);
        $datatables = app('datatables');

        return $datatables->blog($users)->make(true);
    }

    public function getCount()
    {
        return view('datatables.blog.count');
    }

    public function getCountData()
    {
        $users = User::select([
                'users.id',
                'users.name',
                'users.email',
                \DB::raw('count(posts.user_id) as count'),
                'users.created_at',
                'users.updated_at'
        ])->leftJoin('posts', 'posts.user_id', '=', 'users.id')
        ->groupBy('users.id');

        return Datatables::of($users)->make(true);
    }

    public function getAdvanceFilter()
    {
        return view('datatables.blog.advance-filter');
    }

    public function getAdvanceFilterData()
    {
        $users = User::select([
                DB::raw("CONCAT(users.id,'-',users.id) as id"),
                'users.name',
                'users.email',
                DB::raw('count(posts.user_id) AS count'),
                'users.created_at',
                'users.updated_at'
        ])->leftJoin('posts', 'posts.user_id', '=', 'users.id')
        ->groupBy('users.id');

        $datatables =  app('datatables')->of($users)
            ->filterColumn('users.id', 'whereRaw', "CONCAT(users.id,'-',users.id) like ? ", ["$1"]);

        // having count search
        if ($datatables->request->get('post') <> '') {
            $datatables->having('count', $datatables->request->get('operator'), $datatables->request->get('post'));
        }

        // additional users.name search
        if ($name = $datatables->request->get('name')) {
            $datatables->where('users.name', 'like', "$name%");
        }

        return $datatables->make(true);
    }

    public function getAddEditRemoveColumn()
    {
        return view('datatables.blog.add-edit-remove-column');
    }

    public function getAddEditRemoveColumnData()
    {
        $users = User::select(['id', 'name', 'email', 'password', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->addColumn('action', function ($user) {
                return '<a href="#edit-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->removeColumn('password')
            ->make(true);
    }

    public function getDtRow()
    {
        return view('datatables.blog.dt-row');
    }

    public function getDtRowData()
    {
        $users = User::select(['id', 'name', 'email', 'password', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->addColumn('action', function ($user) {
                return '<a href="#edit-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->editColumn('id', '{{$id}}')
            ->removeColumn('password')
            ->setRowId('id')
            ->setRowClass(function ($user) {
                return $user->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })
            ->setRowData([
                'id' => 'test',
            ])
            ->setRowAttr([
                'color' => 'red',
            ])
            ->make(true);
    }

    public function getCustomFilter()
    {
        return view('datatables.blog.custom-filter');
    }

    public function getCustomFilterData(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->filter(function ($query) use ($request) {
                if ($request->has('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }

                if ($request->has('email')) {
                    $query->where('email', 'like', "%{$request->get('email')}%");
                }
            })
            ->make(true);
    }

    public function getCarbon()
    {
        return view('datatables.blog.carbon');
    }

    public function getCarbonData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->editColumn('created_at', '{!! $created_at !!}')
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at->format('Y/m/d');
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function getRelationships()
    {
        return view('datatables.blog.relationships', ['title' => 'Eloquent Datatable with Eager Loading']);
    }

    public function getRelationshipsData()
    {
        $posts = Post::with('user')->select('posts.*');

        return Datatables::of($posts)
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->make(true);
    }

    public function getJoins()
    {
        return view('datatables.blog.joins');
    }

    public function getJoinsData()
    {
        $posts = Post::join('users', 'posts.user_id', '=', 'users.id')
            ->select(['posts.id', 'posts.title', 'users.name', 'users.email', 'posts.created_at', 'posts.updated_at']);

        return Datatables::of($posts)
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->editColumn('name', function ($model) {
                return \HTML::mailto($model->email, $model->name);
            })
            ->make(true);
    }

    public function getMultiFilterSelect()
    {
        return view('datatables.blog.multi-filter-select');
    }

    public function getMultiFilterSelectData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)->make(true);
    }

    public function getRowDetails()
    {
        return view('datatables.blog.row-details');
    }

    public function getRowDetailsData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)->make(true);
    }

    public function getHasMany()
    {
        return view('datatables.blog.has-many');
    }

    public function getHasManyData()
    {
        $posts = User::first()->posts()->with('user');

        return Datatables::of($posts)
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->make(true);
    }

    public function getTransformer()
    {
        return view('datatables.blog.transformer');
    }

    public function getTransformerData()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)
            ->setTransformer(new \App\Transformers\UserTransformer)
            ->make(true);
    }

    public function getPostColumnSearch()
    {
        return view('datatables.blog.post-column-search');
    }

    public function anyColumnSearchData()
    {
        $users = User::select([
            DB::raw("CONCAT(users.id,'-',users.id) as user_id"),
            'name',
            'email',
            'created_at',
            'updated_at',
        ]);

        return Datatables::of($users)
            ->filterColumn('user_id', function($query, $keyword) {
                $query->whereRaw("CONCAT(users.id,'-',users.id) like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getRowNum()
    {
        return view('datatables.blog.rownum');
    }

    public function getRowNumData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $users = User::select([
            DB::raw('@rownum := @rownum + 1 AS rownum'),
            'id',
            'name',
            'email',
            'created_at',
            'updated_at']);
        $datatables = Datatables::of($users);

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }

    public function getBlacklist(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::query())
                ->blacklist(['password', 'name'])
                ->make(true);
        }

        return view('datatables.blog.blacklist', ['title' => 'Blacklist Columns']);
    }

    public function getWhitelist(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::query())
                ->whitelist(['name', 'email'])
                ->make(true);
        }

        return view('datatables.blog.whitelist', ['title' => 'Whitelist Columns']);
    }

    public function getOrderColumn(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::query())
                ->orderColumn('name', 'email $1')
                ->make(true);
        }

        return view('datatables.blog.order-column', ['title' => 'Order Column API']);
    }

    public function getWithTrashed(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::withTrashed())
                             ->withTrashed()
                             ->make(true);
        }

        return view('datatables.blog.with-trashed', ['title' => 'Model With Trashed (Soft Deletes)']);
    }
}
