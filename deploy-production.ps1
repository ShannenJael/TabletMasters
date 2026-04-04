[CmdletBinding()]
param(
    [string]$RemoteHost = "rzixjmmy@129.121.81.231",
    [string]$RemoteDir = "/home2/rzixjmmy/public_html/website_34e35390",
    [string]$SshKey = "C:\Users\JoshuaDixon\.ssh\id_ed25519_antipolo"
)

$ErrorActionPreference = "Stop"

$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $repoRoot

$requiredPaths = @(
    ".gitignore",
    "about.php",
    "assets",
    "includes",
    "index.php",
    "insurance.php",
    "plans.php",
    "privacy.php",
    "public",
    "send-repair.php",
    "shop.php",
    "support.php",
    "terms.php",
    "tablet-masters.code-workspace"
)

Write-Host "Deploying Tablet Masters to ${RemoteHost}:${RemoteDir}"

foreach ($path in $requiredPaths) {
    if (-not (Test-Path -LiteralPath $path)) {
        throw "Required path missing: $path"
    }
}

$sshArgs = @("-i", $SshKey)
$scpArgs = @("-i", $SshKey, "-r") + $requiredPaths + @("$RemoteHost`:$RemoteDir/")

Write-Host "Uploading site files..."
& scp @scpArgs
if ($LASTEXITCODE -ne 0) {
    throw "scp failed with exit code $LASTEXITCODE"
}

$remoteScript = @'
set -e
cd "$1"

# Keep web assets publicly readable after deploy.
for dir in assets includes public; do
  if [ -d "$dir" ]; then
    find "$dir" -type d -exec chmod 755 {} +
    find "$dir" -type f -exec chmod 644 {} +
  fi
done

for file in .gitignore about.php index.php insurance.php plans.php privacy.php send-repair.php shop.php support.php terms.php tablet-masters.code-workspace; do
  if [ -f "$file" ]; then
    chmod 644 "$file"
  fi
done

echo "Verified permissions:"
stat -c "%A %n" assets assets/css assets/js assets/images public includes 2>/dev/null || true
'@

Write-Host "Normalizing server permissions..."
$remoteScript = $remoteScript -replace "`r`n", "`n"
$remoteScript | & ssh @sshArgs $RemoteHost "bash -s -- '$RemoteDir'"
if ($LASTEXITCODE -ne 0) {
    throw "ssh permission fix failed with exit code $LASTEXITCODE"
}

Write-Host "Deploy complete."
