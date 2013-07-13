<div id="factsheet" class="four columns">
	<h2>Factsheet</h2>
	<dl>
		<dt>Developer:</dt>
		<dd><?php echo $developer->title; ?></dd>
		<dd>Based in: <?php echo $developer->basedIn; ?></dd>

		<dt>Founding date:</dt>
		<dd><?php echo $developer->foundingDate; ?></dd>

		<dt>Website:</dt>
		<dd><?php echo $view->link($developer->website); ?></dd>

		<dt>Press / Business Contact:</dt>
		<dd><?php echo $view->email($developer->pressContact); ?></dd>

		<dt>Social:</dt>
		<?php foreach($developer->social as $social): ?>
			<dd><?php echo $view->link($social['link'], $social['name']); ?></dd>
		<?php endforeach; ?>

		<dt>Phone:</dt>
		<dd><?php echo $view->callto($developer->phone); ?></dd>
	</dl>
</div>

<div id="description" class="ten columns">
	<h2>Description</h2>
	<p><?php echo $developer->description; ?></p>
</div>

<div id="history" class="ten columns">
	<h2>History</h2>
	<?php foreach($developer->history as $history): ?>
		<h3><?php echo $history['header']; ?></h3>
		<p><?php echo $history['text']; ?></p>
	<?php endforeach; ?>
</div>