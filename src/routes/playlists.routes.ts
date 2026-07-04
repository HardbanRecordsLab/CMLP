import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as playlistsController from '../controllers/playlists.controller.ts';

const router = Router();

router.get('/', requireAuth, playlistsController.getAll);
router.get('/public', playlistsController.getPublic);
router.post('/', requireAuth, playlistsController.create);
router.get('/:id', requireAuth, playlistsController.getById);
router.put('/:id', requireAuth, playlistsController.update);
router.delete('/:id', requireAuth, playlistsController.remove);
router.post('/:id/tracks', requireAuth, playlistsController.addTrack);
router.delete('/:id/tracks/:trackId', requireAuth, playlistsController.removeTrack);

export default router;
