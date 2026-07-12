// DEPRECATED: Sentry is initialized via src/instrument.ts using the official @sentry/node SDK.
// This file was a duplicate custom implementation. All error reporting is handled
// by the official Sentry SDK. See server.ts and errorHandler.ts for usage.
export const monitor = {
  captureException: (_error: any, _requestContext?: any) => {
    // No-op — use official Sentry SDK
  },
};
