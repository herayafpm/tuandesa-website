@extends('admin.layout')
@section('headTitle',__('admin.dataprofiledesas').' Image')
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Image Profile Desa</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/profiledesas')}}">Profil Desa</a></li>
              <li class="breadcrumb-item active">Profile Desa</li>
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
              @include('admin.partials.flash',['$errors' => $errors])
              {!! Form::open(['url' => 'admin/profiledesas/images/'.$profiledesaId, 'method' => "POST", 'enctype' => 'multipart/form-data']) !!}
              <div class="form-group">
                {!! Form::label('image', 'Profile Desa Image') !!}
                {!! Form::file('image', ['class' => 'form-control-file','placeholder' => 'Profile Desa Image']) !!}
              </div>
              <div class="form-footer pt-2 border-top">
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