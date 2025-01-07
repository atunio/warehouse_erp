import platform
import psutil
import subprocess
import json
import os
import re  # Regular expression for parsing

def get_device_info():
    device_info = {}

    # CPU Information
    device_info["Processor"] = platform.processor() or "Unknown"

    # To get more detailed processor information like clock speed and model, we'll use psutil
    try:
        # Getting Processor Clock Speed (GHz)
        cpu_speed = psutil.cpu_freq()
        if cpu_speed:
             device_info["CPU Speed"] = f"{cpu_speed.current / 1000:.2f} GHz"
        else:
            device_info["CPU Speed"] = "Unknown"

    except Exception as e:
        device_info["Processor"] = "Error retrieving processor info"
        device_info["CPU Speed"] = "Error retrieving clock speed"
        device_info["Processor Generation"] = "Error retrieving generation"

    # RAM Information
    svmem = psutil.virtual_memory()
    device_info["RAM"] = f"{svmem.total / (1024 ** 3):.2f} GB"

    # Storage Information
    partitions = psutil.disk_partitions()
    total_storage = 0
    for partition in partitions:
        try:
            partition_usage = psutil.disk_usage(partition.mountpoint)
            total_storage += partition_usage.total
        except PermissionError:
            continue
    device_info["Storage"] = f"{total_storage / (1024 ** 3):.2f} GB"

    # OS-specific Serial Number and Model retrieval
    system = platform.system()
    
    try:
        if system == "Windows":
            # Get serial number and model on Windows
            serial_cmd = 'wmic bios get serialnumber'
            model_cmd = 'wmic computersystem get model'
            serial_number = subprocess.check_output(serial_cmd, shell=True).decode().split("\n")[1].strip()
            model = subprocess.check_output(model_cmd, shell=True).decode().split("\n")[1].strip()
            device_info["Serial Number"] = serial_number or "Unknown"
            device_info["Model"] = model or "Unknown"

        elif system == "Linux":
            # Get serial number and model on Linux
            with open("/sys/class/dmi/id/product_serial", "r") as f:
                serial_number = f.read().strip()
            with open("/sys/class/dmi/id/product_name", "r") as f:
                model = f.read().strip()
            device_info["Serial Number"] = serial_number or "Unknown"
            device_info["Model"] = model or "Unknown"

        elif system == "Darwin":  # macOS
            # Get serial number and model on macOS
            serial_cmd = "system_profiler SPHardwareDataType | awk '/Serial/ {print $4}'"
            model_cmd = "system_profiler SPHardwareDataType | awk '/Model Identifier/ {print $3}'"
            serial_number = subprocess.check_output(serial_cmd, shell=True).decode().strip()
            model = subprocess.check_output(model_cmd, shell=True).decode().strip()
            device_info["Serial Number"] = serial_number or "Unknown"
            device_info["Model"] = model or "Unknown"
        else:
            device_info["Serial Number"] = "Not Available"
            device_info["Model"] = "Not Available"

    except Exception as e:
        device_info["Serial Number"] = "Error retrieving"
        device_info["Model"] = "Error retrieving"

    return device_info

# Run and print information
if __name__ == "__main__":
    info = get_device_info()
    # Print in JSON format for easy readability
    print(json.dumps(info, indent=4))
