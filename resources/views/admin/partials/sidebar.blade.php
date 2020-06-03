<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="{{URL::asset('vendor/adminlte/img/AdminLTELogo.png')}}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
     <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{URL::asset(auth()->user()->photo)}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        <p class="text-muted">{{auth()->user()->getRoleNames()[0]}}</p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" id="sidebarApp" data-widget="treeview" role="menu" data-accordion="true">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
                <a href="{{url('admin/dashboard')}}" class="nav-link {{(Request::segment(2) == 'dashboard' || Request::segment(2) == null)?'active':''}}">
                <i class="fa fa-tachometer-alt nav-icon"></i>
                <p>{{__('admin.dashboard')}}</p>
            </a>
          </li>
          <li class="nav-item">
                <a href="{{url('admin/profile')}}" class="nav-link {{(Request::segment(2) == 'profile')?'active':''}}">
                <i class="far fa-user nav-icon"></i>
                <p>{{__('admin.profile')}}</p>
            </a>
          </li>
          <li class="nav-item">
              <a href="#" id="logout" class="nav-link" role="button" onclick="logout()">
                <i class="fa fa-power-off nav-icon"></i>
                <p>{{__('admin.logout')}}</p>
              </a>
              {!! Form::open(['url' => 'logout','class' => 'd-none','id' => 'formlogout']) !!}
              {!! Form::close() !!}
          </li>
            @can('view_profiledesas')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  {{__('admin.datamaster')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>                
              <ul class="nav nav-treeview">
                @can('view_users')
                <li class="nav-item">
                      <a href="{{url('admin/users')}}" class="nav-link {{(Request::segment(2) == 'users')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.datausers')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_roles')
                <li class="nav-item">
                      <a href="{{url('admin/roles')}}" class="nav-link {{(Request::segment(2) == 'roles')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.dataroles')}}</p>
                  </a>
                </li>
                 @endcan
                @can('view_profiledesas')
                <li class="nav-item">
                      <a href="{{url('admin/profiledesas')}}" class="nav-link {{(Request::segment(2) == 'profiledesas')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.dataprofiledesas')}}</p>
                  </a>
                </li>
                 @endcan
              </ul>
              
            </li>
            @endcan
            @can('view_aduans')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                  {{__('admin.aduans')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view_aduans')
                <li class="nav-item">
                      <a href="{{url('admin/aduans')}}" class="nav-link {{(Request::segment(2) == 'aduans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.dataaduans')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_jenisaduans')
                <li class="nav-item">
                      <a href="{{url('admin/jenisaduans')}}" class="nav-link {{(Request::segment(2) == 'jenisaduans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.jenisaduans')}}</p>
                  </a>
                </li>
                  @endcan
              </ul>
            </li>
            @endcan
            @can('view_beritas')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas  fa-circle"></i>
                <p>
                  {{__('admin.beritas')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view_beritas')
                <li class="nav-item">
                      <a href="{{url('admin/beritas')}}" class="nav-link {{(Request::segment(2) == 'beritas')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.databeritas')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_jenisberitas')
                <li class="nav-item">
                      <a href="{{url('admin/jenisberitas')}}" class="nav-link {{(Request::segment(2) == 'jenisberitas')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.jenisberitas')}}</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('view_pelayanans')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas  fa-circle"></i>
                <p>
                  {{__('admin.pelayanans')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view_pelayanans')
                <li class="nav-item">
                      <a href="{{url('admin/pelayanans')}}" class="nav-link {{(Request::segment(2) == 'pelayanans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.datapelayanans')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_jenispelayanans')
                <li class="nav-item">
                      <a href="{{url('admin/jenispelayanans')}}" class="nav-link {{(Request::segment(2) == 'jenispelayanans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.jenispelayanans')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_laporanpelayanans')
                <li class="nav-item">
                      <a href="{{url('admin/laporanpelayanan')}}" class="nav-link {{(Request::segment(2) == 'laporanpelayanan')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.laporanpelayanan')}}</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('view_bantuans')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas  fa-circle"></i>
                <p>
                  {{__('admin.bantuans')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view_bantuans')
                <li class="nav-item">
                      <a href="{{url('admin/bantuans')}}" class="nav-link {{(Request::segment(2) == 'bantuans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.databantuans')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_jenisbantuans')
                <li class="nav-item">
                      <a href="{{url('admin/jenisbantuans')}}" class="nav-link {{(Request::segment(2) == 'jenisbantuans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.jenisbantuans')}}</p>
                  </a>
                </li>
                @endcan
                @can('view_soaljawabans')
                <li class="nav-item">
                      <a href="{{url('admin/soaljawabans')}}" class="nav-link {{(Request::segment(2) == 'soaljawabans')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.soaljawabans')}}</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('view_zakats')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas  fa-circle"></i>
                <p>
                  {{__('admin.zakats')}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view_zakats')
                <li class="nav-item">
                      <a href="{{url('admin/zakats')}}" class="nav-link {{(Request::segment(2) == 'zakats')?'active':''}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('admin.datazakats')}}</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>