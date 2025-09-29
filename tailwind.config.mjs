/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
	theme: {
		extend: {
			colors: {
				primary: '#1f2c3d',
				accent: '#c5a47e',
				white: '#ffffff',
				light: '#f8f9fa',
				text: {
					DEFAULT: '#333333',
					light: '#666666'
				}
			},
			fontFamily: {
				heading: ['Playfair Display', 'serif'],
				body: ['Lato', 'sans-serif'],
			},
			boxShadow: {
				card: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
				hover: '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
			}
		},
	},
	plugins: [],
}
