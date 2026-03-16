/**
 * FNB Outlet Create Script
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const mapElement = document.getElementById('outletMap');
  const latitudeInput = document.getElementById('latitude');
  const longitudeInput = document.getElementById('longitude');

  if (!mapElement || !latitudeInput || !longitudeInput || typeof window.leaFlet === 'undefined') {
    return;
  }

  const L = window.leaFlet;

  const defaultLatitude = -6.2;
  const defaultLongitude = 106.816666;

  const initialLatitude = parseFloat(latitudeInput.value);
  const initialLongitude = parseFloat(longitudeInput.value);

  const startLatitude = Number.isFinite(initialLatitude) ? initialLatitude : defaultLatitude;
  const startLongitude = Number.isFinite(initialLongitude) ? initialLongitude : defaultLongitude;

  const map = L.map(mapElement).setView([startLatitude, startLongitude], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const marker = L.marker([startLatitude, startLongitude], {
    draggable: true
  }).addTo(map);

  function updateCoordinateInputs(latlng) {
    latitudeInput.value = latlng.lat.toFixed(6);
    longitudeInput.value = latlng.lng.toFixed(6);
  }

  updateCoordinateInputs(marker.getLatLng());

  map.on('click', function (event) {
    marker.setLatLng(event.latlng);
    updateCoordinateInputs(event.latlng);
  });

  marker.on('dragend', function () {
    updateCoordinateInputs(marker.getLatLng());
  });

  function updateMarkerFromInputs() {
    const latitude = parseFloat(latitudeInput.value);
    const longitude = parseFloat(longitudeInput.value);

    if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
      return;
    }

    const latlng = L.latLng(latitude, longitude);
    marker.setLatLng(latlng);
    map.panTo(latlng);
  }

  latitudeInput.addEventListener('change', updateMarkerFromInputs);
  longitudeInput.addEventListener('change', updateMarkerFromInputs);
});
