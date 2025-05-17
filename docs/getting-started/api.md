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

## Trait Reference

here is a list of all the Markable/Marker Trait API Reference

### Likes

| 🧩 Type  | 🧱 Trait   | ⚙️ Method                     |  
|----------|------------|-------------------------------|
| Markable | Relations  | `likedBy`                     |  
|          |            | `likes`                       |  
|          |            | `like`                        |  
|          | Actions    | `unmarkLike`                  |  
|          |            | `likeBy`                      |  
|          |            | `dislikeBy`                   |  
|          | Scopes     | `scopeWhereLikedOrDislikedBy` |  
|          |            | `scopeWhereLikedBy`           |  
|          |            | `scopeWhereDislikedBy`        |  
|          | Indicators | `isLikedBy`                   |  
|          |            | `isDislikedBy`                |  
| Marker   | Relations  | `likes`                       |  
|          | Actions    | `markLike`                    |  
|          |            | `unmarkLike`                  |  
|          |            | `like`                        |  
|          |            | `dislike`                     |  
|          | Scopes     | `scopeWhereLikedOrDisliked`   |  
|          |            | `scopeWhereLiked`             |  
|          |            | `scopeWhereDisliked`          |  
|          | Indicators | `hasLiked`                    |  

### Bookmarks:

| 🧩 Type  | 🧱 Trait   | ⚙️ Method                |  
|----------|------------|--------------------------|
| Markable | Relations  | `bookmarkedBy`           |  
|          |            | `bookmarks`              |  
|          |            | `bookmark`               |  
|          | Actions    | `unmarkBookmark`         |  
|          |            | `bookmarkBy`             |  
|          | Scopes     | `scopeWhereBookmarkedBy` |  
|          | Indicators | `isBookmarkedBy`         |  
| Marker   | Relations  | `bookmarks`              |  
|          | Actions    | `markBookmark`           |  
|          |            | `unmarkBookmark`         |  
|          |            | `bookmark`               |  
|          | Scopes     | `scopeWhereBookmarked`   |  
|          | Indicators | `hasBookmarked`          |

### Rating:

| 🧩 Type  | 🧱 Trait   | ⚙️ Method           |  
|----------|------------|---------------------|
| Markable | Relations  | `ratedBy`           |  
|          |            | `ratings`           |  
|          |            | `rating`            |  
|          | Actions    | `unmarkRating`      |  
|          |            | `rateBy`            |  
|          | Scopes     | `scopeWhereRatedBy` |  
|          | Indicators | `isRatedBy`         |  
| Marker   | Relations  | `ratings`           |  
|          | Actions    | `markRating`        |  
|          |            | `unmarkRating`      |  
|          |            | `rate`              |  
|          | Scopes     | `scopeWhereRated`   |  
|          | Indicators | `hasRated`          |  
