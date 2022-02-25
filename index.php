<?php

/**
 * Commencez par importer le fichier sql live.sql via PHPMyAdmin.
 * 1. Sélectionnez tous les utilisateurs.
 * 2. Sélectionnez tous les articles.
 * 3. Sélectionnez tous les utilisateurs qui parlent de poterie dans un article.
 * 4. Sélectionnez tous les utilisateurs ayant au moins écrit deux articles.
 * 5. Sélectionnez l'utilisateur Jane uniquement s'il elle a écris un article ( le résultat devrait être vide ! ).
 *
 * ( PS: Sélectionnez, mais affichez le résultat à chaque fois ! ).
 */

try {
    $server = 'localhost';
    $db = 'live';
    $user = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$server;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Tous les utilisateurs
    $request = $pdo->prepare("
        SELECT * FROM user 
            WHERE EXISTS (SELECT * FROM user)
    ");

    if ($request->execute()) {
        foreach ($request->fetchAll() as $value) {
            echo "Nom : " . $value['username'] . "<br>";
        }
    }

    // Tous les articles
    $request = $pdo->prepare("
        SELECT * FROM article
            WHERE EXISTS (SELECT * FROM article)
    ");

    if ($request->execute()) {
        foreach ($request->fetchAll() as $value) {
            echo "Titre : " . $value['titre'] . "<br>" . $value['contenu'] . "<br>";
        }
    }

    // Tous les utilisateurs qui parlent de poterie
    $request = $pdo->prepare("
        SELECT username FROM user
            WHERE id = ANY (SELECT user_fk FROM article WHERE contenu LIKE '%poterie%')
    ");

    if ($request->execute()) {
        echo "<pre>";
        print_r($request->fetchAll());
        echo "</pre>";
    }

    // Article par Jane
    $request = $pdo->prepare("
        SELECT contenu FROM article
            WHERE user_fk = 6 OR user_fk = 7
    ");

    if ($request->execute()) {
        echo "<pre>";
        print_r($request->fetchAll());
        echo "</pre>";
    }
}

catch (Exception $exception) {
    echo $exception->getMessage();
}