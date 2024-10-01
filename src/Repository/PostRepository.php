<?php

namespace App\Repository;
use App\Entity\Post;

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
}