import { describe, it, expect, vi, beforeEach } from 'vitest';
import { reqLang, pick } from '../../src/utils/requestLang.ts';

describe('request language (hrl_lang cookie)', () => {
  const req = (cookie?: string) => ({ headers: cookie === undefined ? {} : { cookie } }) as any;

  it('defaults to English without the cookie', () => {
    expect(reqLang(req())).toBe('en');
    expect(reqLang(req('a=b; c=d'))).toBe('en');
  });

  it('reads pl and en from the shared cookie', () => {
    expect(reqLang(req('x=1; hrl_lang=pl; y=2'))).toBe('pl');
    expect(reqLang(req('hrl_lang=en'))).toBe('en');
  });

  it('ignores unknown values and lookalike names', () => {
    expect(reqLang(req('hrl_lang=de'))).toBe('en');
    expect(reqLang(req('not_hrl_lang=pl'))).toBe('en');
  });

  it('pick returns the message for the request language', () => {
    expect(pick(req('hrl_lang=pl'), 'cześć', 'hello')).toBe('cześć');
    expect(pick(req(), 'cześć', 'hello')).toBe('hello');
  });
});

// ── certificate PDF follows the request language ─────────────────────────────
const texts: string[] = [];
vi.mock('pdfkit', () => {
  class FakeDoc {
    pipe() { return this; }
    fontSize() { return this; }
    fillColor() { return this; }
    font() { return this; }
    moveDown() { return this; }
    end() { return this; }
    text(t: string) { texts.push(String(t)); return this; }
  }
  return { default: FakeDoc };
});
vi.mock('../../src/db/index.ts', () => {
  const rows = (table: unknown) => (table === 'contracts' ? [{ signed: true }] : [{
    id: 1, certificateNumber: 'CMLP-TEST-1', licenseType: 'standard', companyName: 'ACME', jurisdiction: 'PL',
    issuedAt: new Date('2026-01-01'), expiresAt: new Date('2027-01-01'), status: 'active', authorUid: 'u1',
  }]);
  const chain = (table: unknown) => ({ where: () => Promise.resolve(rows(table)) });
  return { db: { select: () => ({ from: (t: unknown) => chain(t) }) } };
});
vi.mock('../../src/db/schema.ts', () => ({ licenses: 'licenses', contracts: 'contracts', users: 'users' }));
vi.mock('drizzle-orm', () => ({ eq: () => ({}), and: () => ({}), desc: () => ({}), sql: () => ({}) }));
vi.mock('../../src/services/logging.service.ts', () => ({ logAuditEvent: vi.fn() }));
vi.mock('../../src/services/webhook-delivery.service.ts', () => ({ emitWebhookEvent: vi.fn() }));
vi.mock('../../src/utils/pagination.ts', () => ({ parsePagination: vi.fn(), buildSearchCondition: vi.fn(), paginateQuery: vi.fn() }));

describe('licence certificate PDF', () => {
  beforeEach(() => { texts.length = 0; });

  const run = async (cookie?: string) => {
    const { getPdf } = await import('../../src/controllers/licenses.controller.ts');
    const res: any = { setHeader: vi.fn(), status: vi.fn().mockReturnThis(), json: vi.fn(), headersSent: false };
    await getPdf({ params: { id: '1' }, user: { uid: 'u1', role: 'admin' }, headers: cookie ? { cookie } : {} }, res);
    return texts.join('\n');
  };

  it('is in English by default', async () => {
    const out = await run();
    expect(out).toContain('LICENCE CERTIFICATE');
    expect(out).toContain('Issue date');
    expect(out).not.toContain('CERTYFIKAT LICENCYJNY');
  });

  it('is in Polish for hrl_lang=pl', async () => {
    const out = await run('hrl_lang=pl');
    expect(out).toContain('CERTYFIKAT LICENCYJNY');
    expect(out).toContain('Data wydania');
  });
});
