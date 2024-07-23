<div class="top-bar -mx-4 px-4 md:mx-0 md:px-0">
    <!-- BEGIN: Breadcrumb -->
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active" aria-current="page">
            <?php $link = ''; ?>
                @for ($i = 1; $i <= count(Request::segments()); $i++)
                    @if ($i < count(Request::segments()) & $i > 0)
                    <?php $link .= '/' . Request::segment($i); ?>
                        <a href="<?= $link ?>">{{ ucwords(str_replace('_',' ',Request::segment($i))) }}</a>
                    @else {{ ucwords(str_replace('_', ' ', Request::segment($i))) }}
                    @endif
                @endfor
            </li>
        </ol>
    </nav>
    <!-- END: Breadcrumb -->
    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
            <img alt="Siskupala Web" class="rounded-full" src="{{ asset('/images/orang1.svg')}}">
        </div>
        <div class="dropdown-menu w-56">
            <ul class="dropdown-content bg-primary text-white">
                <li class="p-2">
                    <div class="font-medium">Siskupala</div>
                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">admin@mail.com</div>
                    <li>
                        <a href="{{route('logout')}}" class="dropdown-item hover:bg-white/5"> <i data-feather="log-out" class="w-4 h-4 mr-2"></i> Logout </a>
                    </li>
            </ul>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
