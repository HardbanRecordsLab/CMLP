<?php
/**
 * Template Part: Sidebar — BlogCast
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$market_data = function_exists( 'hrl_get_live_financial_string' ) ? hrl_get_live_financial_string() : '';
?>
<div class="sidebar-blogcast" style="position:sticky;top:100px;display:flex;flex-direction:column;gap:24px;">

    <!-- Newsletter Widget -->
    <div class="widget" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:22px;backdrop-filter:blur(10px);">
        <h4 class="widget-title" style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:2px;color:var(--gold);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--border-color);">
            <?php esc_html_e( 'Premium Newsletter', 'hrl-theme' ); ?>
        </h4>
        <p style="font-size:0.8rem;color:var(--text-secondary);margin-bottom:14px;line-height:1.5;">
            <?php esc_html_e( 'Codzienna dawka analiz rynkowych, AI i niezależnej publicystyki.', 'hrl-theme' ); ?>
        </p>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="newsletter-form-premium">
            <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
            <input type="hidden" name="action" value="hrl_subscribe_newsletter">
            <input type="text" name="subscriber_name" placeholder="<?php esc_attr_e( 'Imię', 'hrl-theme' ); ?>" required style="width:100%;padding:10px;margin-bottom:8px;background:rgba(255,255,255,0.04);border:1px solid var(--border-color);color:var(--text-primary);border-radius:4px;font-size:0.85rem;">
            <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required style="width:100%;padding:10px;margin-bottom:10px;background:rgba(255,255,255,0.04);border:1px solid var(--border-color);color:var(--text-primary);border-radius:4px;font-size:0.85rem;">
            <button type="submit" style="width:100%;padding:10px;background:var(--gradient-gold);color:#000;border:none;font-weight:700;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;border-radius:4px;cursor:pointer;transition:all 0.3s;">
                <?php esc_html_e( 'Zapisz się', 'hrl-theme' ); ?>
            </button>
        </form>
    </div>

    <!-- Market Watch Widget -->
    <div class="widget" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:22px;">
        <h4 class="widget-title" style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:2px;color:var(--gold);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--border-color);">
            <?php esc_html_e( 'Live Markets', 'hrl-theme' ); ?>
        </h4>
        <?php if ( ! empty( $market_data ) ) : ?>
            <?php $segments = explode( ' || ', $market_data ); foreach ( $segments as $seg ) : $seg = trim( $seg ); if ( empty( $seg ) ) continue; ?>
                <?php
                $label = ''; $value = $seg;
                if ( 0 === strpos( $seg, '[CRYPTO]' ) ) { $label = '₿'; $value = substr( $seg, 8 ); }
                elseif ( 0 === strpos( $seg, '[FOREX]' ) )  { $label = '💱'; $value = substr( $seg, 7 ); }
                elseif ( 0 === strpos( $seg, '[STOCKS]' ) ) { $label = '📈'; $value = substr( $seg, 8 ); }
                ?>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.75rem;font-family:var(--font-mono, 'JetBrains Mono', monospace);">
                    <span style="color:var(--gold);"><?php echo esc_html( $label ); ?></span>
                    <span style="color:#E0E0E0;flex:1;margin-left:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo esc_html( trim( $value ) ); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="font-size:0.8rem;color:var(--text-secondary);"><?php esc_html_e( 'Ładowanie danych...', 'hrl-theme' ); ?></p>
        <?php endif; ?>
    </div>

    <!-- Radio Player Widget -->
    <div class="widget" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:22px;">
        <h4 class="widget-title" style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:2px;color:var(--gold);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--border-color);">
            <?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?>
        </h4>
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarPlayBtn" onclick="toggleSidebarAudio()" style="
                width:40px;height:40px;border-radius:50%;
                background:var(--gradient-accent);border:none;
                color:#fff;font-size:1rem;cursor:pointer;
                display:flex;align-items:center;justify-content:center;
                transition:transform 0.2s;flex-shrink:0;
            " aria-label="<?php esc_attr_e( 'Play Radio', 'hrl-theme' ); ?>">▶</button>
            <div>
                <div style="font-size:0.85rem;color:var(--text-primary);font-weight:600;"><?php esc_html_e( 'Słuchaj na żywo', 'hrl-theme' ); ?></div>
                <div style="font-size:0.7rem;color:var(--text-secondary);">128kbps · AzuraCast</div>
            </div>
        </div>
    </div>
</div>
