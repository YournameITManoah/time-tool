<template layout="guest">
    <div>
        <v-card-title>{{ t('Login') }}</v-card-title>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <v-container>
                <v-row>
                    <v-col v-if="status" cols="12">
                        <v-alert :text="status" />
                    </v-col>
                    <v-col cols="12">
                        <field-email
                            v-model="form.fields.email"
                            :errors="form.errors.email"
                        />
                    </v-col>
                    <v-col cols="12">
                        <div
                            v-if="canResetPassword"
                            class="d-flex align-center justify-end"
                        >
                            <text-link
                                :href="route('password.request')"
                                class="text-caption"
                                :label="t('Forgot password?')"
                                rel="noopener noreferrer"
                                target="_blank"
                            />
                        </div>
                        <field-password
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="current-password"
                        />
                    </v-col>
                    <v-col class="pa-0" cols="12">
                        <v-checkbox
                            v-model="form.fields.remember"
                            :label="t('Remember me')"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                            :text="t('Login')"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-card-text class="text-center mt-4">
            <text-link
                :href="route('register')"
                :label="t('No account yet?')"
            />
        </v-card-text>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '@/types'

defineOptions({ name: 'LoginPage' })

const { t } = useI18n()

useHead({ title: t('Login') })

defineProps<{
    status?: string | null
    canResetPassword?: boolean
}>()

const form = useForm({
    fields: {
        email: 'admin@example.com',
        password: 'admin',
        remember: false,
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
