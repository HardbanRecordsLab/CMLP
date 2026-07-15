import { X, Printer, Download } from 'lucide-react';
import { useTranslation } from 'react-i18next';

interface InvoiceModalProps {
  isOpen: boolean;
  onClose: () => void;
  invoiceId: string;
  clientName?: string;
  clientAddress?: string;
  clientNip?: string;
  amount?: number;
  currency?: string;
  issueDate?: string;
  vatRate?: number;
}

export default function InvoiceModal({ isOpen, onClose, invoiceId, clientName, clientAddress, clientNip, amount, currency, issueDate, vatRate }: InvoiceModalProps) {
  const { t } = useTranslation();
  if (!isOpen) return null;

  const netAmount = amount ? (amount / 100).toFixed(2) : '0.00';
  const vatPercent = vatRate || 23;
  const vatAmount = amount ? ((amount * vatPercent / 10000).toFixed(2)) : '0.00';
  const grossAmount = amount ? ((amount * (1 + vatPercent / 100) / 100).toFixed(2)) : '0.00';
  const cur = currency || 'PLN';

  return (
    <div role="dialog" aria-modal="true" aria-label={t('invoiceModal.ariaLabel')} className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
      <div className="w-full max-w-2xl bg-white text-slate-900 rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div className="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
          <div className="flex items-center gap-2">
            <h2 className="font-semibold text-lg">{t('invoiceModal.heading', { id: invoiceId })}</h2>
            <span className="px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded border border-emerald-200">{t('invoiceModal.paid')}</span>
          </div>
          <div className="flex items-center gap-2">
            <button className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title={t('invoiceModal.printTitle')}>
              <Printer className="w-4 h-4" />
            </button>
            <button className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title={t('invoiceModal.downloadTitle')}>
              <Download className="w-4 h-4" />
            </button>
            <button onClick={onClose} className="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded transition ml-2">
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>

        <div className="flex-1 overflow-y-auto p-8 font-sans">

          <div className="flex justify-between items-start mb-12">
            <div>
              <div className="w-12 h-12 bg-blue-600 text-white flex items-center justify-center font-bold text-xl rounded mb-4">H</div>
              <h1 className="text-2xl font-bold tracking-tight text-slate-900">{t('invoiceModal.taxInvoice')}</h1>
              <p className="text-sm text-slate-500 mt-1">{t('invoiceModal.companyName')}</p>
              <p className="text-sm text-slate-500">{t('invoiceModal.vatId')}</p>
            </div>
            <div className="text-right">
              <p className="text-sm font-semibold text-slate-900">{t('invoiceModal.invoiceNo', { id: invoiceId })}</p>
              <p className="text-sm text-slate-500 mt-1">{t('invoiceModal.date', { date: issueDate || new Date().toISOString().split('T')[0] })}</p>
            </div>
          </div>

          <div className="flex justify-between mb-12">
            <div>
              <p className="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">{t('invoiceModal.billedTo')}</p>
              <p className="font-medium text-slate-900">{clientName || t('invoiceModal.client')}</p>
              <p className="text-sm text-slate-500">{clientAddress || t('invoiceModal.addressNotProvided')}</p>
              {clientNip && <p className="text-sm text-slate-500">{t('invoiceModal.nip', { nip: clientNip })}</p>}
            </div>
          </div>

          <table className="w-full text-left mb-8">
            <thead className="border-b border-slate-200">
              <tr>
                <th className="py-3 text-sm font-semibold text-slate-900">{t('invoiceModal.descriptionHeader')}</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-center">{t('invoiceModal.qtyHeader')}</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-right">{t('invoiceModal.unitPriceHeader')}</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-right">{t('invoiceModal.netAmountHeader')}</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              <tr>
                <td className="py-4">
                  <p className="text-sm font-medium text-slate-900">{t('invoiceModal.lineItem')}</p>
                  <p className="text-xs text-slate-500">{t('invoiceModal.lineItemDesc')}</p>
                </td>
                <td className="py-4 text-sm text-slate-900 text-center">1</td>
                <td className="py-4 text-sm text-slate-900 text-right">{netAmount} {cur}</td>
                <td className="py-4 text-sm text-slate-900 text-right">{netAmount} {cur}</td>
              </tr>
            </tbody>
          </table>

          <div className="flex justify-end">
            <div className="w-64 space-y-3">
              <div className="flex justify-between text-sm">
                <span className="text-slate-500">{t('invoiceModal.subtotal')}</span>
                <span className="font-medium text-slate-900">{netAmount} {cur}</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className="text-slate-500">{t('invoiceModal.vat', { percent: vatPercent })}</span>
                <span className="font-medium text-slate-900">{vatAmount} {cur}</span>
              </div>
              <div className="flex justify-between border-t border-slate-200 pt-3">
                <span className="font-bold text-slate-900">{t('invoiceModal.total')}</span>
                <span className="font-bold text-slate-900">{grossAmount} {cur}</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
}
