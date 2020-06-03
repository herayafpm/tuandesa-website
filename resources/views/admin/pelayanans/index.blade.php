@extends('admin.layout')
@section('headTitle',__('admin.datapelayanans'))
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css')}}">   
@endpush
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('admin.datapelayanans')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{__('admin.datapelayanans')}}</li>
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
                  @can('add_pelayanans')
                  <div><a class="btn btn-primary" href="{{url('admin/pelayanans/create')}}">Add New</a></div>
                  @endcan
                  <div class="card-tools">
                    <div class="input-group input-group-sm">
                      {!! Form::open(['url' => route('pelayanans.index'), 'method' => 'GET']) !!}
                        <div class="row">
                          <div class="col">
                            {!! Form::select('orderBy',  ['id' => 'id','created_at' => 'Dibuat','status' => 'Status'],app('request')->input('orderBy'),['placeholder' => '--Pilih Order By--','class' => 'form-control']) !!}
                          </div>
                          <div class="col">
                            {!! Form::select('order',  ['asc' => 'ASC','desc' => 'DESC'],app('request')->input('order'),['placeholder' => '--Pilih Order--','class' => 'form-control']) !!}
                          </div>
                          <div class="col-4">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                              <input name="date" type="text" class="form-control float-right" id="daterange" value={{urldecode(app('request')->input('date'))}}>
                            </div>
                          </div>
                          <div class="col">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{app('request')->input('search')}}">
                          </div>
                          <div class="col">
                            <button type="submit" class="btn btn-primary">Proses</button>
                          </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Jenis Pelayanan</th>
                        <th>Komentar</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        @can('edit_pelayanans', 'delete_pelayanans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($pelayanans as $pelayanan)
                        <tr>
                          <td>{{$pelayanan->id}}</td>
                          <td>{{$pelayanan->user->username}}</td>
                          <td>{{$pelayanan->jenispelayanan->name}}</td>
                          <td>{{Str::limit($pelayanan->komentar,10)}}</td>
                          <td>{{$pelayanan->getStatus($pelayanan->status)}}</td>
                          <td>{{$pelayanan->created_at}}</td>
                          <td>{{$pelayanan->updated_at}}</td>
                          @can('edit_pelayanans', 'delete_pelayanans')
                          <td class="row w-100">
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/pelayanans/'.$pelayanan->id.'/edit')}}" role="button">
                            <i class="fa fa-fw fa-pen" aria-hidden="true"></i>
                            Edit</a>
                          {!! Form::open(['url' => 'admin/pelayanans/'.$pelayanan->id,'class'=> 'delete']) !!}
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
                            <td colspan="8">No Records</td>
                          </tr>
                      @endforelse
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <div class="card-footer">
                {{$pelayanans->appends(request()->input())->links()}}
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
<script src="{{URL::asset('vendor/adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
  var startDate = "{{date('Y-m-d')}}";
  var endDate;
  $(function(){
    var date = "{{app('request')->input('date')}}";
    if(date){
      date = date.split(" - ")
      startDate = date[0];
      endDate = date[1];
      $('#daterange').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        },
        startDate,
        endDate
      })
    }else{
      $('#daterange').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      })
    }
    
  })
  
</script>
@endpush