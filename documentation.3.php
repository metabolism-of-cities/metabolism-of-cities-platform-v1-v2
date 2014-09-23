<p>This section provides details on the more advanced options provided by OMAT:</p>

<ul>
  <li><a class="scroll" href="omat/documentation/2/#section1">Data Quality Indicators</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section2">Multiple Scales</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section3">Contact and Source Management</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section4">Time Tracking</a></li>
</ul>

<h2 id="section1"><i class="fa fa-dot-circle-o"></i> Data Quality Indicators</h2>

<p>
  Data Quality Indicators allow you to provide information on how reliable the data is. 
  It is one possible way of <a href="tags/109/uncertainty">managing uncertainty in Material
  Flow Analysis</a>. To start using Data Quality Indicators, you need to activate this option
  in the settings of your project (either when you create the project, or by going to 
  'Change Settings' in your dashboard). 
</p>

<p>
  You can choose to load a predefined set of indicators, or you can define your own. The pre-defined
  set comes with the following Data Quality Indicators:
</p>

<ul>
  <li>Access</li>
  <li>Completeness</li>
  <li>Further technological correlation</li>
  <li>Geographical correlation</li>
  <li>Reliability</li>
  <li>Temporal correlation</li>
</ul>

<p>
  Most of these indicators are defined the way they have been defined by 
  <a href="publication/183">Weidema and Wesnæs</a> for Life Cycle Assessments. 
  Each of the indicators will come with a scale between 1-5, and for each of these 
  scores an explanation is provided what this score should represent. However, all of
  these scores and the indicators can be freely modified to suit your needs. If you want
  to set up your own set of indicators, then simply load an empty skeleton and take it from there.
</p>

<p>
  <img src="img/documentation/dqi.png" alt="" />
</p>

<p>
  Once you have defined the different Data Quality Indicators, you will be able to set them
  for each of the data points. You will see a new box appear when you enter a new value in the 
  system. This box shows all your indicators and their possible scores. You can set a score 
  by simply clicking the relevant number.
</p>

<p>
  <img src="img/documentation/dqi-data.png" alt="" />
</p>

<h2 id="section2"><i class="fa fa-dot-circle-o"></i> Multiple Scales</h2>

<p>
  You may be doing the same kind of research on multiple scales. You can for instance study a city,
  while also doing an MFA on the city-wide region around it. OMAT enables you to use one dataset and 
  create single graphs and tables that contrast the different scales at once. In order to start using
  multiple scales, be sure you have activated this option in the settings of your project.
</p>

<p>
  Before you can use the multiple scales, be sure to go to Maintenance &raquo; Types of Scales. Here, 
  you can add each of the options (for instance 'City of Cape Town', and 'Western Cape Province') as 
  two different scales.
</p>

<p>
  <img src="img/documentation/types-scales.png" alt="" />
</p>

<p>
  Once this is done, you will see these different scales appear in a dropdown list when you add a 
  data point. For each data point, you will have to select to which of the options it applies.
</p>

<p>
  <img src="img/documentation/scale.png" alt="" />
</p>


<h2 id="section3"><i class="fa fa-dot-circle-o"></i> Contact and Source Management</h2>

<p>
  Performing a Material Flow Analysis is not just about putting down numbers in a spreadsheet and then
  analyzing the results. The actual process of obtaining this data is often the major part of the work. 
  Finding reliable sources that can provide you with the data you need on the scale you need is a
  major struggle for many of the MFAs done. OMAT therefore comes with tools that help you keep track 
  of this 'back-office' part of doing an MFA. Be sure to activate this option in the settings if you
  want to use it.
</p>

<h3>Managing Contacts</h3>

<p>
  Contacts are the people and organizations that you use to locate the data that you need. Depending
  on how easily data is available, you may need to contact people in order to understand where to get
  the data from, or to request reports that are not publicly available. Similarly, it may be necessary
  to contact experts who can help with conversion of data or other interpretation or checking of information.
</p>

<p>
  <img src="img/documentation/contacts.png" alt="" />
</p>

<p>
  In OMAT you can add a contact and manage the following details:
</p>

<ul>
  <li>Name and general information of the contact</li>
  <li>Notes (from meetings, etc.) related to this contact</li>
  <li>Leads provided by this contact (so if a person sent you the contact details of someone else, or a link
  to download a report, then you can link these leads to the original contact)</li>
  <li>Status (pending, processed, etc)</li>
  <li>High priority tags to indicate which ones you want to prioritize</li>
  <li>Other tags that you can define to flag particular contacts (e.g. the ones that have asked you to send
  your final result).</li>
  <li>For organizations, you can add employees to the organization.</li>
</ul>

<p>
  <img src="img/documentation/contact.png" alt="" />
</p>

<h3>Managing sources</h3>

<p>
  Similar to how you manage contacts, you can also manage the different documents and other sources
  that you use. For each source, you can create a record that holds the following information:
</p>

<ul>
  <li>General information</li>
  <li>Notes</li>
  <li>Status (pending, processed, etc.)</li>
  <li>High priority tags to indicate which ones you want to prioritize</li>
  <li>Other tags that you can define to flag particular contacts (e.g. the ones that have asked you to send
  your final result).</li>
  <li>Links to the actual files (either by uploading the files or by entering an URL)</li>
</ul>

<p>
  <img src="img/documentation/file.png" alt="" />
</p>


<h2 id="section4"><i class="fa fa-dot-circle-o"></i> Time Tracking</h2>

<p>
  If you activate time tracking, then OMAT will provide tools that make it very easy to track how
  much time you spent on the different activities. You can se the different activities yourself, but among the 
  options are to track time spent on:
</p>

<ul>
  <li>Reading through documents</li>
  <li>Meetings with people</li>
  <li>Transportation</li>
  <li>Calculation and extrapolation</li>
  <li>Searching and browsing for relevant information</li>
</ul>

<p>
  To set the activities you want to track, you can go to Maintenance and click on Types of Activities. Here you 
  can add and edit the types you want to track.
</p>

<p>
  <img src="img/documentation/activities.png" alt="" />
</p>

<p>
  Once you have set these options, you can record the time spent on each contact and source. You will see a new
  box appear, where you can track the time. There are two options to track this: you can either simply enter 
  the time in minutes you spent doing something, or you can start a timer when you initiate and you end it 
  when you finish the activity. 
</p>

<p>
  <img src="img/documentation/time.png" alt="" />
</p>

<a href="omat/documentation/4" class="btn btn-primary pull-right">Next Section &raquo;</a>
