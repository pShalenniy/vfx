FROM php:8.1-fpm-alpine

RUN apk update \
    && apk add --no-cache \
      $PHPIZE_DEPS \
      git \
      less \
      libpng-dev \
      libzip-dev \
      mysql-client \
      nodejs \
      python3 \
      unzip \
      vim \
      yarn \
      zip \
      zlib-dev \
      zsh \
    && apk add --update npm \
    && pecl install redis \
    && docker-php-ext-install bcmath gd pcntl pdo_mysql zip \
    && docker-php-ext-enable redis \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG PHP_USE_XDEBUG
ARG USER_ID=1000
ARG GROUP_ID=1000

ENV USER_ID ${USER_ID}
ENV GROUP_ID ${GROUP_ID}

RUN addgroup --gid ${GROUP_ID} developer \
    && adduser --disabled-password --shell /bin/zsh -h /home/developer -u ${USER_ID} -G developer developer \
    && mkdir /cache \
    && yarn config set cache-folder /cache/.yarn \
    && chown -R ${USER_ID}:${GROUP_ID} /cache

RUN if [ "$PHP_USE_XDEBUG" = "true" ] ; then \
        apk update \
        && apk add --no-cache linux-headers \
        && pecl install xdebug \
        && docker-php-ext-enable xdebug; \
    fi

USER developer

RUN sh -c "$(curl -fsSL https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh)" \
    && git clone https://github.com/zsh-users/zsh-completions ${ZSH_CUSTOM:-${ZSH:-~/.oh-my-zsh}/custom}/plugins/zsh-completions \
    && git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions \
    && git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting \
    && git clone https://github.com/popstas/zsh-command-time.git ~/.oh-my-zsh/custom/plugins/command-time

WORKDIR /app
