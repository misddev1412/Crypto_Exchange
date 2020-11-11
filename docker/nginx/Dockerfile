FROM nginx:latest

# Change Nginx config here...
RUN rm /etc/nginx/conf.d/default.conf
RUN mkdir -p /etc/ssl/vikiexchange
COPY ./default.conf /etc/nginx/conf.d/
COPY ./nginx.crt /etc/ssl/vikiexchange
COPY ./nginx.key /etc/ssl/vikiexchange
