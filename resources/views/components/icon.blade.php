@props([
'name' => 'circle',
'color' => 'currentColor',
'size' => '16',
'stroke' => '2',
])

@php
$paths = [
'layers' => '
<path d="M12 2L2 7l10 5 10-5-10-5z" stroke="%c" stroke-width="%s" stroke-linejoin="round" />
<path d="M2 17l10 5 10-5" stroke="%c" stroke-width="%s" stroke-linejoin="round" />
<path d="M2 12l10 5 10-5" stroke="%c" stroke-width="%s" stroke-linejoin="round" />',
'chevron-right' => '
<path d="M9 18l6-6-6-6" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'zap' => '
<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'clock' => '
<circle cx="12" cy="12" r="10" stroke="%c" stroke-width="%s" />
<path d="M12 8v4l3 3" stroke="%c" stroke-width="%s" stroke-linecap="round" />',
'check-circle' => '
<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />
<path d="M22 4L12 14.01l-3-3" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'file-text' => '
<path d="M9 12h6M9 16h6M9 8h6M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" stroke="%c" stroke-width="%s" stroke-linecap="round" />',
'star' => '
<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke="%c" stroke-width="%s" stroke-linejoin="round" />',
'mail' => '
<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="%c" stroke-width="%s" />
<polyline points="22,6 12,13 2,6" stroke="%c" stroke-width="%s" />',
'instagram' => '
<rect x="2" y="2" width="20" height="20" rx="5" stroke="%c" stroke-width="%s" />
<path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke="%c" stroke-width="%s" />
<line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="%c" stroke-width="%s" stroke-linecap="round" />',
'twitter' => '
<path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'help-circle' => '
<circle cx="12" cy="12" r="10" stroke="%c" stroke-width="%s" />
<path d="M9 9a3 3 0 015.12 2.13c0 1.74-1.77 2.6-2.62 3.37V16" stroke="%c" stroke-width="%s" stroke-linecap="round" />
<circle cx="12" cy="19" r="0.5" fill="%c" stroke="%c" />',
'plus' => '
<path d="M12 5v14M5 12h14" stroke="%c" stroke-width="%s" stroke-linecap="round" />',
'message' => '
<path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'align-left' => '
<path d="M9 12h6M9 16h6M9 8h6" stroke="%c" stroke-width="%s" stroke-linecap="round" />',
'check' => '
<path d="M5 13l4 4L19 7" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'send' => '
<path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
'user' => '
<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="%c" stroke-width="%s" stroke-linecap="round" />
<circle cx="12" cy="7" r="4" stroke="%c" stroke-width="%s" />',
'chevron-left' => '
<path d="M15 18l-6-6 6-6" stroke="%c" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" />',
];

$template = $paths[$name] ?? '
<circle cx="12" cy="12" r="10" stroke="%c" stroke-width="%s" />';
$inner = str_replace(['%c', '%s'], [$color, $stroke], $template);
@endphp

<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" {!! $attributes !!}>
    {!! $inner !!}
</svg>