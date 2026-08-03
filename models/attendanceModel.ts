export class Attendance {
    id: number;
    userId: number;
    date: Date;
    status: 'present' | 'absent' | 'late';

    constructor(id: number, userId: number, date: Date, status: 'present' | 'absent' | 'late') {
        this.id = id;
        this.userId = userId;
        this.date = date;
        this.status = status;
    }
}