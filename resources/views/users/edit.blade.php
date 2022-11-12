@include('nue::partials.breadcrumb', ['lists' => [
    __('Extension') => 'javascript:;', 
    $title => route("$prefix.index"), 
    __('Edit') => 'active'
]])

@include('nue::partials.toolbar', [
    'back' => route("$prefix.index")
])

{!! Form::model($edit, ['route' => ["$prefix.update", $edit->id], 'method' => 'PUT', 'form-pjax', 'files' => true]) !!}
    <div class="card rounded-0 shadow-none border-0">
        @include("$view.form")
    </div>
{!! Form::close() !!}
