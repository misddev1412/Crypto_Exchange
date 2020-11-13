FROM nginx:latest
ARG CONFIG_VHOST_PATH
# Change Nginx config here...
RUN rm /etc/nginx/conf.d/default.conf
RUN mkdir -p /etc/ssl/vikiexchange
COPY $CONFIG_VHOST_PATH /etc/nginx/conf.d/default.conf
COPY ./nginx.crt /etc/ssl/vikiexchange
COPY ./nginx.key /etc/ssl/vikiexchange
COPY ./vikione_com.crt /etc/ssl/vikione/nginx.crt
COPY ./vikione_com.key /etc/ssl/vikione/nginx.key
