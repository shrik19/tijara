</section>

</div>
      <footer class="main-footer">
        <div class="footer-left">
          {{ __('lang.copyright_label')}} &copy; {{date('Y')}} - {{config('constants.PROJECT_NAME')}}</a>
        </div>
        <!-- <div class="footer-right">
          2.3.0
        </div> -->
      </footer>
    </div>
  </div>
<script type="text/javascript">
  /*translation*/
  var input_letter_no_err="{{ __('errors.input_letter_no_err')}}";
  var category_name_req="{{ __('errors.category_name_req')}}";
</script>
  <script src="{{url('/')}}/assets/admin/js/prism.js"></script>
  
  <!-- Template JS File -->
  <script src="{{url('/')}}/assets/admin/js/scripts.js"></script>
  <script src="{{url('/')}}/assets/admin/js/bootstrap-modal.js"></script>
  <script src="{{url('/')}}/assets/admin/js/custom.js"></script>
  
</body>
</html>