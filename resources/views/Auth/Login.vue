<script lang="ts" setup>
import type { VFormRef } from '~/resources/types'

defineOptions({ name: 'LoginPage' })

useHead({ title: 'Login' })

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
<template layout="guest">
    <div>
        <v-card-title>Login</v-card-title>
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
                        <email-field
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
                                label="Forgot password?"
                                rel="noopener noreferrer"
                                target="_blank"
                            />
                        </div>
                        <password-field
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="current-password"
                        />
                    </v-col>
                    <v-col class="pa-0" cols="12">
                        <v-checkbox
                            v-model="form.fields.remember"
                            label="Remember me"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                        >
                            Login
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-card-text class="text-center mt-4">
            <text-link :href="route('register')" label="No account yet?" />
        </v-card-text>
    </div>
</template>
