@extends('layouts.app')
@section('content')
<!-- Content area -->
<div class="content">
    <vue-page-transition name="fade">
        <router-view/>
    </vue-page-transition>
</div>
<!-- /content area -->


<!-- Footer -->
<div class="navbar navbar-expand-lg navbar-light">
    <div class="text-center d-lg-none w-100">
        <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            Footer
        </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-footer">
        <span class="navbar-text">
            &copy; 2020
        </span>
    </div>
</div>
<!-- /footer -->
@endsection