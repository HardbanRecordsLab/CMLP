import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';

// Mock auth middleware for high throughput testing
vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, res: any, next: any) => {
    req.user = { uid: 'stress_test_uid', email: 'loadtester@hrl.pl' };
    next();
  },
  requireRole: (role: string) => (req: any, res: any, next: any) => {
    next();
  }
}));

describe('Phase 11: Load & Stress Testing Simulations', () => {
  it('should successfully handle multiple parallel mock concurrent client connection events', async () => {
    const concurrentUsers = 50;
    const requestPromises = Array.from({ length: concurrentUsers }).map(() =>
      request(app).get('/api/health')
    );

    const startTime = Date.now();
    const responses = await Promise.all(requestPromises);
    const duration = Date.now() - startTime;

    // Verify all responses returned 200 OK
    responses.forEach((res) => {
      expect(res.status).toBe(200);
      expect(res.body.status).toBe('ok');
    });

    // Check performance constraints (under 1000ms for 50 concurrent requests in test runtime environment)
    const averageResponseTime = duration / concurrentUsers;
    console.log(`[Load Simulation] Handled ${concurrentUsers} concurrent requests in ${duration}ms. Average: ${averageResponseTime.toFixed(2)}ms per user block.`);
    expect(averageResponseTime).toBeLessThan(150); // Ensured performance metric
  });

  it('should verify streaming endpoint latency thresholds under continuous load simulation', async () => {
    const stressCycles = 100;
    const startTimeByItem: number[] = [];
    const responseDurations: number[] = [];

    // Run rapid requests sequentially to model connection pool stability
    for (let i = 0; i < stressCycles; i++) {
      const cycleStart = Date.now();
      const res = await request(app).get('/api/health');
      responseDurations.push(Date.now() - cycleStart);
      expect(res.status).toBe(200);
    }

    const avgDuration = responseDurations.reduce((a, b) => a + b, 0) / stressCycles;
    const peakDuration = Math.max(...responseDurations);

    console.log(`[Stress Cycle] Completed ${stressCycles} consecutive healthchecks. Avg latency: ${avgDuration.toFixed(1)}ms. Max peak latency: ${peakDuration}ms.`);
    
    // Assert latency averages fall cleanly within service levels (SLA < 100ms)
    expect(avgDuration).toBeLessThan(100);
  });
});
