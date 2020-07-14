@extends('admin.layout')
@section('headTitle',"Bantuan Pemeringkatan")
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{"Bantuan Pemeringkatan"}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{"Bantuan Pemeringkatan"}}</li>
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
                  @can('add_bantuanpemeringkatans')
                  <div><a class="btn btn-primary" href="{{url('admin/bantuanpemeringkatans/create')}}">Add New</a></div>
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
                        <th>Judul</th>
                        <th>Dimulai</th>
                        <th>Berakhir</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        @can('edit_bantuanpemeringkatans', 'delete_bantuanpemeringkatans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($bantuanpemeringkatans as $bantuanpemeringkatan)
                        <tr>
                          <td>{{$bantuanpemeringkatan->id}}</td>
                          <td>{{$bantuanpemeringkatan->judul}}</td>
                          <td>{{\Carbon\Carbon::parse($bantuanpemeringkatan->start)->translatedFormat('d F Y H:i:s')}}</td>
                          <td>
                            {{\Carbon\Carbon::parse($bantuanpemeringkatan->end)->translatedFormat('d F Y H:i:s')}} /
                            {{\Carbon\Carbon::parse($bantuanpemeringkatan->end)->diffForHumans()}}
                          </td>
                          <td>{{$bantuanpemeringkatan->created_at}}</td>
                          <td>{{$bantuanpemeringkatan->updated_at}}</td>
                          @can('edit_bantuanpemeringkatans', 'delete_bantuanpemeringkatans')
                          <td class="row w-100">
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id)}}" role="button">
                            <i class="fa fa-fw fa-eye" aria-hidden="true"></i>
                            Detail</a>
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id.'/edit')}}" role="button">
                            <i class="fa fa-fw fa-pen" aria-hidden="true"></i>
                            Edit</a>
                          {!! Form::open(['url' => 'admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id,'class'=> 'delete']) !!}
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
                            <td colspan="7">No Records</td>
                          </tr>
                      @endforelse
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <div class="card-footer">
                {{$bantuanpemeringkatans->links()}}
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