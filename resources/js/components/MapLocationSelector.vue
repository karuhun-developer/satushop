<script setup lang="ts">
import L, { LatLngExpression } from 'leaflet';
import { GeoSearchControl, OpenStreetMapProvider } from 'leaflet-geosearch';
import 'leaflet-geosearch/dist/geosearch.css';
import 'leaflet/dist/leaflet.css';
import { onMounted, ref, watch } from 'vue';

interface Props {
    initialLat?: number;
    initialLon?: number;
    mapHeight?: string;
    zoomLevel?: number;
}

interface Emits {
    (e: 'update:location', payload: { lat: number; lon: number }): void;
}

const props = withDefaults(defineProps<Props>(), {
    initialLat: -6.2088,
    initialLon: 106.8456,
    mapHeight: '400px',
    zoomLevel: 13,
});

const emit = defineEmits<Emits>();

const mapContainer = ref<HTMLDivElement | null>(null);
let map: L.Map | null = null;
let marker: L.Marker | null = null;

const initialLat = ref<number>(props.initialLat);
const initialLon = ref<number>(props.initialLon);

const latitude = ref<number>(props.initialLat);
const longitude = ref<number>(props.initialLon);

onMounted(() => {
    if (!mapContainer.value) return;

    // Initialize Leaflet map
    map = L.map(mapContainer.value).setView(
        [props.initialLat, props.initialLon],
        props.zoomLevel,
    );

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Add initial marker
    addMarker([props.initialLat, props.initialLon]);

    // Add search control
    const provider = new OpenStreetMapProvider();
    const searchControl = GeoSearchControl({
        provider,
        style: 'bar',
        autoCompleteDelay: 250,
        retainZoomLevel: false,
        animateZoom: true,
        showMarker: false,
    });

    map.addControl(searchControl);

    // Handle search results
    map.on('geosearch/showlocation', (result: any) => {
        const { x, y } = result.location;
        updateLocation(y, x);
    });

    // Handle map clicks to select location
    map.on('click', (e: L.LeafletMouseEvent) => {
        updateLocation(e.latlng.lat, e.latlng.lng);
    });
});

function addMarker(position: LatLngExpression) {
    if (marker) {
        map?.removeLayer(marker);
    }

    marker = L.marker(position, {
        draggable: true,
    }).addTo(map!);

    // Update location when marker is dragged
    marker.on('dragend', () => {
        const position = marker!.getLatLng();
        updateLocation(position.lat, position.lng);
    });
}

function updateLocation(lat: number, lon: number) {
    latitude.value = Math.round(lat * 1000000) / 1000000;
    longitude.value = Math.round(lon * 1000000) / 1000000;

    if (map) {
        map.setView([lat, lon], props.zoomLevel);
    }

    addMarker([lat, lon]);
    emit('update:location', { lat: latitude.value, lon: longitude.value });
}

function resetToInitial() {
    updateLocation(initialLat.value, initialLon.value);
}

watch(
    () => props.initialLat,
    (newLat) => {
        latitude.value = newLat;
    },
);

watch(
    () => props.initialLon,
    (newLon) => {
        longitude.value = newLon;
    },
);
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-col gap-4 lg:flex-row">
            <!-- Map Container -->
            <div class="flex-1">
                <div
                    ref="mapContainer"
                    class="rounded border border-border"
                    :style="{ height: mapHeight }"
                />
            </div>

            <!-- Coordinates Display -->
            <div class="flex flex-col gap-4 lg:w-64">
                <div class="rounded border border-border p-4">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium">Latitude</label>
                            <input
                                v-model.number="latitude"
                                type="number"
                                step="0.000001"
                                placeholder="Latitude"
                                class="mt-1 w-full rounded border border-border bg-background px-2 py-1 text-sm"
                                @change="updateLocation(latitude, longitude)"
                            />
                        </div>

                        <div>
                            <label class="text-sm font-medium">Longitude</label>
                            <input
                                v-model.number="longitude"
                                type="number"
                                step="0.000001"
                                placeholder="Longitude"
                                class="mt-1 w-full rounded border border-border bg-background px-2 py-1 text-sm"
                                @change="updateLocation(latitude, longitude)"
                            />
                        </div>

                        <button
                            @click="resetToInitial"
                            type="button"
                            class="w-full rounded bg-muted px-3 py-1 text-sm hover:bg-muted/80"
                        >
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="text-xs text-muted-foreground">
                    <p class="mb-2 font-medium">How to use:</p>
                    <ul class="list-inside list-disc space-y-1">
                        <li>Search location using the search bar</li>
                        <li>Click on map to select location</li>
                        <li>Drag marker to adjust position</li>
                        <li>Edit coordinates manually</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(.geosearch-bar) {
    border-radius: 0.5rem;
    border: 1px solid hsl(var(--border));
    background-color: hsl(var(--background));
}

:deep(.geosearch-bar input) {
    border-radius: 0.25rem;
    border: none;
    background-color: hsl(var(--background));
    color: hsl(var(--foreground));
}

:deep(.geosearch-bar button) {
    background-color: hsl(var(--primary));
    color: hsl(var(--primary-foreground));
}

:deep(.geosearch-bar button:hover) {
    opacity: 0.9;
}

:deep(.leaflet-control-geosearch.bar) {
    top: 0.75rem !important;
    left: 0.75rem !important;
}
</style>
