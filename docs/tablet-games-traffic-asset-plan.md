# Turn The Page Into A Traffic Asset

Turn `tablet-games.php` into a lightweight editorial system instead of a one-off page.

Start with three practical changes:
1. Move the page content into structured arrays or a JSON/content file.
2. Add a visible monthly refresh workflow.
3. Add stronger commerce hooks tied to each recommendation.

A good implementation in this codebase would look like this:

## 1. Extract the page data

Right now the picks already live in arrays inside `tablet-games.php`. Push that one step further so updates are easy:

- Keep `$games`, `$playStyles`, `$tabletMatches`, and `$sources` in a separate include like `includes/game-page-data.php`
- Add fields like `published_month`, `priority`, `featured`, `status`, and `cta_label`
- That makes monthly edits a content update, not a layout/code edit

Example fields:

```php
[
  'name' => 'Pokemon TCG Pocket',
  'img' => 'assets/images/games/pokemon-tcg-pocket.png',
  'img_fit' => 'contain',
  'featured' => true,
  'published_month' => '2026-04',
  'status' => 'active',
  'tablet_brand' => 'Apple',
  'tablet_category_url' => 'shop.php?brand=Apple',
]
```

## 2. Make featured dynamic

Instead of hardcoding:

```php
$featured = $games[0];
```

pick the featured item from a `featured` flag:

```php
$featured = current(array_filter($games, fn($game) => !empty($game['featured']))) ?: $games[0];
```

That lets you swap the hero game monthly without reordering the full page.

## 3. Add freshness signals

The page should feel current every time someone lands on it.
Add:

- `Last updated` at the top
- `April 2026 picks` style copy in the hero
- A small `New this month` badge on newly added games
- A short editor note near the top explaining what changed this month

That can be as simple as:

```php
$editorNote = 'New this month: added Disco Elysium and refreshed the featured pick.';
```

## 4. Strengthen the commerce path

Every section should push toward a tablet category, not just `read more`.
You already have `shop_href`; expand it:

- Add `Best tablet for this game` CTA under each card
- Add one comparison section like `If you like X game, shop these tablets`
- Add a mid-page CTA after the first 4 cards
- Add internal links to `shop.php`, `reviews.php`, and protection pages

Best pattern:

- editorial recommendation
- proof/source
- matching tablet CTA
- supporting review/shop link

## 5. Add monthly update slots

Build the page around a repeatable workflow:

- Keep 8 core picks
- Replace 1-2 monthly
- Refresh the featured card monthly
- Rotate one `best for` section monthly
- Update source links only when needed

A simple section called `New This Month` above the main grid would help a lot.

## 6. Add SEO depth without bloating the design

This page can rank better if it has a little more search intent coverage.
Add:

- a short FAQ near the bottom
- 2-3 paragraphs answering `What games are best on tablets?`
- a section for `Best iPad games`, `Best Android tablet games`, `Best Surface games`

That gives search engines more context while still fitting the editorial format.

## 7. Add internal linking rules

To make it a traffic asset, connect it to the rest of the site:

- link each game to the right brand/category in `shop.php`
- link buying-intent text to `reviews.php`
- link device protection mentions to `plans.php`
- add a homepage/module link to the games page if it is meant to grow search traffic

## 8. Add basic tracking

If you care whether it works, track:

- card CTA clicks
- featured CTA clicks
- shop clicks by brand
- outbound source clicks

Even lightweight JS event tracking is enough to learn which games actually move people toward purchase pages.

## Recommended implementation order

1. Move the game content into an include/data file.
2. Make the featured card driven by a `featured` flag.
3. Add an editor note and `new this month` badges.
4. Add one stronger mid-page commerce CTA.
5. Add a small FAQ block for SEO/internal linking.

If implemented, `tablet-games.php` becomes a reusable monthly-updated content template rather than a static landing page.
