import { X, Printer, Download, ShieldCheck } from 'lucide-react';
import { jsPDF } from 'jspdf';
import toast from 'react-hot-toast';

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
      doc.text("COMMERCIAL MUSIC LICENSING PLATFORM (CMLP)", 105, 36, { align: "center" });
      doc.text("TECHNICAL & LEGAL COMPLIANCE DIVISION", 105, 41, { align: "center" });

      // Gruba czarna linia separacyjna
      doc.setDrawColor(15, 23, 42);
      doc.setLineWidth(1.5);
      doc.line(20, 50, 190, 50);

      // Sekcja główna: Certyfikat Zwolnienia
      doc.setTextColor(16, 185, 129); // emerald-500
      doc.setFont("helvetica", "bold");
      doc.setFontSize(18);
      doc.text("CERTIFICATE OF ROYALTY EXEMPTION", 105, 62, { align: "center" });

      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(12);
      doc.text(`Certificate Reference Number: ${certNumber}`, 105, 70, { align: "center" });

      // 3. Treść Prawna i Oświadczenie o Zwolnieniu
      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85); // slate-700
      
      const paragraph1 = `This document officially certifies that the business entity operating under the commercial name:`;
      doc.text(paragraph1, 20, 85);

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
      doc.text("Registered Outlet Address / Location:", 25, 105);
      doc.setTextColor(15, 23, 42);
      doc.setFontSize(11);
      doc.text(address, 25, 111);

      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85);

      const bodyText2 = doc.splitTextToSize(
        "Is hereby granted a full, unrestricted legal exemption from the payment of public performance royalties and licensing fees to any regional Collective Rights Management Organizations (OZZ / PROs), including but not limited to regional administrative societies such as ZAiKS, STOART, ZPAV, and SAWP.",
        170
      );
      doc.text(bodyText2, 20, 132);

      const bodyText3 = doc.splitTextToSize(
        "This exemption is validated under Article 107 of the Polish Act on Copyright and Related Rights (Ustawa o prawie autorskim i prawach pokrewnych), as the media broadcasted within the Licensee's customer-facing business boundaries originates exclusively from the Custom Music Licensing Platform (CMLP) catalog. All sound recordings, lyrics, and musical arrangements in this catalog are fully, directly-licensed from independent artists who have explicitly retained their individual economic copyrights and are not associated with or represented by any regional collective collection society.",
        170
      );
      doc.text(bodyText3, 20, 150);

      const bodyText4 = doc.splitTextToSize(
        "Furthermore, this certificate guarantees that any background audio playback inside the location above is compliant with current intellectual property laws and does not constitute copyright infringement.",
        170
      );
      doc.text(bodyText4, 20, 185);

      // Daty Ważności
      doc.setFillColor(248, 250, 252);
      doc.rect(20, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFontSize(9);
      doc.text("ISSUE DATE (VALID FROM)", 24, 211);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(issueDate, 24, 219);

      doc.setFillColor(248, 250, 252);
      doc.rect(110, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      doc.text("EXPIRATION DATE (VALID UNTIL)", 114, 211);
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
      doc.text("Issuer / Representative Details:", 20, 248);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text("Hardban Records Lab Sp. z o.o.", 20, 254);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      doc.setTextColor(100, 116, 139);
      doc.text("Legal and Compliance Department", 20, 259);
      doc.text("KRS: 0000123456 | NIP: 1234567890", 20, 264);

      doc.line(130, 260, 185, 260);
      doc.setFontSize(8);
      doc.text("AUTHORIZED DIGITAL SIGNATURE", 131, 264);
      doc.setTextColor(16, 185, 129);
      doc.setFont("helvetica", "italic");
      doc.setFontSize(9);
      doc.text("[ Digitally Signed / Verified ]", 133, 256);

      doc.save(`Exemption_Certificate_HRL_${certNumber}.pdf`);
    } catch (e: unknown) {
      toast.error('Failed to generate PDF');
      console.error('Failed to generate PDF:', e);
    }
  };

  return (
    <div role="dialog" aria-modal="true" aria-label="Exemption Certificate" className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" id="certificate-modal">
      <div className="w-full max-w-3xl bg-white text-slate-900 rounded-xl shadow-2xl overflow-hidden flex flex-col my-auto">
        
        {/* Header Bar */}
        <div className="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50 print:hidden">
          <div className="flex items-center gap-2">
            <ShieldCheck className="w-5 h-5 text-emerald-600" />
            <h2 className="font-semibold text-lg">Exemption Certificate</h2>
          </div>
          <div className="flex items-center gap-2">
            <button onClick={() => window.print()} className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title="Print Certificate">
              <Printer className="w-4 h-4" />
            </button>
            <button onClick={downloadPDF} className="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded transition" title="Download dynamic PDF">
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
            <h1 className="text-3xl font-bold uppercase tracking-widest text-slate-900">Certificate of Exemption</h1>
            <p className="text-sm text-slate-500 uppercase tracking-widest mt-2">Public Performance Licensing (ZAiKS / STOART / ZPAV)</p>
          </div>

          <div className="space-y-6 text-slate-800 leading-relaxed text-justify mb-12">
            <p>
              This document certifies that the business entity operating under the name <strong>{clientName}</strong>, located at <strong>{address}</strong>, is completely exempt from the obligation to pay public performance royalties to collective rights management organizations (OZZ), including but not limited to ZAiKS, STOART, ZPAV, and SAWP.
            </p>
            <p>
              The exemption is granted on the basis that the music broadcasted at the aforementioned location is sourced exclusively from the <strong>Commercial Music Licensing Platform (CMLP)</strong> operated by Hardban Records Lab Sp. z o.o. The entire catalog provided under this license consists of 100% royalty-free tracks directly licensed from independent artists and rights holders who do not belong to any collective rights management organization.
            </p>
            <p>
              Under Article 107 of the Polish Copyright and Related Rights Act (Ustawa o prawie autorskim i prawach pokrewnych), public playback of the licensed compositions does not infringe upon the copyrights managed by OZZ.
            </p>
          </div>

          <div className="grid grid-cols-2 gap-8 mb-16">
            <div className="bg-slate-50 p-6 rounded border border-slate-200">
              <p className="text-xs uppercase tracking-widest text-slate-500 mb-1">Issue Date</p>
              <p className="font-semibold text-slate-900">{issueDate}</p>
            </div>
            <div className="bg-slate-50 p-6 rounded border border-slate-200">
              <p className="text-xs uppercase tracking-widest text-slate-500 mb-1">Valid Until</p>
              <p className="font-semibold text-slate-900">{validUntil}</p>
            </div>
          </div>

          <div className="flex justify-between items-end mt-16 pt-8 border-t border-slate-200">
            <div>
              <p className="text-sm font-semibold text-slate-900">Hardban Records Lab Sp. z o.o.</p>
              <p className="text-xs text-slate-500">Legal Department & Compliance</p>
              <p className="text-xs text-slate-500">KRS: 0000123456 | NIP: 1234567890</p>
            </div>
            <div className="text-center">
              <div className="w-48 border-b border-slate-400 mb-2"></div>
              <p className="text-xs text-slate-500 uppercase tracking-widest">Authorized Signature</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
}
