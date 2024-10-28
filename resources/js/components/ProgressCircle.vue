<template>
    <svg
        :style="{ 'width': circle_size + 'px', 'height': circle_size + 'px', '--progress': percentage, '--size': circle_size + 'px', '--stroke-width': stroke_width + 'px' }"
        class="circular-progress">
        <circle class="bg"></circle>
        <circle class="fg" :style="{'stroke': helpers.calculateProgressForegroundColor(color_ranges, percentage)}"></circle>
    </svg>
</template>

<script>
import helpers from "../helpers.js";
export default {
    props: ['circle_size', 'percentage', 'color_ranges', 'stroke_width'],
    data() {
        return {
            helpers: helpers
        }
    },
}
</script>
