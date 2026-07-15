import React, { useState, useEffect } from 'react';
import { Mail, Bell, Shield, RefreshCw, Send, CheckCircle2, AlertTriangle, Database, AlertCircle, Sparkles } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import toast from 'react-hot-toast';
import { useTranslation } from 'react-i18next';

interface NotificationSettings {
  id?: number;
  provider: string;
  smtpHost: string;
  smtpPort: number;
  smtpUser: string;
  smtpPass: string;
  sendgridApiKey: string;
  fromEmail: string;
  fromName: string;
  templateWelcomeSubject: string;
  templateWelcomeBody: string;
  templateExpirySubject: string;
  templateExpiryBody: string;
  templatePaymentSubject: string;
  templatePaymentBody: string;
}

interface NotificationLog {
  id: number;
  channel: 'email' | 'websocket';
  recipient: string;
  type: string;
  subject: string;
  body: string;
  status: 'sent' | 'failed';
  errorMessage: string | null;
  createdAt: string;
}

export default function NotificationsHub() {
  const { t } = useTranslation();
  const { fetchWithAuth, loading: apiLoading } = useApi();
  const [activeTab, setActiveTab] = useState<'settings' | 'templates' | 'broadcast' | 'logs'>('settings');
  const [settings, setSettings] = useState<NotificationSettings>({
    provider: 'smtp',
    smtpHost: 'smtp.mailtrap.io',
    smtpPort: 587,
    smtpUser: '',
    smtpPass: '',
    sendgridApiKey: '',
    fromEmail: 'noreply@hrl.pl',
    fromName: 'Hardban Records Lab',
    templateWelcomeSubject: '',
    templateWelcomeBody: '',
    templateExpirySubject: '',
    templateExpiryBody: '',
    templatePaymentSubject: '',
    templatePaymentBody: ''
  });

  const [logs, setLogs] = useState<NotificationLog[]>([]);
  const [selectedTemplate, setSelectedTemplate] = useState<'welcome' | 'expiry' | 'payment'>('welcome');

  // Broadcast and Test state
  const [broadcastType, setBroadcastType] = useState<'broadcast_alert' | 'license_expiry' | 'payment_confirmation'>('broadcast_alert');
  const [broadcastSubject, setBroadcastSubject] = useState('');
  const [broadcastBody, setBroadcastBody] = useState('');
  const [broadcastResult, setBroadcastResult] = useState<{ success: boolean; count?: number; error?: string } | null>(null);

  const [testEmail, setTestEmail] = useState('');
  const [testEmailType, setTestEmailType] = useState<'user_registration' | 'license_expiry' | 'payment_confirmation'>('user_registration');
  const [testResult, setTestResult] = useState<{ success: boolean; logId?: number; error?: string } | null>(null);
  const [saveStatus, setSaveStatus] = useState<string | null>(null);

  // Load configuration and audit logs on load
  const loadSettingsAndLogs = () => {
    fetchWithAuth(getApiUrl('/api/notifications/settings'))
      .then(res => res.json())
      .then(data => setSettings(data))
      .catch(err => { toast.error(t('notificationsHub.loadConfigError')); console.error('Failed to load notification configurations', err); });

    fetchWithAuth(getApiUrl('/api/notifications/logs'))
      .then(res => res.json())
      .then(data => setLogs(data))
      .catch(err => { toast.error(t('notificationsHub.loadLogsError')); console.error('Failed to load notification audit trails', err); });
  };

  useEffect(() => {
    loadSettingsAndLogs();
  }, [fetchWithAuth]);

  // Handle saving configurations
  const handleSaveSettings = (e: React.FormEvent) => {
    e.preventDefault();
    setSaveStatus(t('notificationsHub.savingConfig'));
    fetchWithAuth(getApiUrl('/api/notifications/settings'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(settings)
    })
      .then(res => res.json())
      .then(data => {
        setSettings(data);
        setSaveStatus(t('notificationsHub.configSaved'));
        setTimeout(() => setSaveStatus(null), 3000);
        loadSettingsAndLogs();
      })
      .catch(err => {
        setSaveStatus(t('notificationsHub.configSaveError') + ': ' + err.message);
        setTimeout(() => setSaveStatus(null), 5000);
      });
  };

  // Trigger test email simulation
  const handleSendTestEmail = (e: React.FormEvent) => {
    e.preventDefault();
    if (!testEmail) return;
    setTestResult(null);

    // Mock variable setups mapping template expectations
    const vars: Record<string, string> = {
      name: 'System Test Recipient',
      email: testEmail,
      companyName: 'Boutique Lounge S.A.',
      certificateNumber: 'CERT-2026-X8',
      expiresAt: '2026-07-20',
      amount: '59.00',
      currency: 'USD',
      gateway: 'Stripe Credit Card'
    };

    fetchWithAuth(getApiUrl('/api/notifications/test-email'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        toEmail: testEmail,
        type: testEmailType,
        variables: vars
      })
    })
      .then(res => res.json())
      .then(data => {
        setTestResult(data);
        loadSettingsAndLogs();
      })
      .catch(err => {
        setTestResult({ success: false, error: err.message });
      });
  };

  // Trigger real-time WS alert trigger
  const handleBroadcastAlert = (e: React.FormEvent) => {
    e.preventDefault();
    if (!broadcastSubject || !broadcastBody) return;
    setBroadcastResult(null);

    fetchWithAuth(getApiUrl('/api/notifications/broadcast'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        type: broadcastType,
        subject: broadcastSubject,
        body: broadcastBody
      })
    })
      .then(res => res.json())
      .then(data => {
        setBroadcastResult({ success: true, count: data.broadcastedClients });
        setBroadcastSubject('');
        setBroadcastBody('');
        loadSettingsAndLogs();
        setTimeout(() => setBroadcastResult(null), 5000);
      })
      .catch(err => {
        setBroadcastResult({ success: false, error: err.message });
      });
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
          <h1 className="text-xl font-bold text-white flex items-center gap-2">
            <Bell className="w-5 h-5 text-blue-500" /> {t('notificationsHub.heading')}
          </h1>
          <p className="text-xs text-slate-500 mt-1 uppercase tracking-wider">
            {t('notificationsHub.subtitle')}
          </p>
        </div>
        <button 
          onClick={loadSettingsAndLogs} 
          className="flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-xs text-slate-300 rounded-lg transition"
        >
          <RefreshCw className="w-3.5 h-3.5" /> {t('notificationsHub.refreshStatus')}
        </button>
      </div>

      {/* Tabs Switch bar */}
      <div className="flex border-b border-slate-800/80 gap-1">
        <button 
          onClick={() => setActiveTab('settings')}
          className={`px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border-b-2 transition-colors ${
            activeTab === 'settings' 
              ? 'border-blue-500 text-white bg-blue-500/5' 
              : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-900/30'
          }`}
        >
          {t('notificationsHub.tabSettings')}
        </button>
        <button 
          onClick={() => setActiveTab('templates')}
          className={`px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border-b-2 transition-colors ${
            activeTab === 'templates' 
              ? 'border-blue-500 text-white bg-blue-500/5' 
              : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-900/30'
          }`}
        >
          {t('notificationsHub.tabTemplates')}
        </button>
        <button 
          onClick={() => setActiveTab('broadcast')}
          className={`px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border-b-2 transition-colors ${
            activeTab === 'broadcast' 
              ? 'border-blue-500 text-white bg-blue-500/5' 
              : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-900/30'
          }`}
        >
          {t('notificationsHub.tabBroadcast')}
        </button>
        <button 
          onClick={() => setActiveTab('logs')}
          className={`px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border-b-2 transition-colors ${
            activeTab === 'logs' 
              ? 'border-blue-500 text-white bg-blue-500/5' 
              : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-900/30'
          }`}
        >
          {t('notificationsHub.tabLogs')}
        </button>
      </div>

      {saveStatus && (
        <div className="p-3 bg-blue-950/50 border border-blue-500/30 text-blue-300 text-xs rounded-xl flex items-center gap-2">
          <Sparkles className="w-4 h-4 text-blue-400 animate-pulse" />
          <span>{saveStatus}</span>
        </div>
      )}

      {/* Render Active Tab */}
      {activeTab === 'settings' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <form onSubmit={handleSaveSettings} className="lg:col-span-2 bg-slate-900/40 border border-slate-850 p-6 rounded-xl space-y-5">
            <div>
              <h3 className="text-sm font-semibold text-white">{t('notificationsHub.mailGatewayHeading')}</h3>
              <p className="text-[11px] text-slate-500">{t('notificationsHub.mailGatewayDesc')}</p>
            </div>

            <div className="space-y-4">
              <div>
                <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1.5">{t('notificationsHub.activeProviderLabel')}</label>
                <div className="grid grid-cols-2 gap-3">
                  <button
                    type="button"
                    onClick={() => setSettings(p => ({ ...p, provider: 'smtp' }))}
                    className={`p-3 border rounded-xl text-left flex items-center gap-2.5 transition ${
                      settings.provider === 'smtp' 
                        ? 'border-blue-500/80 bg-blue-500/5 text-white' 
                        : 'border-slate-800 bg-slate-950/40 text-slate-400'
                    }`}
                  >
                    <Mail className="w-4 h-4" />
                    <span className="text-xs font-semibold">{t('notificationsHub.providerSmtp')}</span>
                  </button>
                  <button
                    type="button"
                    onClick={() => setSettings(p => ({ ...p, provider: 'sendgrid' }))}
                    className={`p-3 border rounded-xl text-left flex items-center gap-2.5 transition ${
                      settings.provider === 'sendgrid' 
                        ? 'border-blue-500/80 bg-blue-500/5 text-white' 
                        : 'border-slate-800 bg-slate-950/40 text-slate-400'
                    }`}
                  >
                    <Shield className="w-4 h-4" />
                    <span className="text-xs font-semibold">{t('notificationsHub.providerSendgrid')}</span>
                  </button>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.fromEmailLabel')}</label>
                  <input
                    type="email"
                    value={settings.fromEmail}
                    onChange={e => setSettings(s => ({ ...s, fromEmail: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    placeholder={t('notificationsHub.fromEmailPlaceholder')}
                    required
                  />
                </div>
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.fromNameLabel')}</label>
                  <input
                    type="text"
                    value={settings.fromName}
                    onChange={e => setSettings(s => ({ ...s, fromName: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    placeholder={t('notificationsHub.fromNamePlaceholder')}
                    required
                  />
                </div>
              </div>

              {settings.provider === 'smtp' ? (
                <div className="space-y-3 pt-2 border-t border-slate-850">
                  <div className="grid grid-cols-3 gap-3">
                    <div className="col-span-2">
                      <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.smtpHostLabel')}</label>
                      <input
                        type="text"
                        value={settings.smtpHost}
                        onChange={e => setSettings(s => ({ ...s, smtpHost: e.target.value }))}
                        className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none font-mono"
                        placeholder={t('notificationsHub.smtpHostPlaceholder')}
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.smtpPortLabel')}</label>
                      <input
                        type="number"
                        value={settings.smtpPort}
                        onChange={e => setSettings(s => ({ ...s, smtpPort: Number(e.target.value) }))}
                        className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none font-mono"
                        placeholder={t('notificationsHub.smtpPortPlaceholder')}
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-2 gap-3">
                    <div>
                      <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.smtpUserLabel')}</label>
                      <input
                        type="text"
                        value={settings.smtpUser}
                        onChange={e => setSettings(s => ({ ...s, smtpUser: e.target.value }))}
                        className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none font-mono"
                        placeholder={t('notificationsHub.smtpUserPlaceholder')}
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.smtpPassLabel')}</label>
                      <input
                        type="password"
                        value={settings.smtpPass}
                        onChange={e => setSettings(s => ({ ...s, smtpPass: e.target.value }))}
                        className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none font-mono"
                        placeholder={t('notificationsHub.smtpPassPlaceholder')}
                      />
                    </div>
                  </div>
                </div>
              ) : (
                <div className="pt-2 border-t border-slate-850">
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.sendgridApiKeyLabel')}</label>
                  <input
                    type="password"
                    value={settings.sendgridApiKey}
                    onChange={e => setSettings(s => ({ ...s, sendgridApiKey: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none font-mono"
                    placeholder={t('notificationsHub.sendgridApiKeyPlaceholder')}
                  />
                  <p className="text-[10px] text-slate-500 mt-1.5">{t('notificationsHub.sendgridApiKeyHint')}</p>
                </div>
              )}
            </div>

            <div className="pt-4 border-t border-slate-850 flex justify-end">
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold tracking-wide transition flex items-center gap-2 shadow-lg shadow-blue-500/15"
              >
                <Database className="w-3.5 h-3.5" /> {t('notificationsHub.saveConfigBtn')}
              </button>
            </div>
          </form>

          {/* Test email triggering side panel */}
          <div className="bg-slate-900/40 border border-slate-850 p-6 rounded-xl flex flex-col justify-between">
            <div className="space-y-4">
              <div>
                <h3 className="text-sm font-semibold text-white">{t('notificationsHub.sandboxHeading')}</h3>
                <p className="text-[11px] text-slate-500">{t('notificationsHub.sandboxDesc')}</p>
              </div>

              <form onSubmit={handleSendTestEmail} className="space-y-3.5">
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.recipientEmailLabel')}</label>
                  <input
                    type="email"
                    value={testEmail}
                    onChange={e => setTestEmail(e.target.value)}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    placeholder={t('notificationsHub.recipientEmailPlaceholder')}
                    required
                  />
                </div>

                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.scenarioModeLabel')}</label>
                  <select
                    value={testEmailType}
                     onChange={e => setTestEmailType(e.target.value as 'user_registration' | 'license_expiry' | 'payment_confirmation')}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                  >
                    <option value="user_registration">{t('notificationsHub.scenarioRegistration')}</option>
                    <option value="license_expiry">{t('notificationsHub.scenarioLicenseExpiry')}</option>
                    <option value="payment_confirmation">{t('notificationsHub.scenarioPaymentReceipt')}</option>
                  </select>
                </div>

                <button
                  type="submit"
                  className="w-full py-2.5 bg-slate-950 hover:bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2"
                >
                  <Send className="w-3 h-3" /> {t('notificationsHub.execTestSendBtn')}
                </button>
              </form>

              {testResult && (
                <div className={`p-3 border rounded-xl text-xs flex gap-2.5 ${
                  testResult.success 
                    ? 'bg-emerald-950/40 border-emerald-500/20 text-emerald-300' 
                    : 'bg-red-950/40 border-red-500/20 text-red-300'
                }`}>
                  {testResult.success ? (
                    <>
                      <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0" />
                      <div>
                        <p className="font-semibold">{t('notificationsHub.dispatchSuccess')}</p>
                        <p className="text-[10px] text-slate-400 mt-0.5">{t('notificationsHub.recordedLogId')}: {testResult.logId || t('notificationsHub.na')}</p>
                      </div>
                    </>
                  ) : (
                    <>
                      <AlertTriangle className="w-5 h-5 text-red-400 shrink-0" />
                      <div>
                        <p className="font-semibold">{t('notificationsHub.dispatchFailed')}</p>
                        <p className="text-[10px] text-slate-400 mt-0.5">{testResult.error}</p>
                      </div>
                    </>
                  )}
                </div>
              )}
            </div>

            <div className="p-4 bg-slate-950/60 border border-slate-850/50 rounded-xl text-[11px] text-slate-500 space-y-2 mt-4">
              <span className="font-semibold text-slate-400 flex items-center gap-1">
                <Shield className="w-3 h-3 text-blue-500" /> {t('notificationsHub.sandboxProtectionTitle')}
              </span>
              <p className="leading-relaxed">
                {t('notificationsHub.sandboxProtectionDesc')}
              </p>
            </div>
          </div>
        </div>
      )}

      {/* Templates editor */}
      {activeTab === 'templates' && (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="md:col-span-1 bg-slate-950/80 border border-slate-850 rounded-xl overflow-hidden">
            <div className="p-3 bg-slate-900 border-b border-slate-850">
              <span className="text-[11px] uppercase tracking-wider font-extrabold text-slate-400">{t('notificationsHub.selectScenario')}</span>
            </div>
            <div className="divide-y divide-slate-850/60">
              <button
                onClick={() => setSelectedTemplate('welcome')}
                className={`w-full p-4 text-left transition select-none ${
                  selectedTemplate === 'welcome' 
                    ? 'bg-slate-900 text-white font-semibold border-r-2 border-blue-500' 
                    : 'text-slate-400 hover:text-white hover:bg-slate-900/40'
                }`}
              >
                <p className="text-xs">{t('notificationsHub.scenarioUserReg')}</p>
                <p className="text-[10px] text-slate-500 mt-1">{t('notificationsHub.scenarioUserRegDesc')}</p>
              </button>

              <button
                onClick={() => setSelectedTemplate('expiry')}
                className={`w-full p-4 text-left transition select-none ${
                  selectedTemplate === 'expiry' 
                    ? 'bg-slate-900 text-white font-semibold border-r-2 border-blue-500' 
                    : 'text-slate-400 hover:text-white hover:bg-slate-900/40'
                }`}
              >
                <p className="text-xs">{t('notificationsHub.scenarioLicenseExp')}</p>
                <p className="text-[10px] text-slate-500 mt-1">{t('notificationsHub.scenarioLicenseExpDesc')}</p>
              </button>

              <button
                onClick={() => setSelectedTemplate('payment')}
                className={`w-full p-4 text-left transition select-none ${
                  selectedTemplate === 'payment' 
                    ? 'bg-slate-900 text-white font-semibold border-r-2 border-blue-500' 
                    : 'text-slate-400 hover:text-white hover:bg-slate-900/40'
                }`}
              >
                <p className="text-xs">{t('notificationsHub.scenarioPayment')}</p>
                <p className="text-[10px] text-slate-500 mt-1">{t('notificationsHub.scenarioPaymentDesc')}</p>
              </button>
            </div>
          </div>

          <form onSubmit={handleSaveSettings} className="md:col-span-3 bg-slate-900/40 border border-slate-850 p-6 rounded-xl space-y-4">
            <div>
              <h3 className="text-sm font-semibold text-white capitalize">{selectedTemplate} {t('notificationsHub.mailContentTemplate')}</h3>
              <p className="text-[11px] text-slate-500 mt-0.5">{t('notificationsHub.editTemplateDesc')}</p>
            </div>

            {selectedTemplate === 'welcome' && (
              <div className="space-y-4">
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.subjectLabel')}</label>
                  <input
                    type="text"
                    value={settings.templateWelcomeSubject}
                    onChange={e => setSettings(s => ({ ...s, templateWelcomeSubject: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    placeholder={t('notificationsHub.welcomeSubjectPlaceholder')}
                    required
                  />
                </div>
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.bodyLabel')}</label>
                  <textarea
                    value={settings.templateWelcomeBody}
                    onChange={e => setSettings(s => ({ ...s, templateWelcomeBody: e.target.value }))}
                    className="w-full h-44 bg-slate-950 border border-slate-800 text-xs text-white p-3 rounded-lg focus:border-blue-500 outline-none font-mono resize-none"
                    required
                  />
                </div>
                <div className="p-3 bg-slate-950 rounded-lg text-[10px] text-slate-500 space-y-1">
                  <p className="font-semibold text-slate-400">{t('notificationsHub.availableVars')}</p>
                  <ul className="list-disc leading-relaxed pl-4">
                    <li><code className="text-blue-400">{"{{name}}"}</code>: {t('notificationsHub.varNameDesc')}</li>
                    <li><code className="text-blue-400">{"{{email}}"}</code>: {t('notificationsHub.varEmailDesc')}</li>
                  </ul>
                </div>
              </div>
            )}

            {selectedTemplate === 'expiry' && (
              <div className="space-y-4">
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.subjectLabel')}</label>
                  <input
                    type="text"
                    value={settings.templateExpirySubject}
                    onChange={e => setSettings(s => ({ ...s, templateExpirySubject: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    required
                  />
                </div>
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.bodyLabel')}</label>
                  <textarea
                    value={settings.templateExpiryBody}
                    onChange={e => setSettings(s => ({ ...s, templateExpiryBody: e.target.value }))}
                    className="w-full h-44 bg-slate-950 border border-slate-800 text-xs text-white p-3 rounded-lg focus:border-blue-500 outline-none font-mono resize-none"
                    required
                  />
                </div>
                <div className="p-3 bg-slate-950 rounded-lg text-[10px] text-slate-500 space-y-1">
                  <p className="font-semibold text-slate-400">{t('notificationsHub.availableVars')}</p>
                  <ul className="list-disc leading-relaxed pl-4">
                    <li><code className="text-blue-400">{"{{companyName}}"}</code>: {t('notificationsHub.varCompanyNameDesc')}</li>
                    <li><code className="text-blue-400">{"{{certificateNumber}}"}</code>: {t('notificationsHub.varCertNumberDesc')}</li>
                    <li><code className="text-blue-400">{"{{expiresAt}}"}</code>: {t('notificationsHub.varExpiresAtDesc')}</li>
                  </ul>
                </div>
              </div>
            )}

            {selectedTemplate === 'payment' && (
              <div className="space-y-4">
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.subjectLabel')}</label>
                  <input
                    type="text"
                    value={settings.templatePaymentSubject}
                    onChange={e => setSettings(s => ({ ...s, templatePaymentSubject: e.target.value }))}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    required
                  />
                </div>
                <div>
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.bodyLabel')}</label>
                  <textarea
                    value={settings.templatePaymentBody}
                    onChange={e => setSettings(s => ({ ...s, templatePaymentBody: e.target.value }))}
                    className="w-full h-44 bg-slate-950 border border-slate-800 text-xs text-white p-3 rounded-lg focus:border-blue-500 outline-none font-mono resize-none"
                    required
                  />
                </div>
                <div className="p-3 bg-slate-950 rounded-lg text-[10px] text-slate-500 space-y-1">
                  <p className="font-semibold text-slate-400">{t('notificationsHub.availableVars')}</p>
                  <ul className="list-disc leading-relaxed pl-4">
                    <li><code className="text-blue-400">{"{{amount}}"}</code>: {t('notificationsHub.varAmountDesc')}</li>
                    <li><code className="text-blue-400">{"{{currency}}"}</code>: {t('notificationsHub.varCurrencyDesc')}</li>
                    <li><code className="text-blue-400">{"{{gateway}}"}</code>: {t('notificationsHub.varGatewayDesc')}</li>
                  </ul>
                </div>
              </div>
            )}

            <div className="pt-4 border-t border-slate-850 flex justify-end">
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold tracking-wide transition shadow-lg shadow-blue-500/15"
              >
                {t('notificationsHub.saveTemplateBtn')}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* WebSocket Alerts Sender */}
      {activeTab === 'broadcast' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <form onSubmit={handleBroadcastAlert} className="lg:col-span-2 bg-slate-900/40 border border-slate-850 p-6 rounded-xl space-y-5">
            <div>
              <h3 className="text-sm font-semibold text-white">{t('notificationsHub.wsDispatcherHeading')}</h3>
              <p className="text-[11px] text-slate-500">{t('notificationsHub.wsDispatcherDesc')}</p>
            </div>

            <div className="space-y-4">
              <div className="grid grid-cols-3 gap-3">
                <div className="col-span-1">
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.severityLabel')}</label>
                  <select
                    value={broadcastType}
                     onChange={e => setBroadcastType(e.target.value as 'broadcast_alert' | 'license_expiry' | 'payment_confirmation')}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                  >
                    <option value="broadcast_alert">{t('notificationsHub.severitySystemNotice')}</option>
                    <option value="license_expiry">{t('notificationsHub.severityLicenseExpiry')}</option>
                    <option value="payment_confirmation">{t('notificationsHub.severityPaymentBroadcast')}</option>
                  </select>
                </div>
                <div className="col-span-2">
                  <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.alertTitleLabel')}</label>
                  <input
                    type="text"
                    value={broadcastSubject}
                    onChange={e => setBroadcastSubject(e.target.value)}
                    className="w-full bg-slate-950 border border-slate-800 text-xs text-white p-2.5 rounded-lg focus:border-blue-500 outline-none"
                    placeholder={t('notificationsHub.alertTitlePlaceholder')}
                    required
                  />
                </div>
              </div>

              <div>
                <label className="block text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">{t('notificationsHub.detailBodyLabel')}</label>
                <textarea
                  value={broadcastBody}
                  onChange={e => setBroadcastBody(e.target.value)}
                  className="w-full h-32 bg-slate-950 border border-slate-800 text-xs text-white p-3 rounded-lg focus:border-blue-500 outline-none resize-none font-mono"
                  placeholder={t('notificationsHub.detailBodyPlaceholder')}
                  required
                />
              </div>
            </div>

            <div className="pt-4 border-t border-slate-850 flex justify-end">
              <button
                type="submit"
                disabled={!broadcastSubject || !broadcastBody}
                className="px-5 py-2.5 bg-blue-600 disabled:opacity-50 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold tracking-wide transition flex items-center gap-1.5 shadow-lg shadow-blue-500/15"
              >
                <Send className="w-3.5 h-3.5 animate-bounce" /> {t('notificationsHub.broadcastBtn')}
              </button>
            </div>
          </form>

          {/* WS metadata notes */}
          <div className="bg-slate-900/40 border border-slate-850 p-6 rounded-xl flex flex-col justify-between">
            <div className="space-y-4">
              <div>
                <h3 className="text-sm font-semibold text-white">{t('notificationsHub.streamSocketHeading')}</h3>
                <p className="text-[11px] text-slate-500">{t('notificationsHub.streamSocketDesc')}</p>
              </div>

              <div className="space-y-3 font-mono">
                <div className="flex justify-between items-center py-2 border-b border-slate-850">
                  <span className="text-[10px] text-slate-500">{t('notificationsHub.socketService')}</span>
                  <span className="text-[10px] text-emerald-400 bg-emerald-900/20 px-2 py-0.5 rounded border border-emerald-500/20">{t('notificationsHub.operational')}</span>
                </div>
                <div className="flex justify-between items-center py-2 border-b border-slate-850">
                  <span className="text-[10px] text-slate-500">{t('notificationsHub.portAlignment')}</span>
                  <span className="text-[10px] text-slate-300">3000 ({t('notificationsHub.proxyRouted')})</span>
                </div>
                <div className="flex justify-between items-center py-2">
                  <span className="text-[10px] text-slate-500">{t('notificationsHub.activeHandshakes')}</span>
                  <span className="text-xs text-blue-400 font-extrabold font-mono">{t('notificationsHub.liveConnected')}</span>
                </div>
              </div>

              {broadcastResult && (
                <div className="p-3.5 bg-blue-950/40 border border-blue-500/20 text-blue-300 text-xs rounded-xl flex gap-2">
                  <CheckCircle2 className="w-4 h-4 text-blue-400 shrink-0 mt-0.5" />
                  <div>
                    <p className="font-bold">{t('notificationsHub.broadcastSuccess')}</p>
                    <p className="text-[10px] text-slate-400 mt-1">
                      {t('notificationsHub.broadcastSuccessDesc')}
                    </p>
                  </div>
                </div>
              )}
            </div>

            <div className="p-4 bg-slate-950/60 border border-slate-850/50 rounded-xl text-[11px] text-slate-500 space-y-1.5 mt-4">
              <span className="font-semibold text-slate-400 flex items-center gap-1">
                <AlertCircle className="w-3 h-3 text-yellow-500" /> {t('notificationsHub.activeBroadcastProtocol')}
              </span>
              <p className="leading-relaxed">
                {t('notificationsHub.activeBroadcastDesc')}
              </p>
            </div>
          </div>
        </div>
      )}

      {/* Notifications Audit logs */}
      {activeTab === 'logs' && (
        <div className="bg-slate-900/40 border border-slate-850 rounded-xl overflow-hidden">
          <div className="p-4 bg-slate-900 border-b border-slate-850 flex justify-between items-center">
            <div>
              <h3 className="text-xs uppercase tracking-wider font-extrabold text-white">{t('notificationsHub.ledgerHeading')}</h3>
              <p className="text-[11px] text-slate-500 mt-0.5">{t('notificationsHub.ledgerDesc')}</p>
            </div>
            <span className="text-[10px] font-mono bg-slate-950 px-2.5 py-1 rounded border border-slate-800 text-slate-400">
              {t('notificationsHub.totalLogged', { count: logs.length })}
            </span>
          </div>

          {logs.length === 0 ? (
            <div className="p-12 text-center text-slate-500">
              <Database className="w-8 h-8 mx-auto text-slate-700 mb-3" />
              <p className="text-sm">{t('notificationsHub.emptyLogs')}</p>
              <p className="text-[11px] text-slate-600 mt-1">{t('notificationsHub.emptyLogsHint')}</p>
            </div>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs whitespace-nowrap">
                <thead className="bg-slate-950 text-slate-400 font-mono text-[9px] uppercase tracking-wider border-b border-slate-850">
                  <tr>
                    <th className="px-5 py-3">{t('notificationsHub.timestampHeader')}</th>
                    <th className="px-5 py-3">{t('notificationsHub.channelHeader')}</th>
                    <th className="px-5 py-3">{t('notificationsHub.eventTypeHeader')}</th>
                    <th className="px-5 py-3">{t('notificationsHub.recipientHeader')}</th>
                    <th className="px-5 py-3">{t('notificationsHub.subjectHeader')}</th>
                    <th className="px-5 py-3">{t('notificationsHub.transitStatusHeader')}</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-850 bg-slate-900/20 font-sans">
                  {logs.map(log => (
                    <tr key={log.id} className="hover:bg-slate-900/40 transition">
                      <td className="px-5 py-3.5 text-slate-400 text-[11px] font-mono">
                        {new Date(log.createdAt).toLocaleString()}
                      </td>
                      <td className="px-5 py-3.5">
                        <span className={`px-2 py-0.5 text-[9px] font-mono font-bold rounded border ${
                          log.channel === 'email' 
                            ? 'bg-blue-950/50 text-blue-400 border-blue-500/20' 
                            : 'bg-purple-950/50 text-purple-400 border-purple-500/20'
                        }`}>
                          {log.channel.toUpperCase()}
                        </span>
                      </td>
                      <td className="px-5 py-3.5 font-mono text-slate-300 text-[10px]">
                        {log.type}
                      </td>
                      <td className="px-5 py-3.5 text-slate-300 text-[11px] font-mono truncate max-w-xsSelector">
                        {log.recipient}
                      </td>
                      <td className="px-5 py-3.5 text-slate-200 font-medium truncate max-w-sm">
                        {log.subject}
                      </td>
                      <td className="px-5 py-3.5">
                        {log.status === 'sent' ? (
                          <div className="flex items-center gap-1 text-emerald-400">
                            <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span className="font-semibold text-[10px]">{t('notificationsHub.statusSuccess')}</span>
                          </div>
                        ) : (
                          <div className="space-y-0.5">
                            <div className="flex items-center gap-1 text-red-400 font-semibold">
                              <span className="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                              <span className="text-[10px]">{t('notificationsHub.statusFailed')}</span>
                            </div>
                            {log.errorMessage && (
                              <p className="text-[9px] font-mono text-red-500 max-w-sm overflow-hidden text-ellipsis">
                                {log.errorMessage}
                              </p>
                            )}
                          </div>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
