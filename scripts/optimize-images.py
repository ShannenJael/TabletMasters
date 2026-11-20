"""Create lightweight WebP siblings (photo.png -> photo.png.webp) for large site images.

.htaccess serves the sibling transparently to browsers that accept WebP, so image
paths in pages and data/inventory.json never change. Re-run after adding images:

    python scripts/optimize-images.py
"""
from pathlib import Path
import sys

from PIL import Image

ROOT = Path(__file__).resolve().parent.parent
SOURCES = [ROOT / 'assets' / 'images', ROOT / 'public']
MIN_BYTES = 60 * 1024          # small files are not worth a second copy
MAX_EDGE = {'games': 1400}     # per-subfolder longest edge; default below
DEFAULT_MAX_EDGE = 900
QUALITY = 82


def convert(path):
    target = path.with_name(path.name + '.webp')
    if target.exists() and target.stat().st_mtime >= path.stat().st_mtime:
        return None
    limit = MAX_EDGE.get(path.parent.name, DEFAULT_MAX_EDGE)
    with Image.open(path) as image:
        image.load()
        if image.mode not in ('RGB', 'RGBA'):
            image = image.convert('RGBA' if 'A' in image.getbands() or 'transparency' in image.info else 'RGB')
        image.thumbnail((limit, limit), Image.LANCZOS)
        image.save(target, 'WEBP', quality=QUALITY, method=6)
    before, after = path.stat().st_size, target.stat().st_size
    if after >= before * 0.8:
        target.unlink()
        return None
    return before, after


def main():
    total_before = total_after = 0
    for folder in SOURCES:
        for path in sorted(folder.rglob('*')):
            if path.suffix.lower() not in ('.png', '.jpg', '.jpeg') or path.stat().st_size < MIN_BYTES:
                continue
            result = convert(path)
            if result:
                total_before += result[0]
                total_after += result[1]
                print(f'{result[0] // 1024:>6} KB -> {result[1] // 1024:>4} KB  {path.relative_to(ROOT)}')
    print(f'Total: {total_before // 1024} KB -> {total_after // 1024} KB')


if __name__ == '__main__':
    sys.exit(main())
