<template>
  <PanelItem :index="index" :field="field">
    <template #value>
        <ProgressBar :resource="resource" :resourceName="resourceName" :resourceId="resourceId" :field="field"/>
        <NoDataAvailable v-if="!field.value" :label="field.no_data_label"/>
    </template>
  </PanelItem>
</template>

<script>
import ProgressBar from "./ProgressBar.vue";
import NoDataAvailable from "./NoDataAvailable.vue";

export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ProgressBar,
        NoDataAvailable,
    }
}
</script>
