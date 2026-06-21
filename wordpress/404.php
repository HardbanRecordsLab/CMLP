<?php
get_header();
?>
<section class="error-404">
    <div>
        <h1>404</h1>
        <p><?php esc_html_e( 'Strona nie została odnaleziona.', 'hrl-theme' ); ?></p>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:24px;"><?php esc_html_e( 'Plik nie istnieje lub został przeniesiony. Sprawdź poniższe ścieżki ratunkowe.', 'hrl-theme' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( '← Strona Główna', 'hrl-theme' ); ?></a>
        <div class="terminal-links" style="margin-top:32px;">
            <p style="margin-bottom:8px;">$ ls /ratunkowe-linki/</p>
            <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>">/blogcast</a> ·
            <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>">/cmlp</a> ·
            <a href="<?php echo esc_url( home_url( '/academy/' ) ); ?>">/academy</a> ·
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">/contact</a> ·
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>">/radio</a>
        </div>
    </div>
</section>
<?php get_footer(); ?>