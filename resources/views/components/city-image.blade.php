@props([
    'city'   => null,
    'alt'    => null,
    'class'  => 'w-full h-full object-cover',
    'width'  => 800,
    'height' => 400,
])

@php
    /*
     * Mapa cidade → seed do Picsum Photos (picsum.photos/seed/{seed}/{w}/{h})
     * Cada seed produz sempre a mesma imagem — sem API key, sem custos.
     */
    $seeds = [
        // Portugal
        'Lisboa'    => 'lisboa',
        'Porto'     => 'porto',
        'Coimbra'   => 'coimbra',
        'Braga'     => 'braga',
        // Croácia
        'Zagreb'    => 'zagreb',
        'Split'     => 'split',
        'Dubrovnik' => 'dubrovnik',
        // Espanha
        'Madrid'    => 'madrid',
        'Barcelona' => 'barcelona',
        'Valência'  => 'valencia',
        'Valencia'  => 'valencia',
        // Itália
        'Bolonha'   => 'bologna',
        'Roma'      => 'roma',
        // França
        'Paris'     => 'paris',
        'Lyon'      => 'lyon',
        // Alemanha
        'Berlim'    => 'berlin',
        'Munique'   => 'munich',
        // República Checa
        'Praga'     => 'prague',
    ];

    $seed    = $seeds[$city] ?? 'erasmus';
    $src     = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    $altText = $alt ?? ($city ? "Photo of {$city}" : 'Erasmus destination');
@endphp

<img
    src="{{ $src }}"
    alt="{{ $altText }}"
    class="{{ $class }}"
    loading="lazy"
>
