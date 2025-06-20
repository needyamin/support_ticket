# Support Ticket System

A modern, secure, and feature-rich support ticket system built with Laravel. Designed for businesses, IT teams, and customer support centers to efficiently manage, track, and resolve customer issues.

## Why This Script?
- **Centralized Support:** Manage all customer queries and issues in one place.
- **Role-Based Access:** Admins, moderators, and users have different permissions for security and workflow.
- **Modern UI:** Clean, responsive, and user-friendly interface for both staff and customers.
- **Security First:** Strict file upload validation, .htaccess protection, and role-based access control.
- **Notifications:** Real-time in-app and email notifications for ticket activity.
- **Easy Customization:** Built on Laravel, easy to extend and integrate.

## Features
- User authentication (login/register)
- Create, view, edit, and delete support tickets
- Assign tickets to specific users/admins
- Add replies and attachments (images, PDF, video only, max 20MB)
- In-app and email notifications for ticket creation, replies, status changes, and attachments
- Admin-only user management (CRUD) with group/role assignment
- Ticket status and priority management
- Secure file upload with .htaccess protection
- Beautiful dashboard and ticket list with search and filters
- Responsive design (Bootstrap 5)

## Technologies Used
- **Backend:** Laravel 12.x (PHP 8.3+)
- **Frontend:** Blade, Bootstrap 5, jQuery, DataTables
- **Database:** MySQL (or compatible)
- **Notifications:** Laravel Notification System (mail + database)
- **Security:** Laravel validation, .htaccess, role-based middleware

## Getting Started
1. Clone the repo and install dependencies (`composer install`, `npm install`)
2. Configure your `.env` (database, mail, etc.)
3. Run migrations: `php artisan migrate`
4. (Optional) Seed users/tickets: `php artisan db:seed`
5. Start the server: `php artisan serve` or use Laragon/XAMPP

## Screenshots

Below are some screenshots of the Support Ticket System in action:

![Attachment Upload](https://github.com/user-attachments/assets/070bafac-eea2-4e14-9ba7-dae22d4df4fb)
![User Management](https://github.com/user-attachments/assets/860bdaa5-d44b-4349-aed5-91640c9bc68f)
![Notification System](https://github.com/user-attachments/assets/a69f36ae-3d2a-4d35-b34e-3a2023035a09)
![Dashboard](https://github.com/user-attachments/assets/9d798246-bb7d-471c-80d5-f41d13500140)
![Ticket List](https://github.com/user-attachments/assets/0cb8242c-eab3-4a69-bb06-b480d7288780)
![Ticket Details](https://github.com/user-attachments/assets/431d24b1-f86d-469d-9dd8-8d42d086b094)

## License
MIT
