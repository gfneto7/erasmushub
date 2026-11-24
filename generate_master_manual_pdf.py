# -*- coding: utf-8 -*-
"""
Gerador do Manual Técnico Explicativo Completo do Projeto ErasmusHub.
Documento exaustivo e aprofundado, com explicação passo a passo de código,
arquitetura, decisões de engenharia e roteiro para defesa académica.
"""

import os
import sys
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.pdfgen import canvas

class DetailedNumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super(DetailedNumberedCanvas, self).__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super(DetailedNumberedCanvas, self).showPage()
        super(DetailedNumberedCanvas, self).save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748B"))

        # Cabeçalho da página 2 em diante
        if self._pageNumber > 1:
            self.drawString(36, 810, "ErasmusHub — Manual Técnico e Explicativo do Projeto")
            self.drawRightString(559, 810, "Design Web Avançado de Serviços Web (NTPWS)")
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(36, 804, 559, 804)

        # Rodapé em todas as páginas
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(36, 32, 559, 32)
        self.drawString(36, 22, "ErasmusHub | Código, Arquitetura e Guia Passo a Passo de Estudo")
        page_str = f"Página {self._pageNumber} de {page_count}"
        self.drawRightString(559, 22, page_str)

        self.restoreState()


def build_manual_pdf(filename="c:/laragon/www/erasmushub/ErasmusHub_Manual_Tecnico_Explicativo.pdf"):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=36,
        rightMargin=36,
        topMargin=46,
        bottomMargin=46
    )

    styles = getSampleStyleSheet()

    # Cores de paleta moderna
    c_primary = colors.HexColor("#1E3A8A")     # Azul Marinho Escuro
    c_secondary = colors.HexColor("#0369A1")   # Azul Oceano
    c_dark = colors.HexColor("#0F172A")        # Slate 900
    c_gray_bg = colors.HexColor("#F8FAFC")     # Slate 50
    c_code_bg = colors.HexColor("#0F172A")     # Terminal Dark
    c_code_fg = colors.HexColor("#38BDF8")     # Sky Blue code
    c_border = colors.HexColor("#E2E8F0")      # Slate 200
    c_why_bg = colors.HexColor("#FEF3C7")      # Amber 100
    c_why_border = colors.HexColor("#F59E0B")  # Amber 500

    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=22,
        leading=26,
        textColor=c_primary,
        spaceAfter=4
    )

    subtitle_style = ParagraphStyle(
        'DocSubTitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=11,
        leading=15,
        textColor=c_secondary,
        spaceAfter=14
    )

    h1_style = ParagraphStyle(
        'H1',
        parent=styles['Heading1'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=17,
        textColor=c_primary,
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )

    h2_style = ParagraphStyle(
        'H2',
        parent=styles['Heading2'],
        fontName='Helvetica-Bold',
        fontSize=10.5,
        leading=14,
        textColor=c_secondary,
        spaceBefore=10,
        spaceAfter=4,
        keepWithNext=True
    )

    body_style = ParagraphStyle(
        'Body',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12.5,
        textColor=c_dark,
        spaceAfter=5
    )

    code_style = ParagraphStyle(
        'Code',
        parent=styles['Normal'],
        fontName='Courier',
        fontSize=7,
        leading=9.5,
        textColor=c_code_fg
    )

    why_style = ParagraphStyle(
        'WhyStyle',
        parent=styles['Normal'],
        fontName='Helvetica-Oblique',
        fontSize=8,
        leading=11.5,
        textColor=colors.HexColor("#78350F")
    )

    tbl_header = ParagraphStyle('TH', parent=styles['Normal'], fontName='Helvetica-Bold', fontSize=8, leading=10, textColor=colors.white)
    tbl_cell = ParagraphStyle('TC', parent=styles['Normal'], fontName='Helvetica', fontSize=7.5, leading=10, textColor=c_dark)
    tbl_cell_bold = ParagraphStyle('TCB', parent=tbl_cell, fontName='Helvetica-Bold')

    story = []

    # =========================================================================
    # CAPA DO MANUAL
    # =========================================================================
    story.append(Paragraph("🎓 ErasmusHub — Manual Técnico & Explicativo Passo a Passo", title_style))
    story.append(Paragraph("Guia Integral de Engenharia de Software: Arquitetura MVC, Base de Dados, Código-Fonte, APIs e Defesa Académica", subtitle_style))
    story.append(HRFlowable(width="100%", thickness=2, color=c_secondary, spaceBefore=0, spaceAfter=10))

    meta_table = [
        [
            Paragraph("<b>Unidade Curricular:</b> Design Web Avançado de Serviços Web (NTPWS)", tbl_cell),
            Paragraph("<b>Framework:</b> Laravel 11/12 (PHP 8.5.4) &bull; MySQL (Laragon)", tbl_cell)
        ],
        [
            Paragraph("<b>Repositório GitHub:</b> https://github.com/gfneto7/erasmushub", tbl_cell),
            Paragraph("<b>Padrão Arquitetural:</b> MVC + Service Layer + Policy Authorization", tbl_cell)
        ],
        [
            Paragraph("<b>Estado dos Testes:</b> 46 testes automatizados / 164 asserções (100% OK)", tbl_cell),
            Paragraph("<b>Público-Alvo:</b> Estudante em mobilidade internacional Erasmus+", tbl_cell)
        ]
    ]
    t_meta = Table(meta_table, colWidths=[260, 263])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F0F9FF")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#BAE6FD")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E0F2FE")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    # =========================================================================
    # CAPÍTULO 1: CICLO DE VIDA E FLUXO DA APLICAÇÃO
    # =========================================================================
    story.append(Paragraph("1. O Ciclo de Vida de um Pedido HTTP no ErasmusHub", h1_style))
    story.append(Paragraph(
        "Para compreenderes o projeto a fundo perante o professor, é essencial dominar como o Laravel recebe um pedido desde o clique no browser até à resposta renderizada na página. "
        "O ErasmusHub respeita rigorosamente o padrão <b>MVC (Model-View-Controller)</b> desacoplado, sem ficheiros monolíticos:",
        body_style
    ))

    flow_box = [
        [Paragraph("<b>Fluxo Passo a Passo de uma Requisição:</b>", tbl_cell_bold)],
        [Paragraph(
            "<b>1. Entrada (public/index.php):</b> Ponto único de entrada onde o Composer autoloader e o kernel da aplicação são inicializados.<br/>"
            "<b>2. Pipeline de Middlewares:</b> Antes de chegar ao controlador, o pedido passa por <code>SetLocale</code> (define idioma EN/PT/HR) e <code>EnsureUserIsActive</code> (expulsa utilizadores com <code>is_active=false</code>).<br/>"
            "<b>3. Roteamento (routes/web.php & api.php):</b> Mapeia o verbo HTTP e URI para o método específico no Controller correspondente.<br/>"
            "<b>4. Autorização (Policies):</b> Antes de executar ações sensíveis, o controller invoca <code>Gate::authorize()</code> ou <code>$this->authorize()</code> para verificar permissões baseadas em papéis.<br/>"
            "<b>5. Camada de Serviço (WeatherService):</b> Se a operação envolve lógica externa pesada (como meteorologia), o controller delega ao Service.<br/>"
            "<b>6. Camada de Dados (Eloquent ORM & MySQL):</b> O Model faz a leitura/escrita com prepared statements, garantindo integridade e transações via <code>DB::transaction()</code>.<br/>"
            "<b>7. Resposta:</b> Renderização de template Blade compilado com Tailwind CSS ou retorno de <code>JsonResponse</code> semântica na API.",
            body_style
        )]
    ]
    t_flow = Table(flow_box, colWidths=[523])
    t_flow.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_gray_bg),
        ('BOX', (0,0), (-1,-1), 1, c_border),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_flow)
    story.append(Spacer(1, 10))

    # =========================================================================
    # CAPÍTULO 2: BASE DE DADOS E MIGRAÇÕES
    # =========================================================================
    story.append(Paragraph("2. Base de Dados Relacional: Migrações, Tabelas e Integridade", h1_style))
    story.append(Paragraph(
        "A base de dados foi estruturada em <b>3ª Forma Normal (3FN)</b>. Cada tabela tem uma responsabilidade única, evitando redundâncias e garantindo a integridade dos dados através de restrições relacionais:",
        body_style
    ))

    db_details = [
        ("Tabela 'roles'", "Armazena os papéis de utilizador: <code>registered (1)</code>, <code>editor (2)</code>, <code>admin (3)</code>. Garante que as funções não são simples strings soltas, mas entidades relacionais normalizadas."),
        ("Tabela 'users'", "Armazena utilizadores com <code>role_id</code> (chave estrangeira com <code>restrictOnDelete</code> para impedir apagar um papel em uso) e a flag booleana <code>is_active</code> que permite desativação imediata de contas."),
        ("Tabela 'categories'", "Categorias dos guias (Alojamento, Transportes, etc.). Regra de integridade: se uma categoria contiver guias, a sua eliminação é bloqueada no controlador para evitar registos órfãos."),
        ("Tabela 'countries'", "Países europeus participantes no programa Erasmus (Portugal, Croácia, Espanha, França, etc.), com código ISO, bandeira emoji e moeda."),
        ("Tabela 'cities'", "Cidades universitárias com <code>country_id</code> (chave estrangeira com <code>cascadeOnDelete</code>), latitude e longitude precisas (usadas na API Open-Meteo), nome da universidade principal e custo de vida."),
        ("Tabela 'guides'", "Guias e notícias publicados ou rascunhos. Contém <code>category_id</code>, <code>author_id</code>, <code>city_id</code> e estado (<code>draft</code> ou <code>published</code>)."),
        ("Tabela 'images'", "Imagens complementares associadas a um guia (relação 1:1 com <code>cascadeOnDelete</code>). Ao apagar o guia, apaga também o ficheiro físico do disco no <code>Storage::disk('public')</code>."),
        ("Tabela 'personal_access_tokens'", "Criada pelo Laravel Sanctum para armazenar tokens de autenticação bearer da API REST.")
    ]

    for tbl_name, tbl_desc in db_details:
        story.append(Paragraph(f"&bull; <b>{tbl_name}:</b> {tbl_desc}", body_style))

    story.append(Spacer(1, 6))

    # Código da Migration de Cidades e Países
    story.append(Paragraph("Trecho Real de Código da Migração (database/migrations/create_countries_and_cities_tables.php):", h2_style))
    code_migration = (
        "Schema::create('countries', function (Blueprint $table) {\n"
        "    $table->id();\n"
        "    $table->string('name')->unique();\n"
        "    $table->string('code', 5)->unique(); // PT, HR, ES...\n"
        "    $table->string('flag_emoji', 10)->default('🇪🇺');\n"
        "    $table->string('currency', 10)->default('EUR');\n"
        "    $table->timestamps();\n"
        "});\n\n"
        "Schema::create('cities', function (Blueprint $table) {\n"
        "    $table->id();\n"
        "    $table->foreignId('country_id')->constrained()->cascadeOnDelete();\n"
        "    $table->string('name');\n"
        "    $table->string('slug')->unique();\n"
        "    $table->decimal('latitude', 10, 7);\n"
        "    $table->decimal('longitude', 10, 7);\n"
        "    $table->string('main_university')->nullable();\n"
        "    $table->text('description')->nullable();\n"
        "    $table->string('cost_of_living', 20)->default('Medium');\n"
        "    $table->timestamps();\n"
        "});"
    )
    t_c_mig = Table([[Paragraph(code_migration.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_mig.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_mig)

    # Caixa 'Por que foi feito assim?'
    why_mig = (
        "<b>Por que foi feito assim?</b> O uso de <code>cascadeOnDelete()</code> nas cidades garante que, se um país for removido, "
        "as suas cidades são limpas automaticamente. Por outro lado, na tabela de guias usámos <code>nullOnDelete()</code> na cidade "
        "para que, se uma cidade for excluída, o guia não desapareça misteriosamente, ficando apenas sem cidade vinculada."
    )
    t_why_mig = Table([[Paragraph(why_mig, why_style)]], colWidths=[523])
    t_why_mig.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_why_bg),
        ('BOX', (0,0), (-1,-1), 1, c_why_border),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(Spacer(1, 4))
    story.append(t_why_mig)

    # =========================================================================
    # CAPÍTULO 3: MODELOS E RELACIONAMENTOS ELOQUENT
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("3. Modelos Eloquent e Relacionamentos ORM", h1_style))
    story.append(Paragraph(
        "Os modelos no Laravel representam as tabelas e definem as relações orientadas a objetos. No ErasmusHub, cada modelo foi configurado com métodos expressivos:",
        body_style
    ))

    # Tabela com as relações dos modelos
    rel_data = [
        [Paragraph("Modelo", tbl_header), Paragraph("Relação Eloquent", tbl_header), Paragraph("Descrição no Sistema", tbl_header)],
        [
            Paragraph("<b>User</b>", tbl_cell_bold),
            Paragraph("<code>belongsTo(Role::class)</code><br/><code>hasMany(Guide::class, 'author_id')</code>", tbl_cell),
            Paragraph("O utilizador tem um papel e pode ser autor de vários guias. Métodos auxiliares: <code>isAdmin()</code>, <code>isEditor()</code>, <code>canManageGuides()</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Guide</b>", tbl_cell_bold),
            Paragraph("<code>belongsTo(Category::class)</code><br/><code>belongsTo(User::class, 'author_id')</code><br/><code>belongsTo(City::class, 'city_id')</code><br/><code>hasOne(Image::class)</code>", tbl_cell),
            Paragraph("O guia pertence a uma categoria, autor e cidade, e pode ter uma imagem em anexo. Possui o Scope <code>scopePublished()</code> para filtrar artigos visíveis ao público.", tbl_cell)
        ],
        [
            Paragraph("<b>Category</b>", tbl_cell_bold),
            Paragraph("<code>hasMany(Guide::class)</code>", tbl_cell),
            Paragraph("Uma categoria agrupa vários guias (ex: Burocracia, Alojamento, Transportes).", tbl_cell)
        ],
        [
            Paragraph("<b>Country & City</b>", tbl_cell_bold),
            Paragraph("<code>Country->hasMany(City)</code><br/><code>City->belongsTo(Country)</code><br/><code>City->hasMany(Guide)</code>", tbl_cell),
            Paragraph("Permite navegar hierarquicamente: País &rarr; Cidades &rarr; Guias locais da cidade.", tbl_cell)
        ]
    ]
    t_rel = Table(rel_data, colWidths=[80, 190, 253])
    t_rel.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_primary),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_gray_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_rel)
    story.append(Spacer(1, 8))

    story.append(Paragraph("Trecho Real de Código do Modelo Guide (app/Models/Guide.php):", h2_style))
    code_guide = (
        "class Guide extends Model {\n"
        "    protected $fillable = ['title', 'slug', 'summary', 'content', 'category_id', 'city_id', 'city', 'status', 'published_at'];\n\n"
        "    public function category(): BelongsTo {\n"
        "        return $this->belongsTo(Category::class);\n"
        "    }\n\n"
        "    public function author(): BelongsTo {\n"
        "        return $this->belongsTo(User::class, 'author_id');\n"
        "    }\n\n"
        "    public function destinationCity(): BelongsTo {\n"
        "        return $this->belongsTo(City::class, 'city_id');\n"
        "    }\n\n"
        "    // Scope local que restringe consultas apenas a artigos aprovados e publicados\n"
        "    public function scopePublished(Builder $query): Builder {\n"
        "        return $query->where('status', 'published');\n"
        "    }\n"
        "}"
    )
    t_c_guide = Table([[Paragraph(code_guide.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_guide.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_guide)

    # =========================================================================
    # CAPÍTULO 4: AUTENTICAÇÃO, CONTROLO DE ACESSO (RBAC) E POLICIES
    # =========================================================================
    story.append(Paragraph("4. Controlo de Acesso Baseado em Papéis (RBAC) & Policies", h1_style))
    story.append(Paragraph(
        "Para responder ao requisito da Secção 6 do enunciado, o ErasmusHub implementa uma matriz rigorosa de permissões através de <b>Laravel Policies</b>. "
        "Não usamos 'if/else' espalhados pelos templates; as regras de negócio vivem numa classe dedicada:",
        body_style
    ))

    code_policy = (
        "class GuidePolicy {\n"
        "    // Visitantes só veem artigos se estiverem 'published'\n"
        "    public function view(?User $user, Guide $guide): bool {\n"
        "        if ($guide->status === 'published') return true;\n"
        "        if (! $user) return false;\n"
        "        return $user->isAdmin() || $user->id === $guide->author_id;\n"
        "    }\n\n"
        "    // Apenas Administradores ou Editores podem criar guias\n"
        "    public function create(User $user): bool {\n"
        "        return $user->isAdmin() || $user->isEditor();\n"
        "    }\n\n"
        "    // EDITORES SÓ PODEM ALTERAR OS SEUS PRÓPRIOS ARTIGOS\n"
        "    public function update(User $user, Guide $guide): bool {\n"
        "        if ($user->isAdmin()) return true;\n"
        "        return $user->isEditor() && $user->id === $guide->author_id;\n"
        "    }\n\n"
        "    // EDITORES SÓ PODEM ELIMINAR OS SEUS PRÓPRIOS ARTIGOS\n"
        "    public function delete(User $user, Guide $guide): bool {\n"
        "        if ($user->isAdmin()) return true;\n"
        "        return $user->isEditor() && $user->id === $guide->author_id;\n"
        "    }\n"
        "}"
    )
    t_c_pol = Table([[Paragraph(code_policy.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_pol.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_pol)

    why_pol = (
        "<b>Como defender perante o Professor:</b> Se o professor perguntar: <i>'O que impede o Editor 1 de entrar na URL de edição do Editor 2 e alterar os dados dele?'</i>, "
        "a resposta exata é: <i>'A chamada a <code>Gate::authorize(\"update\", $guide)</code> no método <code>edit()</code> e <code>update()</code> do GuideController. "
        "A policy avalia <code>$user->id === $guide->author_id</code>. Se os IDs forem diferentes, o Laravel aborta a requisição com <b>403 Forbidden</b> antes de executar qualquer linha de código.'</i>"
    )
    t_why_pol = Table([[Paragraph(why_pol, why_style)]], colWidths=[523])
    t_why_pol.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_why_bg),
        ('BOX', (0,0), (-1,-1), 1, c_why_border),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(Spacer(1, 4))
    story.append(t_why_pol)

    # =========================================================================
    # CAPÍTULO 5: MIDDLEWARES DE SEGURANÇA E MULTILINGUISMO
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("5. Middlewares de Segurança: Desativação Instantânea & Idiomas", h1_style))
    story.append(Paragraph(
        "Os middlewares operam como filtros de segurança em todas as requisições HTTP recebidas pelo servidor. Criámos dois middlewares cruciais registados em <code>bootstrap/app.php</code>:",
        body_style
    ))

    story.append(Paragraph("A) Middleware de Desativação de Sessão (app/Http/Middleware/EnsureUserIsActive.php):", h2_style))
    story.append(Paragraph(
        "Um problema clássico em sistemas de autenticação é: quando um admin bloqueia uma conta, o utilizador já logado continuaria a navegar até fechar a janela. "
        "O nosso middleware resolve este problema invalidando a sessão no milissegundo em que <code>is_active == false</code>:",
        body_style
    ))

    code_mid_active = (
        "class EnsureUserIsActive {\n"
        "    public function handle(Request $request, Closure $next): Response {\n"
        "        $user = $request->user();\n\n"
        "        // Se há um utilizador autenticado mas a conta foi desativada no banco:\n"
        "        if ($user && ! $user->is_active) {\n"
        "            Auth::logout();                    // Encerra autenticação\n"
        "            $request->session()->invalidate(); // Destrói os dados de sessão\n"
        "            $request->session()->regenerateToken(); // Regenera token CSRF\n\n"
        "            return redirect()->route('login')->withErrors([\n"
        "                'email' => 'A tua conta está desativada. Contacta um administrador.',\n"
        "            ]);\n"
        "        }\n"
        "        return $next($request);\n"
        "    }\n"
        "}"
    )
    t_c_mid = Table([[Paragraph(code_mid_active.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_mid.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_mid)

    story.append(Spacer(1, 6))
    story.append(Paragraph("B) Middleware de Multilinguismo (app/Http/Middleware/SetLocale.php):", h2_style))
    story.append(Paragraph(
        "A aplicação suporta 3 idiomas: <b>Inglês (en)</b>, <b>Português (pt)</b> e <b>Croata (hr)</b>. "
        "Ao clicar no seletor da navbar, a rota <code>/locale/{locale}</code> grava a preferência na sessão. "
        "Em cada requisição seguinte, o <code>SetLocale</code> lê a sessão e ativa <code>App::setLocale($locale)</code>, carregando os ficheiros JSON em <code>lang/</code>.",
        body_style
    ))

    # =========================================================================
    # CAPÍTULO 6: CMS, GESTÃO DE CONTEÚDO E SEGURANÇA DE UPLOAD
    # =========================================================================
    story.append(Paragraph("6. Painel CMS: Transações de Banco, Upload de Imagens e Sanitização XSS", h1_style))
    story.append(Paragraph(
        "No controlador do CMS (<code>app/Http/Controllers/Admin/GuideController.php</code>), nenhuma alteração crítica é feita diretamente no banco sem proteções:",
        body_style
    ))

    cms_features = [
        ("Sanitização contra XSS", "O conteúdo do editor de texto avançado passa por <code>strip_tags($content, '&lt;p&gt;&lt;b&gt;&lt;i&gt;&lt;u&gt;&lt;h2&gt;&lt;ul&gt;&lt;li&gt;&lt;a&gt;')</code>. Tags de risco como <code>&lt;script&gt;</code>, <code>&lt;iframe&gt;</code> ou <code>onload=</code> são eliminadas no backend antes de persistir."),
        ("Transações Atómicas (DB::transaction)", "A criação de um guia envolve gravar o registo do artigo e salvar o ficheiro na tabela <code>images</code>. Encapsulamos as duas operações em <code>DB::transaction()</code>. Se o upload falhar, o guia sofre rollback automático."),
        ("Segurança de Ficheiros", "Ficheiros são validados por regra: <code>'image' =&gt; ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']</code>. Tentativas de enviar ficheiros executáveis (ex: <code>shell.php</code>) disparam erro 422."),
        ("Ciclo de Vida (Rascunho vs. Publicado)", "Editores podem criar rascunhos. Na tabela do CMS foi adicionado um botão de ação rápida <b>Despublicar / Publicar</b> que altera o estado do artigo sem precisar de abrir o formulário completo de edição.")
    ]
    for feat_t, feat_d in cms_features:
        story.append(Paragraph(f"&bull; <b>{feat_t}:</b> {feat_d}", body_style))

    # =========================================================================
    # CAPÍTULO 7: API RESTFUL PRÓPRIA, SANCTUM E JSON SCHEMA
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("7. API RESTful Própria, Autenticação Sanctum & JSON Schema", h1_style))
    story.append(Paragraph(
        "A API do ErasmusHub foi desenhada sob os mais elevados padrões da arquitetura RESTful, residindo em <code>routes/api.php</code> sob o prefixo <code>/api/v1</code>:",
        body_style
    ))

    api_deep_dive = [
        ("Semântica Estrita de Códigos HTTP", "O método <code>destroy()</code> devolve <code>response()->noContent()</code> (<b>HTTP 204</b>, sem qualquer corpo JSON), conforme exige a especificação HTTP/1.1 para deleções bem-sucedidas. Criações retornam <b>HTTP 201 Created</b>. Entradas inválidas retornam <b>HTTP 400</b>."),
        ("Autenticação Stateless com Laravel Sanctum", "Para que utilizadores externos possam interagir via Postman/Bruno, criámos o endpoint <code>POST /api/v1/login</code>. O utilizador fornece email/password e recebe um Bearer Token pessoal persistido na tabela <code>personal_access_tokens</code>."),
        ("Validação com JSON Schema Oficial (Draft 2020-12)", "Em <code>public/schemas/guide-schema.json</code> definimos a estrutura formal de um guia (tipos, minLength, maxLength, enums). O controlador <code>SchemaController</code> valida payloads recebidos e devolve mensagens descritivas de conformidade.")
    ]
    for ad_t, ad_d in api_deep_dive:
        story.append(Paragraph(f"&bull; <b>{ad_t}:</b> {ad_d}", body_style))

    story.append(Spacer(1, 6))
    story.append(Paragraph("Trecho do Endpoint de Autenticação da API (app/Http/Controllers/Api/V1/AuthController.php):", h2_style))
    code_api_auth = (
        "public function login(Request $request): JsonResponse {\n"
        "    $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);\n"
        "    $user = User::where('email', $request->email)->first();\n\n"
        "    if (! $user || ! Hash::check($request->password, $user->password)) {\n"
        "        return response()->json(['status' => 'error', 'message' => 'Credenciais incorretas.'], 422);\n"
        "    }\n\n"
        "    if (! $user->is_active) {\n"
        "        return response()->json(['status' => 'error', 'message' => 'Conta desativada.'], 403);\n"
        "    }\n\n"
        "    // Cria token Sanctum para autorização Bearer\n"
        "    $token = $user->createToken($request->device_name ?? 'api-client')->plainTextToken;\n\n"
        "    return response()->json(['status' => 'success', 'token' => $token, 'user' => $user], 200);\n"
        "}"
    )
    t_c_aauth = Table([[Paragraph(code_api_auth.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_aauth.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_aauth)

    # =========================================================================
    # CAPÍTULO 8: SERVIÇO EXTERNO OPEN-METEO
    # =========================================================================
    story.append(Spacer(1, 8))
    story.append(Paragraph("8. Integração de Serviço Web Externo: Open-Meteo & Previsão 7 Dias", h1_style))
    story.append(Paragraph(
        "A classe <code>App\\Services\\WeatherService</code> é um exemplo perfeito de integração robusta com serviços web externos. "
        "A classe não apenas faz uma requisição cega; implementa três camadas de proteção industrial:",
        body_style
    ))

    code_weather = (
        "public function getWeatherForCity(?string $city): ?array {\n"
        "    if (! $city) return null;\n"
        "    $coords = $this->resolveCoordinates($city); // Obtém latitude/longitude\n"
        "    $cacheKey = 'weather_city_extended_' . strtolower(trim($city));\n\n"
        "    // 1. CAMADA DE CACHE (30 Minutos): Evita chamadas repetitivas\n"
        "    if (Cache::has($cacheKey)) {\n"
        "        $cached = Cache::get($cacheKey);\n"
        "        $cached['from_cache'] = true;\n"
        "        return $cached;\n"
        "    }\n\n"
        "    try {\n"
        "        // 2. TIMEOUT ESTREITO (3 Segundos): Não bloqueia o PHP se a API externa cair\n"
        "        $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [\n"
        "            'latitude' => $coords['lat'],\n"
        "            'longitude' => $coords['lon'],\n"
        "            'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',\n"
        "            'daily' => 'weather_code,temperature_2m_max,temperature_2m_min',\n"
        "            'timezone' => 'auto',\n"
        "        ]);\n\n"
        "        if (! $response->successful()) return $this->getFallbackData($coords['name'], 'Erro API');\n\n"
        "        // 3. PROCESSAMENTO DE DADOS: Mapeia WMO codes para Emojis e dias da semana\n"
        "        $result = $this->formatWeatherData($response->json(), $coords['name']);\n"
        "        Cache::put($cacheKey, $result, now()->addMinutes(30));\n"
        "        return $result;\n"
        "    } catch (Exception $e) {\n"
        "        // 4. FALLBACK EM CASO DE ERRO DE REDE: Devolve valores seguros sem crashar\n"
        "        return $this->getFallbackData($coords['name'], 'Serviço temporariamente indisponível');\n"
        "    }\n"
        "}"
    )
    t_c_w = Table([[Paragraph(code_weather.replace("\n", "<br/>"), code_style)]], colWidths=[523])
    t_c_w.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_code_bg),
        ('BOX', (0,0), (-1,-1), 1, c_primary),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_c_w)

    # =========================================================================
    # CAPÍTULO 9: FRONTEND E EXPERIÊNCIA DO UTILIZADOR
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("9. Frontend, Interface Blade e Página de Destinos Erasmus", h1_style))
    story.append(Paragraph(
        "O frontend foi construído utilizando **Blade Components** nativos do Laravel, estilizados com **Tailwind CSS** e reatividade ligeira via **Alpine.js**:",
        body_style
    ))

    fe_details = [
        ("Página de Destinos & Meteorologia (/destinations)", "Contém uma caixa de seleção rápida no topo. Ao escolher qualquer cidade europeia (Lisboa, Zagreb, Split, Madrid, Bolonha, Paris, Praga...), a página exibe a universidade de acolhimento, moeda, custo de vida, tempo ao vivo e cartões dos 7 dias da semana com temperaturas máxima e mínima."),
        ("Catálogo de Guias com Pesquisa Dinâmica (/guides)", "Filtros combinados por palavra-chave (título e sumário), categoria e cidade, com paginação integrada e links no formato Tailwind."),
        ("Alternador de Língua Global", "Dropdown no cabeçalho permitindo alternar instantaneamente entre Inglês, Português e Croata em qualquer ecrã do sistema."),
        ("Modo Live API no Frontend", "Na listagem de guias, foi implementado um seletor que permite ao utilizador alternar entre a renderização tradicional Blade ou carregar os guias via <code>fetch()</code> da nossa API própria REST.")
    ]
    for fe_t, fe_d in fe_details:
        story.append(Paragraph(f"&bull; <b>{fe_t}:</b> {fe_d}", body_style))

    # =========================================================================
    # CAPÍTULO 10: SUITE DE TESTES AUTOMATIZADOS (46 TESTES)
    # =========================================================================
    story.append(Spacer(1, 8))
    story.append(Paragraph("10. Testes Automatizados (PHPUnit / Pest)", h1_style))
    story.append(Paragraph(
        "A aplicação é protegida por uma suite de **46 testes de integração e unitários** com **164 asserções** (executados com sucesso via <code>php artisan test</code>). "
        "Os testes correm sobre uma base de dados SQLite em memória através do trait <code>RefreshDatabase</code>, garantindo testes isolados, ultrarrápidos e sem corromper a BD de produção MySQL:",
        body_style
    ))

    test_groups = [
        ("Autenticação & Breeze (16 testes)", "Validação de ecrãs de login, registo, redefinição de password, logout e verificação de email."),
        ("Políticas e RBAC (8 testes)", "Confirma que rascunhos retornam 403 a visitantes; que o Editor 1 não edita o Editor 2; e que um administrador não consegue rebaixar o seu próprio papel de admin."),
        ("Middleware EnsureUserIsActive (3 testes)", "Assegura que utilizadores com <code>is_active=false</code> são deslogados imediatamente ao tentar qualquer ação."),
        ("Segurança de Upload (2 testes)", "Valida a rejeição imediata de ficheiros com extensão ou MIME type inválido (ex: ficheiro PHP simulando imagem)."),
        ("Integridade de Categorias (2 testes)", "Garante que o sistema recusa apagar uma categoria que ainda possua artigos associados."),
        ("API RESTful v1 (10 testes)", "Valida estrutura JSON, paginação, endpoints públicos vs. autenticados, emissão de tokens e resposta 204 no Delete."),
        ("Validação JSON Schema (2 testes)", "Testa payload perfeito (retorna 200 válido) e payload malformado (retorna 400 com lista de erros)."),
        ("Módulo de Destinos e Clima (3 testes)", "Valida carregamento de cidades, filtro de destinos e endpoint JSON da previsão meteorológica.")
    ]
    for tg_t, tg_d in test_groups:
        story.append(Paragraph(f"&bull; <b>{tg_t}:</b> {tg_d}", body_style))

    # =========================================================================
    # CAPÍTULO 11: GUIA DE PERGUNTAS E RESPOSTAS PARA A DEFESA ACADÉMICA
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("11. Roteiro de Perguntas & Respostas para a Apresentação Oral", h1_style))
    story.append(Paragraph("Utiliza estas respostas como o teu guião de estudo para responder com confiança técnica e maturidade:", body_style))

    extended_qa = [
        (
            "1. 'Como organizaste o teu código para garantir que não tens ficheiros monolíticos?'",
            "<i>'Segui a arquitetura MVC clássica reforçada com Camada de Serviços e Policies. Os controladores são finos (Skinny Controllers): delegam a persistência ao Eloquent ORM, a autorização às Policies (ex: GuidePolicy), a lógica de integrações complexas a Services (ex: WeatherService), e a validação a Form Requests e validações de esquema. A separação garante que a lógica de negócio, a persistência e a apresentação nunca se misturam.'</i>"
        ),
        (
            "2. 'Por que razão a API retorna 204 No Content na eliminação em vez de 200 OK com mensagem?'",
            "<i>'Porque respeitei integralmente a RFC 9110 e a especificação HTTP RESTful. O código 204 No Content indica formalmente ao cliente que a ação de exclusão foi processada com sucesso no servidor e que, propositadamente, nenhum corpo de resposta precisa de ser transferido pela rede, poupando largura de banda.'</i>"
        ),
        (
            "3. 'Como funciona a proteção contra injeção SQL no teu projeto?'",
            "<i>'No ErasmusHub não existe uma única query SQL crua (Raw SQL) concatenada com strings. Todas as consultas usam o Query Builder e o Eloquent ORM, que utilizam internamente PDO com Prepared Statements e Parameter Binding, tornando a injeção SQL matematicamente impossível.'</i>"
        ),
        (
            "4. 'Como desenhaste a integração do Open-Meteo para não dependeres da internet na defesa?'",
            "<i>'Implementei uma estratégia de contingência tripla no WeatherService: cache de 30 minutos em memória/disco (Cache::remember), timeout rígido de 3 segundos com Http::timeout(3) para evitar bloqueios, e um método getFallbackData() que devolve uma estrutura estática coerente com success=false caso o serviço falhe, garantindo que a aplicação nunca crasha.'</i>"
        ),
        (
            "5. 'Qual é a diferença entre um Utilizador Registado e um Editor?'",
            "<i>'O Utilizador Registado é um estudante que consome os guias, pesquisa e gere o seu perfil pessoal. O Editor é um autor ou embaixador de uma cidade europeia responsável por partilhar dicas práticas. Esta separação evita spam comunitário descontrolado e garante que cada editor só mexe no seu próprio conteúdo através da GuidePolicy.'</i>"
        ),
        (
            "6. 'Como comprovaste que o sistema cumpre todos os requisitos?'",
            "<i>'Através de uma suite de 46 testes automatizados com PHPUnit. Todos os cenários críticos — desde a tentativa de upload de scripts maliciosos até à expulsão imediata de contas desativadas e validação de JSON Schema — são testados e estão 100% verdes.'</i>"
        )
    ]

    for q_txt, a_txt in extended_qa:
        q_card = [
            [Paragraph(f"<b>{q_txt}</b>", ParagraphStyle('Q', parent=body_style, fontName='Helvetica-Bold', textColor=c_primary))],
            [Paragraph(a_txt, ParagraphStyle('A', parent=body_style, fontSize=8, leading=11))]
        ]
        t_card = Table(q_card, colWidths=[523])
        t_card.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#EFF6FF")),
            ('BACKGROUND', (0,1), (-1,1), colors.white),
            ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#93C5FD")),
            ('LINEBELOW', (0,0), (-1,0), 0.5, colors.HexColor("#BFDBFE")),
            ('TOPPADDING', (0,0), (-1,-1), 3),
            ('BOTTOMPADDING', (0,0), (-1,-1), 3),
            ('LEFTPADDING', (0,0), (-1,-1), 5),
            ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ]))
        story.append(t_card)
        story.append(Spacer(1, 4))

    doc.build(story, canvasmaker=DetailedNumberedCanvas)
    print(f"Manual Técnico Explicativo Completo gerado com sucesso em: {filename}")


if __name__ == '__main__':
    target = sys.argv[1] if len(sys.argv) > 1 else "c:/laragon/www/erasmushub/ErasmusHub_Manual_Tecnico_Explicativo.pdf"
    build_manual_pdf(target)
