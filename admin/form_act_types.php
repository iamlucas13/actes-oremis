<?php if (isset($user_role) && $user_role === 'admin') { ?>
<h2 class="mt-3">Ajouter un type d'acte</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="act_type">Ajouter un nouveau type d'acte:</label>
        <input type="text" name="act_type" id="act_type" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
<?php } ?>
