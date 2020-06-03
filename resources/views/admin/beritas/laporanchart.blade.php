@extends('admin.layout')
@section('headTitle',__('admin.datapelayanans').' Grafik Laporan')
@push('css')
<link rel="stylesheet" href="{{url('public/admin/plugins/daterangepicker/daterangepicker.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Laporan Grafik Pelayanan</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <div class="card-tools">
                {!! Form::open(['url' => route('laporanpelayanan.index'), 'method' => 'GET']) !!}
                <div class="row">
                  <div class="col">
                    {!! Form::select('tipe',  ['0' => 'Bulan','1' => 'Tahun'],app('request')->input('tipe'),['placeholder' => '--Pilih Tipe--','class' => 'form-control']) !!}
                  </div>
                  <div class="col">
                    <input name="date" type="daterange" id="datepicker1" class="form-control" value={{app('request')->input('date')}}/>
                  </div>
                  <div class="col">
                    <button type="submit" class="btn btn-primary">Proses</button>
                  </div>
                  <div class="col">
                    <a name="" id="" class="btn btn-primary" href="#" role="button" onClick="printLaporan()">Print Grafik</a>
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              {!! $pelayananChart->container() !!}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </section>
@endsection

@push('scripts')
  <script src="{{url('public/admin/plugins/chart.js/Chart.min.js')}}"></script>
  @if($pelayananChart)
  {!! $pelayananChart->script() !!}
  @endif
  <script src="{{url('public/admin/plugins/moment/moment.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <script>
    $(function(){
      $('#datepicker1').daterangepicker({
            locale: {
              format: 'MM-YYYY'
            },
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10)
        })
    })
    function printLaporan() {
      var canvas = {{$pelayananChart->id}}.ctx.canvas
      var DataUrl = canvas.toDataURL('image/png', 1.0)
      var windowContent = '<!DOCTYPE html>';
      windowContent += '<html>'
      windowContent += '<head><title>Laporan Grafik Pelayanan {{config("app.name")}}</title></head>';
      windowContent += '<body>'
      windowContent += '<img src="'+DataUrl+'"/>';
      windowContent += '</body>';
      windowContent += '</html>';
      var printWin = window.open('','','width=440,height=440');
      printWin.document.open();
      printWin.document.write(windowContent);
      printWin.document.close();
      printWin.location.reload();
      printWin.location.reload();
      printWin.location.reload();
      printWin.focus();
      printWin.print();
      printWin.close();
    }
  </script>
@endpush