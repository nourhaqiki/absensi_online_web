require('dotenv').config();
const express = require('express');
const cors = require('cors');
const { Pool } = require('pg');
const cloudinary = require('cloudinary').v2;
const multer = require('multer');

const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Koneksi ke Supabase PostgreSQL
const pool = new Pool({
    connectionString: process.env.DATABASE_URL,
    ssl: { rejectUnauthorized: false }
});

// Setup Cloudinary
cloudinary.config({
    cloud_name: process.env.CLOUD_NAME,
    api_key: process.env.API_KEY,
    api_secret: process.env.API_SECRET
});

// Setup upload file (simpan sementara di memory)
const upload = multer({ storage: multer.memoryStorage() });

// ============================================
// ENDPOINTS API
// ============================================

// Test server aktif
app.get('/', (req, res) => {
    res.json({ message: 'Backend Absensi Online Aktif!' });
});

// Upload absensi dengan foto
app.post('/api/absensi', upload.single('foto'), async (req, res) => {
    try {
        const { nama, latitude, longitude, status } = req.body;

        if (!req.file) {
            return res.status(400).json({ success: false, error: 'Foto wajib diupload' });
        }

        // Upload foto ke Cloudinary
        const uploadResult = await new Promise((resolve, reject) => {
            cloudinary.uploader.upload_stream(
                { folder: 'absensi', resource_type: 'image' },
                (error, result) => {
                    if (error) reject(error);
                    else resolve(result);
                }
            ).end(req.file.buffer);
        });

        // Simpan data ke Supabase
        const result = await pool.query(
            `INSERT INTO absensi (nama, latitude, longitude, foto_url, status)
             VALUES ($1, $2, $3, $4, $5) RETURNING *`,
            [nama, latitude, longitude, uploadResult.secure_url, status || 'hadir']
        );

        res.json({ success: true, data: result.rows[0] });

    } catch (err) {
        console.error('Error:', err);
        res.status(500).json({ success: false, error: err.message });
    }
});

// Ambil semua data absensi
app.get('/api/absensi', async (req, res) => {
    try {
        const result = await pool.query(
            'SELECT * FROM absensi ORDER BY tanggal DESC'
        );
        res.json({ success: true, data: result.rows });
    } catch (err) {
        res.status(500).json({ success: false, error: err.message });
    }
});

// Jalankan server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`✅ Server jalan di port ${PORT}`);
});
