# 🚀 CRM WhatsApp - Sistema Completo de Gestão de Leads

Sistema web desenvolvido com **Laravel 12**, **Blade**, **Tailwind CSS** e **Alpine.js** para gestão completa de leads com integração ao WhatsApp via Evolution API.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Tailwind](https://img.shields.io/badge/Tailwind-3-cyan)

---

## 📋 Índice

- [Funcionalidades](#-funcionalidades)
- [Requisitos](#-requisitos)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso](#-uso)
- [Estrutura](#-estrutura)
- [Documentação Detalhada](#-documentação-detalhada)

---

## ✨ Funcionalidades

### 🎯 Dashboard
- **Métricas em tempo real**: Total de leads, conversões, leads de hoje
- **Leads não lidos**: Contador de mensagens pendentes
- **Leads que precisam de atenção**: Alertas automáticos (24h sem resposta)
- **Últimas atividades**: Histórico dos últimos 10 leads

### 👥 Gestão de Leads
- ✅ **CRUD Completo**: Criar, editar, visualizar e excluir leads
- 🔍 **Busca Avançada**: Por nome ou telefone
- 🎨 **Filtros Múltiplos**: Status, tags, responsável
- 📄 **Paginação**: 15 leads por página
- 👤 **Atribuição**: Vincular leads a vendedores específicos
- 🏷️ **Tags Coloridas**: Classificação visual (Interessado, Quente, VIP, etc.)
- 📝 **Notas Internas**: Observações privadas sobre cada lead
- 🔔 **Notificações**: Badge de mensagens não lidas

### 💬 Chat WhatsApp
- 📱 **Integração Evolution API**: Envio e recebimento de mensagens
- ⚡ **Templates Rápidos**: Respostas pré-configuradas
- 📎 **Anexos**: Envio de imagens, PDFs e documentos (até 10MB)
- ✓ **Status de Mensagens**: Enviada, Entregue, Lida
- 🔄 **Pooling Automático**: Atualização a cada 3 segundos
- 💬 **Histórico Completo**: Todas as conversas salvas

### 📊 Funil de Vendas (Kanban)
- 🎨 **Visualização em Colunas**: Novo → Em Contato → Convertido/Perdido
- 🖱️ **Drag & Drop**: Arraste e solte para mudar status
- 📈 **Métricas por Etapa**: Contadores em cada coluna

### 🔐 Autenticação
- 🔒 **Laravel Breeze**: Sistema completo de login/logout
- 👤 **Gestão de Perfil**: Atualizar dados e senha
- 🔑 **Proteção de Rotas**: Middleware de autenticação

---

## 📦 Requisitos

- **PHP**: 8.2 ou superior
- **Composer**: 2.x
- **Node.js**: 18+ e NPM
- **MySQL**: 5.7+ ou 8.0+
- **MAMP** (recomendado para Mac/Windows) ou outro servidor MySQL

---

## 🚀 Instalação

### 1. Clone o Repositório

```bash
git clone https://github.com/ovitoreduv4/eduxcrm.git
cd eduxcrm
```

### 2. Instale as Dependências

```bash
# Dependências PHP
composer install

# Dependências Node.js
npm install
```

### 3. Configure o Ambiente

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate
```

### 4. Configure o Banco de Dados

**Se estiver usando MAMP:**

1. Abra o MAMP e inicie os servidores
2. Acesse phpMyAdmin: `http://localhost:8888/phpMyAdmin`
3. Crie um novo banco de dados chamado `eduxcrm`

**Edite o arquivo `.env`:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=eduxcrm
DB_USERNAME=root
DB_PASSWORD=root
```

> **Nota**: A porta padrão do MAMP é `8889`. Se usar outro servidor, ajuste conforme necessário.

### 5. Execute as Migrations e Seeders

```bash
php artisan migrate:fresh --seed
```

Isso criará:
- ✅ Todas as tabelas do banco
- ✅ Usuário admin (admin@crm.com / password)
- ✅ 5 tags pré-configuradas
- ✅ 4 templates de mensagens
- ✅ 4 leads de exemplo com mensagens

### 6. Configure o Storage (para anexos)

```bash
php artisan storage:link
```

### 7. Compile os Assets

```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 8. Inicie o Servidor

```bash
php artisan serve
```

O sistema estará disponível em: **http://localhost:8000**

---

## 🔧 Configuração

### Evolution API (Opcional)

Para integração com WhatsApp, adicione no `.env`:

```env
EVOLUTION_API_URL=https://sua-evolution-api.com
EVOLUTION_API_KEY=sua-chave-api
EVOLUTION_INSTANCE_NAME=nome-da-instancia
```

**Configurar Webhook:**

Na Evolution API, configure o webhook para:
```
https://seu-dominio.com/webhook/messages
```

Para testes locais, use **ngrok**:
```bash
ngrok http 8000
```

---

## 👤 Credenciais Padrão

**Email**: admin@crm.com
**Senha**: password

> ⚠️ **IMPORTANTE**: Altere a senha após o primeiro login!

---

## 📊 Uso

### Dashboard
Acesse `/dashboard` para ver:
- Total de leads
- Leads por status
- Mensagens não lidas
- Leads que precisam de atenção

### Gerenciar Leads
1. **Criar Lead**: Clique em "Novo Lead"
2. **Adicionar Tags**: Selecione uma ou mais tags
3. **Atribuir Vendedor**: Escolha o responsável
4. **Salvar**

### Chat
1. Clique em um lead na lista
2. Visualize o histórico de mensagens
3. Use templates para respostas rápidas
4. Envie anexos (imagens, PDFs)
5. O chat atualiza automaticamente

### Funil Kanban
1. Acesse `/kanban`
2. Arraste os cards entre as colunas
3. O status é atualizado automaticamente

### Tags
1. Acesse `/tags`
2. Crie novas tags com cores personalizadas
3. As tags aparecem nos formulários de leads

### Templates
1. Acesse `/message-templates`
2. Crie templates globais ou pessoais
3. Use no chat com um clique

---

## 📁 Estrutura

```
eduxcrm/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php    # Métricas
│   │   ├── LeadController.php         # CRUD de leads
│   │   ├── ChatController.php         # Chat e anexos
│   │   ├── KanbanController.php       # Funil visual
│   │   ├── TagController.php          # Gestão de tags
│   │   ├── NoteController.php         # Notas internas
│   │   └── WebhookController.php      # Receber mensagens
│   └── Models/
│       ├── Lead.php                   # Model principal
│       ├── Message.php                # Mensagens
│       ├── Tag.php                    # Tags
│       ├── Note.php                   # Notas
│       └── MessageTemplate.php        # Templates
├── database/
│   ├── migrations/                    # Estrutura do banco
│   └── seeders/                       # Dados iniciais
├── resources/
│   └── views/
│       ├── dashboard.blade.php        # (A criar)
│       ├── kanban.blade.php           # (A criar)
│       └── leads/                     # Views de leads
└── routes/
    └── web.php                        # Rotas da aplicação
```

---

## 🗄️ Banco de Dados

### Tabelas Principais

**leads**
- id, nome, telefone, status
- assigned_to (usuário responsável)
- unread_count (mensagens não lidas)
- last_message_at (última interação)

**messages**
- id, lead_id, author (user/lead)
- content, status (sent/delivered/read)
- attachment_path, attachment_type

**tags**
- id, name, color

**notes**
- id, lead_id, user_id, content

**message_templates**
- id, name, content, user_id

---

## 🎨 Recursos Visuais

- **Tailwind CSS**: Design responsivo e moderno
- **Alpine.js**: Interatividade sem recarregar
- **Badges**: Indicadores visuais de status
- **Cards**: Layout organizado
- **Modais**: Confirmações e formulários

---

## 🔒 Segurança

- ✅ CSRF Protection
- ✅ Autenticação obrigatória
- ✅ Validação de inputs
- ✅ Passwords hasheados
- ✅ Webhook sem CSRF (rota pública)

---

## 🐛 Troubleshooting

### Erro de Conexão com Banco
```bash
# Verifique se o MySQL está rodando
# Confirme porta, usuário e senha no .env
php artisan config:clear
```

### Assets não Carregam
```bash
npm run build
php artisan optimize:clear
```

### Storage não Funciona
```bash
php artisan storage:link
chmod -R 775 storage
```

### Migrations Falham
```bash
php artisan migrate:fresh --seed
```

---

## 📚 Documentação Detalhada

Consulte o arquivo `README_CRM.md` para documentação técnica completa incluindo:
- Estrutura de código
- API da Evolution
- Customizações
- Melhorias futuras

---

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

---

## 📝 Licença

Este projeto é um exemplo educacional e pode ser usado livremente.

---

## 🙏 Agradecimentos

- Laravel Team
- Tailwind CSS
- Alpine.js
- Evolution API

---

**Desenvolvido com ❤️ usando Laravel**
