@props(['success', 'message', 'toastr'])

@if ($success)
    <div {{ $attributes->merge(['class' => 'd-none toastr']) }} id="toastr" data-toastr="{{ $toastr }}">{{ $message }}</div>
@endif
