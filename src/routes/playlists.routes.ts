import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as playlistsController from '../controllers/playlists.controller.ts';
import { cacheMiddleware } from '../lib/redis.ts';

const router = Router();

router.get('/', requireAuth, cacheMiddleware(30, (req) => `playlists:${(req as any).user?.uid || 'anon'}:${JSON.stringify(req.query)}`), playlistsController.getAll);
router.get('/public', cacheMiddleware(60, () => 'playlists:public'), playlistsController.getPublic);
router.post('/', requireAuth, playlistsController.create);
router.get('/:id', requireAuth, playlistsController.getById);
router.put('/:id', requireAuth, playlistsController.update);
router.delete('/:id', requireAuth, playlistsController.remove);
router.post('/:id/tracks', requireAuth, playlistsController.addTrack);
router.delete('/:id/tracks/:trackId', requireAuth, playlistsController.removeTrack);

export default router;
