#!/usr/bin/env bash

# right now this file only assures that files in the example directory have varying timestamps

CFG_DECREMENT_DAYS=42 # for each file, the modified date will be reduced by this many days

ACCUMULATED_DAYS=0
for file in example/*; do
	touch -d $(date -d "-$ACCUMULATED_DAYS days" +%Y-%m-%d) "$file"
	ACCUMULATED_DAYS=$(expr $ACCUMULATED_DAYS - $CFG_DECREMENT_DAYS)
done