<?php if (session()->has('errors')): ?>
	<ul class="ml-6">
		<?php foreach(session('errors') as $error): ?>
			<li class="tag is-danger is-light"><?= $error ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif ?>