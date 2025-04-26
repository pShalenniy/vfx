<header class="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand mr-auto" href="{{ URL::route('home.page') }}">
                @if (isset($logoData[\App\Models\ContentData::KEY_BLOCK_LOGO]))
                    <img src="{{ $logoData[\App\Models\ContentData::KEY_BLOCK_LOGO] }}" alt="" height="80px">
                @endif
            </a>
            <button class="navbar-toggler collapsed" type="button" v-b-toggle="'navbarSupportedContent'">
                <span class="navbar-toggler-icon"></span>
            </button>

            <b-collapse class="navbar-collapse" id="navbarSupportedContent">
                @include('layouts.partials.social-media-links')
                @php
                    /** @var \App\Models\User $user */
                    $user = Illuminate\Support\Facades\Auth::user();
                @endphp
                <ul id="top-navbar-menu" class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a id="home-link" class="nav-link" href="{{ URL::route('home.page') }}">
                            @lang('common.header.menu.home')
                        </a>
                    </li>
                    @if (null !== $user?->getAttribute('email_verified_at'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ URL::route('candidate.page.list') }}">
                                @lang('common.header.menu.search')
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::route('about-us.page') }}">
                            @lang('common.header.menu.about_us')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::route('contact-us.view') }}">
                            @lang('common.header.menu.contact_us')
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ URL::route('login.view') }}">
                                @lang('common.header.menu.login')
                            </a>
                        </li>
                    @endguest
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ URL::route('candidate.login.view') }}">
                                @lang('common.header.menu.candidate_login')
                            </a>
                        </li>
                    @endguest
                    @if (null !== $user)
                        @if ($user->getAttribute('role_id'))
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/candidates">
                                    @lang('common.header.menu.admin_part')
                                </a>
                            </li>
                        @endif
                        @if (null !== $user?->getAttribute('email_verified_at'))
                            <li class="nav-item">
                                <a id="account-settings-link" class="nav-link" href="{{ URL::route('user.show') }}">
                                    @lang('common.header.menu.account_settings')
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a id="account-settings-link" class="nav-link" href="{{ URL::route('candidate.account-settings.show') }}">
                                    @lang('common.header.menu.account_settings')
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ URL::route('common.logout') }}">
                                @csrf
                                <button type="submit" class="nav-link w-100">
                                    @lang('common.header.menu.logout')
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </b-collapse>
        </nav>
    </div>
</header>
