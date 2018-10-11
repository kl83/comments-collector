<?php

namespace app\core;

use Countable;
use Iterator;

class CommentsCollection implements Countable, Iterator
{
    /**
     * @var Comment[]
     */
    private $comments;

    /**
     * @param Comment[] $comments
     */
    public function __construct(array $comments = [])
    {
        $this->comments = $comments;
    }

    public function count()
    {
        return count($this->comments);
    }

    public function current()
    {
        return current($this->comments);
    }

    public function next()
    {
        next($this->comments);
    }

    public function key()
    {
        return key($this->comments);
    }

    public function valid()
    {
        return key($this->comments) !== null;
    }

    public function rewind()
    {
        reset($this->comments);
    }

    public function add(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    public function getValid(): CommentsCollection
    {
        return new self(array_filter($this->comments, function (Comment $comment) {
            return $comment->validate();
        }));
    }
}
