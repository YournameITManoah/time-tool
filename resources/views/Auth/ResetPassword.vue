<template layout="guest">
    <div>
        <v-card-title>{{ t('Reset Password') }}</v-card-title>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <field-email
                            v-model="form.fields.email"
                            :errors="form.errors.email"
                            readonly
                        />
                    </v-col>
                    <v-col cols="12">
                        <field-password
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="new-password"
                        />
                    </v-col>
                    <v-col cols="12">
                        <field-password
                            v-model="form.fields.password_confirmation"
                            :errors="form.errors.password_confirmation"
                            hint="new-password"
                            :label="t('Password Confirmation')"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                            :text="t('Reset Password')"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '@/types'

const { t } = useI18n()

useHead({ title: t('Reset Password') })

const props = defineProps<{
    email: string
    token: string
}>()

const form = useForm({
    fields: {
        email: props.email,
        password: '',
        password_confirmation: '',
        token: props.token,
    },
    url: route('password.store'),
})

const formRef = ref<null | VFormRef>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
