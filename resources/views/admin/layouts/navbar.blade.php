<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-sm-left">
          <a href="#" class="dropdown-item">
               عرض الملف الشخصي
          </a>
          <a href="#" class="dropdown-item" onclick="document.getElementById('postForm').submit(); return false;">
                   <form id= 'postForm' method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            تسجيل خروج

                    </form>
          </a>
        </div>
      </li>

    </ul>



  </nav>
