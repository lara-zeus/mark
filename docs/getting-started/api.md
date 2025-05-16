---
title: Api Ref
weight: 4
---

# Usage With Laravel

Check each Marker and markables traits for full list of available usages, the following is a sample:

## Marker

to get Markables related to the marker, (User and posts), use these methods

Note: metadata parameter is optional

```php
$post = Post::first();

auth()->user()->like($post, metadata: []);
auth()->user()->dislike($post, metadata: []);
auth()->user()->hasLiked($post);
auth()->user()->markLike($post, value: true, metadata: []);
auth()->user()->unmarkLike($post);
```

## Markable:

to get related to the post

```PHP
$user = auth()->user();
$post->likeBy($user, metadata: []);
$post->dislikeBy($user, metadata: []);
$post->isLikedBy($user);
$post->markLike($user, value: true, metadata: []);
$post->unmarkLike($user);
```