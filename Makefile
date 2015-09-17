.PHONY: all test clean db

all: test db

test:
	./vendor/bin/phpspec run
	./vendor/bin/phpunit

clean:
	rm resources/ucd.all.flat.*

db: resources/ucd.all.flat.xml
	./bin/ucd generate-database $<

resources/ucd.all.flat.xml: resources/ucd.all.flat.zip
	unzip -o -d resources/ $<
	touch "$@"

resources/ucd.all.flat.zip:
	wget -P resources/ -q "http://www.unicode.org/Public/UCD/latest/ucdxml/ucd.all.flat.zip"