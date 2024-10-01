<?php

namespace App\Repository;
use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;

class PostRepository {
    public function persist(Post $post) {
        $connection = Database::connect();
        $query = $connection->prepare('INSERT INTO post (content, posted_at,respond_to,author_id) VALUES (:content,:postedAt,:respondTo,:authorId)');

        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':postedAt', $post->getPostedAt()->format('Y-m-d H:i:s'));
        $query->bindValue(':respondTo', null);
        $query->bindValue(':authorId', $post->getAuthor()->getId());
        $query->execute();

        $post->setId($connection->lastInsertId());

    }

    public function findAll(int $offset, int $limit) {
        $connection = Database::connect();
        $query = $connection->prepare("SELECT * FROM post INNER JOIN user ON user.id=post.author_id ORDER BY posted_at DESC LIMIT $offset,$limit ");
        $query->execute();

        $list= [];
        foreach($query->fetchAll() as $line) {
            $user = new User();
            $user->setId($line['author_id']);
            $user->setUsername($line['username']);
            $user->setEmail($line['email']);
            $user->setCreatedAt(new DateTimeImmutable($line['created_at']));
            $user->setRole($line['role']);
            $user->setBanDate($line['ban_date']);
            
            $post = new Post();
            $post->setId($line['id']);
            $post->setPostedAt(new DateTimeImmutable($line['posted_at']));
            $post->setContent($line['content']);
            $post->setAuthor($user);
            // $post->setRespondTo(null)
            
            
            
            $list[] = $post;
        }
        return $list;
    }
}