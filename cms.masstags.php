<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 10;
$tab = 3;

if ($_GET['letter']) {
  $letter = substr($_GET['letter'], 0, 1);
  $sql = " WHERE title LIKE '$letter%'";
} elseif ($_GET['all']) {
  $sql = " ";
}
if ($sql) {
  $list = $db->query("SELECT * FROM papers $sql ORDER BY title");
}

$taglist = $db->query("SELECT tags_papers.* 
FROM tags_papers
JOIN tags ON tags_papers.tag = tags.id
ORDER BY tag
");
foreach ($taglist as $row) {
  $tag[$row['paper']][$row['tag']] = true;
}
$tags = $db->query("SELECT * FROM tags ORDER BY tag");
$letters = range("A", "Z");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Mass tag review | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <style type="text/css">
      #error {position:fixed;top:100px;display:none}
      .fa-spin{margin-left:10px}
      .results{color:green}
      .saving{font-weight:bold;color:black}
    </style>
    <script type="text/javascript" src="js/select2.min.js"></script>
    <script type="text/javascript">
    $(function(){
      $("select").select2();
      $("select").change(function(){
        select = $(this);
        tags = $(this).val();
        console.log(tags);
        var id = $(this).data("id");  
        var success = $("#results-"+id); 
        success.append('<span class="saving"><i class="fa fa-spin fa-spinner"></i> Saving...</span>');
        $.post("cms.tag.php",{
          id: $(this).data("id"),
          action: "synctags",
          tags: tags,
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            console.log("All good");
            success.html(data.count+" tags saved");
            $("#error").html('');
            $("#error").hide();
          } else {
            success.html('');
            console.log("Error, response: " + data.response);
            $("#error").show();
            if (data.error) {
              $("#error").html(data.error);
            } else {
              $("#error").html("There was an error. The data could be saved.");
            }
          }
        },'json')
        .error(function(){
          success.html('');
          $("#error").html("Could not send data.");
          $("#error").show();
          console.log("Problem sending data");
        });
      });
    });
    </script>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <div id="error" class="alert alert-danger"></div>

  <h1>Mass tag review</h1>

  <?php require_once 'include.cmstags.php'; ?> 

  <div class="alert alert-info">
    <?php echo count($list) ?> records found
  </div>

  <div class="buttongroup">
    <a class="btn btn-<?php echo $_GET['letter'] || !$_GET['all'] ? 'default' : 'primary'; ?>" href="cms/masstags/all">All</a>
    <?php foreach ($letters as $letter) { ?>
      <a class="btn btn-<?php echo $letter == $_GET['letter'] ? 'primary' : 'default'; ?>"
      href="cms.masstags.php?letter=<?php echo $letter ?>">
      <?php echo $letter ?>
      </a>
    <?php } ?>
  </div>

  <?php if (count($list)) { ?>

  <table class="table table-striped">
    <tr>
      <th width="400">Title</th>
      <th>Tags</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td width="400"><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td>
          <select name="name" class="form-control" multiple data-id="<?php echo $row['id'] ?>">
            <?php foreach ($tags as $subrow) { ?>
              <option value="<?php echo $subrow['id'] ?>"<?php if ($subrow['id'] == $tag[$row['id']][$subrow['id']]) { echo ' selected'; } ?>><?php echo $subrow['tag'] ?></option>
            <?php } ?>
          </select>
          <span class="results" id="results-<?php echo $row['id'] ?>"></span>
      </td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
