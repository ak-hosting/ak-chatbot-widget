# CSS-Anpassungsanleitung für das Chatbot-Widget

## Übersicht

Diese Anleitung erklärt, wie Sie das Erscheinungsbild des Chatbot-Widgets durch eigene CSS-Regeln anpassen können. **Wichtig:** Das Core-Script darf niemals direkt verändert werden - alle Anpassungen erfolgen ausschließlich über eigene CSS-Dateien.

## ⚠️ Wichtige Regeln

### ✅ Erlaubt
- Eigene CSS-Regeln erstellen und einbinden
- Farben, Schriftarten und Abstände anpassen
- Animationen und Übergänge hinzufügen
- Responsive Anpassungen vornehmen
- CSS-Variablen überschreiben

### ❌ Verboten
- Das Core-Script `chatbot-widget.js` direkt bearbeiten
- Inline-Styles im Widget-HTML ändern
- JavaScript-Funktionen überschreiben
- Strukturelle HTML-Änderungen am Widget
- Sicherheitsrelevante CSS-Eigenschaften manipulieren

## CSS-Selektoren und Struktur

### Haupt-Container

```css
/* Haupt-Widget-Container */
.chatbot-widget {
    /* Ihre Anpassungen hier */
}

/* Widget im geöffneten Zustand */
.chatbot-widget.open {
    /* Anpassungen für geöffnetes Widget */
}

/* Widget im geschlossenen Zustand */
.chatbot-widget.closed {
    /* Anpassungen für geschlossenes Widget */
}

/* Minimierter Button */
.chatbot-toggle-button {
    /* Anpassungen für den Toggle-Button */
}
```

### Header-Bereich

```css
/* Chat-Header */
.chatbot-header {
    /* Header-Styling */
}

/* Titel im Header */
.chatbot-title {
    /* Titel-Styling */
}

/* Schließen-Button */
.chatbot-close-button {
    /* Close-Button-Styling */
}

/* Minimieren-Button */
.chatbot-minimize-button {
    /* Minimize-Button-Styling */
}
```

### Chat-Bereich

```css
/* Nachrichten-Container */
.chatbot-messages {
    /* Container für alle Nachrichten */
}

/* Einzelne Nachricht */
.chatbot-message {
    /* Basis-Styling für Nachrichten */
}

/* Benutzer-Nachricht */
.chatbot-message.user {
    /* Styling für Benutzer-Nachrichten */
}

/* Bot-Nachricht */
.chatbot-message.bot {
    /* Styling für Bot-Nachrichten */
}

/* System-Nachricht */
.chatbot-message.system {
    /* Styling für System-Nachrichten */
}

/* Nachrichtentext */
.chatbot-message-text {
    /* Text-Styling */
}

/* Zeitstempel */
.chatbot-message-timestamp {
    /* Zeitstempel-Styling */
}
```

### Eingabe-Bereich

```css
/* Eingabe-Container */
.chatbot-input-container {
    /* Container für Eingabefeld und Button */
}

/* Eingabefeld */
.chatbot-input {
    /* Eingabefeld-Styling */
}

/* Senden-Button */
.chatbot-send-button {
    /* Send-Button-Styling */
}

/* Typing-Indikator */
.chatbot-typing-indicator {
    /* "Bot tippt..."-Indikator */
}
```

### Suggestion-Buttons

```css
/* Container für Suggestion-Buttons */
.chatbot-suggestions {
    /* Suggestions-Container */
}

/* Einzelner Suggestion-Button */
.chatbot-suggestion-button {
    /* Suggestion-Button-Styling */
}

/* Hover-Zustand für Suggestions */
.chatbot-suggestion-button:hover {
    /* Hover-Effekt */
}
```

## Grundlegende Anpassungen

### Farben ändern

```css
/* Primärfarbe anpassen */
.chatbot-widget {
    --chatbot-primary-color: #28a745; /* Grün statt Blau */
    --chatbot-primary-hover: #218838;
}

/* Header-Farbe */
.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Benutzer-Nachrichten */
.chatbot-message.user {
    background-color: #007bff;
    color: white;
}

/* Bot-Nachrichten */
.chatbot-message.bot {
    background-color: #f8f9fa;
    color: #333;
    border-left: 4px solid #007bff;
}
```

### Schriftarten anpassen

```css
/* Globale Schriftart */
.chatbot-widget {
    font-family: 'Roboto', 'Helvetica Neue', Arial, sans-serif;
}

/* Header-Schriftart */
.chatbot-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 16px;
}

/* Nachrichten-Schriftart */
.chatbot-message-text {
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    line-height: 1.5;
}

/* Eingabefeld-Schriftart */
.chatbot-input {
    font-family: inherit;
    font-size: 14px;
}
```

