<template layout="guest">
    <div>
        <v-card-title>{{ t('Register') }}</v-card-title>
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
                        <v-text-field
                            v-model="form.fields.name"
                            :error-messages="form.errors.name"
                            :label="t('Name')"
                            prepend-inner-icon="mdi-account"
                            type="text"
                            :rules="[isRequired]"
                        />
                    </v-col>
                    <v-col cols="12">
                        <field-email
                            v-model="form.fields.email"
                            :errors="form.errors.email"
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
                            :text="t('Register')"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-card-text class="text-center mt-4">
            <text-link
                :href="route('login')"
                :label="t('Already registered?')"
            />
        </v-card-text>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '@/types'

defineOptions({ name: 'RegisterPage' })

const { t } = useI18n()
const { isRequired } = useValidation()

useHead({ title: t('Register') })

defineProps<{
    status?: null | string
}>()

const form = useForm({
    fields: {
        email: '',
        name: '',
        password: '',
        password_confirmation: '',
    },
})

const formRef = ref<null | VFormRef>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
