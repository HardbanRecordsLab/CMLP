<?php
/**
 * HRL Amoled Premium — Single Post View
 *
 * Layout dwukolumnowy: artykuł (lewo) + sidebar BlogCast (prawo).
 * Używa template partu content-single.php.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();

$reading_time = function_exists( 'hrl_estimate_reading_time' ) ? hrl_estimate_reading_time( get_the_content() ) : 5;
$listen_time  = function_exists( 'hrl_estimate_listen_time' ) ? hrl_estimate_listen_time( $reading_time ) : 7;
?>

<div class="single-layout-wrapper" style="padding-top:120px;max-width:1300px;margin:0 auto;padding-left:24px;padding-right:24px;padding-bottom:60px;">
    <div class="single-layout-inner" style="display:grid;grid-template-columns:1fr 320px;gap:48px;align-items:start;">

        <main>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'template-parts/content', 'single' ); ?>

                <?php if ( comments_open() || get_comments_number() ) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>

                <?php
                $categories = get_the_category();
                if ( $categories ) :
                    $cat_ids = wp_list_pluck( $categories, 'term_id' );
                    $related = new WP_Query( array(
                        'category__in'   => $cat_ids,
                        'post__not_in'   => array( get_the_ID() ),
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                    ) );
                    if ( $related->have_posts() ) :
                ?>
                <section class="related-posts" style="margin-top:60px;padding-top:32px;border-top:1px solid var(--border-color);">
                    <h2 style="font-family:var(--font-headings);font-size:1.4rem;color:var(--gold);margin-bottom:24px;letter-spacing:1px;text-transform:uppercase;">
                        <?php esc_html_e( 'Powiązane Artykuły', 'hrl-theme' ); ?>
                    </h2>
                    <div class="related-posts-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
                        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                            <article class="related-post-card" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;overflow:hidden;transition:var(--transition-smooth);">
                                <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div style="overflow:hidden;">
                                            <?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:160px;object-fit:cover;display:block;transition:var(--transition-smooth);' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div style="padding:16px;">
                                        <h3 style="font-family:var(--font-headings);font-size:1rem;color:var(--text-primary);line-height:1.4;margin-bottom:8px;">
                                            <?php the_title(); ?>
                                        </h3>
                                        <span style="font-size:0.75rem;color:var(--text-secondary);">
                                            <?php echo get_the_date(); ?>
                                        </span>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
                <?php endif; endif; ?>

                <?php
                $author_id = get_the_author_meta( 'ID' );
                $author_desc = get_the_author_meta( 'description' );
                $author_posts = count_user_posts( $author_id );
                if ( $author_desc || $author_posts > 0 ) :
                ?>
                <footer class="author-bio" style="margin-top:48px;padding:32px;background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;display:flex;gap:24px;align-items:flex-start;">
                    <div class="author-avatar" style="flex-shrink:0;">
                        <?php echo get_avatar( $author_id, 80, '', '', array( 'style' => 'border-radius:50%;border:2px solid var(--gold);' ) ); ?>
                    </div>
                    <div class="author-info">
                        <h3 style="font-family:var(--font-headings);font-size:1.1rem;color:var(--gold);margin-bottom:8px;">
                            <?php the_author(); ?>
                        </h3>
                        <?php if ( $author_desc ) : ?>
                            <p style="font-size:0.9rem;color:var(--text-secondary);line-height:1.7;margin-bottom:12px;">
                                <?php echo esc_html( $author_desc ); ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" style="font-size:0.8rem;color:var(--gold);text-transform:uppercase;letter-spacing:1px;text-decoration:none;">
                            <?php esc_html_e( 'Wszystkie artykuły autora', 'hrl-theme' ); ?> →
                        </a>
                    </div>
                </footer>
                <?php endif; ?>

            <?php endwhile; endif; ?>
        </main>

        <aside class="single-sidebar" style="position:sticky;top:140px;">
            <?php get_sidebar( 'blogcast' ); ?>
        </aside>

    </div>
</div>

<?php get_footer(); ?>
