<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ URL('/') }}/sneat-bootstrap5-theme/assets/"
  data-template="vertical-menu-template" >
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ env('APP_NAME') }}</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->

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
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{ URL('/') }}/login" class="app-brand-link gap-2">
                    <h3>{{ env('APP_NAME') }}</h3>
                </a>
              </div>

              @yield('content')

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/main.js"></script>

    <!-- Page JS -->
  </body>
</html>
