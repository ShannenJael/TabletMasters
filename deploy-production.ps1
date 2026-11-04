[CmdletBinding()]
param(
    [string]$RemoteHost = "rzixjmmy@129.121.81.231",
    [string]$RemoteDir = "/home2/rzixjmmy/public_html/website_34e35390",
    # Defaults to the current user's key; if it isn't there, ssh falls back to
    # its own default identities / agent.
    [string]$SshKey = (Join-Path $env:USERPROFILE ".ssh\id_ed25519_antipolo")
)

$ErrorActionPreference = "Stop"

$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $repoRoot

$requiredPaths = @(
    ".gitignore",
    "admin",
    "about.php",
    "accessories.php",
    "assets",
    "business-conferences.php",
    "checkout.php",
    "forum.php",
    "healthcare-hospitals.php",
    "includes",
    "index.php",
    "insurance.php",
    "manifest.json",
    "plans.php",
    "privacy.php",
    "register.php",
    "reviews.php",
    "save-device.php",
    "send-conference-quote.php",
    "send-healthcare-quote.php",
    "send-school-quote.php",
    "public",
    "send-repair.php",
    "shop.php",
    "sms-inbound.php",
    "sms-status.php",
    "sms-terms.php",
    "subscribe.php",
    "schools.php",
    "success.php",
    "support.php",
    "sw.js",
    "tablet-games.php",
    "terms.php",
    "webhook.php",
    "tablet-masters.code-workspace"
)

Write-Host "Deploying Tablet Masters to ${RemoteHost}:${RemoteDir}"

foreach ($path in $requiredPaths) {
    if (-not (Test-Path -LiteralPath $path)) {
        throw "Required path missing: $path"
    }
}

$sshArgs = @()
if ($SshKey -and (Test-Path -LiteralPath $SshKey)) {
    $sshArgs = @("-i", $SshKey)
} else {
    Write-Host "SSH key '$SshKey' not found; using ssh default identities."
}
$scpArgs = $sshArgs + @("-r") + $requiredPaths + @("$RemoteHost`:$RemoteDir/")

Write-Host "Uploading site files..."
& scp @scpArgs
if ($LASTEXITCODE -ne 0) {
    throw "scp failed with exit code $LASTEXITCODE"
}

$remoteScript = @'
set -e
cd "$1"

# Keep web assets publicly readable after deploy.
for dir in admin assets includes public; do
  if [ -d "$dir" ]; then
    find "$dir" -type d -exec chmod 755 {} +
    find "$dir" -type f -exec chmod 644 {} +
  fi
done

for file in .gitignore about.php accessories.php business-conferences.php checkout.php forum.php healthcare-hospitals.php index.php insurance.php manifest.json plans.php privacy.php register.php reviews.php save-device.php send-conference-quote.php send-healthcare-quote.php send-school-quote.php send-repair.php shop.php sms-inbound.php sms-status.php sms-terms.php schools.php subscribe.php success.php support.php sw.js tablet-games.php terms.php webhook.php tablet-masters.code-workspace; do
  if [ -f "$file" ]; then
    chmod 644 "$file"
  fi
done

echo "Verified permissions:"
stat -c "%A %n" admin assets assets/css assets/js assets/images public includes 2>/dev/null || true
'@

Write-Host "Normalizing server permissions..."
$remoteScript = $remoteScript -replace "`r`n", "`n"
# Send the script base64-encoded in the command line instead of piping it over
# stdin: Windows PowerShell prepends a UTF-8 BOM when piping to native programs,
# which broke the remote script's first line (`set -e`).
$encodedScript = [Convert]::ToBase64String((New-Object System.Text.UTF8Encoding $false).GetBytes($remoteScript))
& ssh @sshArgs $RemoteHost "echo $encodedScript | base64 -d | bash -s -- '$RemoteDir'"
if ($LASTEXITCODE -ne 0) {
    throw "ssh permission fix failed with exit code $LASTEXITCODE"
}

Write-Host "Deploy complete."
