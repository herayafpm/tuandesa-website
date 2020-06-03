@extends('admin.layout')
@php
    $formTitle = !empty($pelayanan)?'Update':'New'
@endphp
@section('headTitle',__('admin.datapelayanans').' '.$formTitle)
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/select2/css/select2.min.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$formTitle}} {{__('admin.datapelayanans')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin/pelayanans')}}">{{__('admin.datapelayanans')}}</a></li>
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
              @if (!empty($pelayanan))
                  {!! Form::model($pelayanan, ['url' => ['admin/pelayanans',$pelayanan->id],'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/pelayanans','enctype' => 'multipart/form-data']) !!}
              @endif
              <div class="form-group">
                {!! Form::label('user', 'User') !!}
                {!! SelectUser::getSelect($users,null,['class' => 'form-control select-2', 'id' => 'user','name' => 'user_id','placeholder' => '--Pilih User--']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('jenis_pelayanan', 'Jenis Pelayanan') !!}
                {!! Form::select('jenis_pelayanan_id', $jenispelayanans,null, ['class' => 'form-control','id' => 'jenis_pelayanan','placeholder' => '--Pilih Jenis Pelayanan--']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('komentar', 'Komentar') !!}
                {!! Form::textarea('komentar', null, ['class' => 'form-control','placeholder' => 'Komentar']) !!}
              </div>
              @if(empty($pelayanan))
              <div class="form-group">
                {!! Form::label('image', 'Lampiran') !!}
                {!! Form::file('image[]', ['class' => 'form-control-file','placeholder' => 'Lampiran','multiple' => true]) !!}
              </div>
              @endif
              @if (!empty($pelayanan))
              <div class="form-group">
                {!! Form::label('status', 'Status') !!}
                {!! Form::select('status', $statuses,$pelayanan->status, ['class' => 'form-control']) !!}
              </div>
              @endif
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
              {!! Form::close() !!}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        @if (!empty($pelayanan))
            <div class="col-6">
            <div class="card">
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-header">
                  @can('add_pelayanans')
                  <div><a class="btn btn-primary" href="{{url('admin/pelayanans/'.$pelayanan->id.'/add-image')}}">Add New</a></div>
                  @endcan
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        @can('edit_pelayanans', 'delete_pelayanans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($pelayanan->pelayanan_images as $image)
                        <tr>
                          <td>{{$image->id}}</td>
                          <td>
                            <img style="width:150px" src="{{url($image->path)}}">
                          </td>
                          @can('edit_pelayanans', 'delete_pelayanans')
                          <td class="row w-100">
                          {!! Form::open(['url' => 'admin/pelayanans/images/'.$image->id,'class'=> 'delete']) !!}
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

@push('scripts')
  <script src="{{URL::asset('vendor/adminlte/plugins/select2/js/select2.min.js')}}"></script>
  <script>
    $(function(){
      $('.select-2').select2({
        placeholder: $('.select-2').attr('placeholder'),
        allowClear: true
      })
      $('.select-2').val("{{!empty($pelayanan)?$pelayanan->user->id:'0'}}")
      $('.select-2').trigger('change')
    })
  </script>
@endpush