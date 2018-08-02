<?= render("header") ?>

<pre><?= $_SERVER["REMOTE_ADDR"] ?> used <?= $_SERVER["REQUEST_URI"] ?>!
<?= $_SERVER["REMOTE_ADDR"] ?>'s attack missed!
Enemy <?= $_SERVER["SERVER_NAME"] ?> used 404 Not Found!
<?= $_SERVER["REMOTE_ADDR"] ?> became confused!</pre>

<?= render("footer") ?>
