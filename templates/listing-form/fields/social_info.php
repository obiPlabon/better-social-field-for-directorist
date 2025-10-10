<?php
/**
 * @author  obiPlabon
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$saved_value = [];
if ( !empty( $data['value'] ) && is_array( $data['value'] ) ) {
	$saved_value = array_column( $data['value'], 'url', 'id' );
}

?>
<div class="directorist-form-group directorist-form-social-info-field">

	<?php $listing_form->field_label_template( $data ); ?>
	
	<?php
	$index = 0;

	foreach ( bsfd()->get_supported_social_media() as $media ) :
		$value    = $saved_value[ $media['id'] ] ?? '';
		$html_id = 'bsfd-social-media-' . $media['id'];
		?>
		<div class="directorist-form-social-fields__input directorist-align-center directorist-mt-10"> 
			<label for="<?php echo esc_attr( $html_id ); ?>" class="directorist-form-group directorist-mb-0">
				<span class="directorist-flex directorist-justify-content-start directorist-align-center">
					<?php directorist_icon( $media['icon'] ); ?> <span style="margin-left: 10px"><?php echo esc_html( $media['label'] ); ?></span>
				</span>
				<input type="hidden" name="social[<?php echo esc_attr( $index ); ?>][id]" value="<?php echo esc_attr( $media['id'] ); ?>">
			</label>
			<div class="directorist-form-group">
				<input type="text"
					id="<?php echo esc_attr( $html_id ); ?>"
					name="social[<?php echo esc_attr( $index ); ?>][url]"
					class="directorist-form-element directory_field atbdp_social_input"
					value="<?php echo esc_attr( $value ); ?>"
					placeholder="<?php echo esc_attr( $media['placeholder'] ) ?>">
			</div>
		</div>
		<?php
	$index++;

	endforeach;
	?>
</div>
