# CM Plugin Packer

**CM Plugin Packer** is a Python script designed to package WordPress plugins into versioned directories. This allows for easy rollback to previous versions if needed and ensures that all plugin versions are stored within the same plugin structure.

## Folder Structure

The plugin structure with this packer script looks like this:

/plugin-root
├── /assets
│├── /css
│├── /js
├── /includes
├── /versions # This is where versioned backups are stored
├── rootfil.php
├── uninstall.php
├── README.md
├── .gitignore
└── /cm-plugin-packer # Packer script folder
    └── main.py # The packer script

## Features

- Automatically packages the plugin files into a versioned directory inside the `/versions` folder.
- Ignores specific files and directories such as:
  - `/versions` (where backups are stored)
  - `/cm-plugin-packer` (the packer script itself)
  - `README.md`
  - `.gitignore`
- Maintains a log of all packed versions in `backup_log.json` inside the `/versions` folder.

## How It Works

1. The script reads the version of the plugin from the `rootfil.php` file.
2. It creates a new directory under `/versions` for the specific version (e.g., `/versions/v1.0.0`).
3. It copies the plugin files (excluding ignored ones) into the versioned directory.
4. A log entry is created in `backup_log.json`, recording the version and timestamp of the backup.

## How to Use

1. Navigate to the **cm-plugin-packer** folder in your terminal:

    ```bash
    cd /path/to/your/plugin/cm-plugin-packer
    ```

2. Run the script:

    ```bash
    python3 main.py
    ```

3. The script will package your plugin into a versioned folder inside `/versions`.

## Requirements

- Python 3.x
- A plugin structure with `rootfil.php` containing the plugin version in a standard header format like this:

    ```php
    /*
    Plugin Name: My Plugin
    Version: 1.0.0
    */
    ```

## Configuration

To modify which files or folders are ignored during the packaging process, update the `IGNORE_LIST` in `main.py`. The default values are:

```python
IGNORE_LIST = ['versions', 'cm-plugin-packer', 'README.md', '.gitignore']
```

You can add more files or directories to this list as needed.

## License

This script is open-source and can be freely modified and distributed as part of your plugin projects.
