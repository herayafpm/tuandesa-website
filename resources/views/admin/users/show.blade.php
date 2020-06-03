@extends('admin.layout')
@section('headTitle',__('admin.profile'))
@push('css')
@endpush
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('admin.profile'). ' '.$user->username}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{__('admin.profile')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      @include('flash::message')
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{url($user->photo)}}" alt="User profile picture">
                </div>

              <h3 class="profile-username text-center">{{$user->name}}</h3>
              <p class="text-muted text-center">{{$user->username}}</p>

              <p class="text-muted text-center">{{$user->getRoleNames()[0]}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                  <b>Aduan</b> <a class="float-right">{{$user->aduans->count()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Pelayanan</b> <a class="float-right">{{$user->pelayanans->count()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Bantuan</b> <a class="float-right">{{$user->pelayanans->count()}}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <div class="float-right">
                  <a class="btn btn-secondary btn-sm" href="{{route('users.index')}}" role="button">
                    Back</a>
                  <a class="btn btn-primary btn-sm" href="{{url('admin/users/'.$user->id.'/edit')}}" role="button">
                    <i class="fa fa-fw fa-pen" aria-hidden="true"></i>
                    Edit</a>
                </div>
                <div class="form-group">
                  {!! Form::label('email', 'Email') !!}
                  {!! Form::text('email', $user->email, ['class' => 'form-control','placeholder' => 'Email','readonly'=> true]) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('ttl', 'Tempat Tanggal Lahir') !!}
                  {!! Form::text('ttl', $user->ttl, ['class' => 'form-control','placeholder' => 'Tempat Tanggal Lahir','readonly'=> true]) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('alamat', 'Alamat') !!}
                  {!! Form::textarea('alamat', $user->address, ['class' => 'form-control','placeholder' => 'Alamat','readonly'=> true]) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('no_hp', 'NO HP') !!}
                  {!! Form::text('no_hp', $user->no_hp, ['class' => 'form-control','placeholder' => 'NO HP','readonly'=> true]) !!}
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      
    </section>
    <!-- /.content -->

@stop
@push('scripts')
@endpush