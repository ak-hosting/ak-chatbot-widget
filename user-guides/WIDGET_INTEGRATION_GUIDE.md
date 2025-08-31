# Chatbot-Widget Integrationsleitfaden

## Übersicht

Dieser Leitfaden erklärt, wie Sie das universelle Chatbot-Widget in Ihre Website integrieren können. Das Widget bietet eine vollständige Chat-Funktionalität mit anpassbarem Design und umfassender API-Integration.

## 🚀 Schnellstart (2 Minuten)

### Basis-Integration

```html
<!DOCTYPE html>
<html>
<head>
    <title>Meine Website</title>
</head>
<body>
    <!-- Ihr Website-Inhalt -->
    
    <!-- Chatbot-Container -->
    <div id="chatbot-container" 
         data-api-url="https://chatbot-api.ak-pro.com"
         data-title="Support Chat">
    </div>
    
    <!-- Chatbot-Script -->
    <script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
</body>
</html>
```

**Das war's!** Das Widget wird automatisch geladen und ist einsatzbereit.

## 📦 Bezugsquellen

### 1. CDN (Empfohlen)

**jsDelivr CDN (Primär):**
```html
<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

**Vorteile:**
- ✅ Automatische Updates
- ✅ Globale Verfügbarkeit
- ✅ Optimierte Ladezeiten
- ✅ Keine lokale Wartung

### 2. GitHub Raw URL (Fallback)

```html
<script src="https://raw.githubusercontent.com/ak-hosting/ak-chatbot-widget/main/chatbot-widget.js"></script>
```

**Hinweis:** Nur als Fallback verwenden, da GitHub Raw URLs nicht für Produktionsumgebungen optimiert sind.

### 3. Selbst-Hosting

**Download:**
1. Besuchen Sie: https://github.com/ak-hosting/ak-chatbot-widget
2. Laden Sie `chatbot-widget.js` herunter
3. Speichern Sie die Datei in Ihrem Webserver

**Einbindung:**
```html
<script src="/js/chatbot-widget.js"></script>
```

**Vorteile:**
- ✅ Vollständige Kontrolle
- ✅ Keine externen Abhängigkeiten
- ✅ Anpassbare Caching-Strategien

## 🔧 Integration

### HTML Data-Attribute (Einfach)

```html
<div id="chatbot-container"
     data-api-url="https://chatbot-api.ak-pro.com"
     data-title="Kundenservice"
     data-welcome-message="Hallo! Wie kann ich Ihnen helfen?"
     data-placeholder="Ihre Nachricht..."
     data-position="bottom-right"
     data-language="de"
     data-z-index="9999">
</div>
```

### JavaScript-Konfiguration (Erweitert)

```html
<div id="chatbot-container"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbot = new ChatbotWidget({
        containerId: 'chatbot-container',
        apiUrl: 'https://chatbot-api.ak-pro.com',
        title: 'Kundenservice',
        welcomeMessage: 'Hallo! Wie kann ich Ihnen helfen?',
        placeholder: 'Ihre Nachricht...',
        position: 'bottom-right',
        language: 'de',
        theme: {
            primaryColor: '#007bff',
            fontFamily: 'Arial, sans-serif'
        },
        features: {
            suggestions: true,
            typing: true,
            timestamps: true
        }
    });
});
</script>
```

## 🛠️ Plattform-spezifische Integration

### Shopify

**Schritt 1: Theme-Editor öffnen**
1. Shopify Admin → Online Store → Themes
2. "Actions" → "Edit code"

**Schritt 2: Script hinzufügen**
In `theme.liquid` vor `</body>` einfügen:

```liquid
<!-- Chatbot-Widget -->
<div id="chatbot-container"
     data-api-url="https://chatbot-api.ak-pro.com"
     data-title="{{ shop.name }} Support"
     data-customer-id="{{ customer.id | default: 'guest' }}"
     data-shop-domain="{{ shop.domain }}"
     data-language="{{ localization.language.iso_code }}">
</div>

