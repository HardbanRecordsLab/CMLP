<?php
/**
 * HRL BlogCast — Magazynowy layout
 * Magazine-style grid z kategoriami, featured post, sidebar.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="blogcast-magazine" style="padding-top:100px;">
    <div class="container">

        <!-- Header -->
        <header class="text-center py-8">
            <div class="flex justify-center items-center gap-4 text-xs uppercase tracking-widest text-secondary mb-3">
                <span><?php esc_html_e( 'Wydanie', 'hrl-theme' ); ?></span>
                <span class="w-8 h-px bg-accent opacity-40"></span>
                <span id="blogcastDate"><?php echo esc_html( date_i18n( 'j F Y' ) ); ?></span>
                <span class="w-8 h-px bg-accent opacity-40"></span>
                <span><?php esc_html_e( 'Laboratorium Cyfrowej Kreatywności', 'hrl-theme' ); ?></span>
            </div>
            <h1 class="font-serif text-5xl tracking-wide inline-block my-1">HRL<span class="text-accent">BlogCast</span></h1>
            <p class="text-secondary text-base italic font-serif"><?php esc_html_e( 'Zaawansowane transkrypcje, analizy rynkowe, niezależna publicystyka', 'hrl-theme' ); ?></p>
        </header>

        <!-- Categories Nav -->
        <nav class="categories-nav" style="margin-bottom:2.5rem;">
            <ul class="main-cats" id="mainCategoriesMenu">
                <?php
                $parent_cats = get_categories(array('parent' => 0, 'hide_empty' => 0));
                foreach ($parent_cats as $cat) :
                    if ($cat->slug === 'uncategorized') continue;
                ?>
                <li class="cat-item">
                    <a class="cat-link" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">#<?php echo esc_html($cat->name); ?></a>
                    <?php
                    $subs = get_categories(array('child_of' => $cat->term_id, 'hide_empty' => 0));
                    if (!empty($subs)) :
                    ?>
                    <div class="sub-cats-container">
                        <?php foreach ($subs as $sub) : ?>
                        <a class="sub-link" href="<?php echo esc_url(get_category_link($sub->term_id)); ?>"><?php echo esc_html($sub->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <?php
        $featured_query = new WP_Query(array(
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'meta_key' => '_thumbnail_id',
        ));
        $has_featured = $featured_query->have_posts();

        $recent_query = new WP_Query(array(
            'posts_per_page' => 10,
            'post_status' => 'publish',
            'offset' => $has_featured ? 1 : 0,
        ));
        ?>

        <?php if ($has_featured) : ?>
        <!-- FEATURED STORY -->
        <?php $featured_query->the_post(); ?>
        <section class="featured-story reveal-up">
            <a href="<?php the_permalink(); ?>" style="display:block;position:relative;border-radius:12px;overflow:hidden;min-height:420px;">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('style' => 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;')); ?>
                <?php endif; ?>
                <div style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(0,0,0,0.95) 0%,rgba(0,0,0,0.4) 50%,rgba(0,0,0,0.2) 100%);"></div>
                <div style="position:absolute;bottom:0;left:0;right:0;padding:3rem 2.5rem;">
                    <?php $cats = get_the_category(); if ($cats) : ?>
                    <span style="display:inline-block;background:var(--gold);color:#000;padding:3px 12px;border-radius:3px;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;margin-bottom:1rem;"><?php echo esc_html($cats[0]->name); ?></span>
                    <?php endif; ?>
                    <h2 style="font-size:clamp(1.5rem,3vw,2.4rem);color:#fff;margin:0 0 0.8rem;line-height:1.2;"><?php the_title(); ?></h2>
                    <p style="color:rgba(255,255,255,0.7);margin:0;max-width:600px;font-size:0.95rem;line-height:1.6;"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?></p>
                    <div style="display:flex;gap:1rem;align-items:center;margin-top:1rem;">
                        <span style="color:var(--gold);font-size:0.8rem;"><?php echo esc_html(get_the_date()); ?></span>
                        <span style="color:rgba(255,255,255,0.4);font-size:0.8rem;">·</span>
                        <span style="color:rgba(255,255,255,0.4);font-size:0.8rem;"><?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> min czytania</span>
                    </div>
                </div>
            </a>
        </section>
        <?php wp_reset_postdata(); endif; ?>

        <!-- MAIN GRID -->
        <div class="magazine-grid" style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;margin-top:2.5rem;">
            <!-- LEFT: Articles grid -->
            <div>
                <?php if ($recent_query->have_posts()) : ?>
                <div class="articles-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.8rem;">
                    <?php $idx = 0; while ($recent_query->have_posts()) : $recent_query->the_post(); $idx++; ?>
                    <article class="mag-card reveal-up" style="background:rgba(18,15,12,0.5);border:1px solid rgba(200,169,110,0.08);border-radius:10px;overflow:hidden;transition:all 0.3s;display:flex;flex-direction:column;<?php if ($idx === 1) echo 'grid-column:1/-1;flex-direction:row;'; ?>">
                        <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" style="display:block;overflow:hidden;<?php echo $idx === 1 ? 'flex:0 0 45%;min-height:240px;' : 'height:200px;'; ?>">
                            <?php the_post_thumbnail($idx === 1 ? 'large' : 'medium_large', array('style' => 'width:100%;height:100%;object-fit:cover;transition:transform 0.5s;')); ?>
                        </a>
                        <?php endif; ?>
                        <div style="padding:1.5rem;flex:1;display:flex;flex-direction:column;">
                            <?php $cats = get_the_category(); if ($cats) : ?>
                            <span style="font-size:0.68rem;color:var(--gold);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.5rem;"><?php echo esc_html($cats[0]->name); ?></span>
                            <?php endif; ?>
                            <h3 style="font-size:<?php echo $idx === 1 ? '1.3rem' : '1rem'; ?>;margin:0 0 0.5rem;line-height:1.35;">
                                <a href="<?php the_permalink(); ?>" style="color:var(--text-primary);text-decoration:none;"><?php the_title(); ?></a>
                            </h3>
                            <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.6;flex:1;"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $idx === 1 ? 35 : 18)); ?></p>
                            <div style="display:flex;gap:0.8rem;align-items:center;font-size:0.72rem;color:var(--text-secondary);margin-top:0.8rem;">
                                <span><?php echo esc_html(get_the_date()); ?></span>
                                <span>·</span>
                                <span><?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> min</span>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                <?php else : ?>
                <div style="text-align:center;padding:4rem 0;color:var(--text-secondary);">
                    <p style="font-size:1.2rem;">📰 <?php esc_html_e('Artykuły już wkrótce.', 'hrl-theme'); ?></p>
                    <p style="font-size:0.9rem;"><?php esc_html_e('Nasz zespół pracuje nad pierwszymi publikacjami. Zapisz się do newslettera, aby otrzymać powiadomienie.', 'hrl-theme'); ?></p>
                </div>
                <?php endif; wp_reset_postdata(); ?>
            </div>

            <!-- RIGHT: Sidebar -->
            <aside class="blogcast-sidebar" style="display:flex;flex-direction:column;gap:2rem;">
                <!-- Newsletter -->
                <div style="background:rgba(18,15,12,0.6);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:1.5rem;">
                    <h4 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold);margin:0 0 0.8rem;">📬 <?php esc_html_e('Premium Newsletter', 'hrl-theme'); ?></h4>
                    <p style="font-size:0.82rem;color:var(--text-secondary);line-height:1.6;margin-bottom:1rem;"><?php esc_html_e('Codzienna dawka analiz rynkowych, AI i niezależnej publicystyki.', 'hrl-theme'); ?></p>
                    <form style="display:flex;flex-direction:column;gap:0.6rem;">
                        <input type="text" placeholder="<?php esc_attr_e('Imię', 'hrl-theme'); ?>" style="background:rgba(0,0,0,0.3);border:1px solid rgba(200,169,110,0.15);padding:0.6rem 0.8rem;border-radius:5px;color:var(--text-primary);font-size:0.85rem;">
                        <input type="email" placeholder="<?php esc_attr_e('Adres e-mail', 'hrl-theme'); ?>" style="background:rgba(0,0,0,0.3);border:1px solid rgba(200,169,110,0.15);padding:0.6rem 0.8rem;border-radius:5px;color:var(--text-primary);font-size:0.85rem;">
                        <button type="submit" class="btn btn-primary" style="width:100%;font-size:0.8rem;padding:0.6rem;"><?php esc_html_e('Zapisz się', 'hrl-theme'); ?></button>
                    </form>
                </div>

                <!-- Live Markets -->
                <div style="background:rgba(18,15,12,0.6);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:1.5rem;">
                    <h4 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold);margin:0 0 1rem;">📊 <?php esc_html_e('Live Markets', 'hrl-theme'); ?></h4>
                    <div style="display:flex;flex-direction:column;gap:0.8rem;font-size:0.8rem;color:var(--text-secondary);">
                        <div style="display:flex;justify-content:space-between;"><span>BTC</span><span style="color:var(--gold);">$64,172</span></div>
                        <div style="display:flex;justify-content:space-between;"><span>ETH</span><span style="color:var(--gold);">$1,873</span></div>
                        <div style="display:flex;justify-content:space-between;"><span>USD/PLN</span><span>3.77</span></div>
                        <div style="display:flex;justify-content:space-between;"><span>EUR/PLN</span><span>4.33</span></div>
                        <div style="display:flex;justify-content:space-between;"><span>NVDA</span><span style="color:#4caf50;">$207.40</span></div>
                        <div style="display:flex;justify-content:space-between;"><span>AAPL</span><span style="color:#4caf50;">$333.26</span></div>
                    </div>
                </div>

                <!-- Tags -->
                <div style="background:rgba(18,15,12,0.6);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:1.5rem;">
                    <h4 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold);margin:0 0 1rem;">🏷️ <?php esc_html_e('Kluczowe Tagi', 'hrl-theme'); ?></h4>
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                        <?php
                        $tags = array('AI', 'Music', 'Licensing', 'Tech', 'SaaS', 'Indie', 'Premium', 'Forex');
                        foreach ($tags as $tag) : ?>
                        <span style="background:rgba(200,169,110,0.08);border:1px solid rgba(200,169,110,0.15);padding:0.3rem 0.7rem;border-radius:4px;font-size:0.7rem;color:var(--gold);letter-spacing:0.05em;">#<?php echo esc_html($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
