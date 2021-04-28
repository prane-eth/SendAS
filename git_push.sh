#!/bin/bash
message="$1"

if [ -z "$message" ]
then
	message='Edited files'
fi

git add .
git commit -m "$message"
git push