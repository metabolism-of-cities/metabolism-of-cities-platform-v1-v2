<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mooc_questions WHERE id = $id");

if ($_POST) {
  $post = array(
    'question' => html($_POST['question']),
    'module' => (int)$_POST['module'],
    'position' => (int)$_POST['position'],
  );
  if ($id) {
    $db->update("mooc_questions",$post,"id = $id");
  } else {
    $db->insert("mooc_questions",$post);
    $last = $db->record("SELECT id FROM mooc_questions ORDER BY id DESC LIMIT 1");
    $id = $last->id;
  }
  for ($i = 1; $i <= 5; $i++) {
    if ($_POST['answer'][$i]) {
      $post = array(
        'answer' => html($_POST['answer'][$i]),
        'question' => $id,
      );
      if ($_POST['answer_id'][$i]) {
        $db->update("mooc_answers",$post,"id = " . (int)$_POST['answer_id'][$i]);
      } else {
        $db->insert("mooc_answers",$post);
        $answer = $db->insert_id;
      }
    }
  }
  header("Location: ".URL."cms.moocquestions.php?question=$id&id=".$_POST['module']);
  exit();
}
$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");

$module = $info->module ?: (int)$_GET['module'];
$module_info = $db->record("SELECT * FROM mooc_modules WHERE id = $module");

if ($id) {
  $answers = $db->query("SELECT * FROM mooc_answers WHERE question = $id");
  $count = 1;
  foreach ($answers as $row) { 
    $answer[$count] = $row['answer'];
    $answer_id[$count] = $row['id'];
    $count++;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->question ? 'Edit Question' : 'Add Question' ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->question ? 'Edit Question' : 'Add Question' ?></h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><a href="cms.modules.php?id=<?php echo $mooc_info->id ?>"><?php echo $mooc_info->name ?></a></li>
    <li><a href="cms.module.php?id=<?php echo $module_info->id ?>"><?php echo $module_info->title ?></a></li>
    <li><a href="cms.moocquestions.php?id=<?php echo $module_info->id ?>">Questions</a></li>
    <li><?php echo $info->question ? 'Edit question' : "Add question" ?></li>
  </ol>


  <form method="post" class="form form-horizontal">

    <div class="form-group">
        <label class="col-sm-2 control-label">Question</label>
        <div class="col-sm-10">
            <textarea class="form-control" name="question"><?php echo $info->question ?></textarea>
        </div>
    </div>
  
    <?php for ($i = 1; $i <= 5; $i++) { ?>
    <div class="form-group <?php if ($info->right_answer == $answer_id[$i] && $info->right_answer) { echo 'has-success'; } ?>">
      <label class="col-sm-2 control-label">Answer</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="answer[<?php echo $i ?>]" value="<?php echo $answer[$i] ?>" />
      </div>
    </div>
    <input type="hidden" name="answer_id[<?php echo $i ?>]" value="<?php echo $answer_id[$i] ?>" />
    <?php } ?>

    <div class="form-group">
        <label class="col-sm-2 control-label">Position</label>
        <div class="col-sm-10">
            <input class="form-control" type="number" name="position" value="<?php echo $info->position ?>" />
        </div>
    </div>

    <input type="hidden" name="module" value="<?php echo $info->module ?: (int)$_GET['module'] ?>" />

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
