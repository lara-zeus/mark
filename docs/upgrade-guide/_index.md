---
title: Upgrade guide for v2.0
weight: 1
---


# Migrations

## Bookmark

bookmark migration no more has a value column, its existence is enough to indicate the bookmarking state.

- so you need to drop the column using migration with the following content (copy and paste)

```php
Schema::table((new (Mark::getBookmarkMorphPivotModel()))->getTable(), function (Blueprint $table) {
            $table->dropColumn('value');
        });
```

- also remove value data with any eloquent (update, create, attach, and select) operations.
