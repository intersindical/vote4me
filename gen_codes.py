#!/usr/bin/python

import secrets

num_bytes = 16
codes = 50

f = open("codes.txt", "w")

for i in range(0, codes):
    f.write(secrets.token_hex(16))
    f.write("\n");


