<?php // src/views/admin/techs.php ?>
<h2>Technologies</h2>
<form method="post" action="?r=admin/techs">
  <input type="text" name="name" placeholder="Tech name"><button type="submit">Add</button>
</form>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Action</th></tr>
  <?php foreach($techs as $t): ?>
    <tr>
      <td><?= $t['TechID'] ?></td>
      <td><?= htmlspecialchars($t['TechName']) ?></td>
      <td><a href="?r=admin/techs&delete=<?= $t['TechID'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
    </tr>
  <?php endforeach; ?>
</table>
