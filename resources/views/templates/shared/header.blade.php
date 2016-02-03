
<ul id="navbar" style="z-index:1000">

    <li><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

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

        <li><a href="/">entries</a></li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span>foods</span>
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
                <span>exercises</span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/exercises'}" href="#">Exercises</a></li>
                <li><a v-link="{path: '/series'}" href="#">Series</a></li>
                <li><a v-link="{path: '/exercise-units'}" href="#">Units</a></li>
            </ul>
        </li>

        <li><a href="/journal">journal</a></li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span>timers</span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/timers/timers'}" href="#">Timers</a></li>
                <li><a v-link="{path: '/timers/activities'}" href="#">Activities</a></li>
                <li><a v-link="{path: '/timers/graphs'}" href="#">Graphs</a></li>
            </ul>
        </li>

        @include('templates.header.user')

    @endif

</ul>

