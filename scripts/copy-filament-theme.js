import fs from 'node:fs';
import path from 'node:path';

const manifestPath = path.resolve('public/build/manifest.json');
const destDir = path.resolve('public/css/filament/admin');
const destFile = path.join(destDir, 'theme.css');

if (fs.existsSync(manifestPath)) {
    const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
    const entry = manifest['resources/css/filament/admin/theme.css'];
    if (entry && entry.file) {
        const srcFile = path.resolve('public/build', entry.file);
        if (fs.existsSync(srcFile)) {
            if (!fs.existsSync(destDir)) {
                fs.mkdirSync(destDir, { recursive: true });
            }
            fs.copyFileSync(srcFile, destFile);
            console.log(`[copy-filament-theme] Synced ${entry.file} -> public/css/filament/admin/theme.css`);
        }
    }
}
