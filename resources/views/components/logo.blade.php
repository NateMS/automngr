@php
    $logoPath = App\Models\Setting::get('logo_path');
@endphp
@if ($logoPath && Storage::exists($logoPath))
<img src="data:image/png;base64,{{ base64_encode(Storage::get($logoPath)) }}" alt="Logo" style="max-height: 80px;">
@else
@include('components.logo-default')
@endif
