<?php
/**
 * Template Name: 3D Showcase
 * Full-page 3D/WebGL interactive experience.
 * Optional: requires Three.js (loaded via CDN).
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
$three_js = get_post_meta( get_the_ID(), 'hrl_3d_enable_three', true );
?>

<?php if ( $three_js ) : ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
<?php endif; ?>

<section class="hero relative overflow-hidden">
    <div class="hero-content relative z-10">
        <p class="hero-eyebrow"><?php esc_html_e( 'Immersive Experience', 'hrl-theme' ); ?></p>
        <h1><?php the_title(); ?></h1>
        <?php if ( get_post_meta( get_the_ID(), 'hrl_3d_subtitle', true ) ) : ?>
            <p class="hero-sub"><?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_3d_subtitle', true ) ); ?></p>
        <?php endif; ?>
    </div>

    <?php if ( $three_js ) : ?>
        <div id="three-canvas" class="absolute inset-0 z-0 pointer-events-none opacity-30"></div>
    <?php else : ?>
        <div id="fallback-3d-bg" class="absolute inset-0 z-0 bg-radial-gold"></div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="container">
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <div class="hrl-stagger">
            <?php
            $children = new WP_Query( array(
                'post_type'      => 'page',
                'post_parent'    => get_the_ID(),
                'posts_per_page' => 6,
                'order'          => 'ASC',
            ));
            if ( $children->have_posts() ) :
                while ( $children->have_posts() ) : $children->the_post();
            ?>
                <div class="card cursor-default">
                    <div class="card-icon">✨</div>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php if ( $three_js ) : ?>
<script>
(function(){
  'use strict';
  try {
    var container = document.getElementById('three-canvas');
    if (!container || typeof THREE === 'undefined') return;
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    var renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);
    var geo = new THREE.BufferGeometry();
    var verts = new Float32Array(3000);
    for (var i = 0; i < 3000; i++) { verts[i] = (Math.random() - 0.5) * 40; }
    geo.setAttribute('position', new THREE.BufferAttribute(verts, 3));
    var mat = new THREE.PointsMaterial({ color: 0xC8A96E, size: 0.06, transparent: true, opacity: 0.7 });
    var points = new THREE.Points(geo, mat);
    scene.add(points);
    camera.position.z = 12;
    function animate() {
      requestAnimationFrame(animate);
      points.rotation.y += 0.001;
      points.rotation.x += 0.0005;
      renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', function(){
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
  } catch (e) { console.warn('Three.js init failed:', e); }
})();
</script>
<?php endif; ?>

<?php get_footer(); ?>
