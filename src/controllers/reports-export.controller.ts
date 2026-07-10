import { Response } from 'express';
import PDFDocument from 'pdfkit';
import { db } from '../db/index.ts';
import { tracks, licenses, payments, contracts, users, audit_logs } from '../db/schema.ts';
import { desc } from 'drizzle-orm';

export async function exportPDF(req: any, res: Response) {
  try {
    const [allTracks, allLicenses, allPayments, allUsers] = await Promise.all([
      db.select().from(tracks),
      db.select().from(licenses),
      db.select().from(payments),
      db.select().from(users),
    ]);

    const doc = new PDFDocument({ margin: 50, size: 'A4' });
    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename=hrl_report_${Date.now()}.pdf`);
    doc.pipe(res);

    const brandColor = '#1e3a5f';
    const accentColor = '#3b82f6';

    doc.fontSize(22).font('Helvetica-Bold').fillColor(brandColor).text('Hardban Records Lab', { align: 'center' });
    doc.fontSize(10).font('Helvetica').fillColor('#666').text('Commercial Music Licensing Platform — Analytics Report', { align: 'center' });
    doc.moveDown(0.5);
    doc.fontSize(8).fillColor('#999').text(`Generated: ${new Date().toLocaleString('pl-PL')}`, { align: 'center' });
    doc.moveDown(1.5);

    doc.moveTo(50, doc.y).lineTo(545, doc.y).strokeColor('#ddd').stroke();
    doc.moveDown(1);

    const netRevenue = allPayments.filter(p => p.status === 'completed').reduce((s, p) => s + p.amount, 0);
    const activeLicenses = allLicenses.filter(l => l.status === 'active').length;

    doc.fontSize(14).font('Helvetica-Bold').fillColor(brandColor).text('Executive Summary', { underline: true });
    doc.moveDown(0.5);

    const summaryData = [
      ['Total Tracks', String(allTracks.length)],
      ['Active Licenses', String(activeLicenses)],
      ['Total Payments', String(allPayments.length)],
      ['Net Revenue (PLN)', (netRevenue / 100).toFixed(2)],
      ['Registered Users', String(allUsers.length)],
    ];

    summaryData.forEach(([key, val]) => {
      doc.fontSize(10).font('Helvetica-Bold').fillColor('#333').text(`  ${key}: `, { continued: true });
      doc.font('Helvetica').fillColor(accentColor).text(val);
    });

    doc.moveDown(1.5);
    doc.fontSize(14).font('Helvetica-Bold').fillColor(brandColor).text('License Status', { underline: true });
    doc.moveDown(0.5);

    const statusBreakdown = [
      ['Active', String(allLicenses.filter(l => l.status === 'active').length)],
      ['Expired', String(allLicenses.filter(l => l.status === 'expired').length)],
      ['Locked', String(allLicenses.filter(l => l.status === 'locked').length)],
      ['Removed', String(allLicenses.filter(l => l.status === 'removed').length)],
    ];

    statusBreakdown.forEach(([key, val]) => {
      doc.fontSize(10).font('Helvetica-Bold').fillColor('#333').text(`  ${key}: `, { continued: true });
      doc.font('Helvetica').fillColor(accentColor).text(val);
    });

    doc.moveDown(1.5);
    doc.fontSize(14).font('Helvetica-Bold').fillColor(brandColor).text('Recent Payments', { underline: true });
    doc.moveDown(0.5);

    const recentPayments = allPayments.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()).slice(0, 10);
    recentPayments.forEach(p => {
      const date = new Date(p.createdAt).toLocaleDateString('pl-PL');
      doc.fontSize(9).font('Helvetica').fillColor('#333').text(`  ${date}  |  ${(p.amount / 100).toFixed(2)} ${p.currency}  |  ${p.gateway.toUpperCase()}  |  ${p.status}`, { indent: 0 });
    });

    doc.moveDown(2);
    doc.fontSize(8).fillColor('#999').text('— End of Report —', { align: 'center' });

    doc.end();
  } catch (e: any) {
    res.status(500).json({ error: 'PDF generation failed: ' + e.message });
  }
}

export async function exportCSV(req: any, res: Response) {
  try {
    const [allPayments, allLicenses] = await Promise.all([
      db.select().from(payments),
      db.select().from(licenses),
    ]);

    const rows: string[] = ['Transaction ID,Amount,Currency,Gateway,Type,Status,License ID,Date'];
    allPayments.forEach(p => {
      rows.push(`"${p.id}","${(p.amount / 100).toFixed(2)}","${p.currency}","${p.gateway}","${p.transactionType}","${p.status}","${p.licenseId || ''}","${new Date(p.createdAt).toISOString()}"`);
    });

    const csv = '\uFEFF' + rows.join('\r\n');
    res.setHeader('Content-Type', 'text/csv; charset=utf-8');
    res.setHeader('Content-Disposition', `attachment; filename=hrl_payments_${Date.now()}.csv`);
    res.send(csv);
  } catch (e: any) {
    res.status(500).json({ error: 'CSV generation failed: ' + e.message });
  }
}
