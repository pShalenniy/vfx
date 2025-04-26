FROM node:18-alpine

RUN set -ex; \
    apk update \
    && apk add --no-cache chromium

WORKDIR /app

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD true

COPY ["./scraping/yarn.lock", "./scraping/package.json", "./"]
RUN yarn install
COPY ./scraping ./

EXPOSE 8080
CMD ["node", "index.js"]
