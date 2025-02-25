FROM alpine:latest

LABEL maintainer="Alvin F"

RUN apk add --no-cache curl

######################
#    Run crond       #
# -f for Foreground  #
######################

CMD ["/usr/sbin/crond", "-f"]
