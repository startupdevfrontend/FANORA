FROM nginx:alpine

# FANORA nginx: baked, no host bind-mounts.
# Docker Desktop's 9p host file-sharing (used by bind mounts) can return
# ENODEV / "No such device" after a restart, breaking the site. Bake the
# static public/ and config to keep this container self-contained, matching
# the app container strategy.
# NOTE: after changing public/ assets or nginx.conf, rebuild:
# docker compose build nginx && docker compose up -d nginx

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY public /var/www/html/public