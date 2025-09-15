@if($message)
@php
    $alertId = 'alert-' . uniqid();
@endphp

<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert" id="{{ $alertId }}">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById("{{ $alertId }}");
    if (alertBox) {
        setTimeout(function() {
            alertBox.classList.remove('show');
            alertBox.classList.add('fade');
            setTimeout(function() {
                alertBox.remove();
            }, 300);
        }, 500);
    }
});
</script>
@endif
