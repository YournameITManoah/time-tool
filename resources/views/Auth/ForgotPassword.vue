<template layout="guest">
    <div>
        <v-card-title>{{ t('Forgot Password') }}</v-card-title>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <v-container>
                <v-row>
                    <v-col class="py-0" cols="12">
                        <div class="text-subtitle-2 text-medium-emphasis">
                            {{ t('messages.forgot_password_text') }}
                        </div>
                    </v-col>
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
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                            :text="t('Email Password Reset Link')"
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

useHead({ title: t('Forgot Password') })

defineProps<{
    status?: string | null
}>()

const form = useForm({
    fields: {
        email: '',
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
