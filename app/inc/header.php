
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- <a class="navbar-brand fa fa-usd" href="#"></a>-->
        </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li id="menu-dropdown" class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="credits.php">credits</a></li>
                    </ul>
                </li>

                <li><a ng-click="changeTab('entries')" href="<?php //echo $home;?>">home</a></li>
                <li><a ng-click="changeTab('foods')" href="<?php //echo $php . '/foods.php';?>">my foods</a></li>
                <li><a ng-click="changeTab('exercises')" href="<?php //echo $php . '/exercises.php';?>">my exercises</a></li>
                <li><a ng-click="changeTab('units')" href="<?php //echo $php . '/units.php';?>">my units</a></li>
            </ul><!-- .navbar-nav -->

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Auth::user()->name; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/auth/logout">Logout</a></li>
                    </ul>

                </li>
              
                <li><a href="#" id="search_button" class="location_button fa fa-search"></a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

