FROM nginx:latest
ARG CONFIG_VHOST_PATH
# Change Nginx config here...
RUN rm /etc/nginx/conf.d/default.conf
RUN mkdir -p /etc/ssl/vikiexchange
COPY $CONFIG_VHOST_PATH /etc/nginx/conf.d/default.conf

COPY ./ssl/vikione_exchange.crt /etc/ssl/vikiexchange/nginx.crt
COPY ./ssl/vikione_exchange.key /etc/ssl/vikiexchange/nginx.key

COPY ./ssl/vikione_com.crt /etc/ssl/vikione/nginx.crt
COPY ./ssl/vikione_com.key /etc/ssl/vikione/nginx.key

COPY ./ssl/vikioneos_com.crt /etc/ssl/vikioneos/nginx.crt
COPY ./ssl/vikioneos_com.key /etc/ssl/vikioneos/nginx.key
