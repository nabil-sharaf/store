<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link ">
      <img src="{{asset('front/assets/img/logo.png')}}" alt="mama store Logo" class="mama-logo  elevation-3">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image mr-2">
          <img src="{{asset('admin/img/avatar04.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview {{ Request::is('admin/customers*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p class='font-weight-bold'>
                لوحة التحكم
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.settings.edit')}}" class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>اعدادات الموقع</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ Request::is('admin/customers*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p class='font-weight-bold'>
                العملاء
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.customers.index')}}" class="nav-link {{ Request::is('admin/customers/index*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>العملاء</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ Request::is('admin/categories*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p class='font-weight-bold'>
                الأقسام
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::is('admin/categories') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>كل الأقسام</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.categories.create') }}" class="nav-link {{ Request::is('admin/categories/create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>إضافة قسم</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ Request::is('admin/products*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p class='font-weight-bold'>
                المنتجات
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ Request::is('admin/products') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>كل المنتجات</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.products.create') }}" class="nav-link {{ Request::is('admin/products/create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>إضافة منتج</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ Request::is('admin/orders*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p class='font-weight-bold'>
                الأوردرات
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Request::is('admin/orders') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>كل الأوردرات</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.orders.create') }}" class="nav-link {{ Request::is('admin/orders/create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>إضافة أوردر</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ Request::is('admin/promo-codes*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/promo-codes*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p class='font-weight-bold'>
                البرومو كودز
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.promo-codes.index') }}" class="nav-link {{ Request::is('admin/promo-codes') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>كل البرومو</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.promo-codes.create') }}" class="nav-link {{ Request::is('admin/promo-codes/create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>إضافة برومو كود</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ Request::is('admin/reports*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p class='font-weight-bold'>
               التقارير
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Request::is('admin/reports') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>تقارير المبيعات</p>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
