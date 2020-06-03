@extends('admin.layout')
@php
    $formTitle = !empty($zakat)?'Update':'New'
@endphp
@section('headTitle',__('admin.zakats').' '.$formTitle)
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css')}}">   
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$formTitle}} Zakat</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/zakats')}}">Zakat</a></li>
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
              @if (!empty($zakat))
                  {!! Form::model($zakat, ['url' => ['admin/zakats',$zakat->id],'method' => 'PUT']) !!}
                  {!! Form::hidden('id') !!}
              @else
                  {!! Form::open(['url' => 'admin/zakats']) !!}
              @endif
              <div class="form-group">
                {!! Form::label('name', 'Judul') !!}
                {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Judul']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('date', 'Tanggal') !!}
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  {!! Form::text('date', null, ['class' => "form-control float-right",'id' => 'daterange' ]) !!}
                </div>
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              <a href="{{route('zakats.index')}}" class="btn btn-secondary" >back</a>
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

@push('scripts')
<script src="{{URL::asset('vendor/adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.js')}}"></script>

@if(!empty($zakat))
<script>
  var startDate;
  var endDate;
  $(function(){
    $('#daterange').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      startDate: "{{$zakat->start}}",
      endDate: "{{$zakat->end}}"
    })
  })
</script>
@else
<script>
  $('#daterange').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
  });
</script>
@endif

@endpush