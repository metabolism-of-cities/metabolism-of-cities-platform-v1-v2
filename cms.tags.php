<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 10;
$tab = 1;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $count = $db->query("SELECT * FROM tags_papers WHERE tag = $delete");
  if (count($count)) {
    die("Sorry, you can only delete a tag if there are no papers associated with it");
  }
  $db->query("DELETE FROM tags WHERE id = $delete LIMIT 1");
  $print = "Tag was deleted";
}

if ($_GET['parent']) {
  $parent = (int)$_GET['parent'];
  $sql = "WHERE tags.parent = $parent";
}

$list = $db->query("SELECT tags.*, tags_parents.name AS parentname 
FROM tags JOIN tags_parents ON tags.parent = tags_parents.id 
$sql
ORDER BY tags.tag");

$i = 1;

$parents = $db->query("SELECT * FROM tags_parents ORDER BY name");

$alltags = $db->query("SELECT * FROM tags ORDER BY tag");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Tags | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <style type="text/css">
      table {border:1px solid #ccc; width:100px;table-layout: fixed;}
      th, td { max-width:100px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
      #error {position:fixed;top:100px;display:none}
      input.greyout{background:#ccc !important}
      .alert-success{margin-top:11px}
      .modal-dialog {margin-top:180px}
      .table-striped>tbody>tr:nth-child(odd)>td.alert-success {
        background:#dff0d8;
      }
    </style>
    <script type="text/javascript" src="js/select2.min.js"></script>
    <script type="text/javascript">
    $(function(){

      var mergebutton;
      var mergename;
      $(".select2").select2({ width: '100%' });
      var counter = <?php echo count($list) ?>;

      $("a.merge").click(function(){ 
        var id = $(this).data("id");
        $("#oldtag").val(id);
        var name = $(this).data("name");
        mergename = name;
        $(".mergename").html(name);
        $("#mergetag").html("Save");
        $("#mergetag").removeClass("btn-success").addClass("btn-primary");
        mergebutton = $(this);
      });

      $("#mergetag").click(function(e){
        e.preventDefault();
        var newtag = $("#newtag").val();
        var oldtag = $("#oldtag").val();
        if (newtag == "none") {
          alert("Error. You did not select a new tag");
        } else {
          console.log("Merging " + oldtag + " into " + newtag);
          $("#mergetag").append('<i style="margin-left:15px" class="fa fa-spin fa-spinner"></i>');
          $.post("cms.tag.php",{
            oldtag: oldtag,
            newtag: newtag,
            action: "merge",
            dataType: "json"
          }, function(data) {
            if (data.response == "OK") {
              $("#mergetag").removeClass("btn-primary").addClass("btn-success");
              $(".close").click();
              $("#error").html();
              tr = $(mergebutton).closest("tr");
              tr.html('');
              tr.append('<td colspan="3" class="alert alert-success">' + mergename + ' was merged with ' + data.newtag + '</td>'); 
              counter = counter - 1;
              $("#counter").html(counter);
            } else {
              $("#mergetag").removeClass("btn-primary").addClass("btn-danger").html("Error, tags not merged. Refresh and try again");
            }
          },'json')
          .error(function(){
            $("#mergetag").removeClass("btn-primary").addClass("btn-danger").html("Error, tags not merged. Refresh and try again");
          });
        }
      });

      $("select.parents").change(function(){
        var input = $(this);
        input.addClass("greyout");
        var success = $(input).next(".results"); 
        success.append('<i class="fa fa-spin fa-spinner"></i>');
        $.post("cms.tag.php",{
          id: $(this).data("id"),
          action: "updateparent",
          parent: $(this).val(),
          dataType: "json"
        }, function(data) {
          input.removeClass("greyout");
          if (data.response == "OK") {
            console.log("All good");
            $(input).closest("td").removeClass("has-error").addClass('has-success'); 
            success.html("Saved on "+data.time);
            $("#error").html('');
            $("#error").hide();
          } else {
            success.html('');
            $(input).closest("td").removeClass("has-success").addClass('has-error'); 
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
          $(input).closest("td").removeClass("has-success").addClass('has-error'); 
          $("#error").html("Could not send data.");
          $("#error").show();
          console.log("Problem sending data");
        });
      });

      $("input.name").change(function(){
        var input = $(this);
        input.addClass("greyout");
        var success = $(input).next(".results"); 
        success.append('<i class="fa fa-spin fa-spinner"></i>');
        $.post("cms.tag.php",{
          id: $(this).data("id"),
          action: "update",
          name: $(this).val(),
          dataType: "json"
        }, function(data) {
          input.removeClass("greyout");
          if (data.response == "OK") {
            console.log("All good");
            $(input).closest("td").removeClass("has-error").addClass('has-success'); 
            success.html("Saved on "+data.time);
            $("#error").html('');
            $("#error").hide();
          } else {
            success.html('');
            $(input).closest("td").removeClass("has-success").addClass('has-error'); 
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
          $(input).closest("td").removeClass("has-success").addClass('has-error'); 
          $("#error").html("Could not send data.");
          $("#error").show();
          console.log("Problem sending data");
        });
      });

      $("button.btn-danger").click(function(e){
        var button = $(this);
        $.post("cms.tag.php",{
          id: $(this).data("id"),
          action: "delete",
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $(button).closest("td").addClass('has-success'); 
            console.log("All good");
            $(button).closest("tr").hide('fast'); 
            $("#error").html();
            $("#error").hide();
            counter = counter - 1;
            $("#counter").html(counter);
          } else {
            $(button).closest("td").addClass('has-error'); 
            console.log("Error, response: " + data.response);
            $("#error").show();
            $("#error").html("There was an error. The data could be saved.");
          }
        },'json')
        .error(function(){
          $(button).closest("td").addClass('has-error'); 
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

  <h1>Tags</h1>

  <?php require_once 'include.cmstags.php'; ?> 

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-success"><span id="counter"><?php echo count($list) ?></span> tags found</div>

  <div id="error" class="alert alert-danger"></div>

  <ul class="nav nav-tabs">
    <li class="<?php echo $_GET['parent'] ? 'reg' : 'active'; ?>"><a href="cms/tags">All</a></li>
    <?php foreach ($parents as $row) { ?>
      <li class="<?php echo $row['id'] == $_GET['parent'] ? 'active' : 'reg'; ?>"><a href="cms.tags.php?parent=<?php echo $row['id'] ?>"><?php echo smartcut($row['name'], 15) ?></a></li>
    <?php } ?>
  </ul>
  <table class="table table-striped">
    <tr>
      <th>Name</th>
      <th>Header</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td>
        <input type="text" tabindex="<?php echo $i++ ?>" value="<?php echo $row['tag'] ?>" data-id="<?php echo $row['id']; ?>" class="form-control name" />
        <span class="results"></span>
      </td>
      <td>
        <select class="parents form-control" data-id="<?php echo $row['id'] ?>">
          <?php foreach ($parents as $subrow) { ?>
            <option value="<?php echo $subrow['id'] ?>"<?php if ($subrow['id'] == $row['parent']) { echo ' selected'; } ?>><?php echo $subrow['name'] ?></option>
          <?php } ?>
        </select>
        <span class="results"></span>
      </td>
      <td>
        <a href="tags/<?php echo $row['id'] ?>/edit" class="btn btn-default">Publications</a>
        <button class="btn btn-danger" data-id="<?php echo $row['id'] ?>">Delete</button>
        <a href="javascript:void()" class="btn btn-warning merge" title="Merge this into another tag" data-toggle="modal" data-id="<?php echo $row['id'] ?>" 
        data-name="<?php echo $row['tag'] ?>"
        data-target="#myModal">
        <i class="fa fa-arrow-circle-right"></i>
        </a>
      </td>
    </tr>
  <?php } ?>
  </table>

  <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Merge this tag</h4>
        </div>
        <div class="modal-body">
          <p>Use this form to merge the tag <strong class="mergename"></strong> into another tag.
          Note: the tag <strong class="mergename"></strong> will cease to exist.
          </p>
          <div class="form-group">
            <select name="name" class="form-control select2" id="newtag">
              <option value="none">Select a new tag</option>
              <?php foreach ($alltags as $row) { ?>
                <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->name) { echo ' selected'; } ?>><?php echo $row['tag'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="hidden" id="oldtag" value="" />
          <button type="button" class="btn btn-primary" id="mergetag">Save</button>
        </div>
      </div>
    </div>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
