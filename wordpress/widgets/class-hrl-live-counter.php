<?php
/**
 * HRL Widget: Live Counter (animated numbers)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Widget_LiveCounter extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hrl_live_counter',
            __( 'HRL — Live Counter', 'hrl-theme' ),
            array( 'description' => __( 'Animated number counter (up to 4 stats).', 'hrl-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        $counters = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            $num  = $instance[ 'counter_' . $i . '_num' ] ?? '';
            $lbl  = $instance[ 'counter_' . $i . '_label' ] ?? '';
            if ( $num && $lbl ) {
                $counters[] = array( 'num' => $num, 'label' => $lbl );
            }
        }
        if ( ! empty( $counters ) ) :
            echo '<div class="hrl-counter-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(120px, 1fr));gap:16px;">';
            foreach ( $counters as $c ) :
        ?>
                <div style="text-align:center;padding:16px;background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;">
                    <div class="hrl-counter" data-target="<?php echo esc_attr( $c['num'] ); ?>" style="
                        font-family:var(--font-headings);font-size:2rem;
                        color:var(--gold);font-weight:700;
                    ">0</div>
                    <div style="font-size:0.7rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.06em;margin-top:4px;">
                        <?php echo esc_html( $c['label'] ); ?>
                    </div>
                </div>
        <?php
            endforeach;
            echo '</div>';
        else :
            echo '<p style="font-size:0.8rem;color:var(--text-secondary);">' . esc_html__( 'Skonfiguruj liczniki w ustawieniach widgetu.', 'hrl-theme' ) . '</p>';
        endif;
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = $instance['title'] ?? __( 'Statystyki', 'hrl-theme' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Tytuł:', 'hrl-theme' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'counter_' . $i . '_num' ) ); ?>"><?php printf( esc_html__( 'Licznik %d — Wartość:', 'hrl-theme' ), $i ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'counter_' . $i . '_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'counter_' . $i . '_num' ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'counter_' . $i . '_num' ] ?? '' ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'counter_' . $i . '_label' ) ); ?>"><?php printf( esc_html__( 'Licznik %d — Etykieta:', 'hrl-theme' ), $i ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'counter_' . $i . '_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'counter_' . $i . '_label' ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'counter_' . $i . '_label' ] ?? '' ); ?>">
            </p>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        for ( $i = 1; $i <= 4; $i++ ) {
            $instance[ 'counter_' . $i . '_num' ]   = sanitize_text_field( $new_instance[ 'counter_' . $i . '_num' ] ?? '' );
            $instance[ 'counter_' . $i . '_label' ] = sanitize_text_field( $new_instance[ 'counter_' . $i . '_label' ] ?? '' );
        }
        return $instance;
    }
}
