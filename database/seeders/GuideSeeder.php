<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    /**
     * Seed comprehensive, realistic student guides across major Erasmus destinations.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $authors = User::pluck('id', 'email');

        // [Título, Resumo, Categoria, Cidade, Autor, Publicado?]
        $guides = [
            // ── PORTUGAL ─────────────────────────────────────────────────────────────
            [
                'Como encontrar quarto em Lisboa: zonas recomendadas e contratos',
                'Dicas essenciais para procurar alojamento seguro em Lisboa, evitar burlas e escolher os melhores bairros universitários como Saldanha, Campo Grande e Alameda.',
                'alojamento',
                'Lisboa',
                'editor1@example.com',
                true,
                '<p>Procurar alojamento em Lisboa é um dos primeiros desafios de qualquer estudante internacional. Zonas como <strong>Alameda</strong>, <strong>Saldanha</strong>, <strong>Entrecampos</strong> e <strong>Benfica</strong> são muito populares pela proximidade às linhas de metro e aos polos da Universidade de Lisboa e NOVA.</p><p>Exige sempre contrato de arrendamento com recibos fiscais para poderes deduzir no IRS ou comprovar morada oficial, e nunca transfiras dinheiro antes de visitar o quarto pessoalmente ou via videochamada confirmada.</p>'
            ],
            [
                'Passe Navegante Metropolitano em Lisboa com desconto Sub-23',
                'Como pedir e usufruir do passe mensal de transportes em Lisboa com desconto total ou bonificado para estudantes até aos 23 anos.',
                'transportes',
                'Lisboa',
                'editor1@example.com',
                true,
                '<p>Em Lisboa, os estudantes têm direito ao passe <strong>Navegante Sub-23</strong>, que permite viajar de forma ilimitada por toda a Área Metropolitana de Lisboa (metro, autocarros Carris, comboios CP e travessias fluviais Transtejo).</p><p>Para solicitar, precisas de pedir a declaração de matrícula na secretaria da tua faculdade e dirigir-te a um balcão da Carris ou Metro de Lisboa (ex: Marquês de Pombal ou Campo Grande) com o teu documento de identificação.</p>'
            ],
            [
                'Número de Identificação Fiscal (NIF) em Portugal: guia passo a passo',
                'Como obter o teu NIF nas Finanças portuguesas para abrir conta bancária, celebrar contratos de arrendamento e aceder a serviços públicos.',
                'burocracia',
                'Lisboa',
                'admin@example.com',
                true,
                '<p>O NIF (Número de Identificação Fiscal) é obrigatório para quase tudo em Portugal: desde assinar o contrato de habitação até obter o passe de estudante ou aderir a um serviço de telecomunicações.</p><p>Como cidadão da União Europeia, podes solicitar o NIF presencialmente num balcão da Autoridade Tributária e Aduaneira (Finanças) ou Loja de Cidadão, apresentando o passaporte ou cartão de cidadão e comprovativo de morada.</p>'
            ],
            [
                'Onde comer bem e barato no Porto: tascas tradicionais e cantinas da U.Porto',
                'Guia prático para comer francesinhas, pratos do dia económicos e aproveitar a rede de cantinas dos Serviços de Ação Social da Universidade do Porto.',
                'comida',
                'Porto',
                'editor2@example.com',
                true,
                '<p>O Porto é uma cidade fantástica para quem gosta de boa gastronomia com orçamento controlado. As cantinas dos <strong>SASUP</strong> (Serviços de Ação Social da U.Porto) oferecem refeições completas (sopa, prato principal, pão e fruta) por menos de 3 euros.</p><p>Nas ruas da baixa e junto ao Polo Universitário da Asprela, existem inúmeras tascas tradicionais com o famoso "prato do dia" e claro, a emblemática Francesinha a preços muito acessíveis.</p>'
            ],
            [
                'Andante no Porto: zonas, validações e circulação no Metro e STCP',
                'Tudo sobre o sistema intermodal Andante, zonas de circulação, validações obrigatórias e passe mensal estudantil.',
                'transportes',
                'Porto',
                'editor2@example.com',
                true,
                '<p>O sistema de bilhética do Porto é o <strong>Andante</strong>. Para estudantes universitários até aos 23 anos, o passe Andante Metropolitano tem condições muito vantajosas.</p><p>Lembra-te que é estritamente obrigatório validar o cartão antes de entrar no metro ou autocarro, mesmo que tenhas passe mensal ativo, para evitar multas de fiscalização.</p>'
            ],
            [
                'Tradições académicas e vida estudantil em Coimbra: a Queima das Fitas',
                'Descobre o espírito da cidade dos estudantes por excelência, a praxe académica centenária e os festivais culturais de Coimbra.',
                'vida-social',
                'Coimbra',
                'editor2@example.com',
                true,
                '<p>Coimbra respira vida académica como nenhuma outra cidade na Europa. A sua universidade, fundada em 1290, moldou as tradições do país, desde a capa e batina até às serenatas monumentais na Sé Velha.</p><p>A <strong>Festa das Latas</strong> no início do ano letivo e a lendária <strong>Queima das Fitas</strong> em maio são experiências inesquecíveis para qualquer aluno Erasmus.</p>'
            ],
            [
                'Repúblicas de Coimbra: viver a tradição universitária comunitária',
                'O que são as Repúblicas de Coimbra, como funcionam estas casas comunitárias e como integrar a comunidade estudantil local.',
                'alojamento',
                'Coimbra',
                'editor2@example.com',
                true,
                '<p>As Repúblicas de Coimbra são residências estudantis comunitárias autogeridas, históricas e únicas no mundo. Viver ou visitar uma República é uma oportunidade única para conhecer estudantes locais e mergulhar a fundo na cultura e história cívica da cidade.</p>'
            ],

            // ── CROÁCIA ──────────────────────────────────────────────────────────────
            [
                'ZET em Zagreb: como usar os elétricos e obter o passe mensal estudantil',
                'Guia completo para andar de elétrico (tramway) em Zagreb, horários noturnos e como emitir o cartão ZET com desconto para estudantes.',
                'transportes',
                'Zagreb',
                'editor1@example.com',
                true,
                '<p>A rede de elétricos azuis da <strong>ZET (Zagrebački električni tramvaj)</strong> cobre praticamente toda a capital croata com grande frequência e pontualidade. As linhas funcionam durante todo o dia e existem linhas noturnas especiais.</p><p>Para estudantes de intercâmbio, o passe mensal estudantil custa apenas cerca de 13 a 15 euros por mês e permite viagens ilimitadas em todos os elétricos e autocarros da zona urbana.</p>'
            ],
            [
                'Registo de residência e número OIB na Croácia para alunos Erasmus',
                'Procedimentos burocráticos obrigatórios em Zagreb: emissão do OIB nas Finanças croatas e registo policial na esquadra de Petrinjska.',
                'burocracia',
                'Zagreb',
                'editor1@example.com',
                true,
                '<p>Ao chegar à Croácia para uma estadia superior a 90 dias, os cidadãos da UE devem obter o <strong>OIB (Osobni identifikacijski broj)</strong>, que é o número de identificação fiscal croata, emitido gratuitamente em qualquer repartição de Finanças (Porezna uprava).</p><p>Posteriormente, deves deslocar-te à esquadra central de polícia (MUP, habitualmente em Petrinjska ulica em Zagreb) com o contrato de alojamento e seguro de saúde para obter o certificado de residência temporária para estudantes.</p>'
            ],
            [
                'Restaurantes Menza em Zagreb: refeições completas a preços subsidiados',
                'Como usar os famosos refeitórios universitários (Menza) com o cartão X-ica e comer refeições de qualidade por menos de 2 euros.',
                'comida',
                'Zagreb',
                'editor1@example.com',
                true,
                '<p>O sistema de cantinas universitárias na Croácia (conhecido carinhosamente por <strong>Menza</strong>) é dos melhores e mais económicos de toda a Europa. Com o cartão de estudante <strong>X-ica</strong> emitido pela universidade, cada refeição completa custa apenas cerca de 1 a 2 euros.</p><p>As cantinas mais famosas em Zagreb situam-se no complexo do SC (Studentski Centar na Savska cesta) e no campus de Cvjetno Naselje.</p>'
            ],
            [
                'Vida à beira-mar em Split: praias de Bačvice e passeios pelas ilhas dálmatas',
                'Como conciliar os estudos na Universidade de Split com a vida costeira, desportos náuticos e eventos da ESN Split.',
                'vida-social',
                'Split',
                'editor1@example.com',
                true,
                '<p>Estudar em Split é combinar aulas de alto nível com um estilo de vida mediterrânico descontraído. A cidade tem um campus moderno (Campus Visoka) e uma vida social efervescente concentrada na famosa Riva e na praia de Bačvice.</p><p>A secção local da <strong>ESN Split</strong> organiza viagens regulares às ilhas de Hvar, Brač e Vis, além de visitas ao Parque Nacional de Krka.</p>'
            ],

            // ── ESPANHA ──────────────────────────────────────────────────────────────
            [
                'Abono Joven em Madrid: viagens ilimitadas por toda a comunidade a preço fixo',
                'Como pedir a Tarjeta de Transporte Público (TTP) e usufruir de viagens ilimitadas em metro, comboio cercanías e autocarros em Madrid.',
                'transportes',
                'Madrid',
                'editor2@example.com',
                true,
                '<p>Madrid tem um dos sistemas de transporte mais eficientes do mundo. Para estudantes e jovens até aos 26 anos, o <strong>Abono Transporte Joven</strong> permite viajar em todas as zonas (A até E2) por uma tarifa mensal fixa muito reduzida.</p><p>Podes agendar a emissão presencial da tua tarjeta TTP numa estação de metro central (como Sol, Atocha ou Moncloa) logo nos primeiros dias de chegada.</p>'
            ],
            [
                'Guia de bairros para viver em Madrid: Moncloa, Malasaña e Chamberí',
                'Dicas para escolher alojamento seguro e central em Madrid próximo dos principais polos universitários da Complutense e Politécnica.',
                'alojamento',
                'Madrid',
                'editor2@example.com',
                true,
                '<p>Para quem estuda na Cidade Universitária (UCM ou UPM), os bairros de <strong>Moncloa</strong>, <strong>Argüelles</strong> e <strong>Chamberí</strong> são as opções mais convenientes pela proximidade a pé das faculdades.</p><p>Se procuras um ambiente boémio, artístico e cheio de cafés com esplanadas, <strong>Malasaña</strong> e <strong>La Latina</strong> são escolhas muito populares entre a comunidade internacional de intercâmbio.</p>'
            ],
            [
                'Viver entre a praia e a cultura em Barcelona: conselhos de segurança e lazer',
                'Como aproveitar a vida universitária em Barcelona, usufruir de eventos ao ar livre e manter cuidados de segurança nos transportes públicos.',
                'vida-social',
                'Barcelona',
                'editor2@example.com',
                true,
                '<p>Barcelona oferece uma combinação única de praias mediterrânicas, património de Gaudí e prestigiadas instituições de ensino como a UB, UPF e UPC.</p><p>Para o dia a dia, lembra-te de prestar especial atenção aos teus pertences em zonas turísticas como Las Ramblas ou no metro, e aproveita as inúmeras iniciativas desportivas e culturais organizadas nas praias de Bogatell e Barceloneta.</p>'
            ],
            [
                'Valência: o destino número um do Erasmus e o encanto das Fallas',
                'Por que razão Valência recebe mais estudantes internacionais do que qualquer outra cidade e como tirar o melhor partido da experiência.',
                'vida-social',
                'Valência',
                'editor2@example.com',
                true,
                '<p>Valência é frequentemente eleita a cidade ideal para mobilidade Erasmus: clima quente o ano inteiro, praias amplas a minutos do centro, custo de vida moderado e o espetacular antigo leito do Rio Turia transformado num imenso parque verde que atravessa a cidade.</p><p>Em março, a cidade transforma-se completamente durante o festival das <strong>Fallas</strong>, Património da Humanidade, com desfiles, música e espetáculos pirotécnicos impressionantes.</p>'
            ],

            // ── ITÁLIA ───────────────────────────────────────────────────────────────
            [
                'A vida na universidade mais antiga do mundo: como funcionam as aulas e exames na UniBo',
                'Dicas práticas sobre o calendário letivo na Universidade de Bolonha, inscrições no AlmaWelcome e como preparar os exames orais italianos.',
                'universidade',
                'Bolonha',
                'admin@example.com',
                true,
                '<p>Fundada em 1088, a <strong>Università di Bologna</strong> é o berço do programa Erasmus. Em Itália, o sistema de avaliação tem particularidades que deves conhecer: muitos exames têm uma componente oral individual perante o professor e a nota máxima é de 30 pontos (com louvor "30 e lode").</p><p>Participa nas sessões de boas-vindas do gabinete de relações internacionais para entenderes como reservar salas de estudo nas históricas bibliotecas da Via Zamboni.</p>'
            ],
            [
                'Aperitivos estudantis e trattorias em Bolonha: o templo gastronómico de Itália',
                'Onde comer massa fresca autêntica (tagliatelle al ragù) e desfrutar do ritual do aperitivo italiano com os teus colegas de faculdade.',
                'comida',
                'Bolonha',
                'admin@example.com',
                true,
                '<p>Conhecida como "La Grassa" pela sua riqueza culinária, Bolonha é o paraíso gastronómico de Itália. Na zona universitária do Pratello e Piazza Verdi, o final de tarde é marcado pelo ritual do <strong>aperitivo</strong>: ao pedir uma bebida, tens acesso a um buffet de petiscos italianos incluído.</p>'
            ],
            [
                'Codice Fiscale e autorização de residência para estudantes em Roma',
                'Passo a passo burocrático em Roma: emissão do Codice Fiscale na Agenzia delle Entrate e dicas de integração na Sapienza.',
                'burocracia',
                'Roma',
                'admin@example.com',
                true,
                '<p>Viver na Cidade Eterna é uma experiência extraordinária, mas exige paciência com a administração pública italiana. O primeiro documento indispensável é o <strong>Codice Fiscale</strong>, que podes obter gratuitamente junto da Agenzia delle Entrate apresentando o teu passaporte e carta de aceitação da universidade.</p>'
            ],

            // ── FRANÇA ───────────────────────────────────────────────────────────────
            [
                'Subsídio de alojamento CAF em Paris: como candidatar-se passo a passo',
                'Como os estudantes internacionais podem receber ajuda financeira mensal para pagar a renda do quarto através da CAF francesa (APL).',
                'alojamento',
                'Paris',
                'editor1@example.com',
                true,
                '<p>França tem uma das maiores vantagens para estudantes de mobilidade: o apoio ao alojamento da <strong>CAF (Caisse d\'Allocations Familiales)</strong>. Quase todos os estudantes com contrato de arrendamento em seu nome têm direito a uma comparticipação mensal na renda (APL).</p><p>A candidatura é feita online no portal caf.fr e pode cobrir entre 100€ e 200€ por mês do custo do teu quarto.</p>'
            ],
            [
                'Passe Navigo Imagine R para estudantes em Paris e na região de Île-de-France',
                'Como viajar de metro, RER, elétrico e autocarro em Paris com tarifas subsidiadas para estudantes universitários.',
                'transportes',
                'Paris',
                'editor1@example.com',
                true,
                '<p>Para te deslocares em Paris, o passe <strong>Navigo Imagine R Étudiant</strong> oferece viagens ilimitadas por todas as 5 zonas da região de Paris por um valor anual ou mensal muito inferior aos bilhetes normais.</p><p>O passe dá acesso a todas as 14 linhas de metro, linhas de RER e autocarros urbanos da RATP.</p>'
            ],

            // ── ALEMANHA ─────────────────────────────────────────────────────────────
            [
                'Semesterticket e vida académica nas universidades de Berlim',
                'Como funciona a taxa semestral (Semesterbeitrag), o passe de transportes de Berlim e os serviços do Studierendenwerk.',
                'universidade',
                'Berlim',
                'editor2@example.com',
                true,
                '<p>Na Alemanha não existem propinas elevadas no ensino superior público, pagando-se apenas o <strong>Semesterbeitrag</strong> (taxa de solidariedade social e estudantil). Esta taxa inclui habitualmente o <strong>Semesterticket</strong>, que permite viajar gratuitamente em toda a rede de transportes de Berlim (S-Bahn, U-Bahn, elétricos e autocarros).</p>'
            ],
            [
                'Studierendenwerk em Munique: candidaturas a residências universitárias',
                'Dicas para conseguir alojamento acessível em Munique através do serviço de ação social estudantil bávaro.',
                'alojamento',
                'Munique',
                'editor2@example.com',
                true,
                '<p>Munique tem um dos mercados imobiliários mais caros da Alemanha. A forma mais económica de conseguir alojamento é candidatar-te às residências públicas do <strong>Studierendenwerk München</strong> logo que tenhas a tua carta de nomeação Erasmus em mãos.</p>'
            ],

            // ── CHÉQUIA ──────────────────────────────────────────────────────────────
            [
                'Custo de vida acessível e cantinas universitárias (Menza) em Praga',
                'Dicas práticas para gerir o teu orçamento na capital checa, refeições económicas nas residências da Charles University e câmbio de moeda.',
                'comida',
                'Praga',
                'admin@example.com',
                true,
                '<p>A República Checa utiliza a Coroa Checa (CZK). Praga é uma cidade cosmopolita fantástica com um custo de vida mais acessível do que a maioria das capitais da Europa Ocidental. As refeições nas cantinas universitárias da prestigiada Universidade Carolina são fartas e económicas.</p>'
            ],

            // ── RASCUNHOS (DRAFTS - Ocultos do público e da API) ──────────────────────
            [
                'Guia de equivalências ECTS e secretaria académica (Rascunho)',
                'Ainda em elaboração pelo editor responsável.',
                'universidade',
                'Porto',
                'editor1@example.com',
                false,
                '<p>Texto de rascunho por rever antes da publicação oficial.</p>'
            ],
            [
                'Como abrir conta bancária espanhola sem comissões (Rascunho)',
                'Artigo em revisão pelo autor para alunos em Madrid.',
                'burocracia',
                'Madrid',
                'editor2@example.com',
                false,
                '<p>Em preparação com informação de bancos digitais e tradicionais.</p>'
            ],
            [
                'Excursões de fim de semana aos Lagos de Plitvice (Rascunho)',
                'Rascunho com rotas de autocarro a partir de Zagreb.',
                'vida-social',
                'Zagreb',
                'admin@example.com',
                false,
                '<p>Conteúdo de rascunho temporário reservado a administradores.</p>'
            ],
        ];

        foreach ($guides as [$title, $summary, $category, $city, $author, $published, $content]) {
            $slug = Str::slug($title);
            $counter = 1;
            while (Guide::where('slug', $slug)->exists()) {
                $slug = Str::slug($title) . '-' . $counter;
                $counter++;
            }

            Guide::forceCreate([
                'title' => $title,
                'slug' => $slug,
                'summary' => $summary,
                'content' => $content,
                'category_id' => $categories[$category],
                'author_id' => $authors[$author],
                'city' => $city,
                'status' => $published ? 'published' : 'draft',
                'published_at' => $published ? now() : null,
            ]);
        }
    }
}
