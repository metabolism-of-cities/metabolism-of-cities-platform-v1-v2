<?php
require_once 'functions.php';
$section = 7;

$id = (int)$_GET['id'];

if ($_GET['remove'] > -1) {
  $remove = (int)$_GET['remove'];
  setcookie("votes[$remove]", false, time()-1, "/");
  unset($_COOKIE['votes'][$remove]);
}

$votes = $_COOKIE['votes'];

if ($id) {
  $total_votes = count($votes);
  if ($total_votes >= 3) {
    $error = "Sorry, you already have three data visualizations on your list. Remove one of them before adding another one.";
  } else {
    setcookie("votes[$id]", 'true', time()+60*60*24*31, "/");
    $_COOKIE['votes'][$id] = true;
  }
}

$votes = $_COOKIE['votes'];

if (is_array($votes)) {
  foreach ($votes as $key => $value) {
    $where .= (int)$key . ",";
  }
}

if ($where) {
  $where = substr($where, 0, -1);
  $list = $db->query("SELECT * FROM datavisualizations WHERE id IN ($where) LIMIT 3");
}

if (is_array($_POST['vote']) && !$_COOKIE['voted']) {
  $count = 0;
  foreach ($_POST['vote'] as $key => $value) {
    $count++;
    if ($count <= 3) {
      $post = array(
        'datavisualization' => (int)$key,
        'ip' => mysql_clean($_SERVER["REMOTE_ADDR"]),
        'browser' => mysql_clean($_SERVER["HTTP_USER_AGENT"]),
        'info' => mysql_clean(getinfo()),
        'comments' => html($_POST['comments'][$key]),
        'name' => html($_POST['name']),
        'email' => html($_POST['email']),
        'newsletter' => (int)$_POST['news'],
        'stakeholders' => (int)$_POST['stakeholders'],
      );
      $db->insert("votes",$post);
    }
  }
  $print = "Thanks, we have received your vote(s)!";
  setcookie("voted", 'true', time()+60*60*24*31, "/");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Vote for your favorite data visualization | <?php echo SITENAME ?></title>
    <style type="text/css">
      .panel-heading a{position:relative;top:-6px;right:-10px}
      .row img{width:100%}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li><a href="datavisualization">Data Visualizations</a></li>
    <li><a href="datavisualization/examples">Examples</a></li>
    <li class="active">Vote Now</li>
  </ol>

  <?php if ($_COOKIE['voted']) { ?>

    <div class="jumbotron">
      
      <h1>You have already cast your vote(s)</h1>
      <p>
        Thanks for participating! Check back in the first week of January 2017
        to see the results.
      </p>

    </div>

  <?php } elseif ($print) { echo "<div class=\"jumbotron alert alert-success\"><h1>$print</h1></div>"; ?>

  <?php } else { ?>

  <?php if ($error) { echo "<div class=\"alert alert-danger\">$error</div>"; } ?>

  <div class="jumbotron">
    <h1>Cast your vote</h1>
    <p>
      We are collecting votes to identify the best data visualizations. Which one(s) do you like most? 
      Which are the most creative, the clearest, the cleverest, the most inspiring data visualizations?
      Cast your vote now! You can select up to <strong>3</strong> data visualizations.
    </p>
    <?php if (count($votes) < 3) { ?>
    <p>
      <a class="btn btn-lg btn-primary" href="datavisualization/examples" role="button">Add other data visualizations &raquo;</a>
    </p>
    <?php } ?>
  </div>

  <h1>Your votes</h1>

  <?php if (count($list)) { ?>

  <p>Please feel free to add optional comments to each data visualization. These comments will be 
  shared on our site after voting has closed.
  </p>

  <form method="post" class="form form-horizontal">
  
  <div class="row">

    <?php foreach ($list as $row) { ?>

      <div class="col-md-4">
      
        <div class="panel panel-default">
          <div class="panel-heading">
            <a 
            href="vote.php?remove=<?php echo $row['id'] ?>" 
            class="btn btn-danger pull-right" 
            title="Remove from the list"><i class="fa fa-close"></i></a>
          <?php echo $row['title'] ?>
          </div>
          <div class="panel-body">
            <span>
              <a href="datavisualizations/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>">
                <img src="media/dataviz/<?php echo $row['id'] ?>.jpg" alt="" />
              </a>
            </span>
            <br />
              <textarea class="form-control"
              placeholder="Why do you like this one? [OPTIONAL]"
              name="comments[<?php echo $row['id'] ?>]"></textarea>
              <input type="hidden" name="vote[<?php echo $row['id'] ?>]" value="true" />
          </div>
        </div>

      </div>

    <?php } ?>

    <div class="clearfix"></div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Your name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" required />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Your e-mail</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="email" placeholder="Your e-mail will not be published" required />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="news" value="1" /> 
              Yes, subscribe me to the Metabolism of Cities newsletter
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="stakeholders" value="1" /> 
              Yes, subscribe me to the Metabolism of Cities Stakeholders Initiative
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Cast your vote now!</button>
      </div>
    </div>

  </form>
  

  </div>

  <?php } else { ?>

    <div class="alert alert-danger">
      You have not yet selected any data visualizations. Please go to our
      <a href="datavisualization/examples">catalog</a> to view them and select
      your favorite one.
    </div>

  <?php } ?>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
