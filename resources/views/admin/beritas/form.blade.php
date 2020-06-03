@extends('admin.layout')
@php
    $formTitle = !empty($berita)?'Update':'New'
@endphp
@section('headTitle',__('admin.databeritas').' '.$formTitle)
@push('css')
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$formTitle}} {{__('admin.databeritas')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin/beritas')}}">{{__('admin.databeritas')}}</a></li>
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
              @if (!empty($berita))
                  {!! Form::model($berita, ['url' => ['admin/beritas',$berita->id],'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/beritas', 'enctype' => 'multipart/form-data']) !!}
              @endif
              {!! Form::hidden('user_id', Auth::user()->id)!!}
              <div class="form-group">
                {!! Form::label('jenis_berita', 'Jenis berita') !!}
                {!! Form::select('jenis_berita_id', $jenisberitas,null, ['class' => 'form-control','id' => 'jenis_berita','placeholder' => '--Pilih Jenis berita--']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('komentar', 'Komentar') !!}
                {!! Form::textarea('komentar', null, ['class' => 'form-control','placeholder' => 'Komentar']) !!}
              </div>
              @if(empty($berita))
              <div class="form-group">
                {!! Form::label('image', 'Lampiran') !!}
                {!! Form::file('image[]', ['class' => 'form-control-file','placeholder' => 'Lampiran','multiple' => true]) !!}
              </div>
              @endif
              @if (!empty($berita))
              <div class="form-group">
                {!! Form::label('status', 'Status') !!}
                {!! Form::select('status', $statuses,$berita->status, ['class' => 'form-control']) !!}
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
        @if (!empty($berita))
            <div class="col-6">
            <div class="card">
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-header">
                  @can('add_beritas')
                  <div><a class="btn btn-primary" href="{{url('admin/beritas/'.$berita->id.'/add-image')}}">Add New</a></div>
                  @endcan
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        @can('edit_beritas', 'delete_beritas')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($berita->berita_images as $image)
                        <tr>
                          <td>{{$image->id}}</td>
                          <td>
                            <img style="width:150px" src="{{url($image->path)}}">
                          </td>
                          @can('edit_beritas', 'delete_beritas')
                          <td class="row w-100">
                          {!! Form::open(['url' => 'admin/beritas/images/'.$image->id,'class'=> 'delete']) !!}
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
@endpush