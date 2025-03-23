# freequence.org

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Overview

freequence.org is a web application designed to help radio users (and users of other devices utilizing radio waves) coordinate frequencies and avoid interference. The platform aims to be:

*   **Flexible:** Allowing registration of various device types (DMR, DMR446, PMR446, CB, SDR, Repeaters, and custom setups).
*   **Scalable:** Built to accommodate future growth (new features, more users).
*   **User-Friendly:** Easy to navigate, with an intuitive and clear interface.
*   **Community-Driven:** Featuring a forum for users to share experiences, ask questions, and connect.
*   **Open:** Offering similar capabilities to [Brandmeister](https://brandmeister.network/), allowing logged-in users to enter data about their *own* devices. The service focuses on device *location* and configuration, not real-time activity.

## Key Features (Planned)

*   **User Registration & Login:**
    *   Secure user accounts with password recovery.
    *   "Remember Me" option.
*   **User Profiles:**
    *   Display basic user information.
    *   List of registered devices.
*   **Device Registration:**
    *   Support for unlimited device types and configuration.
    *   Predefined System and User templates.
    *   Grouping of entries (e.g., by location).
    *   Subgroups within groups (e.g., for different teams).
    *   Interactive map integration (OpenLayers) for location selection.
    *   Status settings (active, inactive, reserved).
    *   Availability scheduling.
    *   "Call sign/alias" field.
*   **Search & Filtering:** Find entries by location (on a map), device type, parameters, and status.
*   **Interactive Map:** Visualize registered devices on a map (using OpenLayers).
*   **Forum:** Integrated forum for user discussions (categories to be determined).
*   **Documentation:** Standards, device configuration guides, and usage examples (future consideration).
*   **DMR Network Wizard (Future):** Automatic generation of DMR network configurations.
*   **Android App (Future):**  Potential for real-time location tracking and data entry.

## Technologies

*   **Frontend:** HTML, CSS, JavaScript
*   **Backend:** PHP
*   **Database:** MySQL
*   **Web Server:** Apache with SSL (using `.htaccess` for request rewriting)
*   **External APIs:** OpenLayers

## Getting Started

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/starepiernikowe/freequence.org.git](https://github.com/starepiernikowe/freequence.org.git)
    ```
2.  **Database Setup:**
    *   Create a MySQL database.
    *   Import the SQL schema from the provided SQL code `database/freequence.sql`.
3.  **Configuration:**
    *   Copy `app/config/config.php.example` to `app/config/config.php` (or create `config.php` from scratch).
    *   Edit `app/config/config.php` and enter your database credentials.
4.  **Web Server:**
    *   Ensure your web server is configured to use the `.htaccess` file for proper request rewriting (this is crucial for the application routing).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.