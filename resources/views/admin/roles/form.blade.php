@extends('admin.layout')
@section('headTitle',__('admin.dataroles'))
@section('content')
    <div class="row m-2">
      @include('flash::message')
      <div class="col-12">
        <div class="card p-2">
            <div class="card-header">
              Add new Roles
            </div>
            <div class="card-body table-responsive p-0">
              {!! Form::open(['url' => route('roles.store'),'method' => 'post']) !!}
              <div class="form-group @if ($errors->has('name')) has-error @endif">
                  {!! Form::label('name', 'Name') !!}
                  {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Role Name']) !!}
                  @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
              </div>
                <a class="btn btn-secondary" href="{{route('roles.index')}}" role="button">Back</a>
              {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        <!-- /.card -->
      </div>
    </div>
      <!-- /.card -->

   
@endsection