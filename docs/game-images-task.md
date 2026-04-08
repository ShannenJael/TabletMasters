# Game Images Task — tablet-games.php

Add cover art / key art images to each game card on `tablet-games.php` and optionally improve the hero backdrop.

---

## 1. Download the images

Visit each press kit link below and download one good landscape or square screenshot / key art image per game. Aim for at least **800px wide**. PNG or JPG both work.

| Game | Press Kit / Media Source |
|------|--------------------------|
| Pokemon TCG Pocket | https://press.pokemon.com/en/Pokemon-Trading-Card-Game-Pocket |
| DREDGE | https://www.dredge.game/press-kit |
| Infinity Nikki | https://www.igdb.com/games/infinity-nikki/presskit |
| Prince of Persia: Lost Crown | https://www.igdb.com/games/prince-of-persia-the-lost-crown-complete-edition/presskit |
| Disney Speedstorm | https://disneyspeedstorm.com/media/screenshot |
| Disco Elysium | https://www.igdb.com/games/disco-elysium/presskit |
| Wuthering Waves | https://www.igdb.com/games/wuthering-waves/presskit |
| Chants of Sennaar | https://www.igdb.com/games/chants-of-sennaar/presskit |

> **IGDB tip:** On any IGDB press kit page, scroll to the Screenshots section and right-click → Save Image As. No account needed.

---

## 2. Save the images to the project

Place all downloaded images in:

```
assets/images/games/
```

Name them exactly as follows (lowercase, hyphens, no spaces):

```
assets/images/games/pokemon-tcg-pocket.jpg
assets/images/games/dredge.jpg
assets/images/games/infinity-nikki.jpg
assets/images/games/prince-of-persia-lost-crown.jpg
assets/images/games/disney-speedstorm.jpg
assets/images/games/disco-elysium.jpg
assets/images/games/wuthering-waves.jpg
assets/images/games/chants-of-sennaar.jpg
```

Use `.jpg` or `.png` — just be consistent with whatever you downloaded and update the filenames accordingly.

---

## 3. Add an image to each game card in tablet-games.php

Open `tablet-games.php`. Each game in the `$games` array needs an `'img'` key added. Example for the first entry:

```php
[
  'name' => 'Pokemon TCG Pocket',
  'img'  => 'assets/images/games/pokemon-tcg-pocket.jpg',
  // ... rest of fields unchanged
],
```

Add `'img'` to all 8 entries in the `$games` array, pointing to the correct filename.

---

## 4. Render the image in the card template

In `tablet-games.php`, find the `<article class="tablet-game-card">` block (around line 286). Add an image at the top, right after the opening `<article>` tag and before `<div class="tablet-game-card-top">`:

```php
<article class="tablet-game-card">
  <?php if (!empty($game['img'])): ?>
  <div class="tablet-game-card-img">
    <img src="<?= htmlspecialchars($game['img']) ?>" alt="<?= htmlspecialchars($game['name']) ?>" loading="lazy" />
  </div>
  <?php endif; ?>
  <div class="tablet-game-card-top">
```

---

## 5. Add CSS for the card image

Open `assets/css/style.css` and add these rules after the `.tablet-game-card` block (around line 3550):

```css
.tablet-game-card-img {
  width: 100%;
  aspect-ratio: 16 / 9;
  overflow: hidden;
  border-radius: 6px;
}

.tablet-game-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
```

---

## 6. Optional — improve the hero backdrop

The hero section currently uses tablet hardware images as a background (set in CSS at `.tablet-games-hero-backdrop`). If you want a gaming-in-action lifestyle feel instead of hardware, free options are:

- [Unsplash — tablet gaming](https://unsplash.com/s/photos/tablet-gaming) (free, no attribution required)
- [Pexels — tablet gaming](https://www.pexels.com/search/tablet%20gaming/) (free, no attribution required)

Download a wide landscape image, save it as `assets/images/tablet-gaming-hero.jpg`, then update the CSS in `style.css`:

Find `.tablet-games-hero-backdrop` and replace the background-image URLs for the tablet PNGs with your new image:

```css
.tablet-games-hero-backdrop {
  background-image:
    linear-gradient(90deg, rgba(7,10,16,0.92) 0%, rgba(7,10,16,0.78) 38%, rgba(7,10,16,0.42) 100%),
    url("../images/tablet-gaming-hero.jpg");
  background-size: cover;
  background-position: center;
}
```

---

## Files to touch

| File | What changes |
|------|--------------|
| `assets/images/games/` | New folder — add 8 game images here |
| `tablet-games.php` | Add `'img'` key to each game in `$games`, add `<img>` tag in card template |
| `assets/css/style.css` | Add `.tablet-game-card-img` styles |
| `assets/images/` | Optional: add `tablet-gaming-hero.jpg` for hero backdrop |

---

*Task created April 8, 2026*
