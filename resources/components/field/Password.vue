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
        hint: 'current-password' | 'new-password' | 'cc-number' | 'cc-csc'
        label?: string
        icon?: string
        errors?: string
        required?: boolean
    }>(),
    {
        label: undefined,
        icon: 'mdi-lock-outline',
        errors: undefined,
        required: true,
    },
)

const model = defineModel<string | null>()
const showPassword = ref(false)
</script>
