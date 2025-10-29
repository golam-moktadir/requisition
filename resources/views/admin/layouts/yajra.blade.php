<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ URL('/') }}/sneat-bootstrap5-theme/assets/" 
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME') }} {{ isset($title) && $title!="" ? "::. $title" : "" }} {{ isset($title_sub) && $title_sub!='' ? "::. $title_sub" : "" }}</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/datapicker/flatpickr.min.css">
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/apex-charts/apex-charts.css" /> 
    <!-- Page CSS -->     
    @yield('pagecss')
    <script>
      // document.addEventListener('contextmenu', event => event.preventDefault());
      var rootURL = '{{ URL::to("/") }}';
    </script>
    <!-- Helpers -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/config.js"></script>
    @vite(['resources/sass/datatable.scss'])
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
        @include('admin.layouts.aside')

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          @include('admin.layouts.nav-top')

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-1">
                {{ $title }}
              </h4>
              <!-- Custom style1 Breadcrumb -->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                  <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                  </li>
                  @yield('breadcrumbs')
                </ol>
              </nav>
              <!--/ Custom style1 Breadcrumb -->
              <div class="row">                
                @yield('content-body')                
              </div>              
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by ThemeSelection
                </div>
                <div class="d-none d-lg-inline-block">
                  <!-- <a href="#" class="footer-link me-4" target="_self">License</a> -->
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/menu.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/datapicker/flatpickr.min.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/main.js"></script>

    @vite(['resources/js/datatable.js'])  
    @yield('footerjs')
  </body>
  <script type="text/javascript">
   $(document).ready(function(){
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
   });
</script>
</html>