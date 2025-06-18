# PowerShell script to create self-signed certificate for lightning.local
$ErrorActionPreference = "Stop"

# Certificate details
$domain = "lightning.local"
$certPath = ".\lightning.local.crt"
$keyPath = ".\lightning.local.key"

# Create a self-signed certificate
$cert = New-SelfSignedCertificate -DnsName $domain -CertStoreLocation "Cert:\CurrentUser\My" -KeyAlgorithm RSA -KeyLength 2048 -NotAfter (Get-Date).AddYears(1)

# Export certificate to PFX format (including private key)
$pfxPassword = ConvertTo-SecureString -String "password" -Force -AsPlainText
$pfxPath = ".\lightning.local.pfx"
Export-PfxCertificate -Cert $cert -FilePath $pfxPath -Password $pfxPassword

# Extract private key from PFX to PEM (key file)
$p = Start-Process -FilePath "openssl" -ArgumentList "pkcs12 -in $pfxPath -nocerts -nodes -out $keyPath -password pass:password" -NoNewWindow -Wait -PassThru
if ($p.ExitCode -ne 0) {
    Write-Host "Failed to extract private key. Make sure OpenSSL is installed or in PATH."
}

# Extract certificate from PFX to PEM (crt file)
$p = Start-Process -FilePath "openssl" -ArgumentList "pkcs12 -in $pfxPath -nokeys -nodes -out $certPath -password pass:password" -NoNewWindow -Wait -PassThru
if ($p.ExitCode -ne 0) {
    Write-Host "Failed to extract certificate. Make sure OpenSSL is installed or in PATH."
}

Write-Host "Certificate and key files have been generated at: $certPath and $keyPath"
Write-Host "You may need to manually install the certificate in your browser to trust it." 
