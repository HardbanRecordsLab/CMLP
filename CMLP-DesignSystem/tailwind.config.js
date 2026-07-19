/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./components/html/**/*.html",
    "./src/**/*.css",
    "./src/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: 'oklch(var(--p) / <alpha-value>)',
        secondary: 'oklch(var(--s) / <alpha-value>)',
        accent: 'oklch(var(--a) / <alpha-value>)',
        neutral: 'oklch(var(--n) / <alpha-value>)',
        'base-100': 'oklch(var(--b1) / <alpha-value>)',
        'base-200': 'oklch(var(--b2) / <alpha-value>)',
        'base-300': 'oklch(var(--b3) / <alpha-value>)',
        info: 'oklch(var(--in) / <alpha-value>)',
        success: 'oklch(var(--su) / <alpha-value>)',
        warning: 'oklch(var(--wa) / <alpha-value>)',
        error: 'oklch(var(--er) / <alpha-value>)',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      animation: {
        'fade-in': 'fadeIn 0.2s ease-in-out',
        'slide-up': 'slideUp 0.2s ease-out',
        'slide-down': 'slideDown 0.2s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [require('daisyui')],
  daisyui: {
    themes: [
      {
        'cmlp-light': {
          'primary': '#C8A96E',
          'primary-content': '#000000',
          'secondary': '#38bdf8',
          'secondary-content': '#ffffff',
          'accent': '#a855f7',
          'accent-content': '#ffffff',
          'neutral': '#1f2937',
          'neutral-content': '#ffffff',
          'base-100': '#ffffff',
          'base-200': '#f3f4f6',
          'base-300': '#e5e7eb',
          'base-content': '#1f2937',
          'info': '#3b82f6',
          'success': '#10b981',
          'warning': '#f59e0b',
          'error': '#ef4444',
        },
      },
      {
        'cmlp-dark': {
          'primary': '#C8A96E',
          'primary-content': '#000000',
          'secondary': '#38bdf8',
          'secondary-content': '#ffffff',
          'accent': '#a855f7',
          'accent-content': '#ffffff',
          'neutral': '#1f2937',
          'neutral-content': '#ffffff',
          'base-100': '#0a0a0a',
          'base-200': '#121212',
          'base-300': '#1a1a1a',
          'base-content': '#ffffff',
          'info': '#3b82f6',
          'success': '#10b981',
          'warning': '#f59e0b',
          'error': '#ef4444',
        },
      },
    ],
  },
}
