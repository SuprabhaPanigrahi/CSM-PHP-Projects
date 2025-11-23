<?php // src/views/admin/results.php ?>
<h2>Results</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Guest</th><th>Email</th><th>Score</th><th>Total</th><th>Date</th></tr>
<?php foreach($results as $r): ?>
  <tr>
    <td><?= $r['attemptID'] ?></td>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= $r['score'] ?></td>
    <td><?= $r['total'] ?></td>
    <td><?= $r['created_at'] ?></td>
  </tr>
<?php endforeach; ?>
</table>
