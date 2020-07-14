@extends('admin.layout')
@section('headTitle',__('admin.databantuans').' Sosial '.$bantuan->jenisbantuan->name)
@push('css')
<link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/select2/css/select2.min.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1>{{__('admin.databantuans')}} {{$bantuan->user->name}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin/bantuans')}}">{{__('admin.databantuans')}}</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row">
        <div class="col">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                {!! Form::open(['url' => ['admin/bantuans',$bantuan->id],'method' => 'PUT']) !!}
                <div class="form-group">
                    {!! Form::label('jenis_bantuan', 'Jenis bantuan') !!}
                    {!! Form::text('name', $bantuan->jenisbantuan->name, ['class' => 'form-control disabled','placeholder' => 'Name','readonly'=>true]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $statuses,$bantuan->status, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('komentar', 'Komentar') !!}
                    {!! Form::textarea('komentar', $bantuan->komentar, ['rows'=>3,'class' => 'form-control','placeholder' => 'Komentar','readonly' =>true]) !!}
                </div>
                <div class="form-footer pt-2 border-top">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-5">
            <div class="card">
            <!-- /.card-header -->
            <div class="card p-2">
                <div class="card-body table-responsive p-0">
                <table class="table table-hover table-fixed-head">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Soal</th>
                        <th>Jawaban</th>
                        <th>Nilai</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $no = 1; @endphp
                    @forelse ($bantuan->bantuan_jawabans as $jawaban)
                        <tr>
                        <td>{{$no}}</td>
                        <td>
                            {{$jawaban->soal_jawaban->soal}}
                        </td>
                        <td>
                            {{$jawaban->jawaban->jawaban}}
                        </td>
                        <td>
                            {{$jawaban->jawaban->nilai}}
                        </td>
                        </tr>
                    @php $no++ @endphp
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
        <div class="col-4">
            <div class="card">
            <!-- /.card-header -->
            <div class="card p-2">
                <div class="card-body table-responsive p-0">
                <table class="table table-hover table-fixed-head">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($bantuan->bantuan_images as $image)
                        <tr>
                        <td>{{$image->id}}</td>
                        <td>
                            <img style="width:150px" src="{{url($image->path)}}">
                        </td>
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
      $('.select-2').val("{{!empty($bantuan)?$bantuan->user->id:'0'}}")
      $('.select-2').trigger('change')
    })
  </script>
@endpush