<?php if (isset($user_role) && $user_role === 'admin') { ?>

<h2 class="mt-3">Types d'actes</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="act_type">Ajouter un nouveau type d'acte:</label>
        <input type="text" name="act_type" id="act_type" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<h2 class="mt-3">Types d'instances</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="instance_type">Ajouter un nouveau type d'instance:</label>
        <input type="text" name="instance_type" id="instance_type" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<h2 class="mt-3">Catégories</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="category">Ajouter une nouvelle catégorie:</label>
        <input type="text" name="category" id="category" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<h2 class="mt-3">Utilisateurs</h2>
<form action="actions.php" method="post">
    <div class="form-group">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" id="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email google (@oremis.fr)</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" class="form-control">
        <small>Laissez vide pour un compte Google</small>
    </div>
    <div class="form-group">
        <label for="role">Rôle:</label>
        <select name="role" id="role" class="form-control" required>
            <option value="admin">Administrateur</option>
            <option value="user">Utilisateur</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php } ?>
