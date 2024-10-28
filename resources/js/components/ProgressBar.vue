<template>
    <div>
        <div style="position:relative;" v-for="(suite_settings, suite) in field.suites" v-bind:key="suite">
        <Menu>
            <div style="border:1px solid #f1f5f9;border-radius:10px;padding:10px;margin-bottom:10px;" :style="{'cursor': field.value[suite].percentage != 100 ? 'pointer' : 'inherit' }">
                <div style="display:flex;justify-content:space-between;width:100%;">
                    <div>
                        {{ suite_settings.name }}
                    </div>
                    <div>
                        {{ field.value[suite].percentage }}%
                    </div>
                </div>
                <div class="progress" :style="{'height': field.progress_bar_height + 'px'}">
                    <div class="progress-value" :style="{'width': field.value[suite].percentage + '%', 'background': helpers.calculateProgressForegroundColor(field.color_ranges, field.value[suite].percentage), 'box-shadow': '0 10px 40px -10px ' + helpers.calculateProgressForegroundColor(field.color_ranges, field.value[suite].percentage), 'height': (field.progress_bar_height / 100) * 75 + 'px'}"></div>
                </div>
            </div>

            <template #popper v-if="value[suite].percentage != 100">
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
import {
  Menu,
} from 'floating-vue'
import ProgressTooltip from "./ProgressTooltip.vue";
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  components: {
        Menu,
        ProgressTooltip,
    },

    data() {
        return {
            helpers: helpers
        }
    },

  props: ['resourceName', 'resourceId', 'field'],

    mounted() {
        console.log(this.field.suites);
        console.log(this.field);
    },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.fieldAttribute, this.value || '')
    },
  },
}
</script>
<style>
.progress {
    background: rgba(255,255,255,0.1);
    justify-content: flex-start;
    border-radius: 100px;
    align-items: center;
    position: relative;
    padding: 0 5px;
    display: flex;
    width: 100%;
}

.progress-value {
    border-radius: 100px;
    background: red;
    height: 30px;
}
</style>
