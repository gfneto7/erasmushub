<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Guide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountryAndCitySeeder extends Seeder
{
    /**
     * Seed EU countries and prominent Erasmus student destinations.
     */
    public function run(): void
    {
        $destinations = [
            [
                'country' => 'Portugal',
                'code' => 'PT',
                'flag' => '🇵🇹',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Lisboa',
                        'lat' => 38.7223,
                        'lon' => -9.1393,
                        'university' => 'Universidade de Lisboa (ULisboa) & NOVA',
                        'cost' => 'Medium',
                        'desc' => 'Capital vibrante, clima ameno durante todo o ano, excelente vida noturna no Bairro Alto e grande comunidade internacional de estudantes.',
                    ],
                    [
                        'name' => 'Porto',
                        'lat' => 41.1579,
                        'lon' => -8.6291,
                        'university' => 'Universidade do Porto (U.Porto)',
                        'cost' => 'Medium',
                        'desc' => 'Cidade histórica e acolhedora, famosa pelo vinho do Porto, pontes icónicas e ambiente académico muito caloroso.',
                    ],
                    [
                        'name' => 'Coimbra',
                        'lat' => 40.2033,
                        'lon' => -8.4103,
                        'university' => 'Universidade de Coimbra (UC)',
                        'cost' => 'Low',
                        'desc' => 'Uma das universidades mais antigas do mundo, repleta de tradições académicas centenárias e custo de vida acessível para estudantes.',
                    ],
                    [
                        'name' => 'Braga',
                        'lat' => 41.5454,
                        'lon' => -8.4265,
                        'university' => 'Universidade do Minho (UMinho)',
                        'cost' => 'Low',
                        'desc' => 'Cidade jovem e dinâmica no norte de Portugal, polo tecnológico crescente com vida cultural ativa.',
                    ],
                ],
            ],
            [
                'country' => 'Croácia',
                'code' => 'HR',
                'flag' => '🇭🇷',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Zagreb',
                        'lat' => 45.8150,
                        'lon' => 15.9819,
                        'university' => 'Sveučilište u Zagrebu (UniZagreb)',
                        'cost' => 'Medium',
                        'desc' => 'Capital da Croácia com rica arquitetura centro-europeia, imensos cafés, parques verdes e excelente localização para viajar pelos Balcãs.',
                    ],
                    [
                        'name' => 'Split',
                        'lat' => 43.5081,
                        'lon' => 16.4402,
                        'university' => 'Sveučilište u Splitu (UniSplit)',
                        'cost' => 'Medium',
                        'desc' => 'Destino mediterrânico de sonho na Costa Dálmata, construído em redor do Palácio de Diocleciano e praias de água cristalina.',
                    ],
                    [
                        'name' => 'Dubrovnik',
                        'lat' => 42.6507,
                        'lon' => 18.0944,
                        'university' => 'Sveučilište u Dubrovniku',
                        'cost' => 'High',
                        'desc' => 'A Pérola do Adriático, famosa pelas suas imponentes muralhas de pedra e atmosfera cinematográfica.',
                    ],
                ],
            ],
            [
                'country' => 'Espanha',
                'code' => 'ES',
                'flag' => '🇪🇸',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Madrid',
                        'lat' => 40.4168,
                        'lon' => -3.7038,
                        'university' => 'Universidad Complutense de Madrid (UCM)',
                        'cost' => 'High',
                        'desc' => 'Metrópole energética com museus de renome mundial, tapas sem fim e uma das vidas noturnas mais ativas da Europa.',
                    ],
                    [
                        'name' => 'Barcelona',
                        'lat' => 41.3851,
                        'lon' => 2.1734,
                        'university' => 'Universitat de Barcelona (UB) & UPF',
                        'cost' => 'High',
                        'desc' => 'Arquitetura modernista de Gaudí, mar, montanha e uma atmosfera cosmopolita irresistível para estudantes.',
                    ],
                    [
                        'name' => 'Valência',
                        'lat' => 39.4699,
                        'lon' => -0.3763,
                        'university' => 'Universitat de València (UV) & UPV',
                        'cost' => 'Medium',
                        'desc' => 'O destino Erasmus número 1 de Espanha, famoso pelo clima soalheiro, praias amplas e custo de vida moderado.',
                    ],
                ],
            ],
            [
                'country' => 'Itália',
                'code' => 'IT',
                'flag' => '🇮🇹',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Bolonha',
                        'lat' => 44.4949,
                        'lon' => 11.3426,
                        'university' => 'Università di Bologna (UniBo)',
                        'cost' => 'Medium',
                        'desc' => 'O berço histórico do programa Erasmus e a universidade mais antiga do mundo ocidental. A verdadeira capital estudantil de Itália.',
                    ],
                    [
                        'name' => 'Roma',
                        'lat' => 41.9028,
                        'lon' => 12.4964,
                        'university' => 'Sapienza Università di Roma',
                        'cost' => 'High',
                        'desc' => 'A Cidade Eterna: viver num museu a céu aberto rodeado de milénios de história e culinária incomparável.',
                    ],
                ],
            ],
            [
                'country' => 'França',
                'code' => 'FR',
                'flag' => '🇫🇷',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Paris',
                        'lat' => 48.8566,
                        'lon' => 2.3522,
                        'university' => 'Sorbonne Université',
                        'cost' => 'High',
                        'desc' => 'Cidade Luz, centro global de arte, gastronomia e moda, com prestigiadas instituições de ensino superior.',
                    ],
                    [
                        'name' => 'Lyon',
                        'lat' => 45.7640,
                        'lon' => 4.8357,
                        'university' => 'Université de Lyon',
                        'cost' => 'Medium',
                        'desc' => 'Capital gastronómica de França, com grande tradição universitária e qualidade de vida excecional.',
                    ],
                ],
            ],
            [
                'country' => 'Alemanha',
                'code' => 'DE',
                'flag' => '🇩🇪',
                'currency' => 'EUR',
                'cities' => [
                    [
                        'name' => 'Berlim',
                        'lat' => 52.5200,
                        'lon' => 13.4050,
                        'university' => 'Humboldt-Universität zu Berlin & FU Berlin',
                        'cost' => 'Medium',
                        'desc' => 'Capital multicultural, alternativa e histórica, com vibrante cena criativa e descontraída para estudantes.',
                    ],
                    [
                        'name' => 'Munique',
                        'lat' => 48.1351,
                        'lon' => 11.5820,
                        'university' => 'LMU München & TUM',
                        'cost' => 'High',
                        'desc' => 'Coração da Baviera, excelência académica de nível mundial, parques deslumbrantes e proximidade aos Alpes.',
                    ],
                ],
            ],
            [
                'country' => 'Chéquia',
                'code' => 'CZ',
                'flag' => '🇨🇿',
                'currency' => 'CZK',
                'cities' => [
                    [
                        'name' => 'Praga',
                        'lat' => 50.0755,
                        'lon' => 14.4378,
                        'university' => 'Univerzita Karlova (Charles University)',
                        'cost' => 'Medium',
                        'desc' => 'A Cidade das Cem Torres, com atmosfera mágica de conto de fadas e uma das comunidades Erasmus mais ativas e acessíveis da Europa Central.',
                    ],
                ],
            ],
        ];

        foreach ($destinations as $dest) {
            $country = Country::firstOrCreate(
                ['code' => $dest['code']],
                [
                    'name' => $dest['country'],
                    'flag_emoji' => $dest['flag'],
                    'currency' => $dest['currency'],
                ]
            );

            foreach ($dest['cities'] as $cityData) {
                $city = City::firstOrCreate(
                    ['slug' => Str::slug($cityData['name'])],
                    [
                        'country_id' => $country->id,
                        'name' => $cityData['name'],
                        'latitude' => $cityData['lat'],
                        'longitude' => $cityData['lon'],
                        'main_university' => $cityData['university'],
                        'description' => $cityData['desc'],
                        'cost_of_living' => $cityData['cost'],
                    ]
                );

                // Associate existing guides that have this city name
                Guide::where('city', $cityData['name'])
                    ->orWhere('city', strtolower($cityData['name']))
                    ->update(['city_id' => $city->id]);
            }
        }
    }
}
