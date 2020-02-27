FROM php:7.2-fpm

LABEL Name=datagen Version=0.0.1
RUN apt-get -y update && apt-get install -y fortunes
CMD /usr/games/fortune -a | cowsay
