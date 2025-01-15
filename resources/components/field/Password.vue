<template>
    <v-text-field
        v-model="model"
        :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
        :autocomplete="hint"
        :error-messages="errors"
        :label="label ? label : t('Password')"
        :prepend-inner-icon="icon"
        :rules="required ? [isRequired] : []"
        :type="showPassword ? 'text' : 'password'"
        @click:append-inner="showPassword = !showPassword"
    />
</template>
<script lang="ts" setup>
defineOptions({ name: 'FieldPassword' })

const { t } = useI18n()
const { isRequired } = useValidation()

withDefaults(
    defineProps<{
        errors?: string
        hint: 'cc-csc' | 'cc-number' | 'current-password' | 'new-password'
        icon?: string
        label?: string
        required?: boolean
    }>(),
    {
        errors: undefined,
        icon: 'mdi-lock-outline',
        label: undefined,
        required: true,
    },
)

const model = defineModel<null | string>()
const showPassword = ref(false)
</script>
