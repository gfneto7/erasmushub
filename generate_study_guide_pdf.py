# -*- coding: utf-8 -*-
"""
Script de Geração do Dossier de Estudo e Defesa do Projeto ErasmusHub.
Gera um PDF completo com formatação profissional via ReportLab.
"""

import sys
import os
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import cm, mm
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    """Canvas com numeração de páginas no formato 'Página X de Y' e cabeçalho dinâmico."""
    def __init__(self, *args, **kwargs):
        super(NumberedCanvas, self).__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super(NumberedCanvas, self).showPage()
        super(NumberedCanvas, self).save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#6B7280"))

        # Cabeçalho (a partir da página 2)
        if self._pageNumber > 1:
            self.drawString(36, 810, "ErasmusHub | Guia Completo para Defesa do Projeto (NTPWS)")
            self.drawRightString(559, 810, "PHP 8.5 / Laravel &bull; REST API &bull; Open-Meteo")
            self.setStrokeColor(colors.HexColor("#E5E7EB"))
            self.setLineWidth(0.5)
            self.line(36, 804, 559, 804)

        # Rodapé em todas as páginas
        self.setStrokeColor(colors.HexColor("#E5E7EB"))
        self.setLineWidth(0.5)
        self.line(36, 35, 559, 35)

        self.drawString(36, 24, "ErasmusHub &bull; Licenciatura / Mestrado &bull; Engenharia Web")
        page_str = f"Página {self._pageNumber} de {page_count}"
        self.drawRightString(559, 24, page_str)

        self.restoreState()


