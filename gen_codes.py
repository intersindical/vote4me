#!/usr/bin/python

import os
import secrets
import struct
import sys

from Crypto.Cipher import AES
from Crypto import Random

if len(sys.argv) > 3:
    codes = int(sys.argv[1])
else:
    print("Usage: gen_codes numcodes outplainfile.txt outaesfile.bin")
    exit()

num_bytes = 16

print("Generant fitxers", sys.argv[2], "i", sys.argv[3], "...")

with open(sys.argv[2], "w") as f:
    for i in range(0, codes):
        f.write(secrets.token_hex(16))
        f.write("\n");

# Codifiquem el fitxer

rndfile = Random.new()

key = rndfile.read(16)
iv = rndfile.read(16)

aes = AES.new(key, AES.MODE_CBC, iv)
sz = 2048

fsz = os.path.getsize(sys.argv[2])

with open(sys.argv[3], "wb") as fout:
    # Save original file size
    #fout.write(struct.pack('<Q', fsz))
    # Save the initialization vector iv
    #fout.write(iv)
    with open(sys.argv[2], "r") as fin:
        while True:
            data = fin.read(sz)
            n = len(data)
            if n == 0:
                break
            elif n % 16 != 0:
                # Padding with spaces
                data += ' ' * (16 - n % 16)
            encd = aes.encrypt(data.encode("utf8"))
            fout.write(encd)

print("key:", key.hex())
print("iv:", iv.hex())

