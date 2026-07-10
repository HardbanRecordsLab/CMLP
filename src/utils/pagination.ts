import { sql, ilike, or, and, asc, desc, type SQL } from 'drizzle-orm';
import { type PgTable } from 'drizzle-orm/pg-core';
import { db } from '../db/index.ts';

export interface PaginationParams {
  page: number;
  limit: number;
  search?: string;
  sortBy?: string;
  sortOrder?: 'asc' | 'desc';
}

export interface PaginatedResult<T> {
  data: T[];
  pagination: {
    page: number;
    limit: number;
    total: number;
    totalPages: number;
  };
}

export function parsePagination(query: any, defaultLimit = 20): PaginationParams {
  const page = Math.max(1, parseInt(query.page as string, 10) || 1);
  const limit = Math.min(100, Math.max(1, parseInt(query.limit as string, 10) || defaultLimit));
  const search = (query.search as string)?.trim() || undefined;
  const sortBy = (query.sortBy as string) || undefined;
  const sortOrder = query.sortOrder === 'asc' ? 'asc' : 'desc';
  return { page, limit, search, sortBy, sortOrder };
}

export function buildSearchCondition(
  search: string | undefined,
  searchableColumns: string[]
): SQL | undefined {
  if (!search) return undefined;
  const conditions = searchableColumns.map(col =>
    ilike(sql.raw(`"${col}"`), `%${search}%`)
  );
  return or(...conditions);
}

export function buildOrderBy(
  sortBy: string | undefined,
  sortOrder: 'asc' | 'desc',
  validColumns: string[],
  table: any,
  defaultSort: string = 'id'
) {
  const column = sortBy && validColumns.includes(sortBy) ? sortBy : defaultSort;
  return sortOrder === 'asc' ? asc(table[column]) : desc(table[column]);
}

export async function paginateQuery<T>(
  table: PgTable,
  conditions: (SQL | undefined)[],
  params: PaginationParams,
  validSortColumns: string[],
  defaultSort = 'id'
): Promise<PaginatedResult<T>> {
  const where = and(...conditions.filter(Boolean));
  const offset = (params.page - 1) * params.limit;
  const orderBy = buildOrderBy(params.sortBy, params.sortOrder, validSortColumns, table, defaultSort);

  const countResult = await db.select({ count: sql<number>`count(*)` }).from(table).where(where);
  const total = Number(countResult[0]?.count || 0);

  const data = await (db.select() as any)
    .from(table)
    .where(where)
    .orderBy(orderBy)
    .limit(params.limit)
    .offset(offset);

  return {
    data: data as T[],
    pagination: {
      page: params.page,
      limit: params.limit,
      total,
      totalPages: Math.ceil(total / params.limit),
    },
  };
}
