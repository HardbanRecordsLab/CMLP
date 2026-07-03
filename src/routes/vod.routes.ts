import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as vodController from '../controllers/vod.controller.ts';

const router = Router();

const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    const dir = path.join(process.cwd(), 'media_files');
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    cb(null, dir);
  },
  filename: function (req, file, cb) {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, uniqueSuffix + path.extname(file.originalname));
  }
});

const upload = multer({
  storage: storage,
  limits: { fileSize: 500 * 1024 * 1024 },
});

router.get('/', requireAuth, vodController.getAll);
router.post('/', requireAuth, requireRole('admin'), upload.single('media_file'), vodController.create);
router.delete('/:id', requireAuth, requireRole('admin'), vodController.remove);

export default router;
