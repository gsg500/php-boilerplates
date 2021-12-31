<?php if ( !empty ( $messages['errors'] ) ) : ?>
	<div class="message">
	<?php foreach( $messages['errors'] as $error ) : ?>
		<p class="error"><?= $error; ?></p>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if ( !empty ( $messages['success'] ) ) : ?>
	<div class="message">
	<?php foreach( $messages['success'] as $success ) : ?>
		<p class="success"><?= $success; ?></p>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if ( !empty ( $messages['notifications'] ) ) : ?>
	<div class="message">
		<?php foreach( $messages['notifications'] as $notification ) : ?>
			<p class="success"><?= $notification; ?></p>
		<?php endforeach; ?>
	</div>
<?php endif; ?>