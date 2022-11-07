@extends('layouts.base')
@section('title', __('Edit')." :: $title")

@section('css')
    <link rel="stylesheet" href="https://cdn.enterwind.com/template/epanel/css/separate/vendor/select2.min.css">
@endsection

@section('js')
    <script src="https://cdn.enterwind.com/template/epanel/js/lib/select2/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            'use strict'
            $("#ajax-sso").select2({
                ajax: {
                    url: 'https://sso.samarindakota.go.id/api/sso/findUser',
                    dataType: 'json',
                    delay: 250,
                    type: "POST",
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
                placeholder: 'Pilih Pengguna SSO',
                minimumInputLength: 2
            });
        });
    </script>
@endsection

@section('content')
    @include('nue::partials.breadcrumb', ['lists' => [
        __('Extension') => 'javascript:;', 
        $title => route("$prefix.index"), 
        __('Edit') => 'active'
    ]])

    @include('nue::partials.toolbar', [
        'back' => route("$prefix.index")
    ])

    {!! Form::model($edit, ['route' => ["$prefix.update", $edit->id], 'method' => 'PUT', 'files' => true]) !!}
        <div class="card rounded-0 shadow-none border-0">
            @include("$view.form")
        </div>
    {!! Form::close() !!}
@endsection