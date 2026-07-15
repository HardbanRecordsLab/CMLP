import React, { useState, useEffect } from 'react';
import { Video, Plus, Trash2, Shield, Globe, Play } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import toast from 'react-hot-toast';
import { useTranslation } from 'react-i18next';

export default function VODManager() {
  const { t } = useTranslation();
  const [vods, setVods] = useState<Record<string, unknown>[]>([]);
  const [isUploading, setIsUploading] = useState(false);
  const [file, setFile] = useState<File | null>(null);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [isPublic, setIsPublic] = useState(false);
  const { fetchWithAuth } = useApi();

  const loadVODs = () => {
    fetchWithAuth(getApiUrl('/api/vod'))
      .then(res => res.json())
      .then(data => setVods(data))
      .catch(e => { toast.error(t('vodManager.failedToLoad')); console.error(e); });
  };

  useEffect(() => {
    loadVODs();
  }, []);

  const handleUpload = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file) return;

    const formData = new FormData();
    formData.append('media_file', file);
    formData.append('title', title);
    formData.append('description', description);
    formData.append('isPublic', isPublic ? 'true' : 'false');

    setIsUploading(true);
    try {
      await fetchWithAuth(getApiUrl('/api/vod'), {
        method: 'POST',
        body: formData
      });
      setFile(null);
      setTitle('');
      setDescription('');
      setIsPublic(false);
      loadVODs();
    } catch (err: unknown) {
      toast.error(t('vodManager.uploadFailed'));
      console.error(err);
    } finally {
      setIsUploading(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm(t('vodManager.deleteConfirm'))) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/vod/${id}`), { method: 'DELETE' });
      loadVODs();
    } catch (err: unknown) {
      toast.error(t('vodManager.deleteFailed'));
      console.error(err);
    }
  };

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl flex flex-col min-h-[600px] overflow-hidden">
      <div className="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
        <div>
          <h2 className="text-white font-medium flex items-center gap-2">
            <Video className="w-5 h-5 text-purple-400" />
            {t('vodManager.heading')}
          </h2>
          <p className="text-xs text-slate-500 mt-1">{t('vodManager.description')}</p>
        </div>
      </div>
      
      <div className="flex flex-1 overflow-hidden">
        {/* Upload Form */}
        <div className="w-1/3 border-r border-slate-800 p-6 bg-slate-950">
          <h3 className="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{t('vodManager.uploadNewMedia')}</h3>
          <form onSubmit={handleUpload} className="space-y-4">
            <div>
              <label className="text-xs font-semibold text-slate-300 block mb-1">{t('vodManager.titleLabel')}</label>
              <input 
                required 
                value={title} 
                onChange={e => setTitle(e.target.value)} 
                className="w-full bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm text-white" 
              />
            </div>
            <div>
              <label className="text-xs font-semibold text-slate-300 block mb-1">{t('vodManager.descriptionLabel')}</label>
              <textarea 
                value={description} 
                onChange={e => setDescription(e.target.value)} 
                className="w-full bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm text-white h-24 resize-none" 
              />
            </div>
            <div>
              <label className="text-xs font-semibold text-slate-300 block mb-1">{t('vodManager.mediaFileLabel')}</label>
              <input 
                required 
                type="file" 
                onChange={e => setFile(e.target.files?.[0] || null)} 
                className="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" 
              />
            </div>
            <div className="flex items-center gap-2">
              <input 
                type="checkbox" 
                id="vodPublic" 
                checked={isPublic} 
                onChange={e => setIsPublic(e.target.checked)} 
              />
              <label htmlFor="vodPublic" className="text-sm text-slate-300">{t('vodManager.publiclyAccessible')}</label>
            </div>
            <button 
              disabled={isUploading} 
              type="submit" 
              className="w-full py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold rounded text-sm transition"
            >
              {isUploading ? t('vodManager.uploading') : t('vodManager.uploadToVault')}
            </button>
          </form>
        </div>

        {/* Media List */}
        <div className="flex-1 p-6 overflow-y-auto bg-slate-900">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {vods.map(vod => (
              <div key={vod.id as React.Key} className="bg-slate-800 border border-slate-700 rounded-lg p-4 flex flex-col relative group">
                <div className="flex justify-between items-start mb-2">
                  <div className="flex items-center gap-2">
                    <h4 className="font-semibold text-white truncate max-w-[200px]">{vod.title as string}</h4>
                    {vod.isPublic ? <Globe className="w-3 h-3 text-emerald-400" /> : <Shield className="w-3 h-3 text-slate-500" />}
                  </div>
                  <button onClick={() => handleDelete(vod.id as number)} className="text-slate-500 hover:text-red-400 transition-colors p-1 bg-slate-900 rounded opacity-0 group-hover:opacity-100">
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
                <p className="text-xs text-slate-400 mb-4 line-clamp-2">{vod.description as string || t('vodManager.noDescription')}</p>
                <div className="mt-auto flex justify-between items-end">
                  <span className="text-[10px] text-slate-500 font-mono bg-slate-900 px-2 py-1 rounded">{vod.mimeType as string}</span>
                </div>
              </div>
            ))}
            {vods.length === 0 && (
              <div className="col-span-full h-40 flex items-center justify-center text-slate-500 border border-dashed border-slate-700 rounded lg">
                {t('vodManager.emptyState')}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
