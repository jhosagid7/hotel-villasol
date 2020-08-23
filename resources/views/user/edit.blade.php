@extends('layouts.admin3')

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit User') }}</div>

                <div class="card-body">
                    @include('custom.message')

                    <form action="{{ route('user.update', $user->id)}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="container">
                    <h3>{{__('Require Data')}}</h3>

                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                    <input name="name" type="text" class="form-control" id="name" value="{{ old('name', $user->name)}}" placeholder="{{__('Name')}}...">
                    </div>

                    <div class="form-group">
                        <label for="email">{{__('email')}}</label>
                        <input name="email" type="text" class="form-control" id="email" value="{{ old('email', $user->email)}}" placeholder="{{__('email')}}...">
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="roles">Roles</label>
                        </div>
                        <select name="roles" class="custom-select" id="roles">
                          {{-- <option selected>Choose...</option> --}}
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    @isset($user->roles[0]->name)
                                        @if ($role->name == $user->roles[0]->name)
                                        selected
                                        @endif
                                    @endisset>{{ $role->name }}</option>
                            @endforeach
                        </select>
                      </div>

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
