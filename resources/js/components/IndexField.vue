<template>
    <div style="display:flex;justify-items:center;gap: 0.25rem;">

        <div style="position:relative;" v-for="(suite_settings, suite) in field.suites" v-bind:key="suite">
            <Menu v-if="field.value && field.value[suite]">
                <ProgressCircle :circle_size="field.circle_size" :percentage="field.value[suite].percentage" :color_ranges="field.color_ranges" :stroke_width="field.stroke_width"/>

                <template #popper v-if="field.value[suite].percentage != 100">
                    <ProgressTooltip
                        :name="suite_settings.name"
                        :percentage="field.value[suite].percentage"
                         :messages="field.value[suite].messages"/>
                </template>
            </Menu>
        </div>
    </div>
</template>

<script>
import helpers from "../helpers.js";
import ProgressTooltip from "./ProgressTooltip.vue";
import ProgressCircle from "./ProgressCircle.vue";

import {
  vTooltip,
  Menu,
} from 'floating-vue'

export default {
    props: ['resourceName', 'field'],
    components: {
        vTooltip,
        Menu,
        ProgressTooltip,
        ProgressCircle,
    },

    data() {
        return {
            helpers: helpers
        }
    },

    mounted() {
        // console.log(this.field);
    },

    computed: {
        fieldValue() {
            return this.field.displayedAs || this.field.value
        },
    }
}
</script>

<style>
.circular-progress {
  --half-size: calc(var(--size) / 2);
  --radius: calc((var(--size) - var(--stroke-width)) / 2);
  --circumference: calc(var(--radius) * pi * 2);
  --dash: calc((var(--progress) * var(--circumference)) / 100);
}

.circular-progress circle {
  cx: var(--half-size);
  cy: var(--half-size);
  r: var(--radius);
  stroke-width: var(--stroke-width);
  fill: none;
  stroke-linecap: round;
}

.circular-progress circle.bg {
  stroke: #ddd;
}

.circular-progress circle.fg {
  transform: rotate(-90deg);
  transform-origin: var(--half-size) var(--half-size);
  stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
  transition: stroke-dasharray 0.3s linear 0s;
}
</style>
