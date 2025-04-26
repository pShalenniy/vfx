FROM node:18-alpine
LABEL company="amgrade" type="laravel" project="vfx" image="scraping"

RUN --mount=id=cache-apk,type=cache,target=/var/cache/apk \
    set -ex; \
    apk update \
    && apk add --no-cache \
      ca-certificates \
      chromium \
      freetype \
      harfbuzz \
      nss \
      ttf-freefont \
      udev

WORKDIR /app

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD true

COPY ["./scraping/yarn.lock", "./scraping/package.json", "./"]
RUN mkdir -p /cache/.yarn && yarn config set cache-folder /cache/.yarn
RUN --mount=id=cache-yarn,type=cache,target=/cache/.yarn yarn install
COPY ./scraping ./

EXPOSE 8080
CMD ["node", "index.js"]
