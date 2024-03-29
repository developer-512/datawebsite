@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.city.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.cities.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="city_name">{{ trans('cruds.city.fields.city_name') }}</label>
                    <input class="form-control {{ $errors->has('city_name') ? 'is-invalid' : '' }}" type="text" name="city_name" id="city_name" value="{{ old('city_name', '') }}" required>
                    @if($errors->has('city_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('city_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.city.fields.city_name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="country_id">{{ trans('cruds.city.fields.country') }}</label>
                    <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id" required>
                        @foreach($countries as $id => $entry)
                            <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('country'))
                        <div class="invalid-feedback">
                            {{ $errors->first('country') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.city.fields.country_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="state_id">{{ trans('cruds.city.fields.state') }}</label>
                    <select class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}" name="state_id" id="state_id" required>
                        @foreach($states as $id => $entry)
                            <option value="{{ $id }}" {{ old('state_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('state'))
                        <div class="invalid-feedback">
                            {{ $errors->first('state') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.city.fields.state_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>



@endsection
