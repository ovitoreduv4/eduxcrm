@extends('layouts.crm')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Lead: {{ $lead->nome }}</h1>
        <a href="{{ route('leads.index') }}" class="text-blue-600 hover:text-blue-900">Voltar</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Informações do Lead -->
    <div class="md:col-span-1">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Informações</h2>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Nome:</p>
                <p class="font-medium">{{ $lead->nome }}</p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Telefone:</p>
                <p class="font-medium">{{ $lead->telefone }}</p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Status:</p>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    @if($lead->status === 'novo') bg-green-100 text-green-800
                    @elseif($lead->status === 'em_contato') bg-blue-100 text-blue-800
                    @elseif($lead->status === 'convertido') bg-purple-100 text-purple-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                </span>
            </div>
            <div class="mt-6">
                <a href="{{ route('leads.edit', $lead) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                    Editar Lead
                </a>
            </div>
        </div>
    </div>

    <!-- Chat -->
    <div class="md:col-span-2">
        <div class="bg-white shadow-md rounded-lg" x-data="chatApp({{ $lead->id }})">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">Chat WhatsApp</h2>
            </div>

            <!-- Mensagens -->
            <div class="h-96 overflow-y-auto p-4 bg-gray-50" id="messages-container">
                <template x-for="message in messages" :key="message.id">
                    <div class="mb-3" :class="message.author === 'user' ? 'text-right' : 'text-left'">
                        <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg"
                             :class="message.author === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-800'">
                            <p class="text-sm" x-text="message.content"></p>
                            <p class="text-xs mt-1 opacity-75" x-text="formatDate(message.created_at)"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Formulário de Envio -->
            <div class="p-4 border-t">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input type="text" x-model="newMessage" placeholder="Digite sua mensagem..." required
                           class="flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <button type="submit" :disabled="sending"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50">
                        <span x-show="!sending">Enviar</span>
                        <span x-show="sending">Enviando...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function chatApp(leadId) {
    return {
        messages: @json($messages),
        newMessage: '',
        sending: false,
        leadId: leadId,
        pollingInterval: null,

        init() {
            this.scrollToBottom();
            this.startPolling();
        },

        startPolling() {
            this.pollingInterval = setInterval(() => {
                this.fetchMessages();
            }, 3000);
        },

        async fetchMessages() {
            try {
                const response = await fetch(`/leads/${this.leadId}/messages`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.length !== this.messages.length) {
                        this.messages = data;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                }
            } catch (error) {
                console.error('Erro ao buscar mensagens:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            this.sending = true;
            try {
                const response = await fetch(`/leads/${this.leadId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        content: this.newMessage
                    })
                });

                if (response.ok) {
                    const message = await response.json();
                    this.messages.push(message);
                    this.newMessage = '';
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    alert('Erro ao enviar mensagem');
                }
            } catch (error) {
                console.error('Erro ao enviar mensagem:', error);
                alert('Erro ao enviar mensagem');
            } finally {
                this.sending = false;
            }
        },

        scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endsection
