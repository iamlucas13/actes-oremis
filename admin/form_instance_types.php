<?php if (isset($user_role) && $user_role === 'admin') { ?>
<h2 class="mt-3">Ajouter un type d'instance</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="instance_type">Ajouter un nouveau type d'instance:</label>
        <input type="text" name="instance_type" id="instance_type" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
<?php } ?>
