<h1>Hi</h1>
<p>
	Try one of these:
	<ul>
		<li>
			<a href="<?= route("hello_world") ?>">
				Hello world!
			</a>
		</li>
		<li>
			<a href="<?= route("hello_something", ["person"]) ?>">
				Hello person!
			</a>
		</li>
		<li>
			<a href="/unhandled/route">
				Hello 404!
			</a>
		</li>
	</ul>
</p>
