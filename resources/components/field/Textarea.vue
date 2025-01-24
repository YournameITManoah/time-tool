<template>
    <v-textarea
        v-model="model"
        :label="label"
        :counter="max"
        :rules="rules"
        clearable
        auto-grow
        rows="1"
    />
</template>
<script lang="ts" setup>
defineOptions({ name: 'FieldEmail' })

const { isRequired, maxLength } = useValidation()
const props = defineProps<{
    label?: string
    max?: number
    required?: boolean
}>()

const model = defineModel<null | string>()
const rules = computed(() => {
    return [
        props.max ? maxLength(props.max) : null,
        props.required ? isRequired : null,
    ].filter((r) => r !== null)
})
</script>
