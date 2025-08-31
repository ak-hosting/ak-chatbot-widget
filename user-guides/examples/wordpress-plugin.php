<?php
/**
 * Plugin Name: Chatbot Widget
 * Plugin URI: https://github.com/your-username/chatbot-widget
 * Description: Intelligenter Kundenservice-Chatbot für WordPress-Websites mit WooCommerce-Integration
 * Version: 1.0.0
 * Author: AK-Hosting
 * Author URI: https://ak-hosting.de
 * License: MIT
 * Text Domain: chatbot-widget
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// Direkten Zugriff verhindern
if (!defined('ABSPATH')) {
    exit;
}

// Plugin-Konstanten definieren
define('CHATBOT_WIDGET_VERSION', '1.0.0');
define('CHATBOT_WIDGET_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CHATBOT_WIDGET_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CHATBOT_WIDGET_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Hauptklasse für das Chatbot Widget Plugin
 */
class Chatbot_Widget {
    
    /**
     * Plugin-Instanz
     */
    private static $instance = null;
    
    /**
     * Plugin-Optionen
     */
    private $options;
    
    /**
     * Singleton-Instanz abrufen
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Konstruktor
     */
    private function __construct() {
        $this->options = get_option('chatbot_widget_options', $this->get_default_options());
        $this->init_hooks();
    }
    
    /**
     * Standard-Optionen
     */
    private function get_default_options() {
        return array(
            'enabled' => true,
            'api_url' => 'https://chatbot-api.ak-pro.com',
            'position' => 'bottom-right',
            'title' => 'Support Chat',
            'welcome_message' => 'Hallo! Wie kann ich Ihnen helfen?',
            'placeholder' => 'Nachricht eingeben...',
            'language' => 'de',
            'primary_color' => '#007bff',
            'background_color' => '#ffffff',
            'text_color' => '#333333',
            'z_index' => 9999,
            'enable_woocommerce' => true,
            'enable_analytics' => true,
            'show_on_pages' => array('all'),
            'hide_on_pages' => array(),
            'custom_css' => '',
            'debug_mode' => false
        );
    }
    
