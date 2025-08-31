# Chatbot-Widget Konfigurationsanleitung

## Übersicht

Diese Anleitung erklärt alle verfügbaren Konfigurationsparameter für das Chatbot-Widget. Das Widget kann sowohl über HTML-Datenattribute als auch über JavaScript-Optionen konfiguriert werden.

## Grundlegende Konfiguration

### HTML-Datenattribute (Empfohlen für einfache Setups)

```html
<div id="chatbot-widget" 
     data-api-url="https://chatbot-api.ak-pro.com"
     data-position="bottom-right"
     data-title="Support Chat"
     data-language="de"
     data-welcome-message="Hallo! Wie kann ich Ihnen helfen?"
     data-placeholder="Nachricht eingeben..."
     data-primary-color="#007bff"
     data-z-index="9999">
</div>
<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

### JavaScript-Konfiguration (Empfohlen für erweiterte Setups)

```javascript
const chatbotWidget = new ChatbotWidget({
    // Basis-Konfiguration
    apiUrl: 'https://chatbot-api.ak-pro.com',
    position: 'bottom-right',
    language: 'de',
    title: 'Support Chat',
    welcomeMessage: 'Hallo! Wie kann ich Ihnen helfen?',
    placeholder: 'Nachricht eingeben...',
    
    // Erscheinungsbild
    primaryColor: '#007bff',
    backgroundColor: '#ffffff',
    textColor: '#333333',
    zIndex: 9999,
    
    // Erweiterte Optionen
    enableTypingIndicator: true,
    enableSoundNotifications: false,
    maxMessages: 100,
    sessionTimeout: 30,
    
    // Event-Handler
    onReady: function() {
        console.log('Chatbot ist bereit');
    },
    onMessage: function(message) {
        console.log('Nachricht gesendet:', message);
    }
});
```

## Vollständige Parameter-Referenz

### 🔧 Basis-Konfiguration

#### `apiUrl` (erforderlich)
- **Typ:** String
- **Standard:** Kein Standard
- **Beschreibung:** URL der Chatbot-API
- **HTML-Attribut:** `data-api-url`
- **Beispiele:**
  ```javascript
  apiUrl: 'https://chatbot-api.ak-pro.com'
  apiUrl: 'https://chatbot-api.ak-pro.com'
  ```

#### `position`
- **Typ:** String
- **Standard:** `'bottom-right'`
- **Optionen:** `'bottom-right'`, `'bottom-left'`, `'top-right'`, `'top-left'`, `'center'`
- **HTML-Attribut:** `data-position`
- **Beschreibung:** Position des Chat-Widgets auf der Seite
- **Beispiele:**
  ```javascript
  position: 'bottom-right'  // Unten rechts (Standard)
  position: 'bottom-left'   // Unten links
  position: 'center'        // Zentriert (Vollbild-Modus)
  ```

#### `language`
- **Typ:** String
- **Standard:** `'de'`
- **Optionen:** `'de'`, `'en'`, `'fr'`, `'es'`, `'it'`
- **HTML-Attribut:** `data-language`
- **Beschreibung:** Sprache der Benutzeroberfläche
- **Beispiele:**
  ```javascript
  language: 'de'  // Deutsch
  language: 'en'  // Englisch
  ```

#### `title`
- **Typ:** String
- **Standard:** `'Chat Support'`
- **HTML-Attribut:** `data-title`
- **Beschreibung:** Titel des Chat-Fensters
- **Beispiele:**
  ```javascript
  title: 'Kundenservice'
  title: 'Support Chat'
  title: 'Fragen? Wir helfen!'
  ```

#### `welcomeMessage`
- **Typ:** String
- **Standard:** `'Hallo! Wie kann ich Ihnen helfen?'`
- **HTML-Attribut:** `data-welcome-message`
- **Beschreibung:** Erste Nachricht, die der Bot anzeigt
- **Beispiele:**
  ```javascript
  welcomeMessage: 'Willkommen! Haben Sie Fragen zu unseren Produkten?'
  welcomeMessage: 'Hallo! Ich bin Ihr virtueller Assistent.'
  ```

#### `placeholder`
- **Typ:** String
- **Standard:** `'Nachricht eingeben...'`
- **HTML-Attribut:** `data-placeholder`
- **Beschreibung:** Platzhaltertext im Eingabefeld
- **Beispiele:**
  ```javascript
  placeholder: 'Ihre Frage hier eingeben...'
  placeholder: 'Wie kann ich helfen?'
  ```

### 🎨 Erscheinungsbild

#### `primaryColor`
- **Typ:** String (Hex-Farbe)
- **Standard:** `'#007bff'`
- **HTML-Attribut:** `data-primary-color`
- **Beschreibung:** Hauptfarbe des Widgets (Header, Buttons)
- **Beispiele:**
  ```javascript
  primaryColor: '#007bff'  // Blau (Standard)
  primaryColor: '#28a745'  // Grün
  primaryColor: '#dc3545'  // Rot
  primaryColor: '#6f42c1'  // Lila
  ```

#### `backgroundColor`
- **Typ:** String (Hex-Farbe)
- **Standard:** `'#ffffff'`
- **HTML-Attribut:** `data-background-color`
- **Beschreibung:** Hintergrundfarbe des Chat-Bereichs
- **Beispiele:**
  ```javascript
  backgroundColor: '#ffffff'  // Weiß (Standard)
  backgroundColor: '#f8f9fa'  // Hellgrau
  backgroundColor: '#2c3e50'  // Dunkelblau (Dark Mode)
  ```

#### `textColor`
- **Typ:** String (Hex-Farbe)
- **Standard:** `'#333333'`
- **HTML-Attribut:** `data-text-color`
- **Beschreibung:** Textfarbe für Nachrichten
- **Beispiele:**
  ```javascript
  textColor: '#333333'  // Dunkelgrau (Standard)
  textColor: '#000000'  // Schwarz
  textColor: '#ffffff'  // Weiß (für dunkle Themes)
  ```

#### `zIndex`
- **Typ:** Number
- **Standard:** `9999`
- **HTML-Attribut:** `data-z-index`
- **Beschreibung:** Z-Index für die Layering-Reihenfolge
- **Beispiele:**
  ```javascript
  zIndex: 9999   // Standard
  zIndex: 999999 // Sehr hoch (über allen anderen Elementen)
  ```

#### `width`
- **Typ:** String
- **Standard:** `'350px'` (Desktop), `'100%'` (Mobile)
- **HTML-Attribut:** `data-width`
- **Beschreibung:** Breite des Chat-Widgets
- **Beispiele:**
  ```javascript
  width: '350px'    // Feste Breite
  width: '400px'    // Breiteres Widget
  width: '100%'     // Vollbreite
  ```

#### `height`
- **Typ:** String
- **Standard:** `'500px'` (Desktop), `'100vh'` (Mobile)
- **HTML-Attribut:** `data-height`
- **Beschreibung:** Höhe des Chat-Widgets
- **Beispiele:**
  ```javascript
  height: '500px'   // Standard-Höhe
  height: '600px'   // Höheres Widget
  height: '80vh'    // 80% der Viewport-Höhe
  ```

### ⚙️ Verhalten und Features

#### `autoOpen`
- **Typ:** Boolean
- **Standard:** `false`
- **HTML-Attribut:** `data-auto-open`
- **Beschreibung:** Widget automatisch beim Laden öffnen
- **Beispiele:**
  ```javascript
  autoOpen: false  // Standard (geschlossen)
  autoOpen: true   // Automatisch öffnen
  ```

#### `enableTypingIndicator`
- **Typ:** Boolean
- **Standard:** `true`
- **HTML-Attribut:** `data-enable-typing-indicator`
- **Beschreibung:** "Bot tippt..."-Indikator anzeigen
- **Beispiele:**
  ```javascript
  enableTypingIndicator: true   // Anzeigen (Standard)
  enableTypingIndicator: false  // Ausblenden
  ```

#### `enableSoundNotifications`
- **Typ:** Boolean
- **Standard:** `false`
- **HTML-Attribut:** `data-enable-sound-notifications`
- **Beschreibung:** Ton-Benachrichtigungen für neue Nachrichten
- **Beispiele:**
  ```javascript
  enableSoundNotifications: false  // Stumm (Standard)
  enableSoundNotifications: true   // Töne aktivieren
  ```

#### `enableEmojis`
- **Typ:** Boolean
- **Standard:** `true`
- **HTML-Attribut:** `data-enable-emojis`
- **Beschreibung:** Emoji-Unterstützung in Nachrichten
- **Beispiele:**
  ```javascript
  enableEmojis: true   // Emojis erlauben (Standard)
  enableEmojis: false  // Keine Emojis
  ```

#### `enableFileUpload`
- **Typ:** Boolean
- **Standard:** `false`
- **HTML-Attribut:** `data-enable-file-upload`
- **Beschreibung:** Datei-Upload-Funktion aktivieren
- **Beispiele:**
  ```javascript
  enableFileUpload: false  // Deaktiviert (Standard)
  enableFileUpload: true   // Upload erlauben
  ```

#### `maxMessages`
- **Typ:** Number
- **Standard:** `100`
- **HTML-Attribut:** `data-max-messages`
- **Beschreibung:** Maximale Anzahl gespeicherter Nachrichten
- **Beispiele:**
  ```javascript
  maxMessages: 100  // Standard
  maxMessages: 50   // Weniger Speicherverbrauch
  maxMessages: 200  // Mehr Historie
  ```

#### `sessionTimeout`
- **Typ:** Number (Minuten)
- **Standard:** `30`
- **HTML-Attribut:** `data-session-timeout`
- **Beschreibung:** Session-Timeout in Minuten
- **Beispiele:**
  ```javascript
  sessionTimeout: 30  // 30 Minuten (Standard)
  sessionTimeout: 60  // 1 Stunde
  sessionTimeout: 0   // Kein Timeout
  ```

### 🔗 Integration und Kontext

#### `context`
- **Typ:** Object
- **Standard:** `{}`
- **Beschreibung:** Zusätzliche Kontextinformationen für den Bot
- **Beispiele:**
  ```javascript
  context: {
    userId: '12345',
    userType: 'premium',
    currentPage: 'product-page',
    productId: 'product-123'
  }
  ```

#### `customHeaders`
- **Typ:** Object
- **Standard:** `{}`
- **Beschreibung:** Benutzerdefinierte HTTP-Headers für API-Anfragen
- **Beispiele:**
  ```javascript
  customHeaders: {
    'Authorization': 'Bearer your-token',
    'X-Custom-Header': 'custom-value'
  }
  ```

#### `enableAnalytics`
- **Typ:** Boolean
- **Standard:** `true`
- **HTML-Attribut:** `data-enable-analytics`
- **Beschreibung:** Analytics-Tracking aktivieren
- **Beispiele:**
  ```javascript
  enableAnalytics: true   // Tracking aktiviert (Standard)
  enableAnalytics: false  // Kein Tracking
  ```

### 📱 Mobile-spezifische Optionen

#### `mobileBreakpoint`
- **Typ:** Number (Pixel)
- **Standard:** `768`
- **HTML-Attribut:** `data-mobile-breakpoint`
- **Beschreibung:** Breakpoint für Mobile-Layout
- **Beispiele:**
  ```javascript
  mobileBreakpoint: 768   // Standard
  mobileBreakpoint: 1024  // Tablet als Mobile behandeln
  ```

#### `mobileFullscreen`
- **Typ:** Boolean
- **Standard:** `true`
- **HTML-Attribut:** `data-mobile-fullscreen`
- **Beschreibung:** Vollbild-Modus auf mobilen Geräten
- **Beispiele:**
  ```javascript
  mobileFullscreen: true   // Vollbild auf Mobile (Standard)
  mobileFullscreen: false  // Normales Widget auch auf Mobile
  ```

### 🎯 Event-Handler

#### `onReady`
- **Typ:** Function
- **Beschreibung:** Wird aufgerufen, wenn das Widget initialisiert ist
- **Parameter:** Keine
- **Beispiel:**
  ```javascript
  onReady: function() {
    console.log('Chatbot ist bereit');
    // Benutzerdefinierte Initialisierung
  }
  ```

#### `onOpen`
- **Typ:** Function
- **Beschreibung:** Wird aufgerufen, wenn das Chat-Fenster geöffnet wird
- **Parameter:** Keine
- **Beispiel:**
  ```javascript
  onOpen: function() {
    console.log('Chat wurde geöffnet');
    // Analytics-Event senden
    gtag('event', 'chat_opened');
  }
  ```

#### `onClose`
- **Typ:** Function
- **Beschreibung:** Wird aufgerufen, wenn das Chat-Fenster geschlossen wird
- **Parameter:** Keine
- **Beispiel:**
  ```javascript
  onClose: function() {
    console.log('Chat wurde geschlossen');
    // Cleanup-Aktionen
  }
  ```

#### `onMessage`
- **Typ:** Function
- **Beschreibung:** Wird aufgerufen, wenn eine Nachricht gesendet wird
- **Parameter:** `message` (Object)
- **Beispiel:**
  ```javascript
  onMessage: function(message) {
    console.log('Nachricht gesendet:', message.text);
    // Custom Analytics
    analytics.track('Chat Message Sent', {
      message_length: message.text.length,
      timestamp: message.timestamp
    });
  }
  ```

#### `onResponse`
- **Typ:** Function
- **Beschreibung:** Wird aufgerufen, wenn eine Bot-Antwort empfangen wird
- **Parameter:** `response` (Object)
- **Beispiel:**
  ```javascript
  onResponse: function(response) {
    console.log('Bot-Antwort:', response.text);
    // Antwort-spezifische Aktionen
    if (response.type === 'product_recommendation') {
      showProductHighlight(response.productId);
    }
  }
  ```

#### `onError`
- **Typ:** Function
- **Beschreibung:** Wird bei Fehlern aufgerufen
- **Parameter:** `error` (Object)
- **Beispiel:**
  ```javascript
  onError: function(error) {
    console.error('Chatbot-Fehler:', error);
    // Fehler-Behandlung
    showErrorNotification('Chat temporär nicht verfügbar');
  }
  ```

## Erweiterte Konfigurationsbeispiele

### E-Commerce Integration

```javascript
const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    position: 'bottom-right',
    language: 'de',
    title: 'Shopping Assistant',
    welcomeMessage: 'Hallo! Ich helfe Ihnen gerne bei der Produktauswahl.',
    
    // E-Commerce-spezifischer Kontext
    context: {
        platform: 'shopify',
        currency: 'EUR',
        customerId: getCurrentCustomerId(),
        cartItems: getCartItems(),
        currentProduct: getCurrentProduct()
    },
    
    // Event-Handler für E-Commerce
    onMessage: function(message) {
        // Warenkorb-Kontext zu jeder Nachricht hinzufügen
        message.ecommerceContext = {
            cartValue: getCartTotal(),
            itemCount: getCartItemCount()
        };
    },
    
    onProductRecommendation: function(products) {
        // Produktempfehlungen in der UI hervorheben
        highlightRecommendedProducts(products);
    }
});
```

### Multi-Language Setup

```javascript
// Sprache basierend auf Browser-Einstellung erkennen
const userLanguage = navigator.language.substring(0, 2);
const supportedLanguages = ['de', 'en', 'fr', 'es'];
const chatLanguage = supportedLanguages.includes(userLanguage) ? userLanguage : 'de';

