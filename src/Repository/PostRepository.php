<?php

namespace App\Repository;
use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;

class PostRepository
{
    public function persist(Post $post)
    {
        $connection = Database::connect();
        $query = $connection->prepare('INSERT INTO post (content, posted_at,respond_to,author_id) VALUES (:content,:postedAt,:respondTo,:authorId)');

        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':postedAt', $post->getPostedAt()->format('Y-m-d H:i:s'));
        $query->bindValue(':authorId', $post->getAuthor()->getId());

        if ($post->getRespondTo()) {
            $query->bindValue(':respondTo', $post->getRespondTo()->getId());

        } else {
            $query->bindValue(':respondTo', value: null);

        }
        $query->execute();

        $post->setId($connection->lastInsertId());

    }

    public function findAll(int $offset, int $limit)
    {
        $connection = Database::connect();
        $query = $connection->prepare("SELECT post.*, user.*, post.id post_id, COUNT(response.id) as response_count FROM post LEFT JOIN post AS response ON response.respond_to=post.id INNER JOIN user ON user.id=post.author_id  WHERE post.respond_to IS NULL GROUP BY post.id ORDER BY post.posted_at LIMIT $offset,$limit ");
        $query->execute();

        $list = [];
        foreach ($query->fetchAll() as $line) {
            $user = new User();
            $user->setId($line['author_id']);
            $user->setUsername($line['username']);
            $user->setEmail($line['email']);
            $user->setCreatedAt(new DateTimeImmutable($line['created_at']));
            $user->setRole($line['role']);
            $user->setBanDate($line['ban_date']);

            $post = new Post();
            $post->setId($line['post_id']);
            $post->setPostedAt(new DateTimeImmutable($line['posted_at']));
            $post->setContent($line['content']);
            $post->setAuthor($user);
            $post->setResponseCount($line['response_count']);
            // $post->setRespondTo(null)



            $list[] = $post;
        }
        return $list;
    }
    /**
     * Méthode pour récupérer tous les posts d'un User par son username
     * @param string $username le username de la personne dont on veut récupérer les posts
     * @param int $offset combien on saute de posts
     * @param int $limit combien on récupère de posts
     * @return Post[] Les posts de la personne
     */
    public function findByUsername(string $username, int $offset, int $limit)
    {
        $connection = Database::connect();
        $query = $connection->prepare("SELECT post.*, user.*, post.id post_id, COUNT(response.id) as response_count FROM post LEFT JOIN post AS response ON response.respond_to=post.id INNER JOIN user ON user.id=post.author_id  WHERE user.username=:username AND post.respond_to IS NULL GROUP BY post.id ORDER BY post.posted_at LIMIT $offset,$limit");
        $query->bindValue(':username', $username);
        $query->execute();

        $list = [];
        foreach ($query->fetchAll() as $line) {
            $user = new User();
            $user->setId($line['author_id']);
            $user->setUsername($line['username']);
            $user->setEmail($line['email']);
            $user->setCreatedAt(new DateTimeImmutable($line['created_at']));
            $user->setRole($line['role']);
            $user->setBanDate($line['ban_date']);

            $post = new Post();
            $post->setId($line['post_id']);
            $post->setPostedAt(new DateTimeImmutable($line['posted_at']));
            $post->setContent($line['content']);
            $post->setAuthor($user);
            $post->setResponseCount($line['response_count']);
            // $post->setRespondTo(null)



            $list[] = $post;
        }
        return $list;
    }

    public function findResponses(int $respondTo, int $offset, int $limit)
    {
        $connection = Database::connect();
        $query = $connection->prepare("SELECT post.*, user.*, post.id post_id, COUNT(response.id) as response_count FROM post LEFT JOIN post AS response ON response.respond_to=post.id INNER JOIN user ON user.id=post.author_id  WHERE post.respond_to=:respondTo GROUP BY post.id ORDER BY post.posted_at LIMIT $offset,$limit");
        $query->bindValue(':respondTo', $respondTo);
        $query->execute();

        $list = [];
        foreach ($query->fetchAll() as $line) {
            $user = new User();
            $user->setId($line['author_id']);
            $user->setUsername($line['username']);
            $user->setEmail($line['email']);
            $user->setCreatedAt(new DateTimeImmutable($line['created_at']));
            $user->setRole($line['role']);
            $user->setBanDate($line['ban_date']);

            $post = new Post();
            $post->setId($line['post_id']);
            $post->setPostedAt(new DateTimeImmutable($line['posted_at']));
            $post->setContent($line['content']);
            $post->setAuthor($user);
            $post->setResponseCount($line['response_count']);
            // $post->setRespondTo(null)



            $list[] = $post;
        }
        return $list;
    }
}