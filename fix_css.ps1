$file = 'assets\css\style.css'
$css = [System.IO.File]::ReadAllText((Resolve-Path $file))

# Normalize CRLF to LF
$css = $css.Replace("`r`n", "`n")

# Replace the accessory-buy-btn block - handle blank line inside
$css = $css -replace '\.accessory-buy-btn \{\n\n  flex: 1;\n  white-space: nowrap;\n\}', ".accessory-buy-btn {`n  flex: 1;`n  min-width: 0;`n  width: auto !important;`n  white-space: nowrap;`n  text-align: center;`n}"

# Also fix accessory-bundle-cta to not use space-between (causes button to shrink)
$css = $css -replace '\.accessory-bundle-cta \{\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n  gap: 12px;\n\}', ".accessory-bundle-cta {`n  display: flex;`n  align-items: center;`n  gap: 12px;`n}"

[System.IO.File]::WriteAllText((Resolve-Path $file), $css)
Write-Host "Done"
