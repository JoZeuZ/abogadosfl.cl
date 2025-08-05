/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
	theme: {
		extend: {
			colors: {
				primary: '#1f2c3d',
				accent: '#c5a47e',
				light: '#f8f9fa',
			},
			fontFamily: {
				heading: ['Playfair Display', 'serif'],
				body: ['Lato', 'sans-serif'],
			},
		},
	},
	plugins: [],
}
