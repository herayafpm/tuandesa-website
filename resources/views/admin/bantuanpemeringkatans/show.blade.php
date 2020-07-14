@extends('admin.layout')
@section('headTitle','Detail '.$bantuanpemeringkatan->judul)
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Detail {{$bantuanpemeringkatan->judul}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('bantuanpemeringkatans.index')}}">Back</a></li>
              <li class="breadcrumb-item active">{{'Detail '.$bantuanpemeringkatan->judul}}</li>
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
              <div class="card-header">
              @if(sizeof($pemeringkatans) == 0)
              {!! Form::open(['url' => 'admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id.'/peringkat']) !!}
              {!! Form::hidden('_method', 'POST') !!}
                <button type="submit" class="btn btn-primary btn-md">
                  <i class="fa fa-fw fa-sync" aria-hidden="true"></i>Mulai Pemeringkatan
                </button>
              {!! Form::close() !!}
              <!-- <a name="" id="" class="btn btn-primary" href="{{url('admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id.'/peringkat')}}" role="button">Mulai Pemeringkatan</a> -->
              @endif
              </div>
              <div class="card p-2">
                <div class="card-header">
                  <h4>Pemeringkatan</h4>
                  @if(sizeof($pemeringkatans) > 0)
                  <div>
                    {!! Form::open(['url' => 'admin/bantuanpemeringkatans/'.$bantuanpemeringkatan->id.'/peringkat/reset','class'=> 'delete']) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-fw fa-sync" aria-hidden="true"></i>Reset Pemeringkatan
                      </button>
                    {!! Form::close() !!}
                  </div>
                  @endif
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Peringkat</th>
                        <th>Nama Pengajuan Bantuan</th>
                        <th>Total</th>
                        <th>Created At</th>
                        @can('edit_bantuanpemeringkatans', 'delete_bantuanpemeringkatans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($pemeringkatans as $pemeringkatan)
                        <tr>
                          <td>{{$pemeringkatan->id}}</td>
                          <td>{{$pemeringkatan->peringkat}}</td>
                          <td>{{$pemeringkatan->bantuan->user->name}}</td>
                          <td>{{ceil($pemeringkatan->total)}}%</td>
                          <td>{{$pemeringkatan->created_at}}</td>
                          @can('edit_bantuanpemeringkatans', 'delete_bantuanpemeringkatans')
                          <td class="row w-100">
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/bantuans/'.$pemeringkatan->bantuan_id)}}" role="button">
                            <i class="fa fa-fw fa-eye" aria-hidden="true"></i>
                            Detail</a>
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
                {{$pemeringkatans->links()}}
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