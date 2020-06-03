@extends('admin.layout')
@php
    $formTitle = !empty($soaljawaban)?'Update':'New'
@endphp
@section('headTitle',$formTitle.' '.__('admin.soaljawabans').' '.$jenisbantuan->name)
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1>{{$formTitle}} {{__('admin.soaljawabans')}} {{$jenisbantuan->name}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin/soaljawabans')}}">{{__('admin.soaljawabans')}}</a></li>
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
              @if (!empty($soaljawaban))
                  {!! Form::model($soaljawaban, ['url' => ['admin/soaljawabans',$soaljawaban->id],'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/soaljawabans']) !!}
              @endif
              {!! Form::hidden('jenis_bantuan_id', $jenisbantuan->id) !!}
              <div class="form-group">
                {!! Form::label('soal', 'Soal') !!}
                {!! Form::text('soal', null, ['class' => 'form-control','placeholder' => 'Soal']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('bobot', 'bobot') !!}
                {!! Form::number('bobot', null, ['class' => 'form-control','placeholder' => 'Bobot untuk perhitungan']) !!}
                <p class="text-muted">* Bobot yang bisa digunakkan {{$bobot}}</p>
              </div>
              <div class="form-group">
                {!! Form::label('tipe', 'Tipe') !!}
                {!! Form::select('tipe',$tipes, null, ['class' => 'form-control','placeholder' => '--Pilih Tipe--']) !!}
                <p class="text-muted">* benefit = semakin besar nilai yang dimasukkan penduduk maka semakin baik<br>
                  * cost = semakin kecil nilai yang dimasukkan penduduk maka semakin baik</p>
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              <a href="{{route('soaljawabans.index')}}" class="btn btn-secondary" >back</a>
              </div>
              {!! Form::close() !!}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        @if (!empty($soaljawaban))
            <div class="col-8">
            <div class="card">
              <!-- /.card-header -->
              <div class="card p-2">
                <div class="card-header">
                  @can('add_jawabans')
                  <div><a class="btn btn-primary" href="{{url('admin/soaljawabans/'.$soaljawaban->id.'/add-jawaban')}}">Add New</a></div>
                  @endcan
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-fixed-head">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Jawaban</th>
                        <th>Nilai</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        @can('edit_jawabans', 'delete_jawabans')
                        <th>Action</th>
                        @endcan
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($soaljawaban->jawabans as $jawaban)
                        <tr>
                          <td>{{$jawaban->id}}</td>
                          <td>{{$jawaban->jawaban}}</td>
                          <td>{{$jawaban->nilai}}</td>
                          <td>{{$jawaban->created_at}}</td>
                          <td>{{$jawaban->updated_at}}</td>
                          @can('edit_jawabans', 'delete_jawabans')
                          <td class="row w-100">
                          <a class="btn btn-primary btn-sm mr-1" href="{{url('admin/soaljawabans/'.$soaljawaban->id.'/edit-jawaban/'.$jawaban->id)}}" role="button">
                          <i class="fa fa-fw fa-pen" aria-hidden="true"></i>
                          Edit</a>
                          {!! Form::open(['url' => 'admin/soaljawabans/'.$soaljawaban->id.'/delete-jawaban/'.$jawaban->id,'class'=> 'delete']) !!}
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