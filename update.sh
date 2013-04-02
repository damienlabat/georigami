#!/bin/sh

echo 'compile lesscss files'
lessc ./public/css/less/bootstrap.less > ./public/css/main.css --yui-compress

echo 'php docs'
phpdoc -d './application' -t './docs'

#sensible-browser ./docs/index.html

echo 'compress js'
cat ./public/js/3d.js ./public/js/home.js ./public/js/map.js ./public/js/page_bloc.js ./public/js/page_location.js ./public/js/page_map.js ./public/js/page_new.js ./public/js/main.js | yui-compressor -o ./public/js/combined.min.js --type js