def build_pdf(filename="c:/laragon/www/erasmushub/ErasmusHub_Guia_Defesa_Projeto.pdf"):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=36,
        rightMargin=36,
        topMargin=45,
        bottomMargin=45
    )

    styles = getSampleStyleSheet()

    # Cores
    primary = colors.HexColor("#1E3A8A")     # Navy
    secondary = colors.HexColor("#0284C7")   # Sky Blue
    accent = colors.HexColor("#D97706")      # Amber
    dark = colors.HexColor("#111827")        # Dark slate
    light_bg = colors.HexColor("#F9FAFB")    # Gray 50
    code_bg = colors.HexColor("#F3F4F6")     # Gray 100
    border_c = colors.HexColor("#E5E7EB")    # Gray 200
    green_c = colors.HexColor("#059669")     # Emerald

    # Estilos customizados
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=24,
        leading=28,
        textColor=primary,
        alignment=0,
        spaceAfter=6
    )

    subtitle_style = ParagraphStyle(
        'DocSubTitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=12,
        leading=16,
        textColor=secondary,
        spaceAfter=15
    )

    h1_style = ParagraphStyle(
        'SectionH1',
        parent=styles['Heading1'],
        fontName='Helvetica-Bold',
        fontSize=14,
        leading=18,
        textColor=primary,
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )

    h2_style = ParagraphStyle(
        'SectionH2',
        parent=styles['Heading2'],
        fontName='Helvetica-Bold',
        fontSize=11,
        leading=15,
        textColor=secondary,
        spaceBefore=10,
        spaceAfter=4,
        keepWithNext=True
    )

    body_style = ParagraphStyle(
        'Body',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9,
        leading=13,
        textColor=dark,
        spaceAfter=6
    )

    body_bold = ParagraphStyle(
        'BodyBold',
        parent=body_style,
        fontName='Helvetica-Bold'
    )

    callout_style = ParagraphStyle(
        'Callout',
        parent=body_style,
        fontName='Helvetica-Oblique',
        fontSize=8.5,
        leading=12,
        textColor=colors.HexColor("#1E293B")
    )

    code_style = ParagraphStyle(
        'CodeSnippet',
        parent=styles['Normal'],
        fontName='Courier',
        fontSize=7.5,
        leading=10,
        textColor=colors.HexColor("#0F172A")
    )

    table_header = ParagraphStyle(
        'TableHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11,
        textColor=colors.white
    )

    table_cell = ParagraphStyle(
        'TableCell',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=10,
        textColor=dark
    )

    table_cell_bold = ParagraphStyle(
        'TableCellBold',
        parent=table_cell,
        fontName='Helvetica-Bold'
    )

    story = []

    # =========================================================================
    # CAPA / CABEÇALHO DO DOCUMENTO
    # =========================================================================
    story.append(Paragraph("🎓 ErasmusHub — Dossier Completo de Defesa", title_style))
    story.append(Paragraph("Manual de Arquitetura, Código-Fonte, APIs, Base de Dados & Roteiro de Perguntas", subtitle_style))
    story.append(HRFlowable(width="100%", thickness=2, color=secondary, spaceBefore=0, spaceAfter=12))

    # Caixa de Metadados do Projeto
    meta_data = [
        [
            Paragraph("<b>Unidade Curricular:</b> Design Web Avançado de Serviços Web (NTPWS)", table_cell),
            Paragraph("<b>Stack:</b> PHP 8.5.4 | Laravel 11/12 | MySQL (Laragon)", table_cell)
        ],
        [
            Paragraph("<b>Tema do Projeto:</b> Plataforma de Apoio a Alunos Erasmus+", table_cell),
            Paragraph("<b>Frontend:</b> Blade Components + Tailwind CSS + Alpine.js", table_cell)
        ],
        [
            Paragraph("<b>APIs:</b> RESTful v1 (Sanctum) + Open-Meteo (Tempo 7 dias)", table_cell),
            Paragraph("<b>Repositório:</b> github.com/gfneto7/erasmushub (Branch: main)", table_cell)
        ],
        [
            Paragraph("<b>Idiomas:</b> Português (PT), Inglês (EN), Croata (HR)", table_cell),
            Paragraph("<b>Testes Automatizados:</b> 46 testes | 164 asserções (100% Green)", table_cell)
        ]
    ]
    t_meta = Table(meta_data, colWidths=[260, 263])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F0F9FF")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#BAE6FD")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E0F2FE")),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 12))

    # =========================================================================
    # 1. OBJETIVO DO PROJETO & RESUMO EXECUTIVO
    # =========================================================================
    story.append(Paragraph("1. Objetivo do Projeto & Conceito ErasmusHub", h1_style))
    story.append(Paragraph(
        "O <b>ErasmusHub</b> foi concebido como uma plataforma web colaborativa desenhada para estudantes em mobilidade internacional Erasmus+. "
        "O portal unifica três pilares fundamentais de serviços: "
        "<b>(1) Base de Conhecimento Comunitária</b> (guias práticos de alojamento, transportes, custo de vida e universidade); "
        "<b>(2) Centro de Destinos Europeus</b> com integração meteorológica externa em tempo real e previsão para 7 dias; e "
        "<b>(3) API RESTful Própria</b> com autenticação via tokens e validação formal de esquemas JSON, permitindo consumo multiplataforma.",
        body_style
    ))
    story.append(Paragraph(
        "<b>Visão de Produto vs. Requisitos do Professor:</b> O sistema foi modelado com uma hierarquia rigorosa de Controlo de Acesso Baseado em Papéis (RBAC). "
        "Para evitar desordem e desinformação num domínio crítico como o intercâmbio, os conteúdos são criados por <b>Editores locais</b> (estudantes embaixadores de cada cidade/país) "
        "e geridos globalmente por <b>Administradores</b>, enquanto os utilizadores comuns e visitantes consultam e tiram partido do conhecimento estruturado.",
        body_style
    ))

    # =========================================================================
    # 2. CHECKLIST COMPLETA DO ENUNCIADO DO PROFESSOR (18 PONTOS)
    # =========================================================================
    story.append(Paragraph("2. Conformidade Rigorosa com o Enunciado da Disciplina", h1_style))
    story.append(Paragraph("A tabela seguinte detalha como cada secção exigida pelo professor foi rigorosamente implementada e validada:", body_style))

    req_data = [
        [Paragraph("Secção / Requisito", table_header), Paragraph("Descrição do Requisito", table_header), Paragraph("Implementação no ErasmusHub", table_header), Paragraph("Estado", table_header)],
        [
            Paragraph("1. Objetivo Geral", table_cell_bold),
            Paragraph("Frontend, lógica PHP, BD relacional e serviços web.", table_cell),
            Paragraph("Laravel 11 MVC + MySQL + API REST + Open-Meteo.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("2. Tema & Utilizadores", table_cell_bold),
            Paragraph("Tema específico com utilizadores claramente definidos.", table_cell),
            Paragraph("Estudantes Erasmus+, embaixadores (editores) e admins.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("3. Frontend Público", table_cell_bold),
            Paragraph("Homepage, lista/detalhe de notícias, filtros, pesquisa, paginação, perfil.", table_cell),
            Paragraph("Rotas <code>/</code>, <code>/guides</code>, <code>/destinations</code>, <code>/about</code>, <code>/profile</code>.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("4. Backend PHP", table_cell_bold),
            Paragraph("Separação de camadas, sem ficheiro único, validação no servidor.", table_cell),
            Paragraph("Controllers finos, Services dedicados, Form Requests, Policies.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("5. CMS & Notícias", table_cell_bold),
            Paragraph("Criar/editar guias, upload imagens, rascunhos, despublicar, rich text.", table_cell),
            Paragraph("Painel CMS em <code>/admin/guides</code> com barra de formatação e toggle.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("6. Gestão de Utilizadores", table_cell_bold),
            Paragraph("4 papéis, alteração de password, desativação imediata de sessão.", table_cell),
            Paragraph("Roles (Visitor, Reg, Editor, Admin) + Middleware <code>EnsureUserIsActive</code>.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("7. BD Relacional", table_cell_bold),
            Paragraph("MySQL, diagrama ER, chaves forasteiras, transações, seeders.", table_cell),
            Paragraph("8 migrações, FK constraints com cascade/restrict, <code>DB::transaction()</code>.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("8. API REST Própria", table_cell_bold),
            Paragraph("Endpoints GET/POST/PATCH/DELETE, códigos HTTP, JSON Schema.", table_cell),
            Paragraph("API v1 com <code>200, 201, 204, 400, 403, 404</code> e Draft 2020-12 Schema.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("9. API Externa", table_cell_bold),
            Paragraph("Serviço externo, timeout, cache, tratamento de falhas.", table_cell),
            Paragraph("Open-Meteo: timeout 3s, cache 30m, previsão 7 dias e fallback.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("10. Segurança", table_cell_bold),
            Paragraph("Bcrypt, prepared statements, CSRF, sanitização XSS, upload seguro.", table_cell),
            Paragraph("Eloquent PDO, <code>strip_tags</code>, <code>@csrf</code>, validação MIME e tamanho.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("11. Repositório Git", table_cell_bold),
            Paragraph("Commits regulares e significativos, .gitignore, sem ficheiros desnecessários.", table_cell),
            Paragraph("Repositório no GitHub com commits semânticos estruturados.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("12. Documentação", table_cell_bold),
            Paragraph("README completo com instalação, ER diagram e dados de teste.", table_cell),
            Paragraph("README com Mermaid ER, tabelas de teste e comandos de instalação.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ],
        [
            Paragraph("13. Multilinguismo", table_cell_bold),
            Paragraph("Suporte de línguas além do default.", table_cell),
            Paragraph("3 idiomas: Inglês (EN), Português (PT) e Croata (HR) via sessão.", table_cell),
            Paragraph("100% OK", table_cell_bold)
        ]
    ]

    t_req = Table(req_data, colWidths=[90, 160, 220, 53])
    t_req.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary),
        ('ALIGN', (-1,0), (-1,-1), 'CENTER'),
        ('GRID', (0,0), (-1,-1), 0.5, border_c),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, light_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_req)
    story.append(Spacer(1, 14))

    # =========================================================================
    # 3. CONTAS DE TESTE PARA AVALIAÇÃO
    # =========================================================================
    story.append(Paragraph("3. Contas de Demonstração Pré-Carregadas (Seeders)", h1_style))
    story.append(Paragraph("Todas as contas foram populadas com a palavra-passe padrão <code>password</code> para fácil verificação pelo professor:", body_style))

    users_data = [
        [Paragraph("Email", table_header), Paragraph("Papel (Role)", table_header), Paragraph("Estado", table_header), Paragraph("Objetivo de Demonstração / Teste", table_header)],
        [
            Paragraph("<b>admin@example.com</b>", table_cell),
            Paragraph("<font color='#B91C1C'><b>admin</b></font>", table_cell),
            Paragraph("<font color='#059669'>Ativo</font>", table_cell),
            Paragraph("Acesso irrestrito a todos os guias, gestão de categorias e utilizadores.", table_cell)
        ],
        [
            Paragraph("<b>editor1@example.com</b>", table_cell),
            Paragraph("<font color='#0284C7'><b>editor</b></font>", table_cell),
            Paragraph("<font color='#059669'>Ativo</font>", table_cell),
            Paragraph("Cria e edita <i>apenas os seus próprios guias</i>. Não altera categorias.", table_cell)
        ],
        [
            Paragraph("<b>editor2@example.com</b>", table_cell),
            Paragraph("<font color='#0284C7'><b>editor</b></font>", table_cell),
            Paragraph("<font color='#059669'>Ativo</font>", table_cell),
            Paragraph("Demonstrar que o Editor 1 não consegue editar nem apagar guias do Editor 2.", table_cell)
        ],
        [
            Paragraph("<b>user@example.com</b>", table_cell),
            Paragraph("<font color='#4B5563'><b>registered</b></font>", table_cell),
            Paragraph("<font color='#059669'>Ativo</font>", table_cell),
            Paragraph("Estudante regular. Consulta guias públicos e edita o seu perfil pessoal.", table_cell)
        ],
        [
            Paragraph("<b>inactive@example.com</b>", table_cell),
            Paragraph("<font color='#9CA3AF'><b>registered</b></font>", table_cell),
            Paragraph("<font color='#DC2626'><b>Inativo</b></font>", table_cell),
            Paragraph("Demonstração do middleware de segurança: login rejeitado e sessão revogada.", table_cell)
        ]
    ]

    t_users = Table(users_data, colWidths=[120, 70, 55, 278])
    t_users.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary),
        ('GRID', (0,0), (-1,-1), 0.5, border_c),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, light_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_users)
    story.append(Spacer(1, 14))

    # =========================================================================
    # 4. ARQUITETURA DA BASE DE DADOS (MODELO ENTIDADE-RELACIONAMENTO)
    # =========================================================================
    story.append(Paragraph("4. Arquitetura da Base de Dados Relacional (MySQL)", h1_style))
    story.append(Paragraph(
        "A base de dados <code>erasmushub</code> foi concebida segundo a 3ª Forma Normal (3FN), com integridade referencial estrita "
        "e restrições de chaves forasteiras (Foreign Keys) para prevenir inconsistências:",
        body_style
    ))

    db_tables = [
        [Paragraph("Tabela", table_header), Paragraph("Colunas Principais", table_header), Paragraph("Relações / Regras de Integridade", table_header)],
        [
            Paragraph("<b>roles</b>", table_cell_bold),
            Paragraph("<code>id, name</code>", table_cell),
            Paragraph("1:N com <code>users</code>. Contém 3 papéis base: <i>registered, editor, admin</i>.", table_cell)
        ],
        [
            Paragraph("<b>users</b>", table_cell_bold),
            Paragraph("<code>id, name, email, password, role_id, is_active, timestamps</code>", table_cell),
            Paragraph("<code>role_id</code> FK com <code>restrictOnDelete</code>. <code>is_active</code> booleano.", table_cell)
        ],
        [
            Paragraph("<b>categories</b>", table_cell_bold),
            Paragraph("<code>id, name, slug, description, timestamps</code>", table_cell),
            Paragraph("1:N com <code>guides</code>. Bloqueada a eliminação se tiver guias associados.", table_cell)
        ],
        [
            Paragraph("<b>countries</b>", table_cell_bold),
            Paragraph("<code>id, name, code, flag_emoji, currency, timestamps</code>", table_cell),
            Paragraph("1:N com <code>cities</code>. Países europeus (Portugal, Croácia, Espanha, etc.).", table_cell)
        ],
        [
            Paragraph("<b>cities</b>", table_cell_bold),
            Paragraph("<code>id, country_id, name, slug, latitude, longitude, main_university, description, cost_of_living</code>", table_cell),
            Paragraph("FK <code>country_id</code> (cascadeOnDelete). 1:N com <code>guides</code>.", table_cell)
        ],
        [
            Paragraph("<b>guides</b>", table_cell_bold),
            Paragraph("<code>id, title, slug, summary, content, category_id, city_id, author_id, city, status, published_at</code>", table_cell),
            Paragraph("FK <code>category_id</code> (restrict), FK <code>author_id</code> (restrict), FK <code>city_id</code> (nullOnDelete). Status: <i>draft/published</i>.", table_cell)
        ],
        [
            Paragraph("<b>images</b>", table_cell_bold),
            Paragraph("<code>id, guide_id, path, mime_type, size, alt_text</code>", table_cell),
            Paragraph("1:1 com <code>guides</code> (cascadeOnDelete). Remove ficheiro físico no delete.", table_cell)
        ],
        [
            Paragraph("<b>personal_access_tokens</b>", table_cell_bold),
            Paragraph("<code>id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at</code>", table_cell),
            Paragraph("Tabela nativa do Laravel Sanctum para autenticação da API REST.", table_cell)
        ]
    ]

    t_db = Table(db_tables, colWidths=[110, 170, 243])
    t_db.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary),
        ('GRID', (0,0), (-1,-1), 0.5, border_c),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, light_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_db)
    story.append(Spacer(1, 14))

    # =========================================================================
    # 5. CATÁLOGO COMPLETO DE APIS (REST PRÓPRIA + OPEN-METEO)
    # =========================================================================
    story.append(PageBreak()) # Nova página para APIs e Segurança
    story.append(Paragraph("5. Catálogo Completo de APIs", h1_style))

    story.append(Paragraph("A) API RESTful Própria (<code>/api/v1</code>)", h2_style))
    story.append(Paragraph(
        "A API própria devolve sempre <code>Content-Type: application/json</code> e cumpre rigorosamente os códigos semânticos HTTP:",
        body_style
    ))

    api_data = [
        [Paragraph("Método", table_header), Paragraph("Endpoint", table_header), Paragraph("Autenticação", table_header), Paragraph("Códigos HTTP", table_header), Paragraph("Descrição / Ação", table_header)],
        [
            Paragraph("<b>POST</b>", table_cell),
            Paragraph("<code>/api/v1/login</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200, 403, 422</code>", table_cell),
            Paragraph("Emissão de Bearer Token Sanctum para testes (Postman/Bruno).", table_cell)
        ],
        [
            Paragraph("<b>POST</b>", table_cell),
            Paragraph("<code>/api/v1/logout</code>", table_cell),
            Paragraph("Sanctum Token", table_cell),
            Paragraph("<code>200, 401</code>", table_cell),
            Paragraph("Revogação do token ativo do utilizador.", table_cell)
        ],
        [
            Paragraph("<b>GET</b>", table_cell),
            Paragraph("<code>/api/v1/destinations</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200</code>", table_cell),
            Paragraph("Lista de países da UE e respetivas cidades universitárias.", table_cell)
        ],
        [
            Paragraph("<b>GET</b>", table_cell),
            Paragraph("<code>/api/v1/destinations/{slug}</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200, 404</code>", table_cell),
            Paragraph("Detalhes da cidade + previsão meteorológica completa a 7 dias.", table_cell)
        ],
        [
            Paragraph("<b>GET</b>", table_cell),
            Paragraph("<code>/api/v1/guides</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200</code>", table_cell),
            Paragraph("Lista guias publicados com paginação e filtros (search, cat, city).", table_cell)
        ],
        [
            Paragraph("<b>GET</b>", table_cell),
            Paragraph("<code>/api/v1/guides/{id}</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200, 404</code>", table_cell),
            Paragraph("Retorna guia se publicado; se for rascunho (draft) retorna 404.", table_cell)
        ],
        [
            Paragraph("<b>POST</b>", table_cell),
            Paragraph("<code>/api/v1/guides</code>", table_cell),
            Paragraph("Editor / Admin", table_cell),
            Paragraph("<code>201, 400, 401, 403</code>", table_cell),
            Paragraph("Criação de novo guia. Sanitiza HTML e associa o autor autenticado.", table_cell)
        ],
        [
            Paragraph("<b>PATCH</b>", table_cell),
            Paragraph("<code>/api/v1/guides/{id}</code>", table_cell),
            Paragraph("Autor / Admin", table_cell),
            Paragraph("<code>200, 400, 403, 404</code>", table_cell),
            Paragraph("Atualização parcial do guia. Protegido pela <code>GuidePolicy</code>.", table_cell)
        ],
        [
            Paragraph("<b>DELETE</b>", table_cell),
            Paragraph("<code>/api/v1/guides/{id}</code>", table_cell),
            Paragraph("Autor / Admin", table_cell),
            Paragraph("<code>204, 401, 403, 404</code>", table_cell),
            Paragraph("Eliminação do guia. <b>Retorna 204 No Content sem corpo</b>.", table_cell)
        ],
        [
            Paragraph("<b>GET</b>", table_cell),
            Paragraph("<code>/api/v1/categories</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200</code>", table_cell),
            Paragraph("Lista de categorias e contagem de guias publicados.", table_cell)
        ],
        [
            Paragraph("<b>POST</b>", table_cell),
            Paragraph("<code>/api/v1/validate-guide-schema</code>", table_cell),
            Paragraph("Pública", table_cell),
            Paragraph("<code>200, 400</code>", table_cell),
            Paragraph("Valida payload contra <b>JSON Schema (Draft 2020-12)</b>.", table_cell)
        ]
    ]

    t_api = Table(api_data, colWidths=[45, 135, 75, 88, 180])
    t_api.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary),
        ('GRID', (0,0), (-1,-1), 0.5, border_c),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, light_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 2.5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2.5),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_api)
    story.append(Spacer(1, 10))

    story.append(Paragraph("B) Integração com API Externa (Open-Meteo Weather Service)", h2_style))
    story.append(Paragraph(
        "A classe <code>App\\Services\\WeatherService</code> consome a API meteorológica gratuita Open-Meteo sem necessidade de chaves privadas (zero-config). "
        "Principais técnicas de robustez implementadas:",
        body_style
    ))
    story.append(Paragraph(
        "&bull; <b>Timeout de 3 segundos:</b> <code>Http::timeout(3)->get(...)</code> para nunca bloquear o carregamento das páginas.<br/>"
        "&bull; <b>Cache Inteligente de 30 minutos:</b> Evita saturação e respeita limites externos via <code>Cache::put($key, $data, 30m)</code>.<br/>"
        "&bull; <b>Previsão Semanal a 7 Dias:</b> Solicita <code>daily=weather_code,temperature_2m_max,temperature_2m_min</code>.<br/>"
        "&bull; <b>Tolerância a Falhas (Graceful Fallback):</b> Se o serviço falhar ou houver timeout, retorna valores pré-definidos com <code>success=false</code> sem causar erro 500.",
        body_style
    ))

    # =========================================================================
    # 6. SEGURANÇA E PROTEÇÃO DA APLICAÇÃO
    # =========================================================================
    story.append(Paragraph("6. Medidas de Segurança Implementadas", h1_style))
    sec_points = [
        ("Hash com Bcrypt", "Todas as palavras-passe são encriptadas com algoritmo seguro via <code>Hash::make()</code>."),
        ("Proteção SQL Injection", "Uso exclusivo do Eloquent ORM / PDO com binding automático de parâmetros parametrizados."),
        ("Sanitização XSS", "O conteúdo HTML de editores passa por <code>strip_tags()</code> no backend, permitindo apenas tags seguras (<code>&lt;p&gt;, &lt;b&gt;, &lt;ul&gt;...</code>)."),
        ("Proteção CSRF", "Todos os formulários POST/PUT/DELETE web validam o token <code>@csrf</code>."),
        ("Middleware de Sessão Ativa", "O middleware <code>EnsureUserIsActive</code> desloga utilizadores inativos no exato milissegundo em que tentam qualquer ação."),
        ("Validação Rigorosa de Uploads", "Imagens validadas por tipo MIME real (JPEG, PNG, WebP) e limite de 2 MB. Ficheiros PHP são imediatamente rejeitados."),
        ("Políticas de Autorização (Policies)", "<code>GuidePolicy</code> impede que um Editor altere ou apague conteúdos pertencentes a outro colega.")
    ]
    sec_data = [[Paragraph(f"&bull; <b>{title}:</b> {desc}", body_style)] for title, desc in sec_points]
    t_sec = Table(sec_data, colWidths=[523])
    t_sec.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, border_c),
        ('TOPPADDING', (0,0), (-1,-1), 2),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2),
    ]))
    story.append(t_sec)
    story.append(Spacer(1, 14))

    # =========================================================================
    # 7. GUIA DE PERGUNTAS E RESPOSTAS PARA A DEFESA (O QUE O PROFESSOR VAI PERGUNTAR)
    # =========================================================================
    story.append(PageBreak())
    story.append(Paragraph("7. Roteiro de Perguntas & Respostas para a Apresentação", h1_style))
    story.append(Paragraph("Preparações estratégicas para as perguntas mais prováveis que o professor colocará durante a avaliação:", body_style))

    qa_list = [
        (
            "P1: Por que têm 4 papéis de utilizador e não deixam toda a gente editar livremente?",
            "<b>Resposta:</b> 'Para cumprir o requisito RBAC exigido na Secção 6 do enunciado e por uma razão de produto: num portal de intercâmbio com milhares de estudantes, permitir que qualquer pessoa edite sem moderação geraria vandalismo, desinformação e spam. Assim, temos <b>Editores</b> que atuam como embaixadores locais de cada cidade/país, gerindo com responsabilidade os seus próprios guias, e o Administrador que supervisiona a qualidade global.'"
        ),
        (
            "P2: Como garantem que um Editor não altera os artigos de outro Editor?",
            "<b>Resposta:</b> 'Através da <code>GuidePolicy</code> do Laravel, registada no <code>AppServiceProvider</code>. O método <code>update(User $user, Guide $guide)</code> verifica se <code>$user->id === $guide->author_id</code> (ou se é Admin). Se não for o autor, o Laravel dispara automaticamente um código <b>403 Forbidden</b>, tanto na interface web como na API.'"
        ),
        (
            "P3: O que acontece se o serviço meteorológico externo (Open-Meteo) cair?",
            "<b>Resposta:</b> 'O nosso <code>WeatherService</code> tem três salvaguardas: primeiro, define um timeout estrito de <b>3 segundos</b> para não congelar o servidor. Segundo, guarda os resultados em <b>Cache por 30 minutos</b>. Terceiro, se houver falha de rede ou timeout, dispara um <code>fallback</code> graceful que devolve dados seguros com <code>success: false</code> e mensagem de aviso, garantindo que o utilizador nunca vê um ecrã de erro 500.'"
        ),
        (
            "P4: Como é feita a troca de dados e a validação na vossa API própria?",
            "<b>Resposta:</b> 'A API v1 expõe endpoints RESTful com respostas JSON padronizadas. Os códigos de estado HTTP são semânticos (200 para sucesso, 201 no Create, 204 sem corpo no Delete, 400 em erros de input, 401 para falta de token e 403 para permissões negadas). Além disso, implementámos o endpoint <code>/api/v1/validate-guide-schema</code> que valida os dados contra um <b>JSON Schema oficial (Draft 2020-12)</b> antes de qualquer persistência.'"
        ),
        (
            "P5: Como asseguram a desativação imediata de um utilizador cuja sessão já estava iniciada?",
            "<b>Resposta:</b> 'Criámos o middleware <code>EnsureUserIsActive</code> que faz parte do grupo <code>web</code> em <code>bootstrap/app.php</code>. Em cada requisição HTTP, se <code>Auth::check()</code> for verdadeiro mas <code>is_active == false</code>, o sistema invalida a sessão imediatamente com <code>Auth::logout()</code> e redireciona o utilizador para o login com mensagem de aviso.'"
        ),
        (
            "P6: Como foi testada a aplicação?",
            "<b>Resposta:</b> 'Criámos uma suite completa de testes de integração e unitários em <code>tests/Feature/ErasmusHubTest.php</code> utilizando PHPUnit e banco de dados SQLite em memória. Temos <b>46 testes automatizados e 164 asserções</b> cobrindo autenticação, bloqueio de rascunhos para visitantes, integridade de categorias, upload malicioso de ficheiros PHP, validação de schema e tolerância da API externa.'"
        )
    ]

    for question, answer in qa_list:
        q_box = [
            [Paragraph(f"<b>{question}</b>", ParagraphStyle('QStyle', parent=body_style, fontName='Helvetica-Bold', textColor=primary))],
            [Paragraph(answer, ParagraphStyle('AStyle', parent=body_style, fontSize=8.5, leading=12))]
        ]
        t_qa = Table(q_box, colWidths=[523])
        t_qa.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#EFF6FF")),
            ('BACKGROUND', (0,1), (-1,1), colors.white),
            ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#BFDBFE")),
            ('LINEBELOW', (0,0), (-1,0), 0.5, colors.HexColor("#DBEAFE")),
            ('TOPPADDING', (0,0), (-1,-1), 4),
            ('BOTTOMPADDING', (0,0), (-1,-1), 4),
            ('LEFTPADDING', (0,0), (-1,-1), 6),
            ('RIGHTPADDING', (0,0), (-1,-1), 6),
        ]))
        story.append(t_qa)
        story.append(Spacer(1, 6))

    # =========================================================================
    # 8. RESUMO DOS FICHEIROS E ESTRUTURA DO CÓDIGO-FONTE
    # =========================================================================
    story.append(Spacer(1, 8))
    story.append(Paragraph("8. Mapa de Ficheiros-Chave Criados no Projeto", h1_style))

    files_summary = [
        [Paragraph("Ficheiro", table_header), Paragraph("Função / Responsabilidade Principal", table_header)],
        [Paragraph("<code>app/Services/WeatherService.php</code>", table_cell_bold), Paragraph("Integração com Open-Meteo, cálculo de coordenadas, previsão 7 dias e cache.", table_cell)],
        [Paragraph("<code>app/Policies/GuidePolicy.php</code>", table_cell_bold), Paragraph("Controlo de autorização: apenas o próprio autor ou admin pode editar/apagar.", table_cell)],
        [Paragraph("<code>app/Http/Middleware/EnsureUserIsActive.php</code>", table_cell_bold), Paragraph("Termina sessão de contas desativadas instantaneamente.", table_cell)],
        [Paragraph("<code>app/Http/Middleware/SetLocale.php</code>", table_cell_bold), Paragraph("Gestão de idiomas (PT, EN, HR) baseado na sessão e browser.", table_cell)],
        [Paragraph("<code>app/Http/Controllers/Api/V1/AuthController.php</code>", table_cell_bold), Paragraph("Emissão e revogação de tokens Sanctum para a API REST.", table_cell)],
        [Paragraph("<code>app/Http/Controllers/Api/V1/GuideController.php</code>", table_cell_bold), Paragraph("CRUD completo da API REST com códigos de estado 200, 201, 204, etc.", table_cell)],
        [Paragraph("<code>app/Http/Controllers/DestinationController.php</code>", table_cell_bold), Paragraph("Controlador público da página de Destinos & Meteorologia.", table_cell)],
        [Paragraph("<code>database/seeders/CountryAndCitySeeder.php</code>", table_cell_bold), Paragraph("Seeder com países europeus e 16 cidades universitárias Erasmus.", table_cell)],
        [Paragraph("<code>resources/views/destinations/index.blade.php</code>", table_cell_bold), Paragraph("Vista com seletor interativo, previsão 7 dias, custo de vida e guias locais.", table_cell)],
        [Paragraph("<code>tests/Feature/ErasmusHubTest.php</code>", table_cell_bold), Paragraph("Suite com 46 testes que comprovam todos os requisitos do enunciado.", table_cell)]
    ]

    t_files = Table(files_summary, colWidths=[190, 333])
    t_files.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary),
        ('GRID', (0,0), (-1,-1), 0.5, border_c),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, light_bg]),
        ('TOPPADDING', (0,0), (-1,-1), 2.5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2.5),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_files)

    # Construir documento
    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"PDF gerado com sucesso em: {filename}")

if __name__ == '__main__':
    target = sys.argv[1] if len(sys.argv) > 1 else "c:/laragon/www/erasmushub/ErasmusHub_Guia_Defesa_Projeto.pdf"
    build_pdf(target)
