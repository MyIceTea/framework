#!/bin/bash

cd $1
pass=$(cat $3)

expect << EOF
  spawn git commit -am "$2"
  send "$pass\r"
EOF