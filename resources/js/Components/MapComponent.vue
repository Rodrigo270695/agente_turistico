<script setup>
import { onMounted, ref, nextTick, watch } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    initialLat: {
        type: Number,
        default: null, // Latitud inicial proporcionada
    },
    initialLng: {
        type: Number,
        default: null, // Longitud inicial proporcionada
    },
    height: {
        type: String,
        default: "240px", // Altura predeterminada del mapa
    },
    draggable: {
        type: Boolean,
        default: true, // Permitir arrastrar el marcador
    },
});

const emit = defineEmits(["update:lat", "update:lng", "update:duration", "update:distance"]);

const mapContainer = ref(null);
const mapHeight = ref(props.height);
let map = null;
let marker = null;
let routeLayer = null;

const fixedLat = -6.770983493921217; // Latitud fija para la catedral
const fixedLng = -79.83925580978395; // Longitud fija para la catedral

const initializeMap = async () => {
    map = L.map(mapContainer.value).setView(
        [fixedLat, fixedLng],
        13
    );
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Marcador estático inicial
    const initialMarker = L.marker([fixedLat, fixedLng]).addTo(map);

    // Marcador draggable
    marker = L.marker([props.initialLat || fixedLat, props.initialLng || fixedLng], {
        draggable: props.draggable,
    }).addTo(map);

    if (props.draggable) {
        marker.on("dragend", async () => {
            const { lat, lng } = marker.getLatLng();
            emit("update:lat", lat);
            emit("update:lng", lng);

            // Obtener la ruta desde el marcador inicial hasta el nuevo punto
            await drawRoute(initialMarker.getLatLng(), { lat, lng });
        });
    }

    // Dibujar la ruta inicial si se proporcionan coordenadas
    if (props.initialLat !== null && props.initialLng !== null) {
        await drawRoute(initialMarker.getLatLng(), { lat: props.initialLat, lng: props.initialLng });
    }

    // Forzar la actualización del tamaño del mapa
    map.invalidateSize();
};

const drawRoute = async (start, end) => {
    const response = await fetch(`https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`);
    const data = await response.json();
    const route = data.routes[0].geometry;
    const duration = data.routes[0].duration; // Duración en segundos
    const distance = data.routes[0].distance; // Distancia en metros

    // Emitir duración y distancia
    emit("update:duration", duration);
    emit("update:distance", distance);

    // Convertir duración a horas y minutos
    const hours = Math.floor(duration / 3600);
    const minutes = Math.floor((duration % 3600) / 60);

    // Convertir distancia a kilómetros
    const distanceKm = (distance / 1000).toFixed(2);

    // Crear contenido del popup
    const popupContent = `
        <b>Tiempo de viaje:</b> ${hours} horas y ${minutes} minutos<br>
        <b>Distancia:</b> ${distanceKm} km
    `;

    // Dibujar la ruta en el mapa
    if (routeLayer) {
        map.removeLayer(routeLayer);
    }
    routeLayer = L.geoJSON(route, {
        style: { color: 'blue' }
    }).addTo(map);

    // Mostrar el popup inmediatamente
    L.popup()
        .setLatLng([end.lat, end.lng])
        .setContent(popupContent)
        .openOn(map);
};

onMounted(async () => {
    await nextTick(); // Asegúrate de que el DOM esté completamente renderizado
    await initializeMap();
});

watch([() => props.initialLat, () => props.initialLng], () => {
    if (map && marker) {
        map.setView([props.initialLat || fixedLat, props.initialLng || fixedLng], 13);
        marker.setLatLng([props.initialLat || fixedLat, props.initialLng || fixedLng]);
    }
});
</script>

<template>
    <div ref="mapContainer" :style="{ height: mapHeight }"></div>
</template>

<style scoped>
/* Estilos adicionales para mejorar la apariencia */
body {
    font-family: 'Inter', sans-serif;
}
</style>