<script>
// Shopify-Kontext für Chatbot
window.shopifyContext = {
    shop: {
        name: "{{ shop.name }}",
        domain: "{{ shop.domain }}",
        currency: "{{ cart.currency.iso_code }}"
    },
    customer: {
        id: {{ customer.id | default: 'null' }},
        email: "{{ customer.email | default: '' }}",
        name: "{{ customer.name | default: '' }}"
    },
    cart: {
        itemCount: {{ cart.item_count }},
        totalPrice: {{ cart.total_price | money_without_currency }}
    },
    page: {
        type: "{{ template.name }}",
        handle: "{{ page.handle | default: product.handle | default: collection.handle }}"
    }
};
</script>

<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

### WordPress

**Methode 1: Theme-Dateien**
In `functions.php` hinzufügen:

```php
function add_chatbot_widget() {
    ?>
    <div id="chatbot-container"
         data-api-url="https://chatbot-api.ak-pro.com"
         data-title="<?php echo get_bloginfo('name'); ?> Support"
         data-user-id="<?php echo get_current_user_id(); ?>"
         data-language="<?php echo get_locale(); ?>">
    </div>
    
    <script>
    window.wpContext = {
        siteUrl: "<?php echo home_url(); ?>",
        siteName: "<?php echo get_bloginfo('name'); ?>",
        userId: <?php echo get_current_user_id(); ?>,
        isLoggedIn: <?php echo is_user_logged_in() ? 'true' : 'false'; ?>
    };
    </script>
    
    <script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
    <?php
}
add_action('wp_footer', 'add_chatbot_widget');
```

**Methode 2: Plugin**
Siehe [WordPress-Plugin Beispiel](examples/wordpress-plugin.php)

### React

```jsx
import React, { useEffect } from 'react';

const ChatbotWidget = ({ apiUrl, title }) => {
    useEffect(() => {
        // Script dynamisch laden
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js';
        script.onload = () => {
            new window.ChatbotWidget({
                containerId: 'chatbot-container',
                apiUrl: apiUrl,
                title: title,
                language: 'de'
            });
        };
        document.head.appendChild(script);
        
        return () => {
            document.head.removeChild(script);
        };
    }, [apiUrl, title]);
    
    return <div id="chatbot-container"></div>;
};

export default ChatbotWidget;
```

### Vue.js

```vue
<template>
    <div id="chatbot-container"></div>
</template>

<script>
export default {
    name: 'ChatbotWidget',
    props: {
        apiUrl: {
            type: String,
            required: true
        },
        title: {
            type: String,
            default: 'Support Chat'
        }
    },
    mounted() {
        this.loadChatbot();
    },
    methods: {
        loadChatbot() {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js';
            script.onload = () => {
                new window.ChatbotWidget({
                    containerId: 'chatbot-container',
                    apiUrl: this.apiUrl,
                    title: this.title,
                    language: 'de'
                });
            };
            document.head.appendChild(script);
        }
    }
};
</script>
```

## ⚙️ Konfigurationsparameter

### Basis-Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|----------|-------------|
| `apiUrl` | String | **Erforderlich** | URL zu Ihrer Chat-API |
| `title` | String | "Chat" | Titel im Chat-Header |
| `welcomeMessage` | String | "Hallo! Wie kann ich helfen?" | Begrüßungsnachricht |
| `placeholder` | String | "Nachricht eingeben..." | Platzhalter im Eingabefeld |
| `language` | String | "de" | Sprache (de, en, fr, es, it) |
| `position` | String | "bottom-right" | Position (bottom-right, bottom-left, top-right, top-left) |

### Erweiterte Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|----------|-------------|
| `zIndex` | Number | 9999 | Z-Index für Überlagerung |
| `width` | String | "350px" | Breite des Widgets |
| `height` | String | "500px" | Höhe des Widgets |
| `minimized` | Boolean | true | Startet minimiert |
| `autoOpen` | Boolean | false | Öffnet automatisch nach Verzögerung |
| `autoOpenDelay` | Number | 5000 | Verzögerung für Auto-Open (ms) |

