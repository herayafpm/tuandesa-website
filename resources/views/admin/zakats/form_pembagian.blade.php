@extends('admin.layout')
@section('headTitle','Pembagian '.$zakat->name)
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/select2/css/select2.min.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{'Pembagian '.$zakat->name}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('zakats.show',$zakat->id)}}">Back</a></li>
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
              {!! Form::open(['url' => 'admin/zakats/'.$zakat->id.'/pembagian']) !!}
              {!! Form::hidden('zakat_id', $zakat->id) !!}
              <div class="form-group">
                {!! Form::label('user', 'Penerima Zakat') !!}
                {!! SelectUser::getSelect($users,null,['class' => 'form-control select-2','multiple' => true, 'id' => 'user','name' => 'user_id[]','placeholder' => '--Pilih User--']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('presentase_user', 'Presentase Penerima Zakat') !!}
                {!! Form::number('presentase_user', null,['placeholder'=> 'Isikan presentase pembagian user','class' => 'form-control','max'=>'100','min'=> '0']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('amil', 'Penerima Amil') !!}
                {!! SelectUser::getSelect($amils,null,['class' => 'form-control select-2-amil','multiple' => true, 'id' => 'amil','name' => 'amil_id[]','placeholder' => '--Pilih Amil--']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('presentase_amil', 'Presentase Penerima Amil') !!}
                {!! Form::number('presentase_amil', null,['placeholder'=> 'Isikan presentase pembagian amil','class' => 'form-control','max'=>'100','min'=> '0']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('fisabililah', 'Penerima Fisabilillah (Sisa Pembulatan)') !!}
                {!! SelectUser::getSelect($users,null,['class' => 'form-control select-2-fisabililah','multiple' => true, 'id' => 'fisabililah','name' => 'fisabililah_id[]','placeholder' => '--Pilih Fisabililah--']) !!}
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
 <script src="{{URL::asset('vendor/adminlte/plugins/select2/js/select2.min.js')}}"></script>
 <script>
    $(function(){
      $('.select-2').select2({
        placeholder: $('.select-2').attr('placeholder'),
        allowClear: true
      })
      $('.select-2-amil').select2({
        placeholder: $('.select-2-amil').attr('placeholder'),
        allowClear: true
      })
      $('.select-2-fisabililah').select2({
        placeholder: $('.select-2-fisabililah').attr('placeholder'),
        allowClear: true
      })
    })
  </script>
@endpush