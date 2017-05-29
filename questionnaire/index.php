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
		                <form action="thanks.php" method="post">
		                <!--        You can switch " data-color="green" "  with one of the next bright colors: "blue", "azure", "orange", "red"       -->

		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">Material Stock Special Session</h3>
		                        	<p class="category">Questionnaire</p>
		                    	</div>
								<div class="wizard-navigation">
									<div class="progress-with-circle">
									    <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="4" style="width: 15%;"></div>
									</div>
									<ul>
			                            <li>
											<a href="#location" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-user"></i>
												</div>
												Personal Info
											</a>
										</li>
			                            <li>
											<a href="#type" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-pencil"></i>
												</div>
												Work
											</a>
										</li>
			                            <li>
											<a href="#research" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-book"></i>
												</div>
												Research
											</a>
										</li>
			                            <li>
											<a href="#data" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-list"></i>
												</div>
												Data
											</a>
										</li>
			                        </ul>
								</div>

<div class="tab-content">

  <div class="tab-pane" id="location">
    <div class="row">
        <div class="col-sm-12">
            <h5 class="info-text">First things first</h5>
      </div>
        <div class="col-sm-5 col-sm-offset-1">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="firstname" >
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="lastname" >
            </div>
        </div>
        <div class="col-sm-10 col-sm-offset-1 ">
            <div class="form-group">
                <label>Affiliation</label>
                <input type="text" class="form-control" name="affiliation">
            </div>
        </div>
        <div class="col-sm-5 col-sm-offset-1">
            <div class="form-group">
                <label>City</label>
                <input type="text" class="form-control" name="city">
            </div>
        </div>
        <div class="col-sm-5 ">
            <div class="form-group">
                <label>Country</label>
                <input type="text" class="form-control" name="country">
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <h5 class="info-text">Disclaimer</h5>
      </div>
      <div class="col-sm-11 col-sm-offset-1">
      The information gathered in this questionnaire are collected and will be processes for the special session on Material Stocks during the ISIE conference. Information will then be processed to provide a general overview of the current state of research. If you wish that your information is not shared with other researchers please tick the following box.
      </div>
        <div class="col-sm-11 col-sm-offset-1" style="margin-top:11px">
            <div class="form-group">
                  <label>
                        <input type="checkbox" name="do_not_share" value="1" >
                        Do not share my information during the ISIE conference.
                  </label>
            </div>
        </div>
    </div>
  </div>
  <div class="tab-pane" id="type">
    <div class="row">
        <div class="col-sm-12">
            <h5 class="info-text">Information about your work</h5>
        </div>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
                <label>What field do you work in?</label>
                <select class="form-control" name="work_field">
                    <option disabled="" selected="">- select -</option>
                    <?php foreach ($fields as $key => $value) { ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <h5 class="info-text">My work includes...</h5>
            <p class="centertext">Mark all that apply</p>
      </div>
        <?php foreach ($work as $key => $value) { ?>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
                  <label>
                        <input type="checkbox" name="work[<?php echo $key ?>]" value="true" <?php if ($key == 99) { ?> id="work" <?php } ?>>
                      <?php echo $value ?>
                  </label>
            </div>
        </div>
        <?php } ?>
        <div class="col-sm-10 col-sm-offset-1 otherinfo work_other">
          <div class="form-group">
              <textarea class="form-control" name="work_other" placeholder="Please provide more details here"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h5 class="info-text">Which of the following areas concerns your work on material stock analysis?</h5>
            <p class="centertext">Mark all that apply</p>
        </div>
        <?php foreach ($work_areas as $key => $value) { ?>
        <div class="col-sm-10 col-sm-offset-1">
              <div class="form-group">
                    <label>
                        <input type="checkbox" name="areas[<?php echo $key ?>]" value="true" <?php if ($key == 99) { ?> id="workarea" <?php } ?>>
                        <?php echo $value ?>
                    </label>
              </div>
          </div>
        <?php } ?>
        <div class="col-sm-10 col-sm-offset-1 otherinfo work_areas_other">
          <div class="form-group">
              <textarea class="form-control" name="areas_other" placeholder="Please provide more details here"></textarea>
            </div>
        </div>
    </div>
  </div>
  <div class="tab-pane" id="research">
      <div class="row">
      <h5 class="info-text">Does your work cover specific regions?</h5>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
                <select class="form-control" id="regions" name="regions">
                    <option disabled="" selected="">- select -</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1 extra regions">
          <div class="form-group">
              <textarea class="form-control" name="regions_other" placeholder="Which?"></textarea>
            </div>
        </div>
      </div>

    <div class="row">
        <div class="col-sm-12">
            <h5 class="info-text">What is the scale of your work?</h5>
            <p class="centertext">Mark all that apply</p>
      </div>
        <?php foreach ($scales as $key => $value) { ?>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
                  <label>
                        <input type="checkbox" name="scales[<?php echo $key ?>]" value="true" <?php if ($key == 99) { ?> id="scale" <?php } ?>>
                      <?php echo $value ?>
                  </label>
            </div>
        </div>
        <?php } ?>
        <div class="col-sm-10 col-sm-offset-1 otherinfo scale_other">
          <div class="form-group">
              <textarea class="form-control" name="scales_other" placeholder="Please provide more details here"></textarea>
            </div>
        </div>
      <div class="row">
        <div class="col-sm-12">
          <h5 class="info-text">Does your work cover specific materials?</h5>
        </div>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
                <select class="form-control" name="materials" id="materials">
                    <option disabled="" selected="">- select -</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
        <div class="col-sm-10 col-sm-offset-1 extra materials">
          <div class="form-group">
              <textarea class="form-control" name="materials_details" placeholder="Which?"></textarea>
            </div>
        </div>
      </div>
    </div>
  </div>


  <div class="tab-pane" id="data">
      <div class="row">
          <h5 class="info-text">Last couple of questions about data use</h5>
          <div class="col-sm-5 col-sm-offset-1">
              <div class="form-group">
                  <label>Do you produce primary data in your work?</label>
                  <select class="form-control" name="primary_data">
                      <option disabled="" selected="">- select -</option>
                      <option value="1">Yes</option>
                      <option value="2">No </option>
                  </select>
              </div>
          </div>
          <div class="col-sm-5">
              <div class="form-group">
                  <label>What type of data you use?</label>
                  <textarea class="form-control" name="data_type" rows="9"></textarea>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-sm-5 col-sm-offset-1">
              <div class="form-group">
                  <label>How do you process, store, and archive your data?</label>
                  <textarea class="form-control" name="data_details" rows="9"></textarea>
              </div>
          </div>
          <div class="col-sm-5">
              <div class="form-group">
                  <label>What software do you typically use for calculating MFA?</label>
                  <textarea class="form-control" name="software" rows="9"></textarea>
              </div>
          </div>

      </div>
  </div>
</div>
<div class="wizard-footer">
  <div class="pull-right">
        <input type='button' class='btn btn-next btn-fill btn-success btn-wd' name='next' id="next" value='Next' />
        <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='finish' value='Finish' />
</div>

	                                <div class="pull-left">
	                                    <input type='button' class='btn btn-previous btn-default btn-wd' name='previous' value='Previous' />
	                                </div>
	                                <div class="clearfix"></div>
		                        </div>
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	        </div> <!-- row -->
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


$("form input").on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    $("#next").click();
  }
});
});
</script>

</body>

</html>
