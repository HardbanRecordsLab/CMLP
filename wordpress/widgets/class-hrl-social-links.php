<?php
/**
 * HRL Widget: Animated Social Links
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Widget_SocialLinks extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hrl_social_links',
            __( 'HRL — Social Links', 'hrl-theme' ),
            array( 'description' => __( 'Animated social media icon links.', 'hrl-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        $networks = array( 'facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok', 'spotify', 'github', 'bandcamp', 'soundcloud' );
        $icons = array(
            'facebook'  => '&#x1F4F2;',
            'twitter'   => '&#x1F426;',
            'instagram' => '&#x1F4F7;',
            'youtube'   => '&#x1F3AC;',
            'linkedin'  => '&#x1F4BC;',
            'tiktok'    => '🎵',
            'spotify'   => '🎧',
            'github'    => '&#x1F4BB;',
            'bandcamp'  => '🎸',
            'soundcloud'=> '☁️',
        );
        $links = array();
        foreach ( $networks as $net ) {
            $url = get_theme_mod( 'hrl_social_' . $net, '' );
            if ( $url ) {
                $links[] = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="hrl-social-icon" style="'
                    . 'display:inline-flex;align-items:center;justify-content:center;'
                    . 'width:42px;height:42px;border-radius:50%;'
                    . 'border:1px solid var(--border-color);'
                    . 'color:var(--gold);font-size:1.1rem;'
                    . 'text-decoration:none;transition:all 0.3s;'
                    . '" onmouseover="this.style.borderColor=\'var(--gold)\';this.style.background=\'rgba(200,169,110,0.1)\';this.style.transform=\'translateY(-3px)\';" '
                    . 'onmouseout="this.style.borderColor=\'var(--border-color)\';this.style.background=\'transparent\';this.style.transform=\'translateY(0)\';" '
                    . 'aria-label="' . esc_attr( ucfirst( $net ) ) . '">'
                    . $icons[ $net ] ?? '🔗'
                    . '</a>';
            }
        }
        if ( ! empty( $links ) ) {
            echo '<div class="hrl-social-grid" style="display:flex;flex-wrap:wrap;gap:10px;">' . implode( '', $links ) . '</div>';
        } else {
            echo '<p style="font-size:0.8rem;color:var(--text-secondary);">' . esc_html__( 'Skonfiguruj linki społecznościowe w Customizer.', 'hrl-theme' ) . '</p>';
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = $instance['title'] ?? __( 'Social', 'hrl-theme' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Tytuł:', 'hrl-theme' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p style="font-size:0.75rem;color:var(--text-secondary);">
            <?php esc_html_e( 'Linki społecznościowe konfiguruje się w Customizer → HRL — Social Links.', 'hrl-theme' ); ?>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        return $instance;
    }
}
