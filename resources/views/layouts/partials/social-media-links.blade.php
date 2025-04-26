<ul class="navbar-socials ml-auto">
    @if (isset($socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_INSTAGRAM]))
        <li class="nav-item">
            <a class="nav-link" href="{{ $socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_INSTAGRAM] }}">
                <img src="/images/client/icons/instagram.svg" alt="" />
            </a>
        </li>
    @endif
    @if (isset($socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_LINKEDIN]))
        <li class="nav-item">
            <a class="nav-link" href="{{ $socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_LINKEDIN] }}">
                <img src="/images/client/icons/linkedin.svg" alt="" />
            </a>
        </li>
    @endif
    @if (isset($socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_TWITTER]))
        <li class="nav-item">
            <a class="nav-link" href="{{ $socialData[\App\Models\ContentData::KEY_BLOCK_SOCIAL_TWITTER] }}">
                <img src="/images/client/icons/twitter.svg" alt="" />
            </a>
        </li>
    @endif
</ul>
