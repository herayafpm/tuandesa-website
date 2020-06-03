@extends('admin.layout')
@section('headTitle',__('admin.soaljawabans'))
@php 
  $jenisbantuan_id = app('request')->input('jenis_bantuan')??$jenisbantuan->id;
@endphp
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('admin.soaljawabans')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{__('admin.soaljawabans')}}</li>
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
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-header">
                  @can('add_soaljawabans')
                  <div class="d-flex">
                    <a class="btn btn-success" href="{{url('admin/soaljawabans/create?jenis_bantuan='.$jenisbantuan_id)}}">Add New</a>
                    @foreach ($jenisbantuans as $item)
                      {!! Form::open(['url' => route('soaljawabans.index'), 'method' => 'GET']) !!}
                        {!! Form::hidden('jenis_bantuan', $item->id) !!}
                        <button type="submit" class="btn btn-primary mx-1">{{$item->name}}</button>
                      {!! Form::close() !!}
                    @endforeach
                    </div>
                  @endcan
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Soal</th>
                        <th>Bobot</th>
                        <th>Tipe</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        @can('edit_soaljawabans', 'delete_soaljawabans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($soaljawabans as $soaljawaban)
                        <tr>
                          <td>{{$soaljawaban->id}}</td>
                          <td>{{$soaljawaban->soal}}</td>
                          <td>{{$soaljawaban->bobot}}</td>
                          <td>{{$soaljawaban->getTipes($soaljawaban->tipe)}}</td>
                          <td>{{$soaljawaban->created_at}}</td>
                          <td>{{$soaljawaban->updated_at}}</td>
                          @can('edit_soaljawabans', 'delete_soaljawabans')
                          <td class="row w-100">
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/soaljawabans/'.$soaljawaban->id.'/edit')}}" role="button">
                            <i class="fa fa-fw fa-pen" aria-hidden="true"></i>
                            Edit</a>
                          {!! Form::open(['url' => 'admin/soaljawabans/'.$soaljawaban->id,'class'=> 'delete']) !!}
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
              <div class="card-footer">
                {{$soaljawabans->links()}}
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->

@stop
@push('scripts')

@endpush