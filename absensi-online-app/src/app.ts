import express from 'express';
import bodyParser from 'body-parser';
import { setAttendanceRoutes } from './routes/attendanceRoutes';

const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

setAttendanceRoutes(app);

app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});