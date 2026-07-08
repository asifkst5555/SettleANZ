@extends('admin.layouts.app')

@section('page-title', 'AI Admin Assistant')

@section('content')
<style>
    .chat-container { display:grid; grid-template-columns:280px 1fr; gap:1.5rem; height:calc(100vh - 12rem); }
    .chat-sidebar { background:white; border:1px solid #e5e7eb; border-radius:0.75rem; display:flex; flex-direction:column; overflow:hidden; }
    .chat-sidebar-header { padding:1rem; border-bottom:1px solid #e5e7eb; }
    .chat-conv-list { flex:1; overflow-y:auto; padding:0.5rem; }
    .chat-conv-item { padding:0.625rem; border-radius:0.5rem; cursor:pointer; transition:background 0.2s; }
    .chat-conv-item:hover { background:#f9fafb; }
    .chat-main { background:white; border:1px solid #e5e7eb; border-radius:0.75rem; display:flex; flex-direction:column; overflow:hidden; }
    .chat-messages { flex:1; overflow-y:auto; padding:1.5rem; }
    .chat-input-area { border-top:1px solid #e5e7eb; padding:1rem; }
    .chat-bubble-user { display:inline-block; background:#2563eb; color:white; border-radius:12px 12px 4px 12px; padding:0.625rem 1rem; max-width:75%; }
    .chat-bubble-ai { display:inline-block; background:#f3f4f6; color:#1f2937; border-radius:12px 12px 12px 4px; padding:0.625rem 1rem; max-width:75%; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>AI Admin Assistant</h2>
            <p>Use AI to manage leads, send emails, and run reports using natural language</p>
        </div>
    </section>

    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <h3 style="font-size:0.9375rem;font-weight:600;">Conversations</h3>
            </div>
            <div class="chat-conv-list">
                @if($conversations->isNotEmpty())
                    @foreach($conversations as $conv)
                    <div class="chat-conv-item" onclick="loadConversation({{ $conv->id }})">
                        <div style="font-size:0.875rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $conv->title ?? 'Untitled' }}</div>
                        <div style="font-size:0.8125rem;color:#6b7280;">{{ $conv->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                @else
                <p style="color:#6b7280;font-size:0.875rem;padding:0.5rem;text-align:center;">No conversations yet.</p>
                @endif
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-messages" id="chat-messages">
                <div style="text-align:center;color:#6b7280;padding:3rem 1rem;">
                    <svg style="width:48px;height:48px;margin:0 auto 1rem;color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <p style="font-weight:500;color:#374151;">AI Admin Assistant</p>
                    <p style="font-size:0.875rem;margin-top:0.25rem;">Try commands like:</p>
                    <div style="font-size:0.8125rem;margin-top:0.5rem;color:#9ca3af;">
                        <p>"Send the SEO ebook to john@example.com."</p>
                        <p>"Resend yesterday's download links."</p>
                        <p>"Email everyone who downloaded the Marketing Guide."</p>
                        <p>"Show today's download analytics."</p>
                    </div>
                </div>
            </div>

            <div class="chat-input-area">
                <form id="chat-form" style="display:flex;gap:0.75rem;">
                    @csrf
                    <input type="text" id="chat-input" placeholder="Type your command..." autofocus
                        style="flex:1;border:1px solid #d1d5db;border-radius:0.5rem;padding:0.75rem 1rem;font-size:0.9375rem;">
                    <button type="submit" style="background:#2563eb;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.5rem;cursor:pointer;font-weight:600;">Send</button>
                </form>
                <div style="display:flex;gap:0.5rem;margin-top:0.5rem;align-items:center;">
                    <span style="font-size:0.8125rem;color:#9ca3af;">Quick:</span>
                    <button onclick="quickCommand('Show today\\'s download analytics.')" style="font-size:0.8125rem;color:#2563eb;background:none;border:none;cursor:pointer;text-decoration:underline;">Analytics</button>
                    <button onclick="quickCommand('List all ebooks.')" style="font-size:0.8125rem;color:#2563eb;background:none;border:none;cursor:pointer;text-decoration:underline;">Ebooks</button>
                    <button onclick="quickCommand('List recent leads.')" style="font-size:0.8125rem;color:#2563eb;background:none;border:none;cursor:pointer;text-decoration:underline;">Leads</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let conversationId = null;

document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    addMessage('user', message);
    input.value = '';

    const messagesDiv = document.getElementById('chat-messages');
    const loadingDiv = document.createElement('div');
    loadingDiv.style.cssText = 'text-align:left;margin-bottom:1rem;';
    loadingDiv.innerHTML = '<div style="display:inline-block;background:#f3f4f6;border-radius:12px;padding:0.625rem 1rem;color:#6b7280;font-size:0.875rem;">Thinking...</div>';
    messagesDiv.appendChild(loadingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;

    try {
        const response = await fetch('{{ route("admin.ai-assistant.chat") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message, conversation_id: conversationId }),
        });
        const data = await response.json();
        loadingDiv.remove();
        addMessage('assistant', data.message);
    } catch (err) {
        loadingDiv.remove();
        addMessage('assistant', 'Sorry, something went wrong. Please try again.');
    }
});

function addMessage(role, content) {
    const div = document.createElement('div');
    div.style.cssText = 'margin-bottom:1rem;' + (role === 'user' ? 'text-align:right;' : 'text-align:left;');
    div.innerHTML = `<div class="${role === 'user' ? 'chat-bubble-user' : 'chat-bubble-ai'}" style="white-space:pre-wrap;">${escapeHtml(content)}</div>`;
    document.getElementById('chat-messages').appendChild(div);
    document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
}

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

function quickCommand(cmd) {
    document.getElementById('chat-input').value = cmd;
    document.getElementById('chat-form').dispatchEvent(new Event('submit'));
}

async function loadConversation(id) {
    try {
        const response = await fetch('/admin/ai-assistant/conversations/' + id);
        const data = await response.json();
        const container = document.getElementById('chat-messages');
        container.innerHTML = '';
        data.conversation.messages.forEach(msg => addMessage(msg.role, msg.content));
    } catch (err) {
        console.error('Failed to load conversation:', err);
    }
}
</script>
@endsection
