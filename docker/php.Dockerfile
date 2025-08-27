FROM registry.gitlab.com/6go/dx/docker/composer:latest AS composer

ENV COMPOSER_CACHE_DIR=/tmp/cache

COPY composer.json composer.lock ./

RUN --mount=type=cache,target=/tmp/cache \
    composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --no-autoloader \
    --prefer-dist

FROM registry.gitlab.com/6go/dx/docker/php:8.4

ARG UID=1001
ARG PRIMARY_GID=1001
ARG SECONDARY_GID=999
ARG USER=laravel

ENV OPCACHE_JIT_FLAGS=1225
ENV OPCACHE_ENABLE=1

WORKDIR /var/www

RUN --mount=type=cache,target=/var/cache/apk apk add --no-cache zip unzip && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    addgroup -g $PRIMARY_GID $USER && \
    addgroup -g $SECONDARY_GID caddy && \
    adduser -D -u $UID -G $USER $USER && \
    adduser $USER caddy && \
    rm -rf /tmp/* /var/tmp/* /var/cache/apk/*

COPY --from=composer --chown=${USER}:${USER} --link /app/vendor ./vendor
COPY --chown=${USER}:${USER} --link bin/docker/queue.sh ./bin/docker/queue.sh
COPY --chown=${USER}:${USER} --link bin/docker/scheduler.sh ./bin/docker/scheduler.sh
COPY --chown=${USER}:${USER} --link app ./app
COPY --chown=${USER}:${USER} --link artisan composer.json composer.lock ./
COPY --chown=${USER}:${USER} --link bootstrap ./bootstrap
COPY --chown=${USER}:${USER} --link config ./config
COPY --chown=${USER}:${USER} --link database ./database
COPY --chown=${USER}:${USER} --link public ./public
COPY --chown=${USER}:${USER} --link resources ./resources
COPY --chown=${USER}:${USER} --link routes ./routes
COPY --chown=${USER}:${USER} --link storage ./storage
COPY --chown=${USER}:${USER} --link envs/.env.prod .env

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative && \
    chmod -R g+w ./storage ./bootstrap/cache && \
    chmod 775 -R ./storage/framework/cache && \
    php artisan storage:link && \
    rm -rf /root/.composer /tmp/* /var/tmp/*

USER ${USER}
