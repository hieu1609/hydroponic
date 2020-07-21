from smbus2 import SMBus

addr = 7
bus = SMBus(1)

dataSend = "5=ppm=0"
dataSend = dataSend.encode()
bus.write_i2c_block_data(addr, 0, dataSend)
