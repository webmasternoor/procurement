@if (Auth::guest())
        <!-- <li><a href="{{ url('/auth/login') }}">Login</a></li> -->
        <!-- <li><a href="{{ url('/auth/register') }}">Register</a></li> -->
@else
<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display: block; position: static; margin-bottom: 5px; *width: 180px;">
    <li><a tabindex="-1" href="{{ url('/materialdetail') }}">Material Detail</a></li>
    <li><a tabindex="-1" href="{{ url('/materialsreqform') }}">Materials Requsition Form</a></li>
    <li><a tabindex="-1" href="{{ url('/productprocurement') }}">Product</a></li>
    <li><a tabindex="-1" href="{{ url('/statuspro') }}">Status</a></li>
    <li><a tabindex="-1" href="{{ url('/usergroup') }}">Usergroup</a></li>
    <li><a tabindex="-1" href="{{ url('/userspro') }}">Users</a></li>
</ul>
@endif