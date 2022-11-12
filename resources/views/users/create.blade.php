@include('nue::partials.breadcrumb', ['lists' => [
    __('Extension') => 'javascript:;', 
    $title => route("$prefix.index"), 
    __('Create') => 'active'
]])

@include('nue::partials.toolbar', [
    'back' => route("$prefix.index")
])

{!! Form::open(['route' => "$prefix.store", 'form-pjax', 'files' => true]) !!}
    <div class="card rounded-0 shadow-none border-0">
        @include("$view.form")
    </div>
{!! Form::close() !!}
