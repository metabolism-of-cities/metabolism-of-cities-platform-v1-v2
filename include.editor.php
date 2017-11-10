    <link href="css/summernote.css" rel="stylesheet">

<script src="js/editor.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshim.setOptions("forms-ext", {
    "widgets": {
      "startView": 2,
      "openOnFocus": true
    }
  });
  webshims.polyfill('forms forms-ext');
</script>

<script type="text/javascript">
$(function(){
  $('#summernote').summernote();
  $("form").submit(function() {
    $('textarea[name="content"]').html($('#summernote').summernote('code'));
  });
});
</script>

