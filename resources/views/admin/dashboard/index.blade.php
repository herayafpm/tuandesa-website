@extends('admin.layout')
@section('headTitle',"Dashboard")
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="row">
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$users}}</h3>

                <p>Total Pengguna</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              @can('view_users')
              <a href="{{url('admin/users')}}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              @endcan
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$aduans}}</h3>

                <p>Aduan</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              @can('view_aduans')
              <a href="{{url('admin/aduans')}}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              @endcan
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$pelayanans}}</h3>

                <p>Pelayanan</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              @can('view_pelayanans')
              <a href="{{url('admin/pelayanans')}}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              @endcan
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$bantuans}}</h3>

                <p>Bantuan Sosial</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              @can('view_bantuans')
              <a href="{{url('admin/bantuans')}}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              @endcan
            </div>
          </div>
          <!-- ./col -->
        </div>
    </div>
    </section>
    <!-- /.content -->

@stop