import { X, Printer, Download, ShieldCheck } from 'lucide-react';
import { jsPDF } from 'jspdf';
import toast from 'react-hot-toast';
import { useTranslation } from 'react-i18next';

interface CertificateModalProps {
  isOpen: boolean;
  onClose: () => void;
  clientName: string;
  address: string;
  issueDate: string;
  validUntil: string;
  certificateNumber?: string;
}

export default function CertificateModal({ isOpen, onClose, clientName, address, issueDate, validUntil, certificateNumber }: CertificateModalProps) {
  const { t } = useTranslation();
  if (!isOpen) return null;

  const downloadPDF = () => {
    try {
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4'
      });

      const certNumber = certificateNumber || `HRL-LIC-${Math.random().toString(36).substring(2, 8).toUpperCase()}`;

      // 1. Elegancja i Autentyczność - Podwójna Ramka Certyfikatu
      doc.setDrawColor(15, 23, 42); // slate-900
      doc.setLineWidth(1);
      doc.rect(5, 5, 200, 287); // Zewnętrzna ramka
      
      doc.setDrawColor(203, 213, 225); // slate-300
      doc.setLineWidth(0.5);
      doc.rect(7, 7, 196, 283); // Wewnętrzna ramka

      // 2. Nagłówek i tożsamość wizualna CMLP/HRL
      doc.setTextColor(15, 23, 42); // slate-900
      doc.setFont("helvetica", "bold");
      doc.setFontSize(26);
      doc.text("HARDBAN RECORDS LAB", 105, 30, { align: "center" });

      doc.setFontSize(10);
      doc.setFont("helvetica", "normal");
      doc.setTextColor(100, 116, 139); // slate-500
      doc.text(t('certificateModal.pdfHeaderSubtitle'), 105, 36, { align: "center" });
      doc.text(t('certificateModal.pdfHeaderDivision'), 105, 41, { align: "center" });

      // Gruba czarna linia separacyjna
      doc.setDrawColor(15, 23, 42);
      doc.setLineWidth(1.5);
      doc.line(20, 50, 190, 50);

      // Sekcja główna: Certyfikat Zwolnienia
      doc.setTextColor(16, 185, 129); // emerald-500
      doc.setFont("helvetica", "bold");
      doc.setFontSize(18);
      doc.text(t('certificateModal.certTitle'), 105, 62, { align: "center" });

      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(12);
      doc.text(t('certificateModal.certRefNumber', { number: certNumber }), 105, 70, { align: "center" });

      // 3. Treść Prawna i Oświadczenie o Zwolnieniu
      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85); // slate-700
      
      doc.text(t('certificateModal.paragraph1'), 20, 85);

      // Blok Licencjobiorcy (Licensee Highlight Card)
      doc.setFillColor(248, 250, 252); // slate-50
      doc.setDrawColor(226, 232, 240); // slate-200
      doc.setLineWidth(0.5);
      doc.rect(20, 90, 170, 32, "FD");

      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(13);
      doc.text(clientName, 25, 98);
      
      doc.setFont("helvetica", "normal");
      doc.setFontSize(10);
      doc.setTextColor(100, 116, 139);
      doc.text(t('certificateModal.addressLabel'), 25, 105);
      doc.setTextColor(15, 23, 42);
      doc.setFontSize(11);
      doc.text(address, 25, 111);

      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85);

      const bodyText2 = doc.splitTextToSize(
        t('certificateModal.body2'),
        170
      );
      doc.text(bodyText2, 20, 132);

      const bodyText3 = doc.splitTextToSize(
        t('certificateModal.body3'),
        170
      );
      doc.text(bodyText3, 20, 150);

      const bodyText4 = doc.splitTextToSize(
        t('certificateModal.body4'),
        170
      );
      doc.text(bodyText4, 20, 185);

      // Daty Ważności
      doc.setFillColor(248, 250, 252);
      doc.rect(20, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFontSize(9);
      doc.text(t('certificateModal.issueDateLabel'), 24, 211);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(issueDate, 24, 219);

      doc.setFillColor(248, 250, 252);
      doc.rect(110, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      doc.text(t('certificateModal.expirationDateLabel'), 114, 211);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(validUntil, 114, 219);

      // Linie oddzielające dół i podpisy
      doc.setDrawColor(226, 232, 240);
      doc.setLineWidth(0.5);
      doc.line(20, 240, 190, 240);

      // Podpisy i Reprezentatywność HRL
      doc.setFont("helvetica", "normal");
      doc.setFontSize(10);
      doc.setTextColor(100, 116, 139);
      doc.text(t('certificateModal.issuerDetails'), 20, 248);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(t('certificateModal.issuerName'), 20, 254);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      doc.setTextColor(100, 116, 139);
      doc.text(t('certificateModal.legalDepartment'), 20, 259);
      doc.text(t('certificateModal.krsNip'), 20, 264);

      doc.line(130, 260, 185, 260);
      doc.setFontSize(8);
      doc.text(t('certificateModal.digitalSignature'), 131, 264);
      doc.setTextColor(16, 185, 129);
      doc.setFont("helvetica", "italic");
      doc.setFontSize(9);
      doc.text(t('certificateModal.signedVerified'), 133, 256);

      doc.save(`Exemption_Certificate_HRL_${certNumber}.pdf`);
    } catch (e: unknown) {
      toast.error(t('certificateModal.failedToGenerate'));
      console.error('Failed to generate PDF:', e);
    }
  };

  return (
    <div role="dialog" aria-modal="true" aria-label={t('certificateModal.ariaLabel')} className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" id="certificate-modal">
      <div className="w-full max-w-3xl bg-white text-slate-900 rounded-xl shadow-2xl overflow-hidden flex flex-col my-auto">
        
        {/* Header Bar */}
        <div className="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50 print:hidden">
          <div className="flex items-center gap-2">
            <ShieldCheck className="w-5 h-5 text-emerald-600" />
            <h2 className="font-semibold text-lg">{t('certificateModal.heading')}</h2>
          </div>
          <div className="flex items-center gap-2">
            <button onClick={() => window.print()} className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title={t('certificateModal.printTitle')}>
              <Printer className="w-4 h-4" />
            </button>
            <button onClick={downloadPDF} className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title={t('certificateModal.downloadTitle')}>
              <Download className="w-4 h-4" />
            </button>
            <button onClick={onClose} className="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded transition ml-2" id="close-modal-btn">
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* Certificate Content - Printable Area */}
        <div className="p-12 font-serif bg-white" id="printable-certificate">
          <div className="text-center mb-12 border-b-2 border-slate-900 pb-8 relative">
            <div className="absolute top-0 left-0 w-16 h-16 bg-slate-900 rounded-lg flex items-center justify-center print:hidden">
              <span className="text-white font-bold tracking-widest text-2xl">HRL</span>
            </div>
            <h1 className="text-3xl font-bold uppercase tracking-widest text-slate-900">{t('certificateModal.documentTitle')}</h1>
            <p className="text-sm text-slate-500 uppercase tracking-widest mt-2">{t('certificateModal.documentSubtitle')}</p>
          </div>

          <div className="space-y-6 text-slate-800 leading-relaxed text-justify mb-12">
            <p>
              {t('certificateModal.paragraphModal1', { clientName, address })}
            </p>
            <p>
              {t('certificateModal.paragraphModal2')}
            </p>
            <p>
              {t('certificateModal.paragraphModal3')}
            </p>
          </div>

          <div className="grid grid-cols-2 gap-8 mb-16">
            <div className="bg-slate-50 p-6 rounded border border-slate-200">
              <p className="text-xs uppercase tracking-widest text-slate-500 mb-1">{t('certificateModal.issueDate')}</p>
              <p className="font-semibold text-slate-900">{issueDate}</p>
            </div>
            <div className="bg-slate-50 p-6 rounded border border-slate-200">
              <p className="text-xs uppercase tracking-widest text-slate-500 mb-1">{t('certificateModal.validUntil')}</p>
              <p className="font-semibold text-slate-900">{validUntil}</p>
            </div>
          </div>

          <div className="flex justify-between items-end mt-16 pt-8 border-t border-slate-200">
            <div>
              <p className="text-sm font-semibold text-slate-900">{t('certificateModal.issuerName')}</p>
              <p className="text-xs text-slate-500">{t('certificateModal.legalDeptCompliance')}</p>
              <p className="text-xs text-slate-500">{t('certificateModal.krsNip')}</p>
            </div>
            <div className="text-center">
              <div className="w-48 border-b border-slate-400 mb-2"></div>
              <p className="text-xs text-slate-500 uppercase tracking-widest">{t('certificateModal.authorizedSignature')}</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
}
