---
title: Customization
weight: 5
---

In your `AppServiceProvider`, you can do the following:

```PHP
use App\Models\User;
use LaraZeus\Mark\Facades\Mark;
```

in case you want to have your own pivot model, (must use the Mark trait)

```php
Mark::likeMorphPivotModel(YourOwnLikeMorphPivotClass::class)
```