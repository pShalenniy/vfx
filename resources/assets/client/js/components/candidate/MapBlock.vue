<template>
  <div id="map-block">
    <div v-if="candidate.city?.longitude && candidate.city?.latitude">
      <l-map
        :center="cityCenter"
        :zoom="zoom"
        :options="{ attributionControl: false }"
        class="map"
        @update:zoom="zoomUpdated"
        @update:center="centerUpdated"
      >
        <l-tile-layer :url="mapLayerUrl" />
        <l-marker :lat-lng="cityCenter">
          <l-icon
            icon-url="/images/client/icons/map-marker.png"
            :icon-size="iconSize"
            opacity="1.2"
          />
        </l-marker>
      </l-map>
      <div class="profile-grid-card-rollover">
        <div v-if="candidate.city || candidate.country">
          <p>
            {{ $t('client.candidate.content_section.location') }}
            {{ getCurrentLocation(candidate.city?.name, candidate.country?.name) }}
          </p>
        </div>
        <div v-if="candidate.timezone">
          <p>
            {{ $t('client.candidate.content_section.timezone.label') }}
            {{ candidate.timezone.name }} - {{ candidate.timezone.offset }}
          </p>
        </div>
      </div>
    </div>
    <div v-else class="profile-grid-card card background-blue text-center">
      <div class="card text-white">
        <h1 class="h1">
          <b>
            {{ $t('client.candidate.content_section.location') }}
          </b>
        </h1>
        <h2 class="h2">
          {{ getCurrentLocation(candidate.city?.name, candidate.country?.name, candidate.region?.name) }}
        </h2>
      </div>
    </div>
  </div>
</template>

<script>
import { latLng } from 'leaflet';
import { LIcon, LMap, LMarker, LTileLayer } from 'vue2-leaflet';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';

export default {
  components: {
    LIcon,
    LMap,
    LMarker,
    LTileLayer,
  },

  mixins: [
    hasLocationOptions,
  ],

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      // @link https://wiki.openstreetmap.org/wiki/Raster_tile_providers
      mapLayerUrl: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
      zoom: 4,
      iconSize: [20, 20],
    };
  },

  computed: {
    cityCenter() {
      if (!this.isEmpty(this.candidate?.city)) {
        return latLng(
          this.candidate.city?.latitude,
          this.candidate.city?.longitude,
        );
      }

      return null;
    },
  },

  methods: {
    zoomUpdated(zoom) {
      this.zoom = zoom;
    },

    centerUpdated(center) {
      this.center = center;
    },
  },
};
</script>
