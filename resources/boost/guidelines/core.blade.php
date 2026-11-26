# Lara Zeus Mark (`lara-zeus/mark`)

Ready-to-use Laravel marking system (likes, bookmarks, ratings, and custom marks) with Eloquent traits, migrations, and a Filament form field (`LaraZeus\Mark\Forms\Components\Mark`).

## Key Classes & Traits
- **Facade & Manager**: `LaraZeus\Mark\Facades\Mark` (`LaraZeus\Mark\Mark`)
- **Filament Field**: `LaraZeus\Mark\Forms\Components\Mark` (extends `Filament\Forms\Components\Field`)
- **Pivot Models**: `MarkLike`, `MarkBookmark`, `MarkRate` under `LaraZeus\Mark\Models\`
- **Marker (User) Traits**: `LaraZeus\Mark\Traits\Like\HasLikes`, `LaraZeus\Mark\Traits\Bookmark\HasBookmarks`, `LaraZeus\Mark\Traits\Rating\HasRatings`
- **Markable (Target Model) Traits**: `LaraZeus\Mark\Traits\Like\Likeable`, `LaraZeus\Mark\Traits\Bookmark\Bookmarkable`, `LaraZeus\Mark\Traits\Rating\Rateable`

## Rules & Anti-Patterns
- **No Filament Panel Plugin**: Do not register a panel plugin; `Mark` is a standalone form field + Eloquent trait package.
- **Always configure the Marker model**: Call `Mark::markerModel(User::class)` via `LaraZeus\Mark\Facades\Mark` in `AppServiceProvider::boot()` — omitting it throws `RuntimeException('Marker Model is not set.')`.
- **Publish and run migrations**: Publish with `php artisan vendor:publish --tag=zeus-mark-migrations` and keep only the mark tables you need before `php artisan migrate`.
- **Theme CSS**: Add `@source '../../../../vendor/lara-zeus/mark/resources/views/**/*.blade.php';` to your Filament custom theme CSS.
- **Relationships on the Filament field**: Call `->relationship()` (uses `MorphOne`/`MorphMany` on the record) when saving marks for the authenticated `Model` user.
