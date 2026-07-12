<?php
/**
 * HRL Widget: Live Market Watch
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Widget_MarketWatch extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hrl_market_watch',
            __( 'HRL — Live Market Watch', 'hrl-theme' ),
            array( 'description' => __( 'Displays live FOREX, STOCKS, CRYPTO prices.', 'hrl-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        $raw = function_exists( 'hrl_get_live_financial_string' ) ? hrl_get_live_financial_string() : '';
        if ( $raw ) {
            $segments = explode( ' || ', $raw );
            echo '<div class="market-mini-dashboard">';
            foreach ( $segments as $seg ) {
                $seg = trim( $seg );
                if ( empty( $seg ) ) continue;
                $label = ''; $value = $seg;
                if ( 0 === strpos( $seg, '[CRYPTO]' ) ) { $label = '₿'; $value = substr( $seg, 8 ); }
                elseif ( 0 === strpos( $seg, '[FOREX]' ) )  { $label = '💱'; $value = substr( $seg, 7 ); }
                elseif ( 0 === strpos( $seg, '[STOCKS]' ) ) { $label = '📈'; $value = substr( $seg, 8 ); }
                echo '<div style="display:flex;justify-content:space-between;padding:5px 0;font-size:0.75rem;border-bottom:1px solid rgba(255,255,255,0.03);font-family:var(--font-mono, monospace);">'
                   . '<span style="color:var(--gold);">' . esc_html( $label ) . '</span>'
                   . '<span style="color:#E0E0E0;flex:1;margin-left:8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . esc_html( trim( $value ) ) . '</span>'
                   . '</div>';
            }
            echo '</div>';
        } else {
            echo '<p style="font-size:0.8rem;color:var(--text-secondary);">' . esc_html__( 'Ładowanie...', 'hrl-theme' ) . '</p>';
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = $instance['title'] ?? __( 'Live Markets', 'hrl-theme' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Tytuł:', 'hrl-theme' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        return $instance;
    }
}
