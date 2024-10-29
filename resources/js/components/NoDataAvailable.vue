<template>
    <div class="no-data-available">
        {{ label }}
    </div>
</template>

<script>
export default {
     props: ['label'],
}
</script>
<style>
.no-data-available {
    background: #f1f5f9;
    border-radius: 10px;
    padding: 5px 10px;
    font-size: 13px;
    display: inline-block;
}
</style>
