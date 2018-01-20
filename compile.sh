#!/bin/bash

cd $1
pass=$(cat $3)

expect << EOF
  spawn git commit -am "$2"
  expect "Enter passphrase"
  send "$pass\r"
  expect eof
EOF