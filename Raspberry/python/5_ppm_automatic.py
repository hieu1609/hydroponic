import time
from mysql.connector import MySQLConnection, Error
import mysql.connector
from smbus2 import SMBus

addr = 5
bus = SMBus(1)


def update_autostatus(device_id, status):
    # prepare query and data
    query = """ UPDATE ppm_automatic
                SET auto_status = %s
                WHERE device_id = %s """
    data = (status, device_id)
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='raspberry',
                                       user='admin',
                                       password='admin')
        # update book title
        cursor = conn.cursor()
        cursor.execute(query, data)

        # accept the changes
        conn.commit()

    except Error as error:
        print(error)

    finally:
        cursor.close()
        conn.close()


try:
    count = 0
    while True:
        device = "5"
        deviceid = 5
        connection = mysql.connector.connect(host='localhost',
                                             database='raspberry',
                                             user='admin',
                                             password='admin')
        sql_select_Query = "select * from ppm_automatic where device_id=" + device
        sql_select_Query2 = "select * from sensors where device_id=" + device
        sql_select_Query3 = "select * from nutrients where id=" + device
        cursor = connection.cursor(dictionary=True)
        cursor.execute(sql_select_Query)
        ppm = cursor.fetchone()
        cursor2 = connection.cursor(dictionary=True)
        cursor2.execute(sql_select_Query2)
        ppmNow = cursor2.fetchone()
        # print(records)
        if ppm["auto_mode"] == 1:
            count = 0
            print("auto ppm on")
            cursor3 = connection.cursor(dictionary=True)
            cursor3.execute(sql_select_Query3)
            temp = cursor3.fetchone()
            case = 0
            checkWaterAfterMix = (
                ppmNow["PPM"]*(ppmNow["water"]+25) + 70*(80-ppmNow["water"]))/80
            ppmDifferent = checkWaterAfterMix - ppmForDevice
            if ppmNow["temperature"] <= 25:
                ppmForDevice = temp["ppm_max"]
            elif ppmNow["temperature"] >= 45:
                ppmForDevice = temp["ppm_min"]
            else:
                ppmForDevice = temp["ppm_max"] - ((ppmNow["temperature"] - 25)*0.05)*(
                    temp["ppm_max"]-temp["ppm_min"])
            print(ppmForDevice)
            if(abs(ppmForDevice-ppmNow["PPM"])) <= 100:
                if ppmNow["water"] <= 20:
                    if case != 1:
                        if case == 2 or case == 0:
                            update_autostatus(deviceid, 1)
                            print("auto_status =1")
                        print("ppm=0")
                        dataSend = device + "=ppm=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterIn=1")
                        dataSend = device + "=waterIn=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterOut=0")
                        dataSend = device + "=waterOut=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("mix=1")
                        dataSend = device + "=mix=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(10)
                        print("waterIn=1")
                        dataSend = device + "=waterIn=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(5)
                        case = 1
                        print(case)
                    else:
                        time.sleep(5)
                else:
                    if case != 2:
                        print("waterIn=0")
                        dataSend = device + "=waterIn=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterOut=0")
                        dataSend = device + "=waterOut=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("mix=0")
                        dataSend = device + "=mix=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(30)
                        case = 2
                        print(case)
                    else:
                        time.sleep(30)
            elif (ppmForDevice-ppmNow["PPM"] > 100):
                if case == 2 and ppmNow["PPM"] >= temp["ppm_min"] and ppmNow["PPM"] <= temp["ppm_max"]:
                    time.sleep(30)
                else:
                    if ppmNow["water"] < 50:
                        if case != 3:
                            if case == 2 or case == 0:
                                print("autostatus=1")
                                update_autostatus(deviceid, 1)
                                print("mix=1")
                                dataSend = device + "=mix=1"
                                dataSend = dataSend.encode()
                                bus.write_i2c_block_data(addr, 0, dataSend)
                            print("ppm=0")
                            dataSend = device + "=ppm=0"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                            print("waterIn=1")
                            dataSend = device + "=waterIn=1"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                            print("waterOut=0")
                            dataSend = device + "=waterOut=0"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                            time.sleep(5)
                            case = 3
                            print(case)
                        else:
                            time.sleep(5)
                    elif ppmNow["water"] >= 50:
                        if case == 2 or case == 0:
                            print("autostatus=1")
                            update_autostatus(deviceid, 1)
                            print("mix=1")
                            dataSend = device + "=mix=1"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterIn=0")
                        dataSend = device + "=waterIn=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("ppm=1")
                        dataSend = device + "=ppm=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterOut=0")
                        dataSend = device + "=waterOut=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(20)
                        case = 4
                        print(case)
            elif (ppmNow["PPM"]-ppmForDevice) > 100:
                if case == 2 and ppmNow["PPM"] >= temp["ppm_min"] and ppmNow["PPM"] <= temp["ppm_max"]:
                    time.sleep(30)
                else:
                    if ppmDifferent > 100:
                        if case == 2 or case == 0:
                            print("autostatus=1")
                            update_autostatus(deviceid, 1)
                            print("mix=1")
                            dataSend = device + "=mix=1"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterIn=0")
                        dataSend = device + "=waterIn=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("pump=1")
                        dataSend = device + "=pump=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterOut=1")
                        dataSend = device + "=waterOut=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(10)
                        if ppmNow["pump"] = 0:
                            print("pump=0")
                            dataSend = device + "=pump=0"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(5)
                        case = 5
                        print(case)
                    else:
                        if case == 2 or case == 0:
                            print("autostatus=1")
                            update_autostatus(deviceid, 1)
                            print("mix=1")
                            dataSend = device + "=mix=1"
                            dataSend = dataSend.encode()
                            bus.write_i2c_block_data(addr, 0, dataSend)
                        print("ppm=0")
                        dataSend = device + "=ppm=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterOut=0")
                        dataSend = device + "=waterOut=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        print("waterIn=1")
                        dataSend = device + "=waterIn=1"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(10)
                        print("waterIn=0")
                        dataSend = device + "=waterIn=0"
                        dataSend = dataSend.encode()
                        bus.write_i2c_block_data(addr, 0, dataSend)
                        time.sleep(20)
                        case = 6
        elif ppm["auto_mode"] == 0:
            if (count == 0):
                print("waterIn=0")
                dataSend = device + "=waterIn=0"
                dataSend = dataSend.encode()
                bus.write_i2c_block_data(addr, 0, dataSend)
                print("waterOut=0")
                dataSend = device + "=waterOut=0"
                dataSend = dataSend.encode()
                bus.write_i2c_block_data(addr, 0, dataSend)
                print("ppm=0")
                dataSend = device + "=ppm=0"
                dataSend = dataSend.encode()
                bus.write_i2c_block_data(addr, 0, dataSend)
                print("mix=0")
                dataSend = device + "=mix=0"
                dataSend = dataSend.encode()
                bus.write_i2c_block_data(addr, 0, dataSend)
            print(count)
            count = count + 1
            time.sleep(1)

except Error as e:
    print("Error reading data from MySQL table", e)
finally:
    if (connection.is_connected()):
        connection.close()
        cursor.close()
        print("MySQL connection is closed")
