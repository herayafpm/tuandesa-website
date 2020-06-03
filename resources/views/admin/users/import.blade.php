@extends('admin.layout')
@section('headTitle','Import User')
@section('content')
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Import User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/users')}}">Users</a></li>
              <li class="breadcrumb-item active">Import User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      @include('admin.partials.flash',['$errors' => $errors])
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row mb-2">
                {!! Form::open(['url' => 'admin/users/import/', 'method' => "POST", 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                  {!! Form::label('import', 'Import User') !!}
                  {!! Form::file('file', ['class' => 'form-control-file','placeholder' => 'Import User']) !!}
                  <p class="text-muted">
                    * yang diperbolehkan adalah xls <br>
                    * jika setelah muncul error namun tidak semua baris artinya baris yang lainnya sudah terimport dengan benar
                  </p>
                </div>
                <div class="form-footer pt-2 border-top">
                  <button type="submit" class="btn btn-primary">Import</button>
                </div>
                {!! Form::close() !!}
              </div>
              <div class="row mb-1">
                <p class="text-muted">
                  * dibawah ini aturan yang harus ada di excel dan struktur table di excel
                </p>
              </div>
              <div class="row">
                <ul>
                  <li>no (pastikan ada isinya, walaupun nanti tidak digunakkan)</li>
                  <li>nama (tidak ada aturan)</li>
                  <li>username (pastikan tidak ada yang sama satu sama lain, jika sama akan ada pesan error)</li>
                  <li>email (pastikan tidak ada yang sama satu sama lain, jika sama akan ada pesan error)</li>
                  <li>ttl (pastikan diisi, tidak boleh kosong)</li>
                  <li>alamat (pastikan diisi, tidak boleh kosong)</li>
                  <li>no hp (pastikan diisi, tidak boleh kosong)</li>
                </ul>
              </div>
              <div class="row">
                <table class="table">
                  <thead>
                    <tr>
                      <th>no</th>
                      <th>nama</th>
                      <th>username</th>
                      <th>email</th>
                      <th>ttl</th>
                      <th>alamat</th>
                      <th>no_hp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td scope="row">1</td>
                      <td>Nama saya</td>
                      <td>NIK saya</td>
                      <td>email saya</td>
                      <td>tempat lahir saya</td>
                      <td>alamat saya</td>
                      <td>no_hp saya</td>
                    </tr>
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