<?php // src/views/admin/batches.php ?>
<h2>Batches</h2>
<form method="post" action="?r=admin/batches">
  <input type="text" name="name" placeholder="Batch name"><button type="submit">Add</button>
</form>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Action</th></tr>
  <?php foreach($batches as $b): ?>
    <tr>
      <td><?= $b['BatchID'] ?></td>
      <td><?= htmlspecialchars($b['BatchName']) ?></td>
      <td><a href="?r=admin/batches&delete=<?= $b['BatchID'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
    </tr>
  <?php endforeach; ?>
</table>
