<template>
    <v-date-input v-model="local" />
</template>
<script setup lang="ts">
const local = ref<Date | null>(null)
const model = defineModel<string | null>({ required: true })

watch(local, (val) => {
    model.value = val ? val.toLocaleString() : null
})

watchImmediate(model, (val) => {
    local.value = val ? new Date(val) : null
})
</script>
