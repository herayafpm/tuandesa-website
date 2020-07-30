@extends('admin.layout')
@section('headTitle','Laporan Grafik Bantuan')
@push('css')
<link rel="stylesheet" href="{{url('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endpush
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Laporan Grafik Bantuan</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <div class="card-header">
              {!! Form::open(['url' => route('bantuanlaporans'), 'method' => 'GET']) !!}
              <div class="row">
                <div class="col-2">
                  {!! Form::select('jenis_bantuan_id',  $jenis_bantuans,app('request')->input('jenis_bantuan_id'),['placeholder' => '--Pilih Jenis Bantuan--','class' => 'form-control']) !!}
                </div>
                <div class="col-2">
                  {!! Form::select('status',  $statuses,app('request')->input('status'),['placeholder' => '--Pilih Status--','class' => 'form-control']) !!}
                </div>
                <div class="col-2">
                  <input name="bulan" type="text" id="datepicker1" class="form-control" value="{{app('request')->input('bulan')}}" placeholder="Pilih Bulan"/>
                </div>
                <div class="col-2">
                  <input name="tahun" type="text" id="datepicker2" class="form-control" value="{{app('request')->input('tahun')}}" placeholder="Pilih Tahun"/>
                </div>
                <div class="col-1">
                  <button type="submit" class="btn btn-primary">Proses</button>
                </div>
                <div class="col">
                  <a name="" id="" class="btn btn-primary" href="#" role="button" onClick="printLaporan()">Print Grafik</a>
                </div>
                <div class="col">
                  <a name="" id="" class="btn btn-success" target="_blank" href="{{url('admin/bantuanlaporanexcel')}}?jenis_bantuan_id={{app('request')->input('jenis_bantuan_id')}}&status={{app('request')->input('status')}}&bulan={{app('request')->input('bulan')}}&tahun={{app('request')->input('tahun')}}" role="button">Unduh Excel</a>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <canvas id="bantuanChart"></canvas>
              {{-- {!! $bantuanChart->container() !!} --}}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </section>
@endsection

@push('scripts')
  <script src="{{url('vendor/adminlte/plugins/chart.js/Chart.min.js')}}"></script>
  {{--@if($bantuanChart)
  {!! $bantuanChart->script() !!}
  @endif--}}
  <script src="{{url('vendor/adminlte/plugins/moment/moment.min.js')}}"></script>
  <script src="{{url('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{url('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js')}}"></script>
  <script>
    $(function(){
      $("#datepicker1").datepicker( {
          format: "mm",
          startView: "months", 
          minViewMode: "months"
      });
      $("#datepicker2").datepicker( {
          format: "yyyy",
          startView: "years", 
          minViewMode: "years"
      });
    })
    var color = Chart.helpers.color;
    var UserVsMyAppsData = {
        labels: <?= json_encode($data['labels'])?>,
        datasets:[{
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: <?= json_encode($data['dataset'])?>
        }]
    };
 
    var UserVsMyAppsCtx = document.getElementById('bantuanChart').getContext('2d');
    var UserVsMyApps = new Chart(UserVsMyAppsCtx, {
        type: 'bar',
        data: UserVsMyAppsData,
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
                display: false,
 
            },
            "hover": {
              "animationDuration": 0
            },
             "animation": {
                "duration": 1,
              "onComplete": function() {
                var chartInstance = this.chart,
                  ctx = chartInstance.ctx;
 
                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
 
                this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                  });
                });
              }
            },
            title: {
                display: true,
                padding:20,
                text: "<?=$data['titleChart']?>"
            },
        }
    });
    function printLaporan() {
      var canvas = UserVsMyAppsCtx.canvas
      var DataUrl = canvas.toDataURL('image/png', 1.0)
      var windowContent = '<!DOCTYPE html>';
      windowContent += '<html>'
      windowContent += '<head><title>Laporan Grafik Bantuan Sosial {{config("app.name")}}</title></head>';
      windowContent += '<body>'
      windowContent += '<img src="'+DataUrl+'"/>';
      windowContent += '</body>';
      windowContent += '</html>';
      var printWin = window.open('','','width=1280,height=760');
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