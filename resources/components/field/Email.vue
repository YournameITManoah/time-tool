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
        errors?: string
        icon?: string
        label?: string
        required?: boolean
    }>(),
    {
        errors: undefined,
        icon: 'mdi-email-outline',
        label: undefined,
        required: true,
    },
)

const model = defineModel<null | string>()
</script>
