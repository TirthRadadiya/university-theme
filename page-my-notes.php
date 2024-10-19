<?php

if ( ! is_user_logged_in() ) {
	wp_redirect( esc_url( site_url( '/' ) ) );
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	pageBanner();
	?>

	<div class="container container--narrow page-section">
		<div class="create-note">
			<h2 class="headline headline--medium"><?php esc_html_e( 'Create New Note', 'text-domain' ); ?></h2>
			<input class="new-note-title" placeholder="<?php esc_attr_e( 'Title', 'text-domain' ); ?>">
			<textarea class="new-note-body" placeholder="<?php esc_attr_e( 'Your note here...', 'text-domain' ); ?>"></textarea>
			<span class="submit-note"><?php esc_html_e( 'Create Note', 'text-domain' ); ?></span>
			<span class="note-limit-message">
				<?php esc_html_e( 'Note limit reached: delete an existing note to make room for a new one.', 'text-domain' ); ?>
			</span>
		</div>

		<ul class="min-list link-list" id="my-notes">
			<?php
			$user_notes = new WP_Query(
				array(
					'posts_per_page' => -1,
					'post_type'      => 'note',
					'author'         => get_current_user_id(),
				)
			);

			while ( $user_notes->have_posts() ) {
				$user_notes->the_post(); ?>
				<li data-id="<?php the_ID(); ?>">
					<input readonly class="note-title-field" value="<?php echo esc_attr( str_replace( 'Private: ', '', get_the_title() ) ); ?>">
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> <?php esc_html_e( 'Edit', 'text-domain' ); ?></span>
					<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php esc_html_e( 'Delete', 'text-domain' ); ?></span>
					<textarea readonly class="note-body-field"><?php echo esc_textarea( wp_strip_all_tags( get_the_content() ) ); ?></textarea>
					<span class="update-note btn btn--blue btn--small">
						<i class="fa fa-arrow-right" aria-hidden="true"></i> <?php esc_html_e( 'Save', 'text-domain' ); ?>
					</span>
				</li>
			<?php }
			?>
		</ul>
	</div>

	<?php
}

get_footer(); ?>