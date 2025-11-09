# Script: clear-windows-temp.ps1
# Execute em PowerShell como Administrador.
# Aviso: alguns arquivos em uso nao serao removidos. Use por sua conta e risco.

function Clear-Folder {
    param([string]$Path)
    if (-not (Test-Path $Path)) { Write-Host "Nao existe: $Path"; return }
    try {
        Get-ChildItem -Path $Path -Force -ErrorAction SilentlyContinue |
            Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
        Write-Host "Limpo: $Path"
    } catch {
        Write-Host "Falha ao limpar (provavelmente arquivos em uso): $Path"
    }
}

# Monta a lista de paths usando += para evitar problemas de sintaxe
$paths = @()
$paths += $env:TEMP
$paths += (Join-Path $env:WINDIR "Temp")
$paths += (Join-Path $env:WINDIR "Prefetch")    # opcional
$paths += (Join-Path $env:LOCALAPPDATA "Temp")

# Inclui Temp de todos os perfis em C:\Users (exceto perfis especiais)
$excludeProfiles = @('All Users','Default','Default User','Public','desktop.ini')
Get-ChildItem 'C:\Users' -Directory -ErrorAction SilentlyContinue |
    Where-Object { $excludeProfiles -notcontains $_.Name } |
    ForEach-Object {
        $userTemp = Join-Path $_.FullName "AppData\Local\Temp"
        if (Test-Path $userTemp) { $paths += $userTemp }
    }

# Remove duplicatas
$paths = $paths | Sort-Object -Unique

Write-Host "Pastas que serao limpas:"
$paths | ForEach-Object { Write-Host " - $_" }

$confirm = Read-Host "Deseja continuar e apagar os arquivos acima? (S/N)"
if ($confirm -notin @('S','s','Y','y')) {
    Write-Host "Operacao cancelada."
    exit
}

# Parar servicos antes de limpar Windows Update cache
$servicesToStop = @('wuauserv','bits')
foreach ($s in $servicesToStop) {
    try { Stop-Service -Name $s -Force -ErrorAction SilentlyContinue; Write-Host "Parado: $s" } catch {}
}

# Limpar as pastas listadas
foreach ($p in $paths) {
    Clear-Folder -Path $p
}

# Limpar cache do Windows Update (conteudo da pasta Download)
$wuCache = Join-Path $env:WINDIR "SoftwareDistribution\Download"
Clear-Folder -Path $wuCache

# Reiniciar servicos parados
foreach ($s in $servicesToStop) {
    try { Start-Service -Name $s -ErrorAction SilentlyContinue; Write-Host "Iniciado: $s" } catch {}
}

# Limpar Lixeira
try {
    Clear-RecycleBin -Force -ErrorAction SilentlyContinue
    Write-Host "Lixeira esvaziada."
} catch {
    Write-Host "Falha ao esvaziar a Lixeira (pode ser permissões)."
}

# Reset do Microsoft Store (opcional)
try {
    Start-Process -FilePath "wsreset.exe" -WindowStyle Hidden -ErrorAction SilentlyContinue
    Write-Host "WSReset iniciado (Microsoft Store)."
} catch {}

Write-Host "Concluido. Reinicie o PC para liberar arquivos que estavam em uso."