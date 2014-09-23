<p>For a basic MFA we are considering that you do not include any extra options, but 
just want to focus on an Economy-Wide Material Flow Analysis for a particular system, 
without keeping track of 'back-office' affairs like managing your contacts and sources,
and without extra options like Data Quality Indicators.</p>

<p>These are the steps to use OMAT for performing a basic MFA:</p>

<ul>
  <li>Create your project and enter some basic settings (see <a href="omat/documentation/1">Getting Started</a>)</li>
  <li><a class="scroll" href="omat/documentation/2/#section1">Define which materials you want to analyze</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section2">Collect your data</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section3">Enter the data for each of the materials and for all of the years of study</a></li>
  <li><a class="scroll" href="omat/documentation/2/#section4">Generate indicators and reports</a></li>
</ul>

<h2 id="section1"><i class="fa fa-dot-circle-o"></i> Defining materials and data groups</h2>

<p>
  There are two ways of managing the different materials and data groups. You could study a few different materials 
  that are of your interest, but you could also decide to study hundreds of materials based on a particular framework. 
  In your dashboard you will see these two options under Data:
</p>

<p>
  <img src="img/documentation/load-data.png" alt="" />
</p>

<p>
  If you load the EUROSTAT data groups, then the system will automatically load 238 materials as defined in the 
  <a href="publication/164">EUROSTAT 2013 Compilation Guide</a>, in 4 different groups (Domestic Extraction Used,
  Imports, Exports, and Domestic Processed Output). If you load an empty skeleton, then you will have the option
  to define your different groups and materials. In either way, the system allows you to freely edit the names and
  materials of your study. 
</p>

<p> 
  <img src="img/documentation/data-groups.png" alt="" />
</p>

<h2 id="section2"><i class="fa fa-dot-circle-o"></i> Collect your data</h2>

<p>
  This is the actual work of your MFA! Find trustworthy sources that can provide you with the information you need.
  Be sure to convert it all to the same measurement (the one you have defined in the settings of your project).
  Click here if you want to learn more about the <a href="omat/documentation/4">data collection</a> process.
</p>

<h2 id="section3"><i class="fa fa-dot-circle-o"></i> Entering information</h2>

<p>
  Entering information in OMAT is simple. In the Data section, navigate to the particular material that you have
  information for. Say you have found the <strong>Domestic Extraction Used</strong> for <strong>Cereals</strong>. 
  Find this item in the list, and click on the name:
</p>

<p>
  <img src="img/documentation/cereals.png" alt="" />
</p>

<p>
  Here, click the <strong>Add data point</strong> button. You can now fill out a form with the details:
</p>

<p>
  <img src="img/documentation/add-data.png" alt="" />
</p>

<p>
  Please note that the source name and link are optional (but highly recommended so that you can more easily 
  review information later and report to others where you found this). Comments allow you to further elaborate
  about this particular data point. If you want to record comments about this material (Cereals in thise case), 
  then you can enter those as well in the list of materials. These could be internal comments that help you 
  keep your notes about different materials in the right place.
</p>

<h2 id="section4"><i class="fa fa-dot-circle-o"></i> Indicators and reports</h2>

<p>
  OMAT will be able to calculate indicators and generate reports on the fly. These reports can be created at 
  any moment, and they can be adjusted to your needs. If you load data from the EUROSTAT framework, then the 
  system will automatically calculate five different indicators:
</p>

<ul>
  <li>Direct Material Input (DMI)</li>
  <li>Domestic Material Consumption (DMC)</li>
  <li>Physical Trade Balance (PTB)</li>
  <li>Domestic Processed Output (DPO)</li>
  <li>Direct Material Output (DMO)</li>
</ul>

<p>
  <img src="img/documentation/indicators.png" alt="" class="limitwidth" />
</p>

<p>
  OMAT also shows you other indicator types and their description, and it invites you to define these indicators 
  for your particular situation (you can tell the system which data groups to add or subtract for each of the 
  indicators). 
</p>

<p>
  For each indicator the system will generate a table with the information and a graph to visually display the 
  results:
</p>

<p>
  <img src="img/documentation/indicator-graph.png" alt="" class="limitwidth" />
</p>

<a href="omat/documentation/3" class="btn btn-primary pull-right">Next Section &raquo;</a>
