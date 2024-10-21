<script lang="ts" setup>
withDefaults(
    defineProps<{
        label?: string
        icon?: string
        errors?: string
        required?: boolean
    }>(),
    {
        label: 'Email',
        icon: 'mdi-email-outline',
        errors: undefined,
        required: true,
    },
)

const model = defineModel<string | null>()
</script>

<template>
    <v-text-field
        v-model="model"
        :error-messages="errors"
        :label="label"
        :prepend-inner-icon="icon"
        autocomplete="username"
        :rules="required ? [isRequired] : []"
        type="email"
    />
</template>
