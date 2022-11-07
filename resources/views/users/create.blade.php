@extends('layouts.base')
@section('title', __('Create')." :: $title")

@section('css')
    <link rel="stylesheet" href="https://cdn.enterwind.com/template/epanel/css/separate/vendor/select2.min.css">
    <style type="text/css">
        .select2-dropdown {
            border: 1px solid #e5e5e5;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #eff0f7;
            -webkit-border-radius: 2.5px;
            border-radius: 2.5px;
            height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
            right: 7px;
            width: 25px;
        }
    </style>
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
        __('Create') => 'active'
    ]])

    @include('nue::partials.toolbar', [
        'back' => route("$prefix.index")
    ])

    {!! Form::open(['route' => "$prefix.store", 'files' => true]) !!}
        <div class="card rounded-0 shadow-none border-0">
            @include("$view.form")
        </div>
    {!! Form::close() !!}
@endsection