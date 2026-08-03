export class AttendanceController {
    private attendanceService: AttendanceService;

    constructor(attendanceService: AttendanceService) {
        this.attendanceService = attendanceService;
    }

    public async markAttendance(req: Request, res: Response): Promise<void> {
        try {
            const { userId, date, status } = req.body;
            const attendanceRecord = await this.attendanceService.saveAttendance({ userId, date, status });
            res.status(201).json(attendanceRecord);
        } catch (error) {
            res.status(500).json({ message: 'Error marking attendance', error });
        }
    }

    public async getAttendance(req: Request, res: Response): Promise<void> {
        try {
            const { userId } = req.params;
            const attendanceRecords = await this.attendanceService.fetchAttendance(userId);
            res.status(200).json(attendanceRecords);
        } catch (error) {
            res.status(500).json({ message: 'Error retrieving attendance records', error });
        }
    }
}