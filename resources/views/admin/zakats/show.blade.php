@extends('admin.layout')
@section('headTitle','Detail '.$zakat->name)
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Detail {{$zakat->name}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('zakats.index')}}">Back</a></li>
              <li class="breadcrumb-item active">{{'Detail '.$zakat->name}}</li>
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
              <a name="" id="" class="btn btn-primary" href="{{url('admin/zakats/'.$zakat->id.'/pembagian')}}" role="button">Pembagian Zakat</a>
              </div>
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama Amil</th>
                        <th>Dusun</th>
                        <th>Beras</th>
                        <th>Uang</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        @can('edit_zakats', 'delete_zakats')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($zakat->amils as $zakatamil)
                        <tr>
                          <td>{{$zakatamil->id}}</td>
                          <td>{{$zakatamil->user->name}}</td>
                          <td>{{$zakatamil->dusun}}</td>
                          <td>{{$zakatamil->beras}} Kg</td>
                          <td>Rp. {{Currency::idr($zakatamil->uang)}}</td>
                          <td>{{$zakatamil->created_at}}</td>
                          <td>{{$zakatamil->updated_at}}</td>
                          @can('edit_zakats', 'delete_zakats')
                          <td class="row w-100">
                          {!! Form::open(['url' => 'admin/zakats/amil/'.$zakatamil->id,'class'=> 'delete']) !!}
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
              <div class="card p-2">
                <div class="card-header">
                  <h4>Pembagian Zakat</h4>
                  <p>Total Beras: {{round($beras,2)}} / Total uang: {{Currency::idr($uang)}}</p>
                  <div>
                    {!! Form::open(['url' => 'admin/zakats/'.$zakat->id.'/pembagian/reset','class'=> 'delete']) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-fw fa-sync" aria-hidden="true"></i>Reset Pembagian
                      </button>
                    {!! Form::close() !!}
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama Penerima</th>
                        <th>Beras</th>
                        <th>Uang</th>
                        <th>Tipe</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($pembagians as $zakatpembagian)
                        <tr>
                          <td>{{$zakatpembagian->id}}</td>
                          <td>{{$zakatpembagian->user->name}}</td>
                          <td>{{round($zakatpembagian->beras,2)}} Kg</td>
                          <td>Rp. {{Currency::idr($zakatpembagian->uang)}}</td>
                          <td>{{$zakatpembagian->tipe}}</td>
                          <td>{{$zakatpembagian->created_at}}</td>
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
                {{$pembagians->links()}}
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