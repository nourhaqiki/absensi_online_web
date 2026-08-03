import { Attendance } from '../models/attendanceModel';

export class AttendanceService {
    private attendanceRecords: Attendance[] = [];

    public markAttendance(userId: string, date: string, status: string): Attendance {
        const attendance: Attendance = { id: this.generateId(), userId, date, status };
        this.attendanceRecords.push(attendance);
        return attendance;
    }

    public getAttendance(userId: string): Attendance[] {
        return this.attendanceRecords.filter(record => record.userId === userId);
    }

    private generateId(): string {
        return (this.attendanceRecords.length + 1).toString();
    }
}