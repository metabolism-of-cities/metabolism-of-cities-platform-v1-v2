
<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="./" class="simple-text">
                    Material Stock
                </a>
            </div>

            <ul class="nav">
                <li class="<?php echo !$id ? "active" : ""; ?>">
                    <a href="index.php">
                        <i class="ti-panel"></i>
                        <p>Overview</p>
                    </a>
                </li>
                <?php foreach ($list as $row) { ?>
                <li class="<?php echo $id == $row['id'] ? "active" : ""; ?>">
                    <a href="person.php?id=<?php echo $row['id'] ?>">
                        <i class="ti-user"></i>
                        <p>
                            <?php echo $row['affiliation'] ?>
                        </p>
                    </a>
                </li>
                <?php } ?>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $title ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right hide">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-panel"></i>
								<p>Stats</p>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-bell"></i>
                                    <p class="notification">5</p>
									<p>Notifications</p>
									<b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                              </ul>
                        </li>
						<li>
                            <a href="#">
								<i class="ti-settings"></i>
								<p>Settings</p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

