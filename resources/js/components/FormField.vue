<template>
   <DefaultField
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
        <ProgressBar :resource="resource" :resourceName="resourceName" :resourceId="resourceId" :field="field"/>
    </template>
 </DefaultField>
</template>

<script>

import ProgressBar from "./ProgressBar.vue";

export default {
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        ProgressBar,
    },
}
</script>
