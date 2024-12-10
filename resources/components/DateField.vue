<template>
    <v-date-input v-model="local" />
</template>
<script setup lang="ts">
const local = ref<Date | null>(null)
const model = defineModel<string | null>({ required: true })

watchImmediate(local, (val) => {
    model.value = val ? val.toLocaleString() : null
})
</script>
