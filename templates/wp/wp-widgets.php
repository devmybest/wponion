<?php $settings = $this->widgets(); ?>

<div class="<?php echo $settings->wrap_class( '', true ); ?>">
	<?php
	foreach ( $settings->fields() as $field ) {
		echo $settings->render_field( $field );
	}
	?>
</div>
