---
title: Installation
weight: 2
---

## Installation

Install @zeus mark by running the following commands in your Laravel project directory.

```bash
composer require lara-zeus/mark
```

## Filament Theme
Then, set up a [Filament Custom Theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) and add the following path to the Tailwind configuration:

```js
'./vendor/lara-zeus/mark/resources/**/*.blade.php'
```
