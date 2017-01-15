<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 4;
$page = 6;

$keywords = $db->query("SELECT COUNT(*) AS total, keywords.keyword 
FROM keywords_papers JOIN keywords ON keywords_papers.keyword = keywords.id 
GROUP BY keywords.keyword
ORDER BY COUNT(*) DESC");

$keywords_alfabet = $db->query("SELECT COUNT(*) AS total, keywords.keyword 
FROM keywords_papers JOIN keywords ON keywords_papers.keyword = keywords.id 
GROUP BY keywords.keyword
ORDER BY keywords.keyword");

$tag_parents = $db->query("SELECT SQL_CACHE * FROM tags_parents ORDER BY id");

foreach ($tag_parents as $row) {
  $tags[$row['id']] = $db->query("SELECT SQL_CACHE * FROM tags WHERE parent = {$row['id']} ORDER BY tag");
}

$tags_all = $db->query("SELECT COUNT(*) AS total, tags.tag, tags.id
FROM tags_papers JOIN tags ON tags_papers.tag = tags.id 
GROUP BY tags.tag
ORDER BY COUNT(*) DESC");

$tags_alfabet = $db->query("SELECT COUNT(*) AS total, tags.tag, tags.id
FROM tags_papers JOIN tags ON tags_papers.tag = tags.id 
GROUP BY tags.tag
ORDER BY tags.tag");

$sources = $db->query("SELECT * FROM sources ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Studies | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <style type="text/css">
    .shortlist {
      max-height: 270px;
      position: relative;
      overflow: hidden;
      background-color:#fff;
      background-color:rgb(255,255,255);
    }
    .shortlist .read-more { 
      position: absolute; 
      bottom: 0; left: 0;
      width: 100%; 
      text-align: center; 
      margin: 0; 
      padding: 30px 0; 
      list-style:none;
      
      /* "transparent" only works here because == rgba(0,0,0,0) */ 
      background-image: -webkit-linear-gradient(top, transparent, white);
      background-image: -ms-linear-gradient(top, transparent, white);
      background-image: -o-linear-gradient(top, transparent, white);

			background-image: -moz-linear-gradient(top, rgba(255,255,255,0), rgba(255,255,255,100));
			background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, rgba(255,255,255,0)),color-stop(1, rgba(255,255,255,100)));
    }
    </style>
    <script type="text/javascript">
    $(function(){

      // From: http://css-tricks.com/text-fade-read-more/
		
			var $el, $ps, $up, totalHeight;
			
			$(".shortlist .btn").click(function() {
			
				// IE 7 doesn't even get this far. I didn't feel like dicking with it.
						
				totalHeight = 0
			
				$el = $(this);
				$p  = $el.parent();
				$up = $p.parent();
				$ps = $up.find("li:not('.read-more')");
				
				// measure how tall inside should be by adding together heights of all inside paragraphs (except read-more paragraph)
				$ps.each(function() {
					totalHeight += $(this).outerHeight();
					// FAIL totalHeight += $(this).css("margin-bottom");
				});
							
				$up
					.css({
						// Set height to prevent instant jumpdown when max height is removed
						"height": $up.height(),
						"max-height": 9999
					})
					.animate({
						"height": totalHeight
					});
				
				// fade out read-more
				$p.fadeOut();
				
				// prevent jump-down
				return false;
					
			});
		
		});
  </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Search</h1>

<h2>Search in title/abstract</h2>

<form method="post" class="form form-horizontal" action="publications/list">

  <div class="form-group">
    <label class="col-sm-2 control-label">Search</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="searchphrase" value="<?php echo $info->name ?>" />
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="title" value="1" checked /> 
            Search in title
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="abstract" value="1" checked /> 
            Search in abstract
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>

</form>

<h2>Custom Filters</h2>

<div class="alert alert-warning">
  Use the fields below to create your own filters and easily find what you are looking for. 
  All of the fields are optional; only fill out those that are of importance to you. 
  <br />
  <strong>The system will only find publications that match ALL of the filters that
  you select.</strong>
</div>

<form method="post" class="form form-horizontal" action="publications/list">

  <?php foreach ($tag_parents as $row) { ?>
  <div class="form-group">
    <label class="col-sm-2 control-label"><?php echo $row['name'] ?></label>
    <div class="col-sm-10">
      <select name="tags[<?php echo $row['id'] ?>][]" class="form-control" multiple size="<?php echo min(6, count($tags[$row['id']])) ?>">
      <?php foreach ($tags[$row['id']] as $subrow) { ?>
        <option value="<?php echo $subrow['id'] ?>"<?php if ($tag[$subrow['id']]) { echo ' selected'; } ?>><?php echo $subrow['tag'] ?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  <?php } ?>

   <div class="form-group">
     <label class="col-sm-2 control-label">Publication date</label>
     <div class="col-sm-3">
     In or after
       <input class="form-control" type="text" name="start" value="<?php echo $start ? $start : '' ?>" placeholder="E.g. <?php echo date("Y")-10 ?>" />
     </div>
     <div class="col-sm-3">
     But not later than
       <input class="form-control" type="text" name="end" value="<?php echo $end ? $end : '' ?>" placeholder="E.g. <?php echo date("Y") ?>" />
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-2 control-label">Source</label>
     <div class="col-sm-10">
       <select name="source" class="form-control">
        <option value=""></option>
       <?php foreach ($sources as $row) { ?>
         <option value="<?php echo $row['id'] ?>"<?php if ($source == $row['id']) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
       <?php } ?>
       </select>
     </div>
   </div>
   
   <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">
       <button type="submit" class="btn btn-primary">Search</button>
     </div>
   </div>

</form>

<h2>By Tag</h2>

<div class="alert alert-warning">
  Tags have been manually assigned by our team to each paper. The tags have been standardized
  among papers and we have tried to be consistent and make tags useful for filtering through 
  urban metabolism-related papers. 
</div>

<h3>Tag List</h3>

<div class="row">
  <div class="col-md-4">
    <h4>List by ocurrence</h4>
    <div class="resultbox">
      <i class="fa fa-info-circle"></i>
      <strong><?php echo count($tags_all) ?></strong> tags found
    </div>
    <ul class="shortlist">
    <?php foreach ($tags_all as $row) { ?>
      <li>
        <a href="tags/<?php echo $row['id'] ?>/<?php echo flatten($row['tag']) ?>"><?php echo $row['tag'] ?></a>
        (<?php echo $row['total'] ?>)
      </li>
    <?php } ?>
      <li class="read-more"><a href="#" class="btn btn-info">Read more</a></li>
    </ul>
  </div>
  <div class="col-md-4">
    <h4>Alphabetical list</h4>
    <div class="resultbox">
      <i class="fa fa-info-circle"></i>
      <strong><?php echo count($tags_alfabet) ?></strong> tags found
    </div>
    <ul class="shortlist">
    <?php foreach ($tags_alfabet as $row) { ?>
      <li>
        <a href="tags/<?php echo $row['id'] ?>/<?php echo flatten($row['tag']) ?>"><?php echo $row['tag'] ?></a>
        (<?php echo $row['total'] ?>)
      </li>
    <?php } ?>
      <li class="read-more"><a href="#" class="btn btn-info">Read more</a></li>
    </ul>
  </div>
</div>

<div class="hide">


<h2>By Keyword</h2>

<div class="alert alert-warning">
  Please note that keywords are provided directly by authors and possibly adjusted by
  editors. There are no industry-wide standards and the keywords used come in
  a wide variety. We have listed the different keywords used in papers here, but
  if you want to filter using a shorter and more standardized list of keywords,
  then we recommend you use the <a href="tags">tags</a> that we assigned to
  classify each paper instead.
</div>

<h3>Keyword List</h3>

<div class="row">
  <div class="col-md-4">
    <h4>List by ocurrence</h4>
    <div class="resultbox">
      <i class="fa fa-info-circle"></i>
      <strong><?php echo count($keywords) ?></strong> keywords found
    </div>
    <ul class="shortlist">
    <?php foreach ($keywords as $row) { ?>
      <li>
        <a href="studies.search.php?keyword=<?php echo $row['id'] ?>"><?php echo $row['keyword'] ?></a>
        (<?php echo $row['total'] ?>)
      </li>
    <?php } ?>
      <li class="read-more"><a href="#" class="btn btn-info">Read more</a></li>
    </ul>
  </div>
  <div class="col-md-4">
    <h4>Alphabetical list</h4>
    <div class="resultbox">
      <i class="fa fa-info-circle"></i>
      <strong><?php echo count($keywords_alfabet) ?></strong> keywords found
    </div>
    <ul class="shortlist">
    <?php foreach ($keywords_alfabet as $row) { ?>
      <li>
        <a href="studies.search.php?keyword=<?php echo $row['id'] ?>"><?php echo $row['keyword'] ?></a>
        (<?php echo $row['total'] ?>)
      </li>
    <?php } ?>
      <li class="read-more"><a href="#" class="btn btn-info">Read more</a></li>
    </ul>
  </div>
</div>

</div>

<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
$(function(){
  $("select").select2();
});
</script>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
