#!/bin/sh
today=testboard_$(date +%Y%m%d)
# -----------------------------
# create a named pipe
##mknod named.pipe p
# -----------------------------
# read the pipe - output to zip file in the background
##gzip < named.pipe > $today.tar.gz &
# -----------------------------
# feed the pipe
find . -name '*.bak' -print | xargs rm -rf
find . -name '*.php' -print | xargs tar cvf $today.tar
#
find ./docs -name '*.*' -print | xargs tar rvf $today.tar
find ./images -name '*.*' -print | xargs tar rvf $today.tar
find ./inc -name '*.*' -print | xargs tar rvf $today.tar
find ./skins -name '*.*' -print | xargs tar rvf $today.tar
#
tar rvf $today.tar `ls index.html`
tar rvf $today.tar `ls *.txt`
tar rvf $today.tar `ls *.sh`
# -----------------------------
# 파이프 삭제하기
##rm -rf named.pipe
# -----------------------------
# 예전데이타 삭제하기
gzip $today.tar
