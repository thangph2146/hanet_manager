<?php
/**
 * 9/17/2022
 * AUTHOR:PDV-PC
 */
?>
<a
	<?php if ($url != '') : ?>
		href="<?= $url ?>"
	<?php endif; ?>

	<?php if ($style != '') : ?>
		style = "<?= $style ?>"
	<?php endif; ?>

	<?php if ($class != '') : ?>
		class="<?= $class ?>"
	<?php endif; ?>

	<?php if ($title != '') : ?>
		title="<?= $title ?>"
	<?php endif; ?>

	<?= $js ?? '' ?>
	data-toggle="tooltip">
	<div class="font-22 text-primary">
		<?php if ($icon != '') : ?>
			<i class="<?= $icon ?>"></i>
		<?php endif; ?>
		<?= $label ?? '' ?>
	</div>
</a>

