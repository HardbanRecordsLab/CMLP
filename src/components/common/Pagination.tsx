import React from 'react'
import { ChevronLeft, ChevronRight } from 'lucide-react'

interface PaginationProps {
  page: number
  totalPages: number
  onPageChange: (page: number) => void
}

export default function Pagination({ page, totalPages, onPageChange }: PaginationProps) {
  if (totalPages <= 1) return null
  return (
    <div className="flex items-center justify-center gap-2 mt-4">
      <button
        onClick={() => onPageChange(page - 1)}
        disabled={page <= 1}
        className="p-2 rounded-lg bg-slate-800 text-slate-300 disabled:opacity-30 hover:bg-slate-700 transition-colors"
        aria-label="Previous page"
      >
        <ChevronLeft size={18} />
      </button>
      {Array.from({ length: totalPages }, (_, i) => i + 1)
        .filter(p => p === 1 || p === totalPages || Math.abs(p - page) <= 2)
        .map((p, idx, arr) => (
          <React.Fragment key={p}>
            {idx > 0 && arr[idx - 1] !== p - 1 && <span className="text-slate-500">...</span>}
            <button
              onClick={() => onPageChange(p)}
              className={`px-3 py-1.5 rounded-lg text-sm font-medium transition-colors ${
                p === page
                  ? 'bg-indigo-600 text-white'
                  : 'bg-slate-800 text-slate-300 hover:bg-slate-700'
              }`}
            >
              {p}
            </button>
          </React.Fragment>
        ))}
      <button
        onClick={() => onPageChange(page + 1)}
        disabled={page >= totalPages}
        className="p-2 rounded-lg bg-slate-800 text-slate-300 disabled:opacity-30 hover:bg-slate-700 transition-colors"
        aria-label="Next page"
      >
        <ChevronRight size={18} />
      </button>
    </div>
  )
}
