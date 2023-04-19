FROM ubuntu:22.04

WORKDIR /var/www

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC+1

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates libcap2-bin libpng-dev nginx ufw zip \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /usr/share/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.2-cli php8.2-dev php8.2-fpm \
       php8.2-mysql php8.2-gd \
       php8.2-curl \
       php8.2-imap php8.2-mbstring \
       php8.2-intl php8.2-readline \
       php8.2-msgpack php8.2-igbinary \
       php8.2-pcov php8.2-xdebug \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get update \
    && apt-get install -y mysql-client \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./nginx /etc/nginx
RUN ufw allow 'Nginx HTTP'

COPY init.sh /usr/local/bin/init.sh
COPY ./php /etc/php
RUN useradd -ms /bin/bash -u 1000 developer

ENTRYPOINT ["init.sh"]
