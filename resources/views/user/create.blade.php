@extends('layouts.admin3')

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Role') }}</div>

                <div class="card-body">
                    @include('custom.message')

                    <form action="{{ route('role.store')}}" method="POST">
                    @csrf

                    <div class="container">
                    <h3>{{__('Require Data')}}</h3>

                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                    <input name="name" type="text" class="form-control" id="name" value="{{ old('name')}}" placeholder="{{__('Name')}}...">
                    </div>

                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input name="slug" type="text" class="form-control" id="slug" value="{{ old('slug')}}" placeholder="{{__('slug')}}...">
                    </div>

                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" name="description" id="description" placeholder="{{__('Description')}}..." rows="3">{{ old('description')}}</textarea>
                      </div>
                    </div>

                    <hr>
                    <h3>{{__('Full Access')}}</h3>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="fullaccessyes" name="full-access" class="custom-control-input" value="yes"
                        @if (old('full-access')=="yes")
                            checked
                        @endif>
                    <label class="custom-control-label" for="fullaccessyes">{{__('Yes')}}</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="fullaccessno" name="full-access" class="custom-control-input" value="no"
                        @if (old('full-access')=="no")
                            checked
                        @endif

                        @if (old('full-access')===null)
                            checked
                        @endif>
                        <label class="custom-control-label" for="fullaccessno">{{__('No')}}</label>
                      </div>

                    <hr>

                    <h3>{{__('Permission List')}}</h3>
                    @foreach ($permissions as $permission)

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                         class="custom-control-input"
                         id="permission_{{$permission->id}}"
                         value="{{$permission->id}}"
                         name="permission[]"
                         @if( is_array(old('permission')) && in_array("$permission->id", old('permission')))
                         checked
                         @endif

                         >
                    <label class="custom-control-label"
                        for="permission_{{$permission->id}}">
                        {{$permission->id}}
                        -
                        {{__($permission->name)}}
                        <em>({{__($permission->description)}})</em>
                    </label>
                      </div>
                      @endforeach

                      <hr>

                      <input class="btn btn-primary" type="submit" value="{{__('Save')}}">
                    </form>

                    {{-- {!! dd(old())!!} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
