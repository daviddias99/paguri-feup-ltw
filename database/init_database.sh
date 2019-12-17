#!/bin/bash
rm paguri.db
sqlite3 paguri.db < init_db.sql
sqlite3 paguri.db < populate_db.sql

rm ../images/properties/big/*
rm ../images/properties/small/*
rm ../images/properties/medium/*
rm ../images/properties/originals/*