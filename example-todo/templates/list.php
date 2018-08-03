<?= render("header") ?>

<h1>TODO</h1>

<?php if(count($tasks) > 0): ?>
	<?php foreach($tasks as $task): ?>
		<div class="task">
			<h2>
				<a class="button" href="<?= route("done", [$task->id]) ?>">x</a>
				<?= $task->body ?>
				<a class="button" href="<?= route("edit", [$task->id]) ?>">edit</a>
			</h2>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<h2>You're all done :)</h2>
<?php endif; ?>

<hr>

<a class="button" href="<?= route("new") ?>">New task</a>

<?= render("footer") ?>
