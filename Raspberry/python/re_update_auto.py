import os
import time

while 1:
    os.system("python3 /hydroponic/Raspberry/python/update_auto.py")
    print("Restarting update auto...")
    time.sleep(3)
