<template>
  <PanelItem :index="index" :field="field">
    <template #value>
        <ProgressBar :resource="resource" :resourceName="resourceName" :resourceId="resourceId" :field="field"/>
    </template>
  </PanelItem>
</template>

<script>
import ProgressBar from "./ProgressBar.vue";

export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ProgressBar,
    }
}
</script>
