---
title: Introduction
weight: 1
---

## Introduction
Ready-to-use Marking feature for Laravel, with built-in components for Filament

Integrate likes, bookmarks, favorites, reactions and custom made marks into your application

**[Demo](https://demo.larazeus.com/admin/components-demo/mark) · [Github](https://github.com/lara-zeus/mark) · [Discord](https://discord.com/channels/883083792112300104/1367982388747173888/1367982388747173888)**

## Features

- 🔥 Ready-to-use Database and Application structure.
- 🔥 Ready-to-use Filament form component.
- 🔥 Fully customizable icons per state.
- 🔥 Fully customizable colors per state.

## Screenshots

![](https://larazeus.com/images/screenshots/mark/mark-1.webp)

![](https://larazeus.com/images/screenshots/mark/mark-2.webp)

![](https://larazeus.com/images/screenshots/mark/mark-3.webp)

![](https://larazeus.com/images/screenshots/mark/mark-4.webp)

## Glossary

- **Mark**: The mark itself, e.g., like, bookmark, or rating.
- **Marker**: The entity that created the mark, e.g., a User.
- **Markable**: The entity that can be marked, e.g., a Post or Comment.

## Markable/Marker Trait Reference

| 🔖 Mark     | 🧩 Type     | 🧱 Trait     | ⚙️ Method                   |
|-------------|------------|--------------|----------------------------|
| **Like**        | Markable   | Relations    | `likedBy`                  |
|             |            |              | `likes`                    |
|             |            |              | `like`                     |
|             |            | Actions      | `unmarkLike`               |
|             |            |              | `likeBy`                   |
|             |            |              | `dislikeBy`                |
|             |            | Scopes       | `scopeWhereLikedOrDislikedBy` |
|             |            |              | `scopeWhereLikedBy`        |
|             |            |              | `scopeWhereDislikedBy`     |
|             |            | Indicators   | `isLikedBy`                |
|             |            |              | `isDislikedBy`             |
|             | Marker     | Relations    | `likes`                    |
|             |            | Actions      | `markLike`                 |
|             |            |              | `unmarkLike`               |
|             |            |              | `like`                     |
|             |            |              | `dislike`                  |
|             |            | Scopes       | `scopeWhereLikedOrDisliked` |
|             |            |              | `scopeWhereLiked`          |
|             |            |              | `scopeWhereDisliked`       |
|             |            | Indicators   | `hasLiked`                 |
| **Rating**      | Markable   | Relations    | `ratedBy`                  |
|             |            |              | `ratings`                  |
|             |            |              | `rating`                   |
|             |            | Actions      | `unmarkRating`             |
|             |            |              | `rateBy`                   |
|             |            | Scopes       | `scopeWhereRatedBy`        |
|             |            | Indicators   | `isRatedBy`                |
|             | Marker     | Relations    | `ratings`                  |
|             |            | Actions      | `markRating`               |
|             |            |              | `unmarkRating`             |
|             |            |              | `rate`                     |
|             |            | Scopes       | `scopeWhereRated`          |
|             |            | Indicators   | `hasRated`                 |
| **Bookmark**    | Markable   | Relations    | `bookmarkedBy`             |
|             |            |              | `bookmarks`                |
|             |            |              | `bookmark`                 |
|             |            | Actions      | `unmarkBookmark`           |
|             |            |              | `bookmarkBy`               |
|             |            | Scopes       | `scopeWhereBookmarkedBy`   |
|             |            | Indicators   | `isBookmarkedBy`           |
|             | Marker     | Relations    | `bookmarks`                |
|             |            | Actions      | `markBookmark`             |
|             |            |              | `unmarkBookmark`           |
|             |            |              | `bookmark`                 |
|             |            | Scopes       | `scopeWhereBookmarked`     |
|             |            | Indicators   | `hasBookmarked`            |


## Support

Available support channels:

* Open an issue on [GitHub](https://github.com/lara-zeus/mark/issues)
* Join our channel on [discord channel](https://discord.com/channels/883083792112300104/1367982388747173888/1367982388747173888)
