FROM mysql:8
COPY *.cnf /etc/mysql/conf.d/my.cnf

RUN echo "default-authentication-plugin=mysql_native_password" >> /etc/mysql/my.cnf
