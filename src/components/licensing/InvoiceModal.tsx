import { X, Printer, Download } from 'lucide-react';

interface InvoiceModalProps {
  isOpen: boolean;
  onClose: () => void;
  invoiceId: string;
}

export default function InvoiceModal({ isOpen, onClose, invoiceId }: InvoiceModalProps) {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
      <div className="w-full max-w-2xl bg-white text-slate-900 rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        {/* Header Bar */}
        <div className="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
          <div className="flex items-center gap-2">
            <h2 className="font-semibold text-lg">Invoice {invoiceId}</h2>
            <span className="px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded border border-emerald-200">PAID</span>
          </div>
          <div className="flex items-center gap-2">
            <button className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition">
              <Printer className="w-4 h-4" />
            </button>
            <button className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition">
              <Download className="w-4 h-4" />
            </button>
            <button onClick={onClose} className="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded transition ml-2">
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* Invoice Content */}
        <div className="flex-1 overflow-y-auto p-8 font-sans">
          
          <div className="flex justify-between items-start mb-12">
            <div>
              <div className="w-12 h-12 bg-blue-600 text-white flex items-center justify-center font-bold text-xl rounded mb-4">H</div>
              <h1 className="text-2xl font-bold tracking-tight text-slate-900">TAX INVOICE</h1>
              <p className="text-sm text-slate-500 mt-1">Hardban Records Lab Sp. z o.o.</p>
              <p className="text-sm text-slate-500">VAT ID: PL1234567890</p>
            </div>
            <div className="text-right">
              <p className="text-sm font-semibold text-slate-900">Invoice No: {invoiceId}</p>
              <p className="text-sm text-slate-500 mt-1">Date: 2026-06-13</p>
              <p className="text-sm text-slate-500">Due Date: 2026-06-27</p>
            </div>
          </div>

          <div className="flex justify-between mb-12">
            <div>
              <p className="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Billed To</p>
              <p className="font-medium text-slate-900">Kawiarnia Aroma - Branch 1</p>
              <p className="text-sm text-slate-500">ul. Przykładowa 12/4</p>
              <p className="text-sm text-slate-500">00-001 Warszawa, Poland</p>
              <p className="text-sm text-slate-500">NIP: 9876543210</p>
            </div>
          </div>

          <table className="w-full text-left mb-8">
            <thead className="border-b border-slate-200">
              <tr>
                <th className="py-3 text-sm font-semibold text-slate-900">Description</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-center">Qty</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-right">Unit Price</th>
                <th className="py-3 text-sm font-semibold text-slate-900 text-right">Net Amount</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              <tr>
                <td className="py-4">
                  <p className="text-sm font-medium text-slate-900">PMPro Level 2 Subscription</p>
                  <p className="text-xs text-slate-500">Coverage: 2026-06-01 to 2026-06-30</p>
                  <p className="text-xs text-slate-500 mt-1">Incl. Royalty-Free Music License & White-label</p>
                </td>
                <td className="py-4 text-sm text-slate-900 text-center">1</td>
                <td className="py-4 text-sm text-slate-900 text-right">299.00 PLN</td>
                <td className="py-4 text-sm text-slate-900 text-right">299.00 PLN</td>
              </tr>
            </tbody>
          </table>

          <div className="flex justify-end">
            <div className="w-64 space-y-3">
              <div className="flex justify-between text-sm">
                <span className="text-slate-500">Subtotal (Net)</span>
                <span className="font-medium text-slate-900">299.00 PLN</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className="text-slate-500">VAT (23%)</span>
                <span className="font-medium text-slate-900">68.77 PLN</span>
              </div>
              <div className="flex justify-between border-t border-slate-200 pt-3">
                <span className="font-bold text-slate-900">Total (Gross)</span>
                <span className="font-bold text-slate-900">367.77 PLN</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
}
