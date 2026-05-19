module.exports = {
  content: [
    './resources/views/**/*.php',
    './src/**/*.php',
    './public/assets/javascript/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0f172a',
        secondary: '#0f766e',
        background: '#f7f9fb',
        surface: '#f7f9fb',
        'surface-dim': '#d8dadc',
        'surface-bright': '#f7f9fb',
        'surface-container-lowest': '#ffffff',
        'surface-container-low': '#f2f4f6',
        'surface-container': '#eceef0',
        'surface-container-high': '#e6e8ea',
        'surface-container-highest': '#e0e3e5',
        'on-surface': '#191c1e',
        'on-surface-variant': '#45464d',
        outline: '#76777d',
        'outline-variant': '#c6c6cd',
        'secondary-container': '#ccfbf1',
        'on-secondary-container': '#115e59',
        'primary-fixed': '#e2e8f0',
        'primary-fixed-variant': '#475569',
        error: '#ba1a1a',
        'error-container': '#ffdad6',
        'on-error-container': '#93000a'
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif']
      },
      maxWidth: {
        'container-max': '1280px'
      },
      spacing: {
        gutter: '24px'
      },
      boxShadow: {
        soft: '0px 4px 20px rgba(15, 23, 42, 0.05)',
        float: '0px 10px 40px rgba(15, 23, 42, 0.08)'
      },
      borderRadius: {
        xl: '0.75rem',
        '2xl': '1.25rem'
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/container-queries')
  ]
};
