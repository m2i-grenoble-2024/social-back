<?php


namespace App\Repository;
use App\Entity\User;

class UserRepository {

    public function persist(User $user) {
        $connection = Database::connect();
        $query = $connection->prepare('INSERT INTO user (username,email,password,role,created_at) VALUES (:username,:email,:password,:role,:createdAt)');
        $query->bindValue($user->getUsername(), ':username');
        $query->bindValue($user->getEmail(), ':email');
        $query->bindValue($user->getPassword(), ':password');
        $query->bindValue($user->getRole(), ':role');
        $query->bindValue($user->getCreatedAt()->format('Y-m-d H:i:s'), ':createdAt');

        $query->execute();

        $user->setId($connection->lastInsertId());
    }
    /**
     * 
     * Méthode qui va récupérer un User par son identifiant, celui ci pouvant être l'email ou le username
     * @param string $identifier Soit le username, soit l'email
     * @return ?User Soit un user qui correspond au user ou à l'email, soit null
     */
    public function findByIdentifier(string $identifier): ?User {
        $connection = Database::connect();
        $query = $connection->prepare('SELECT * FROM user WHERE username=:username OR email=:email');
        $query->bindValue(':username', $identifier);
        $query->bindValue(':email', $identifier);
    }
}