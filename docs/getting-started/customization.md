---
title: Customization
weight: 5
---

## Custom Models

If you want to use a custom pivot model for markable relationships, simply create your own model that uses the Mark trait. Then, register it in your AppServiceProvider:

```PHP
use LaraZeus\Mark\Facades\Mark;

Mark::likeMorphPivotModel(YourOwnLikeMorphPivotClass::class) // [tl! focus]
```