 <footer class="main-footer">
    جميع الحقوق محفوظة.
    <strong>Copyright &copy; mama store</strong>
    <div class="float-right d-none d-sm-inline-block">
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 rtl -->
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('admin/plugins/jqvmap/maps/jquery.vmap.world.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('admin/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin/js/demo.js')}}"></script>
<!-- Toastr Cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

 <!-- تضمين ملفات Summernote CSS و JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>


 <!--select2-->
 <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    // select2
    $('.select2').select2({
        theme: 'bootstrap4'
    })
     toastr.options = {
        "positionClass": "toast-top-left", // هنا نغير الموقع إلى أعلى اليسار
        "closeButton": true,
        "progressBar": true,
         "showDuration": "2000", // مدة عرض الرسالة
         "hideDuration": "1000", // مدة اختفاء الرسالة
         "timeOut": "4000", // مدة عرض الرسالة قبل الاختفاء (بالملي ثانية)
    };




    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");

    @endif


    $('#inputInfo,#inputDescription,#popup-text').summernote({
        placeholder: 'ادخل تفاصيل او معلومات المنتج هنا ',
        tabsize: 2,
        height: 110, // تعيين ارتفاع المحرر
        fontSizes: ['12', '14', '16', '18', '20', '22', '24','36' ,'48','72'], // إضافة 16 بكسل

        toolbar: [
            // تقسيم الأدوات إلى مجموعات
            ['font', ['bold', 'italic', 'underline', 'clear']], // إضافة تنسيق النص
            ['fontsize', ['fontsize']], // تغيير حجم الخط
            ['color', ['forecolor']], // تغيير لون النص والخلفية
            ['para', ['ul', 'ol', 'paragraph']], // خيارات الفقرات والقوائم
            ['style', ['style']], // إضافة خيارات نمط النص
            ['height', ['height']], // تغيير ارتفاع السطر
            ['insert', ['link']], // إدراج روابط وصور وفيديوهات
            ['view', ['fullscreen']] // عرض كود HTML وخيارات إضافية
        ],
        lang: 'ar-AR', // دعم اللغة العربية
        direction: 'rtl', // دعم الكتابة من اليمين لليسار
        callbacks: {
            onInit: function() {
                $('.note-editable p').css('margin-bottom', '12px');
            }
        }
    });
</script>
    @stack('scripts')
</body>
</html>
