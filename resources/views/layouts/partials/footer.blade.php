<footer class="footer bg-secondary text-white">
    <div class="container">
        <div class="row">
            <div class="col-auto mr-auto">
                <a class="navbar-brand mr-auto" href="#">
                    @if (isset($logoData[\App\Models\ContentData::KEY_BLOCK_LOGO]))
                        <img src="{{ $logoData[\App\Models\ContentData::KEY_BLOCK_LOGO] }}" alt="" height="80px">
                    @endif
                </a>
            </div>
            @guest
                <div class="col-auto">
                    <a href="{{ URL::route('sign-up.view') }}" class="btn btn-primary text-blue">
                        @lang('common.button.register')
                    </a>
                </div>
            @endguest
        </div>
        <div class="row">
            <div class="col-auto mr-auto">
                <a href="#">
                    @lang('common.contact')
                </a>
            </div>
            <div class="col-auto">
                <a href="#">
                    @lang('common.follow')
                </a>
            </div>
        </div>

        <div class="border-top">
            <div class="row">
                @include('layouts.partials.contact-data')
                <div class="col">
                    <p>
                        <a href="{{ URL::route('terms-and-conditions.page') }}">
                            @lang('common.link.terms_and_conditions')
                        </a>
                    </p>
                    <p>
                        <a href="{{ URL::route('privacy-policy.page') }}">
                            @lang('common.link.privacy_policy')
                        </a>
                    </p>
                </div>
                <div class="col-auto ml-auto">
                    @include('layouts.partials.social-media-links')
                </div>
            </div>
        </div>
    </div>
</footer>
