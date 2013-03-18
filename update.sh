#!/bin/sh

echo 'compile lesscss files'
lessc ./public/css/less/bootstrap.less > ./public/css/main.css --yui-compress

echo 'php docs'
phpdoc -d './application' -t './application/docs'

sensible-browser ./application/docs/index.html