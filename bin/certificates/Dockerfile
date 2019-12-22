FROM alpine

RUN apk update && \
  apk add --no-cache openssl && \
  rm -rf /var/cache/apk/*

RUN mkdir -p /home/certs
RUN mkdir -p /home/ssl

RUN openssl dhparam -out /home/certs/dhparam.pem 2048

ARG country
ARG state
ARG city
ARG org_name
ARG common_name
ARG crt_name
ARG crt_key_name

RUN openssl req -new -newkey rsa:2048 -days 365 -nodes -x509 \
    -subj "/C=$country/ST=$state/L=$city/O=$org_name/CN=$common_name" \
    -keyout /home/certs/$crt_key_name  -out /home/certs/$crt_name
