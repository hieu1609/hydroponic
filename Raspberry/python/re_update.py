import os
import time

while 1:
    os.system("python3 /hydroponic/Raspberry/python/update.py")
    print("Restarting update...")
    time.sleep(3)
