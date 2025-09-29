/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './includes/**/*.php',
    './admin/**/*.php',
    './admin/includes/**/*.php',
    './js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1d4ed8',
        secondary: '#0f172a',
        surface: '#ffffff',
        muted: '#e2e8f0',
        panel: '#0f172a',
        panelMuted: '#1e293b',
        panelCard: '#13203d',
        panelAccent: '#38bdf8',
        panelDanger: '#ef4444',
      },
      boxShadow: {
        soft: '0 15px 30px -15px rgba(15, 23, 42, 0.25)',
        glow: '0 25px 60px -30px rgba(56, 189, 248, 0.7)',
      },
      fontFamily: {
        heading: ['DM Sans', 'Inter', 'sans-serif'],
        body: ['Inter', 'DM Sans', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
