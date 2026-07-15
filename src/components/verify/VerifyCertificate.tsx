import { useState, useEffect } from 'react';
import { getApiUrl } from '@/utils.ts';
import { useTranslation } from 'react-i18next';

interface VerifyData {
  valid: boolean;
  certificate: {
    number: string;
    type: string;
    status: string;
    issuedAt: string;
    expiresAt: string;
    jurisdiction: string;
    territories: string[] | null;
    maxLocations: number | null;
    maxConcurrentStreams: number | null;
  };
  company: {
    name: string;
    country?: string;
    region?: string;
  };
  signature: {
    signed: boolean;
    signedAt: string | null;
    status: string;
  } | null;
}

export default function VerifyCertificate({ certNumber }: { certNumber: string }) {
  const { t } = useTranslation();
  const [data, setData] = useState<VerifyData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!certNumber) return;
    setLoading(true);
    fetch(getApiUrl(`/api/verify/certificate/${encodeURIComponent(certNumber)}`))
      .then(r => r.json())
      .then(d => {
        if (d.valid === false && !d.certificate) {
          setError(d.error || t('verifyCertificate.errorNotFound'));
        } else {
          setData(d);
        }
      })
      .catch(() => setError(t('verifyCertificate.errorUnavailable')))
      .finally(() => setLoading(false));
  }, [certNumber]);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-slate-950 text-slate-400">
        <div className="text-center space-y-4">
          <div className="w-10 h-10 border-2 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto" />
          <p className="text-sm font-mono">{t('verifyCertificate.verifying')}</p>
        </div>
      </div>
    );
  }

  if (error || !data) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-slate-950 text-slate-400 p-6">
        <div className="max-w-md w-full bg-red-950/20 border border-red-800/40 rounded-2xl p-8 text-center space-y-4">
          <div className="w-16 h-16 mx-auto rounded-full bg-red-500/10 flex items-center justify-center">
            <svg className="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <h1 className="text-xl font-bold text-white">{t('verifyCertificate.notFound')}</h1>
          <p className="text-sm text-slate-500">{t('verifyCertificate.notFoundDesc')}</p>
        </div>
      </div>
    );
  }

  const isValid = data.valid;
  const cert = data.certificate;
  const company = data.company;

  return (
    <div className="flex items-center justify-center min-h-screen bg-slate-950 text-slate-300 p-6">
      <div className="max-w-2xl w-full bg-slate-900/40 border border-slate-800/80 rounded-2xl shadow-2xl backdrop-blur-md overflow-hidden">
        {/* Header Seal */}
        <div className={`p-8 text-center ${isValid ? 'bg-emerald-500/5 border-b border-emerald-500/20' : 'bg-red-500/5 border-b border-red-500/20'}`}>
          <div className={`w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 ${isValid ? 'bg-emerald-500/10' : 'bg-red-500/10'}`}>
            {isValid ? (
              <svg className="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            ) : (
              <svg className="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            )}
          </div>
          <h1 className={`text-2xl font-bold tracking-tight ${isValid ? 'text-emerald-400' : 'text-red-400'}`}>
            {isValid ? t('verifyCertificate.valid') : t('verifyCertificate.invalid')}
          </h1>
          <p className="text-slate-500 text-xs font-mono mt-1 uppercase tracking-widest">{t('verifyCertificate.directLicensingExemption')}</p>
        </div>

        {/* Certificate Details */}
        <div className="p-8 space-y-6">
          <div className="grid grid-cols-2 gap-4 text-sm">
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.certificateNumber')}</p>
              <p className="text-white font-mono font-semibold">{cert.number}</p>
            </div>
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.licenseType')}</p>
              <p className="text-white font-semibold">{cert.type.toUpperCase()}</p>
            </div>
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.status')}</p>
              <span className={`inline-block px-2 py-0.5 rounded text-[11px] font-bold uppercase ${cert.status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'}`}>
                {cert.status}
              </span>
            </div>
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.jurisdiction')}</p>
              <p className="text-white font-semibold">{cert.jurisdiction}</p>
            </div>
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.issued')}</p>
              <p className="text-slate-300 font-mono text-xs">{new Date(cert.issuedAt).toLocaleDateString()}</p>
            </div>
            <div className="space-y-1">
              <p className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{t('verifyCertificate.expires')}</p>
              <p className="text-slate-300 font-mono text-xs">{new Date(cert.expiresAt).toLocaleDateString()}</p>
            </div>
          </div>

          {/* Company */}
          <div className="bg-slate-950/50 rounded-xl p-5 border border-slate-800/60 space-y-3">
            <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400">{t('verifyCertificate.licensee')}</h3>
            <div className="space-y-1">
              <p className="text-white font-semibold">{company.name}</p>
              {company.country && <p className="text-slate-400 text-xs">{company.country}{company.region ? ` / ${company.region}` : ''}</p>}
            </div>
          </div>

          {/* Usage Scope */}
          {(cert.maxLocations || cert.maxConcurrentStreams) && (
            <div className="bg-slate-950/50 rounded-xl p-5 border border-slate-800/60 space-y-3">
              <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400">{t('verifyCertificate.usageLimits')}</h3>
              <div className="grid grid-cols-2 gap-4">
                {cert.maxLocations && (
                  <div>
                    <p className="text-[10px] text-slate-500 uppercase tracking-widest">{t('verifyCertificate.maxLocations')}</p>
                    <p className="text-white font-mono text-sm">{cert.maxLocations}</p>
                  </div>
                )}
                {cert.maxConcurrentStreams && (
                  <div>
                    <p className="text-[10px] text-slate-500 uppercase tracking-widest">{t('verifyCertificate.maxConcurrentStreams')}</p>
                    <p className="text-white font-mono text-sm">{cert.maxConcurrentStreams}</p>
                  </div>
                )}
              </div>
            </div>
          )}

          {/* Digital Signature */}
          <div className="bg-slate-950/50 rounded-xl p-5 border border-slate-800/60 space-y-3">
            <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400">{t('verifyCertificate.digitalSignature')}</h3>
            {data.signature ? (
              <div className="flex items-center gap-3">
                <span className={`w-3 h-3 rounded-full ${data.signature.signed ? 'bg-emerald-500' : 'bg-yellow-500'}`} />
                <span className={`text-sm font-semibold ${data.signature.signed ? 'text-emerald-400' : 'text-yellow-400'}`}>
                  {data.signature.signed ? t('verifyCertificate.signedVerified') : t('verifyCertificate.pendingSignature')}
                </span>
              </div>
            ) : (
              <p className="text-slate-500 text-sm">{t('verifyCertificate.noSignature')}</p>
            )}
          </div>

          {/* Footer */}
          <div className="pt-4 border-t border-slate-800/50 text-center">
            <p className="text-[10px] text-slate-500">{t('verifyCertificate.footer')}</p>
            <p className="text-[10px] text-slate-600 mt-1">{t('verifyCertificate.verifiedAt', { date: new Date().toLocaleString() })}</p>
          </div>
        </div>
      </div>
    </div>
  );
}
