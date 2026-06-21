<?php
/**
 * HRL BlogCast — Hierarchical Category Provisioner
 * HardbanRecords Lab 2.0
 *
 * Automatically provisions 7 primary category pillars and 113 specialized
 * subcategories within the WordPress 'category' taxonomy.
 *
 * Design Principles:
 *   - Runs ONLY on theme activation (after_switch_theme) OR on explicit
 *     administrator request via a dedicated AJAX/Action endpoint.
 *   - Deduplication: category_exists() + get_term_by() ensure no duplicates.
 *   - Transient caching: a 24h transient stores the full term tree to speed
 *     up dropdown rendering and AJAX category lookups.
 *   - Modular: this file is loaded via require_once from functions.php and
 *     is never auto-executed on page load.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Category_Provisioner {

    const TREE_TRANSIENT = 'hrl_category_tree_v1';
    const TREE_TTL       = DAY_IN_SECONDS;

    /**
     * Complete category taxonomy map: 7 parents → 113 children.
     *
     * @return array
     */
    public static function get_structure() {
        return array(
            'Biznes i SaaS' => array(
                'Startupy technologiczne',
                'Modele subskrypcyjne SaaS',
                'Finansowanie projektów kreatywnych',
                'Zarządzanie zespołem rozproszonym',
                'Analityka biznesowa B2B',
                'Skalowanie infrastruktury komercyjnej',
                'Metryki SaaS MRR i Churn',
                'B2B Growth Hacking',
                'Strategie cenowe i pakietyzacja',
                'Inbound Marketing dla SaaS',
                'Zarządzanie relacjami z klientem CRM',
                'Lejek sprzedażowy B2B',
                'Onboarding klientów biznesowych',
                'Optymalizacja podatkowa w IT',
                'Prawo autorskie w umowach B2B',
                'Zarządzanie ryzykiem finansowym',
                'Analiza konkurencji rynkowej',
                'Wyjście z inwestycji i M&A',
                'Ochrona własności intelektualnej w startupach',
                'Venture Capital i pozyskiwanie inwestorów',
                'Marketing automation i lead nurturing',
                'Budowanie kultury organizacyjnej w firmie technologicznej',
            ),
            'AI i Automatyzacja' => array(
                'Modele LLM na produkcji',
                'Optymalizacja Prompt Engineering',
                'Integracje n8n i Make',
                'Sztuczna inteligencja w produkcji audio',
                'Automatyzacja marketingu treści',
                'Analiza predykcyjna rynków',
                'Generatywne modele tekstowe',
                'RAG Retrieval Augmented Generation',
                'Autonomiczne agenty AI',
                'Fine-tuning modeli językowych',
                'Przetwarzanie języka naturalnego NLP',
                'Wizja komputerowa i OCR',
                'Automatyzacja procesów RPA',
                'Web scraping i data mining',
                'Integracja API n8n na serwerze',
                'Zarządzanie kolejkami w automatyzacji',
                'Obsługa błędów i retry-logic',
                'Monitorowanie scenariuszy n8n',
                'Uczenie maszynowe w analizie danych',
                'AI Governance i etyka algorytmów',
                'Bezpieczeństwo modeli generatywnych',
                'Zastosowania AI w e-learningu',
                'Computer Vision dla przemysłu audio',
                'AI w kompozycji muzycznej',
                'Edge AI i inferencja na urządzeniach',
                'Synteza mowy i przetwarzanie głosu',
            ),
            'Muzyka Komercyjna' => array(
                'Dystrybucja metadanych DDEX',
                'Umowy synchronizacyjne',
                'Cyfrowe stacje nadawcze audio',
                'Licencjonowanie bezpośrednie',
                'Branding dźwiękowy marek',
                'Niezależna dystrybucja cyfrowa',
                'Zwolnienia z opłat OZZ',
                'Audyt muzyczny w lokalach',
                'Dobór playlist pod branże',
                'Weryfikacja certyfikatów QR',
                'Zarządzanie prawami autorskimi',
                'Analiza akustyczna przestrzeni',
                'Sprzęt audio i instalacje AV',
                'Dystrybucja sygnału radiowego',
                'Metadane muzyczne w bazach SQL',
                'Produkcja identyfikatorów dźwiękowych audio logos',
                'Organizacja sesji nagraniowych na zamówienie',
                'Publikowanie nut i partytur cyfrowych',
                'Muzyka generatywna na potrzeby gier i VR i AR',
                'Konsulting akustyczny dla lokali komercyjnych',
            ),
            'Development i Architektura' => array(
                'NextJS i React Edge Deployment',
                'Optymalizacja baz danych PostgreSQL',
                'Zarządzanie kontenerami Docker',
                'Serwery VPS i bezpieczeństwo Linux',
                'Integracje API Headless WordPress',
                'Architektura mikroserwisów',
                'Nginx Reverse Proxy i SSL',
                'Prywatny streaming plików audio',
                'Konfiguracja X-Accel-Redirect',
                'Generowanie i walidacja tokenów JWT',
                'Uwierzytelnianie bezstanowe',
                'Mechanizmy CORS i bezpieczeństwo',
                'Strategie buforowania i Redis',
                'CI/CD i automatyczne wdrożenia',
                'Kompilacja i bundling esbuild',
                'Optymalizacja Event Loop w Node',
                'Asynchroniczne operacje bazy danych',
                'Zabezpieczenia OWASP i nagłówki',
                'Zarządzanie wolumenami Docker',
                'Monitorowanie infrastruktury serwerowej',
                'Serverless i funkcje lambda',
                'WebSocket i komunikacja w czasie rzeczywistym',
                'Testy penetracyjne i audyty bezpieczeństwa',
                'Mikrofrontendy i architektura modułowa',
                'Zarządzanie sekretami i Vault',
                'Observability i tracing rozproszony',
            ),
            'Design i Branding' => array(
                'Interfejsy Amoled Premium',
                'Projektowanie tożsamości audio',
                'Psychologia kolorów w e-commerce',
                'Projektowanie identyfikacji wizualnej marek audio',
                'Typografia w brandingu muzycznym',
                'UX writing i mikrointerakcje w aplikacjach muzycznych',
            ),
            'Cyfrowe Prawo' => array(
                'Dyrektywa DSM i prawa autorskie',
                'Umowy B2B w sektorze IT',
                'RODO w architekturze SaaS',
                'Prawo własności intelektualnej w AI',
                'Licencje Creative Commons i open source',
                'Ochrona danych w sektorze kreatywnym',
            ),
            'Produkcja Audio' => array(
                'Mastering dla platform streamingowych',
                'Projektowanie fali dźwiękowej',
                'Inżynieria brzmienia syntezatorów',
                'Nagrywanie wokalowe i produkcja lektorska',
                'Edycja i czyszczenie dialogów',
                'Miksowanie w Dolby Atmos',
                'Restauracja nagrań archiwalnych',
            ),
        );
    }

    /**
     * Execute the full provisioning of categories.
     *
     * Idempotent — only inserts missing terms.
     *
     * @return array{parents_created:int, children_created:int, total:int}
     */
    public static function provision() {
        $structure        = self::get_structure();
        $created_parents  = 0;
        $created_children = 0;

        foreach ( $structure as $parent_name => $child_names ) {
            $parent_slug = sanitize_title( $parent_name );
            $parent_id   = self::term_id_by_slug( $parent_slug, 'category' );

            if ( ! $parent_id ) {
                $inserted = wp_insert_term( $parent_name, 'category', array(
                    'slug' => $parent_slug,
                ));
                if ( ! is_wp_error( $inserted ) ) {
                    $parent_id = $inserted['term_id'];
                    $created_parents++;
                }
            }

            if ( ! $parent_id ) {
                continue;
            }

            foreach ( $child_names as $child_name ) {
                $existing = term_exists( $child_name, 'category' );
                if ( 0 === $existing || null === $existing ) {
                    $inserted = wp_insert_term( $child_name, 'category', array(
                        'slug'   => sanitize_title( $child_name ),
                        'parent' => $parent_id,
                    ));
                    if ( ! is_wp_error( $inserted ) ) {
                        $created_children++;
                    }
                }
            }
        }

        delete_transient( self::TREE_TRANSIENT );
        wp_cache_flush();

        return array(
            'parents_created'  => $created_parents,
            'children_created' => $created_children,
            'total'            => $created_parents + $created_children,
        );
    }

    /**
     * Return the full hierarchical term tree, cached for 24h.
     *
     * @return array[]
     */
    public static function get_tree() {
        $cached = get_transient( self::TREE_TRANSIENT );
        if ( is_array( $cached ) ) {
            return $cached;
        }

        $tree = self::build_tree();
        set_transient( self::TREE_TRANSIENT, $tree, self::TREE_TTL );
        return $tree;
    }

    private static function build_tree() {
        $parents = get_categories( array(
            'taxonomy'   => 'category',
            'parent'     => 0,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));

        $tree = array();
        foreach ( $parents as $parent ) {
            if ( 'uncategorized' === $parent->slug ) {
                continue;
            }

            $children = get_categories( array(
                'taxonomy'   => 'category',
                'child_of'   => $parent->term_id,
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));

            $child_nodes = array();
            foreach ( $children as $child ) {
                $child_nodes[] = array(
                    'term_id' => $child->term_id,
                    'name'    => $child->name,
                    'slug'    => $child->slug,
                    'link'    => get_category_link( $child->term_id ),
                );
            }

            $tree[] = array(
                'term_id'  => $parent->term_id,
                'name'     => $parent->name,
                'slug'     => $parent->slug,
                'link'     => get_category_link( $parent->term_id ),
                'children' => $child_nodes,
            );
        }

        return $tree;
    }

    private static function term_id_by_slug( $slug, $taxonomy = 'category' ) {
        $term = get_term_by( 'slug', $slug, $taxonomy );
        return ( $term && ! is_wp_error( $term ) ) ? (int) $term->term_id : 0;
    }

    /**
     * Register admin AJAX endpoint for manual re-provisioning.
     */
    public static function register_admin_ajax() {
        add_action( 'wp_ajax_hrl_provision_categories', array( __CLASS__, 'ajax_provision' ) );
    }

    public static function ajax_provision() {
        if ( ! current_user_can( 'manage_categories' ) ) {
            wp_send_json_error( array( 'message' => __( 'Brak uprawnień.', 'hrl-theme' ) ), 403 );
        }
        check_ajax_referer( 'hrl_provision_nonce', 'nonce' );

        $result = self::provision();

        wp_send_json_success( array(
            'message'          => sprintf(
                __( 'Utworzono %1$d kategorii głównych i %2$d podkategorii.', 'hrl-theme' ),
                $result['parents_created'],
                $result['children_created']
            ),
            'parents_created'  => $result['parents_created'],
            'children_created' => $result['children_created'],
            'total'            => $result['total'],
        ));
    }

    /**
     * Invalidate the tree transient.
     */
    public static function invalidate_cache() {
        delete_transient( self::TREE_TRANSIENT );
    }
}