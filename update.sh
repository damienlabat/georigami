#!/bin/sh

echo 'compile lesscss files'
lessc ./public/css/less/bootstrap.less > ./public/css/main.css --yui-compress