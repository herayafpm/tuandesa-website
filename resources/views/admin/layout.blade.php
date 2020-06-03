@php
$param = Request::segment(2);
@endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{config('app.name')}} | @yield('headTitle')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{URL::asset('css/app.css')}}">
  <link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{URL::asset('vendor/adminlte/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{URL::asset('vendor/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script>
    window.Url = "<?=url('')?>"
    window.Laravel = <?= json_encode(['csrfToken' => csrf_token()])?>;
  </script>
  @if(!auth()->guest())
      <script>
          window.Laravel.userId = <?= auth()->user()->id ?>;
      </script>
  @endif
  <script src="{{URL::asset('js/app.js')}}"></script>
  @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed" style="height: auto;">
<!-- Site wrapper -->
<div class="wrapper">
  @include('admin.partials.navbar')
  @include('admin.partials.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<!-- Bootstrap 4 -->
<script src="{{URL::asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('vendor/adminlte/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('vendor/adminlte/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('vendor/adminlte/plugins/moment/moment-with-locales.min.js')}}"></script>
<script src="{{URL::asset('js/notifscript.js')}}"></script>
<script src="{{URL::asset('vendor/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script>
  $('#flash-overlay-modal').modal();
  // $('div.alert').not('.alert-important').delay(10000).fadeOut(350);
  $('.delete').on('submit',function(e){
    e.preventDefault();
    var form = this;
    return swal.fire({
        title: 'Yakin ingin menghapus data ini?',
        text: "Kamu mungkin tidak dapat mengembalikannya lagi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!'
      }).then((result) => {
        if(result.value){
          form.submit()
        }
      })
  })
  function logout(){
  return swal.fire({
      title: 'Yakin ingin mengakhiri sesi?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya!'
    }).then((result) => {
      if(result.value){
       $('#formlogout').submit()
      }
    })
  }
  var param = "<?=$param?>"
  $(function(){
    $('body').overlayScrollbars({ });
    if(param != 'dashboard' || param != 'profile'){
      $('#sidebarApp').find('.active').parent().parent().parent().addClass('menu-open')
    }
  })
</script>
@stack('scripts')
</body>
</html>
