# 💪 Home Workout RESTful API

A scalable and secure backend API designed to manage workout plans, exercises, and user progress, built specifically for seamless mobile application integration.

## 🚀 Key Features
- **Workout Management:** CRUD operations for exercises, routines, and workout plans.
- **Secure Authentication:** User registration and login using **Laravel Sanctum**.
- **User Progress Tracking:** APIs to log and retrieve user workout history.
- **API Documentation:** Fully documented endpoints for easy mobile team integration.

## 🛠️ Tech Stack
- **Framework:** Laravel (PHP)
- **Database:** MySQL
- **Authentication:** Laravel Sanctum (Token-based)
- **Tools:** Postman, MySQL Workbench

## 🏗️ Architecture
Built following standard **MVC (Model-View-Controller)** principles adapted for API development, ensuring clean separation between routing, business logic, and database interactions. Relational database design was optimized for performance and data normalization.

## 📡 API Endpoints (Examples)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/user/register` | Register a new user |
| POST | `/api/user/login` | Authenticate and get Bearer token |
| GET | `/api/plan/getAllPlans` | Get all available workout plans |
| GET | `/api/plan/getPlan` | Get specific workout details |
| POST | `/api/userPlanProgress/saveUserDailyProgress` | Log user workout progress |

## ⚙️ Installation & Setup
1. Clone the repo: `git clone <repo-url>`
2. Install dependencies: `composer install`
3. Setup `.env` file and configure MySQL database.
4. Run migrations: `php artisan migrate`
5. Start the server: `php artisan serve`
