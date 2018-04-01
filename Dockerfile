FROM php:7.1

RUN mkdir /var/working
RUN apt-get update && apt-get install -y wget
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
&& echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list
RUN apt-get update && apt-get install -y google-chrome-stable git zip unzip curl default-jre
RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN git clone https://github.com/bogdananton/Selenium-Setup.git /var/working/selenium-setup
RUN git clone https://github.com/ionutcodreanu/worldclassbooking /var/working/worldclass
RUN apt install -y zlib1g-dev
RUN docker-php-ext-install zip
RUN cd /var/working/selenium-setup && composer install && php selenium-setup register worldclass 8040 \
&& php selenium-setup start worldclass
RUN cd /var/working/worldclass && composer install
WORKDIR /var/working/worldclass
#CMD /var/working/worldclass/vendor/bin/behat --config /var/working/worldclass/src/Config/behat.yml.example /var/working/worldclass/src/Features