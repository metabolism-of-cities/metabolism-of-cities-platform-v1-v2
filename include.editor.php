  <script src='js/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#content',
      height: <?php echo $tinymce_height ?: 500; ?>,
      <?php if ($absolute_urls) { ?>
      relative_urls : false,
      remove_script_host : false,
      document_base_url : "<?php echo URL ?>",
      <?php } ?>
      menubar: false,
      paste_data_images: true,
      images_upload_url: 'upload.php',
      images_upload_base_path: '<?php echo URL ?>',
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
      content_css: [
        '<?php echo URL ?>css/bootstrap.min.css'<?php if ($tinymce_css) { echo ", " . $tinymce_css; } ?>,
        '<?php echo URL ?>css/editor.css',
      ],
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload').trigger('click');
        $('#upload').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    }
    });
  </script>

