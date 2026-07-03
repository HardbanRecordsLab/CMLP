import { Request, Response, NextFunction } from 'express';

export interface FieldRule {
  type: 'string' | 'number' | 'boolean' | 'array' | 'object';
  required?: boolean;
  min?: number;
  max?: number;
  pattern?: RegExp;
  enum?: readonly string[];
  message?: string;
}

export interface ValidationSchema {
  [field: string]: FieldRule | ValidationSchema;
}

export type ValidationTarget = 'body' | 'params' | 'query';

export interface ValidationConfig {
  body?: ValidationSchema;
  params?: ValidationSchema;
  query?: ValidationSchema;
}

function isFieldRule(rule: FieldRule | ValidationSchema): rule is FieldRule {
  return 'type' in rule;
}

function validateField(field: string, value: unknown, rules: FieldRule): string | null {
  const label = rules.message || field;

  if (rules.required && (value === undefined || value === null || value === '')) {
    return `${label} is required`;
  }

  if (value === undefined || value === null) {
    return null;
  }

  const actualType = Array.isArray(value) ? 'array' : typeof value;

  if (actualType === 'string' && rules.type !== 'string' && rules.type !== 'array') {
    return `${label} must be a ${rules.type}`;
  }

  if (rules.type === 'number') {
    const num = typeof value === 'string' ? Number(value) : value;
    if (typeof num !== 'number' || isNaN(num)) {
      return `${label} must be a number`;
    }
  }

  if (rules.type === 'array' && !Array.isArray(value)) {
    return `${label} must be an array`;
  }

  if (rules.type === 'string' && typeof value === 'string') {
    if (rules.min !== undefined && value.length < rules.min) {
      return `${label} must be at least ${rules.min} characters`;
    }
    if (rules.max !== undefined && value.length > rules.max) {
      return `${label} must be at most ${rules.max} characters`;
    }
    if (rules.pattern && !rules.pattern.test(value)) {
      return `${label} format is invalid`;
    }
    if (rules.enum && !rules.enum.includes(value)) {
      return `${label} must be one of: ${rules.enum.join(', ')}`;
    }
  }

  if (rules.type === 'number' && typeof value === 'number') {
    if (rules.min !== undefined && value < rules.min) {
      return `${label} must be at least ${rules.min}`;
    }
    if (rules.max !== undefined && value > rules.max) {
      return `${label} must be at most ${rules.max}`;
    }
  }

  return null;
}

function validateSchema(data: Record<string, unknown>, schema: ValidationSchema, path = ''): Record<string, string[]> {
  const errors: Record<string, string[]> = {};

  for (const [key, rules] of Object.entries(schema)) {
    const fieldPath = path ? `${path}.${key}` : key;

    if (!isFieldRule(rules)) {
      const nested = validateSchema(
        (data[key] as Record<string, unknown>) || {},
        rules,
        fieldPath,
      );
      for (const [nk, nv] of Object.entries(nested)) {
        errors[nk] = nv;
      }
      continue;
    }

    if (rules.required && (data[key] === undefined || data[key] === null || data[key] === '')) {
      if (!errors[fieldPath]) errors[fieldPath] = [];
      errors[fieldPath].push(`${rules.message || key} is required`);
      continue;
    }

    if (data[key] === undefined || data[key] === null) continue;

    const error = validateField(fieldPath, data[key], rules);
    if (error) {
      if (!errors[fieldPath]) errors[fieldPath] = [];
      errors[fieldPath].push(error);
    }
  }

  return errors;
}

export function validate<T = any>(config: ValidationConfig) {
  return (req: Request, res: Response, next: NextFunction): void => {
    const errors: Record<string, string[]> = {};

    for (const target of ['body', 'params', 'query'] as ValidationTarget[]) {
      const schema = config[target];
      if (!schema) continue;
      const data = (req[target] || {}) as Record<string, unknown>;
      const targetErrors = validateSchema(data, schema);
      for (const [k, v] of Object.entries(targetErrors)) {
        errors[k] = v;
      }
    }

    if (Object.keys(errors).length > 0) {
      res.status(400).json({ error: 'Validation failed', details: errors });
      return;
    }

    next();
  };
}
