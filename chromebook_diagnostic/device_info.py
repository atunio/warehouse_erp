import platform
import subprocess

def get_serial_number():
    system = platform.system()
    
    try:
        if system == "Windows":
            # Get serial number on Windows
            serial_cmd = 'wmic bios get serialnumber'
            serial_number = subprocess.check_output(serial_cmd, shell=True).decode().split("\n")[1].strip()
        elif system == "Linux":
            # Get serial number on Linux
            serial_cmd = 'sudo dmidecode -s system-serial-number'
            serial_number = subprocess.check_output(serial_cmd, shell=True).decode().strip()
        elif system == "Darwin":  # macOS
            # Get serial number on macOS
            serial_cmd = 'system_profiler SPHardwareDataType | grep "Serial Number"'
            serial_number = subprocess.check_output(serial_cmd, shell=True).decode().split(":")[1].strip()
        else:
            serial_number = "Unsupported OS"
        
        return serial_number
    except Exception as e:
        return f"Error retrieving serial number: {str(e)}"

# Example usage
print(get_serial_number())
