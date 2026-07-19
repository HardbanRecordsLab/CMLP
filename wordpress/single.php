<?php
/**
 * Single Post — Immersive magazine layout
 * Reading progress bar, hero image, typography.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="reading-progress" id="readingProgress"></div>

<div style="padding-top:100px;">
    <div class="container">

        <article class="single-post-article">
            <?php if (has_post_thumbnail()) : ?>
            <div class="single-post-hero reveal-up">
                <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                <div class="single-post-hero-overlay"></div>
                <div class="single-post-hero-content">
                    <?php $cats = get_the_category(); if ($cats) : ?>
                    <span class="post-cat"><?php echo esc_html($cats[0]->name); ?></span>
                    <?php endif; ?>
                    <h1 style="color:#fff;font-size:clamp(1.8rem,3.5vw,2.8rem);margin:0.8rem 0;line-height:1.2;"><?php the_title(); ?></h1>
                    <div class="single-post-meta">
                        <span><?php echo esc_html(get_the_date()); ?></span>
                        <span>·</span>
                        <span><?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> min czytania</span>
                        <span>·</span>
                        <span><?php echo esc_html(get_the_author()); ?></span>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div style="margin-bottom:2rem;">
                <?php $cats = get_the_category(); if ($cats) : ?>
                <span class="post-cat"><?php echo esc_html($cats[0]->name); ?></span>
                <?php endif; ?>
                <h1 style="font-size:clamp(1.8rem,3.5vw,2.8rem);margin:0.8rem 0;line-height:1.2;"><?php the_title(); ?></h1>
                <div class="single-post-meta">
                    <span><?php echo esc_html(get_the_date()); ?></span>
                    <span>·</span>
                    <span><?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> min czytania</span>
                    <span>·</span>
                    <span><?php echo esc_html(get_the_author()); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="single-post-body reveal-fade">
                <?php the_content(); ?>
            </div>

            <!-- Share -->
            <div style="border-top:1px solid rgba(200,169,110,0.15);border-bottom:1px solid rgba(200,169,110,0.15);padding:1.5rem 0;margin:3rem 0;display:flex;gap:1rem;align-items:center;flex-wrap:wrap;justify-content:center;">
                <span style="color:var(--text-secondary);font-size:0.85rem;"><?php esc_html_e('Udostępnij:', 'hrl-theme'); ?></span>
                <?php $url = urlencode(get_permalink()); $title = urlencode(get_the_title()); ?>
                <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;font-size:0.85rem;">𝕏 Twitter</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;font-size:0.85rem;">📘 Facebook</a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;font-size:0.85rem;">💼 LinkedIn</a>
            </div>

            <!-- Related Posts -->
            <?php
            $cats = get_the_category();
            $cat_ids = $cats ? wp_list_pluck($cats, 'term_id') : array();
            $related = new WP_Query(array(
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'category__in' => $cat_ids,
            ));
            if ($related->have_posts()) :
            ?>
            <div style="margin:3rem 0;">
                <h3 style="font-size:1.1rem;color:var(--gold);margin-bottom:1.2rem;text-transform:uppercase;letter-spacing:0.1em;"><?php esc_html_e('Powiązane artykuły', 'hrl-theme'); ?></h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" style="display:block;background:rgba(18,15,12,0.5);border:1px solid rgba(200,169,110,0.08);border-radius:8px;overflow:hidden;text-decoration:none;transition:all 0.3s;">
                        <?php if (has_post_thumbnail()) : ?>
                        <div style="height:140px;overflow:hidden;"><?php the_post_thumbnail('medium', array('style' => 'width:100%;height:100%;object-fit:cover;transition:transform 0.4s;')); ?></div>
                        <?php endif; ?>
                        <div style="padding:1rem;">
                            <h4 style="font-size:0.9rem;color:var(--text-primary);margin:0 0 0.3rem;"><?php the_title(); ?></h4>
                            <span style="font-size:0.7rem;color:var(--text-secondary);"><?php echo esc_html(get_the_date()); ?></span>
                        </div>
                    </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Back -->
            <div style="text-align:center;margin:2rem 0;">
                <a href="<?php echo esc_url(home_url('/blogcast/')); ?>" class="btn btn-outline">← <?php esc_html_e('Powrót do BlogCast', 'hrl-theme'); ?></a>
            </div>
        </article>
    </div>
</div>

<script>
(function(){
    var bar = document.getElementById('readingProgress');
    if (!bar) return;
    window.addEventListener('scroll', function(){
        var h = document.documentElement;
        var scroll = h.scrollTop || document.body.scrollTop;
        var height = h.scrollHeight - h.clientHeight;
        bar.style.width = height > 0 ? (scroll / height * 100) + '%' : '0%';
    });
    document.querySelectorAll('.single-post-article a[href*="twitter"]').forEach(function(a){
        a.addEventListener('mouseenter', function(){ a.style.color = 'var(--gold-light)'; });
        a.addEventListener('mouseleave', function(){ a.style.color = 'var(--gold)'; });
    });
})();
</script>

<?php get_footer(); ?>
