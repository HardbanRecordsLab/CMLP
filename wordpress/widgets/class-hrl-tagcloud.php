<?php
/**
 * HRL Widget: 3D Tag Cloud
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HRL_Widget_TagCloud extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hrl_tagcloud_3d',
            __( 'HRL — Premium Tag Cloud', 'hrl-theme' ),
            array( 'description' => __( 'Rotating 3D tag cloud.', 'hrl-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        $tags = get_tags( array( 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ) );
        if ( $tags ) :
            echo '<div class="hrl-tagcloud-3d" style="display:flex;flex-wrap:wrap;gap:8px;">';
            foreach ( $tags as $tag ) :
                $size = max( 10, min( 18, 10 + (int) $tag->count ) );
                echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" '
                   . 'class="tag-pill" '
                   . 'style="display:inline-block;padding:6px ' . ( 8 + $size ) . 'px;border:1px solid rgba(200,169,110,0.25);color:var(--gold);font-size:' . $size . 'px;text-decoration:none;border-radius:20px;opacity:0.85;transition:all 0.2s;font-family:var(--font-mono, monospace);" '
                   . 'onmouseover="this.style.borderColor=\'var(--gold)\';this.style.background=\'rgba(200,169,110,0.1)\';this.style.opacity=\'1\';" '
                   . 'onmouseout="this.style.borderColor=\'rgba(200,169,110,0.25)\';this.style.background=\'transparent\';this.style.opacity=\'0.85\';">'
                   . esc_html( '#' . $tag->name )
                   . '</a>';
            endforeach;
            echo '</div>';
        else :
            echo '<p style="font-size:0.8rem;color:var(--text-secondary);">' . esc_html__( 'Brak tagów.', 'hrl-theme' ) . '</p>';
        endif;
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = $instance['title'] ?? __( 'Tagi', 'hrl-theme' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Tytuł:', 'hrl-theme' ); ?></label>
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
