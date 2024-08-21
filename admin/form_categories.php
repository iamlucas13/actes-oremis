<?php if (isset($user_role) && $user_role === 'admin') { ?>
<h2 class="mt-3">Ajouter une catégorie</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="category">Ajouter une nouvelle catégorie:</label>
        <input type="text" name="category" id="category" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
<?php } ?>
