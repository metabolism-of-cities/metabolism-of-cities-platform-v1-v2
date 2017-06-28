<?php

require_once '../functions.php';

if ($_POST['finish']) {
  $info = getinfo();
  if (is_array($_POST['areas'])) {
    foreach ($_POST['areas'] as $key => $value) {
      $areas .= $key . ",";
    }
  }
  if (is_array($_POST['work_field'])) {
    foreach ($_POST['work_field'] as $key => $value) {
      $work_field .= $key . ",";
    }
  }
  if (is_array($_POST['work'])) {
    foreach ($_POST['work'] as $key => $value) {
      $work .= $key . ",";
    }
  }
  if (is_array($_POST['scales'])) {
    foreach ($_POST['scales'] as $key => $value) {
      $scales .= $key . ",";
    }
  }

  $post = array(
  'firstname' => html($_POST['firstname']),
  'lastname' => html($_POST['lastname']),
  'affiliation' => html($_POST['affiliation']),
  'city' => html($_POST['city']),
  'country' => html($_POST['country']),
  'do_not_share' => (int)$_POST['do_not_share'],
  'work_field' =>  $work_field,
  'work' => $work,
  'work_other' => html($_POST['work_other']),
  'areas' => $areas,
  'areas_other' => html($_POST['areas_other']),
  'regions' => (int)$_POST['regions'],
  'regions_other' => html($_POST['regions_other']),
  'scales' => $scales,
  'scales_other' => html($_POST['scales_other']),
  'materials' => (int)$_POST['materials'],
  'materials_details' => html($_POST['materials_details']),
  'primary_data' => (int)$_POST['primary_data'],
  'data_type' => html($_POST['data_type']),
  'data_details' => html($_POST['data_details']),
  'email' => html($_POST['email']),
  'software' => html($_POST['software']),
  'browser' => html($_SERVER["HTTP_USER_AGENT"]),
  'ip' => html($_SERVER["REMOTE_ADDR"]),
  'post' => html($info),
  );
  $db->insert("questionnaire",$post);
  $id = $db->lastInsertId();
  $msg = 
"The questionnaire was filled out. 

Name: " . mail_clean($_POST['firstname']) . "  " . mail_clean($_POST['lastname']) . "
Date: " . date("r") . "
ID: $id";
  mailadmins($msg, "Questionnaire filled out");
}

if ($_POST['literature']) {
  $id = (int)$_POST['id'];
  $post = array(
    'literature' => html($_POST['literature']),
  );
  $db->update("questionnaire",$post,"id = $id");
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png" />
	<title>Material Stock Special Session: Questionnaire</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/paper-bootstrap-wizard.css" rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="assets/css/demo.css" rel="stylesheet" />

	<!-- Fonts and Icons -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
	<link href="assets/css/themify-icons.css" rel="stylesheet">
  <style type="text/css">
  .centertext{text-align:center;margin-top:-20px;opacity:0.5}
  .otherinfo, .extra{display:none}
.wizard-card .tab-content{padding-top:0}
  </style>
</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('assets/img/background.jpg')">
	    <!--   Creative Tim Branding   -->

		<!--  Made With Pape
    Kit  -->
		<a href="http://demos.creative-tim.com/paper-kit?ref=paper-bootstrap-wizard" class="made-with-pk">
			<div class="brand">PK</div>
			<div class="made-with">Made with <strong>Paper Kit</strong></div>
		</a>

	    <!--   Big container   -->
	    <div class="container">
      
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">

		            <!--      Wizard container        -->
		            <div class="wizard-container">
		                <div class="card wizard-card" data-color="orange" id="wizard">
		                <form action="" method="post">
		                <!--        You can switch " data-color="green" "  with one of the next bright colors: "blue", "azure", "orange", "red"       -->

		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">Material Stock Special Session</h3>
		                        	<p class="category">Questionnaire</p>
		                    	</div>

                          <div class="tab-content">

                              <div class="row">
                              <div class="col-sm-10 col-sm-offset-1">
                                <div class="alert alert-success">
                                <h2 style="margin-top:5px">Thanks!</h2>
                                <?php if ($_POST['literature']) { ?>
                                  Thanks again for your support, we have received your information.
                                </div>
                                </div>
                                <?php } else { ?>
                                <p>We have received your information. 
                                  This is all we need for the special session. Thank you!
                                </p>
                                </div>
                                </div>
        <div class="col-sm-12">
            <h5 class="info-text">Optional Section</h5>
        </div>
                              <div class="col-sm-10 col-sm-offset-1">
                                <p>
                                We are trying to set up a <strong>literature database</strong> that contains all work published 
                                in the field of material stock research. Can you help? Please list publications that you know of in the field below. Any 
                                number of publications is helpful. You can enter the URL of the publication, the academic reference, or 
                                the DOI. If you have this in spreadsheet format, please feel free to e-mail it to us. 
                                </p>
                                </div>
          <div class="col-sm-10 col-sm-offset-1" >
              <div class="form-group">
                  <label>Literature references</label>
                  <textarea class="form-control" name="literature" rows="9"></textarea>
              </div>
          </div>
<div class="wizard-footer">
  <div class="pull-right">
        <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='lit' value='Send' />
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <input type="hidden" name="hash" value="<?php echo encrypt("Questionnaire$id") ?>" />
  </div>
</div>
                                <?php } ?>

                            </div>
                    </form>
                    </div>
                </div>

            </div>
          </div>

      </div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center">
              Made by the Material Stock Special Session Task Force
	        </div>
	    </div>
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/paper-bootstrap-wizard.js" type="text/javascript"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="assets/js/jquery.validate.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
  $("#workarea").change(function(){
    if ($(this).is(":checked")) {
      $(".work_areas_other").show('fast');
    }
    else 
    {
      $(".work_areas_other").hide('fast');
    }
  });
  $("#work").change(function(){
    if ($(this).is(":checked")) {
      $(".work_other").show('fast');
    }
    else 
    {
      $(".work_other").hide('fast');
    }
  });
  $("#scale").change(function(){
    if ($(this).is(":checked")) {
      $(".scale_other").show('fast');
    }
    else 
    {
      $(".scale_other").hide('fast');
    }
  });
  $("#materials").change(function(){
    if ($(this).val() == "1") {
      $(".materials").show('fast');
    }
    else 
    {
      $(".materials").hide('fast');
    }
  });
  $("#regions").change(function(){
    if ($(this).val() == "1") {
      $(".regions").show('fast');
    }
    else 
    {
      $(".regions").hide('fast');
    }
  });
});
</script>

</body>

</html>
