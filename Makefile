.PHONY: all test clean db

all: test db

test:
	./vendor/bin/phpspec run
	./vendor/bin/phpunit

clean:
	rm -f resources/ucd.all.flat.*
	rm -rf resources/generated/db

db: clean resources/generated/db

resources/generated/db: resources/ucd.all.flat.xml
	mkdir -p $@
	./bin/ucd repository-transfer xml php

resources/ucd.all.flat.xml: resources/ucd.all.flat.zip
	unzip -o -d resources/ $<
	touch "$@"

resources/ucd.all.flat.zip:
	wget -P resources/ -q "http://www.unicode.org/Public/UCD/latest/ucdxml/ucd.all.flat.zip"