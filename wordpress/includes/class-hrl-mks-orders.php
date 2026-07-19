<?php
/**
 * MKS Orders — Custom Post Type & Admin UI
 * Allows unlimited adding of completed Muzyczna Kreacja Słów orders
 * with audio files and client testimonials.
 *
 * @package HRL_Theme
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_MKS_Orders {

    const POST_TYPE = 'hrl_mks_order';

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_type' ) );
        add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
        add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_meta' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ) );
        add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'admin_columns' ) );
        add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'admin_column_data' ), 10, 2 );
    }

    public static function register_post_type() {
        register_post_type( self::POST_TYPE, array(
            'labels' => array(
                'name'               => 'Zamówienia MKS',
                'singular_name'      => 'Zamówienie MKS',
                'add_new'            => 'Dodaj zamówienie',
                'add_new_item'       => 'Dodaj nowe zamówienie MKS',
                'edit_item'          => 'Edytuj zamówienie',
                'new_item'           => 'Nowe zamówienie',
                'view_item'          => 'Zobacz zamówienie',
                'search_items'       => 'Szukaj zamówień',
                'not_found'          => 'Nie znaleziono zamówień',
                'menu_name'          => 'MKS Orders',
            ),
            'public'              => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 25,
            'menu_icon'           => 'dashicons-format-audio',
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'         => false,
            'rewrite'             => false,
        ) );
    }

    public static function add_meta_boxes() {
        add_meta_box(
            'mks_order_details',
            'Szczegóły zamówienia',
            array( __CLASS__, 'render_meta_box' ),
            self::POST_TYPE,
            'normal',
            'high'
        );
        add_meta_box(
            'mks_order_audio',
            'Plik audio',
            array( __CLASS__, 'render_audio_box' ),
            self::POST_TYPE,
            'normal',
            'high'
        );
        add_meta_box(
            'mks_order_client',
            'Opinia klienta',
            array( __CLASS__, 'render_client_box' ),
            self::POST_TYPE,
            'normal',
            'high'
        );
    }

    public static function render_meta_box( $post ) {
        wp_nonce_field( 'mks_order_meta', 'mks_order_nonce' );
        $fields = array(
            'mks_client_name'    => 'Imię i nazwisko / Nazwa klienta',
            'mks_occasion'       => 'Okazja (np. wesele, urodziny, firmowy)',
            'mks_package'        => 'Wybrany pakiet (np. Premium 199 zł)',
            'mks_completion_date' => 'Data realizacji (np. czerwiec 2026)',
            'mks_days'           => 'Czas realizacji (np. 6 dni)',
            'mks_effect'         => 'Efekt / co się wydarzyło',
        );
        echo '<table class="form-table">';
        foreach ( $fields as $key => $label ) {
            $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
            echo '<tr><th><label>' . esc_html( $label ) . '</label></th>';
            echo '<td><input type="text" name="' . esc_attr( $key ) . '" value="' . $val . '" class="large-text" /></td></tr>';
        }
        echo '</table>';
    }

    public static function render_audio_box( $post ) {
        $audio_url = esc_url( get_post_meta( $post->ID, 'mks_audio_url', true ) );
        echo '<p><label><strong>URL pliku MP3:</strong></label><br>';
        echo '<input type="text" name="mks_audio_url" value="' . $audio_url . '" class="large-text" style="width:100%" />';
        echo '<br><small>Wprowadź pełny URL do pliku MP3 (np. https://hardbanrecordslab.online/wp-content/uploads/2026/utwor.mp3)</small></p>';

        if ( $audio_url ) {
            echo '<p><audio controls preload="none" style="width:100%;max-width:500px">';
            echo '<source src="' . $audio_url . '" type="audio/mpeg"></audio></p>';
        }
        echo '<p><button type="button" class="button mks-upload-audio">Wybierz plik z biblioteki mediów</button></p>';
    }

    public static function render_client_box( $post ) {
        $cytat     = esc_textarea( get_post_meta( $post->ID, 'mks_cytat', true ) );
        $cytat_od  = esc_attr( get_post_meta( $post->ID, 'mks_cytat_od', true ) );
        $fragment  = esc_textarea( get_post_meta( $post->ID, 'mks_fragment', true ) );

        echo '<p><label><strong>Cytat klienta:</strong></label><br>';
        echo '<textarea name="mks_cytat" class="large-text" rows="3" style="width:100%">' . $cytat . '</textarea></p>';

        echo '<p><label><strong>Podpis pod cytatem:</strong></label><br>';
        echo '<input type="text" name="mks_cytat_od" value="' . $cytat_od . '" class="large-text" /></p>';

        echo '<p><label><strong>Fragment tekstu utworu:</strong></label><br>';
        echo '<textarea name="mks_fragment" class="large-text" rows="6" style="width:100%">' . $fragment . '</textarea></p>';
    }

    public static function save_meta( $post_id ) {
        if ( ! isset( $_POST['mks_order_nonce'] ) || ! wp_verify_nonce( $_POST['mks_order_nonce'], 'mks_order_meta' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $text_fields = array( 'mks_client_name', 'mks_occasion', 'mks_package', 'mks_completion_date', 'mks_days', 'mks_effect', 'mks_audio_url', 'mks_cytat_od' );
        foreach ( $text_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }

        $textarea_fields = array( 'mks_cytat', 'mks_fragment' );
        foreach ( $textarea_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }

    public static function enqueue_admin( $hook ) {
        global $post;
        if ( ( $hook === 'post-new.php' || $hook === 'post.php' ) && $post && $post->post_type === self::POST_TYPE ) {
            wp_enqueue_media();
            wp_add_inline_script( 'jquery', "
                jQuery(function($){
                    $('.mks-upload-audio').on('click',function(e){
                        e.preventDefault();
                        var frame = wp.media({ title:'Wybierz plik audio', button:{ text:'Wybierz' }, library:{ type:'audio' }, multiple:false });
                        frame.on('select',function(){
                            var attachment = frame.state().get('selection').first().toJSON();
                            $('input[name=mks_audio_url]').val(attachment.url);
                        });
                        frame.open();
                    });
                });
            " );
        }
    }

    public static function admin_columns( $cols ) {
        $new = array();
        foreach ( $cols as $key => $val ) {
            $new[ $key ] = $val;
            if ( $key === 'title' ) {
                $new['mks_client']  = 'Klient';
                $new['mks_package_col'] = 'Pakiet';
                $new['mks_date_col']    = 'Data realizacji';
            }
        }
        return $new;
    }

    public static function admin_column_data( $col, $post_id ) {
        switch ( $col ) {
            case 'mks_client':
                echo esc_html( get_post_meta( $post_id, 'mks_client_name', true ) );
                break;
            case 'mks_package_col':
                echo esc_html( get_post_meta( $post_id, 'mks_package', true ) );
                break;
            case 'mks_date_col':
                echo esc_html( get_post_meta( $post_id, 'mks_completion_date', true ) );
                break;
        }
    }

    /**
     * Get all orders for frontend display
     */
    public static function get_orders() {
        $query = new WP_Query( array(
            'post_type'      => self::POST_TYPE,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ) );

        $orders = array();
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $orders[] = array(
                'id'             => $id,
                'label'          => '✔️ ZREALIZOWANY',
                'title'          => get_the_title(),
                'for'            => get_post_meta( $id, 'mks_client_name', true ),
                'date'           => 'Data: ' . get_post_meta( $id, 'mks_completion_date', true ) . ' · Czas realizacji: ' . get_post_meta( $id, 'mks_days', true ),
                'opis'           => get_the_content(),
                'efekt'          => get_post_meta( $id, 'mks_effect', true ),
                'pakiet'         => get_post_meta( $id, 'mks_package', true ),
                'cytat'          => get_post_meta( $id, 'mks_cytat', true ),
                'cytat_od'       => get_post_meta( $id, 'mks_cytat_od', true ),
                'fragment'       => get_post_meta( $id, 'mks_fragment', true ),
                'audioSrc'       => get_post_meta( $id, 'mks_audio_url', true ),
            );
        }
        wp_reset_postdata();
        return $orders;
    }
}

HRL_MKS_Orders::init();
