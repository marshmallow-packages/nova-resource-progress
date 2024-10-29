<template>
   <DefaultField
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
        <ProgressBar :resource="resource" :resourceName="resourceName" :resourceId="resourceId" :field="field"/>
        <NoDataAvailable v-if="!field.value" :label="field.no_data_label"/>
    </template>
 </DefaultField>
</template>

<script>

import ProgressBar from "./ProgressBar.vue";
import NoDataAvailable from "./NoDataAvailable.vue";

export default {
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        ProgressBar,
        NoDataAvailable,
    },
}
</script>
