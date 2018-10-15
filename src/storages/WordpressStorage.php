<?php

namespace app\storages;

use WP_Query;
use app\core\CommentsCollection;

class WordpressStorage extends BaseStorage
{
    /**
     * @var string
     */
    public $postType;

    /**
     * @var string
     */
    public $wpPath;

    private function initWpEnv()
    {
        $_SERVER = [
            'HTTP_HOST' => 'somedomain.com',
            'DOCUMENT_ROOT' => $this->wpPath,
        ];
        require $this->wpPath . '/wp-load.php';

        // Prevents error in qtranslatex plugin on insert post
        remove_filter('posts_where_request', 'qtranxf_excludeUntranslatedPosts');
        remove_filter('the_posts', 'qtranxf_postsFilter', 5);
    }

    private function isPostExists(string $id): bool
    {
        $query = new WP_Query([
            'post_type' => $this->postType,
            'name' => $id,
        ]);
        return (bool)$query->have_posts();
    }

    public function save(CommentsCollection $comments): int
    {
        if (!function_exists('wp_insert_post')) {
            $this->initWpEnv();
        }
        $addedCommentsCount = 0;
        foreach ($comments as $comment) {
            if (!$this->isPostExists($comment->id)) {
                $postId = wp_insert_post([
                    'post_type' => $this->postType,
                    'post_name' => $comment->id,
                    'post_title' => $comment->userName,
                    'post_content' => nl2br($comment->text),
                    'post_date' => $comment->datetime->format('Y-m-d'),
                    'post_status' => 'publish',
                ]);
                add_post_meta($postId, 'source', $comment->sourceTitle, true);
                add_post_meta($postId, 'review-text', $comment->text, true);
                add_post_meta($postId, 'rating', $comment->getNormalizedRating(10), true);
                $addedCommentsCount++;
            }
        }
        return $addedCommentsCount;
    }
}
