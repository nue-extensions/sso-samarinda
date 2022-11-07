<div class="card-body bg-light pb-10" style="min-height: calc(100vh - 130px);">

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
                    <select id="ajax-sso" class="form-control" name="pengguna"></select>
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
    <div class="mb-10">&nbsp;</div>
</div>

<div class="position-fixed start-50 bottom-0 translate-middle-x w-100 zi-99 mb-3" style="max-width: 40rem;">
    <div class="card card-sm bg-dark border-dark mx-2">
        <div class="card-body">
            <div class="row justify-content-center justify-content-sm-between">
                <div class="col"></div>
                <div class="col-auto">
                    <div class="d-flex gap-3">
                        <button type="reset" class="btn btn-ghost-light">{{ __('Reset') }} </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }} 
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>