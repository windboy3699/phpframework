#!/bin/sh

if [ -d "./php" ]
then
    rm -rf ./php
    mkdir ./php
else
    mkdir ./php
fi

thrift -r --gen php:server -out ./php ShopIntf.thrift