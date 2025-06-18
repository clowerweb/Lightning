@echo off
REM Generate a private key
docker run --rm -v %cd%/nginx/ssl:/cert alpine/openssl genrsa -out /cert/lightning.local.key 2048

REM Create a self-signed certificate directly (simpler approach)
docker run --rm -v %cd%/nginx/ssl:/cert alpine/openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /cert/lightning.local.key -out /cert/lightning.local.crt -subj "/CN=lightning.local" -addext "subjectAltName=DNS:lightning.local"

echo Certificate has been generated! 
