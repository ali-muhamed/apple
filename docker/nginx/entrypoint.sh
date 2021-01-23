#!/bin/bash
set -e

for f in $(find /etc/nginx/conf.d/ -regex '.*\.\(co*nf\|tplphp\)'); do
    envsubst '$$NGINX_HOST' < $f > "/tmp/$(basename $f)"
    mv "/tmp/$(basename $f)" "/etc/nginx/conf.d/$(basename $f)"
done

exec "$@"