### Größe und Position

```css
/* Widget-Größe anpassen */
.chatbot-widget {
    width: 400px !important;
    height: 600px !important;
}

/* Position anpassen */
.chatbot-widget {
    bottom: 20px !important;
    right: 20px !important;
}

/* Auf kleineren Bildschirmen */
@media (max-width: 768px) {
    .chatbot-widget {
        width: calc(100vw - 20px) !important;
        height: calc(100vh - 100px) !important;
        bottom: 10px !important;
        right: 10px !important;
        left: 10px !important;
    }
}
```

## Erweiterte Anpassungen

### Dark Mode

```css
/* Dark Mode Variablen */
.chatbot-widget.dark-mode {
    --chatbot-bg-color: #2c3e50;
    --chatbot-text-color: #ecf0f1;
    --chatbot-border-color: #34495e;
    --chatbot-input-bg: #34495e;
    --chatbot-primary-color: #3498db;
}

/* Dark Mode Styling */
.chatbot-widget.dark-mode {
    background-color: var(--chatbot-bg-color);
    color: var(--chatbot-text-color);
    border: 1px solid var(--chatbot-border-color);
}

.chatbot-widget.dark-mode .chatbot-header {
    background-color: #34495e;
    border-bottom: 1px solid #4a5f7a;
}

.chatbot-widget.dark-mode .chatbot-message.bot {
    background-color: #34495e;
    color: #ecf0f1;
}

.chatbot-widget.dark-mode .chatbot-input {
    background-color: var(--chatbot-input-bg);
    color: var(--chatbot-text-color);
    border: 1px solid var(--chatbot-border-color);
}

/* Dark Mode automatisch erkennen */
@media (prefers-color-scheme: dark) {
    .chatbot-widget {
        --chatbot-bg-color: #2c3e50;
        --chatbot-text-color: #ecf0f1;
        --chatbot-border-color: #34495e;
    }
}
```

### Animationen und Übergänge

```css
/* Sanfte Übergänge */
.chatbot-widget {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Widget Ein-/Ausblenden */
.chatbot-widget.opening {
    animation: chatbotSlideIn 0.3s ease-out;
}

.chatbot-widget.closing {
    animation: chatbotSlideOut 0.3s ease-in;
}

@keyframes chatbotSlideIn {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes chatbotSlideOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
}

/* Nachrichten-Animation */
.chatbot-message {
    animation: messageSlideIn 0.2s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button-Hover-Effekte */
.chatbot-send-button {
    transition: all 0.2s ease;
}

.chatbot-send-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

/* Typing-Indikator Animation */
.chatbot-typing-indicator {
    animation: typingPulse 1.5s infinite;
}

@keyframes typingPulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}
```

### Benutzerdefinierte Scrollbar

```css
/* Scrollbar für Nachrichten-Container */
.chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.chatbot-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chatbot-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Firefox Scrollbar */
.chatbot-messages {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}
```

## Responsive Design

### Mobile-optimierte Anpassungen

```css
/* Tablet-Ansicht */
@media (max-width: 1024px) {
    .chatbot-widget {
        width: 350px !important;
        height: 500px !important;
    }
}

/* Mobile-Ansicht */
@media (max-width: 768px) {
    .chatbot-widget {
        width: 100vw !important;
        height: 100vh !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        border-radius: 0 !important;
        position: fixed !important;
    }
    
    .chatbot-header {
        padding: 15px 20px;
    }
    
    .chatbot-title {
        font-size: 18px;
    }
    
    .chatbot-messages {
        padding: 15px;
    }
    
    .chatbot-input-container {
        padding: 15px 20px 20px;
    }
    
    .chatbot-input {
        font-size: 16px; /* Verhindert Zoom auf iOS */
        padding: 12px 15px;
    }
}

/* Sehr kleine Bildschirme */
@media (max-width: 480px) {
    .chatbot-message {
        font-size: 14px;
        padding: 10px 12px;
    }
    
    .chatbot-suggestion-button {
        font-size: 13px;
        padding: 8px 12px;
    }
}
```

### Landscape-Modus auf Mobile

```css
@media (max-width: 768px) and (orientation: landscape) {
    .chatbot-widget {
        height: 100vh !important;
    }
    
    .chatbot-messages {
        max-height: calc(100vh - 120px);
    }
}
```

## Theme-Integration

### Bootstrap-Integration

