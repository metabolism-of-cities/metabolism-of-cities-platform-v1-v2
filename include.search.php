<h1>Search our database</h1>

<?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

<p>
  Please enter search terms below to start your search. You will see a list of common terms
  appear as you start typing. To confirm each search term, please hit ENTER to add it to 
  the list. The system will only show records that contain <strong>all</strong> of the 
  search terms that you enter. Remove search terms to increase the number of results.
  When you see the results, use the filters on the left hand side to refine your results.
</p>

<div class="resultbox">

  <form method="get" class="form form-horizontal" action="publications/results">

    <h4>
      <i class="glyphicon glyphicon-filter"></i>
      Search: 
    </h4>

    <?php if ($alias) { ?>
    <p>
      Your search term <em>household waste</em>
      is considered an alias for <em>end-of-life waste</em>
    </p>
    <?php } ?>

    <div class="row">
      <div class="col">
        <select name="search[]" class="form-control" id="searchbox" multiple>
          <option value=""></option>
          <?php foreach ($all_tags as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($tags_selected[$row['id']]) { echo ' selected'; } ?>><?php echo $row['tag'] ?></option>
          <?php } ?>
          <?php if (is_array($additional_keywords)) { ?>
            <?php foreach ($additional_keywords as $key => $value) { ?>
              <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
            <?php } ?>
          <?php } ?>
            <option value="">Mouse (aliases: Rat, Field Mouse, Ferret)</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
      <div class="col-9">
        <p class="pull-right">
            <?php if ($results_page) { ?>
          <a href="publications/results">Reset all filters</a> |
            <?php } ?>
          <a href="publications/all">View all publications</a>
        </p>
      </div>
    </div>

  </form>

</div>

<?php if (ID == 1 && $results_page) { ?>

  <div class="alert alert-info">
    <a href="publications.export.php" class="btn btn-default">
      <i class="fa fa-table"></i>
      Download
    </a>
    You can download the full publications database including publication title, author(s), year, journal, tags, etc. as a <strong>CSV file</strong>.
  </div>

<?php } ?>

<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
$(function(){
  $(".show-all").click(function(e){
    e.preventDefault();
    var type = $(this).data("type");
    $(".hide-"+type).show('fast');
    $(this).hide();
  });
  $("#searchbox").select2({
    tags: true,
    tokenSeparators: [',']
  })
});
</script>
