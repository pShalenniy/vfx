<template>
  <div id="candidate-skill-circles-block">
    <b-row>
      <b-col cols="10" offset-md="2">
        <v-stage ref="stage" :config="stageSize">
          <v-layer ref="layer">
            <v-circle :config="config.circles.technical" />
            <v-circle :config="config.circles.creative" />
            <v-circle :config="config.circles.production" />

            <v-text :config="config.text.technical" />
            <v-text :config="config.text.creative " />
            <v-text :config="config.text.production" />

            <v-image
              :config="{
                x: picture.x,
                y: picture.y,
                width: 45,
                height: 45,
                image: picture.path,
                draggable: draggable,
                cornerRadius: 20,
              }"
              @dragmove="coordinate"
              @dragend="setPictureCoordinate"
            />
          </v-layer>
        </v-stage>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  props: {
    candidatePicture: {
      type: String,
      required: true,
    },
    skillCircles: {
      type: Object,
      required: true,
    },
    draggable: {
      type: Boolean,
      required: true,
    },
  },

  data() {
    return {
      stageSize: {
        width: 350,
        height: 250,
      },
      config: {
        circles: {
          technical: {
            x: 140,
            y: 80,
            radius: 80,
            fill: '#FFEA20',
            opacity: 0.8,
          },
          creative: {
            x: 180,
            y: 140,
            radius: 80,
            fill: '#000080',
            opacity: 0.8,
          },
          production: {
            x: 110,
            y: 140,
            radius: 80,
            fill: '#32CD32',
            opacity: 0.8,
          },
        },
        text: {
          technical: {
            x: 100,
            y: 75,
            text: this.$t('common.candidate.skill_circles.technical'),
            fontSize: 20,
            fontFamily: 'Calibri',
            fill: '#FFEA00',
            opacity: 0.9,
          },
          creative: {
            x: 70,
            y: 135,
            text: this.$t('common.candidate.skill_circles.creative'),
            fontSize: 20,
            fontFamily: 'Calibri',
            fill: '#6600cc',
            opacity: 0.9,
          },
          production: {
            x: 150,
            y: 135,
            text: this.$t('common.candidate.skill_circles.production'),
            fontSize: 20,
            fontFamily: 'Calibri',
            fill: '#00f0ff',
            opacity: 0.9,
          },
        },
      },
      picture: {
        x: 0,
        y: 200,
        path: null,
      },
      candidatePicturePosition: [],
    };
  },

  created() {
    this.drawCandidatePicture();
  },

  methods: {
    coordinate(event) {
      const offset = 40;

      const limitX = this.config.circles.creative.x + offset;
      const limitY = this.config.circles.production.y + offset;

      if (event.target.attrs.x > limitX) {
        event.target.attrs.x = limitX;
      }

      if (event.target.attrs.y > limitY) {
        event.target.attrs.y = limitY;
      }

      if (event.target.attrs.x < 0) {
        event.target.attrs.x = 0;
      }

      if (event.target.attrs.y < 0) {
        event.target.attrs.y = 0;
      }
    },

    drawCandidatePicture() {
      const picture = new Image();

      picture.src = this.candidatePicture;

      picture.onload = () => {
        this.picture.path = picture;
      };

      if (!this.isEmpty(this.skillCircles)) {
        this.picture.x = this.skillCircles?.x ? Number(this.skillCircles?.x) : 0;
        this.picture.y = this.skillCircles?.y ? Number(this.skillCircles?.y) : 200;
      }
    },

    setPictureCoordinate(event) {
      this.candidatePicturePosition = {
        x: event.target.attrs.x,
        y: event.target.attrs.y,
      };

      this.$emit('submit', this.candidatePicturePosition);
    },
  },
};
</script>
