import * as L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const initMap = () => {
  const lat = -37.47005715557837;
  const lng = -72.35250437912185;

  const map = L.map('map').setView([lat, lng], 16);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19
  }).addTo(map);

  const customIcon = L.divIcon({
    html: `
      <div style="position: relative;">
        <svg width="40" height="50" viewBox="0 0 40 50" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 0C9 0 0 9 0 20C0 35 20 50 20 50C20 50 40 35 40 20C40 9 31 0 20 0Z" fill="#c5a47e"/>
          <circle cx="20" cy="20" r="8" fill="white"/>
        </svg>
      </div>
    `,
    className: 'custom-marker',
    iconSize: [40, 50],
    iconAnchor: [20, 50],
    popupAnchor: [0, -50]
  });

  const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

  marker.bindPopup(`
    <div style="text-align: center; font-family: sans-serif;">
      <strong style="color: #1f2c3d; font-size: 16px;">Abogados FL</strong><br>
      <span style="color: #666; font-size: 14px;">Valdivia #300 Oficina 505</span><br>
      <span style="color: #666; font-size: 14px;">Edificio Plaza Fundación</span><br>
      <span style="color: #666; font-size: 14px;">Los Ángeles, Biobío</span>
    </div>
  `).openPopup();

  setTimeout(() => map.invalidateSize(), 100);
};

// Run init when DOM is ready
if (typeof window !== 'undefined') {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMap);
  } else {
    initMap();
  }
}
