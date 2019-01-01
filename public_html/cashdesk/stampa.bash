#!/bin/bash
# Script eseguito come root
(
flock -x -w 10 200
opt[1]=$1
opt[2]=$2
opt[3]=$3
opt[4]=$4
opt[5]=$5
opt[6]=$6
opt[7]=$7
opt[8]=$8
opt[9]=$9
opt[10]=$10
python $5 "${opt[@]}"
wait
) 200>/var/lock/.myscript.exclusivelock
