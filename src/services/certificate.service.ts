import PDFDocument from 'pdfkit';
import QRCode from 'qrcode';
import { db } from '../db/index.ts';
import { companies, licenses } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import path from 'path';
import fs from 'fs';

export interface CertificateOptions {
  licenseId: number;
  outputPath?: string;
}

export interface InvoiceOptions {
  licenseId: number;
  amount: number;
  currency: string;
  outputPath?: string;
}

function ensureDir(filePath: string) {
  const dir = path.dirname(filePath);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
}

async function addWatermark(doc: PDFKit.PDFDocument) {
  const fontSize = 60;
  doc.save();
  doc.fontSize(fontSize);
  doc.fillColor('#cccccc');
  doc.opacity(0.15);
  const textWidth = doc.widthOfString('ORIGINAL');
  const pageWidth = (doc.page?.width || 612);
  const pageHeight = (doc.page?.height || 792);
  const cx = (pageWidth - textWidth) / 2;
  const cy = (pageHeight - fontSize) / 2;
  doc.translate(cx, cy).rotate(-45).text('ORIGINAL', 0, 0);
  doc.restore();
}

export async function generateLicenseCertificate(options: CertificateOptions): Promise<string> {
  const [license] = await db.select().from(licenses).where(eq(licenses.id, options.licenseId));
  if (!license) throw new Error(`License #${options.licenseId} not found`);

  const [company] = await db.select().from(companies).where(eq(companies.id, license.companyId as number));

  const outputPath = options.outputPath || path.join(process.cwd(), 'media_files', `certificate-${license.certificateNumber}.pdf`);
  ensureDir(outputPath);

  const qrDataUrl = await QRCode.toDataURL(`https://verify.hrl.pl/certificate/${license.certificateNumber}`);

  return new Promise((resolve, reject) => {
    const doc = new PDFDocument({ size: 'A4', layout: 'landscape' });
    const stream = fs.createWriteStream(outputPath);
    doc.pipe(stream);

    addWatermark(doc);

    doc.fontSize(24).text('LICENSE CERTIFICATE', { align: 'center' });
    doc.moveDown(1);

    doc.fontSize(12);
    doc.text(`Certificate No: ${license.certificateNumber}`);
    doc.text(`License Type: ${license.licenseType}`);
    doc.text(`Status: ${license.status}`);
    doc.text(`Issued: ${new Date(license.issuedAt).toLocaleDateString()}`);
    doc.text(`Expires: ${new Date(license.expiresAt).toLocaleDateString()}`);
    doc.text(`Jurisdiction: ${license.jurisdiction}`);
    doc.moveDown(1);

    doc.text('Company Information:', { underline: true });
    doc.text(`Name: ${company?.name || license.companyName}`);
    doc.text(`Country: ${company?.country || 'N/A'}`);
    doc.text(`Region: ${company?.region || 'N/A'}`);
    doc.moveDown(1);

    if (license.territories) {
      doc.text(`Territories: ${(license.territories as string[]).join(', ')}`);
    }
    if (license.maxLocations) doc.text(`Max Locations: ${license.maxLocations}`);
    if (license.maxConcurrentStreams) doc.text(`Max Concurrent Streams: ${license.maxConcurrentStreams}`);

    doc.moveDown(2);
    doc.image(qrDataUrl, doc.page.width - 120, doc.page.height - 140, { width: 100, height: 100 });
    doc.fontSize(8).text('Scan to verify', doc.page.width - 120, doc.page.height - 30);

    doc.end();
    stream.on('finish', () => resolve(outputPath));
    stream.on('error', reject);
  });
}

export async function generateInvoice(options: InvoiceOptions): Promise<string> {
  const [license] = await db.select().from(licenses).where(eq(licenses.id, options.licenseId));
  if (!license) throw new Error(`License #${options.licenseId} not found`);

  const [company] = await db.select().from(companies).where(eq(companies.id, license.companyId as number));

  const invoiceNumber = `INV-${license.certificateNumber}-${Date.now()}`;
  const outputPath = options.outputPath || path.join(process.cwd(), 'media_files', `${invoiceNumber}.pdf`);
  ensureDir(outputPath);

  const qrDataUrl = await QRCode.toDataURL(`https://verify.hrl.pl/invoice/${invoiceNumber}`);

  return new Promise((resolve, reject) => {
    const doc = new PDFDocument({ size: 'A4' });
    const stream = fs.createWriteStream(outputPath);
    doc.pipe(stream);

    addWatermark(doc);

    doc.fontSize(24).text('INVOICE', { align: 'center' });
    doc.moveDown(1);

    doc.fontSize(12);
    doc.text(`Invoice No: ${invoiceNumber}`);
    doc.text(`Date: ${new Date().toLocaleDateString()}`);
    doc.moveDown(1);

    doc.text('Bill To:', { underline: true });
    doc.text(`${company?.name || license.companyName}`);
    doc.text(`Country: ${company?.country || 'N/A'}`);
    doc.moveDown(1);

    doc.text('License Details:', { underline: true });
    doc.text(`Certificate: ${license.certificateNumber}`);
    doc.text(`Type: ${license.licenseType}`);
    doc.moveDown(1);

    doc.text('Amount:', { underline: true });
    doc.fontSize(16).text(`${options.amount} ${options.currency}`, { align: 'right' });

    doc.moveDown(2);
    doc.image(qrDataUrl, doc.page.width - 120, doc.page.height - 140, { width: 100, height: 100 });
    doc.fontSize(8).text('Scan to verify', doc.page.width - 120, doc.page.height - 30);

    doc.end();
    stream.on('finish', () => resolve(outputPath));
    stream.on('error', reject);
  });
}
