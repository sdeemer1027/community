<style>
    #community-chat-widget {
        position: fixed;
        right: 20px;
        bottom: 0;
        z-index: 9999;
        font-family: inherit;
    }

    #community-chat-toggle {
        display: block;
        margin-left: auto;
        padding: 12px 22px;
        background: #2563eb;
        color: white;
        border: 0;
        border-radius: 12px 12px 0 0;
        cursor: pointer;
        font-weight: 600;
        box-shadow: 0 2px 12px #0002;
    }

    #community-chat-panel {
        display: none;
        width: 300px;
        max-height: 420px;
        margin-bottom: 8px;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        box-shadow: 0 4px 20px #0003;
        overflow: hidden;
    }

    #community-chat-panel.open {
        display: block;
    }

    .community-chat-panel-header {
        padding: 12px 14px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #community-chat-close-list {
        background: transparent;
        color: white;
        border: 0;
        cursor: pointer;
        font-size: 20px;
    }

    #community-chat-online-list {
        max-height: 350px;
        overflow-y: auto;
    }

    .community-chat-person {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    
.community-chat-avatar {
    width: 28px;
    height: 28px;
    min-width: 28px;
    border-radius: 50%;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
    color: #4b5563;
    font-size: 16px;
}

.community-chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.community-chat-window-header .community-chat-avatar {
    width: 24px;
    height: 24px;
    min-width: 24px;
    font-size: 14px;
}


    .community-chat-person button {
        padding: 6px 10px;
        border: 0;
        border-radius: 6px;
        background: #2563eb;
        color: white;
        cursor: pointer;
    }

    .community-chat-empty {
        padding: 20px 14px;
        color: #6b7280;
        text-align: center;
        font-size: 14px;
    }

    #community-chat-windows {
        position: fixed;
        right: 330px;
        bottom: 0;
        display: flex;
        flex-direction: row-reverse;
        align-items: flex-end;
        gap: 10px;
        z-index: 9998;
        max-width: calc(100vw - 350px);
        overflow-x: auto;
    }

    .community-chat-window {
        width: 300px;
        min-width: 300px;
        height: 360px;
        display: flex;
        flex-direction: column;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 2px 15px #0003;
        overflow: hidden;
    }

.community-chat-window-header {
    padding: 10px;
    background: #1d4ed8;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
}

.community-chat-window-header .community-chat-identity {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
}

.community-chat-window-header .community-chat-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.community-chat-window-header .community-chat-actions button {
    padding: 5px 9px;
    border: 1px solid rgba(255, 255, 255, 0.65);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.12);
    color: white;
    font-size: 12px;
    cursor: pointer;
}

.community-chat-window-header .community-chat-actions button:hover {
    background: rgba(255, 255, 255, 0.25);
}

.community-chat-window-header .community-chat-close {
    margin-left: auto;
    border: none;
    background: transparent;
    color: white;
    font-size: 22px;
    cursor: pointer;
}


    .community-chat-window-header button {
        border: 0;
        color: white;
        background: transparent;
        cursor: pointer;
        font-size: 18px;
    }

    .community-chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        background: #f9fafb;
    }

    .community-chat-message {
        margin: 0 0 10px;
        padding: 8px 10px;
        max-width: 85%;
        overflow-wrap: anywhere;
        border-radius: 10px;
        background: #e5e7eb;
        color: #111827;
        font-size: 14px;
    }

    .community-chat-message.mine {
        margin-left: auto;
        background: #dbeafe;
    }

    .community-chat-compose {
        display: flex;
        gap: 6px;
        padding: 8px;
        border-top: 1px solid #e5e7eb;
    }

    .community-chat-compose input {
        flex: 1;
        min-width: 0;
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .community-chat-compose button {
        padding: 8px 10px;
        border: 0;
        border-radius: 6px;
        background: #2563eb;
        color: white;
        cursor: pointer;
    }

    @media (max-width: 650px) {
        #community-chat-widget {
            right: 8px;
        }

        #community-chat-windows {
            right: 8px;
            bottom: 52px;
            max-width: calc(100vw - 16px);
        }

        .community-chat-window {
            width: min(300px, calc(100vw - 24px));
            min-width: min(300px, calc(100vw - 24px));
        }
    }
