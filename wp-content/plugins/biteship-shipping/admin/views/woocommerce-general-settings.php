<script type="text/html" id="tmpl-woocommerce-store-coordinate-picker">
    <div class="wc-backbone-modal" id="woocommerce-store-coordinate-modal">
        <div class="wc-backbone-modal-content">
            <section class="wc-backbone-modal-main" role="main">
                <header class="wc-backbone-modal-header">
                    <h1>Pin Location</h1>
                    <button class="modal-close modal-close-link dashicons dashicons-no-alt"></button>
                </header>

                <article class="wc-backbone-modal-container">
                  <input id="woocommerce-store-coordinate-autocomplete" class="woocommerce-place-autocomplete" type="text" placeholder="Search place">
                  <input id="woocommerce-store-coordinate-preview" type="text" readonly placeholder="Coordinate">
                  <div id="woocommerce-store-coordinate-map" class="woocommerce-map"></div>
                </article>

                <footer>
                    <button id="woocommerce-store-coordinate-saver" class="button-primary modal-close">Save</button>
                    <button class="button-secondary modal-close">Close</button>
                </footer>
            </section>
        </div>
    </div>
</script>
