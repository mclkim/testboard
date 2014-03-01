#!/bin/sh
today=patch_$(date +%Y%m%d)
# -----------------------------
# create a named pipe
##mknod named.pipe p
# -----------------------------
# read the pipe - output to zip file in the background
##gzip < named.pipe > $today.tar.gz &
# -----------------------------
# feed the pipe
find . -name '*.php' -print | xargs tar cvf $today.tar
find . -name '*.inc' -print | xargs tar rvf $today.tar
#
# -----------------------------
# 파이프 삭제하기
##rm -rf named.pipe
# -----------------------------
# 예전데이타 삭제하기
gzip $today.tar
