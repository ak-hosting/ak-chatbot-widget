/**
 * Chatbot Widget für externe Integration
 * Eigenständiges Widget, das in beliebige Webseiten eingebettet werden kann
 * 
 * Verwendung:
 * <script src="http://localhost:5000/static/js/chatbot-widget.js"></script>
 * <script>
 *   new ChatbotWidget({
 *     apiUrl: 'http://localhost:5000',
 *     position: 'bottom-right',
 *     theme: 'default'
 *   });
 * </script>
 */

(function() {
    'use strict';

    class ChatbotWidget {
        constructor(options = {}) {
            this.config = {
                apiUrl: options.apiUrl || 'http://localhost:5000', // Produktionsumgebung: 'https://chatbot-api.ak-pro.com'
                position: options.position || 'bottom-right',
                theme: options.theme || 'default',
                language: options.language || 'de',
                title: options.title || 'Chat Support',
                welcomeMessage: options.welcomeMessage || 'Hallo! Wie kann ich Ihnen helfen?',
                placeholder: options.placeholder || 'Schreiben Sie Ihre Nachricht...',
                buttonText: options.buttonText || 'Senden',
                minimized: options.minimized !== false,
                zIndex: options.zIndex || 10000
            };

            this.sessionId = null;
            this.isOpen = false;
            this.isTyping = false;
            this.messageHistory = [];

            this.init();
        }

        init() {
            this.injectStyles();
            this.createWidget();
            this.bindEvents();
            this.createSession();
        }

        injectStyles() {
            if (document.getElementById('chatbot-widget-styles')) return;

            const styles = `
                .chatbot-widget {
                    position: fixed;
                    z-index: ${this.config.zIndex};
                    font-family: 'Inter', 'Open Sans', sans-serif;
                    font-size: 14px;
                    line-height: 1.4;
                }

                .chatbot-widget.bottom-right {
                    bottom: 20px;
                    right: 20px;
                }

                .chatbot-widget.bottom-left {
                    bottom: 20px;
                    left: 20px;
                }

                .chatbot-widget.top-right {
                    top: 20px;
                    right: 20px;
                }

                .chatbot-widget.top-left {
                    top: 20px;
                    left: 20px;
                }

                .chatbot-toggle {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    color: white;
                    cursor: pointer;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }

                .chatbot-toggle:hover {
                    transform: scale(1.1);
                    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
                }

                .chatbot-toggle svg {
                    width: 24px;
                    height: 24px;
                }

                .chatbot-container {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 400px;
                    height: 600px;
                    max-height: 80vh;
                    overflow: hidden;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                    display: none;
                    flex-direction: column;
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.3s ease;
                }

                .chatbot-container.open {
                    display: flex;
                    opacity: 1;
                    visibility: visible;
                }

                .chatbot-header {
                    flex: 0 1 60px;
                    position: relative;
                    z-index: 2;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: #fff;
                    text-align: left;
                    padding: 15px 20px 15px 70px;
                    border-radius: 20px 20px 0 0;
                }

                .chatbot-title {
                    font-weight: 600;
                    font-size: 16px;
                    margin: 0;
                    padding: 0;
                }

                .chatbot-subtitle {
                    color: rgba(255, 255, 255, 0.8);
                    font-size: 12px;
                    font-weight: 400;
                    letter-spacing: 0.5px;
                    margin: 2px 0 0 0;
                    padding: 0;
                }

                .chatbot-avatar {
                    position: absolute;
                    z-index: 1;
                    top: 15px;
                    left: 20px;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    overflow: hidden;
                    margin: 0;
                    padding: 0;
                    border: 2px solid rgba(255, 255, 255, 0.3);
                    background: rgba(255, 255, 255, 0.1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                }

                .chatbot-close {
                    position: absolute;
                    right: 15px;
                    top: 15px;
                    height: 30px;
                    width: 30px;
                    background: none;
                    border: none;
                    color: rgba(255, 255, 255, 0.8);
                    cursor: pointer;
                    border-radius: 50%;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                }

                .chatbot-close:hover {
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                }

                .chatbot-messages {
                    flex: 1 1 auto;
                    color: #333;
                    overflow: hidden;
                    position: relative;
                    width: 100%;
                    background: #f8f9fa;
                }

                .chatbot-messages-content {
                    position: absolute;
                    top: 0;
                    left: 0;
                    height: 101%;
                    width: 100%;
                    padding: 20px;
                    overflow-y: auto;
                }

                .chatbot-message {
                    clear: both;
                    margin: 15px 0;
                    animation: fadeInUp 0.3s ease;
                }

                .chatbot-message.bot {
                    float: left;
                }

                .chatbot-message.user {
                    float: right;
                }

                .chatbot-message-content {
                    max-width: 80%;
                    padding: 12px 16px;
                    border-radius: 18px;
                    font-size: 14px;
                    line-height: 1.4;
                    position: relative;
                    word-wrap: break-word;
                }

                .chatbot-message.bot .chatbot-message-content {
                    background: #e9ecef;
                    color: #333;
                    margin-left: 10px;
                    border-bottom-left-radius: 4px;
                }

                .chatbot-message.user .chatbot-message-content {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    margin-right: 10px;
                    border-bottom-right-radius: 4px;
                    float: right;
                }

                .chatbot-message-avatar {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 12px;
                    float: left;
                    margin-right: 10px;
                    position: relative;
                    z-index: 5;
                }

                .chatbot-typing {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    margin: 15px 0;
                    float: left;
                    clear: both;
                }

                .chatbot-typing .chatbot-message-avatar {
                    margin-right: 0;
                    flex-shrink: 0;
                }

                .chatbot-typing-content {
                    padding: 16px;
                    background: #e9ecef;
                    border-radius: 18px;
                    border-bottom-left-radius: 4px;
                    margin-left: 10px;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }

                .chatbot-typing-dot {
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    background: #94a3b8;
                    animation: chatbot-typing 1.4s infinite;
                }

                .chatbot-typing-dot:nth-child(2) {
                    animation-delay: 0.2s;
                }

                .chatbot-typing-dot:nth-child(3) {
                    animation-delay: 0.4s;
                }

                @keyframes chatbot-typing {
                    0%, 60%, 100% {
                        transform: translateY(0);
                        opacity: 0.4;
                    }
                    30% {
                        transform: translateY(-10px);
                        opacity: 1;
                    }
                }

                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .chatbot-input-container {
                    flex: 0 1 auto;
                    background: #fff;
                    border-top: 1px solid #e9ecef;
                    padding: 15px 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .chatbot-input {
                    flex: 1;
                    border: 1px solid #ddd;
                    border-radius: 25px;
                    padding: 12px 18px;
                    font-size: 14px;
                    resize: none;
                    outline: none;
                    max-height: 100px;
                    min-height: 40px;
                    transition: all 0.3s ease;
                    background: #f8f9fa;
                }

                .chatbot-input:focus {
                    border-color: #667eea;
                    background: white;
                    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                }

                .chatbot-send {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    color: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
                }

                .chatbot-send:hover {
                    transform: scale(1.1);
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                }

                .chatbot-send:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                    transform: none;
                }

                .chatbot-suggestions {
                    padding: 10px 20px;
                    background: #f8f9fa;
                    border-top: 1px solid #e9ecef;
                    max-height: 120px;
                    overflow-y: auto;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px;
                    align-items: center;
                }

                .chatbot-suggestion {
                    background: white;
                    border: 1px solid #dee2e6;
                    border-radius: 16px;
                    padding: 6px 12px;
                    font-size: 12px;
                    color: #495057;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    white-space: nowrap;
                    max-width: 200px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .chatbot-suggestion:hover {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border-color: transparent;
                    transform: translateY(-1px);
                }

                .chatbot-product-cards {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    margin-top: 12px;
                }

                .chatbot-product-card {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 12px;
                    transition: all 0.2s;
                }

                .chatbot-product-card:hover {
                    border-color: #667eea;
                    transform: translateY(-1px);
                }

                .chatbot-product-name {
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 4px;
                }

                .chatbot-product-price {
                    color: #667eea;
                    font-weight: 600;
                    margin-bottom: 8px;
                }

                .chatbot-product-link {
                    color: #667eea;
                    text-decoration: none;
                    font-size: 13px;
                    font-weight: 500;
                }

                .chatbot-product-link:hover {
                    text-decoration: underline;
                }

                @media (max-width: 480px) {
                    .chatbot-container {
                        width: 95vw;
                        height: 90vh;
                        max-height: 90vh;
                    }
                }
            `;

            const styleSheet = document.createElement('style');
            styleSheet.id = 'chatbot-widget-styles';
            styleSheet.textContent = styles;
            document.head.appendChild(styleSheet);
        }

        createWidget() {
            const widget = document.createElement('div');
            widget.className = `chatbot-widget ${this.config.position}`;
            widget.innerHTML = `
                <button class="chatbot-toggle" id="chatbot-toggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </button>
                <div class="chatbot-container" id="chatbot-container">
                    <div class="chatbot-header">
                        <div class="chatbot-avatar">🤖</div>
                        <div>
                            <div class="chatbot-title">${this.config.title}</div>
                            <div class="chatbot-subtitle">Online • Antwortet normalerweise sofort</div>
                        </div>
                        <button class="chatbot-close" id="chatbot-close">×</button>
                    </div>
                    <div class="chatbot-messages" id="chatbot-messages">
                        <div class="chatbot-messages-content" id="chatbot-messages-content"></div>
                    </div>
                    <div class="chatbot-suggestions" id="chatbot-suggestions"></div>
                    <div class="chatbot-input-container">
                        <input type="text" class="chatbot-input" id="chatbot-input" placeholder="${this.config.placeholder}">
                        <button class="chatbot-send" id="chatbot-send">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 2L11 13"/>
                                <path d="M22 2L15 22L11 13L2 9L22 2Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(widget);
            this.widget = widget;
        }

        bindEvents() {
            const toggle = this.widget.querySelector('#chatbot-toggle');
            const close = this.widget.querySelector('#chatbot-close');
            const input = this.widget.querySelector('#chatbot-input');
            const send = this.widget.querySelector('#chatbot-send');

            toggle.addEventListener('click', () => this.toggleChat());
            close.addEventListener('click', () => this.closeChat());
            send.addEventListener('click', () => this.sendMessage());

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            input.addEventListener('input', () => this.autoResize(input));
        }

        toggleChat() {
            const container = this.widget.querySelector('#chatbot-container');
            const toggle = this.widget.querySelector('#chatbot-toggle');

            if (this.isOpen) {
                this.closeChat();
            } else {
                this.isOpen = true;
                container.classList.add('open');
                toggle.classList.add('open');
                
                if (this.messageHistory.length === 0) {
                    this.addMessage(this.config.welcomeMessage, 'bot');
                    
                    // Show initial suggestions
                    const initialSuggestions = [
                        'Welche Produkte bieten Sie an?',
                        'Wie kann ich Sie kontaktieren?',
                        'Öffnungszeiten',
                        'Preise und Angebote'
                    ];
                    setTimeout(() => {
                        this.showSuggestions(initialSuggestions);
                    }, 500);
                }
                
                setTimeout(() => {
                    this.widget.querySelector('#chatbot-input').focus();
                }, 300);
            }
        }

        closeChat() {
            const container = this.widget.querySelector('#chatbot-container');
            const toggle = this.widget.querySelector('#chatbot-toggle');

            this.isOpen = false;
            container.classList.remove('open');
            toggle.classList.remove('open');
        }

        autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
        }

        async createSession() {
            try {
                const response = await fetch(`${this.config.apiUrl}/api/chat/session/new`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include'
                });

                if (response.ok) {
                    const data = await response.json();
                    this.sessionId = data.session_id;
                }
            } catch (error) {
                console.error('Failed to create session:', error);
            }
        }

        async sendMessage() {
            const input = this.widget.querySelector('#chatbot-input');
            const message = input.value.trim();

            if (!message || this.isTyping) return;

            // Add user message
            this.addMessage(message, 'user');
            input.value = '';
            
            // Hide suggestions after sending message
            this.widget.querySelector('#chatbot-suggestions').innerHTML = '';

            // Show typing indicator
            this.showTyping();

            try {
                const response = await fetch(`${this.config.apiUrl}/api/chat/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        message: message,
                        session_id: this.sessionId,
                        language: this.config.language
                    })
                });

                this.hideTyping();

                if (response.ok) {
                    const data = await response.json();
                    const botResponse = data.bot_response || {};
                    
                    this.addMessage(
                        botResponse.message || data.response || 'Keine Antwort erhalten', 
                        'bot', 
                        false, 
                        botResponse.links || data.links, 
                        botResponse.products || data.products
                    );
                    
                    // Show suggestions if available
                    const suggestions = botResponse.suggestions || data.suggestions;
                    if (suggestions && suggestions.length > 0) {
                        this.showSuggestions(suggestions);
                    }
                    
                    if (data.session_id) {
                        this.sessionId = data.session_id;
                    }
                } else {
                    this.addMessage('Entschuldigung, es gab einen Fehler. Bitte versuchen Sie es erneut.', 'bot');
                }
            } catch (error) {
                this.hideTyping();
                this.addMessage('Verbindungsfehler. Bitte überprüfen Sie Ihre Internetverbindung.', 'bot');
                console.error('Error sending message:', error);
            }
        }

        addMessage(text, sender, isWelcome = false, links = null, products = null) {
            const messagesContainer = this.widget.querySelector('#chatbot-messages-content');
            const messageDiv = document.createElement('div');
            messageDiv.className = `chatbot-message ${sender}`;

            if (sender === 'bot') {
                const avatar = document.createElement('div');
                avatar.className = 'chatbot-message-avatar';
                avatar.textContent = '🤖';
                messageDiv.appendChild(avatar);
            }

            const content = document.createElement('div');
            content.className = 'chatbot-message-content';
            content.innerHTML = this.formatMessage(text);
            messageDiv.appendChild(content);

            // Add products if available
            if (products && products.length > 0) {
                const productsDiv = this.createProductCards(products);
                messageDiv.appendChild(productsDiv);
            }

            messagesContainer.appendChild(messageDiv);
            this.messageHistory.push({ text, sender, timestamp: new Date() });
            
            // Scroll to bottom
            const messagesElement = this.widget.querySelector('#chatbot-messages');
            messagesElement.scrollTop = messagesElement.scrollHeight;
        }

        createProductCards(products) {
            const container = document.createElement('div');
            container.className = 'chatbot-product-cards';

            products.forEach(product => {
                const card = document.createElement('div');
                card.className = 'chatbot-product-card';
                card.innerHTML = `
                    <div class="chatbot-product-name">${product.name}</div>
                    <div class="chatbot-product-price">${product.price}</div>
                    <a href="${product.link}" target="_blank" class="chatbot-product-link">
                        Mehr Details
                    </a>
                `;
                container.appendChild(card);
            });

            return container;
        }

        formatMessage(text) {
            // Basic HTML formatting
            return text
                .replace(/\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>');
        }

        showTyping() {
            if (this.isTyping) return;
            
            this.isTyping = true;
            const messagesContainer = this.widget.querySelector('#chatbot-messages-content');
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chatbot-typing';
            typingDiv.id = 'chatbot-typing-indicator';
            
            const avatar = document.createElement('div');
            avatar.className = 'chatbot-message-avatar';
            avatar.textContent = '🤖';
            
            const typing = document.createElement('div');
            typing.className = 'chatbot-typing-content';
            typing.innerHTML = `
                <div class="chatbot-typing-dot"></div>
                <div class="chatbot-typing-dot"></div>
                <div class="chatbot-typing-dot"></div>
            `;
            
            typingDiv.appendChild(avatar);
            typingDiv.appendChild(typing);
            messagesContainer.appendChild(typingDiv);
            
            const messagesElement = this.widget.querySelector('#chatbot-messages');
            messagesElement.scrollTop = messagesElement.scrollHeight;
        }

        hideTyping() {
            const typingIndicator = this.widget.querySelector('#chatbot-typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
            this.isTyping = false;
        }

        showSuggestions(suggestions) {
            const suggestionsContainer = this.widget.querySelector('#chatbot-suggestions');
            suggestionsContainer.innerHTML = '';
            
            suggestions.forEach(suggestion => {
                const suggestionBtn = document.createElement('button');
                suggestionBtn.className = 'chatbot-suggestion';
                suggestionBtn.textContent = suggestion;
                suggestionBtn.onclick = () => {
                    this.widget.querySelector('#chatbot-input').value = suggestion;
                    this.sendMessage();
                    suggestionsContainer.innerHTML = '';
                };
                suggestionsContainer.appendChild(suggestionBtn);
            });
        }
    }

    // Global export
    window.ChatbotWidget = ChatbotWidget;

    // Auto-initialize if data attributes are present
    document.addEventListener('DOMContentLoaded', () => {
        const autoInit = document.querySelector('[data-chatbot-widget]');
        if (autoInit) {
            const config = {
                apiUrl: autoInit.dataset.apiUrl,
                position: autoInit.dataset.position,
                theme: autoInit.dataset.theme,
                language: autoInit.dataset.language,
                title: autoInit.dataset.title,
                welcomeMessage: autoInit.dataset.welcomeMessage,
                placeholder: autoInit.dataset.placeholder
            };
            
            // Remove undefined values
            Object.keys(config).forEach(key => {
                if (config[key] === undefined) {
                    delete config[key];
                }
            });
            
            new ChatbotWidget(config);
        }
    });

})();