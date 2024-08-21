<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    header("Location: ../session/login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['act_type'])) {
        $act_type = $_POST['act_type'];
        $stmt = $conn->prepare("INSERT INTO act_types (name) VALUES (:act_type)");
        $stmt->bindParam(':act_type', $act_type);
        if ($stmt->execute()) {
            header("Location: admin.php?success=1&message=Nouveau type d'acte ajouté avec succès.");
        } else {
            $error_message = $stmt->errorInfo()[2];
            header("Location: admin.php?success=0&message=Erreur: " . urlencode($error_message));
        }
        exit;
    }

    if (isset($_POST['instance_type'])) {
        $instance_type = $_POST['instance_type'];
        $stmt = $conn->prepare("INSERT INTO instance_type (name) VALUES (:instance_type)");
        $stmt->bindParam(':instance_type', $instance_type);
        if ($stmt->execute()) {
            header("Location: admin.php?success=1&message=Type d'instance ajouté avec succès.");
        } else {
            $error_message = $stmt->errorInfo()[2];
            header("Location: admin.php?success=0&message=Erreur: " . urlencode($error_message));
        }
        exit;
    }

    if (isset($_POST['category'])) {
        $category = $_POST['category'];
        $stmt = $conn->prepare("INSERT INTO category (name) VALUES (:category)");
        $stmt->bindParam(':category', $category);
        if ($stmt->execute()) {
            header("Location: admin.php?success=1&message=Nouvelle catégorie ajoutée avec succès.");
        } else {
            $error_message = $stmt->errorInfo()[2];
            header("Location: admin.php?success=0&message=Erreur: " . urlencode($error_message));
        }
        exit;
    }

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $role = $_POST['role'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (:username, :password, :role, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            header("Location: admin.php?success=1&message=Nouvel utilisateur ajouté avec succès.");
        } else {
            $error_message = $stmt->errorInfo()[2];
            header("Location: admin.php?success=0&message=Erreur: " . urlencode($error_message));
        }
        exit;
    }

    if (isset($_POST['delete_user_id'])) {
        $user_id = $_POST['delete_user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Utilisateur supprimé avec succès."]);
        } else {
            $error_message = $stmt->errorInfo()[2];
            echo json_encode(['success' => false, 'message' => "Erreur: " . $error_message]);
        }
        exit;
    }

}
?>
