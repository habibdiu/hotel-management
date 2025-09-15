@props([
    'content',
    'previewWords' => 5
])

@php
    $plainText = strip_tags($content);
    $words = explode(' ', $plainText);
    $shortText = implode(' ', array_slice($words, 0, $previewWords));
@endphp

<div class="hover-full-text-wrapper" style="position: relative; display: inline-block;">
    <span class="text-primary" style="cursor: pointer;">
        {!! $shortText !!}{{ count($words) > $previewWords ? '...' : '' }}
    </span>

    <div class="hover-full-text" style="
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 400px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        background: white;
        border: 1px solid #ccc;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 999;
    ">
        {!! $content !!}
    </div>
</div>