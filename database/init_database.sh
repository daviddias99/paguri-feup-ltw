#!/bin/bash
rm paguri.db
sqlite3 paguri.db < init_db.sql
sqlite3 paguri.db < populate_db.sql

rm ../images/properties/big/*.jpg
rm ../images/properties/small/*.jpg
rm ../images/properties/medium/*.jpg
rm ../images/properties/originals/*.jpg
rm ../images/properties/big/*.png
rm ../images/properties/small/*.png
rm ../images/properties/medium/*.png
rm ../images/properties/originals/*.png