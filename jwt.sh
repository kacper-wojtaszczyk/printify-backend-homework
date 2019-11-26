#!/usr/bin/env bash

if [ ! -f config/jwt/private.pem ]; then
    mkdir -p config/jwt
    printf "\nJWT keys not exists! Creating...\n\n"

    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pass pass:test -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -passin pass:test -pubout

    printf "\nJWT keys created...Ensure you defined JWT_PASSPHRASE env variable containing PEM pass phrase\n"
fi