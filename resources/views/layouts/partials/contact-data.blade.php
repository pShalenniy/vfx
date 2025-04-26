@if (
    isset($contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_1]) &&
    isset($contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_2])
     )
    <div class="col-md-3">
        <p>
            {{ $contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_1] }}
        </p>
        <p>
            {{ $contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_2] }}
        </p>
    </div>
@endif
<div class="col-md-3 my-4 my-md-0">
    @if (isset($contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_EMAIL]))
        <p>{{ $contactData[\App\Models\ContentData::KEY_BLOCK_CONTACT_EMAIL] }}</p>
    @endif
</div>
