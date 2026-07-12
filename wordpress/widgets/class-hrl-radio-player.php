<?php
/**
 * HRL Widget: Compact Radio Player
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Widget_RadioPlayer extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hrl_radio_player',
            __( 'HRL — Radio Player', 'hrl-theme' ),
            array( 'description' => __( 'Compact AzuraCast radio player embed.', 'hrl-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        $stream = $instance['stream_url'] ?? 'https://radio.hardbanrecordslab.online/radio/8000/radio.mp3';
        ?>
        <div class="hrl-widget-radio" style="display:flex;align-items:center;gap:12px;">
            <button id="widgetRadioPlay_<?php echo esc_attr( $this->id ); ?>" class="hrl-widget-play-btn" data-stream="<?php echo esc_url( $stream ); ?>" style="
                width:42px;height:42px;border-radius:50%;
                background:var(--gradient-accent);border:none;
                color:#fff;font-size:1rem;cursor:pointer;
                display:flex;align-items:center;justify-content:center;
                transition:transform 0.2s;flex-shrink:0;
            " aria-label="<?php esc_attr_e( 'Play Radio', 'hrl-theme' ); ?>">▶</button>
            <div>
                <div style="font-size:0.85rem;color:var(--text-primary);font-weight:600;"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></div>
                <div style="font-size:0.7rem;color:var(--text-secondary);">128kbps · AzuraCast</div>
            </div>
        </div>
        <?php echo $args['after_widget'];
    }

    public function form( $instance ) {
        $stream = $instance['stream_url'] ?? 'https://radio.hardbanrecordslab.online/radio/8000/radio.mp3';
        $title = $instance['title'] ?? __( 'Radio HRL', 'hrl-theme' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Tytuł:', 'hrl-theme' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'stream_url' ) ); ?>"><?php esc_html_e( 'Stream URL:', 'hrl-theme' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'stream_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stream_url' ) ); ?>" type="url" value="<?php echo esc_attr( $stream ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['stream_url'] = esc_url_raw( $new_instance['stream_url'] );
        return $instance;
    }
}
