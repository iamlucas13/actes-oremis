<?php if (isset($user_role) && $user_role === 'admin') { ?>
<h2 class="mt-3">Ajouter un utilisateur</h2>
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
