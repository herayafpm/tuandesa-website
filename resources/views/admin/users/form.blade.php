@extends('admin.layout')
@php
    $formTitle = !empty($user)?'Update':'New'
@endphp
@section('headTitle',__('admin.datausers').' '.$formTitle)
@push('css')
<link rel="stylesheet" href="{{url('public/admin/plugins/select2/css/select2.min.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$formTitle}} {{__('admin.datausers')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin/users')}}">{{__('admin.datausers')}}</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              @include('flash::message')
              @include('admin.partials.flash',['$errors' => $errors])
              @if (!empty($user))
                  {!! Form::model($user, ['url' => ['admin/users',$user->id],'method' => 'PUT','enctype' => 'multipart/form-data']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/users', 'enctype' => 'multipart/form-data']) !!}
              @endif
              <div class="form-group">
                {!! Form::label('username', 'Username') !!}
                {!! Form::text('username', null, ['class' => 'form-control','placeholder' => 'Username']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('name', 'Nama Lengkap') !!}
                {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Nama Lengkap']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('email', 'Email') !!}
                {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('ttl', 'Tempat Tanggal Lahir') !!}
                {!! Form::text('ttl', null, ['class' => 'form-control','placeholder' => 'Tempat Tanggal Lahir']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('alamat', 'Alamat') !!}
                {!! Form::textarea('address', null, ['class' => 'form-control','placeholder' => 'Alamat']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('no_hp', 'NO HP') !!}
                {!! Form::text('no_hp', null, ['class' => 'form-control','placeholder' => 'NO HP']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('role', 'Status') !!}
                {!! Form::select('roles', $roles,null,  ['class' => 'form-control','placeholder' => '--Pilih Status Pengguna--']) !!}
              </div>
              @if(empty($user))
                <div class="form-group">
                  {!! Form::label('password', 'Password') !!}
                  {!! Form::input('password', 'password', config('app.default_pass'),['class' => 'form-control','placeholder' => '--Password--']) !!}
                  <p class="text-muted">Password default: {{config('app.default_pass')}}</p>
                </div>
              @endif
              <div class="form-group">
                {!! Form::label('photo', 'Foto Profil') !!}
                {!! Form::file('photo', ['class' => 'form-control-file','placeholder' => 'Foto Profil']) !!}
                <p class="text-muted">Kosongi jika tidak ingin diubah atau tetap default</p>
              </div>
              <div class="form-footer pt-2 border-top">
                <a name="" id="" class="btn btn-secondary" href="{{route('users.index')}}" role="button">Back</a>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
              {!! Form::close() !!}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </section>
@endsection

@push('scripts')
@endpush