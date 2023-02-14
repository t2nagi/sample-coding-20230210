FROM python:alpine3.17

RUN apk update && apk upgrade \
    && apk add libmagic

VOLUME [ "/work" ]
WORKDIR /work

CMD ["sh", "startup.sh"]