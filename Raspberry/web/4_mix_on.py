from smbus2 import SMBus

addr = 7
bus = SMBus(1)

dataSend = "4=mix=1"
dataSend = dataSend.encode()
bus.write_i2c_block_data(addr, 0, dataSend)