```css
/* Bootstrap-kompatible Styles */
.chatbot-widget {
    font-family: var(--bs-font-sans-serif, 'Segoe UI', Roboto, sans-serif);
}

.chatbot-widget .btn-primary {
    background-color: var(--bs-primary, #007bff);
    border-color: var(--bs-primary, #007bff);
}

.chatbot-message.user {
    background-color: var(--bs-primary, #007bff);
}

.chatbot-message.bot {
    background-color: var(--bs-light, #f8f9fa);
    border-left-color: var(--bs-primary, #007bff);
}
```

### Material Design

```css
/* Material Design Styling */
.chatbot-widget {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    font-family: 'Roboto', sans-serif;
}

.chatbot-header {
    background: #1976d2;
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chatbot-send-button {
    background: #1976d2;
    border: none;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-send-button:hover {
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.4);
    transform: translateY(-1px);
}

.chatbot-input {
    border: none;
    border-bottom: 2px solid #e0e0e0;
    border-radius: 0;
    padding: 12px 0;
    background: transparent;
    transition: border-color 0.2s ease;
}

.chatbot-input:focus {
    border-bottom-color: #1976d2;
    outline: none;
}
```

## Barrierefreiheit (Accessibility)

### Kontrast und Lesbarkeit

```css
/* Hoher Kontrast für bessere Lesbarkeit */
.chatbot-widget.high-contrast {
    --chatbot-bg-color: #ffffff;
    --chatbot-text-color: #000000;
    --chatbot-primary-color: #0066cc;
    --chatbot-border-color: #333333;
}

.chatbot-widget.high-contrast .chatbot-message.bot {
    background-color: #f0f0f0;
    color: #000000;
    border: 2px solid #333333;
}

.chatbot-widget.high-contrast .chatbot-message.user {
    background-color: #0066cc;
    color: #ffffff;
    border: 2px solid #004499;
}
```

### Focus-Indikatoren

```css
/* Deutliche Focus-Indikatoren */
.chatbot-input:focus,
.chatbot-send-button:focus,
.chatbot-suggestion-button:focus {
    outline: 3px solid #4A90E2;
    outline-offset: 2px;
}

/* Focus für Tastaturnavigation */
.chatbot-widget *:focus-visible {
    outline: 2px solid #4A90E2;
    outline-offset: 2px;
}
```

### Schriftgrößen-Anpassung

```css
/* Große Schriftart */
.chatbot-widget.large-text {
    font-size: 18px;
}

.chatbot-widget.large-text .chatbot-message-text {
    font-size: 16px;
    line-height: 1.6;
}

.chatbot-widget.large-text .chatbot-input {
    font-size: 16px;
    padding: 15px;
}
```

## Debugging und Entwicklung

### Debug-Styles

```css
/* Debug-Modus: Grenzen anzeigen */
.chatbot-widget.debug * {
    outline: 1px solid red;
}

.chatbot-widget.debug .chatbot-message {
    outline-color: blue;
}

.chatbot-widget.debug .chatbot-input-container {
    outline-color: green;
}
```

### CSS-Variablen für einfache Anpassung

```css
/* Alle anpassbaren Variablen */
.chatbot-widget {
    /* Farben */
    --chatbot-primary-color: #007bff;
    --chatbot-primary-hover: #0056b3;
    --chatbot-bg-color: #ffffff;
    --chatbot-text-color: #333333;
    --chatbot-border-color: #dee2e6;
    --chatbot-shadow-color: rgba(0, 0, 0, 0.15);
    
    /* Abstände */
    --chatbot-padding: 15px;
    --chatbot-margin: 10px;
    --chatbot-border-radius: 8px;
    
    /* Schriftarten */
    --chatbot-font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    --chatbot-font-size: 14px;
    --chatbot-line-height: 1.5;
    
    /* Animationen */
    --chatbot-transition-duration: 0.3s;
    --chatbot-transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Z-Index */
    --chatbot-z-index: 9999;
}
```

## Vollständiges Beispiel

### Benutzerdefiniertes Theme

