#!/bin/bash
msg=${1:-'Edited files'} # default value for commit message

git add .
git commit -m "$msg"
git push