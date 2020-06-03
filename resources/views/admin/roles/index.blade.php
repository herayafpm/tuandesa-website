@extends('admin.layout')
@section('headTitle',__('admin.dataroles'))
@section('content')
    <div class="row m-2">
      @include('flash::message')
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card p-2">
            <div class="card-header">
              @can('add_roles')
            <div><a href="{{route('roles.create')}}" class="btn btn-sm btn-success pull-right"> <i class="fa fa-fw fa-plus"></i> New</a></div>
              @endcan
            </div>
            <div class="card-body table-responsive p-0">
              @forelse ($roles as $role)
                  {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b']) !!}

                  @if($role->name === 'Super Admin')
                      @include('admin.shared._permissions', [
                                    'id' => $role->id,
                                    'title' => $role->name .' Permissions',
                                    'options' => ['disabled'] ])
                  @else
                      @include('admin.shared._permissions', [
                                    'id' => $role->id,
                                    'title' => $role->name .' Permissions',
                                    'model' => $role ])
                  @endif

                  {!! Form::close() !!}

              @empty
                  <p>No Roles defined, please run <code>php artisan db:seed</code> to seed some dummy data.</p>
              @endforelse
            </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
      <!-- /.card -->

   
@endsection