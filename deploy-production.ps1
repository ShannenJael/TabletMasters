[CmdletBinding()]
param(
    [string]$RemoteHost = 'rzixjmmy@129.121.81.231',
    [string]$RemoteDir = '/home2/rzixjmmy/public_html/website_34e35390',
    [string]$SshKey = (Join-Path $env:USERPROFILE 'Downloads\id_ed25519_antipolo')
)
$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $repoRoot
if (!(Test-Path -LiteralPath $SshKey)) { throw 'The supplied SSH key does not exist.' }
if ($RemoteDir -notmatch '^/[A-Za-z0-9_./-]+$' -or $RemoteHost -notmatch '^[A-Za-z0-9_.@-]+$') { throw 'Invalid remote target.' }
$releaseId = (Get-Date).ToUniversalTime().ToString('yyyyMMddTHHmmssZ') + '-' + [Guid]::NewGuid().ToString('N').Substring(0,8)
$artifactDir = Join-Path $repoRoot "test-results\deployment-$releaseId"
New-Item -ItemType Directory -Path $artifactDir -Force | Out-Null
$commit = (& git rev-parse HEAD).Trim()
if ($LASTEXITCODE -ne 0) { throw 'Cannot determine release commit.' }
$tracked = @(& git -c core.quotepath=false ls-files)
$files = @($tracked | Where-Object {
    (($_ -match '^[^/]+\.php$' -and $_ -notmatch '\.server\.php$') -or
     $_ -match '^(admin|includes|assets|public)/' -or $_ -in @('.htaccess','manifest.json','sw.js')) -and
    $_ -notmatch '^includes/config' -and $_ -notmatch '\.(bak|orig|rej|tmp)([.-]|$)'
})
$hashes = [ordered]@{}
foreach ($file in $files) { $hashes[$file] = (Get-FileHash -LiteralPath (Join-Path $repoRoot $file) -Algorithm SHA256).Hash.ToLowerInvariant() }
$utf8 = New-Object System.Text.UTF8Encoding $false
[System.IO.File]::WriteAllText((Join-Path $artifactDir 'manifest.json'), (@{commit=$commit; files=$hashes} | ConvertTo-Json -Depth 4), $utf8)
[System.IO.File]::WriteAllText((Join-Path $artifactDir 'files.txt'), (($files -join "`n") + "`n"), $utf8)
& tar -czf (Join-Path $artifactDir 'release.tar.gz') -T (Join-Path $artifactDir 'files.txt')
if ($LASTEXITCODE -ne 0) { throw 'Release archive creation failed.' }
$sshArgs = @('-i',$SshKey,'-o','BatchMode=yes','-o','ConnectTimeout=20','-o','StrictHostKeyChecking=yes')
$remoteRelease = ".tabletmasters-deployments/$releaseId"
& ssh @sshArgs $RemoteHost "umask 077; mkdir -p '$remoteRelease/stage'"
if ($LASTEXITCODE -ne 0) { throw 'Cannot create private deployment directory.' }
& scp @sshArgs (Join-Path $artifactDir 'release.tar.gz') (Join-Path $artifactDir 'manifest.json') (Join-Path $repoRoot 'scripts\deploy-release.py') "${RemoteHost}:$remoteRelease/"
if ($LASTEXITCODE -ne 0) { throw 'Release upload failed.' }
$resultLines = & ssh @sshArgs $RemoteHost "set -e; cd '$remoteRelease'; tar -xzf release.tar.gz -C stage; python3 deploy-release.py '$RemoteDir'"
if ($LASTEXITCODE -ne 0) { throw 'Release validation/application failed; previous files were restored when applicable.' }
$resultText = $resultLines -join "`n"
[System.IO.File]::WriteAllText((Join-Path $artifactDir 'result.json'), $resultText, $utf8)
$result = $resultText | ConvertFrom-Json
if ($result.admin_credentials_created) {
    & scp @sshArgs "${RemoteHost}:$remoteRelease/admin-password.txt" (Join-Path $artifactDir 'admin-password.txt')
    if ($LASTEXITCODE -ne 0) { throw 'Release applied, but the new admin credential could not be downloaded.' }
    Write-Host "New private admin password saved to $artifactDir\admin-password.txt"
}
Write-Host "Deployed commit $commit. Changed files: $($result.changed_files.Count)."
Write-Host "Private server backup: $($result.backup)"
Write-Host "Deployment record: $artifactDir\result.json"