</style>

<div id="community-chat-widget">
    <div id="community-chat-panel">
        <div class="community-chat-panel-header">
            <span>Online Connections</span>
            <button id="community-chat-close-list" type="button" aria-label="Close chat list">&times;</button>
        </div>
        <div id="community-chat-online-list">
            <div class="community-chat-empty">Loading online connections...</div>
        </div>
    </div>

    <button id="community-chat-toggle" type="button">
        Chat
    </button>
</div>

<div id="community-chat-windows"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('community-chat-toggle');
    const panel = document.getElementById('community-chat-panel');
    const closeList = document.getElementById('community-chat-close-list');
    const onlineList = document.getElementById('community-chat-online-list');
    const windowsContainer = document.getElementById('community-chat-windows');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const openConversations = new Map();
    const currentUserId = {{ (int) auth()->id() }};

    async function api(url, options = {}) {
        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                ...(options.headers || {})
            }
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || 'The request could not be completed.');
        }

        return data;
    }

    function showError(message) {
        alert(message);
    }

    
async function loadOnlineConnections() {
    try {
        const data = await api('/chat/online');
        onlineList.replaceChildren();

        if (!data.connections || data.connections.length === 0) {
            onlineList.innerHTML =
                '<div class="community-chat-empty">No approved connections are online right now.</div>';
            return;
        }

        data.connections.forEach(function (user) {
            const row = document.createElement('div');
            row.className = 'community-chat-person';

            const avatar = document.createElement('span');
            avatar.className = 'community-chat-avatar';

            if (user.profile_photo_path) {
                const image = document.createElement('img');
                image.src = '/storage/' + user.profile_photo_path;
                image.alt = '';
                avatar.appendChild(image);
            } else {
                avatar.textContent = '👤';
            }

            const name = document.createElement('span');
            name.textContent = user.name;

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = 'Chat';
            button.addEventListener('click', function () {
                openConversation(
                    user.id,
                    user.name,
                    user.profile_photo_path
                );
            });

            row.append(avatar, name, button);
            onlineList.appendChild(row);
        });
    } catch (error) {
        onlineList.innerHTML =
            '<div class="community-chat-empty">Unable to load online connections.</div>';
    }
}


    
async function openConversation(userId, userName, profilePhotoPath = null) {
    const existing = openConversations.get(Number(userId));

    if (existing) {
        existing.element.style.display = 'flex';
        return;
    }

    try {
        const data = await api('/chat/conversations/' + userId, {
            method: 'POST'
        });

        createChatWindow(
            data.conversation.id,
            Number(userId),
            userName,
            profilePhotoPath
        );
    } catch (error) {
        showError(error.message);
    }
}


    function createChatWindow(conversationId, userId, userName, profilePhotoPath = null) {
        const windowElement = document.createElement('section');
        windowElement.className = 'community-chat-window';

        const header = document.createElement('div');
        header.className = 'community-chat-window-header';

        const title = document.createElement('strong');
        title.textContent = userName;


const avatar = document.createElement('span');
avatar.className = 'community-chat-avatar';

if (profilePhotoPath) {
    const image = document.createElement('img');
    image.src = '/storage/' + profilePhotoPath;
    image.alt = '';
    image.onerror = function () {
        avatar.replaceChildren();
        avatar.textContent = '👤';
    };
    avatar.appendChild(image);
} else {
    avatar.textContent = '👤';
}


        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.textContent = '\u00d7';
        closeButton.setAttribute('aria-label', 'Close conversation');
        closeButton.addEventListener('click', function () {
            windowElement.remove();
            openConversations.delete(userId);
        });

        
        const blockChatButton = document.createElement('button');
        blockChatButton.type = 'button';
        blockChatButton.textContent = 'Block Chat';

        blockChatButton.addEventListener('click', async function () {
            if (!confirm('Block chat with ' + userName + '?')) return;

            try {
                await api('/chat/users/' + userId + '/block-chat', {
                    method: 'POST'
                });

                windowElement.remove();
                clearInterval(chat.refreshTimer);
                openConversations.delete(userId);
            } catch (error) {
                showError(error.message);
            }
        });

        const blockConnectionButton = document.createElement('button');
        blockConnectionButton.type = 'button';
        blockConnectionButton.textContent = 'Block Connection';

        blockConnectionButton.addEventListener('click', async function () {
            if (!confirm('Block your connection with ' + userName + '?')) return;

            try {
                await api('/chat/users/' + userId + '/block-connection', {
                    method: 'POST'
                });

                windowElement.remove();
                clearInterval(chat.refreshTimer);
                openConversations.delete(userId);
            } catch (error) {
                showError(error.message);
            }
        });


closeButton.className = 'community-chat-close';

const identity = document.createElement('div');
identity.className = 'community-chat-identity';
identity.append(avatar, title, closeButton);

const actions = document.createElement('div');
actions.className = 'community-chat-actions';
actions.append(blockChatButton, blockConnectionButton);

header.append(identity, actions);



        const messages = document.createElement('div');
        messages.className = 'community-chat-messages';

        const compose = document.createElement('form');
        compose.className = 'community-chat-compose';

        const input = document.createElement('input');
        input.type = 'text';
        input.maxLength = 5000;
        input.placeholder = 'Write a message...';
        input.autocomplete = 'off';
        input.required = true;

        const sendButton = document.createElement('button');
        sendButton.type = 'submit';
        sendButton.textContent = 'Send';

        compose.append(input, sendButton);
        windowElement.append(header, messages, compose);
        windowsContainer.appendChild(windowElement);

        const chat = {
            element: windowElement,
            messagesElement: messages,
            conversationId: conversationId,
            userId: userId,
            lastMessageId: 0
        };

        openConversations.set(userId, chat);

        async function loadMessages() {
            try {
                const data = await api(
                    '/chat/conversations/' + conversationId + '/messages'
                );

                messages.replaceChildren();

                (data.messages || []).forEach(function (message) {
                    const bubble = document.createElement('div');
                    bubble.className = 'community-chat-message';

                    if (Number(message.sender_id) === currentUserId) {
                        bubble.classList.add('mine');
                    }

                    const sender = document.createElement('strong');
                    sender.textContent = (message.sender?.name || 'Member') + ': ';

                    const body = document.createElement('span');
                    body.textContent = message.body;

                    bubble.append(sender, body);
                    messages.appendChild(bubble);

                    chat.lastMessageId = Math.max(
                        chat.lastMessageId,
                        Number(message.id)
                    );
                });

                messages.scrollTop = messages.scrollHeight;
                updateUnreadCount();
            } catch (error) {
                messages.textContent = 'Unable to load messages.';
            }
        }

        compose.addEventListener('submit', async function (event) {
            event.preventDefault();

            const body = input.value.trim();
            if (!body) return;

            sendButton.disabled = true;

            try {
                await api('/chat/conversations/' + conversationId + '/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ body: body })
                });

                input.value = '';
                await loadMessages();
            } catch (error) {
                showError(error.message);
            } finally {
                sendButton.disabled = false;
                input.focus();
            }
        });

        loadMessages();
        chat.refreshTimer = setInterval(loadMessages, 5000);
        closeButton.addEventListener('click', function () {
            clearInterval(chat.refreshTimer);
        });
    }


    async function updateUnreadCount() {
        try {
            const data = await api('/chat/unread-count');
            const count = Number(data.unread_count || 0);
            toggle.textContent = count > 0
                ? 'Chat (' + count + ')'
                : 'Chat';
        } catch (error) {
            // Keep the chat button usable if the count cannot be loaded.
        }
    }

    updateUnreadCount();
    setInterval(updateUnreadCount, 5000);

    toggle.addEventListener('click', async function () {
        panel.classList.toggle('open');

        if (panel.classList.contains('open')) {
            await loadOnlineConnections();
        }
    });

    closeList.addEventListener('click', function () {
        panel.classList.remove('open');
    });

    setInterval(function () {
        if (panel.classList.contains('open')) {
            loadOnlineConnections();
        }
    }, 15000);
});
</script>