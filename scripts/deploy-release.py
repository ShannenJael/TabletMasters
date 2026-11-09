"""Apply a verified release, preserving production configuration and data."""
import hashlib
import json
import os
from pathlib import Path
import secrets
import shutil
import subprocess
import sys


def digest(path):
    return hashlib.sha256(path.read_bytes()).hexdigest()


def php(code, *arguments, **options):
    return subprocess.run(['php', '-r', code, *map(str, arguments)], check=True,
                          capture_output=True, text=True, **options).stdout


def main():
    root = Path(sys.argv[1]).resolve(strict=True)
    release = Path(__file__).resolve().parent
    public = (Path.home() / 'public_html').resolve(strict=True)
    if public not in root.parents:
        raise RuntimeError('Target must be inside this account public_html directory')
    manifest = json.loads((release / 'manifest.json').read_text())
    stage = release / 'stage'
    previous = release / 'previous'
    previous.mkdir(mode=0o700, exist_ok=True)
    changed, missing = [], []
    for name, expected in manifest['files'].items():
        relative = Path(name)
        if relative.is_absolute() or '..' in relative.parts or relative.parts[0] in ('data', '.git', 'ssh-keys') or name.startswith('includes/config'):
            raise RuntimeError('Unsafe release path: ' + name)
        source = (stage / relative).resolve(strict=True)
        target = root / relative
        if stage.resolve() not in source.parents or root not in target.resolve().parents:
            raise RuntimeError('Path escapes deployment directory: ' + name)
        if digest(source) != expected:
            raise RuntimeError('Release checksum mismatch: ' + name)
        if source.suffix == '.php':
            subprocess.run(['php', '-l', str(source)], check=True, capture_output=True)
        if target.is_file() and digest(target) == expected:
            continue
        changed.append(name)
        if target.exists():
            backup = previous / relative
            backup.parent.mkdir(parents=True, exist_ok=True)
            shutil.copy2(target, backup)
        else:
            missing.append(name)

    config = root / 'includes/config.php'
    if not config.is_file():
        raise RuntimeError('Production config is required; it will not be replaced')
    shutil.copy2(config, release / 'config-before.php')
    os.chmod(release / 'config-before.php', 0o600)
    admin = php('require $argv[1]; echo json_encode((defined("TM_ADMIN_PASSWORD_HASH") && TM_ADMIN_PASSWORD_HASH !== "") || (defined("ADMIN_PASSWORD") && ADMIN_PASSWORD !== "") || getenv("TM_ADMIN_PASSWORD_HASH") || getenv("TM_ADMIN_PASSWORD"));', config)
    created_password = admin.strip() != 'true'
    applied = []
    try:
        for name in sorted(changed, key=lambda name: (not name.endswith('.htaccess'), name)):
            target = root / name
            target.parent.mkdir(parents=True, exist_ok=True)
            temporary = target.with_name('.' + target.name + '.release-tmp')
            shutil.copyfile(stage / name, temporary)
            os.chmod(temporary, 0o644)
            os.replace(temporary, target)
            applied.append(name)
        if created_password:
            password = secrets.token_urlsafe(24)
            password_hash = php('echo password_hash(stream_get_contents(STDIN), PASSWORD_DEFAULT);', input=password)
            environment = dict(os.environ, TM_DEPLOY_ADMIN_HASH=password_hash)
            php(r'''$file=$argv[1]; $source=file_get_contents($file); $inside=false;
foreach(token_get_all($source) as $token) { if(is_array($token)) {
if($token[0]===T_OPEN_TAG || $token[0]===T_OPEN_TAG_WITH_ECHO) $inside=true;
if($token[0]===T_CLOSE_TAG) $inside=false; }}
$source .= ($inside ? "\n" : "\n<?php\n") . "define('TM_ADMIN_PASSWORD_HASH', " . var_export(getenv('TM_DEPLOY_ADMIN_HASH'),true) . ");\n";
$temp=$file.'.release-tmp'; file_put_contents($temp,$source); chmod($temp,0600); rename($temp,$file);''', config, env=environment)
            (release / 'admin-password.txt').write_text(password)
            os.chmod(release / 'admin-password.txt', 0o600)
        subprocess.run(['php', '-l', str(config)], check=True, capture_output=True)
        for name, expected in manifest['files'].items():
            if digest(root / name) != expected:
                raise RuntimeError('Deployed checksum mismatch: ' + name)
    except Exception:
        shutil.copy2(release / 'config-before.php', config)
        for name in reversed(applied):
            if name in missing:
                (root / name).unlink(missing_ok=True)
            else:
                shutil.copy2(previous / name, root / name)
        raise
    result = {'commit': manifest['commit'], 'changed_files': changed,
              'admin_credentials_created': created_password, 'backup': str(release)}
    (release / 'result.json').write_text(json.dumps(result, indent=2))
    print(json.dumps(result))


if __name__ == '__main__':
    main()
