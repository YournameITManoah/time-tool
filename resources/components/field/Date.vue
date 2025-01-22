<template>
    <v-date-input v-model="local" hide-actions />
</template>
<script setup lang="ts">
defineOptions({ name: 'FieldDate' })

const local = ref<Date | null>(null)
const model = defineModel<null | string | undefined>({ required: true })

watch(local, (val) => {
    model.value = val ? val.toLocaleDateString('en-CA') : null
})

watchImmediate(model, (val) => {
    local.value = val ? new Date(val) : null
})
</script>
