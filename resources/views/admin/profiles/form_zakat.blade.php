@extends('admin.layout')
@section('headTitle',$zakat->name)
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css')}}">   
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1>{{$zakat->name}}</h1>
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
              {!! Form::model($zakatamil, ['url' => route('profile.zakat',$zakat->id),'method' => 'PUT','id' => 'formZakat']) !!}
              {!! Form::hidden('id') !!}
              {!! Form::hidden('zakat_id', $zakat->id) !!}
              <div class="form-group">
                {!! Form::label('dusun', 'Dusun') !!}
                <select class="form-control" name="dusun" id="dusun">
                  @for ($i = 1; $i <= $jumlahdusun; $i++)
                    <option value="{{$i}}" @if(!empty($zakatamil) &&$i == $zakatamil->dusun) selected @endif>{{$i}}</option>
                  @endfor
                </select>
              </div>
              <div class="form-group">
                {!! Form::label('beras', 'Beras') !!}
                {!! Form::text('beras', $zakatamil->beras ?? null, ['id' => 'beras','class' => 'form-control', 'data-inputmask'=> "'alias':'currency'",'digits'=>'0','allowMinus'=>'false','showMaskOnFocus'=>'false','showMaskOnHover'=>'false']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('uang', 'Uang') !!}
                {!! Form::text('uang', $zakatamil->uang ?? null, ['id' => 'uang','class' => 'form-control','data-inputmask'=> "'alias':'currency'",'digits'=>'0','allowMinus'=>'false','showMaskOnFocus'=>'false','showMaskOnHover'=>'false']) !!}
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              <a href="{{route('profile.index')}}" class="btn btn-secondary" >back</a>
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
<script src="{{URL::asset('vendor/adminlte/plugins/inputmask/jquery.inputmask.bundle.js')}}"></script>
<script>
  $(function(){
    $('#beras').inputmask({
      removeMaskOnSubmit: true,
      prefix: ' Kg '
    });
    $('#uang').inputmask({
      removeMaskOnSubmit: true,
      prefix: ' Rp '
    });
  })

</script>
@endpush