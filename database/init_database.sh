#!/bin/bash
rm paguri.db 2>/dev/null
sqlite3 paguri.db < init_db.sql
sqlite3 paguri.db < populate_db.sql