FROM php:7.1

RUN mkdir /var/working
RUN apt-get update && apt-get install -y wget
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
&& echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list

RUN apt-get update && apt-get install -y google-chrome-stable git zip unzip curl default-jre zlib1g-dev net-tools vim xvfb
RUN \
    echo "deb http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee /etc/apt/sources.list.d/webupd8team-java.list  && \
    echo "deb-src http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee -a /etc/apt/sources.list.d/webupd8team-java.list  && \
    apt-key adv --keyserver keyserver.ubuntu.com --recv-keys EEA14886  && \
    apt-get update  && \
    \
    \
    echo debconf shared/accepted-oracle-license-v1-1 select true | debconf-set-selections  && \
    echo debconf shared/accepted-oracle-license-v1-1 seen true | debconf-set-selections  && \
    DEBIAN_FRONTEND=noninteractive  apt-get install -y --force-yes oracle-java8-installer oracle-java8-set-default  && \
    \
    \
    rm -rf /var/cache/oracle-jdk8-installer  && \
    apt-get clean
ENV JAVA_HOME /usr/lib/jvm/java-8-oracle

RUN docker-php-ext-install zip
RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN git clone https://github.com/ionutcodreanu/worldclassbooking /var/working/worldclass
RUN git clone https://github.com/ionutcodreanu/Selenium-Setup.git /var/working/selenium-setup
RUN cd /var/working/selenium-setup && composer install && php selenium-setup register worldclass 8040 \
&& php selenium-setup start worldclass
#COPY . /var/working/worldclass
RUN cd /var/working/worldclass && composer install
WORKDIR /var/working/worldclass
CMD /var/working/worldclass/run.sh
#CMD /var/working/worldclass/vendor/bin/behat --config /var/working/worldclass/src/Config/behat.yml.example /var/working/worldclass/src/Features