### Theme-Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|----------|-------------|
| `primaryColor` | String | "#007bff" | Hauptfarbe |
| `fontFamily` | String | "system-ui" | Schriftart |
| `borderRadius` | String | "8px" | Ecken-Rundung |
| `shadow` | String | "0 4px 12px rgba(0,0,0,0.15)" | Schatten |

## 🎨 Design-Anpassung

### CSS-Variablen überschreiben

```css
.chatbot-widget {
    --chatbot-primary-color: #28a745;
    --chatbot-font-family: 'Roboto', sans-serif;
    --chatbot-border-radius: 12px;
    --chatbot-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}
```

### Vollständige Theme-Anpassung

```css
/* Eigene CSS-Datei: custom-chatbot.css */
.chatbot-widget {
    font-family: 'Inter', sans-serif;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
}

.chatbot-message.user {
    background: #007bff;
    color: white;
    border-radius: 18px 18px 4px 18px;
}

.chatbot-message.bot {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    border-radius: 18px 18px 18px 4px;
}
```

**Einbindung:**
```html
<link rel="stylesheet" href="/css/custom-chatbot.css">
<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

## 📱 Mobile Optimierung

### Responsive Verhalten

Das Widget passt sich automatisch an verschiedene Bildschirmgrößen an:

- **Desktop:** Schwebendes Widget in der Ecke
- **Tablet:** Angepasste Größe mit Touch-Optimierung
- **Mobile:** Vollbild-Modus für optimale Nutzung

### Mobile-spezifische Konfiguration

```javascript
const chatbot = new ChatbotWidget({
    // ... andere Konfiguration
    mobile: {
        fullscreen: true,
        swipeToClose: true,
        vibration: true
    }
});
```

## 🔒 Sicherheit und Datenschutz

### CORS-Konfiguration

Ihr API-Server muss CORS-Header setzen:

```javascript
// Express.js Beispiel
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', 'https://ihre-domain.com');
    res.header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    next();
});
```

### Content Security Policy

```html
<meta http-equiv="Content-Security-Policy" 
      content="script-src 'self' https://cdn.jsdelivr.net; 
               connect-src 'self' https://ihre-api.com;">
