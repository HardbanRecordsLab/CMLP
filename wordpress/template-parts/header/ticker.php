<?php
/**
 * Template Part: Dual Scrolling Ticker (News + Markets)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="tickers-container">
    <div class="ticker-wrapper">
        <div class="ticker-title"><?php esc_html_e( 'HRL GLOBAL', 'hrl-theme' ); ?></div>
        <div class="ticker-text-container">
            <div class="ticker-move">
                <?php echo hrl_get_ticker_news_html(); ?>
            </div>
        </div>
    </div>

    <div class="ticker-wrapper">
        <div class="ticker-title markets"><?php esc_html_e( 'NBP KURSY', 'hrl-theme' ); ?></div>
        <div class="ticker-text-container">
            <div class="ticker-move reverse" id="market-ticker">
                <?php echo hrl_get_ticker_financial_html(); ?>
            </div>
        </div>
    </div>
</div>
