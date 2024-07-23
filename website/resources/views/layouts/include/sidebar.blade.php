<nav class="side-nav">
                <a href="" class="intro-x flex items-center pl-5 pt-4 mt-3">
                    <img alt="Tinker Tailwind HTML Admin Template" class="w-6" src="{{asset('/images/nila.svg')}}">
                    <span class="hidden xl:block text-white text-lg ml-3"> SISKUPALA </span>
                </a>
                <div class="side-nav__devider my-6"></div>
                <ul>
                    <li>
                        <a href="{{route('dashboard')}}" class="side-menu {{(request()->is('dashboard')) ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                            <div class="side-menu__title">
                                Dashboard
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('controller')}}" class="side-menu {{(request()->is('controller')) ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="aperture"></i> </div>
                            <div class="side-menu__title"> Controller </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('data')}}" class="side-menu {{(request()->is('data')) ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="trello"></i> </div>
                            <div class="side-menu__title">
                                Data
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('logout')}}" class="side-menu {{(request()->is('logout')) ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="log-out"></i> </div>
                            <div class="side-menu__title">
                                Logout
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
