FROM strm/dnsmasq

RUN mkdir -pv /etc/resolver
RUN echo "nameserver 127.0.0.1" > /etc/resolver/localdomain
RUN echo "nameserver 127.0.0.1" > /etc/resolver/test