```css
/* Datei: custom-chatbot-theme.css */

/* Basis-Variablen überschreiben */
.chatbot-widget {
    --chatbot-primary-color: #e74c3c;
    --chatbot-primary-hover: #c0392b;
    --chatbot-bg-color: #ffffff;
    --chatbot-text-color: #2c3e50;
    --chatbot-border-radius: 12px;
    --chatbot-font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    
    /* Benutzerdefinierte Schatten */
    box-shadow: 0 10px 30px rgba(231, 76, 60, 0.2);
    border: none;
    font-family: var(--chatbot-font-family);
}

/* Header-Styling */
.chatbot-header {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border-radius: var(--chatbot-border-radius) var(--chatbot-border-radius) 0 0;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

.chatbot-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(45deg);
    transition: all 0.3s ease;
}

.chatbot-header:hover::before {
    right: -30%;
}

.chatbot-title {
    font-weight: 600;
    font-size: 18px;
    margin: 0;
    position: relative;
    z-index: 1;
}

/* Nachrichten-Styling */
.chatbot-message {
    margin: 15px 0;
    animation: messageSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-message.user {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    border-radius: 18px 18px 4px 18px;
    padding: 12px 18px;
    margin-left: 20%;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.chatbot-message.bot {
    background: #f8f9fa;
    color: var(--chatbot-text-color);
    border-radius: 18px 18px 18px 4px;
    padding: 12px 18px;
    margin-right: 20%;
    border-left: 4px solid var(--chatbot-primary-color);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Eingabe-Bereich */
.chatbot-input-container {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 0 0 var(--chatbot-border-radius) var(--chatbot-border-radius);
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.chatbot-input {
    flex: 1;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    padding: 12px 18px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: white;
}

.chatbot-input:focus {
    border-color: var(--chatbot-primary-color);
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    outline: none;
}

.chatbot-send-button {
    background: var(--chatbot-primary-color);
    border: none;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.chatbot-send-button:hover {
    background: var(--chatbot-primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
}

.chatbot-send-button:active {
    transform: translateY(0);
}

/* Suggestion-Buttons */
.chatbot-suggestions {
    padding: 0 20px 15px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.chatbot-suggestion-button {
    background: white;
    border: 2px solid var(--chatbot-primary-color);
    color: var(--chatbot-primary-color);
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chatbot-suggestion-button:hover {
    background: var(--chatbot-primary-color);
    color: white;
    transform: translateY(-1px);
}

/* Toggle-Button */
.chatbot-toggle-button {
    background: var(--chatbot-primary-color);
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    box-shadow: 0 4px 16px rgba(231, 76, 60, 0.3);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.chatbot-toggle-button:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
}

/* Responsive Anpassungen */
@media (max-width: 768px) {
    .chatbot-widget {
        border-radius: 0;
        box-shadow: none;
    }
    
    .chatbot-header {
        border-radius: 0;
        padding: 15px 20px;
    }
    
    .chatbot-message.user,
    .chatbot-message.bot {
        margin-left: 10px;
        margin-right: 10px;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .chatbot-widget {
        --chatbot-bg-color: #2c3e50;
        --chatbot-text-color: #ecf0f1;
        background-color: var(--chatbot-bg-color);
        color: var(--chatbot-text-color);
    }
    
    .chatbot-message.bot {
        background: #34495e;
        color: #ecf0f1;
    }
    
    .chatbot-input-container {
        background: #34495e;
    }
    
    .chatbot-input {
        background: #2c3e50;
        border-color: #4a5f7a;
        color: #ecf0f1;
    }
}
```

## Integration in Ihre Website

### CSS-Datei einbinden

```html
<!-- Nach dem Chatbot-Script einbinden -->
<link rel="stylesheet" href="/css/custom-chatbot-theme.css">
<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

### Inline-CSS (für kleine Anpassungen)

```html
<style>
.chatbot-widget {
    --chatbot-primary-color: #your-brand-color;
}
</style>
```

### CSS-in-JS (für React/Vue)

```javascript
// React Styled Components Beispiel
const CustomChatbotStyles = styled.div`
    .chatbot-widget {
        --chatbot-primary-color: ${props => props.theme.primaryColor};
        font-family: ${props => props.theme.fontFamily};
    }
`;
```

## Support und Hilfe

### Dokumentation
- **Integrationsleitfaden:** Grundlegende Setup-Anweisungen
- **Konfigurationsanleitung:** Parameter und Optionen
- **Troubleshooting:** Häufige Probleme und Lösungen

### Community-Ressourcen
- **E-Mail:** support@ak-pro.com
- **GitHub Repository:** Quellcode und Issues
- **Beispiele:** Praktische Implementierungsbeispiele
- **Updates:** Informationen zu neuen Versionen

Diese CSS-Anpassungsanleitung ermöglicht es Ihnen, das Chatbot-Widget perfekt an Ihr Design anzupassen, ohne das Core-Script zu verändern. Experimentieren Sie mit den verschiedenen Optionen und erstellen Sie ein einzigartiges Chat-Erlebnis für Ihre Benutzer.