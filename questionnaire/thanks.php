<?php

$fields = array(
  1 => "Academia",
  2 => "Consulting",
  3 => "Government",
  4 => "Industry",
  5 => "Other",
);

$work = array(
  1 => "Material stocks",
  2 => "Material flows (related to stocks)",
  3 => "Material flows (non-related to stocks)",
  4 => "Bottom-up approach",
  5 => "Top-down approach",
  6 => "Stationary and quasi-stationary models",
  7 => "Dynamic MFA models",
  99 => "Other",
);

$work_areas = array(
  1 => "Buildings and infrastructure",
  2 => "Construction/demolition materials",
  3 => "GHG emissions",
  4 => "Energy demand",
  5 => "Forecasting and scenario development",
  99 => "Other",
);

$scales = array(
  1 => "Urban",
  2 => "Rural",
  3 => "National",
  4 => "Regional",
  5 => "Global",
  99 => "Other",
);

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
                                <h2>Thanks!</h2>
                                <?php if ($_POST['Send']) { ?>
                                <div class="alert alert-success">
                                  Thanks again for your support, we have received your information.
                                </div>
                                <?php } else { ?>
                                <p>We have received your information. 
                                </p>
                                <p>
                                We have one last request. We are trying to set up a <strong>literature database</strong> that contains all work published 
                                in the field of material stock research. Can you help? Please list publications that you know of in the field below. Any 
                                number of publications is helpful. You can enter the URL of the publication, the academic reference, or 
                                the DOI. If you have this in spreadsheet format, please feel free to e-mail it to us. 
                                </p>
                                </div>
          <div class="col-sm-10 col-sm-offset-1" >
              <div class="form-group">
                  <label>Literature references</label>
                  <textarea class="form-control" placeholder="" rows="9"></textarea>
              </div>
          </div>
<div class="wizard-footer">
  <div class="pull-right">
        <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='Send' value='Send' />
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
