<template>
    <div>
        <div style="display:flex;justify-content:space-between;width:100%;border-bottom:1px solid #f1f5f9;padding:10px;">
            <div style="padding-right:20px;">
                {{ name }}
            </div>
            <div>
                {{ percentage }}%
            </div>
        </div>
        <div style="padding:10px;">
            <div style="display:flex;" v-for="message in messages" v-bind:key="message">
                <div>
                    <input type="checkbox" disabled style="margin-right:5px;">
                </div>
                <div v-html="message">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['name', 'percentage', 'messages'],
    components: {
    },
}
</script>
