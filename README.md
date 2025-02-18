# Hospital Management System

This Symfony application provides a comprehensive hospital management system with medical records and stay management capabilities.

## Interface Paths

### Front Office (Patient Interface)

#### Patient Dashboard
- **URL**: `/mon-espace/`
- **Route Name**: `app_front_office_dashboard`
- **Description**: Patient's personal space showing their information and list of medical records

#### Patient Medical Record View
- **URL**: `/mon-espace/dossier-medical/{id}`
- **Route Name**: `app_front_office_dossier_show`
- **Description**: Detailed view of a specific medical record with associated stays

#### Patient Stay View
- **URL**: `/mon-espace/sejour/{id}`
- **Route Name**: `app_front_office_sejour_show`
- **Description**: Detailed view of a specific hospital stay

### Back Office (Administrative Interface)

#### Medical Records Management
- **List Records**: `/dossier-medicale/` (GET)
  - Route Name: `app_dossier_medicale_index`
  - Lists all medical records

- **View Record**: `/dossier-medicale/{id}` (GET)
  - Route Name: `app_dossier_medicale_show`
  - Shows detailed information about a specific medical record

- **Create Record**: `/dossier-medicale/new` (GET, POST)
  - Route Name: `app_dossier_medicale_new`
  - Form to create a new medical record

- **Edit Record**: `/dossier-medicale/{id}/edit` (GET, POST)
  - Route Name: `app_dossier_medicale_edit`
  - Form to modify an existing medical record

- **Delete Record**: `/dossier-medicale/{id}` (POST)
  - Route Name: `app_dossier_medicale_delete`
  - Deletes a medical record with confirmation

#### Hospital Stays Management
- **List Stays**: `/sejour/` (GET)
  - Route Name: `app_sejour_index`
  - Lists all hospital stays

- **View Stay**: `/sejour/{id}` (GET)
  - Route Name: `app_sejour_show`
  - Shows detailed information about a specific stay

- **Create Stay**: `/sejour/new` (GET, POST)
  - Route Name: `app_sejour_new`
  - Form to create a new hospital stay

- **Edit Stay**: `/sejour/{id}/edit` (GET, POST)
  - Route Name: `app_sejour_edit`
  - Form to modify an existing hospital stay

- **Delete Stay**: `/sejour/{id}` (POST)
  - Route Name: `app_sejour_delete`
  - Deletes a hospital stay with confirmation

## Important Notes

1. **Temporary Configuration**
   - Currently using hardcoded IDs for relationships:
     - Medical records creation uses hardcoded Medecin ID: 1
     - Medical records creation uses hardcoded ResponsableAdministratif ID: 1
     - Hospital stays creation uses hardcoded DossierMedicale ID: 1
     - Front office uses hardcoded Patient ID: 1

2. **Security**
   - Authentication system not yet implemented
   - All routes are currently accessible without restrictions
   - Security checks will need to be added when user authentication is implemented

3. **Forms**
   - All forms include CSRF protection
   - File upload supported for medical documents
   - Date validation ensures end dates are after start dates

## Setup Instructions

1. Ensure you have the following records in your database:
   - A Medecin record with ID: 1
   - A ResponsableAdministratif record with ID: 1
   - A DossierMedicale record with ID: 1
   - A Patient record with ID: 1

2. Future improvements needed:
   - Integration with user authentication system
   - Role-based access control
   - Dynamic relationship management instead of hardcoded IDs
   - Patient-specific access restrictions in front office 