<script lang="ts" setup>
import type { VFormRef } from '~/resources/types'

defineOptions({ name: 'RegisterPage' })

useHead({ title: 'Register' })

defineProps<{
    status?: string | null
}>()

const form = useForm({
    fields: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
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
        <v-card-title>Register</v-card-title>
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
                            label="Name"
                            prepend-inner-icon="mdi-account"
                            type="text"
                            :rules="[isRequired]"
                        />
                    </v-col>
                    <v-col cols="12">
                        <email-field
                            v-model="form.fields.email"
                            :errors="form.errors.email"
                        />
                    </v-col>
                    <v-col cols="12">
                        <password-field
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="new-password"
                        />
                    </v-col>
                    <v-col cols="12">
                        <password-field
                            v-model="form.fields.password_confirmation"
                            :errors="form.errors.password_confirmation"
                            hint="new-password"
                            label="Password Confirmation"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                        >
                            Register
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-card-text class="text-center mt-4">
            <router-link
                :href="route('login')"
                class="text-primary text-decoration-none"
            >
                Already registered?
            </router-link>
        </v-card-text>
    </div>
</template>
