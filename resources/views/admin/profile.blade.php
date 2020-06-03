@extends('admin.layout')
@section('headTitle',__('admin.profile'))
@php
  $status = $user->getRoleNames()[0];
  $ajuan = false;
  if($status == 'User' || $status == 'Amil'){
    $ajuan = true;
  }
@endphp
@push('css')
@endpush
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('admin.profile')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{__('admin.profile')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      @include('flash::message')
      @include('admin.partials.flash',['$errors' => $errors])
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{url($user->photo)}}" alt="User profile picture">
                </div>

              <h3 class="profile-username text-center">{{$user->name}}</h3>
              <p class="text-muted text-center">{{$user->username}}</p>

              <p class="text-muted text-center">{{$user->getRoleNames()[0]}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                  <b>Aduan</b> <a class="float-right">{{$user->aduans->count()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Pelayanan</b> <a class="float-right">{{$user->pelayanans->count()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Bantuan</b> <a class="float-right">{{$user->pelayanans->count()}}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#aduan" data-toggle="tab">Aduan Saya</a></li>
                  <li class="nav-item"><a class="nav-link" href="#pelayanan" data-toggle="tab">Pelayanan Saya</a></li>
                  <li class="nav-item"><a class="nav-link" href="#bantuan" data-toggle="tab">Bantuan Saya</a></li>
                  @can('view_zakatamils')
                  <li class="nav-item"><a class="nav-link" href="#zakat" data-toggle="tab">Zakat</a></li>
                  @endcan
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="aduan">
                    @if($ajuan)
                    <a name="" id="" class="btn btn-primary m-2" href="{{url('admin/profile/aduan')}}" role="button">Ajukan Aduan</a>
                    @endif
                    @forelse ($user->aduans as $aduan)
                        <div class="post">
                          <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{url($aduan->user->photo)}}" alt="user image">
                            <span class="username">
                            <a href="#">{{$aduan->user->name}}</a>
                            </span>
                          <span class="description">Diajukan pada - {{$aduan->updated_at}}</span>
                          </div>
                          <p class="text-muted">Status: 
                            @if((bool) $aduan->status)
                              <span class="text-success">{{$aduan->getStatus($aduan->status)}}</span>
                            @else
                              <span class="text-info">{{$aduan->getStatus($aduan->status)}}</span>
                            @endif
                          </p>
                          <!-- /.user-block -->
                          <p>
                           {{$aduan->komentar}}
                          </p>
                          <div class="row">
                            <p>
                              {!! Form::open(['url' => ['admin/profile/likeaduan',$aduan->id]]) !!}
                              @if(sizeof($aduan->likes->where('user_id',auth()->user()->id)) === 0)
                                <button tipe="submit" class="btn btn-link text-sm float-left" href="#" role="button"><i class="far fa-thumbs-up mr-1"></i> Like ({{$aduan->likes->count()}})</button>
                              @else
                                <button tipe="submit" class="btn btn-link link-black text-sm float-left" href="#" role="button"><i class="far fa-thumbs-up mr-1"></i> Like ({{$aduan->likes->count()}})</button>
                              @endif
                              {!! Form::close() !!}
                            </p>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="card card-sucress cardutline direct-chat direct-chat-success collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">Komentar Aduan</h3>

                                <div class="card-tools">
                                  <span data-toggle="tooltip" title="{{$aduan->comments->count()}} New komentar" class="badge bg-success">{{$aduan->comments->count()}}</span>
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                  </button>
                                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body p-2" style="display: none;">

                                <!-- Contacts are loaded here -->
                                @foreach ($aduan->comments as $item)
                                  <div class="direct-chat-msg @if($item->user->id != auth()->user()->id) right @endif">
                                    <div class="direct-chat-infos clearfix">
                                      <span class="direct-chat-name float-right">
                                        @if($item->user->id == auth()->user()->id)
                                        {!! Form::open(['url' => 'admin/profile/komentaraduan/'.$item->id,'class'=> 'delete']) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                          <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-fw fa-trash" aria-hidden="true"></i>
                                          </button>
                                        {!! Form::close() !!}
                                        Saya
                                        @else
                                        {{$item->user->name}}
                                        @endif
                                      </span>
                                    <span class="direct-chat-timestamp float-left">{{$item->created_at}} / {{$item->updated_at}}</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="{{url($item->user->photo)}}" alt="Message User Image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                      {{$item->komentar}}
                                    </div>
                                    <!-- /.direct-chat-text -->
                                  </div>
                                  @endforeach
                                <!-- /.direct-chat-pane -->
                              </div>
                              <!-- /.card-body -->
                              <div class="card-footer" style="display: none;">
                                {!! Form::open(['url' => ['admin/profile/komentaraduan',$aduan->id]]) !!}
                                  <div class="input-group">
                                     {!! Form::text('komentar', null, ['class' => 'form-control','placeholder' => 'Tuliskan komentar']) !!}
                                    <span class="input-group-append">
                                      <button type="submit" class="btn btn-success"><i class="fa fa-paper-plane fa-fw" aria-hidden="true"></i> Kirim</button>
                                    </span>
                                  </div>
                                {!! Form::close() !!}
                              </div>
                              <!-- /.card-footer-->
                            </div>
                            </div>
                            
                          </div>
                        </div>
                    @empty
                      <div class="row">
                        <h3 class="text-center">Belum ada aduan yang diajukan</h3>
                      </div>
                    @endforelse
                    <!-- /.post -->
                  </div>
                  <div class="tab-pane" id="pelayanan">
                    @if($ajuan)
                    <a name="" id="" class="btn btn-primary m-2" href="{{url('admin/profile/pelayanan')}}" role="button">Ajukan Pelayanan</a>
                    @endif
                    <!-- Post -->
                    @forelse ($user->pelayanans as $pelayanan)
                        <div class="post">
                          <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{url($pelayanan->user->photo)}}" alt="user image">
                            <span class="username">
                            <a href="#">{{$pelayanan->user->name}}</a>
                            </span>
                          <span class="description">Diajukan pada - {{$pelayanan->updated_at}}</span>
                          </div>
                          <p class="text-muted">Status: 
                            @if((bool) $pelayanan->status)
                              <span class="text-success">{{$pelayanan->getStatus($pelayanan->status)}}</span>
                            @else
                              <span class="text-info">{{$pelayanan->getStatus($pelayanan->status)}}</span>
                            @endif
                          </p>
                          <!-- /.user-block -->
                          <p>
                           {{$pelayanan->komentar}}
                          </p>
                        </div>
                    @empty
                      <div class="row">
                        <h3 class="text-center">Belum ada pelayanan yang diajukan</h3>
                      </div>
                    @endforelse
                    <!-- /.post -->
                  </div>
                  <div class="tab-pane" id="bantuan">
                    <!-- Post -->
                    @forelse ($user->pelayanans as $pelayanan)
                        <div class="post">
                          <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{url($pelayanan->user->photo)}}" alt="user image">
                            <span class="username">
                            <a href="#">{{$pelayanan->user->name}}</a>
                            </span>
                          <span class="description">Diajukan pada - {{$pelayanan->updated_at}}</span>
                          </div>
                          <p class="text-muted">Status: 
                            @if((bool) $pelayanan->status)
                              <span class="text-success">{{$pelayanan->getStatus($pelayanan->status)}}</span>
                            @else
                              <span class="text-info">{{$pelayanan->getStatus($pelayanan->status)}}</span>
                            @endif
                          </p>
                          <!-- /.user-block -->
                          <p>
                           {{$pelayanan->komentar}}
                          </p>
                        </div>
                    @empty
                      <div class="row">
                        <h3 class="text-center">Belum ada pelayanan yang diajukan</h3>
                      </div>
                    @endforelse
                    <!-- /.post -->
                  </div>
                  @can('view_zakatamils')
                  <div class="tab-pane" id="zakat">
                    <!-- Post -->
                    @forelse ($zakats as $zakat)
                        <div class="post">
                          <div class="user-block">
                            <span class="username">
                            <a href="{{route('profile.zakat',$zakat->id)}}">{{$zakat->name}}</a>
                            </span>
                          <span class="description">Dimulai pada - {{\Carbon\Carbon::parse($zakat->start)->translatedFormat('d F Y H:i:s')}}</span>
                          <span class="description">
                            Berakhir pada - {{\Carbon\Carbon::parse($zakat->end)->translatedFormat('d F Y H:i:s') }} / {{\Carbon\Carbon::parse($zakat->end)->diffForHumans() }}
                          </span>
                          </div>
                        </div>
                    @empty
                      <div class="row">
                        <h3 class="text-center">Belum ada zakat yang dijalankan</h3>
                      </div>
                    @endforelse
                    <!-- /.post -->
                  </div>
                  @endcan
                  <!-- /.tab-pane -->
                  
                  <div class="tab-pane" id="settings">
                      {!! Form::model($user, ['url' => ['admin/profile'],'method' => 'PUT','class' => 'form-horizontal']) !!}
                      {!! Form::hidden('id') !!}
                      {!! Form::hidden('name') !!}
                      {!! Form::hidden('username') !!}
                      {!! Form::hidden('address') !!}
                      {!! Form::hidden('roles') !!}
                      <div class="form-group row">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email']) !!}
                      </div>
                      <div class="form-group row">
                        {!! Form::label('ttl', 'Tempat Tanggal Lahir') !!}
                        {!! Form::text('ttl', null, ['class' => 'form-control','placeholder' => 'Tempat Tanggal Lahir']) !!}
                      </div>
                      <div class="form-group row">
                        {!! Form::label('no_hp', 'NO HP') !!}
                        {!! Form::text('no_hp', null, ['class' => 'form-control','placeholder' => 'NO HP']) !!}
                      </div>
                      <div class="form-group row">
                       {!! Form::label('password', 'Password') !!}
                       {!! Form::input('password', 'password', null,['class' => 'form-control','placeholder' => 'Isikan untuk validasi']) !!}
                      </div>
                      <div class="form-footer pt-2 border-top">
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                      {!! Form::close() !!}
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      
    </section>
    <!-- /.content -->

@stop
@push('scripts')
@endpush