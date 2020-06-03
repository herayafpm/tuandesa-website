@extends('admin.layout')
@section('headTitle',__('admin.dataaduans').' Image')
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Image Aduan</h1>
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
              {!! Form::open(['url' => 'admin/aduans/images/'.$aduanId, 'method' => "POST", 'enctype' => 'multipart/form-data']) !!}
              <div class="form-group">
                {!! Form::label('image', 'Aduan Image') !!}
                {!! Form::file('image[]', ['class' => 'form-control-file','placeholder' => 'Aduan Image','multiple' => true]) !!}
              </div>
              <div class="form-footer pt-2 border-top">
                <button type="submit" class="btn btn-primary">Save</button>
              <a name="" id="" class="btn btn-secondary" href="{{url('admin/aduans/'.$aduanId.'/edit')}}" role="button">Back</a>
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