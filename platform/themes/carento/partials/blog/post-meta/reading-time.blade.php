@php($timeReading = $post->time_reading)

@if ($timeReading > 0)
    <span class="post-time">{{ $timeReading == 1 ? __('1 minute read') : __(':count minutes read', ['count' => $timeReading]) }}</span>
@endif
