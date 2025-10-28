<?php 

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    // Determine if the user can view the post
    public function view(User $user, Post $post)
    {
        // Example logic: a user can view a post if they are the author
        return $user->id === $post->user_id;
    }

    // Determine if the user can create a post
    public function create(User $user)
    {
        // Example logic: only authenticated users can create posts
        return $user->is_active;
    }

    // Determine if the user can update the post
    public function update(User $user, Post $post)
    {
        // Example logic: only the owner of the post can update it
        return $user->id === $post->user_id;
    }

    // Determine if the user can delete the post
    public function delete(User $user, Post $post)
    {
        // Example logic: only the owner or an admin can delete the post
        return $user->id === $post->user_id || $user->is_admin;
    }
}

