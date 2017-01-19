<h1 class="page-header">User Group List
    <div class="pull-right">
        <a href="javascript:ajaxLoad('usergroup/create')" class="btn btn-primary pull-right"><i
                    class="glyphicon glyphicon-plus-sign"></i> New</a>
    </div>
</h1>
<div class="col-sm-7 form-group">
    <div class="input-group">
        <input class="form-control" id="search" value="{{ Session::get('usergroup_search') }}"
               onkeydown="if (event.keyCode == 13) ajaxLoad('{{url('usergroup/list')}}?ok=1&search='+this.value)"
               placeholder="Search..."
               type="text">

        <div class="input-group-btn">
            <button type="button" class="btn btn-default"
                    onclick="ajaxLoad('{{url('usergroup/list')}}?ok=1&search='+$('#search').val())"><i
                        class="glyphicon glyphicon-search"></i>
            </button>
        </div>
    </div>
</div>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="50px" style="text-align: center">No</th>
         <th>
            <a href="javascript:ajaxLoad('usergroup/list?field=user_id&sort={{Session::get("usergroup_sort")=="asc"?"desc":"asc"}}')">
                User Group Id
            </a>
            <i style="font-size: 12px"
               class="glyphicon  {{ Session::get('usergroup_field')=='user_id'?(Session::get('usergroup_sort')=='asc'?'glyphicon-sort-by-alphabet':'glyphicon-sort-by-alphabet-alt'):'' }}">
            </i>
        </th>
        <th>
            <a href="javascript:ajaxLoad('usergroup/list?field=name12&sort={{Session::get("usergroup_sort")=="asc"?"desc":"asc"}}')">
                Name/Designation
            </a>
            <i style="font-size: 12px"
               class="glyphicon  {{ Session::get('usergroup_field')=='name12'?(Session::get('usergroup_sort')=='asc'?'glyphicon-sort-by-alphabet':'glyphicon-sort-by-alphabet-alt'):'' }}">
            </i>
        </th>
        <th width="140px">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
    @foreach($usergroups as $key=>$usergroup)
        <tr>
            <td align="center">{{$i++}}</td>
            <td>{{$usergroup->id}}</td>
            <td>{{$usergroup->name12}}</td>
            <td style="text-align: center">
                <a class="btn btn-primary btn-xs" title="Edit"
                   href="javascript:ajaxLoad('usergroup/update/{{$usergroup->id}}')">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a class="btn btn-danger btn-xs" title="Delete"
                   href="javascript:if(confirm('Are you sure want to delete?')) ajaxLoad('usergroup/delete/{{$usergroup->id}}')">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="pull-right">{!! str_replace('/?','?',$usergroups->render()) !!}</div>
<div class="row">
    <i class="col-sm-12">
        Total: {{$usergroups->total()}} records
    </i>
</div>
<script>
    $('.pagination a').on('click', function (event) {
        event.preventDefault();
        ajaxLoad($(this).attr('href'));
    });
</script>