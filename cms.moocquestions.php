<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = (int)$_GET['id'];
$list = $db->query("SELECT * FROM mooc_questions WHERE module = $id ORDER BY position");

$info = $db->record("SELECT * FROM mooc_modules WHERE id = $id");
$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");

if ($_GET['answer']) {
  $answer = (int)$_GET['answer'];
  $question = (int)$_GET['question'];
  $db->query("UPDATE mooc_questions SET right_answer = $answer WHERE id = $question");
  $print = "The answer was saved.";
} elseif ($_GET['question']) {
  $question = (int)$_GET['question'];
  $question_info = $db->record("SELECT * FROM mooc_questions WHERE id = $question");
  $answers = $db->query("SELECT * FROM mooc_answers WHERE question = $question ORDER BY id");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>MOOC Questions | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>MOOC Questions</h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><a href="cms.modules.php?id=<?php echo $mooc_info->id ?>"><?php echo $mooc_info->name ?></a></li>
    <li><a href="cms.module.php?id=<?php echo $info->id ?>"><?php echo $info->title ?></a></li>
    <li>Questions</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if ($question && !$_GET['answer']) { ?>

    <p>What is the right answer to the question?</p>

    <ul>
    <?php foreach ($answers as $row) { ?>

      <li><a href="cms.moocquestions.php?question=<?php echo $question ?>&amp;answer=<?php echo $row['id'] ?>&amp;id=<?php echo $id ?>"><?php echo $row['answer'] ?></a></li>

    <?php } ?>
    </u>

  <?php } else { ?>

  <p><a href="cms.moocquestion.php?module=<?php echo $id ?>" class="btn btn-info">Add Question</a></p>

  <table class="table table-striped">
    <tr>
      <th>Question</th>
      <th>Position</th>
      <th>Options</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="cms.moocquestion.php?id=<?php echo $row['id'] ?>"><?php echo $row['question'] ?></a></td>
      <td><?php echo $row['position'] ?></td>
      <td>
        <a href="cms.moocquestion.php?id=<?php echo $row['id'] ?>">Edit</a>
      </td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
