
<ul id="navbar" style="z-index:1000">

    <li class="big-screens"><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

    @if (Auth::guest())
        <li>
            <a href="/auth/login">Login</a>
        </li>
        <li>
            <a href="/auth/register">Register</a>
        </li>

    @else

        {{--<li id="menu-dropdown" class="dropdown">--}}
            {{--<a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>--}}
            {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a href="/auth/logout">logout</a></li>--}}
            {{--</ul>--}}
        {{--</li>--}}

        <!-- <li class="desktop"><a ng-click="changeTab('entries')" href="">home</a></li>
        <li class="iphone"><a ng-click="changeTab('food_entries')" href="">food log</a></li>
        <li class="iphone"><a ng-click="changeTab('exercise_entries')" href="">exercise log</a></li> -->

        <li><a v-link="{path: '/'}" href="#" class="fa fa-home"></a></li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-cutlery"></span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/foods'}" href="#">Foods</a></li>
                <li><a v-link="{path: '/recipes'}" href="#">Recipes</a></li>
                <li><a v-link="{path: '/food-units'}" href="#v-link="{path: '/exercises'}" ">Units</a></li>
            </ul>
        </li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-heart"></span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/exercises'}" href="#">Exercises</a></li>
                <li><a v-link="{path: '/series'}" href="#">Series</a></li>
                <li><a v-link="{path: '/exercise-units'}" href="#">Units</a></li>
            </ul>
        </li>

        <li>
            <a v-link="{path: '/journal'}" href="#" class="fa fa-pencil"></a>
        </li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-clock-o"></span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/timers'}" href="#">Timers</a></li>
                <li><a v-link="{path: '/activities'}" href="#">Activities</a></li>
                {{--<li><a v-link="{path: '/graphs'}" href="#">Graphs</a></li>--}}
            </ul>
        </li>

        @include('main.shared.header.user')

    @endif

</ul>

