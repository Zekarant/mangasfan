<h2 class="titre"><?= \Rewritting::sanitize($changelog['title_changelog']) ?></h2>
<hr>
<?= htmlspecialchars_decode(\Rewritting::sanitize($changelog['text_changelog'])); ?>
<hr>
<p>Ce changelog a été posté le <?= date('d/m/Y', strtotime(\Rewritting::sanitize($changelog['date_changelog']))); ?>.</p>
