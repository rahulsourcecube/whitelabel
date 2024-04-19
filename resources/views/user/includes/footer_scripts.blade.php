<!-- Core Vendors JS -->
<script src="{{asset('assets/js/vendors.min.js')}}"></script>
<script src="{{ asset('assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('assets/vendors/chartjs/Chart.min.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard-default.js')}}"></script>
<!-- Core JS -->
<script src="{{asset('assets/js/app.min.js')}}"></script>

 {{-- DatePicker JS s--}}
 <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

 <script>
    $('.datepicker-input').datepicker();
 </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"
integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@yield('js')

   


