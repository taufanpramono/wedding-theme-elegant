param(
    [string]$OutputPath
)

$ErrorActionPreference = "Stop"

$ThemeRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$ThemeSlug = "wedding-elegant-wedding"

if (-not $OutputPath) {
    $WordPressRoot = Resolve-Path (Join-Path $ThemeRoot "..\..\..")
    $BuildDir = Join-Path $WordPressRoot "builds"
    $OutputPath = Join-Path $BuildDir "theme-elegant-wedding.zip"
}

$OutputPath = $ExecutionContext.SessionState.Path.GetUnresolvedProviderPathFromPSPath($OutputPath)
$OutputDir = Split-Path -Parent $OutputPath
$TempRoot = Join-Path ([System.IO.Path]::GetTempPath()) ("wew-build-" + [System.Guid]::NewGuid().ToString("N"))
$StageTheme = Join-Path $TempRoot $ThemeSlug

New-Item -ItemType Directory -Force $OutputDir | Out-Null
New-Item -ItemType Directory -Force $StageTheme | Out-Null

$ExcludedNames = @(
    ".git",
    ".github",
    "node_modules",
    "vendor",
    "builds",
    ".DS_Store",
    "Thumbs.db"
)

Get-ChildItem -LiteralPath $ThemeRoot -Force | Where-Object {
    $ExcludedNames -notcontains $_.Name -and $_.Name -notlike "*.zip"
} | ForEach-Object {
    Copy-Item -LiteralPath $_.FullName -Destination $StageTheme -Recurse -Force
}

if (Test-Path -LiteralPath $OutputPath) {
    Remove-Item -LiteralPath $OutputPath -Force
}

Compress-Archive -LiteralPath $StageTheme -DestinationPath $OutputPath -Force
Remove-Item -LiteralPath $TempRoot -Recurse -Force

Write-Host "Created $OutputPath"

