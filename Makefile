.PHONY: all test clean db generate

all: test db

test: test-spec test-integration test-smoke

test-spec:
	./vendor/bin/phpspec run

test-integration:
	./vendor/bin/phpunit

test-smoke:
	./bin/ucd search 2603 > /dev/null
	./bin/ucd properties block Half_Marks > /dev/null
	./bin/ucd properties category Sc > /dev/null
	./bin/ucd properties script Perm > /dev/null

clean:
	rm -f resources/ucd.all.flat.*
	rm -rf resources/generated/ucd
	rm -rf resources/generated/props

db: clean resources/generated/ucd resources/generated/props
	./bin/ucd repository-transfer xml php

resources/generated/props: resources/ucd.all.flat.xml
	mkdir -p $@

resources/generated/ucd: resources/ucd.all.flat.xml
	mkdir -p $@

resources/ucd.all.flat.xml: resources/ucd.all.flat.zip
	unzip -o -d resources/ $<
	touch "$@"

resources/ucd.all.flat.zip:
	wget -P resources/ -q "http://www.unicode.org/Public/UCD/latest/ucdxml/ucd.all.flat.zip"