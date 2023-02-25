FROM nginx:alpine
COPY . /app
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/entrypoint.sh /docker-entrypoint.d/40-generate-selfsigned-cert.sh

RUN apk update && \
    apk add openssl && \
    chmod +x /docker-entrypoint.d/40-generate-selfsigned-cert.sh

WORKDIR /app
