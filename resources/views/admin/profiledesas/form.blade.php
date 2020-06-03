@extends('admin.layout')
@php
    $formTitle = !empty($profiledesa)?'Update':'New'
@endphp
@section('headTitle',__('admin.dataprofiledesas').' '.$formTitle)
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$formTitle}} Profile Desa</h1>
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
              @include('flash::message')
              @include('admin.partials.flash',['$errors' => $errors])
              @if (!empty($profiledesa))
                  {!! Form::model($profiledesa, ['url' => ['admin/profiledesas',$profiledesa->id],'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/profiledesas']) !!}
              @endif
              <div class="form-group">
                {!! Form::label('judul', 'Judul') !!}
                {!! Form::text('judul', null, ['class' => 'form-control','placeholder' => 'Judul']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','placeholder' => 'Description']) !!}
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
        @if (!empty($profiledesa))
            <div class="col-6">
            <div class="card">
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-header">
                  @can('add_profiledesas')
                  <div><a class="btn btn-primary" href="{{url('admin/profiledesas/'.$profiledesa->id.'/add-image')}}">Add New</a></div>
                  @endcan
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        @can('edit_profiledesas', 'delete_profiledesas')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($profiledesa->profile_desa_images as $image)
                        <tr>
                          <td>{{$image->id}}</td>
                          <td>
                            <img style="width:150px" src="{{url($image->path)}}">
                          </td>
                          @can('edit_profiledesas', 'delete_profiledesas')
                          <td class="row w-100">
                          {!! Form::open(['url' => 'admin/profiledesas/images/'.$image->id,'class'=> 'delete']) !!}
                          {!! Form::hidden('_method', 'DELETE') !!}
                            <button type="submit" class="btn btn-danger btn-sm">
                              <i class="fa fa-fw fa-trash" aria-hidden="true"></i>Delete
                            </button>
                          {!! Form::close() !!}
                          </td>
                          @endcan
                        </tr>
                      @empty
                          <tr>
                            <td colspan="5">No Records</td>
                          </tr>
                      @endforelse
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        @endif
      </div>
    </section>
@endsection