    /**
     * Hooks initialisieren
     */
    private function init_hooks() {
        // Plugin-Aktivierung/Deaktivierung
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // WordPress-Hooks
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_chatbot_widget'));
        
        // Admin-Hooks
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'admin_init'));
            add_filter('plugin_action_links_' . CHATBOT_WIDGET_PLUGIN_BASENAME, array($this, 'add_settings_link'));
        }
        
        // AJAX-Hooks
        add_action('wp_ajax_chatbot_widget_test_connection', array($this, 'test_api_connection'));
        add_action('wp_ajax_chatbot_widget_get_stats', array($this, 'get_chatbot_stats'));
        
        // WooCommerce-Integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_after_single_product_summary', array($this, 'add_product_chat_button'), 25);
            add_action('wp_ajax_chatbot_widget_add_to_cart', array($this, 'ajax_add_to_cart'));
            add_action('wp_ajax_nopriv_chatbot_widget_add_to_cart', array($this, 'ajax_add_to_cart'));
        }
        
        // Shortcode registrieren
        add_shortcode('chatbot_widget', array($this, 'chatbot_shortcode'));
    }
    
    /**
     * Plugin initialisieren
     */
    public function init() {
        // Textdomain laden
        load_plugin_textdomain('chatbot-widget', false, dirname(CHATBOT_WIDGET_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Plugin aktivieren
     */
    public function activate() {
        // Standard-Optionen setzen
        if (!get_option('chatbot_widget_options')) {
            add_option('chatbot_widget_options', $this->get_default_options());
        }
        
        // Statistik-Tabelle erstellen
        $this->create_stats_table();
    }
    
    /**
     * Plugin deaktivieren
     */
    public function deactivate() {
        // Cleanup bei Bedarf
    }
    
    /**
     * Statistik-Tabelle erstellen
     */
    private function create_stats_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'chatbot_widget_stats';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            session_id varchar(255) NOT NULL,
            user_id bigint(20) DEFAULT NULL,
            page_url text NOT NULL,
            message_count int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY user_id (user_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Scripts und Styles einbinden
     */
    public function enqueue_scripts() {
        if (!$this->should_show_chatbot()) {
            return;
        }
        
        // Chatbot-Widget-Script
        wp_enqueue_script(
            'chatbot-widget',
            'https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js',
            array(),
            CHATBOT_WIDGET_VERSION,
            true
        );
        
        // WordPress-spezifische Konfiguration
        wp_localize_script('chatbot-widget', 'chatbotWidgetConfig', array(
            'apiUrl' => 'https://chatbot-api.ak-pro.com',
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('chatbot_widget_nonce'),
            'userId' => get_current_user_id(),
            'isLoggedIn' => is_user_logged_in(),
            'siteUrl' => home_url(),
            'siteName' => get_bloginfo('name'),
            'language' => $this->get_site_language(),
            'woocommerceEnabled' => class_exists('WooCommerce'),
            'debugMode' => $this->options['debug_mode']
        ));
        
        // Custom CSS
        if (!empty($this->options['custom_css'])) {
            wp_add_inline_style('chatbot-widget', $this->options['custom_css']);
        }
    }
    
    /**
     * Chatbot-Widget rendern
     */
    public function render_chatbot_widget() {
        if (!$this->should_show_chatbot()) {
            return;
        }
        
        $context = $this->get_page_context();
        
        echo '<div id="chatbot-widget" ';
        echo 'data-api-url="' . esc_attr($this->options['api_url']) . '" ';
        echo 'data-position="' . esc_attr($this->options['position']) . '" ';
        echo 'data-title="' . esc_attr($this->options['title']) . '" ';
        echo 'data-language="' . esc_attr($this->options['language']) . '" ';
        echo 'data-welcome-message="' . esc_attr($this->options['welcome_message']) . '" ';
        echo 'data-placeholder="' . esc_attr($this->options['placeholder']) . '" ';
        echo 'data-context="wordpress"';
        echo '></div>';
        
        // WordPress-spezifische Initialisierung
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // WordPress-Kontext sammeln
            const wpContext = {
                site: {
                    name: '<?php echo esc_js(get_bloginfo('name')); ?>',
                    url: '<?php echo esc_js(home_url()); ?>',
                    description: '<?php echo esc_js(get_bloginfo('description')); ?>',
                    language: '<?php echo esc_js($this->get_site_language()); ?>',
                    timezone: '<?php echo esc_js(get_option('timezone_string')); ?>'
                },
                user: <?php echo json_encode($this->get_user_context()); ?>,
                page: <?php echo json_encode($context); ?>,
                woocommerce: <?php echo json_encode($this->get_woocommerce_context()); ?>
            };
            
            // Chatbot-Widget initialisieren
            const chatbotWidget = new ChatbotWidget({
                apiUrl: 'https://chatbot-api.ak-pro.com',
                position: '<?php echo esc_js($this->options['position']); ?>',
                language: '<?php echo esc_js($this->options['language']); ?>',
                title: '<?php echo esc_js($this->options['title']); ?>',
                welcomeMessage: getContextualWelcomeMessage(),
                placeholder: '<?php echo esc_js($this->options['placeholder']); ?>',
                
                // WordPress-spezifische Konfiguration
                context: wpContext,
                
                // Styling
                primaryColor: '<?php echo esc_js($this->options['primary_color']); ?>',
                backgroundColor: '<?php echo esc_js($this->options['background_color']); ?>',
                textColor: '<?php echo esc_js($this->options['text_color']); ?>',
                zIndex: <?php echo intval($this->options['z_index']); ?>,
                
                // Features
                enableWooCommerce: <?php echo $this->options['enable_woocommerce'] ? 'true' : 'false'; ?>,
                enableAnalytics: <?php echo $this->options['enable_analytics'] ? 'true' : 'false'; ?>,
                
                // Event-Handler
                onMessage: function(message) {
                    // WordPress-Kontext zu Nachricht hinzufügen
                    message.wpContext = wpContext;
                    
                    // Analytics-Event
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'chatbot_message_sent', {
                            'page_title': document.title,
                            'page_location': window.location.href
                        });
                    }
                    
                    // WordPress-AJAX für Statistiken
                    jQuery.post(chatbotWidgetConfig.ajaxUrl, {
                        action: 'chatbot_widget_track_message',
                        nonce: chatbotWidgetConfig.nonce,
                        message: message.text,
                        page_url: window.location.href
                    });
                },
                
                onProductRecommendation: function(products) {
                    <?php if (class_exists('WooCommerce')): ?>
                    // WooCommerce-Produktlinks generieren
                    products.forEach(function(product) {
                        if (product.woocommerce_id) {
                            product.wpUrl = '<?php echo esc_js(home_url()); ?>/?p=' + product.woocommerce_id;
                        }
                    });
                    <?php endif; ?>
                },
                
                onAddToCart: function(productId, quantity) {
                    <?php if (class_exists('WooCommerce')): ?>
                    // WooCommerce AJAX Add to Cart
                    addToCartViaWooCommerce(productId, quantity);
                    <?php endif; ?>
                }
            });
            
            // Globale Referenz speichern
            window.chatbotWidget = chatbotWidget;
            
            // Kontextabhängige Begrüßungsnachricht
            function getContextualWelcomeMessage() {
                <?php if (is_product() && class_exists('WooCommerce')): ?>
                    return 'Hallo! Haben Sie Fragen zu diesem Produkt?';
                <?php elseif (is_shop() && class_exists('WooCommerce')): ?>
                    return 'Hallo! Kann ich Ihnen bei der Produktauswahl helfen?';
                <?php elseif (is_cart() && class_exists('WooCommerce')): ?>
                    return 'Hallo! Haben Sie Fragen zu Ihrem Warenkorb?';
                <?php elseif (is_user_logged_in()): ?>
                    return 'Hallo <?php echo esc_js(wp_get_current_user()->display_name); ?>! Wie kann ich Ihnen helfen?';
                <?php else: ?>
                    return '<?php echo esc_js($this->options['welcome_message']); ?>';
                <?php endif; ?>
            }
            
            <?php if (class_exists('WooCommerce')): ?>
            // WooCommerce AJAX Add to Cart Funktion
            function addToCartViaWooCommerce(productId, quantity) {
                jQuery.post(chatbotWidgetConfig.ajaxUrl, {
                    action: 'chatbot_widget_add_to_cart',
                    nonce: chatbotWidgetConfig.nonce,
                    product_id: productId,
                    quantity: quantity || 1
                }, function(response) {
                    if (response.success) {
                        // Warenkorb-Update erfolgreich
                        jQuery(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);
                        
                        // Chatbot-Benachrichtigung
                        if (chatbotWidget) {
                            chatbotWidget.showNotification('Produkt wurde zum Warenkorb hinzugefügt!', 'success');
                        }
                    } else {
                        // Fehler beim Hinzufügen
                        if (chatbotWidget) {
                            chatbotWidget.showNotification(response.data || 'Fehler beim Hinzufügen zum Warenkorb', 'error');
                        }
                    }
                }).fail(function() {
                    if (chatbotWidget) {
                        chatbotWidget.showNotification('Netzwerkfehler beim Hinzufügen zum Warenkorb', 'error');
                    }
                });
            }
            <?php endif; ?>
        });
        </script>
        <?php
    }
    
    /**
     * Prüfen, ob Chatbot angezeigt werden soll
     */
    private function should_show_chatbot() {
        if (!$this->options['enabled']) {
            return false;
        }
        
        // Admin-Bereich ausschließen
        if (is_admin()) {
            return false;
        }
        
        // Spezifische Seiten prüfen
        $show_on = $this->options['show_on_pages'];
        $hide_on = $this->options['hide_on_pages'];
        
        // Wenn auf bestimmten Seiten ausgeblendet
        if (!empty($hide_on)) {
            foreach ($hide_on as $page) {
                if ($this->is_current_page($page)) {
                    return false;
                }
            }
        }
        
        // Wenn nur auf bestimmten Seiten angezeigt
        if (!empty($show_on) && !in_array('all', $show_on)) {
            foreach ($show_on as $page) {
                if ($this->is_current_page($page)) {
                    return true;
                }
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Prüfen, ob aktuelle Seite einer Bedingung entspricht
     */
    private function is_current_page($condition) {
        switch ($condition) {
            case 'home':
                return is_front_page();
            case 'shop':
                return function_exists('is_shop') && is_shop();
            case 'product':
                return function_exists('is_product') && is_product();
            case 'cart':
                return function_exists('is_cart') && is_cart();
            case 'checkout':
                return function_exists('is_checkout') && is_checkout();
            case 'account':
                return function_exists('is_account_page') && is_account_page();
            default:
                return false;
        }
    }
    
    /**
     * Seitenkontext abrufen
     */
    private function get_page_context() {
        global $post;
        
        $context = array(
            'type' => 'page',
            'title' => get_the_title(),
            'url' => get_permalink(),
            'id' => get_the_ID()
        );
        
        if (is_front_page()) {
            $context['type'] = 'home';
        } elseif (is_single()) {
            $context['type'] = 'post';
            $context['post_type'] = get_post_type();
        } elseif (function_exists('is_shop') && is_shop()) {
            $context['type'] = 'shop';
        } elseif (function_exists('is_product') && is_product()) {
            $context['type'] = 'product';
            $context['product'] = $this->get_product_context();
        } elseif (function_exists('is_cart') && is_cart()) {
            $context['type'] = 'cart';
        } elseif (function_exists('is_checkout') && is_checkout()) {
            $context['type'] = 'checkout';
        }
        
        return $context;
    }
    
    /**
     * Benutzerkontext abrufen
     */
    private function get_user_context() {
        if (!is_user_logged_in()) {
            return null;
        }
        
        $user = wp_get_current_user();
        
        $context = array(
            'id' => $user->ID,
            'email' => $user->user_email,
            'displayName' => $user->display_name,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'roles' => $user->roles
        );
        
        // WooCommerce-Kundendaten hinzufügen
        if (class_exists('WooCommerce')) {
            $customer = new WC_Customer($user->ID);
            $context['woocommerce'] = array(
                'ordersCount' => $customer->get_order_count(),
                'totalSpent' => $customer->get_total_spent(),
                'lastOrder' => $customer->get_last_order()
            );
        }
        
        return $context;
    }
    
    /**
     * WooCommerce-Kontext abrufen
     */
    private function get_woocommerce_context() {
        if (!class_exists('WooCommerce')) {
            return null;
        }
        
        $context = array(
            'enabled' => true,
            'currency' => get_woocommerce_currency(),
            'currencySymbol' => get_woocommerce_currency_symbol(),
            'cartUrl' => wc_get_cart_url(),
            'checkoutUrl' => wc_get_checkout_url(),
            'shopUrl' => get_permalink(wc_get_page_id('shop'))
        );
        
        // Warenkorb-Informationen
        if (!is_admin() && WC()->cart) {
            $context['cart'] = array(
                'itemCount' => WC()->cart->get_cart_contents_count(),
                'total' => WC()->cart->get_cart_total(),
                'subtotal' => WC()->cart->get_cart_subtotal(),
                'items' => array()
            );
            
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $context['cart']['items'][] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'quantity' => $cart_item['quantity'],
                    'price' => $product->get_price(),
                    'total' => $cart_item['line_total']
                );
            }
        }
        
        return $context;
    }
    
    /**
     * Produktkontext abrufen (für Produktseiten)
     */
    private function get_product_context() {
        if (!function_exists('wc_get_product') || !is_product()) {
            return null;
        }
        
        global $product;
        
        if (!$product) {
            return null;
        }
        
        return array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'regularPrice' => $product->get_regular_price(),
            'salePrice' => $product->get_sale_price(),
            'onSale' => $product->is_on_sale(),
            'inStock' => $product->is_in_stock(),
            'stockQuantity' => $product->get_stock_quantity(),
            'sku' => $product->get_sku(),
            'categories' => wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names')),
            'tags' => wp_get_post_terms($product->get_id(), 'product_tag', array('fields' => 'names')),
            'shortDescription' => $product->get_short_description(),
            'description' => $product->get_description(),
            'images' => $this->get_product_images($product)
        );
    }
    
    /**
     * Produktbilder abrufen
     */
    private function get_product_images($product) {
        $images = array();
        
        // Hauptbild
        $main_image_id = $product->get_image_id();
        if ($main_image_id) {
            $images[] = wp_get_attachment_image_url($main_image_id, 'medium');
        }
        
        // Galerie-Bilder
        $gallery_ids = $product->get_gallery_image_ids();
        foreach ($gallery_ids as $image_id) {
            $images[] = wp_get_attachment_image_url($image_id, 'medium');
        }
        
        return array_slice($images, 0, 3); // Maximal 3 Bilder
    }
    
    /**
     * Site-Sprache abrufen
     */
    private function get_site_language() {
        $locale = get_locale();
        return substr($locale, 0, 2); // z.B. 'de' aus 'de_DE'
    }
    
    /**
     * AJAX: Produkt zum Warenkorb hinzufügen
     */
    public function ajax_add_to_cart() {
        check_ajax_referer('chatbot_widget_nonce', 'nonce');
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error('WooCommerce ist nicht aktiviert');
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']) ?: 1;
        
        if (!$product_id) {
            wp_send_json_error('Ungültige Produkt-ID');
        }
        
        $result = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Produkt wurde zum Warenkorb hinzugefügt',
                'cart_hash' => WC()->cart->get_cart_hash(),
                'fragments' => WC_AJAX::get_refreshed_fragments()
            ));
        } else {
            wp_send_json_error('Fehler beim Hinzufügen zum Warenkorb');
        }
    }
    
    /**
     * Shortcode für manuellen Chatbot-Aufruf
     */
    public function chatbot_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => $this->options['title'],
            'position' => 'inline',
            'height' => '400px'
        ), $atts);
        
        return '<div class="chatbot-widget-shortcode" data-title="' . esc_attr($atts['title']) . '" data-position="' . esc_attr($atts['position']) . '" style="height: ' . esc_attr($atts['height']) . ';"></div>';
    }
    
    /**
     * Admin-Menü hinzufügen
     */
    public function add_admin_menu() {
        add_options_page(
            'Chatbot Widget Einstellungen',
            'Chatbot Widget',
            'manage_options',
            'chatbot-widget',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin-Einstellungen initialisieren
     */
    public function admin_init() {
        register_setting('chatbot_widget_options', 'chatbot_widget_options', array($this, 'validate_options'));
        
        // Einstellungssektionen
        add_settings_section('chatbot_widget_general', 'Allgemeine Einstellungen', null, 'chatbot-widget');
        add_settings_section('chatbot_widget_appearance', 'Erscheinungsbild', null, 'chatbot-widget');
        add_settings_section('chatbot_widget_advanced', 'Erweiterte Einstellungen', null, 'chatbot-widget');
        
        // Einstellungsfelder
        $this->add_settings_fields();
    }
    
    /**
     * Einstellungsfelder hinzufügen
     */
    private function add_settings_fields() {
        // Allgemeine Einstellungen
        add_settings_field('enabled', 'Chatbot aktivieren', array($this, 'field_checkbox'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'enabled'));
        add_settings_field('api_url', 'API-URL', array($this, 'field_text'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'api_url'));
        add_settings_field('title', 'Chat-Titel', array($this, 'field_text'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'title'));
        add_settings_field('welcome_message', 'Begrüßungsnachricht', array($this, 'field_textarea'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'welcome_message'));
        add_settings_field('placeholder', 'Eingabe-Platzhalter', array($this, 'field_text'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'placeholder'));
        add_settings_field('language', 'Sprache', array($this, 'field_select'), 'chatbot-widget', 'chatbot_widget_general', array('field' => 'language', 'options' => array('de' => 'Deutsch', 'en' => 'English')));
        
        // Erscheinungsbild
        add_settings_field('position', 'Position', array($this, 'field_select'), 'chatbot-widget', 'chatbot_widget_appearance', array('field' => 'position', 'options' => array('bottom-right' => 'Unten rechts', 'bottom-left' => 'Unten links', 'top-right' => 'Oben rechts', 'top-left' => 'Oben links')));
        add_settings_field('primary_color', 'Primärfarbe', array($this, 'field_color'), 'chatbot-widget', 'chatbot_widget_appearance', array('field' => 'primary_color'));
        add_settings_field('background_color', 'Hintergrundfarbe', array($this, 'field_color'), 'chatbot-widget', 'chatbot_widget_appearance', array('field' => 'background_color'));
        add_settings_field('text_color', 'Textfarbe', array($this, 'field_color'), 'chatbot-widget', 'chatbot_widget_appearance', array('field' => 'text_color'));
        add_settings_field('custom_css', 'Benutzerdefiniertes CSS', array($this, 'field_textarea'), 'chatbot-widget', 'chatbot_widget_appearance', array('field' => 'custom_css'));
        
        // Erweiterte Einstellungen
        add_settings_field('enable_woocommerce', 'WooCommerce-Integration', array($this, 'field_checkbox'), 'chatbot-widget', 'chatbot_widget_advanced', array('field' => 'enable_woocommerce'));
        add_settings_field('enable_analytics', 'Analytics aktivieren', array($this, 'field_checkbox'), 'chatbot-widget', 'chatbot_widget_advanced', array('field' => 'enable_analytics'));
        add_settings_field('debug_mode', 'Debug-Modus', array($this, 'field_checkbox'), 'chatbot-widget', 'chatbot_widget_advanced', array('field' => 'debug_mode'));
    }
    
    /**
     * Admin-Seite rendern
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Chatbot Widget Einstellungen</h1>
            
            <div class="notice notice-info">
                <p><strong>Shortcode:</strong> Verwenden Sie <code>[chatbot_widget]</code> um den Chatbot manuell in Beiträge oder Seiten einzubinden.</p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('chatbot_widget_options');
                do_settings_sections('chatbot-widget');
                submit_button();
                ?>
            </form>
            
            <div class="postbox">
                <h3 class="hndle">API-Verbindung testen</h3>
                <div class="inside">
                    <p>Testen Sie die Verbindung zu Ihrer Chatbot-API:</p>
                    <button type="button" class="button" id="test-api-connection">Verbindung testen</button>
                    <div id="api-test-result"></div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#test-api-connection').click(function() {
                var button = $(this);
                var result = $('#api-test-result');
                
                button.prop('disabled', true).text('Teste...');
                result.html('');
                
                $.post(ajaxurl, {
                    action: 'chatbot_widget_test_connection',
                    nonce: '<?php echo wp_create_nonce('chatbot_widget_test'); ?>'
                }, function(response) {
                    if (response.success) {
                        result.html('<div class="notice notice-success inline"><p>Verbindung erfolgreich!</p></div>');
                    } else {
                        result.html('<div class="notice notice-error inline"><p>Verbindungsfehler: ' + response.data + '</p></div>');
                    }
                }).always(function() {
                    button.prop('disabled', false).text('Verbindung testen');
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Einstellungsfeld: Checkbox
     */
    public function field_checkbox($args) {
        $field = $args['field'];
        $value = isset($this->options[$field]) ? $this->options[$field] : false;
        echo '<input type="checkbox" name="chatbot_widget_options[' . $field . ']" value="1" ' . checked(1, $value, false) . ' />';
    }
    
    /**
     * Einstellungsfeld: Text
     */
    public function field_text($args) {
        $field = $args['field'];
        $value = isset($this->options[$field]) ? $this->options[$field] : '';
        echo '<input type="text" name="chatbot_widget_options[' . $field . ']" value="' . esc_attr($value) . '" class="regular-text" />';
    }
    
    /**
     * Einstellungsfeld: Textarea
     */
    public function field_textarea($args) {
        $field = $args['field'];
        $value = isset($this->options[$field]) ? $this->options[$field] : '';
        echo '<textarea name="chatbot_widget_options[' . $field . ']" rows="4" cols="50">' . esc_textarea($value) . '</textarea>';
    }
    
    /**
     * Einstellungsfeld: Select
     */
    public function field_select($args) {
        $field = $args['field'];
        $options = $args['options'];
        $value = isset($this->options[$field]) ? $this->options[$field] : '';
        
        echo '<select name="chatbot_widget_options[' . $field . ']">';
        foreach ($options as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($key, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    /**
     * Einstellungsfeld: Color
     */
    public function field_color($args) {
        $field = $args['field'];
        $value = isset($this->options[$field]) ? $this->options[$field] : '#007bff';
        echo '<input type="color" name="chatbot_widget_options[' . $field . ']" value="' . esc_attr($value) . '" />';
    }
    
    /**
     * Optionen validieren
     */
    public function validate_options($input) {
        $output = array();
        
        // Checkbox-Felder
        $checkboxes = array('enabled', 'enable_woocommerce', 'enable_analytics', 'debug_mode');
        foreach ($checkboxes as $checkbox) {
            $output[$checkbox] = isset($input[$checkbox]) ? true : false;
        }
        
        // Text-Felder
        $text_fields = array('api_url', 'title', 'welcome_message', 'placeholder', 'language', 'position');
        foreach ($text_fields as $field) {
            $output[$field] = sanitize_text_field($input[$field]);
        }
        
        // Farb-Felder
        $color_fields = array('primary_color', 'background_color', 'text_color');
        foreach ($color_fields as $field) {
            $output[$field] = sanitize_hex_color($input[$field]);
        }
        
        // CSS
        $output['custom_css'] = wp_strip_all_tags($input['custom_css']);
        
        // Z-Index
        $output['z_index'] = intval($input['z_index']) ?: 9999;
        
        return $output;
    }
    
    /**
     * Einstellungslink zu Plugin-Aktionen hinzufügen
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=chatbot-widget">Einstellungen</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    /**
     * AJAX: API-Verbindung testen
     */
    public function test_api_connection() {
        check_ajax_referer('chatbot_widget_test', 'nonce');
        
        $api_url = $this->options['api_url'];
        
        if (empty($api_url)) {
            wp_send_json_error('API-URL ist nicht konfiguriert');
        }
        
        $response = wp_remote_get($api_url . '/api/chat/test', array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'WordPress/Chatbot-Widget-Plugin'
            )
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        
        if ($status_code === 200) {
            wp_send_json_success('API ist erreichbar');
        } else {
            wp_send_json_error('API antwortet mit Status: ' . $status_code);
        }
    }
    
    /**
     * Produkt-Chat-Button auf Produktseiten hinzufügen
     */
    public function add_product_chat_button() {
        if (!$this->options['enable_woocommerce'] || !is_product()) {
            return;
        }
        
        global $product;
        
        echo '<div class="chatbot-widget-product-button">';
        echo '<button type="button" class="button alt" onclick="window.chatbotWidget.sendMessage(\'Ich habe eine Frage zu ' . esc_js($product->get_name()) . '\'); window.chatbotWidget.open();">Fragen zu diesem Produkt?</button>';
        echo '</div>';
    }
}

// Plugin initialisieren
Chatbot_Widget::get_instance();

// Globale Funktionen für Theme-Integration
function chatbot_widget_show_widget() {
    $instance = Chatbot_Widget::get_instance();
    $instance->render_chatbot_widget();
}

function chatbot_widget_is_enabled() {
    $options = get_option('chatbot_widget_options', array());
    return isset($options['enabled']) && $options['enabled'];
}

?>