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
    <link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/apex-charts/apex-charts.css" /> 
    <!-- Page CSS -->     
    {{-- Datatable CSS --}}
    <style type="text/css">
      table tbody tr td span.role_name {
         background-color: gray;
         padding: 3px;
         margin-right: 2px;
         font-size: 12px;
         color: #FFFFFF;
      }
      table thead tr th { text-align: center; }
      table tbody tr td { text-align: center; }
      #dttable {
         margin: 0 0px;
         clear: both;         
         table-layout: fixed;
         font-size: 12px;
      }
      .dttable_width{
        width: auto;
      }
      @media screen {
         table.dataTable tbody tr.header_top td {
            /*display: none; */
            text-align: left;
            border: none;
         }
      }
      table.dataTable thead .sorting::after { display: none; }
      table.dataTable thead .sorting_asc::after { display: none; }
      .table-responsive { min-height: 0.01%; overflow-x: auto; }
      #printable { display: none; }
      #print_image { display: none; }
   </style>
   <style>
    .table-container {
        max-width: 100%;
        margin: 0 auto;
        overflow-x: auto;
    }
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        font-family: sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        width: 100%;    
        display: block;
        overflow-x: auto;
        white-space: nowrap; 
        line-height: 10px;          
    }
    .styled-table thead tr {
        background-color: #EE9321;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th{
        font-weight: 700;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }
    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }
    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #EE9321;
    }
    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #EE9321;
    }  
    .my-button-10 {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 6px 14px;
      font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
      border-radius: 6px;
      border: none;
      color: #fff;
      background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
      background-origin: border-box;
      box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
    }
    .my-button-10:focus {
      box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 
                        0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 
                        0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
      outline: 0;
    } 
    .my-button-11 {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 6px 14px;
      font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
      border-radius: 6px;
      border: none;
      color: #fff;
      /* background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%); */
      background: linear-gradient(180deg, red 45%, red 55%, red);
      background-origin: border-box;
      box-shadow: 0px 0.5px 1.5px rgba(255, 255, 0, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 0, 0.2);
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
    }
    .my-button-11:focus {
      box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 0, 0.2), 
                        0px 0.5px 1.5px rgba(255, 255, 0, 0.25), 
                        0px 0px 0px 3.5px rgba(252, 3, 3, 0.5);
      outline: 0;
    } 
    .button-container {
        display: flex;
        gap: 2px; /* Space between buttons */
    }         
   </style>
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">    
    <script>
      // document.addEventListener('contextmenu', event => event.preventDefault());
      var rootURL = '{{ URL::to("/") }}';
    </script>
    <!-- Helpers -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/config.js"></script>
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

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ URL('/') }}/sneat-bootstrap5-theme/assets/js/dashboards-analytics.js"></script>

    {{-- Datatable JS --}} 
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.72/pdfmake.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.72/vfs_fonts.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.6.1/jszip.min.js" defer></script> -->     
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/jquery.dataTables.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/dataTables.buttons.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/buttons.html5.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/buttons.flash.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/buttons.print.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/pdfmake.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/vfs_fonts.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable') }}/jszip.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/datatable-custome.js') }}" defer></script>
    <!-- Jquery validation -->
    <script type="text/javascript" src="{{ asset('custom-js/jquery-validation/dist/jquery.validate.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('custom-js/jquery.validate.scrip.js') }}" defer></script>    
    @yield('footerjs')
  </body>
</html>