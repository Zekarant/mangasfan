<?php if(isset($_SESSION['flash'])): ?>
<?php foreach($_SESSION['flash'] as $type => $message): ?>
  <div class="alerte<?= $type ;?>">
<?= $message; ?>
  </div>
<?php endforeach; ?>
<?php unset($_SESSION['flash']); ?>
<?php endif; ?>
