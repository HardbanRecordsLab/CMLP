<?php
/**
 * Template Part: Preloader Animation
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$preloader_enabled = get_theme_mod( 'hrl_preloader_toggle', false );
$preloader_type    = get_theme_mod( 'hrl_preloader_type', 'pulse' );
?>

<?php if ( $preloader_enabled ) : ?>
<div id="hrl-preloader" style="
    position:fixed; inset:0; z-index:100000;
    background:#000;
    display:flex; align-items:center; justify-content:center;
    transition: opacity 0.5s ease, visibility 0.5s ease;
">
    <div id="hrl-preloader-inner" style="text-align:center;">
        <?php if ( has_custom_logo() ) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <div class="preloader-logo" style="
                font-family: var(--font-accents, 'Cinzel', serif);
                font-size: 2.5rem; letter-spacing: 4px;
                color: var(--gold, #C8A96E);
                text-transform: uppercase;
            ">HARDBANRECORDS LAB</div>
        <?php endif; ?>

        <div class="preloader-anim" data-type="<?php echo esc_attr( $preloader_type ); ?>" style="
            width: 40px; height: 40px; margin: 24px auto 0;
        ">
            <?php if ( 'pulse' === $preloader_type ) : ?>
                <div style="
                    width:100%; height:100%; border-radius:50%;
                    background:var(--gradient-gold);
                    animation: preloaderPulse 1.2s ease-in-out infinite;
                "></div>
            <?php elseif ( 'spin' === $preloader_type ) : ?>
                <div style="
                    width:100%; height:100%; border-radius:50%;
                    border: 3px solid rgba(200,169,110,0.2);
                    border-top-color: var(--gold, #C8A96E);
                    animation: preloaderSpin 0.8s linear infinite;
                "></div>
            <?php elseif ( 'morph' === $preloader_type ) : ?>
                <div style="
                    width:100%; height:100%;
                    background:var(--gradient-gold);
                    animation: preloaderMorph 1.5s ease-in-out infinite;
                    border-radius: 50%;
                "></div>
            <?php elseif ( '3d-rotate' === $preloader_type ) : ?>
                <div style="
                    width:100%; height:100%;
                    background: var(--gradient-accent);
                    animation: preloader3d 1.5s ease-in-out infinite;
                    transform-style: preserve-3d;
                "></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>
@keyframes preloaderPulse {
  0%, 100% { transform: scale(1); opacity: 0.7; }
  50%      { transform: scale(1.25); opacity: 1; }
}
@keyframes preloaderSpin {
  0%   { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
@keyframes preloaderMorph {
  0%, 100% { border-radius: 50%; transform: rotate(0deg); }
  25%      { border-radius: 12px; transform: rotate(90deg); }
  50%      { border-radius: 50%; transform: rotate(180deg); }
  75%      { border-radius: 12px; transform: rotate(270deg); }
}
@keyframes preloader3d {
  0%   { transform: rotateX(0) rotateY(0); }
  50%  { transform: rotateX(180deg) rotateY(0deg); }
  100% { transform: rotateX(180deg) rotateY(180deg); }
}
</style>
<script>
(function(){
  window.addEventListener('load', function(){
    var p = document.getElementById('hrl-preloader');
    if (p) { p.style.opacity = '0'; setTimeout(function(){ p.style.visibility = 'hidden'; p.remove(); }, 500); }
  });
})();
</script>
<?php endif; ?>
