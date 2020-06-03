@extends('admin.layout')
@php
    $formTitle = !empty($soaljawaban)?'Update':'New'
@endphp
@section('headTitle',$formTitle.' '.__('admin.soaljawabans').' '.$soaljawaban->soal)
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1>{{$formTitle}} {{__('admin.soaljawabans')}} {{$soaljawaban->soal}}</h1>
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
              @if (!empty($jawaban))
                  {!! Form::model($jawaban, ['url' => 'admin/soaljawabans/'.$soaljawaban->id.'/edit-jawaban/'.$jawaban->id,'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/soaljawabans/'.$soaljawaban->id.'/add-jawaban']) !!}
              @endif
              {!! Form::hidden('soal_jawaban_id', $soaljawaban->id) !!}
              <div class="form-group">
                {!! Form::label('jawaban', 'Jawaban') !!}
                {!! Form::text('jawaban', null, ['class' => 'form-control','placeholder' => 'Jawaban']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('nilai', 'Nilai') !!}
                <select class="form-control" name="nilai" id="nilai">
                  <option value="">--Pilih Nilai--</option>
                  @for ($i = 1; $i <= $jumlahnilai; $i++)
                    <option value="{{$i}}" @if(!empty($jawaban) &&$i == $jawaban->nilai) selected @endif>{{$i}}</option>
                  @endfor
                </select>
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('soaljawabans.edit',$soaljawaban->id)}}" class="btn btn-secondary" >back</a>
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