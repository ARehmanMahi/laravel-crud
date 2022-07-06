FROM nginx:stable-alpine

ENV NGINXUSER=www-data
ENV NGINXGROUP=www-data

ADD docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN sed -i "s/user nginx/user ${NGINXUSER}/g" /etc/nginx/nginx.conf

#RUN adduser -g ${NGINXGROUP} -s /bin/sh -D ${NGINXUSER}
RUN adduser -D -H -u 1000 -s /bin/bash www-data -G www-data
