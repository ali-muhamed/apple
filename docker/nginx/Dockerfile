ARG NGINX_TAG_VERSION=${NGINX_TAG_VERSION}
FROM nginx:${NGINX_TAG_VERSION}

ENV NGINX_HOST ${NGINX_DOMAIN_NAME}

COPY ./config/conf.d/ /etc/nginx/conf.d/
COPY entrypoint.sh /bin/entrypoint.sh

WORKDIR /etc/nginx

ENTRYPOINT ["sh", "/bin/entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]
