# System Details

This document outlines the structure and key details of the current project.

## Project Overview

*   **Type:** School Admission System
*   **Framework:** CodeIgniter 4
*   **Backend:** PHP
*   **Frontend:** HTML, CSS, JavaScript (using Bootstrap, possibly the Sneat theme based on `sneat-assets` directory)
*   **Database:** MySQL (inferred from the `skjacth_admission (1).sql` file and typical CodeIgniter setup)

## Key Features

Based on the file structure, the application appears to have the following features:

*   **Public-facing:**
    *   New student admission application form (`app/Controllers/NewAdmission.php`).
    *   Status confirmation page (`app/Controllers/Confirm.php`).
    *   General information/home page (`app/Controllers/Home.php`).
*   **Admin Panel (inferred from `app/Controllers/Admin/` directory, though specific controllers are not listed):**
    *   Management of admissions.
    *   Student data management (`app/Controllers/Students.php`).
    *   System statistics (`app/Controllers/Statistic.php`).
    *   User authentication (`app/Controllers/Login.php`).

## Directory Structure Highlights

*   **`app/Controllers`**: Contains the main application logic (e.g., `Admission.php`, `Students.php`).
*   **`app/Models`**: Handles database interactions (e.g., `AdmissionModel.php`, `LoginModel.php`).
*   **`app/Views`**: Contains the HTML templates for the user interface.
*   **`public`**: Web server's document root, contains assets like CSS, JS, and images (including `sneat-assets`).
*   **`skjacth_admission (1).sql`**: A SQL dump which likely contains the database schema and initial data.
*   **`composer.json`**: Defines PHP dependencies and project metadata.
*   **`spark`**: CodeIgniter 4 CLI tool.
