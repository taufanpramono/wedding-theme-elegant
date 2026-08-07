# Wedding Elegant Wedding

WordPress wedding invitation theme with a one-page elegant layout, Customizer fields, installable ZIP build script, and GitHub update support.

## Build ZIP

From the WordPress project root:

```powershell
powershell -ExecutionPolicy Bypass -File wp-content/themes/wedding-elegant-wedding/build-theme-zip.ps1
```

Output:

```text
builds/theme-elegant-wedding.zip
```

Upload that ZIP in WordPress via Appearance > Themes > Add New > Upload Theme.

## GitHub Update Flow

Repository:

```text
git@github.com:taufanpramono/wedding-theme-elegant.git
https://github.com/taufanpramono/wedding-theme-elegant.git
```

For a new release:

1. Increase `Version:` in `style.css`, for example from `1.0.0` to `1.0.1`.
2. Commit and push to GitHub.
3. Create a GitHub release tag, for example `v1.0.1`.
4. Run the build script and upload `theme-elegant-wedding.zip` as a release asset.

Installed sites will use WordPress' normal update screen. If there is no GitHub Release, the updater falls back to checking `style.css` on the `main` branch and downloads the branch ZIP. The folder is normalized back to `wedding-elegant-wedding` during update.

For a private repository, define a GitHub token in `wp-config.php`:

```php
define( 'WEW_GITHUB_TOKEN', 'github_pat_xxx' );
```

Public repositories do not need a token.

