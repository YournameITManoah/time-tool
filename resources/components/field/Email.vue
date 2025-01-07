<template>
    <v-text-field
        v-model="model"
        :error-messages="errors"
        :label="label ? label : t('Email')"
        :prepend-inner-icon="icon"
        autocomplete="username"
        :rules="required ? [isRequired] : []"
        type="email"
    />
</template>
<script lang="ts" setup>
defineOptions({ name: 'FieldEmail' })

const { t } = useI18n()
const { isRequired } = useValidation()

withDefaults(
    defineProps<{
        label?: string
        icon?: string
        errors?: string
        required?: boolean
    }>(),
    {
        label: undefined,
        icon: 'mdi-email-outline',
        errors: undefined,
        required: true,
    },
)

const model = defineModel<string | null>()
</script>
