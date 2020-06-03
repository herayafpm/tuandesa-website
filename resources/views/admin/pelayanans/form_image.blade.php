@extends('admin.layout')
@section('headTitle',__('admin.datapelayanans').' Image')
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Image Pelayanan</h1>
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
              @include('admin.partials.flash',['$errors' => $errors])
              {!! Form::open(['url' => 'admin/pelayanans/images/'.$pelayananId, 'method' => "POST", 'enctype' => 'multipart/form-data']) !!}
              <div class="form-group">
                {!! Form::label('image', 'Pelayanan Image') !!}
                {!! Form::file('image[]', ['class' => 'form-control-file','placeholder' => 'Pelayanan Image','multiple' => true]) !!}
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              <a name="" id="" class="btn btn-secondary" href="{{url('admin/pelayanans/'.$pelayananId.'/edit')}}" role="button">Back</a>
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