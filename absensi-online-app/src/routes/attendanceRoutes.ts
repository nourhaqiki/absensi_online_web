import { Router } from 'express';
import AttendanceController from '../controllers/attendanceController';

const router = Router();
const attendanceController = new AttendanceController();

export const setAttendanceRoutes = (app) => {
    app.use('/api/attendance', router);

    router.post('/mark', attendanceController.markAttendance.bind(attendanceController));
    router.get('/records', attendanceController.getAttendance.bind(attendanceController));
};