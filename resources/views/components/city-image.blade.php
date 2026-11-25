@props([
    'city'   => null,
    'alt'    => null,
    'class'  => 'w-full h-full object-cover',
    'width'  => 800,
    'height' => 400,
])

@php
    /*
     * Mapa cidade → foto do monumento/landmark via Unsplash (IDs de fotos reais).
     * Formato: https://images.unsplash.com/photo-{ID}?w={w}&h={h}&fit=crop&q=80
     *
     * Se a foto Unsplash falhar (rede, ID inválido), o onerror redireciona
     * para um fallback no Picsum Photos que sempre funciona.
     */
    $landmarks = [
        // Portugal
        'Lisboa'    => '1555881400-74d7acaacd8b',  // Vista de Lisboa / Belém
        'Porto'     => '1587555897-dac3ba3e8b17',  // Ponte D. Luís / Ribeira
        'Coimbra'   => '1534551767192-78b8dd45b51b', // Universidade de Coimbra
        'Braga'     => '1593006685-7dee421b7c74',  // Braga / Bom Jesus (fallback Picsum)

        // Croácia
        'Zagreb'    => '1549996088-4d0a2b7f3d6a',  // Zagreb centro histórico
        'Split'     => '1621602862779-f9d6d80a9af4', // Palácio de Diocleciano
        'Dubrovnik' => '1601297023744-9fa1dcbc4a68', // Muralhas de Dubrovnik

        // Espanha
        'Madrid'    => '1539037116277-4db20889f2d4', // Praça Mayor / Madrid
        'Barcelona' => '1583422409516-2895a77efded', // Sagrada Família
        'Valência'  => '1558618666-fcd25c85cd64',  // Cidade das Artes e Ciências
        'Valencia'  => '1558618666-fcd25c85cd64',

        // Itália
        'Bolonha'   => '1558618047-3c8c76ca7d13',  // Bolonha / Due Torri
        'Roma'      => '1552832230-c0197dd311b5',  // Coliseu de Roma

        // França
        'Paris'     => '1502602898657-3e91760cbb34', // Torre Eiffel
        'Lyon'      => '1555834145-11e49f88e4e5',  // Lyon / Vieux-Lyon

        // Alemanha
        'Berlim'    => '1560969184-10fe8719e047',  // Porta de Brandemburgo
        'Munique'   => '1529543544282-ea669407fca3', // Marienplatz / Munique

        // República Checa
        'Praga'     => '1541849546-216549ae216d',  // Cidade Velha de Praga
    ];

    // Fallback Picsum por cidade (seeds descritivas) — usado se Unsplash falhar
    $picsumFallbacks = [
        'Lisboa'    => 'lisboa-castle',
        'Porto'     => 'porto-bridge',
        'Coimbra'   => 'coimbra-university',
        'Braga'     => 'braga-portugal',
        'Zagreb'    => 'zagreb-croatia',
        'Split'     => 'split-dalmatia',
        'Dubrovnik' => 'dubrovnik-walls',
        'Madrid'    => 'madrid-spain',
        'Barcelona' => 'barcelona-gaudi',
        'Valência'  => 'valencia-spain',
        'Valencia'  => 'valencia-spain',
        'Bolonha'   => 'bologna-italy',
        'Roma'      => 'rome-colosseum',
        'Paris'     => 'paris-eiffel',
        'Lyon'      => 'lyon-france',
        'Berlim'    => 'berlin-gate',
        'Munique'   => 'munich-germany',
        'Praga'     => 'prague-bridge',
    ];

    if ($city && isset($landmarks[$city])) {
        $photoId     = $landmarks[$city];
        $src         = "https://images.unsplash.com/photo-{$photoId}?w={$width}&h={$height}&fit=crop&q=80";
        $fallbackSeed = $picsumFallbacks[$city] ?? 'european-city';
        $fallback    = "https://picsum.photos/seed/{$fallbackSeed}/{$width}/{$height}";
    } else {
        $seed     = $city ? strtolower(str_replace([' ', 'ã', 'â', 'é', 'ê', 'ó', 'ú', 'ç'], ['-', 'a', 'a', 'e', 'e', 'o', 'u', 'c'], $city)) : 'erasmus';
        $src      = "https://picsum.photos/seed/{$seed}-city/{$width}/{$height}";
        $fallback = "https://picsum.photos/seed/european-city/{$width}/{$height}";
    }

    $altText = $alt ?? ($city ? "{$city}" : 'Erasmus destination');
@endphp

<img
    src="{{ $src }}"
    alt="{{ $altText }}"
    class="{{ $class }}"
    loading="lazy"
    onerror="this.onerror=null; this.src='{{ $fallback }}';"
>