```

### Datenschutz

- **Session-Daten:** Werden lokal im Browser gespeichert
- **Nachrichten:** Nur an Ihre konfigurierte API gesendet
- **Cookies:** Nur für Session-Management verwendet
- **Tracking:** Kein externes Tracking implementiert

## ✅ Do's und Don'ts

### ✅ Do's (Empfohlen)

- **CDN verwenden** für automatische Updates
- **HTTPS nutzen** für sichere Verbindungen
- **API-Endpunkt testen** vor der Integration
- **Mobile Ansicht prüfen** auf verschiedenen Geräten
- **CSS-Anpassungen** in separaten Dateien
- **Error-Handling** in der API implementieren
- **Rate-Limiting** für API-Schutz einrichten
- **Backup-Strategie** für Session-Daten

### ❌ Don'ts (Vermeiden)

- **Core-Script nicht modifizieren** - Updates würden Änderungen überschreiben
- **Inline-Styles vermeiden** - Schwer wartbar und überschreibbar
- **HTTP in Produktion** - Sicherheitsrisiko und Mixed-Content-Probleme
- **Hardcoded API-Keys** - Sicherheitsrisiko
- **Zu viele gleichzeitige Requests** - API-Überlastung
- **Unvalidierte Eingaben** - Sicherheitslücken
- **Fehlende Fehlerbehandlung** - Schlechte Benutzererfahrung

## 📋 Checkliste nach der Integration

### Funktionalität
- [ ] Widget lädt ohne Fehler
- [ ] Chat-Nachrichten werden gesendet und empfangen
- [ ] API-Verbindung funktioniert
- [ ] Session-Management arbeitet korrekt
- [ ] Suggestion-Buttons funktionieren (falls aktiviert)

### Design
- [ ] Widget fügt sich in das Website-Design ein
- [ ] Farben entsprechen der Corporate Identity
- [ ] Schriftarten sind konsistent
- [ ] Mobile Ansicht ist optimiert
- [ ] Z-Index-Konflikte sind gelöst

### Performance
- [ ] Ladezeiten sind akzeptabel (<2 Sekunden)
- [ ] Keine JavaScript-Fehler in der Konsole
- [ ] Memory-Leaks sind ausgeschlossen
- [ ] API-Response-Zeiten sind optimal

### Sicherheit
- [ ] HTTPS wird verwendet
- [ ] CORS ist korrekt konfiguriert
- [ ] Input-Validierung ist implementiert
- [ ] Keine sensiblen Daten werden geloggt

### Browser-Kompatibilität
- [ ] Chrome (neueste Version)
- [ ] Firefox (neueste Version)
- [ ] Safari (neueste Version)
- [ ] Edge (neueste Version)
- [ ] Mobile Browser (iOS Safari, Chrome Mobile)

## 🚨 Häufige Probleme

### Widget wird nicht angezeigt

**Mögliche Ursachen:**
- Script-URL falsch oder nicht erreichbar
- Container-Element fehlt oder falsche ID
- JavaScript-Fehler blockieren Ausführung
- CSS-Konflikte verstecken das Widget

**Lösungsansätze:**
1. Browser-Konsole auf Fehler prüfen
2. Network-Tab auf fehlgeschlagene Requests prüfen
3. Element-Inspektor verwenden
4. Z-Index erhöhen

### API-Verbindung schlägt fehl

**Mögliche Ursachen:**
- CORS-Header fehlen oder falsch konfiguriert
- API-URL nicht erreichbar
- Falsche HTTP-Methode
- Authentifizierung erforderlich

**Lösungsansätze:**
1. API-Endpunkt manuell testen (curl/Postman)
2. CORS-Konfiguration prüfen
3. Network-Tab für Details analysieren
4. Server-Logs überprüfen

### Performance-Probleme

**Mögliche Ursachen:**
- Zu große API-Responses
- Memory-Leaks in Event-Listenern
- Nicht optimierte Bilder/Assets
- Zu häufige API-Calls

**Lösungsansätze:**
1. Response-Größen minimieren
2. Debouncing für Eingaben implementieren
3. Lazy Loading verwenden
4. Caching-Strategien einsetzen

## 📞 Support und Hilfe

### Selbsthilfe-Ressourcen

1. **[Konfigurationsleitfaden](CONFIGURATION_GUIDE.md)** - Detaillierte Parameter-Referenz
2. **[CSS-Anpassungsleitfaden](CSS_CUSTOMIZATION_GUIDE.md)** - Design-Anpassungen
3. **[Troubleshooting-Checkliste](TROUBLESHOOTING_CHECKLIST.md)** - Systematische Problemlösung
4. **[Beispiele](examples/)** - Funktionierende Code-Beispiele

### Community-Support

- **E-Mail:** support@ak-pro.com
- **GitHub Issues:** Für Bug-Reports und Feature-Requests
- **Dokumentation:** Regelmäßige Updates und Verbesserungen
- **Code-Beispiele:** Vollständige, getestete Implementierungen

## 🔄 Updates und Wartung

### Automatische Updates (CDN)

Bei Verwendung der `@latest` Version erhalten Sie automatisch:
- ✅ Sicherheitsupdates
- ✅ Bug-Fixes
- ✅ Performance-Verbesserungen
- ✅ Neue Features (rückwärtskompatibel)

### Manuelle Updates (Selbst-gehostet)

1. **Backup erstellen** der aktuellen Version
2. **Neue Version herunterladen** von GitHub
3. **Lokale Datei ersetzen**
4. **Funktionalität testen**
5. **Rollback** bei Problemen

### Breaking Changes

Größere Änderungen werden angekündigt:
- **Release Notes** auf GitHub
- **Migration Guides** für Updates
- **Deprecated Features** werden markiert
- **Mindestens 3 Monate Vorlaufzeit**

---

**Letzte Aktualisierung:** 2025-08-31  
**Version:** 1.0  
**Status:** Aktiv

*Diese Dokumentation wird regelmäßig aktualisiert. Für die neueste Version besuchen Sie unsere GitHub-Repository.*