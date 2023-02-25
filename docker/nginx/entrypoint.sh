#!/bin/sh
openssl req -x509 -nodes -days 365 \
    -subj "/C=CA/ST=QC/O=Nubium, Inc./CN=nubium-sandbox.test" \
    -addext "subjectAltName=DNS:nubium-sandbox.test" \
    -newkey rsa:2048 \
    -keyout /etc/ssl/private/selfsigned.key  \
    -out /etc/ssl/certs/selfsigned.crt 2>/var/log/40-generate-selfsigned-cert.log