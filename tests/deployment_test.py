"""Transactional deployment checks; all files live in temporary fixture homes."""
import importlib.util
import io
import json
import os
from pathlib import Path
import tempfile
import unittest
from unittest.mock import patch

SPEC = importlib.util.spec_from_file_location('release', Path(__file__).parents[1] / 'scripts/deploy-release.py')
release = importlib.util.module_from_spec(SPEC)
SPEC.loader.exec_module(release)


class DeploymentTests(unittest.TestCase):
    def setUp(self):
        self.directory = tempfile.TemporaryDirectory()
        self.home = Path(self.directory.name)
        self.root = self.home / 'public_html/site'
        self.bundle = self.home / 'private/release'
        (self.root / 'includes').mkdir(parents=True)
        (self.root / 'data').mkdir()
        (self.bundle / 'stage/includes').mkdir(parents=True)
        (self.root / 'index.php').write_text('<?php echo "old";')
        self.config = self.root / 'includes/config.php'
        self.config.write_text("<?php define('ADMIN_PASSWORD', 'private-fixture-password');")
        (self.root / 'data/inventory.json').write_text('[{"stock":9}]')
        (self.bundle / 'stage/index.php').write_text('<?php echo "new";')
        (self.bundle / 'stage/includes/new.php').write_text('<?php // new helper\n')
        self.manifest = {'commit': 'fixture', 'files': {
            name: release.digest(self.bundle / 'stage' / name)
            for name in ('index.php', 'includes/new.php')
        }}
        self.write_manifest()

    def tearDown(self):
        self.directory.cleanup()

    def write_manifest(self):
        (self.bundle / 'manifest.json').write_text(json.dumps(self.manifest))

    def apply(self):
        with patch.object(release.Path, 'home', return_value=self.home), \
             patch.object(release, '__file__', str(self.bundle / 'deploy-release.py')), \
             patch.object(release.sys, 'argv', ['deploy-release.py', str(self.root)]), \
             patch('sys.stdout', new_callable=io.StringIO):
            release.main()

    def test_update_preserves_config_and_inventory_and_keeps_backup(self):
        original = self.config.read_bytes()
        self.apply()
        self.assertIn('new', (self.root / 'index.php').read_text())
        self.assertEqual(original, self.config.read_bytes())
        self.assertEqual('[{"stock":9}]', (self.root / 'data/inventory.json').read_text())
        self.assertIn('old', (self.bundle / 'previous/index.php').read_text())
        self.assertFalse((self.bundle / 'admin-password.txt').exists())

    def test_new_password_is_hashed_in_private_config(self):
        self.config.write_text("<?php define('SITE_URL', 'https://example.test'); ?>")
        self.apply()
        password = (self.bundle / 'admin-password.txt').read_text()
        self.assertGreaterEqual(len(password), 24)
        self.assertNotIn(password, self.config.read_text())
        self.assertIn('SITE_URL', self.config.read_text())
        valid = release.php('require $argv[1]; echo password_verify(stream_get_contents(STDIN), TM_ADMIN_PASSWORD_HASH) ? "yes" : "no";', self.config, input=password)
        self.assertEqual('yes', valid)

    def test_checksum_failure_restores_old_files_and_configuration(self):
        original_digest = release.digest
        original = self.config.read_bytes()

        def corrupt_verification(path):
            if path == self.root / 'index.php' and 'new' in path.read_text():
                return 'wrong-checksum'
            return original_digest(path)

        with patch.object(release, 'digest', side_effect=corrupt_verification):
            with self.assertRaisesRegex(RuntimeError, 'Deployed checksum mismatch'):
                self.apply()
        self.assertIn('old', (self.root / 'index.php').read_text())
        self.assertFalse((self.root / 'includes/new.php').exists())
        self.assertEqual(original, self.config.read_bytes())

    def test_parent_directory_path_is_rejected(self):
        self.manifest['files']['../escape.php'] = 'bad'
        self.write_manifest()
        with self.assertRaisesRegex(RuntimeError, 'Unsafe release path'):
            self.apply()
        self.assertIn('old', (self.root / 'index.php').read_text())


if __name__ == '__main__':
    unittest.main()
