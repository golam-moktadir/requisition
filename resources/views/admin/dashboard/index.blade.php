<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ URL('/') }}/sneat-bootstrap5-theme/assets/" 
  data-template="vertical-menu-template">
  <head>

  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME') }} Dashboard</title>

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

        @include('admin.layouts.nav-top')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Welcome to {{ env('APP_NAME') }} Dashboard</h5>                          
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 mb-1">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                                <img src="{{ asset('custom-assets/img/avatar2.png') }}" alt="employee" class="rounded">
                              </div>
                            </div>
                            <span class="fw-medium d-block mb-1">Employee</span>
                            <h3 class="card-title mb-2">{{ $employees }}</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-1">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                              <img src="{{ asset('custom-assets/img/avatar2.png') }}" alt="users" class="rounded">
                              </div>
                            </div>
                            <span>Users</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $users }}</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-1">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                              <img src="{{ asset('custom-assets/img/avatar2.png') }}" alt="customers" class="rounded">
                              </div>
                            </div>
                            <span>Customers</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $customers }}</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-1">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat-bootstrap5-theme/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded">
                              </div>                              
                            </div>
                            <span>Petty Cash</span>
                            <h3 class="card-title text-nowrap mb-1">TK {{ $petty_cash }}</h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
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

    <script>
      function roundToDecimal(num, decimalPlaces) {
          var factor = Math.pow(10, decimalPlaces);
          return Math.round(num * factor) / factor;
      }   
      function getHeight(){
          var value = parseInt($("#hieght").val());
          if(isNaN(value)){
              value = 0;
          }        
          return value;
      }   
      function getWidth(){
          var value = parseInt($("#width").val());
          if(isNaN(value)){
              value = 0;
          }        
          return value;
      }   
      function getPrice(){
          var value = parseInt($("#price").val());
          if(isNaN(value)){
              value = 0;
          }        
          return value;
      }   
      function getSheets(){
          var value = parseInt($("#number_of_sheets").val());
          if(isNaN(value)){
              value = 0;
          }        
          return value;
      } 
      function calculatSquareFeet(height, width){
          var amount = roundToDecimal((height/12)*(width/12), 2);
          $("#totalSquareFeet").text(amount);
          var value2 = parseInt($("#price").val());
          if(isNaN(value2)){
              value2 = 0;
          }         
          $("#totalPrice").text(roundToDecimal(amount*value2*getSheets(), 2));
      }
      $(document).ready(function() {   
          $("#hieght").keyup(function(event) {
              calculatSquareFeet(getHeight(), getWidth());
          });
          $("#width").keyup(function(event) {
              calculatSquareFeet(getHeight(), getWidth());
          });  
          $("#price").keyup(function(event) {
              calculatSquareFeet(getHeight(), getWidth());          
          });   
          $("#number_of_sheets").keyup(function(event) {
              calculatSquareFeet(getHeight(), getWidth());          
          });    
      });    
    </script>  

  </body>
</html>
