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

<script src="https://cdn.enterwind.com/template/epanel/js/lib/select2/select2.full.min.js"></script>
<script>
    Nue.components.NueTomSelect.init('.js-select');
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
</script>

<div class="card-body">
    <div class="row mb-5">
        <div class="col-md-2">
            <h5 class="mt-1">{{ __('User ID') }}</h5>
        </div>
        <div class="col-md-7">
            @isset($edit)
                <div class="form-group mb-2">
                    <label class="form-label text-cap text-muted mb-1" for="roles[]">
                        {{ __('Nama') }}
                    </label>
                    {!! Form::text('name', null, ['class' => 'form-control rounded-xs', 'readonly']) !!}
                </div>
            @else
                <div class="form-group mb-2">
                    <label class="form-label text-cap text-muted mb-1" for="roles[]">
                        {{ __('Pilih User') }}
                    </label>
                    <select id="ajax-sso" class="form-control mb-1" name="pengguna"></select>
                    <span class="small">
                        <a href="javascript:;" onclick="history.go(0)">Refresh</a> bila tidak muncul.
                    </span>
                    {!! $errors->first('pengguna', '<span class="text-muted"><small>:message</small></span>') !!}
                </div>
            @endisset
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-2">
            <h5 class="mt-1">{{ __('Roles & Permissions') }}</h5>
        </div>
        <div class="col-md-7">
            <div class="form-group mb-2">
                <label class="form-label text-cap text-muted mb-1" for="roles[]">
                    {{ __('Roles') }}
                </label>
                <div class="tom-select-custom tom-select-custom-with-tags">
                    <select class="js-select form-select" autocomplete="off" multiple name="roles[]" 
                        data-nue-tom-select-options='{
                            "hideSearch": true,
                            "placeholder": "Select roles ..."
                        }'>
                        @foreach(config('nue.database.roles_model')::pluck('name', 'id') as $i => $temp)
                            <option value="{{ $i }}"
                                @isset($edit)
                                    {{ in_array($i, $edit->roles()->pluck('id')->toArray()) ? 'selected' : '' }}
                                @endisset
                            >
                                {{ $temp }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {!! $errors->first('roles[]', ' <span class="invalid-feedback">:message</span>') !!}
            </div>

            <div class="form-group mb-2">
                <label class="form-label text-cap text-muted mb-1" for="permissions[]">
                    {{ __('Permissions') }} 
                </label>
                <div class="tom-select-custom tom-select-custom-with-tags">
                    <select class="js-select form-select" autocomplete="off" multiple name="permissions[]" 
                        data-nue-tom-select-options='{
                            "hideSearch": true,
                            "placeholder": "Select permissions ..."
                        }'>
                        @foreach(config('nue.database.permissions_model')::pluck('name', 'id') as $i => $temp)
                            <option value="{{ $i }}"
                                @isset($edit)
                                    {{ in_array($i, $edit->permissions()->pluck('id')->toArray()) ? 'selected' : '' }}
                                @endisset
                            >
                                {{ $temp }}
                            </option>
                        @endforeach>
                    </select>
                </div>
                {!! $errors->first('permissions[]', ' <span class="invalid-feedback">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="card-footer bg-light border-bottom d-flex p-0">
    <button type="reset" class="btn btn-secondary rounded-0 me-0">{{ __('Reset') }}</button>
    <button type="submit" class="btn btn-success rounded-0">
        <i class="bi bi-save me-1"></i>
        {{ isset($edit) ? __('Save changes') : __('Save') }}
    </button>
</div>