// Sprachspezifische Nachrichten
const messages = {
    de: {
        title: 'Kundenservice',
        welcome: 'Hallo! Wie kann ich Ihnen helfen?',
        placeholder: 'Nachricht eingeben...'
    },
    en: {
        title: 'Customer Service',
        welcome: 'Hello! How can I help you?',
        placeholder: 'Type your message...'
    }
};

const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    language: chatLanguage,
    title: messages[chatLanguage].title,
    welcomeMessage: messages[chatLanguage].welcome,
    placeholder: messages[chatLanguage].placeholder
});
```

### Dark Mode Integration

```javascript
// Dark Mode erkennen
const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    
    // Dark Mode Farben
    primaryColor: isDarkMode ? '#4dabf7' : '#007bff',
    backgroundColor: isDarkMode ? '#2c3e50' : '#ffffff',
    textColor: isDarkMode ? '#ffffff' : '#333333',
    
    // Dark Mode bei Änderung aktualisieren
    onReady: function() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            this.updateTheme({
                primaryColor: e.matches ? '#4dabf7' : '#007bff',
                backgroundColor: e.matches ? '#2c3e50' : '#ffffff',
                textColor: e.matches ? '#ffffff' : '#333333'
            });
        });
    }
});
```

### Analytics Integration

```javascript
const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    enableAnalytics: true,
    
    // Detailliertes Analytics-Tracking
    onOpen: function() {
        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'chat_opened', {
                'event_category': 'engagement',
                'event_label': 'chatbot'
            });
        }
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'InitiateCheckout');
        }
    },
    
    onMessage: function(message) {
        // Message-Tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', 'chat_message_sent', {
                'event_category': 'engagement',
                'event_label': 'user_message',
                'value': message.text.length
            });
        }
    },
    
    onResponse: function(response) {
        // Bot-Response-Tracking
        if (response.type === 'product_recommendation') {
            gtag('event', 'product_recommendation_shown', {
                'event_category': 'ecommerce',
                'event_label': response.productId
            });
        }
    }
});
```

## Fehlerbehebung bei der Konfiguration

### Häufige Konfigurationsfehler

1. **Ungültige API-URL**
   ```javascript
   // ❌ Falsch
   apiUrl: 'your-chatbot-api.com'  // Protokoll fehlt
   
   // ✅ Richtig
   apiUrl: 'https://chatbot-api.ak-pro.com'
   ```

2. **Ungültige Farbwerte**
   ```javascript
   // ❌ Falsch
   primaryColor: 'blue'  // Kein gültiger Hex-Wert
   
   // ✅ Richtig
   primaryColor: '#007bff'
   ```

3. **Falsche Event-Handler**
   ```javascript
   // ❌ Falsch
   onMessage: 'handleMessage'  // String statt Funktion
   
   // ✅ Richtig
   onMessage: function(message) {
       handleMessage(message);
   }
   ```

### Konfiguration validieren

```javascript
// Konfiguration vor Initialisierung prüfen
function validateConfig(config) {
    const errors = [];
    
    if (!config.apiUrl) {
        errors.push('apiUrl ist erforderlich');
    }
    
    if (config.apiUrl && !config.apiUrl.startsWith('http')) {
        errors.push('apiUrl muss mit http:// oder https:// beginnen');
    }
    
    if (config.primaryColor && !/^#[0-9A-F]{6}$/i.test(config.primaryColor)) {
        errors.push('primaryColor muss ein gültiger Hex-Wert sein');
    }
    
    if (errors.length > 0) {
        console.error('Konfigurationsfehler:', errors);
        return false;
    }
    
    return true;
}

