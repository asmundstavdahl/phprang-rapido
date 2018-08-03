<?= render("header") ?>

<h1>New(?) task</h1>

<form method="POST">
	<input type="text" name="body" value="<?= $task->body ?>">
	<input type="submit">
</form>

<?= render("footer") ?>
