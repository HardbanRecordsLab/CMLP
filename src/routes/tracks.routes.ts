import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as tracksController from '../controllers/tracks.controller.ts';
import { cacheMiddleware } from '../lib/redis.ts';

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
  fileFilter: (req, file, cb) => {
    const allowedTypes = ['audio/mpeg', 'audio/wav', 'audio/flac', 'audio/x-flac'];
    if (allowedTypes.includes(file.mimetype)) {
      cb(null, true);
    } else {
      cb(new Error('Invalid file type. Only MP3, WAV, FLAC are allowed.'));
    }
  }
});

router.get('/', requireAuth, cacheMiddleware(30, (req) => `tracks:${(req as any).user?.uid || 'anon'}:${JSON.stringify(req.query)}`), tracksController.getAll);
router.get('/public', cacheMiddleware(60, () => 'tracks:public'), tracksController.getPublic);
router.post('/', requireAuth, requireRole('admin'), upload.single('audio_file'), tracksController.create);

router.get('/:id/tags', requireAuth, tracksController.getTrackTags);
router.put('/:id/tags', requireAuth, requireRole('admin'), tracksController.setTrackTags);
router.post('/:id/tags/generate', requireAuth, requireRole('admin'), tracksController.generateTrackTags);
router.delete('/:id', requireAuth, requireRole('admin'), tracksController.remove);

export default router;
