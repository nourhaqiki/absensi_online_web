# Absensi Online App

## Overview
Absensi Online App is a web application designed for managing attendance records efficiently. It allows users to mark their attendance and retrieve attendance records through a user-friendly interface.

## Features
- User authentication (login and registration)
- Mark attendance for users
- Retrieve attendance records
- Built with TypeScript and Express.js

## Project Structure
```
absensi-online-app
├── src
│   ├── controllers
│   │   ├── attendanceController.ts
│   │   └── authController.ts
│   ├── routes
│   │   └── attendanceRoutes.ts
│   ├── services
│   │   └── attendanceService.ts
│   ├── models
│   │   └── attendanceModel.ts
│   └── app.ts
├── package.json
├── tsconfig.json
└── README.md
```

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd absensi-online-app
   ```
3. Install the dependencies:
   ```
   npm install
   ```

## Usage
To start the application, run:
```
npm start
```
The application will be available at `http://localhost:3000`.

## API Endpoints
- `POST /attendance/mark` - Mark attendance
- `GET /attendance` - Retrieve attendance records

## Contributing
Contributions are welcome! Please open an issue or submit a pull request for any enhancements or bug fixes.

## License
This project is licensed under the MIT License.