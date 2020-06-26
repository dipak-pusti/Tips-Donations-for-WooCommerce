<div class="tips_container">
    <h2 class="tips_label"><?php echo apply_filters( 'order_tips_heading', get_option( '_otd_tips_heading', 'true' ) ); ?></h2>
    <div class="tips_fields">
        <input type="text" value="" class="tips-amount" name="tips-amount" />
        <input type="button" value="<?php echo apply_filters( 'order_tips_button_text', get_option( '_otd_tips_text', true ) ); ?>" class="tips-action" data-action="add_tips" name="tips-submit" />
        <?php if( 'yes' === get_option( '_otd_allow_remove', true ) ) :  ?>
        	<input type="button" value="<?php echo apply_filters( 'order_tips_remove_text', get_option( '_otd_remove_tips_text', true ) ); ?>" class="tips-action" data-action="remove_tips" name="tips-remove">
        <?php endif; ?>

        <span class="tips-spinner"></span>
        <span class="tips-error"></span>
    </div>
</div>