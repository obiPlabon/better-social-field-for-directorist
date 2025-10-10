<?php
/**
 * @author  obiPlabon
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$socials = $listing->get_socials();

if ( empty( $socials ) ) {
	return;
}

$socials = array_column( $data['value'], 'url', 'id' );

?>
<div class="directorist-single-info directorist-single-info-socials">

	<?php if ( $data['label'] ) : ?>
		<div class="directorist-single-info__label">
			<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
			<span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
		</div>
	<?php endif; ?>

	<div class="directorist-social-links">
		<?php foreach ( bsfd()->get_supported_social_media() as $media ) :
			if ( empty( $socials[ $media['id'] ] ) ) {
				continue;
			}
			?>
			<a target='_blank' href="<?php echo esc_url( $socials[ $media['id'] ] ); ?>" class="bsfd-<?php echo esc_attr( $media['id'] ); ?>">
				<?php directorist_icon( $media['icon'] ); ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