// Verwendung
const config = {
    apiUrl: 'https://chatbot-api.ak-pro.com',
    primaryColor: '#007bff'
};

if (validateConfig(config)) {
    const chatbotWidget = new ChatbotWidget(config);
}
```

## Best Practices

### 1. Performance-Optimierung

```javascript
// Lazy Loading für bessere Performance
function loadChatbot() {
    if (!window.chatbotLoaded) {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js';
        script.onload = function() {
            initializeChatbot();
        };
        document.head.appendChild(script);
        window.chatbotLoaded = true;
    }
}

// Chatbot erst bei Benutzerinteraktion laden
document.addEventListener('scroll', loadChatbot, { once: true });
document.addEventListener('click', loadChatbot, { once: true });
```

### 2. Responsive Design

```javascript
const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    
    // Responsive Konfiguration
    width: window.innerWidth < 768 ? '100%' : '350px',
    height: window.innerWidth < 768 ? '100vh' : '500px',
    mobileFullscreen: true,
    
    // Responsive Updates bei Größenänderung
    onReady: function() {
        window.addEventListener('resize', () => {
            this.updateLayout({
                width: window.innerWidth < 768 ? '100%' : '350px',
                height: window.innerWidth < 768 ? '100vh' : '500px'
            });
        });
    }
});
```

### 3. Sicherheit

```javascript
// Sichere Konfiguration
const chatbotWidget = new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',
    
    // Sichere Headers
    customHeaders: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
    },
    
    // Input-Sanitization
    onMessage: function(message) {
        // XSS-Schutz
        message.text = this.sanitizeInput(message.text);
    },
    
    // Fehlerbehandlung
    onError: function(error) {
        // Keine sensiblen Daten in Fehlermeldungen
        console.error('Chat-Fehler aufgetreten');
        // Detaillierte Fehler nur im Debug-Modus
        if (this.config.debugMode) {
            console.error('Details:', error);
        }
    }
});
```

## Support und Hilfe

### Dokumentation
- **Integrationsleitfaden:** Grundlegende Setup-Anweisungen
- **CSS-Anpassungen:** Styling und Design-Optionen
- **Troubleshooting:** Häufige Probleme und Lösungen

### Community-Ressourcen
- **E-Mail:** support@ak-pro.com
- **GitHub Repository:** Quellcode und Issues
- **Beispiele:** Praktische Implementierungsbeispiele
- **Updates:** Informationen zu neuen Versionen

Diese Konfigurationsanleitung bietet Ihnen alle notwendigen Informationen, um das Chatbot-Widget optimal an Ihre Bedürfnisse anzupassen. Bei Fragen zur Konfiguration konsultieren Sie die weiteren Dokumentationen oder die Community-Ressourcen.