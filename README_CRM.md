# CRM WhatsApp - Sistema de Gestão de Leads

Sistema web simples desenvolvido com Laravel, Blade, Tailwind CSS e Alpine.js para gestão de leads com chat integrado ao WhatsApp via Evolution API.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- MySQL (MAMP recomendado)
- Evolution API (opcional, para integração com WhatsApp)

## Instalação e Configuração

### 1. Configurar o Banco de Dados

**Usando MAMP:**

1. Abra o MAMP e inicie os servidores Apache e MySQL
2. Acesse phpMyAdmin (geralmente em `http://localhost:8888/phpMyAdmin`)
3. Crie um novo banco de dados chamado `eduxcrm`

**Verificar porta do MySQL:**
- A porta padrão do MAMP é 8889
- Se for diferente, ajuste no arquivo `.env`

### 2. Configurar Variáveis de Ambiente

Edite o arquivo `.env` e configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=eduxcrm
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. Rodar Migrations e Seeders

Execute os comandos:

```bash
php artisan migrate:fresh --seed
```

Isso criará as tabelas e dados de teste:
- **Usuário:** admin@crm.com / password
- **3 leads de exemplo** com algumas mensagens

### 4. Instalar Dependências do Front-end

```bash
npm install
npm run build
```

### 5. Iniciar o Servidor

```bash
php artisan serve
```

O sistema estará disponível em: `http://localhost:8000`

## Estrutura do Sistema

### Banco de Dados

**Tabela: leads**
- id
- nome
- telefone
- status (novo, em_contato, convertido, perdido)
- created_at
- updated_at

**Tabela: messages**
- id
- lead_id (FK)
- author (user/lead)
- content
- created_at
- updated_at

**Tabela: users** (Laravel padrão)
- Usuários do sistema para autenticação

### Rotas Principais

**Autenticação:**
- `/login` - Login
- `/logout` - Logout

**Leads:**
- `/leads` - Listar leads
- `/leads/create` - Criar novo lead
- `/leads/{id}` - Detalhes do lead + Chat
- `/leads/{id}/edit` - Editar lead
- `/leads/{id}` DELETE - Excluir lead

**Chat:**
- GET `/leads/{id}/messages` - Buscar mensagens (AJAX)
- POST `/leads/{id}/messages` - Enviar mensagem (AJAX)

**Webhook:**
- POST `/webhook/messages` - Receber mensagens do WhatsApp

### Controllers

**LeadController**
- CRUD completo de leads

**ChatController**
- `getMessages()` - Retorna mensagens em JSON
- `sendMessage()` - Salva mensagem e envia para Evolution API

**WebhookController**
- `receiveMessage()` - Recebe webhook da Evolution API

### Views

```
resources/views/
├── layouts/
│   └── crm.blade.php (Layout principal)
└── leads/
    ├── index.blade.php (Lista de leads)
    ├── create.blade.php (Criar lead)
    ├── edit.blade.php (Editar lead)
    └── show.blade.php (Detalhes + Chat)
```

## Funcionalidades

### 1. Autenticação
- Login/Logout usando Laravel Breeze
- Proteção de rotas com middleware `auth`

### 2. Gestão de Leads
- ✅ Listar todos os leads
- ✅ Criar novo lead
- ✅ Editar lead existente
- ✅ Excluir lead
- ✅ Visualizar detalhes do lead

### 3. Chat com WhatsApp
- ✅ Exibir histórico de mensagens
- ✅ Enviar mensagens
- ✅ Pooling automático a cada 3 segundos (Alpine.js)
- ✅ Interface responsiva
- ✅ Diferenciação visual entre mensagens do usuário e do lead

### 4. Integração com Evolution API

**Configuração:**

Adicione no arquivo `.env`:

```env
EVOLUTION_API_URL=https://sua-evolution-api.com
EVOLUTION_API_KEY=sua-chave-api
EVOLUTION_INSTANCE_NAME=nome-da-instancia
```

**Webhook:**

Configure no Evolution API para enviar webhooks para:
```
https://seu-dominio.com/webhook/messages
```

**Estrutura esperada do webhook:**
```json
{
  "data": {
    "key": {
      "remoteJid": "5511999999999@s.whatsapp.net"
    },
    "message": {
      "conversation": "Texto da mensagem"
    }
  }
}
```

## Características Técnicas

### Frontend
- **Tailwind CSS**: Estilização responsiva e moderna
- **Alpine.js**: Reatividade para o chat (pooling e envio de mensagens)
- **Blade Templates**: Views server-side simples

### Backend
- **Laravel 12**: Framework PHP moderno
- **Eloquent ORM**: Relacionamentos e queries
- **API REST**: Endpoints JSON para chat
- **Validação**: Requests validados

### Chat (Alpine.js)
- Pooling a cada 3 segundos
- Atualização automática de mensagens
- Scroll automático
- Feedback visual durante envio
- Formatação de datas

## Desenvolvimento

### Adicionar novos status de leads

Edite `resources/views/leads/create.blade.php` e `edit.blade.php`:

```html
<option value="novo_status">Novo Status</option>
```

### Personalizar layout

Edite `resources/views/layouts/crm.blade.php`

### Alterar intervalo de pooling

Em `resources/views/leads/show.blade.php`, altere:

```javascript
this.pollingInterval = setInterval(() => {
    this.fetchMessages();
}, 3000); // 3000ms = 3 segundos
```

## Segurança

- ✅ CSRF Protection em todos os formulários
- ✅ Autenticação obrigatória para rotas principais
- ✅ Webhook sem CSRF (rota pública)
- ✅ Validação de inputs
- ✅ Passwords hasheados

## Troubleshooting

### Erro de conexão com banco de dados
- Verifique se o MAMP está rodando
- Confirme a porta do MySQL (padrão 8889)
- Verifique usuário e senha no `.env`

### Assets não carregam
```bash
npm run build
```

### Mensagens não aparecem no chat
- Verifique o console do navegador (F12)
- Confirme que a rota `/leads/{id}/messages` está funcionando
- Verifique se há erros de JavaScript

### Webhook não funciona
- Certifique-se de que a rota está acessível publicamente
- Use ngrok para testes locais: `ngrok http 8000`
- Verifique logs: `tail -f storage/logs/laravel.log`

## Próximas Melhorias (Sugestões)

- [ ] Adicionar paginação na lista de leads
- [ ] Filtros e busca de leads
- [ ] Notificações de novas mensagens
- [ ] Upload de imagens no chat
- [ ] Relatórios e dashboard
- [ ] Tags para leads
- [ ] Atribuição de leads a usuários
- [ ] Histórico de ações

## Licença

Este projeto é um exemplo educacional e pode ser usado livremente.
