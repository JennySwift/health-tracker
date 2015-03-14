
<ul id="navbar">
    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="credits.php">credits</a></li>
        </ul>
    </li>
    
    <li><a ng-click="changeTab('entries')" href="">home</a></li>
    <li>
        <a ng-click="changeTab('foods')" href="">
            <img src="img/apple.svg" alt="" width="14">
        </a>
    </li>
    <li><a ng-click="changeTab('exercises')" href="" class="fa fa-heart"></a></li>
    <li><a ng-click="changeTab('units')" href="">units</a></li>
    
    <li>branch:master</li>
    
    <li class="dropdown">
    
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Auth::user()->name; ?><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="/auth/logout">Logout</a></li>
        </ul>
    
    </li>
    
    <li><a href="#" id="search_button" class="location_button fa fa-search"></a></li>
</ul>

