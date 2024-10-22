import os
import shutil
import json
from datetime import datetime
import zipfile

# Plugin Directory (adjusted for the script being in cm-plugin-packer)
PLUGIN_DIR = os.path.dirname(os.path.dirname(os.path.realpath(__file__)))
VERSIONS_DIR = os.path.join(PLUGIN_DIR, 'versions')
SCRIPT_NAME = 'main.py'

# List of files and directories to ignore
IGNORE_LIST = ['versions', 'cm-plugin-packer', 'README.md', '.gitignore']

# Find the root plugin file by searching for the one containing "Plugin Name"
def find_plugin_file():
    for file in os.listdir(PLUGIN_DIR):
        if file.endswith('.php'):
            with open(os.path.join(PLUGIN_DIR, file), 'r') as f:
                content = f.read()
                if 'Plugin Name:' in content:
                    return file
    return None

# Get plugin version from the found plugin file
def get_plugin_version(plugin_file):
    with open(plugin_file, 'r') as f:
        for line in f:
            if 'Version:' in line:
                return line.split('Version:')[1].strip()
    return None

# Create versioned backup directory in 'versions' folder
def create_version_backup(version):
    backup_dir = os.path.join(VERSIONS_DIR, f'v{version}')
    if not os.path.exists(backup_dir):
        os.makedirs(backup_dir)
    return backup_dir

# Zip the versioned directory and remove the directory after zipping
def zip_version_backup(version):
    backup_dir = os.path.join(VERSIONS_DIR, f'v{version}')
    zip_filename = os.path.join(VERSIONS_DIR, f'v{version}.zip')
    
    # Create a zip file
    with zipfile.ZipFile(zip_filename, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(backup_dir):
            for file in files:
                file_path = os.path.join(root, file)
                zipf.write(file_path, os.path.relpath(file_path, backup_dir))
    
    # Remove the directory after creating the zip
    shutil.rmtree(backup_dir)
    print(f"Plugin version {version} successfully zipped and saved as {zip_filename}.")

# Copy plugin files to versioned directory, ignoring specified files
def pack_plugin(version):
    backup_dir = create_version_backup(version)

    # Copy root files except the ignored ones
    for file in os.listdir(PLUGIN_DIR):
        if os.path.isfile(os.path.join(PLUGIN_DIR, file)) and file not in IGNORE_LIST:
            shutil.copy2(os.path.join(PLUGIN_DIR, file), backup_dir)
    
    # Copy 'assets' and 'includes' directories
    dirs_to_copy = ['assets', 'includes']
    for directory in dirs_to_copy:
        if directory not in IGNORE_LIST:
            shutil.copytree(os.path.join(PLUGIN_DIR, directory), os.path.join(backup_dir, directory), dirs_exist_ok=True)

    # Log the backup
    log_backup(version)

    # Zip the versioned directory and remove it
    zip_version_backup(version)

# Log the backed up version
def log_backup(version):
    log_file = os.path.join(VERSIONS_DIR, 'backup_log.json')
    
    if not os.path.exists(log_file):
        with open(log_file, 'w') as f:
            json.dump([], f)
    
    with open(log_file, 'r+') as f:
        log_data = json.load(f)
        log_data.append({
            'version': version,
            'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        })
        f.seek(0)
        json.dump(log_data, f, indent=4)

if __name__ == "__main__":
    # Find the plugin file automatically
    plugin_file = find_plugin_file()

    if plugin_file:
        version = get_plugin_version(os.path.join(PLUGIN_DIR, plugin_file))
        if version:
            print(f"Packing plugin version {version} from file {plugin_file}...")
            pack_plugin(version)
            print(f"Plugin version {version} packed and zipped successfully.")
        else:
            print("Unable to determine plugin version.")
    else:
        print("No plugin file with 'Plugin Name' found.")
