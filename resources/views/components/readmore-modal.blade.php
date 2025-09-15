@props([
    'id',
    'content',     
    'previewWords'
])

@php
    $plainText = strip_tags($content);
    $words = explode(' ', $plainText);
    $shortText = implode(' ', array_slice($words, 0, $previewWords));
@endphp

<a class="readmore" data-bs-toggle="modal" data-bs-target="#{{ $id }}">
    {!! $shortText !!}{{ count($words) > $previewWords ? '...' : '' }}
</a>



<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        {!! $content !!}
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
categ      </div> --}}
    </div>
  </div>
</div>