# Dockerfile for swarm-ui

FROM alpine

RUN apk add --update nginx php-json php-gd php-curl php-cli php-fpm curl supervisor bash \
 && rm -rf /var/cache/apk/*
 RUN mkdir -p /tmp/nginx/client-body

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/swarm-ui.conf /etc/nginx/conf.d/swarm-ui.conf
COPY nginx/php-fpm.conf /etc/php/php-fpm.conf
COPY supervisor/supervisord.ini /etc/supervisor.d/supervisord.ini
COPY swarm-ui /opt/swarm-ui

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
